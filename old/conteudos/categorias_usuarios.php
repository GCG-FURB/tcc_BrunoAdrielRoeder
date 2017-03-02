<?php
  class CategoriaUsuario {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';               }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';             }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item);
          $this->listarItens($secao, $subsecao, $item);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item);
          $this->listarItens($secao, $subsecao, $item);
        break; 
        
        case "permissoes_usuarios":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item;
          $this->montarFormularioAjuste($link, $codigo);
        break;
        
        case "ajustar_permissoes":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarAjuste();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item);
          $this->listarItens($secao, $subsecao, $item);
        break; 

        case "inicial":
          $this->marcarInicial($codigo);
          $this->listarAcoes($secao, $subsecao, $item);
          $this->listarItens($secao, $subsecao, $item);
        break;

      }
    }
   
    public function listarAcoes($secao, $subsecao, $item) {
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Categorias de Usuários";

      $itens = $this->selectCategoriasUsuarios('1', '2', '2');
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Categoria:</td>\n";
      echo "      <td class=\"celConteudo\">Liberação Automática:</td>\n";
      echo "      <td class=\"celConteudo\">Cadastros Autorizados:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_categoria_usuario']."</td>\n";
        if ($it['eh_liberacao_automatica'] == '1') {
          echo "      <td class=\"celConteudo\">Sim</td>\n";
        } else {
          echo "      <td class=\"celConteudo\">Não</td>\n";
        }
        if ($it['eh_permitido_cadastro'] == '1') {
          echo "      <td class=\"celConteudo\">Sim</td>\n";
        } else {
          echo "      <td class=\"celConteudo\">Não</td>\n";
        }
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharCategoriaUsuario($it['cd_categoria_usuario']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cd=".$it['cd_categoria_usuario']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cd=".$it['cd_categoria_usuario']."&acao=permissoes_usuarios\"><img src=\"icones/usuarios_permissoes.png\" alt=\"Ajustar Permissões do Usuário\" title=\"Ajustar Permissões do Usuário\" border=\"0\"></a>\n";
        if ($it['eh_permitido_cadastro'] == '1') {
          if ($it['eh_categoria_novos_cadastros'] == '1') {
            echo "        <img src=\"icones/vazio.png\">\n";
          } else {
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cd=".$it['cd_categoria_usuario']."&acao=inicial\"><img src=\"icones/inicial.png\" alt=\"Marcar Categoria de Usuário como Inicial (com a qual os novos usuários serão associados)\" title=\"Marcar Categoria de Usuário como Inicial (com a qual os novos usuários serão associados)\" border=\"0\"></a>\n";
          }
        } else {
          echo "        <img src=\"icones/vazio.png\">\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
      echo "  <br /><br />\n"; 
    }
    
    public function detalharCategoriaUsuario($cd_categoria_usuario) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosCategoriaUsuario($cd_categoria_usuario);
      
      $retorno = "";
      if ($dados['cd_usuario_cadastro'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      } else {
        $retorno.= "Cadastro automático<br />";
      }
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
     
    private function montarFormularioEdicao($link, $cd_categoria_usuario) {
      $dados = $this->selectDadosCategoriaUsuario($cd_categoria_usuario);

      $nm_categoria_usuario = $dados['nm_categoria_usuario'];
      $ds_categoria_usuario = $dados['ds_categoria_usuario'];
      $eh_liberacao_automatica = $dados['eh_liberacao_automatica'];
      $ds_permissoes_padrao = $dados['ds_permissoes_padrao'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_permitido_cadastro = $dados['eh_permitido_cadastro'];
      $cd_categoria_usuario_liberacao = $dados['cd_categoria_usuario_liberacao'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Configuração de Acessos da Categoria de Usuário</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_categoria_usuario, $nm_categoria_usuario, $ds_categoria_usuario, $eh_liberacao_automatica, $ds_permissoes_padrao, $eh_ativo, $eh_permitido_cadastro, $cd_categoria_usuario_liberacao);
    }
    
    public function imprimeFormularioCadastro($link, $cd_categoria_usuario, $nm_categoria_usuario, $ds_categoria_usuario, $eh_liberacao_automatica, $ds_permissoes_padrao, $eh_ativo, $eh_permitido_cadastro, $cd_categoria_usuario_liberacao) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/permissoes.php';                                  $per = new Permissao();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_categoria_usuario', $cd_categoria_usuario);
      $util->campoHidden('eh_ativo', $eh_ativo);
      $util->campoHidden('nm_categoria_usuario', $nm_categoria_usuario);
      $util->linhaDuasColunasComentario('Categoria de Usuário: ', $nm_categoria_usuario);
      $util->linhaTexto('1', 'Descrição: ', 'ds_categoria_usuario', $ds_categoria_usuario, '10', '100');
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('Liberação de Novos Usuários deve ser automática: ', 'eh_liberacao_automatica', $eh_liberacao_automatica, $opcoes, '100');
      $util->linhaComentario('Apenas seleciona Categoria de Usuário de Liberação, se a Liberação Cadastral não for automática. Para liberação automática o campo será desconsiderado!');
      $this->retornaSeletorCategoriasUsuarios($cd_categoria_usuario_liberacao, 'cd_categoria_usuario_liberacao');     
      
      $util->linhaSeletor('É permitido que usuários não logados se cadastrem nesta categoria: ', 'eh_permitido_cadastro', $eh_permitido_cadastro, $opcoes, '100');
      
      $util->linhaComentario("<hr>");
      echo "    <tr>\n";
      echo "      <td class=\"celConteudo\" colspan=\"2\">\n";
      
      $per->retornaCamposPermissoes($ds_permissoes_padrao);
      
      echo "      </td>\n";
      echo "    </tr>\n";
      $util->linhaComentario("<hr>");

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'eh_liberacao_automatica'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/permissoes.php';                                  $per = new Permissao();

      $cd_categoria_usuario = addslashes($_POST['cd_categoria_usuario']);
      $nm_categoria_usuario = addslashes($_POST['nm_categoria_usuario']);
      $ds_categoria_usuario = $util->limparVariavel($_POST['ds_categoria_usuario']);
      $eh_liberacao_automatica = addslashes($_POST['eh_liberacao_automatica']);
      $ds_permissoes_padrao = $per->retornaPermissoes();
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $eh_permitido_cadastro = addslashes($_POST['eh_permitido_cadastro']);
      $cd_categoria_usuario_liberacao = addslashes($_POST['cd_categoria_usuario_liberacao']);

      if ($this->alteraCategoriaUsuario($cd_categoria_usuario, $nm_categoria_usuario, $ds_categoria_usuario, $eh_liberacao_automatica, $ds_permissoes_padrao, $eh_ativo, $eh_permitido_cadastro, $cd_categoria_usuario_liberacao)) {
        echo "<p class=\"fontConteudoSucesso\">Categoria de Usuário editada com sucesso!</p>\n";   
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Categoria de Usuário, ou nenhuma informação alterada!</p>\n";
      }
    } 

    private function montarFormularioAjuste($link, $cd_categoria_usuario) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $dados = $this->selectDadosCategoriaUsuario($cd_categoria_usuario);

      $nm_categoria_usuario = $dados['nm_categoria_usuario'];
      $ds_categoria_usuario = $dados['ds_categoria_usuario'];
      $ds_permissoes_padrao = $dados['ds_permissoes_padrao'];

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Ajuste de Permissões dos Usuários da Categoria</h2>\n";

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=ajustar_permissoes\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_categoria_usuario', $cd_categoria_usuario);
      $util->campoHidden('ds_permissoes_padrao', $ds_permissoes_padrao);

      $util->linhaDuasColunasComentario('Categoria de Usuário: ', $nm_categoria_usuario);

      $util->linhaComentario('');
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor("Deseja ajustar as permissões de todos os Usuários da Categoria ".$nm_categoria_usuario.": ", 'eh_ajustar', '0', $opcoes);

      $util->linhaBotao('Ajustar');
      echo "    </table>\n";
      echo "  <p class=\"fontConteudoAlerta\">A operação não poderá ser revertida!<br />Permissões especiais que tenham sido concedidas serão inabilitadas!</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'eh_ajustar'); 
    }

    public function salvarAjuste() {
      require_once 'conteudos/permissoes.php';                                  $per = new Permissao();

      $cd_categoria_usuario = addslashes($_POST['cd_categoria_usuario']);
      $ds_permissoes_padrao = addslashes($_POST['ds_permissoes_padrao']);
      $eh_ajustar = addslashes($_POST['eh_ajustar']);
      
      if ($eh_ajustar == '1') {
        require_once 'usuarios/usuarios.php';                                   $usu = new Usuario();
        $usuarios = $usu->selectUsuarios('2', $cd_categoria_usuario);
        $erros = 0;
        $acertos = 0;
        foreach ($usuarios as $u) {
          if ($usu->setarPermissao($u['cd_usuario'], $ds_permissoes_padrao)) {
            $acertos += 1;
          } else {
            $erros += 1;
          }
        }
        if ($erros == '0') {
          echo "<p class=\"fontConteudoSucesso\">Ajuste de Permissões dos Usuários da Categoria realizado com sucesso!</p>\n";
        } else {
          if ($acertos > 0) {
            echo "<p class=\"fontConteudoSucesso\">".$ajustes." Usuários da Categoria tiveram suas permissões ajustadas!</p>\n";
            echo "<p class=\"fontConteudoAlerta\">".$erros." Usuários da Categoria não tiveram suas permissões ajustadas. Possivelmente já possuiam tais permissões!</p>\n";
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas no Ajuste de Permissões dos Usuários da Categoria, ou os Usuários já possuiam tais permissões!</p>\n";
          }
        }      
      } else {
        echo "<p class=\"fontConteudoAlerta\">Processo abortado pelo usuário!</p>\n";
      }
    } 

    public function retornaSeletorCategoriasUsuarios($cd_categoria_usuario, $campo) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectCategoriasUsuarios('1', '2', '2');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                                   $opcao[]= 'Selecione uma Categoria de Usuário';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_categoria_usuario'];           $opcao[]= $it['nm_categoria_usuario'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Categoria de Usuário: ', $campo, $cd_categoria_usuario, $opcoes, '100');
    }   
    
    public function retornaSeletorCategoriaCadastroProprio($cd_categoria_usuario) {
      $itens = $this->selectCategoriasUsuarios('1', '2', '1');

      echo "          <select name=\"cd_categoria_usuario\" id=\"cd_categoria_usuario\" class=\"fonInputExterno\">\n";
      echo "  			    <option selected value=\"0\">Categoria de Usuário</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option value=\"".$it['cd_categoria_usuario']."\">".$it['nm_categoria_usuario']."</option>\n";
      }
      echo "          </select>\n";
    }      
                               
    public function marcarInicial($cd_categoria_usuario) {
      $erros = false;
      if (!$this->ajustarCategoriaUsuarioInicial('', '0')) {
        $erros = true;
      }
      if (!$this->ajustarCategoriaUsuarioInicial($cd_categoria_usuario, '1')) {
        $erros = true;
      }
      
      if ($erros) {
        echo "<p class=\"fontConteudoAlerta\">Ocorreram Erros ao ajustar a Categoria como Inicial!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Ajuste de Categoria Inicial realizada com sucesso!</p>\n";
      }
    }        
    
    public function retornaCategoriaUsuarioInicial() {
      $dados = $this->selectDadosCategoriaUsuarioInicial();
      
      return $dados['cd_categoria_usuario'];
    }                   
                                    
