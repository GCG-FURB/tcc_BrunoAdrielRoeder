<?php
  class ObjetoAprendizagemComentario {
    
    public function __construct () {
    }

    public function controleExibicaoResponsavel($secao, $subsecao, $item) {
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }

      if (isset($_GET['cdo']))   {
        $cd_oa = addslashes($_GET['cdo']);

        $_SESSION['life_c_eh_proprietario'] = '1';

        if (isset($_SESSION['life_permissoes'])) {
          $permissoes_usuario= $_SESSION['life_permissoes'];
        } else {
          $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        }

        if ($cd_oa > 0) {
          require_once 'conteudos/pessoas.php';                                 $pes = new Pessoa();

          $dados = $oa->selectDadosObjetoAprendizagem($cd_oa);
          $pessoa = $pes->selectDadosPessoaUsuario($dados['cd_usuario_proprietario']);

          if ($permissoes_usuario[21] == '0') {
            $cd_usuario = $_SESSION['life_codigo'];
            if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
              echo "<p class=\"fontConteudoAlerta\">Voc� n�o possui permiss�o para editar este objeto de aprendizagem!</p>\n";
              return false;
            }
          }


          switch ($acao) {
            case "":
              echo "    <p class=\"fontComandosFiltros\">\n";
              echo "      <a href=\"index.php?secao=".$secao."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar para o cadastro de objetos de aprendizagem\" title=\"Voltar para o cadastro de objetos de aprendizagem\" border=\"0\"></a>\n";
              echo "    </p>\n";
              echo "<h2>objeto de aprendizagem ".$dados['nm_objeto_aprendizagem']."</h2>\n";
              echo "<hr>\n";
              $this->listarItensResponsavel($secao, $subsecao, $item, $cd_oa);
            break;

            case "avaliar":
              $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdo=".$cd_oa;
              $this->montarFormularioAnaliseComentario($link, $cd_oa, $codigo);
            break;

            case "avaliar_comentario":
              echo "    <p class=\"fontComandosFiltros\">\n";
              echo "      <a href=\"index.php?secao=".$secao."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar para o cadastro de objetos de aprendizagem\" title=\"Voltar para o cadastro de objetos de aprendizagem\" border=\"0\"></a>\n";
              echo "    </p>\n";
              echo "<h2>objeto de aprendizagem ".$dados['nm_objeto_aprendizagem']."</h2>\n";
              echo "<hr>\n";              if (isset($_SESSION['life_edicao'])) {
                unset($_SESSION['life_edicao']);
                $this->salvarAnaliseComentario();
              }
              $this->listarItensResponsavel($secao, $subsecao, $item, $cd_oa);
            break;

          }
        } else {
          echo "<p class=\"fontConteudoAlerta\">Voc� n�o selecionou nenhum objeto de aprendizagem!</p>\n";

          $oa->controleExibicao($secao, $subsecao, $item);
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Voc� n�o selecionou nenhum objeto de aprendizagem!</p>\n";

        $oa->controleExibicao($secao, $subsecao, $item);
      }
    }

    public function listarItensResponsavel($secao, $subsecao, $item, $cd_objeto_aprendizagem) {
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $itens = $this->selectComentariosObjetosAprendizagem($cd_objeto_aprendizagem, '1', '2');

      $mensagem = "Coment�rios sobre o objeto de aprendizagem";
      echo "<h2>".$mensagem."</h2>\n";

      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn";
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Respons�vel</td>\n";
      echo "      <td class=\"celConteudo\" rowspan=\"2\">Coment�rio</td>\n";
      echo "      <td class=\"celConteudo\" rowspan=\"2\" style=\"width:7%;\">A��es</td>\n";
      echo "    </tr>\n";
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Data</td>\n";
      echo "    </tr>\n";

      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\">".$it['nm_responsavel_comentario']."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"2\">".nl2br($it['ds_comentario'])."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"2\">\n";
        if ($it['cd_usuario_avaliacao'] == '') {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdo=".$cd_objeto_aprendizagem."&cd=".$it['cd_objeto_aprendizagem_comentario']."&acao=avaliar\">";
          if ($it['ds_justificativa'] == '') {
            echo "<img src=\"icones/avaliar_comentarios.png\" alt=\"Avaliar coment�rio sobre o objeto de aprendizagem\" title=\"Avaliar coment�rio sobre o objeto de aprendizagem\" border=\"0\"></a>\n";
          } else {
            echo "<img src=\"icones/reavaliar_comentarios.png\" alt=\"Reavaliar coment�rio sobre o objeto de aprendizagem\" title=\"Reavaliar cooment�rio sobre o objeto de aprendizagem\" border=\"0\"></a>\n";
          }
        } else {
          echo "        <img src=\"icones/avaliar_comentarios_of.png\" alt=\"Coment�rio sobre o objeto de aprendizagem possui avalia��o final\" title=\"Coment�rio sobre o objeto de aprendizagem possui avalia��o final\" border=\"0\"></a>\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\">".$dh->imprimirData($it['dt_comentario'])."</td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";
    }

    private function montarFormularioAnaliseComentario($link, $cd_oa, $cd_comentario) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      }

      $comentario = $this->selectDadosComentario($cd_comentario);
      $dados = $oa->selectDadosObjetoAprendizagem($comentario['cd_objeto_aprendizagem']);

      if ($permissoes_usuario[21] == '0') {
        $cd_usuario = $_SESSION['life_codigo'];
        if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
          echo "<p class=\"fontConteudoAlerta\">Voc� n�o possui permiss�o para editar este objeto de aprendizagem!</p>\n";
          return false;
        }
      }

      echo "<h2>Avalia��o/resposta � coment�rio de objeto de aprendizagem</h2>\n";

      echo "    <p class=\"fontComandosFiltros\">\n";
      echo "      <a href=\"".$link."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "    </p>\n";


      include "js/js_avaliar_comentario_objeto_aprendizagem.js";
      echo "  <form method=\"POST\" name=\"cadastro_o_a\" id=\"cadastro\" action=\"".$link."&acao=avaliar_comentario\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_objeto_aprendizagem', $comentario['cd_objeto_aprendizagem']);
      $util->campoHidden('cd_objeto_aprendizagem_comentario', $comentario['cd_objeto_aprendizagem_comentario']);
      $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $comentario['cd_pessoa_responsavel_objeto_aprendizagem']);
      $util->campoHidden('cd_pessoa_responsavel_comentario', $comentario['cd_pessoa_responsavel_comentario']);
      $util->campoHidden('ds_comentario', $comentario['ds_comentario']);
      $util->campoHidden('dt_comentario', $comentario['dt_comentario']);
      $util->campoHidden('dt_justificativa', date('Y-m-d'));

      $util->linhaDuasColunasComentario('Objeto de aprendizagem ', $dados['nm_objeto_aprendizagem']);
      $util->linhaComentario('<hr>');
      $util->linhaDuasColunasComentario('Data do coment�rio ', $dh->imprimirData($comentario['dt_comentario']));
      $util->linhaDuasColunasComentario('Coment�rio ', nl2br($comentario['ds_comentario']));
      $util->linhaComentario('<hr>');
      $util->linhaTexto('1', 'Justificativa ', 'ds_justificativa', '', '10', '100');

      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'N�o';      $opcoes[]= $opcao;
      $util->linhaSeletor('� Liberado ', 'eh_liberado', $comentario['eh_liberado'], $opcoes, '100');

      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigat�rios</p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'ds_justificativa');
      $_SESSION['life_edicao'] = '1';
    }

    private function salvarAnaliseComentario() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_objeto_aprendizagem = addslashes($_POST['cd_objeto_aprendizagem']);
      $cd_objeto_aprendizagem_comentario = addslashes($_POST['cd_objeto_aprendizagem_comentario']);
      $cd_pessoa_responsavel_objeto_aprendizagem = addslashes($_POST['cd_pessoa_responsavel_objeto_aprendizagem']);
      $cd_pessoa_responsavel_comentario = addslashes($_POST['cd_pessoa_responsavel_comentario']);
      $ds_comentario = $util->limparVariavel($_POST['ds_comentario']);
      $ds_justificativa = $util->limparVariavel($_POST['ds_justificativa']);
      $dt_comentario = addslashes($_POST['dt_comentario']);
      $dt_justificativa = addslashes($_POST['dt_justificativa']);
      $eh_liberado = addslashes($_POST['eh_liberado']);
      $eh_ativo = '1';

      if ($this->editaComentario($cd_objeto_aprendizagem_comentario, $cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_responsavel_comentario, $ds_comentario, $dt_comentario, $eh_liberado, $ds_justificativa, $dt_justificativa, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Sua an�lise do coment�rio foi registrada em nossa base!</p>\n";
        require_once 'conteudos/email.php';                                   $email = new Email();
        require_once 'conteudos/pessoas.php';                                 $pes = new Pessoa();
        require_once 'conteudos/objetos_aprendizagem.php';                    $oa = new ObjetoAprendizagem();

        $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
        $responsavel_comentario = $pes->selectDadosPessoa($cd_pessoa_responsavel_comentario);
        $responsavel_objeto_aprendizagem = $pes->selectDadosPessoa($cd_pessoa_responsavel_objeto_aprendizagem);
        $texto = "Objeto de aprendizagem ".$dados['nm_objeto_aprendizagem']."<br />".
                 "O respons�vel pelo objeto, realizou uma avalia��o do coment�rio.<br />".
                 "O objeto comentado pode ser acessado <a href=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."\">por este link</a>.<br /><br />".
                 "<hr>".
                 "Coment�rio de ".$responsavel_comentario['nm_pessoa']."<br />\"".$ds_comentario."\"<br />".
                 "Avaliado por ".$responsavel_objeto_aprendizagem['nm_pessoa']."<br />Justificativa ".$ds_justificativa."<br />";
        if ($eh_liberado == '1') {
          $texto.= "O coment�rio est� liberado para exibi��o.";
        } else {
          $texto.= "O coment�rio est� bloqueado de exibi��o. <br />Caso se sinta ofendido, voc� pode realizar novo coment�rio ou uma den�ncia contra o objeto de aprendizagem.";
        }

        $email->notificarComentario($responsavel_comentario['cd_contato'], $texto);
        $email->notificarComentario($responsavel_objeto_aprendizagem['cd_contato'], $texto);
      } else {
        echo "<p class=\"fontConteudoAlerta\">Ocorreu um erro ao registrar seu coment�rio. Desculpe!</p>\n";
      }
    }
