<?php
  class PedidoAlteracaoCategoria {
    
    public function __construct () {
    }

    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['av']))    {      $avaliadas = addslashes($_GET['av']);       } else {      $avaliadas = 0;     }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $avaliadas, false);
          $this->listarItens($secao, $subsecao, $item, $ativas, $avaliadas);
        break;

        case "avaliar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&av=".$avaliadas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $avaliadas, false);
          $this->listarItens($secao, $subsecao, $item, $ativas, $avaliadas);
        break;        
        
        case "alt_status":
          $this->alterarStatusItem($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $avaliadas, false);
          $this->listarItens($secao, $subsecao, $item, $ativas, $avaliadas);
        break;
      }
    }
   
    private function listarAcoes($secao, $subsecao, $item, $ativas, $avaliadas, $voltar) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      
      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1&av=".$avaliadas;                 if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }              $opcao['descricao']= "Ativos";                                        $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0&av=".$avaliadas;                 if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }              $opcao['descricao']= "Inativos";                                      $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2&av=".$avaliadas;                 if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }              $opcao['descricao']= "Ativos/Inativos";                               $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }

      $opcoes_2= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&av=1";                 if($avaliadas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }              $opcao['descricao']= "Avaliados";                                     $opcoes_2[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&av=0";                 if($avaliadas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }              $opcao['descricao']= "Pendentes";                                     $opcoes_2[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&av=2";                 if($avaliadas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }              $opcao['descricao']= "Avaliados/Pendentes";                           $opcoes_2[]= $opcao;
      foreach ($opcoes_2 as $op) {        $nome = 'comandos_filtros_2_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "  <select name=\"comandos_filtros_2\" id=\"comandos_filtros_2\" class=\"fontComandosFiltros\" onChange=\"navegar(2);\" alt=\"Filtro de situação\" title=\"Filtro de situação\">\n";
      foreach ($opcoes_2 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }
    
    private function listarItens($secao, $subsecao, $item, $ativas, $avaliadas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $itens = $this->selectPedidosAlteracaoCategoriaUsuarios($ativas, $avaliadas);

      $mensagem = "Pedidos de alteração de categoria de usuários ";

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" rowspan=\"2\">Usuário</td>\n";
      echo "      <td class=\"celConteudo\" colspan=\"2\">Categoria</td>\n";
      echo "      <td class=\"celConteudo\" rowspan=\"2\">Atendido</td>\n";
      echo "      <td class=\"celConteudo\" rowspan=\"2\">Ações</td>\n";
      echo "    </tr>\n";      
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Atual</td>\n";
      echo "      <td class=\"celConteudo\">Pretendida</td>\n";
      echo "    </tr>\n";
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_usuario']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_categoria_usuario_atual']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_categoria_usuario_solicitada']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        if ($it['eh_atendido'] == '1') {
          echo "        Sim\n";
        } else {
          echo "        Não\n";
        }
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalhar($it['cd_pedido_alteracao_categoria']);
        echo "          </span>\n";
        echo "        </a>\n";
        if ($it['eh_avaliado'] == '0') {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&av=".$avaliadas."&cd=".$it['cd_pedido_alteracao_categoria']."&acao=avaliar\"><img src=\"icones/avaliar.png\" alt=\"Avaliar\" title=\"Avaliar\" border=\"0\"></a>\n";
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&av=".$avaliadas."&cd=".$it['cd_pedido_alteracao_categoria']."&acao=alt_status\">";
          if ($it['eh_ativo'] == 1) {
            echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
          } else {
            echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
          }
        }
        echo "      </td>\n";
        echo "    </tr>\n";              
      }
      echo "  </table>\n";
      echo "<br /><br />\n";       
    }
                  
    private function detalhar($cd_pedido_alteracao_categoria) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosPedidoAlteracaoCategoria($cd_pedido_alteracao_categoria);
      
      $retorno = "";
      if ($dados['cd_usuario_avaliacao'] != "") {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_avaliacao']);
        $retorno.= "Avaliada por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data ".$dh->imprimirData($dados['dt_avaliacao_alteracao_categoria'])."\n";
      } else {
        $retorno.= "Nenhuma Ação";
      }
      return $retorno;
    }
    
    private function montarFormularioEdicao($link, $cd_pedido_alteracao_categoria) {
      $dados= $this->selectDadosPedidoAlteracaoCategoria($cd_pedido_alteracao_categoria);

      $cd_usuario = $dados['cd_usuario'];
      $cd_categoria_usuario_atual = $dados['cd_categoria_usuario_atual'];
      $cd_categoria_usuario_solicitada = $dados['cd_categoria_usuario_solicitada'];
      $dt_pedido_alteracao_categoria = $dados['dt_pedido_alteracao_categoria'];
      $ds_justificativa_pedido = $dados['ds_justificativa_pedido'];
      $eh_avaliado = $dados['eh_avaliado'];
      $eh_atendido = $dados['eh_atendido'];
      $ds_justificativa_avaliacao = $dados['ds_justificativa_avaliacao'];
      $cd_usuario_avaliacao = $dados['cd_usuario_avaliacao'];
      $dt_avaliacao_alteracao_categoria = $dados['dt_avaliacao_alteracao_categoria'];
      $eh_ativo = $dados['eh_ativo'];

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Avaliar Pedido de alteração de categoria de usuário</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_pedido_alteracao_categoria, $cd_usuario, $cd_categoria_usuario_atual, $cd_categoria_usuario_solicitada, $dt_pedido_alteracao_categoria, $ds_justificativa_pedido, $eh_avaliado, $eh_atendido, $ds_justificativa_avaliacao, $cd_usuario_avaliacao, $dt_avaliacao_alteracao_categoria, $eh_ativo);
    }    

    private function imprimeFormularioCadastro($link, $cd_pedido_alteracao_categoria, $cd_usuario, $cd_categoria_usuario_atual, $cd_categoria_usuario_solicitada, $dt_pedido_alteracao_categoria, $ds_justificativa_pedido, $eh_avaliado, $eh_atendido, $ds_justificativa_avaliacao, $cd_usuario_avaliacao, $dt_avaliacao_alteracao_categoria, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat = new CategoriaUsuario();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_pedido_alteracao_categoria', $cd_pedido_alteracao_categoria);
      $util->campoHidden('cd_usuario', $cd_usuario);
      $util->campoHidden('cd_categoria_usuario_atual', $cd_categoria_usuario_atual);
      $util->campoHidden('cd_categoria_usuario_solicitada', $cd_categoria_usuario_solicitada);
      $util->campoHidden('dt_pedido_alteracao_categoria', $dt_pedido_alteracao_categoria);
      $util->campoHidden('ds_justificativa_pedido', $ds_justificativa_pedido);
      $util->campoHidden('eh_avaliado', '1');
      $util->campoHidden('cd_usuario_avaliacao', $_SESSION['life_codigo']);
      $util->campoHidden('dt_avaliacao_alteracao_categoria', date('Y-m-d'));
      $util->campoHidden('eh_ativo', $eh_ativo);

      $dados = $pes->selectDadosPessoaUsuario($cd_usuario);
      $usuario = $usu->selectDadosUsuario($cd_usuario);
      $categoria_atual = $cat->selectDadosCategoriaUsuario($cd_categoria_usuario_atual);
      $categoria_pretendida = $cat->selectDadosCategoriaUsuario($cd_categoria_usuario_solicitada);

      $util->linhaDuasColunasComentario('Usuário ', $dados['nm_pessoa']);
      $util->linhaDuasColunasComentario('Categoria Atual ', $categoria_atual['nm_categoria_usuario']);
      $util->linhaDuasColunasComentario('Categoria Pretendida ', $categoria_pretendida['nm_categoria_usuario']);
      $util->linhaDuasColunasComentario('Data do Pedido ', $dh->imprimirData($dt_pedido_alteracao_categoria));
      $util->linhaComentario('<hr />');
      $util->linhaComentario('Justificativa <br />'.nl2br($ds_justificativa_pedido));
      $util->linhaComentario('<hr />');
      $util->linhaTexto(0, 'Avaliação ', 'ds_justificativa_avaliacao', $ds_justificativa_avaliacao, '10', '100');
      $util->linhaComentario('<hr />');
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('Atender o pedido ', 'eh_atendido', $eh_atendido, $opcoes, '100');

      $util->linhaBotao('Salvar', "document.getElementById('cadastro').submit();");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'ds_justificativa_avaliacao');
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat = new CategoriaUsuario();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();
      require_once 'conteudos/email.php';                                       $email = new Email();

      $cd_pedido_alteracao_categoria = addslashes($_POST['cd_pedido_alteracao_categoria']);
      $cd_usuario = addslashes($_POST['cd_usuario']);
      $cd_categoria_usuario_atual = addslashes($_POST['cd_categoria_usuario_atual']);
      $cd_categoria_usuario_solicitada = addslashes($_POST['cd_categoria_usuario_solicitada']);
      $dt_pedido_alteracao_categoria = addslashes($_POST['dt_pedido_alteracao_categoria']);
      $ds_justificativa_pedido = addslashes($_POST['ds_justificativa_pedido']);
      $eh_avaliado = addslashes($_POST['eh_avaliado']);
      $eh_atendido = addslashes($_POST['eh_atendido']);
      $ds_justificativa_avaliacao = $util->limparVariavel($_POST['ds_justificativa_avaliacao']);
      $cd_usuario_avaliacao = addslashes($_POST['cd_usuario_avaliacao']);
      $dt_avaliacao_alteracao_categoria = addslashes($_POST['dt_avaliacao_alteracao_categoria']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      $dados = $pes->selectDadosPessoaUsuario($cd_usuario);
      $usuario = $usu->selectDadosUsuario($cd_usuario);
      $categoria_atual = $cat->selectDadosCategoriaUsuario($cd_categoria_usuario_atual);
      $categoria_pretendida = $cat->selectDadosCategoriaUsuario($cd_categoria_usuario_solicitada);

      if ($this->alterarPedidoAlteracaoCategoria($cd_pedido_alteracao_categoria, $cd_usuario, $cd_categoria_usuario_atual, $cd_categoria_usuario_solicitada, $dt_pedido_alteracao_categoria, $ds_justificativa_pedido, $eh_avaliado, $eh_atendido, $ds_justificativa_avaliacao, $cd_usuario_avaliacao, $dt_avaliacao_alteracao_categoria, $eh_ativo)) {
        if ($eh_atendido == '1') {
          $editou_usuario = $usu->ajustarCategoriaUsuario($cd_usuario, $cd_categoria_usuario_solicitada);
          $acao = ' atendemos ';
        } else {
          $editou_usuario = true;
          $acao = ' negamos ';
        }
        if ($editou_usuario) {
          $texto      = "           Prezado(a) ".$dados['nm_pessoa']."<br /><br />".
                        "           Recebemos seu pedido de alteração de sua categoria de usuário. <br />".
                        "           Você justificou<br />".$ds_justificativa_pedido."<br /><br />".
                        "           Avaliando seu pedido, consideramos<br />".$ds_justificativa_avaliacao."<br />".
                        "           Dessa forma, ".$acao." seu pedido.<br />".
                        "           <br />".
                        "           Obrigado, <br /><br />";
          if ($email->notificarAvaliacaoPedidoAlteracaoCategoria($dados['cd_contato'], $texto)) {
            echo "<p class=\"fontConteudoSucesso\">Pedido de alteração de categoria de usuário avaliado com sucesso!</p>\n";
          } else {
            echo "<p class=\"fontConteudoSucesso\">Pedido de alteração de categoria de usuário avaliado com sucesso!</p>\n";
            echo "<p class=\"fontConteudoAlerta\">Problemas ao notificar usuário!</p>\n";
          }
        } else {        
          echo "<p class=\"fontConteudoAlerta\">Problemas ao avaliar pedido de alteração de categoria de usuário!</p>\n";
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao avaliar pedido de alteração de categoria de usuário!</p>\n";
      }
    } 
    
    private function alterarStatusItem($cd_pedido_alteracao_categoria) {
      $dados= $this->selectDadosPedidoAlteracaoCategoria($cd_pedido_alteracao_categoria);

      $cd_usuario = $dados['cd_usuario'];
      $cd_categoria_usuario_atual = $dados['cd_categoria_usuario_atual'];
      $cd_categoria_usuario_solicitada = $dados['cd_categoria_usuario_solicitada'];
      $dt_pedido_alteracao_categoria = $dados['dt_pedido_alteracao_categoria'];
      $ds_justificativa_pedido = $dados['ds_justificativa_pedido'];
      $eh_avaliado = $dados['eh_avaliado'];
      $eh_atendido = $dados['eh_atendido'];
      $ds_justificativa_avaliacao = $dados['ds_justificativa_avaliacao'];
      $cd_usuario_avaliacao = $dados['cd_usuario_avaliacao'];
      $dt_avaliacao_alteracao_categoria = $dados['dt_avaliacao_alteracao_categoria'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo= 0;      } else {        $eh_ativo= 1;      }
      if ($this->alterarPedidoAlteracaoCategoria($cd_pedido_alteracao_categoria, $cd_usuario, $cd_categoria_usuario_atual, $cd_categoria_usuario_solicitada, $dt_pedido_alteracao_categoria, $ds_justificativa_pedido, $eh_avaliado, $eh_atendido, $ds_justificativa_avaliacao, $cd_usuario_avaliacao, $dt_avaliacao_alteracao_categoria, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do pedido de alteração de categoria de usuário alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do pedido de alteração de categoria de usuário!</p>\n";
      }
    }

//***************ALTERA CATEGORIA***********************************************
    public function controleExibicaoAlterarCategoria($secao, $subsecao, $item, $pagina, $lista_paginas) {

      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario = $_SESSION['life_codigo'];

        $link = '';
        $barra = "";
        foreach ($lista_paginas as $lp) {
          $link .= $barra.$lp;
          $barra = "/";
        }

        if (isset($lista_paginas[2])) {
          if (isset($lista_paginas[3])) {
            if (isset($_SESSION['life_edicao'])) {
              echo "<h1>Categoria de usuário</h1>\n";
              $this->salvarPedidoAlteracaoCategoriaUsuario();
              unset($_SESSION['life_edicao']);
            }
            return true;
          } else {
            echo "<h1>Categoria de usuário</h1>\n";
            if (isset($_POST['trocar'])) {
              $this->retornaFormularioAlteracaoCategoriaUsuario($link, $cd_usuario);
            } else {
              $this->retornaDadosCategoriaUsuario($link, $cd_usuario);
            }
            return false;
          }
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao identificar o usuário!</p>\n";
          return true;
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Nenhum usuário logado!</p>\n";
        return true;
      }
    }

    public function retornaDadosCategoriaUsuario($link, $cd_usuario) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat = new CategoriaUsuario();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();

      $dados = $pes->selectDadosPessoaUsuario($cd_usuario);
      $usuario = $usu->selectDadosUsuario($cd_usuario);
      $categoria = $cat->selectDadosCategoriaUsuario($usuario['cd_categoria_usuario']);

      echo "<p class=\"fontConteudo\">Usuário ".$dados['nm_pessoa']."</p>\n";
      echo "<p class=\"fontConteudo\">Categoria ".$categoria['nm_categoria_usuario']."</p>\n";
      echo "<p class=\"fontConteudo\">".nl2br($categoria['ds_categoria_usuario'])."</p>\n";

      if ($conf->ehPossivelSolicitarAlteracaoCategoriaUsuario()) {
        echo "  <p class=\"fontConteudo\">".nl2br($conf->retornaExplicacaoAlteracaoCategoriaUsuario())."</p>\n";
        echo "  <br />\n";
        echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');
        $util->campoHidden('trocar', '1');
        $util->linhaBotao('Solicitar troca de categoria de usuário', "document.getElementById('cadastro').submit();");
        echo "    </table>\n";
        echo "  </form>\n";
      }
    }

    public function retornaFormularioAlteracaoCategoriaUsuario($link, $cd_usuario) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat = new CategoriaUsuario();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();

      $_SESSION['life_edicao'] = '1';

      $dados = $pes->selectDadosPessoaUsuario($cd_usuario);
      $usuario = $usu->selectDadosUsuario($cd_usuario);
      $categoria = $cat->selectDadosCategoriaUsuario($usuario['cd_categoria_usuario']);

      if ($conf->ehPossivelSolicitarAlteracaoCategoriaUsuario()) {
        echo "<p class=\"fontConteudo\">Usuário ".$dados['nm_pessoa']."</p>\n";
        echo "<p class=\"fontConteudo\">Categoria ".$categoria['nm_categoria_usuario']."</p>\n";
        echo "<p class=\"fontConteudo\">".nl2br($categoria['ds_categoria_usuario'])."</p>\n";
        echo "<hr>\n";

        if ($dados['eh_ficha_atualizada'] == '1') {
          echo "<p class=\"fontConteudo\">Solicito alteração da categoria de ".$categoria['nm_categoria_usuario'].", para</p>\n";

          include "js/js_cadastro_pedido_alteracao_categoria.js";
          echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."/registrar\" onSubmit=\"return valida(this);\">\n";

          echo "  <table class=\"tabConteudo\">\n";
          $util->campoHidden('eh_form', '1');
          $util->campoHidden('cd_usuario', $cd_usuario);
          $util->campoHidden('cd_categoria_usuario_atual', $usuario['cd_categoria_usuario']);
          $style = "linhaOn";
          echo "    <tr class=\"".$style."\">\n";
          echo "      <td class=\"celConteudo\">&nbsp;</td>\n";
          echo "      <td class=\"celConteudo\">Categoria</td>\n";
          echo "      <td class=\"celConteudo\">Descrição</td>\n";
          echo "    </tr>\n";
          $categorias = $cat->selectCategoriasUsuarios('1', '2', '1');
          foreach ($categorias as $c) {
            if ($c['cd_categoria_usuario'] != $categoria['cd_categoria_usuario']) {
              $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
              echo "    <tr class=\"".$style."\">\n";
              echo "      <td class=\"celConteudo\">\n";
              echo "        <input type=\"radio\" name=\"cd_categoria_usuario_solicitada\" id=\"cd_categoria_usuario_solicitada\" value=\"".$c['cd_categoria_usuario']."\" class=\"fontConteudo\" />\n";
              echo "      </td>\n";
              echo "      <td class=\"celConteudo\">".$c['nm_categoria_usuario']."</td>\n";
              echo "      <td class=\"celConteudo\">".nl2br($c['ds_categoria_usuario'])."</td>\n";
              echo "    </tr>\n";
            }
          }
          echo "    <tr>\n";
          echo "  	  <td class=\"celConteudo\" colspan=\"3\"><br />Justificativa para o pedido de alteração de categoria de usuário *<br />\n";
          echo "        <textarea name=\"ds_justificativa_pedido\" id=\"ds_justificativa_pedido\" rows=\"5\" class=\"fontConteudoCampoTextoHint\" style=\"width:100%;\"></textarea>\n";
          echo "      </td>\n";
          echo "    </tr>\n";
          echo "    <tr>\n";
          echo "      <td class=\"celConteudo\" style=\"text-align:center;\" colspan=\"3\">\n";
          echo "  	    <input type=\"button\" class=\"celConteudoBotao\" value=\"Registre a solicitação\" tabindex=\"1\" onClick=\"valida(cadastro);\">\n";
          echo "      </td>\n";
          echo "  	</tr>\n";
          echo "  </table>\n";
        } else {
          echo "  <h3>Importante</h3>\n";
          echo "  <p class=\"fontConteudo\">Não é possível solicitar a alteração de categoria de usuário neste momento, pois sua ficha cadastral está desatualizada ou não foi preenchida completamente.</p>\n";
          echo "  <p class=\"fontConteudo\">Para atualizar sua ficha cadastral, <a href=\"".$_SESSION['life_link_completo']."index.php?secao=17&sub=20&it=32\" class=\"fontLink\">clique aqui</a>.</p>\n";
        }
      } else {
        echo "<p class=\"fontConteudo\">Usuário ".$dados['nm_pessoa']."</p>\n";
        echo "<p class=\"fontConteudo\">Categoria ".$categoria['nm_categoria_usuario']."</p>\n";
        echo "<p class=\"fontConteudo\">".nl2br($categoria['ds_categoria_usuario'])."</p>\n";
      }
    }

    public function salvarPedidoAlteracaoCategoriaUsuario() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_usuario = addslashes($_POST['cd_usuario']);
      $cd_categoria_usuario_atual = addslashes($_POST['cd_categoria_usuario_atual']);
      $cd_categoria_usuario_solicitada = addslashes($_POST['cd_categoria_usuario_solicitada']);
      $dt_pedido_alteracao_categoria = date('Y-m-d');
      $ds_justificativa_pedido = $util->limparVariavel($_POST['ds_justificativa_pedido']);
      $eh_avaliado = '0';
      $eh_atendido = '0';
      $eh_ativo = '1';

      if ($this->inserePedidoAlteracaoCategoria($cd_usuario, $cd_categoria_usuario_atual, $cd_categoria_usuario_solicitada, $dt_pedido_alteracao_categoria, $ds_justificativa_pedido, $eh_avaliado, $eh_atendido, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Seu pedido de alteração de categoria de usuário foi registrado com sucesso e está em análise!</p>\n";
        echo "<p class=\"fontConteudoSucesso\">Você será notificado por e-mail.</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao registrar o seu pedido de alteração de categoria de usuário!</p>\n";
      }
    }