//**************BANCO DE DADOS**************************************************    
    public function selectCategoriasUsuarios($eh_ativo, $eh_liberacao_automatica, $eh_permitido_cadastro) {
      $sql  = "SELECT * ".
              "FROM life_categorias_usuarios ".
              "WHERE cd_categoria_usuario > '0' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      if ($eh_liberacao_automatica != 2) {
        $sql.= "AND eh_liberacao_automatica = '$eh_liberacao_automatica' ";
      }
      if ($eh_permitido_cadastro != 2) {
        $sql.= "AND eh_permitido_cadastro = '$eh_permitido_cadastro' ";
      }
      $sql.= "ORDER BY nm_categoria_usuario ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CATEGORIAS USUÁRIOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosCategoriaUsuarioInicial() {
      $sql  = "SELECT * ".
              "FROM life_categorias_usuarios ".
              "WHERE eh_categoria_novos_cadastros = '1' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CATEGORIAS USUÁRIOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
            
    public function selectDadosCategoriaUsuario($cd_categoria_usuario) {
      $sql  = "SELECT * ".
              "FROM life_categorias_usuarios ".
              "WHERE cd_categoria_usuario = '$cd_categoria_usuario' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CATEGORIAS USUÁRIOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function alteraCategoriaUsuario($cd_categoria_usuario, $nm_categoria_usuario, $ds_categoria_usuario, $eh_liberacao_automatica, $ds_permissoes_padrao, $eh_ativo, $eh_permitido_cadastro, $cd_categoria_usuario_liberacao) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_categorias_usuarios SET ".
             "nm_categoria_usuario = \"$nm_categoria_usuario\", ".
             "ds_categoria_usuario = \"$ds_categoria_usuario\", ".
             "eh_liberacao_automatica = \"$eh_liberacao_automatica\", ".
             "ds_permissoes_padrao = \"$ds_permissoes_padrao\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "eh_permitido_cadastro = \"$eh_permitido_cadastro\", ".
             "cd_categoria_usuario_liberacao = \"$cd_categoria_usuario_liberacao\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_categoria_usuario= '$cd_categoria_usuario' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'categorias_usuarios');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CATEGORIAS USUÁRIOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }
    
    private function ajustarCategoriaUsuarioInicial($cd_categoria_usuario, $eh_categoria_novos_cadastros) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_categorias_usuarios SET ".
             "eh_categoria_novos_cadastros = \"$eh_categoria_novos_cadastros\" ";
      if ($cd_categoria_usuario != '') {             
        $sql.= "WHERE cd_categoria_usuario = '$cd_categoria_usuario' ";
      }
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'categorias_usuarios');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CATEGORIAS USUÁRIOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
  
  }
?>