//*************************ADMINISTRADORES**************************************
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }

      if (isset($_SESSION['life_gerenciar_comentarios'])) {         unset($_SESSION['life_gerenciar_comentarios']);         }

      switch ($acao) {
        case "":
          //$this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;

        case "inativar":
          $this->alterarStatusComentario($codigo);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
        case "liberar":
          $this->alterarSituacaoComentario($codigo);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
      }
    }

    private function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $opcoes_1= array();
      $id = 1;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";     if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                          $opcao['descricao']= "Ativos";                                                               $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";     if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                          $opcao['descricao']= "Inativos";                                                             $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";     if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                          $opcao['descricao']= "Ativos/Inativos";                                                      $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }

      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }


    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $itens = $this->selectComentariosPedentesAvaliacaoAdministradores($ativas, '0');

      $mensagem = "objetos de aprendizagem com coment�rios n�o analisadas";
      echo "<h2>".$mensagem."</h2>\n";

      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn";
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" colspan=\"2\">Respons�veis</td>\n";
      echo "      <td class=\"celConteudo\">Objeto de aprendizagem/Coment�rio</td>\n";
      echo "      <td class=\"celConteudo\" style=\"width:4%;\">A��es</td>\n";
      echo "    </tr>\n";

      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\" colspan=\"2\"> </td>\n";
        echo "      <td class=\"celConteudoDestaque\">".$it['nm_objeto_aprendizagem']."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"5\">\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cd=".$it['cd_objeto_aprendizagem_comentario']."&acao=inativar\"><img src=\"icones/excluir_comentario.png\" alt=\"Excluir coment�rio sobre o objeto de aprendizagem\" title=\"Excluir coment�rio sobre o objeto de aprendizagem\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cd=".$it['cd_objeto_aprendizagem_comentario']."&acao=liberar\"><img src=\"icones/liberar_comentario.png\" alt=\"Liberar coment�rio sobre o objeto de aprendizagem\" title=\"Liberar coment�rio sobre o objeto de aprendizagem\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:12%;\">Pelo coment�rio</td>\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:20%;\">".$it['nm_responsavel_comentario']."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"2\">".nl2br($it['ds_comentario'])."</td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\">Data</td>\n";
        echo "      <td class=\"celConteudoDestaque\">".$dh->imprimirData($it['dt_comentario'])."</td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\">Pelo objeto</td>\n";
        echo "      <td class=\"celConteudoDestaque\">".$it['nm_responsavel_objeto_aprendizagem']."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"2\">".nl2br($it['ds_justificativa'])."</td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\">Data</td>\n";
        echo "      <td class=\"celConteudoDestaque\">".$dh->imprimirData($it['dt_justificativa'])."</td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";
    }

    private function alterarStatusComentario($cd_objeto_aprendizagem_comentario) {
      $dados = $this->selectDadosComentariosObjetosAprendizagem($cd_objeto_aprendizagem_comentario);

      if ($this->alterarStatusComentarioObjetoAprendizagem($cd_objeto_aprendizagem_comentario, '0', $_SESSION['life_codigo'])) {
        echo "<p class=\"fontConteudoSucesso\">O coment�rio foi exclu�do!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">N�o conseguimos excluir o coment�rio. Desculpe!</p>\n";
      }
    }

    private function alterarSituacaoComentario($cd_objeto_aprendizagem_comentario) {
      $dados = $this->selectDadosComentariosObjetosAprendizagem($cd_objeto_aprendizagem_comentario);

      if ($this->alterarSituacaoComentarioObjetoAprendizagem($cd_objeto_aprendizagem_comentario, '1', $_SESSION['life_codigo'])) {
        echo "<p class=\"fontConteudoSucesso\">O coment�rio foi liberado para exibi��o!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">N�o conseguimos liberar o coment�rio para exibi��o. Desculpe!</p>\n";
      }
    }

    public function existeNecessidadeGerenciamentoComentarios() {
      $itens = $this->selectComentariosPedentesAvaliacaoAdministradores('1', '0');

      if (count($itens) > 0) {
        return true;
      } else {
        return false;
      }
    }

    public function temComentario($cd_objeto_aprendizagem) {
      $itens = $this->selectComentariosObjetosAprendizagem($cd_objeto_aprendizagem, '1', '2');

      if (count($itens) > 0) {
        return true;
      } else {
        return false;
      }
    }