//**************BANCO DE DADOS**************************************************
    public function selectPedidosAlteracaoCategoriaUsuarios($eh_ativo, $eh_avaliado) {
      $sql  = "SELECT pac.*, u.nm_usuario, c1.nm_categoria_usuario nm_categoria_usuario_atual, c2.nm_categoria_usuario nm_categoria_usuario_solicitada  ".
              "FROM life_pedidos_alteracao_categoria pac, life_usuarios u, life_categorias_usuarios c1, life_categorias_usuarios c2 ".
              "WHERE pac.cd_usuario = u.cd_usuario ".
              "AND pac.cd_categoria_usuario_atual = c1.cd_categoria_usuario ".
              "AND pac.cd_categoria_usuario_solicitada = c2.cd_categoria_usuario ";
      if ($eh_ativo != 2) {
        $sql.= "AND pac.eh_ativo = '$eh_ativo' ";
      }
      if ($eh_avaliado != 2) {
        $sql.= "AND pac.eh_avaliado = '$eh_avaliado' ";
      }
      $sql.= "ORDER BY pac.dt_pedido_alteracao_categoria";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PEDIDOS ALTERAÇÃO CATEGORIAS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectDadosPedidoAlteracaoCategoria($cd_pedido_alteracao_categoria) {
      $sql  = "SELECT * ".
              "FROM life_pedidos_alteracao_categoria ".
              "WHERE cd_pedido_alteracao_categoria = '$cd_pedido_alteracao_categoria' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PEDIDOS ALTERAÇÃO CATEGORIAS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function inserePedidoAlteracaoCategoria($cd_usuario, $cd_categoria_usuario_atual, $cd_categoria_usuario_solicitada, $dt_pedido_alteracao_categoria, $ds_justificativa_pedido, $eh_avaliado, $eh_atendido, $eh_ativo) {
      $sql = "INSERT INTO life_pedidos_alteracao_categoria ".
             "(cd_usuario, cd_categoria_usuario_atual, cd_categoria_usuario_solicitada, dt_pedido_alteracao_categoria, ds_justificativa_pedido, eh_avaliado, eh_atendido, eh_ativo) ".
             "VALUES ".
             "(\"$cd_usuario\", \"$cd_categoria_usuario_atual\", \"$cd_categoria_usuario_solicitada\", \"$dt_pedido_alteracao_categoria\", \"$ds_justificativa_pedido\", \"$eh_avaliado\", \"$eh_atendido\", \"$eh_ativo\")";
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PEDIDOS ALTERAÇÃO CATEGORIAS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alterarPedidoAlteracaoCategoria($cd_pedido_alteracao_categoria, $cd_usuario, $cd_categoria_usuario_atual, $cd_categoria_usuario_solicitada, $dt_pedido_alteracao_categoria, $ds_justificativa_pedido, $eh_avaliado, $eh_atendido, $ds_justificativa_avaliacao, $cd_usuario_avaliacao, $dt_avaliacao_alteracao_categoria, $eh_ativo) {
      $sql = "UPDATE life_pedidos_alteracao_categoria SET ".
             "cd_usuario = \"$cd_usuario\", ".
             "cd_categoria_usuario_atual = \"$cd_categoria_usuario_atual\", ".
             "cd_categoria_usuario_solicitada = \"$cd_categoria_usuario_solicitada\", ".
             "dt_pedido_alteracao_categoria = \"$dt_pedido_alteracao_categoria\", ".
             "ds_justificativa_pedido = \"$ds_justificativa_pedido\", ".
             "eh_avaliado = \"$eh_avaliado\", ".
             "eh_atendido = \"$eh_atendido\", ".
             "ds_justificativa_avaliacao = \"$ds_justificativa_avaliacao\", ".
             "cd_usuario_avaliacao = \"$cd_usuario_avaliacao\", ".
             "dt_avaliacao_alteracao_categoria = \"$dt_avaliacao_alteracao_categoria\", ".
             "eh_ativo = \"$eh_ativo\" ".
             "WHERE cd_pedido_alteracao_categoria= '$cd_pedido_alteracao_categoria' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pedidos_alteracao_categoria');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PEDIDOS ALTERAÇÃO CATEGORIAS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  }
?>