//***************EXIBICAO PUBLICA***********************************************
    public function montarFormularioComentario($dados) {
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "  <p class=\"fontComandosFiltros\" style=\"height:15px;	vertical-align: baseline; text-align:left;\">\n";
      echo "    Comente ";
      echo "  </p>\n";

      $sobre_comentario = $conf->retornaExplicacaoProcessoComentario();
      if ($sobre_comentario != '') {
        echo "<p class=\"fontConteudo\">".nl2br($sobre_comentario)."</p>\n";
      }

      $responsavel = $pes->selectDadosPessoaUsuario($dados['cd_usuario_proprietario']);
      $responsavel_comentario = $pes->selectDadosPessoaUsuario($_SESSION['life_codigo']);

      include "js/js_comentario_objeto_aprendizagem.js";
      echo "  <form method=\"POST\" name=\"cadastro_o_a\" id=\"cadastro_comentario\" action=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."/comentario\" onSubmit=\"return valida(this);\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_objeto_aprendizagem', $dados['cd_objeto_aprendizagem']);
      $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $responsavel['cd_pessoa']);
      $util->campoHidden('cd_pessoa_responsavel_comentario', $responsavel_comentario['cd_pessoa']);
      $util->campoHidden('dt_comentario', date('Y-m-d'));
      $util->campoHidden('eh_ativo', '1');

      echo "    <p class=\"fontConteudoObjetosAprendizagemComentarioDenuncia\">\n";
      echo "      <textarea name=\"ds_comentario\" id=\"ds_comentario\" rows=\"2\" class=\"fontConteudoCampoTextoHint\" style=\"width:80%;\" alt=\"Escreva seu coment�rio\" title=\"Escreva seu coment�rio\" placeholder=\"Escreva seu coment�rio\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\"></textarea>\n";
      echo "  	  <input type=\"button\" class=\"botao\" value=\"Publicar\" style=\"margin-top:-42px; height:38px;\" onClick=\"valida_comentario(cadastro_comentario);\">\n";
      echo "    </p>\n";
      echo "  </form>\n";
      $_SESSION['life_comentario'] = '1';
    }

    public function salvarComentario() {
      if (isset($_SESSION['life_comentario'])) {
        unset($_SESSION['life_comentario']);

        require_once 'includes/utilitarios.php';                                $util = new Utilitario();

        $cd_objeto_aprendizagem = addslashes($_POST['cd_objeto_aprendizagem']);
        $cd_pessoa_responsavel_objeto_aprendizagem = addslashes($_POST['cd_pessoa_responsavel_objeto_aprendizagem']);
        $cd_pessoa_responsavel_comentario = addslashes($_POST['cd_pessoa_responsavel_comentario']);
        $ds_comentario = $util->limparVariavel($_POST['ds_comentario']);
        $dt_comentario = addslashes($_POST['dt_comentario']);
        $eh_liberado = '1';
        $eh_ativo = '1';

        if ($this->insereComentario($cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_responsavel_comentario, $ds_comentario, $dt_comentario, $eh_liberado, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Obrigado por comentar o objeto de aprendizagem!</p>\n";
          require_once 'conteudos/email.php';                                   $email = new Email();
          require_once 'conteudos/pessoas.php';                                 $pes = new Pessoa();
          require_once 'conteudos/objetos_aprendizagem.php';                    $oa = new ObjetoAprendizagem();

          $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
          $texto = "Objeto de aprendizagem ".$dados['nm_objeto_aprendizagem']."<br />".
                   "O objeto comentado pode ser acessado <a href=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."\">por este link</a>.<br /><br />".
                   "<hr>".
                   "Coment�rio<br />".
                   $ds_comentario;

          $responsavel_comentario = $pes->selectDadosPessoa($cd_pessoa_responsavel_comentario);
          $email->notificarComentario($responsavel_comentario['cd_contato'], $texto);
          $responsavel_objeto_aprendizagem = $pes->selectDadosPessoa($cd_pessoa_responsavel_objeto_aprendizagem);
          $email->notificarComentario($responsavel_objeto_aprendizagem['cd_contato'], $texto);
        } else {
          echo "<p class=\"fontConteudoAlerta\">Ocorreu um erro ao registrar seu coment�rio. Desculpe!</p>\n";
        }
      }
    }

    public function retornaComentarioObjetoAprendizagem($cd_objeto_aprendizagem, $lk_seo, $acao) {
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $comentarios = $this->selectComentariosObjetosAprendizagem($cd_objeto_aprendizagem, '1', '1');

      if (count($comentarios) > 0) {
        echo "<hr>\n";
        $limite = 5;
        if (count($comentarios) > $limite) {
          echo "    <p class=\"fontComandosFiltros\">\n";
          if ($acao == 'todos') {
            echo "      <a href=\"".$_SESSION['life_link_completo']."objetos/".$lk_seo."/todos\" class=\"fontLinkSelecionado\">Ver todos</a>\n";
            echo "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
            echo "      <a href=\"".$_SESSION['life_link_completo']."objetos/".$lk_seo."/ultimos\" class=\"fontLink\">�ltimos</a>\n";
            $limite = 9999;
          } elseif ($acao == 'ultimos') {
            echo "      <a href=\"".$_SESSION['life_link_completo']."objetos/".$lk_seo."/todos\" class=\"fontLink\">Ver todos</a>\n";
            echo "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
            echo "      <a href=\"".$_SESSION['life_link_completo']."objetos/".$lk_seo."/ultimos\" class=\"fontLinkSelecionado\">�ltimos</a>\n";
            $limite = 5;
          }
          echo "    </p>\n";
        }
        if ($limite > count($comentarios)) {
          $limite = count($comentarios);
        }
        for ($i=0;$i<$limite;$i++) {
          $dados = $comentarios[$i];
          echo "<p class=\"fontComentariosObjetosAprendizagem\">\n";
          echo "  <b>".$dados['nm_responsavel_comentario']."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Em ".$dh->imprimirData($dados['dt_comentario'])."<br />\n";
          echo "  ".nl2br($dados['ds_comentario'])."\n";
          echo "</p>\n";
          if ($dados['ds_justificativa'] != '') {
            echo "<p class=\"fontRespostasComentariosObjetosAprendizagem\">\n";
            echo "  <i>".$dados['nm_responsavel_objeto_aprendizagem']."</i><br />\n";
            echo "  ".nl2br($dados['ds_justificativa'])."\n";
            echo "</p>\n";
          }
        }
      }
    }

//**************BANCO DE DADOS**************************************************    
   public function selectComentariosObjetosAprendizagem($cd_objeto_aprendizagem, $eh_ativo, $eh_liberado) {
      $sql  = "SELECT ".
              "     oad.*, ".
              "     pe1.nm_pessoa nm_responsavel_comentario, pe1.cd_contato cd_contato_responsavel_comentario, ".
              "     pe2.nm_pessoa nm_responsavel_objeto_aprendizagem, pe2.cd_contato cd_contato_responsavel_objeto_aprendizagem ".
              "FROM ".
              "     life_objetos_aprendizagem_comentarios oad, ".
              "     life_pessoas pe1, ".
              "     life_pessoas pe2 ".
              "WHERE oad.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
              "AND oad.cd_pessoa_responsavel_comentario = pe1.cd_pessoa ".
              "AND oad.cd_pessoa_responsavel_objeto_aprendizagem = pe2.cd_pessoa ";
      if ($eh_ativo != '2') {
        $sql.= "AND oad.eh_ativo = '$eh_ativo' ";
      }
      if ($eh_liberado != '2') {
        $sql.= "AND oad.eh_liberado = '$eh_liberado' ";
      }
      $sql.= " ORDER BY oad.dt_comentario ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM comentarioS - 1");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;
    }

    public function selectDadosComentariosObjetosAprendizagem($cd_objeto_aprendizagem_comentario) {
      $sql  = "SELECT ".
              "     oad.*, ".
              "     pe1.nm_pessoa nm_responsavel_comentario, pe1.cd_contato cd_contato_responsavel_comentario, ".
              "     pe2.nm_pessoa nm_responsavel_objeto_aprendizagem, pe2.cd_contato cd_contato_responsavel_objeto_aprendizagem ".
              "FROM ".
              "     life_objetos_aprendizagem_comentarios oad, ".
              "     life_pessoas pe1, ".
              "     life_pessoas pe2 ".
              "WHERE oad.cd_objeto_aprendizagem_comentario = '$cd_objeto_aprendizagem_comentario' ".
              "AND oad.cd_pessoa_responsavel_comentario = pe1.cd_pessoa ".
              "AND oad.cd_pessoa_responsavel_objeto_aprendizagem = pe2.cd_pessoa ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM comentarioS - 1");
      $dados = mysql_fetch_assoc($result_id);
      return $dados;
    }

    public function selectDadosComentario($cd_objeto_aprendizagem_comentario) {
      $sql  = "SELECT ".
              "     oad.*, ".
              "     pe1.nm_pessoa nm_responsavel_comentario, pe1.cd_contato cd_contato_responsavel_comentario, ".
              "     pe2.nm_pessoa nm_responsavel_objeto_aprendizagem, pe2.cd_contato cd_contato_responsavel_objeto_aprendizagem ".
              "FROM ".
              "     life_objetos_aprendizagem_comentarios oad, ".
              "     life_pessoas pe1, ".
              "     life_pessoas pe2 ".
              "WHERE oad.cd_objeto_aprendizagem_comentario = '$cd_objeto_aprendizagem_comentario' ".
              "AND oad.cd_pessoa_responsavel_comentario = pe1.cd_pessoa ".
              "AND oad.cd_pessoa_responsavel_objeto_aprendizagem = pe2.cd_pessoa ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM comentarioS - 1");
      $dados = mysql_fetch_assoc($result_id);
      return $dados;
    }

    private function insereComentario($cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_responsavel_comentario, $ds_comentario, $dt_comentario, $eh_liberado, $eh_ativo) {
      $sql = "INSERT INTO life_objetos_aprendizagem_comentarios ".
             "(cd_objeto_aprendizagem, cd_pessoa_responsavel_objeto_aprendizagem, cd_pessoa_responsavel_comentario, ds_comentario, dt_comentario, eh_liberado, eh_ativo) ".
             "VALUES ".
             "(\"$cd_objeto_aprendizagem\", \"$cd_pessoa_responsavel_objeto_aprendizagem\", \"$cd_pessoa_responsavel_comentario\", \"$ds_comentario\", \"$dt_comentario\", \"$eh_liberado\", \"$eh_ativo\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_comentarios');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM comentarioS - 5");
      $saida = mysql_affected_rows();
      return $saida;
    }

    private function editaComentario($cd_objeto_aprendizagem_comentario, $cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_responsavel_comentario, $ds_comentario, $dt_comentario, $eh_liberado, $ds_justificativa, $dt_justificativa, $eh_ativo) {
      $sql = "UPDATE life_objetos_aprendizagem_comentarios SET ".
             "ds_comentario = \"$ds_comentario\", ".
             "dt_comentario = \"$dt_comentario\", ".
             "eh_liberado = \"$eh_liberado\", ".
             "ds_justificativa = \"$ds_justificativa\", ".
             "dt_justificativa = \"$dt_justificativa\", ".
             "eh_ativo = \"$eh_ativo\" ".
             "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
             "AND cd_objeto_aprendizagem_comentario = '$cd_objeto_aprendizagem_comentario' ".
             "AND cd_pessoa_responsavel_objeto_aprendizagem = '$cd_pessoa_responsavel_objeto_aprendizagem' ".
             "AND cd_pessoa_responsavel_comentario = '$cd_pessoa_responsavel_comentario' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_comentarios');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM comentarioS - 7");
      $saida = mysql_affected_rows();
      return $saida;     
    }   

    private function alterarStatusComentarioObjetoAprendizagem($cd_objeto_aprendizagem_comentario, $eh_ativo, $cd_usuario_avaliacao) {
      $sql = "UPDATE life_objetos_aprendizagem_comentarios SET ".
             "cd_usuario_avaliacao = \"$cd_usuario_avaliacao\", ".
             "eh_ativo = \"$eh_ativo\" ".
             "WHERE cd_objeto_aprendizagem_comentario = '$cd_objeto_aprendizagem_comentario' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_comentarios');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM comentarioS - 7");
      $saida = mysql_affected_rows();
      return $saida;
    }

    private function alterarSituacaoComentarioObjetoAprendizagem($cd_objeto_aprendizagem_comentario, $eh_liberado, $cd_usuario_avaliacao) {
      $sql = "UPDATE life_objetos_aprendizagem_comentarios SET ".
             "cd_usuario_avaliacao = \"$cd_usuario_avaliacao\", ".
             "eh_liberado = \"$eh_liberado\" ".
             "WHERE cd_objeto_aprendizagem_comentario = '$cd_objeto_aprendizagem_comentario' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_comentarios');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM comentarioS - 7");
      $saida = mysql_affected_rows();
      return $saida;
    }
  }
?>                   