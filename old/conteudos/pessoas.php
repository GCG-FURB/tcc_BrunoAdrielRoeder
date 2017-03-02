<?php
  class Pessoa {
    
    public function __construct () {
    }

    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['in']))    {      $inicial = addslashes($_GET['in']);         } else {      $inicial = 'A';       }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['ct']))    {      $categoria = addslashes($_GET['ct']);       } else {      $categoria = '';      }

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario = $_SESSION['life_permissoes'];
      } else {
        return false;
      }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;

        case "pesquisar":
          $this->listarOpcoesPesquisa($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;
        
        case "pesq":
          $_SESSION['life_c_pesquisando'] = '1';
          $this->pesquisar($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;
        
        case "zpesquisa":
          unset($_SESSION['life_c_pesquisando']);
          unset($_SESSION['life_c_termo']);
          unset($_SESSION['life_c_campo']);
          $inicial = 'A';
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;
        
        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;  

        case "status":
          $this->alterarStatus($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;

        case "detalhar":
          echo "<p class=\"fontComandosFiltros\">\n";
          echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\"border=\"0\"></a> \n";
          echo "</p>\n";
          $this->apresentarInformacoesPessoa($codigo);
        break;      

        case "permissoes":
          if ($permissoes_usuario[34] == 1) {
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas;
            $this->montarFormularioPermissoes($codigo, $link);
          } else {
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
            $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          }
        break;

        case "salvar_perm":
          if ($permissoes_usuario[34] == 1) {
            if (isset($_SESSION['life_edicao'])) {
              $this->salvarPermissoesUsuario();
              unset($_SESSION['life_edicao']);
            }
          }
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;

        case "categorias":
          if ($permissoes_usuario[32] == 1) {
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas;
            $this->montarFormularioEdicaoCategoria($link, $codigo);
          } else {
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
            $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          }
        break;

        case "salvar_cat":
          if ($permissoes_usuario[32] == 1) {
            if (isset($_SESSION['life_edicao'])) {
              $this->salvarCadastroAlteracaoCategoria();
              unset($_SESSION['life_edicao']);
            }
          }
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;

        case "atualizacao":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas;
          $this->montarFormularioAtualizacao($link);
        break;

        case "salvar_atu":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarAtualizacaoCadastral();
            unset($_SESSION['life_edicao']);
          }
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
        break;
      }
    }

    private function listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat = new CategoriaUsuario();

      $opcoes= array();
      $i = 1;
      $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=1";                               $opcao['descricao']= "Ativos";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=0";                               $opcao['descricao']= "Inativos";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=2";                               $opcao['descricao']= "Ativos/Inativos";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "";                                                                                                                               $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;

      $categorias = $cat->selectCategoriasUsuarios('1', '2', '2');
      if (count($categorias) > 0) {
        foreach ($categorias as $c) {
          $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$c['cd_categoria_usuario']."&in=".$inicial."&at=".$ativas;        $opcao['descricao']= $c['nm_categoria_usuario'];                          $opcoes[]= $opcao;
        }
        if ($categoria != '') {
          $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas;                                          $opcao['descricao']= 'Todas as categorias';                               $opcoes[]= $opcao;
        }
        $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "";                                                                                                                               $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
      }
      $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&acao=pesquisar";      $opcao['descricao']= "Pesquisar";                                         $opcoes[]= $opcao;
      if (isset($_SESSION['life_c_pesquisando'])) {
        $opcao= array();      $opcao['indice']= $i; $i+=1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&acao=zpesquisa";      $opcao['descricao']= "Limpar Pesquisa";                         $opcoes[]= $opcao;
      } else {
        if (isset($_SESSION['life_c_termo'])) {        $termo = $_SESSION['life_c_termo'];            } else {        $_SESSION['life_c_termo'] = '';        $termo = $_SESSION['life_c_termo'];      }
        if (isset($_SESSION['life_c_campo'])) {        $campo = $_SESSION['life_c_campo'];            } else {        $_SESSION['life_c_campo'] = 'N';       $campo = $_SESSION['life_c_campo'];      }
      }      
      $opcao= array();      $opcao['indice']= "7";      $opcao['link']= "";                                                                                                             $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\"border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&in=".$inicial."&acao=atualizacao\"><img src=\"icones/atualizacao.png\" alt=\"Registrar pedido de atualiza��o cadastral\" title=\"Registrar pedido de atualiza��o cadastral\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
      echo "<p class=\"fontComandosCentralizados\">\n";
      $alfabeto= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
      foreach ($alfabeto as $alfa) {
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&in=".$alfa."\" class=\"fontLink\">".$alfa."</a>&nbsp;&nbsp;\n";
      }
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&in=0\" class=\"fontLink\">A � Z</a>\n";
      echo "</p>\n";      
    }

    private function listarOpcoesPesquisa($secao, $subsecao, $item, $ativas, $categoria, $inicial) {
      if (isset($_SESSION['life_c_termo'])) {        $termo = $_SESSION['life_c_termo'];            } else {        $_SESSION['life_c_termo'] = '';        $termo = $_SESSION['life_c_termo'];      }
      if (isset($_SESSION['life_c_campo'])) {        $campo = $_SESSION['life_c_campo'];            } else {        $_SESSION['life_c_campo'] = 'N';       $campo = $_SESSION['life_c_campo'];      }
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&acao=pesq";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."\" >\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->linhaComentario("Pesquisar:");
      $util->linhaUmCampoText(0, 'Nome do Pessoa: ', 'termo', '50', '100', $termo);
      $util->campoHidden('campo', 'N');           
      $util->linhaBotao('Pesquisar');
      echo "    </table>\n";      
      echo "  </form>\n";
    }           

    private function pesquisar($secao, $subsecao, $item, $ativas, $categoria, $inicial) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      if (isset($_POST['termo'])) {        $termo = $util->limparVariavel($_POST['termo']);      } else {        $termo = '';      }
      if (isset($_POST['campo'])) {        $campo = addslashes($_POST['campo']);                 } else {        $campo = 'I';     }
      $_SESSION['life_c_termo'] = $termo;        
      $_SESSION['life_c_campo'] = $campo;
      $inicial = '0';
      $this->listarAcoes($secao, $subsecao, $item, $ativas, $categoria, $inicial);
      $this->listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial);
    }

    private function listarItens($secao, $subsecao, $item, $ativas, $categoria, $inicial) {
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      $termo = $_SESSION['life_c_termo'];
      $campo = $_SESSION['life_c_campo'];

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario = $_SESSION['life_permissoes'];
      } else {
        return false;
      }

      $itens = $this->selectPessoas($ativas, $campo, $termo, $inicial, $categoria);

      $mensagem = "Usu�rios ";
      if ($ativas == 1) {        $mensagem.= "Ativos";      } elseif ($ativas == 0) {        $mensagem.= "Inativos";      }
      if ($termo != '') {
        $mensagem.= "<br />Termo pesquisado: '".$termo."'";
      }
      if ($inicial != '0')     {          $mensagem.= " - com nome iniciado em '".$inicial."'";      } 
      else                     {          $mensagem.= " - com nome iniciado com qualquer letra";     }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Nome:</td>\n";
      echo "      <td class=\"celConteudo\">Categoria:</td>\n";
      echo "      <td class=\"celConteudo\" style=\"width:30px;\">Origem:</td>\n";
      echo "      <td class=\"celConteudo\">A��es:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_pessoa']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_categoria_usuario']."</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:30px; text-align:center;\">\n";
        if ($it['cd_origem'] == '0') {
          echo "        <img src=\"icones/botao_inversos_p.png\" alt=\"Inversos\" title=\"Inversos\"border=\"0\">\n";
        } elseif ($it['cd_origem'] == '1') {
          echo "        <img src=\"icones/botao_facebook_p.png\" alt=\"Facebook\" title=\"Facebook\"border=\"0\">\n";
        } elseif ($it['cd_origem'] == '2') {
          echo "        <img src=\"icones/botao_google_p.png\" alt=\"Google\" title=\"Google\"border=\"0\">\n";
        }
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharPessoa($it['cd_pessoa']);
        echo "          </span>\n";
        echo "        </a>\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/contatos.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $con->detalharContato($it['cd_contato']);
        echo "          </span>\n";
        echo "        </a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_pessoa']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\"border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_pessoa']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\"border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\"border=\"0\"></a>\n";
        }
        if ($permissoes_usuario[34] == 1) {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_pessoa']."&acao=permissoes\"><img src=\"icones/permissoes.png\" alt=\"Permiss�es do Usu�rio\" title=\"Permiss�es do Usu�rio\" border=\"0\"></a>\n";
        }
        if ($permissoes_usuario[32] == 1) {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_pessoa']."&acao=categorias\"><img src=\"icones/categorias_usuarios_pp.png\" alt=\"Categoria do Usu�rio\" title=\"Categoria do Usu�rio\" border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_pessoa']."&acao=detalhar\"><img src=\"icones/detalhar_usuario.png\" alt=\"Detalhar\" title=\"Detalhar\"border=\"0\"></a>\n";

/*
        echo "        <a href=\"index.php?secao=".$secao."&sub=25&it=".$item."&ca=".$it['cd_pessoa']."&cc=0\"><img src=\"icones/cursos_pessoas.png\" alt=\"Inscri��es (Cursos x Pessoas)\" title=\"Inscri��es (Cursos x Pessoas)\" border=\"0\"></a>\n";
*/
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
    }

    private function detalharPessoa($cd_pessoa) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosPessoa($cd_pessoa);
      
      $retorno = "";
      if ($dados['cd_usuario_cadastro'] == '0') {
        $retorno.= "Cadastro realizado pelo pr�prio usu�rio<br />\n";
      } else {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      }
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        if ($dados['cd_usuario_ultima_atualizacao'] == '0') {
          $retorno.= "Atualiza��o realizada pelo pessoa<br />\n";
        } else {
          $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
          $retorno.= "�ltima Atualiza��o por: ".$dados_usuario['nm_usuario']."<br />\n";
        }
        $retorno.= "Data do �ltima Atualiza��o: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }

    public function apresentarInformacoesPessoa($cd_pessoa) {
      require_once 'conteudos/areas_formacao.php';                              $are_for = new AreaFormacao();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat_usu = new CategoriaUsuario();

      $dados = $this->selectDadosPessoa($cd_pessoa);
      echo "<h2>Usu�rio: ".$dados['nm_pessoa']."</h2>\n";
      echo "<hr>\n";
      echo "<p>\n";
      $usuario = $usu->selectDadosUsuario($dados['cd_usuario']);
      if ($usuario['cd_origem'] == '0') {
        echo "  Origem do usu�rio: Inversos<br />\n";
      } elseif ($usuario['cd_origem'] == '1') {
        echo "  Origem do usu�rio: Facebook<br />\n";
      } elseif ($usuario['cd_origem'] == '2') {
        echo "  Origem do usu�rio: Google<br />\n";
      }
      $categoria = $cat_usu->selectDadosCategoriaUsuario($usuario['cd_categoria_usuario']);
      echo "  Categoria: ".$categoria['nm_categoria_usuario']."<br />\n";
      echo "</p>\n";
      echo "<hr>\n";
      echo "<p>\n";
      echo "  CPF: ".$dados['nr_cpf']."<br />\n";
      $formacao = $are_for->selectDadosAreaFormacao($dados['cd_area_formacao']);
      echo "  Forma��o: ".$formacao['nm_area_formacao']."<br />\n";
      echo "</p>\n";
      echo "<hr>\n";
      echo "<p>\n";
      echo "  Endere�o: <br />".$end->retornaDetalhesEnderecoCompleto($dados['cd_endereco'])."\n";
      echo "</p>\n";
      echo "<hr>\n";
      echo "<p>\n";
      echo "  Contatos: <br />".$con->detalharContato($dados['cd_contato'])."\n";
      echo "</p>\n";
      echo "<hr>\n";
      echo "<p>\n";
      echo "  Dados complementares:<br />".nl2br($dados['ds_dados_complementares'])."<br />\n";
      echo "</p>\n";
      echo "<hr>\n";
      echo "<p>\n";
      echo "  Mini-curr�culo: <br />".nl2br($dados['ds_mini_curriculo'])."<br />\n";
      echo "  <a href=\"".$dados['ds_link_lattes']."\" target=\"_blank\" class=\"fontLink\">Curr�culo Lattes</a>\n";
      echo "</p>\n";
    }

    private function montarFormularioEdicao($link, $cd_pessoa) {
      $dados = $this->selectDadosPessoa($cd_pessoa);

      $cd_usuario = $dados['cd_usuario'];
      $nm_pessoa = $dados['nm_pessoa'];
      $nr_cpf = $dados['nr_cpf'];
      $cd_area_formacao = $dados['cd_area_formacao'];
      $cd_endereco = $dados['cd_endereco'];
      $cd_contato = $dados['cd_contato'];
      $ds_dados_complementares = $dados['ds_dados_complementares'];
      $ds_mini_curriculo = $dados['ds_mini_curriculo'];
      $ds_link_lattes = $dados['ds_link_lattes'];
      $eh_ativo = $dados['eh_ativo'];

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edi��o de Pessoa</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_pessoa, $cd_usuario, $nm_pessoa, $nr_cpf, $cd_area_formacao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo);
    }    

     private function imprimeFormularioCadastro($link, $cd_pessoa, $cd_usuario, $nm_pessoa, $nr_cpf, $cd_area_formacao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/areas_formacao.php';                              $are_for = new AreaFormacao();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'conteudos/areas_conhecimento_pessoa.php';                   $acp = new AreasConhecimentoPessoa();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_atualiza_pessoa.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";

      $util->campoHidden('cd_pessoa', $cd_pessoa);
      $util->campoHidden('cd_usuario', $cd_usuario);
      $util->campoHidden('eh_ativo', $eh_ativo);

      $util->linhaUmCampoText(1, 'Nome: ', 'nm_pessoa', 100, 100, $nm_pessoa);

      if ($nr_cpf != '') {
        $util->campoHidden('nr_cpf', $nr_cpf);
        $util->linhaDuasColunasComentario('CPF: ', $nr_cpf);
      } else {
        $util->linhaUmCampoTextAcao(1, 'CPF: ', 'nr_cpf', 14, 100, $nr_cpf, " onKeyPress=\"mascaraCpf('nr_cpf')\"; onBlur=\"validaCpf('nr_cpf');\" ");
      }
      $are_for->retornaSeletorAreasFormacao($cd_area_formacao);
      $acp->retornaSeletorAreasConhecimentoPessoa($cd_pessoa);

      $util->linhaComentario('&nbsp;');
      $end->apresentaFormularioEndereco('Endere�o:', $cd_endereco, false);
      $util->linhaComentario('&nbsp;');
      $con->imprimeFormularioContatos('Contatos:', $cd_contato, true, true, true, true, true);
      $util->linhaComentario('&nbsp;');
      $util->linhaTexto('0', 'Dados complementares: ', 'ds_dados_complementares', $ds_dados_complementares, '5', '100');
      $util->linhaTexto('0', 'Mini curr�culo: ', 'ds_mini_curriculo', $ds_mini_curriculo, '5', '100');
      $util->linhaUmCampoText(0, 'Curr�culo Lattes (link): ', 'ds_link_lattes', 150, '100', $ds_link_lattes);
      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigat�rios</p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'nm_pessoa');
    }

    private function alterarStatus($cd_pessoa) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();

      $dados = $this->selectDadosPessoa($cd_pessoa);

      $cd_usuario = $dados['cd_usuario'];
      $nm_pessoa = $dados['nm_pessoa'];
      $nr_cpf = $dados['nr_cpf'];
      $cd_area_formacao = $dados['cd_area_formacao'];
      $cd_endereco = $dados['cd_endereco'];
      $cd_contato = $dados['cd_contato'];
      $ds_dados_complementares = $dados['ds_dados_complementares'];
      $ds_mini_curriculo = $dados['ds_mini_curriculo'];
      $ds_link_lattes = $dados['ds_link_lattes'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
    
      if ($this->alterarPessoa($cd_pessoa, $nm_pessoa, $nr_cpf, $cd_area_formacao, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo)) {
        if ($usu->alteraStatusUsuario($cd_usuario, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Status do Pessoa alterado com sucesso!</p>\n";
        } else {
          if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
          $this->alterarPessoa($cd_pessoa, $nm_pessoa, $nr_cpf, $cd_area_formacao, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo);
          echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status do Pessoa!</p>\n";
        }
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status do Pessoa!</p>\n";
      }                                                                                               
    }
/*
    private function validarEmailPessoa($pagina, $lista_paginas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      
      $ds_email = $util->limparVariavel($_POST['e_mail_cadastro']);
      
      if ($ds_email != '') {
        if ($usu->existeUsername($ds_email)) {
          $_SESSION['life_erro_cadastro_e_mail'] = "E-mail informado j� est� cadastrado!";
          require_once 'login/login.php';                                       $login = new Login();
          if (isset($lista_paginas[1])) {
            $link = $lista_paginas[0]."/".$lista_paginas[1]."/";
            $acao = "cadastro";
          } else {
            $link = 'index.php';
            $acao = "?secao=1&acao=logar";
          }
          $_SESSION['life_email_username_retorno_login'] = $ds_email;
          //$login->montarLoginInscricao($link, $acao, 'i');
        } else {
          require_once 'includes/cep.php';                                      $cep = new Cep();
          require_once 'conteudos/enderecos.php';                               $end = new Endereco();
          require_once 'conteudos/contatos.php';                                $con = new Contato();
          
          $nome = $util->limparVariavel($_POST['nome']);
          $ds_cep = $util->limparVariavel($_POST['cep']);
          $senha = base64_encode(trim($_POST['ds_senha']));
          $original_senha = $_POST['ds_senha'];
          
          $dados = $cep->recuperaEnderecoCEP($ds_cep);
          if (strpos($dados, "falha")) {
            $cd_endereco = $end->inserirEndereco('5508', '', '', '', '', '');
          } else {
            $dados = explode(',', $dados);
            $estado = $dados['5'];
            $estado = str_replace(":","",$estado);
            $estado = str_replace("\"","",$estado);
            $estado = str_replace("uf","",$estado);
            $cd_estado = $end->selectCodigoEstadoSigla($estado);

            $cidade = $dados['6'];
            $cidade = str_replace(":","",$cidade);
            $cidade = str_replace("\"","",$cidade);
            $cidade = str_replace("cidade","",$cidade);
            $cd_cidade = $end->selectCodigoCidadeEstado($cidade, $cd_estado);

            $bairro = $dados['7'];
            $bairro = str_replace(":","",$bairro);
            $bairro = str_replace("\"","",$bairro);
            $ds_bairro = str_replace("bairro","",$bairro);
            
            $logradouro = $dados['9'];
            $logradouro = str_replace(":","",$logradouro);
            $logradouro = str_replace("\"","",$logradouro);
            $ds_rua = str_replace("logradouro","",$logradouro);
            
            $cd_endereco = $end->inserirEndereco($cd_cidade, $ds_rua, '', '', $ds_bairro, $ds_cep);
          }
          $nm_usuario = $util->cortarStringAposPalavra(' ', $nome);
          $cd_categoria_usuario = '3';
          $ds_permissoes = "0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
          $dt_ultimo_acesso = date('Y-m-d');
          $nr_tentativas_login = "0";
          $ds_chave = "C_O_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,14);
          
          if ($cd_endereco > 0) {
            $cd_contato = $con->insereContato('', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            if ($cd_contato > 0) {
              $cd_usuario = $usu->inserirUsuario($ds_email, $nm_usuario, $ds_email, $senha, $cd_categoria_usuario, $ds_permissoes, $dt_ultimo_acesso, '1', $nr_tentativas_login, $ds_chave);
              if ($cd_usuario > 0) {
                $ds_chave_pessoa = "C_O_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,10);
                $lk_seo = $util->retornaLinkSEO($nome, 'life_pessoas', 'lk_seo', '150', '');      
                if ($this->inserirPessoa($cd_usuario, $cd_contato, $cd_endereco, $nome, '', '', '', '0', '', '1', $lk_seo, $ds_chave)) {
                  $_SESSION['life_cadastro_ds_username'] = $ds_email;
                  $_SESSION['life_cadastro_nm_usuario'] = $nm_usuario;
                  $_SESSION['life_cadastro_ds_email'] = $ds_email;
                  $_SESSION['life_cadastro_ds_senha'] = $original_senha;                  
                  $_SESSION['life_erro_cadastro_e_mail'] = "Cadastro Realizado com Sucesso!<br /><br />Deseja realizar novos cadastros?";
                  return true;
                } else {
                  $_SESSION['life_erro_cadastro_e_mail'] = "Problemas no Cadastro!";
                  return false; 
                }      
              } else {
                $_SESSION['life_erro_cadastro_e_mail'] = "Problemas no Cadastro!";
                return false; 
              }      
            } else {
              $_SESSION['life_erro_cadastro_e_mail'] = "Problemas no Cadastro!";
              return false; 
            }      
          } else {
            $_SESSION['life_erro_cadastro_e_mail'] = "Problemas no Cadastro!";
            return false; 
          }      
        }      
      } else {
        $_SESSION['life_erro_cadastro_e_mail'] = "E-mail n�o informado!";
        return false;
      }
      if ($pagina == '') {
        $this->retornaErroCadastro($pagina, $lista_paginas);
      }
    }
    
    public function retornaErroCadastro($pagina, $lista_paginas) {
      if (isset($_SESSION['life_erro_cadastro_e_mail'])) {
        echo "<p class=\"fontConteudoAlerta\">".$_SESSION['life_erro_cadastro_e_mail']."</p>\n";
        unset($_SESSION['life_erro_cadastro_e_mail']);
        if (isset($_SESSION['life_erro_login'])) {
          unset($_SESSION['life_erro_login']);
        }        
        if (isset($_SESSION['life_curso_retorno_login'])) {
          require_once 'conteudos/cursos_pessoas_inscricoes.php';                $cur_alu_ins = new CursoPessoaInscricao();
          require_once 'conteudos/cursos.php';                                  $cur = new Curso();
          
          $cd_curso = addslashes($_SESSION['life_curso_retorno_login']);
          $curso = $cur->selectDadosCurso($cd_curso);
        
          $cur_alu_ins->retornaInformacoesInscricao($curso, 'inscrever-se', $lista_paginas);  
        } else {
          echo "<p class=\"fontConteudoAlerta\">Erro ao identificar Curso selecionado!</p>\n";
          return false;
        }
      }
    }
    
    public function salvarCadastroPessoaInscricaoCurso($lista_paginas) {
      if (isset($_SESSION['life_edicao'])) {
        if ($this->salvarCadastroAlteracao()) {
          require_once 'login/login.php';                                       $login = new Login();
          require_once 'includes/configuracoes.php';                            $conf= new Configuracao();
          $nm_site = $conf->retornaNomeSite(); 
          $username = addslashes($_SESSION['life_email_username_retorno_login']);
          if ($login->forcarLogin($username)) {
            echo "<p class=\"fontConteudoSucesso\">Ol� ".$_SESSION['life_nome']."!<br />Seja bem vindo(a) ao ".$nm_site."!</p>\n";
            require_once 'conteudos/cursos_pessoas_inscricoes.php';              $cur_alu_ins = new CursoPessoaInscricao();
            $cur_alu_ins->retornaProcedimentosInscricaoPessoaCurso($lista_paginas);
          } else {
            echo "<p class=\"fontConteudoSucesso\">Desculpe por�m n�o foi poss�vel efetuar seu login no ".$nm_site."!</p>\n";
            echo "<p class=\"fontConteudoSucesso\">Tente efetuar seu login com o Usu�rio (e-mail) e senha que utilizou no cadastro! Caso o erro se repita, entre em contato na se��o <a href=\"".$_SESSION['life_link_completo']."fale-conosco\" class=\"fontLink\">Fale Conosco</a>!</p>\n";
          }
          
        }
        unset($_SESSION['life_edicao']);
            
            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Dados para inscri��o j� foram salvos anteriormente ou ocorreu um erro ao efetuar salvamento!</p>\n";
        return false;
      }    
    }
    
    public function validaPessoaRetornoDadosPagamentoPagSeguro() {
      if (isset($_GET['lka'])) {
        $lk_seo_pessoa = addslashes($_GET['lka']);
        $dados_pessoa_seo = $this->selectDadosPessoaSeo($lk_seo_pessoa);
        if ($dados_pessoa_seo['cd_pessoa'] > 0) {
          if (isset($_SESSION['life_pessoa_formulario_pagamento'])) {
            $cd_pessoa = addslashes($_SESSION['life_pessoa_formulario_pagamento']);
            $dados_pessoa_secao = $this->selectDadosPessoa($cd_pessoa);
            if ($dados_pessoa_secao['cd_pessoa'] > 0) {
              if (isset($_GET['cha'])) {
                $ds_chave_pessoa = addslashes($_GET['cha']);
                $dados_pessoa_retorno = $this->selectDadosPessoaChave($ds_chave_pessoa);
                if (($dados_pessoa_retorno['cd_pessoa'] != $dados_pessoa_secao['cd_pessoa']) || ($dados_pessoa_retorno['cd_pessoa'] != $dados_pessoa_seo['cd_pessoa']) || ($dados_pessoa_secao['cd_pessoa'] != $dados_pessoa_seo['cd_pessoa'])) {
                  echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar pessoa!</p>\n";
                  return '0';
                } else {    
                  echo "<h2>Pessoa: ".$dados_pessoa_retorno['nm_pessoa']."</h2>\n";
                  return $cd_pessoa;
                }
              } else {
                echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar pessoa!</p>\n";
                return '0';
              }
            } else {
              echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar pessoa!</p>\n";
              return '0';
            }
          } else {
            echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar pessoa!</p>\n";
            return '0';
          }
        } else {
          echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar pessoa!</p>\n";
          return '0';
        }
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar pessoa!</p>\n";
        return '0';
      }
   }
    
    
            */

//*******************************PERMISSOES*************************************

    public function montarFormularioPermissoes($cd_pessoa, $link) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario = $_SESSION['life_permissoes'];
      } else {
        return false;
      }
      if ($permissoes_usuario[34] == 1) {
        $pessoa = $this->selectdadosPessoa($cd_pessoa);
        $usuario = $usu->selectDadosUsuario($pessoa['cd_usuario']);

        $permissoes = $usuario['ds_permissoes'];

        echo "    <h2>Permiss�es do Usu�rio ".$pessoa['nm_pessoa']."</h2>\n";

        echo "  <form id=\"cadastro\" method=\"post\" action=\"".$link."&acao=salvar_perm\">\n";
        $util->campoHidden('cd_pessoa', $cd_pessoa);
        $util->campoHidden('cd_usuario', $pessoa['cd_usuario']);
        $usu->exibirFormularioPermissoes($permissoes);
        echo "  </form>\n";
        $_SESSION['life_edicao'] = '1';
      }
    }


    public function salvarPermissoesUsuario() {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();

      if ($permissoes_usuario[34] == 1) {

        $cd_usuario = addslashes($_POST['cd_usuario']);
        $permissao = $usu->retornaStringLeituraPermissoes();

        if ($usu->setarPermissao($cd_usuario, $permissao)) {
          echo "<p class=\"fontConteudoSucesso\">Permiss�es do usu�rio alteradas com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar permiss�es do usu�rio, ou nenhuma informa��o alterada!</p>\n";
        }
      }
    }


//**************ATUALIZACAO CADASTRAL*******************************************
    public function montarFormularioAtualizacao($link) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $_SESSION['life_edicao'] = '1';

      echo "  <h2>Chamada de Atualiza��o Cadastral</h2>\n";

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar_atu\">\n";
      echo "    <table class=\"tabConteudo\">\n";

      $lista = array();
      $item = array();
      $item[] = '0';
      $item[] = 'N�o desejo registrar o pedido de atualiza��o cadastral para todos os usu�rios do sistema';
      $lista[] = $item;
      $item = array();
      $item[] = '1';
      $item[] = 'Desejo registrar o pedido de atualiza��o cadastral para todos os usu�rios do sistema';
      $lista[] = $item;
      $util->linhaNCampoRadio($lista, 'eh_atualizacao', '0', true);

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigat�rios</p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'nm_pessoa');

    }

    public function salvarAtualizacaoCadastral() {
      $eh_atualizar = addslashes($_POST['eh_atualizacao']);

      if ($eh_atualizar == '1') {
        if ($this->registrarNecessidadeAtualizacao()) {
          echo "<p class=\"fontConteudoSucesso\">Necessidade de atualiza��o cadastral registrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao registrar necessidade de atualiza��o cadastral!</p>\n";
        }
      }  else {
        echo "<p class=\"fontConteudoAlerta\">Processo abortado pelo usu�rio!</p>\n";
      }

    }


//***************CATEGORIA DO USUARIO ******************************************
    public function montarFormularioEdicaoCategoria($link, $cd_pessoa) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat = new CategoriaUsuario();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();

      $_SESSION['life_edicao'] = '1';

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario = $_SESSION['life_permissoes'];
      } else {
        return false;
      }

      if ($permissoes_usuario[32] == 1) {

        $dados = $this->selectDadosPessoa($cd_pessoa);
        $usuario = $usu->selectDadosUsuario($dados['cd_usuario']);
        $categoria = $cat->selectDadosCategoriaUsuario($usuario['cd_categoria_usuario']);

        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
        echo "</p>\n";

        echo "<p class=\"fontConteudo\">Usu�rio: ".$dados['nm_pessoa']."</p>\n";
        echo "<p class=\"fontConteudo\">Categoria: ".$categoria['nm_categoria_usuario']."</p>\n";
        echo "<hr>\n";

        echo "<p class=\"fontConteudo\">Alterar da categoria ".$categoria['nm_categoria_usuario'].", para:</p>\n";

        include "js/js_cadastro_alteracao_categoria.js";
        echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar_cat\" onSubmit=\"return valida(this);\">\n";

        echo "  <table class=\"tabConteudo\">\n";
        $util->campoHidden('cd_usuario', $usuario['cd_usuario']);
        $util->campoHidden('cd_categoria_usuario_atual', $usuario['cd_categoria_usuario']);
        $style = "linhaOn";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">&nbsp;</td>\n";
        echo "      <td class=\"celConteudo\">Categoria:</td>\n";
        echo "      <td class=\"celConteudo\">Descri��o:</td>\n";
        echo "    </tr>\n";
        $categorias = $cat->selectCategoriasUsuarios('1', '2', '2');
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
        echo "      <td class=\"celConteudo\" style=\"text-align:center;\" colspan=\"3\">\n";
        echo "  	    <input type=\"submit\" class=\"celConteudoBotao\" value=\"Salvar\" tabindex=\"1\">\n";
        echo "      </td>\n";
        echo "  	</tr>\n";
        echo "  </table>\n";
      }
    }

    public function salvarCadastroAlteracaoCategoria() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat = new CategoriaUsuario();
      require_once 'conteudos/email.php';                                       $email = new Email();

      $cd_usuario = addslashes($_POST['cd_usuario']);
      $cd_categoria_usuario_atual = addslashes($_POST['cd_categoria_usuario_atual']);
      $cd_categoria_usuario_solicitada = addslashes($_POST['cd_categoria_usuario_solicitada']);

      $dados = $this->selectDadosPessoaUsuario($cd_usuario);
      $usuario = $usu->selectDadosUsuario($cd_usuario);
      $categoria_atual = $cat->selectDadosCategoriaUsuario($cd_categoria_usuario_atual);
      $categoria_pretendida = $cat->selectDadosCategoriaUsuario($cd_categoria_usuario_solicitada);

      $editou_usuario = $usu->ajustarCategoriaUsuario($cd_usuario, $cd_categoria_usuario_solicitada);
      if ($editou_usuario) {
        $texto      = "           Prezado(a) ".$dados['nm_pessoa']."<br /><br />".
                      "           Analisamos seu perfil e optamos por alterar sua categoria de usu�rio para ".$categoria_pretendida['nm_categoria_usuario'].". <br />".
                      "           <br />".
                      "           Obrigado, <br /><br />";
        if ($email->notificarAvaliacaoPedidoAlteracaoCategoria($dados['cd_contato'], $texto)) {
          echo "<p class=\"fontConteudoSucesso\">Altera��o de categoria de usu�rio realizada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoSucesso\">Altera��o de categoria de usu�rio realizada com sucesso!</p>\n";
          echo "<p class=\"fontConteudoAlerta\">Problemas ao notificar usu�rio!</p>\n";
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar categoria de usu�rio!</p>\n";
      }
    }


//**************FICHA CADASTRAL*************************************************
    public function controleExibicaoFichaCadastral($secao, $subsecao, $item, $pagina, $lista_paginas) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }

      if (isset($_SESSION['life_atualizacao_cadastral'])) {
        unset($_SESSION['life_atualizacao_cadastral']);
      }

      echo "<div class=\"divDadosCadastrais\">\n";

      $exibir_home = false;
      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario = $_SESSION['life_codigo'];

        $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item;

        if ($acao == '') {
          echo "<h1>Minha conta</h1>\n";
          $this->atualizaFichaCadastral($link, $cd_usuario);
        } else {
          if (isset($_SESSION['life_edicao'])) {
            echo "<h1>Minha conta</h1>\n";
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          }
          $exibir_home = true;
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Nenhum usu�rio logado!</p>\n";
      }
      echo "</div>\n";
      return $exibir_home;
    }

    public function atualizaFichaCadastral($link, $cd_usuario) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/areas_formacao.php';                              $are_for = new AreaFormacao();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'conteudos/areas_conhecimento_pessoa.php';                   $acp = new AreasConhecimentoPessoa();

      $_SESSION['life_edicao'] = '1';

      $dados = $this->selectDadosPessoaUsuario($cd_usuario);

      $cd_pessoa = $dados['cd_pessoa'];
      $nm_pessoa = $dados['nm_pessoa'];
      $nr_cpf = $dados['nr_cpf'];
      $cd_area_formacao = $dados['cd_area_formacao'];
      $cd_endereco = $dados['cd_endereco'];
      $cd_contato = $dados['cd_contato'];
      $ds_dados_complementares = $dados['ds_dados_complementares'];
      $ds_mini_curriculo = $dados['ds_mini_curriculo'];
      $ds_link_lattes = $dados['ds_link_lattes'];
      $eh_ativo = $dados['eh_ativo'];

      include "js/js_cadastro_atualiza_pessoa.js";

      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."&acao=registrar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";

      $util->campoHidden('cd_pessoa', $cd_pessoa);
      $util->campoHidden('cd_usuario', $cd_usuario);
      $util->campoHidden('eh_ativo', $eh_ativo);

      $util->linhaUmCampoText(1, 'Nome: ', 'nm_pessoa', 100, 100, $nm_pessoa);

      if ($nr_cpf != '') {
        $util->campoHidden('nr_cpf', $nr_cpf);
        $util->linhaDuasColunasComentario('CPF: ', $nr_cpf);
      } else {
        $util->linhaUmCampoTextAcao(1, 'CPF: ', 'nr_cpf', 14, 100, $nr_cpf, " onKeyPress=\"mascaraCpf('nr_cpf')\"; onBlur=\"validaCpf('nr_cpf');\" ");
      }
      $are_for->retornaSeletorAreasFormacao($cd_area_formacao);
      $acp->retornaSeletorAreasConhecimentoPessoa($cd_pessoa);

      $util->linhaComentario('&nbsp;');
      $end->apresentaFormularioEndereco('Endere�o:', $cd_endereco, false);
      $util->linhaComentario('&nbsp;');
      $con->imprimeFormularioContatos('Contatos:', $cd_contato, true, true, true, true, true);
      $util->linhaComentario('&nbsp;');
      $util->linhaTexto('0', 'Dados complementares: ', 'ds_dados_complementares', $ds_dados_complementares, '5', '100');
      $util->linhaTexto('0', 'Mini curr�culo: ', 'ds_mini_curriculo', $ds_mini_curriculo, '5', '100');
      $util->linhaUmCampoText(0, 'Curr�culo Lattes (link): ', 'ds_link_lattes', 150, '100', $ds_link_lattes);
      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigat�rios</p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'nm_pessoa');
    }

    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/areas_conhecimento_pessoa.php';                   $acp = new AreasConhecimentoPessoa();

      $cd_pessoa = addslashes($_POST['cd_pessoa']);
      $cd_usuario = addslashes($_POST['cd_usuario']);
      $nm_pessoa = $util->limparVariavel($_POST['nm_pessoa']);
      $nr_cpf = $util->limparVariavel($_POST['nr_cpf']);
      $cd_area_formacao = addslashes($_POST['cd_area_formacao']);
      $ds_dados_complementares = $util->limparVariavel($_POST['ds_dados_complementares']);
      $ds_mini_curriculo = $util->limparVariavel($_POST['ds_mini_curriculo']);
      $ds_link_lattes = $util->limparVariavel($_POST['ds_link_lattes']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      $cd_endereco = $end->salvarCadastroEdicao();
      $cd_contato = $con->salvarCadastroEdicao();

      $editou_usuario = $usu->alterarUsuario($cd_usuario, '0', $eh_ativo, $nm_pessoa);
      $editou_areas_interesse = $acp->salvarCadastroAlteracao();
      $editou_pessoa = $this->alterarPessoa($cd_pessoa, $nm_pessoa, $nr_cpf, $cd_area_formacao, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo);

      if (($cd_contato > 0) || ($cd_endereco > 0) || ($editou_usuario > 0) || ($editou_pessoa) || ($editou_areas_interesse)) {
        echo "<p class=\"fontConteudoSucesso\">Minha conta editada com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao editar minha conta, ou nenhuma informa��o alterada!</p>\n";
      }
    }


//***************EXIBICAO PUBLICA***********************************************
    public function controleExibicaoPublica($pagina, $lista_paginas) {
      if (isset($lista_paginas[1])) {
        if ($lista_paginas[1] == 'salvar') {
          if (isset($_SESSION['life_edicao'])) {
            if ($this->salvarCadastroProprio()) {
              unset($_SESSION['life_edicao']);
            } else {
              $this->montarFormularioCadastroProprio();
            }
          } else {
            $this->montarFormularioCadastroProprio();
          }
        }
      } else {
        $this->montarFormularioCadastroProprio();
      }
    }
                                        
    public function montarFormularioCadastroProprio() {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      
      echo "<div class=\"divAreaCadastro\">\n";

      echo "  <p style=\"width:97%; text-align:right;\"><img src=\"".$_SESSION['life_link_completo']."icones/fechar_formulario.png\" alt=\"Fechar tela de cadastro\" title=\"Fechar tela de cadastro\" border=\"0\" onMouseOver=\"this.style.cursor='pointer';\" onClick=\"ocultarCadastro();\"></p>\n";

      $eh_mostrar_mensagem_cadastro_login_redes = $conf->exibirMensagemRedesSociaisCadastroUsuario();
      $nm_site = $conf->retornaNomeSite();
      echo "  <h2>Cadastro no Portal ".$nm_site."</h2>\n";
      if ($eh_mostrar_mensagem_cadastro_login_redes == '1') {
        echo "      <div class=\"divCadastroLateralPessoa\">\n";
      } else {
        echo "      <div class=\"divCadastroCentralPessoa\">\n";
      }
      $ds_username = "";
      $ds_senha = "";
      $nm_pessoa = "";
      $nr_cpf = "";

      $_SESSION['life_edicao'] = '1';

      $this->imprimeFormularioCadastroProprioPessoa($ds_username, $ds_senha, $nm_pessoa, $nr_cpf);
      echo "      </div>\n";

      if ($eh_mostrar_mensagem_cadastro_login_redes == '1') {
        require_once 'conteudos/redes_sociais.php';                             $red_soc = new RedeSocial();

        $ds_mensagem_cadastro_login_redes = $conf->retornaMensagemRedesSociaisCadastroUsuario();
        echo "      <div class=\"divMensagemCadastroRedes\">\n";
        echo "      <h1>Ou acesse com sua conta do:</h1>\n";
        echo "      <p class=\"fontComandosCentralizados\" style=\"margin-left:-10px;\">\n";
        echo "        <a href=\"https://www.facebook.com/dialog/oauth?client_id=1711998152372582&redirect_uri=http://www.inversos.com.br/logar.php\"><img src=\"".$_SESSION['life_link_completo']."icones/botao_login_facebook.png\" alt=\"Entrar com usu�rio do Facebook\" title=\"Entrar com usu�rio do Facebook\" border=\"0\"></a>\n";
        echo "        <br /><br />\n";

        include_once("login/src/Google_Client.php");
        include_once("login/src/contrib/Google_Oauth2Service.php");
        ######### edit details ##########
        $clientId = '821906144175-l83ag268duo72am7cb16pkus28044unf.apps.googleusercontent.com'; //Google CLIENT ID
        $clientSecret = 'VVWwg4ZgmG91iXzvkgJWyq11'; //Google CLIENT SECRET
        $redirectUrl = 'http://www.inversos.com.br/loga.php';  //return url (url to script)
        $homeUrl = 'http://www.inversos.com.br';  //return to home

        ##################################

        $gClient = new Google_Client();
        $gClient->setApplicationName('Login to codexworld.com');
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectUrl);

        $google_oauthV2 = new Google_Oauth2Service($gClient);

        $authUrl = $gClient->createAuthUrl();

        echo "        <a href=\"".$authUrl."\"><img src=\"".$_SESSION['life_link_completo']."icones/botao_login_google.png\" alt=\"Entrar com usu�rio do Google\" title=\"Entrar com usu�rio do Google\" border=\"0\"></a>\n";
        echo "      </p>\n";
        echo "      <p class=\"fontConteudoLogin\">".nl2br($ds_mensagem_cadastro_login_redes)."</p>\n";
        echo "    </div>\n";
      }
      echo "</div>\n";
    }
    
    public function imprimeFormularioCadastroProprioPessoa($ds_username, $ds_senha, $nm_pessoa, $nr_cpf) {
      echo "<h1>Fa�a seu cadastro</h1>\n";

      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();


      $nr_letras_maiusculas_senha = $conf->verificarNumeroLetrasMaiusculasSenha();
      $nr_letras_minusculas_senha = $conf->verificarNumeroLetrasMinusculasSenha();
      $nr_numeros_senha = $conf->verificarNumeroNumerosSenha();
      $nr_caracteres_senha = $conf->verificarNumeroCaracteresSenha();

      echo "          <form  name=\"cadastro\" action=\"".$_SESSION['life_link_completo']."cadastre-se/salvar\" method=\"POST\" onSubmit=\"return valida(this);\">\n";
      echo "            <p class=\"fontConteudoCadastroExterno\">\n";
      echo "              <input type=\"hidden\" name=\"validar\" id=\"validar\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"nr_letras_maiusculas_senha\" id=\"nr_letras_maiusculas_senha\" value=\"".$nr_letras_maiusculas_senha."\">\n";
      echo "              <input type=\"hidden\" name=\"nr_letras_minusculas_senha\" id=\"nr_letras_minusculas_senha\" value=\"".$nr_letras_minusculas_senha."\">\n";
      echo "              <input type=\"hidden\" name=\"nr_numeros_senha\" id=\"nr_numeros_senha\" value=\"".$nr_numeros_senha."\">\n";
      echo "              <input type=\"hidden\" name=\"nr_caracteres_senha\" id=\"nr_caracteres_senha\" value=\"".$nr_caracteres_senha."\">\n";
      echo "              <input type=\"hidden\" name=\"eh_username_valido\" id=\"eh_username_valido\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"eh_senha_valido\" id=\"eh_senha_valido\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"eh_nome_valido\" id=\"eh_nome_valido\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"eh_cpf_valido\" id=\"eh_cpf_valido\" value=\"0\">\n";
      echo "              <input type=\"text\" maxlength=\"100\" class=\"fonInputExterno\" name=\"nm_pessoa\" id=\"nm_pessoa\" placeholder=\"Nome completo\" alt=\"Informe seu Nome completo\" title=\"Informe seu Nome completo\" value=\"".$nm_pessoa."\" onKeyPress=\"ehValidar('1'); ehValido('eh_nome_valido', '0');\" onBlur=\"ehNomeCompleto();\"><br />\n";
      echo "              <input type=\"text\" maxlength=\"100\" class=\"fonInputExterno\" name=\"ds_username\" id=\"ds_username\" placeholder=\"E-mail\" alt=\"Informe seu E-mail\" title=\"Informe seu E-mail\" value=\"".$ds_username."\" onKeyPress=\"ehValidar('1'); ehValido('eh_username_valido', '0');\" onBlur=\"ehEmailValido();\"><br />\n";
      $senha = "Infome uma Senha. Ela deve conter no m�nimo ".$nr_caracteres_senha." caracteres, sendo ao menos ".$nr_letras_maiusculas_senha." letra(s) mai�scula(s), ".$nr_letras_minusculas_senha." letra(s) min�scula(s) e ".$nr_numeros_senha." n�mero(s)";
      echo "              <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_senha\" id=\"ds_senha\" placeholder=\"Senha\" alt=\"".$senha."\" title=\"".$senha."\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaSenha();\"><br />\n";
      echo "              <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_confirma_senha\" id=\"ds_confirma_senha\" placeholder=\"Confirma��o da senha\" alt=\"Confirme sua senha\" title=\"Confirme sua senha\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaConfirmacaoSenha();\"><br />\n";
      echo "              <input type=\"text\" maxlength=\"14\" class=\"fonInputExterno\" name=\"nr_cpf\" id=\"nr_cpf\" placeholder=\"CPF\" value=\"".$nr_cpf."\" alt=\"Informe seu CPF\" title=\"Informe seu CPF\" onKeyPress=\"ehValidar('1'); ehValido('eh_cpf_valido', '0'); mascaraCpf('nr_cpf')\"; onBlur=\"validaCpf('nr_cpf');\"><br />\n";
      echo "  		        <input type=\"submit\" class=\"botao\" value=\"Cadastre-me\">\n";
      echo "            </p>\n";
      echo "          </form>\n";
      $util->posicionarCursor('cadastro', 'nm_pessoa');    
    }

    public function retornoCadastro() {
      echo "  <div class=\"divRetornoCadastroUsuario\">\n";
      echo "    <div class=\"divConteudoRetornoCadastroUsuario\">\n";
      if (isset($_SESSION['life_cadastro_proprio_usuario_sucesso'])) {
        require_once 'includes/configuracoes.php';                              $conf = new Configuracao();
        echo $_SESSION['life_cadastro_proprio_usuario_sucesso'];
        echo "<br />\n";
        unset($_SESSION['life_cadastro_proprio_usuario_sucesso']);
        $mensagem = $conf->retornaMensagemBoasVindas();
        if ($mensagem != '') {
          echo "<p style=\"text-align:center;\">".nl2br($mensagem)."</p>\n";
        } else {
          echo "<br />\n";
        }
        echo "<br />\n";
        echo "    <p class=\"fontConteudoLogin\" style=\"text-align:center;\">\n";
        echo "      Deseja fazer o cadastro completo?";
        echo "    </p>\n";
        echo "<br />\n";
        echo "    <p class=\"fontComandosCentralizados\">\n";
        echo "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=17&sub=20&it=32\"><input type=\"button\" class=\"botao\" value=\"Sim\" alt=\"Realizar cadastro completo agora\" title=\"Realizar cadastro completo agora\" border=\"0\"></a>\n";
        echo "      <img src=\"".$_SESSION['life_link_completo']."icones/espacador.png\" border=\"0\" style=\"width:100px;height:1px;\">\n";
        echo "      <a href=\"".$_SESSION['life_link_completo']."\"><input type=\"button\" class=\"botao\" value=\"Mais tarde\" alt=\"Realizar cadastro completo mais tarde\" title=\"Realizar cadastro completo mais tarde\" border=\"0\"></a>\n";
        echo "    </p>\n";
      } elseif (isset($_SESSION['life_cadastro_proprio_usuario_erro'])) {
        echo $_SESSION['life_cadastro_proprio_usuario_erro'];
        unset($_SESSION['life_cadastro_proprio_usuario_erro']);
      }
      echo "    </div>\n";
      echo "  </div>\n";
    }

    private function salvarCadastroProprio() {
      require_once 'includes/utilitarios.php';                                $util = new Utilitario();
      require_once 'conteudos/contatos.php';                                  $con = new Contato();
      require_once 'conteudos/enderecos.php';                                 $end = new Endereco();
      require_once 'usuarios/usuarios.php';                                   $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                       $cat_usu = new CategoriaUsuario();
      require_once 'login/login.php';                                         $login = new Login();
    
      $ds_username = $util->limparVariavel($_POST['ds_username']);
      $nm_pessoa = $util->limparVariavel($_POST['nm_pessoa']);
      $nr_cpf = $util->limparVariavel($_POST['nr_cpf']);
      if ($usu->existeUsername($ds_username)) {
        $dados = $usu->selectDadosUsuarioUsername($ds_username);
        if ($dados['nm_usuario'] == $nm_pessoa) {
          echo "<p class=\"fontConteudoAlerta\">J� existe um usu�rio com este nome e e-mail cadastrado no sistema!</p>\n";
        }
        echo "<p class=\"fontConteudoAlerta\">Esqueceu a sua senha! Utilize o formul�rio abaixo para recuper�-la!</p>\n";
        $this->imprimeFormularioEsqueceuSenha($ds_username);
        echo "<p class=\"fontConteudoAlerta\">O usu�rio cadastrado n�o � seu? Reinicie o cadastro com um e-mail pr�prio!</p>\n";
        $this->imprimeFormularioCadastroProprioPessoa($ds_username, '', $nm_pessoa, $nr_cpf);
        echo "    <script>\n";
        echo "      <!--\n";
        echo "        ehValido('eh_nome_valido', '1');\n";
        echo "        ehValido('eh_cpf_valido', '1');\n";
        echo "      -->\n";
        echo "    </script>\n";
        return true;
      }
    }

    public function salvarCadastroProprioDireto() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat_usu = new CategoriaUsuario();
      require_once 'login/login.php';                                           $login = new Login();

      $ds_username = $util->limparVariavel($_POST['ds_username']);
      $nm_pessoa = $util->limparVariavel($_POST['nm_pessoa']);
      $nr_cpf = $util->limparVariavel($_POST['nr_cpf']);
      if (!$usu->existeUsername($ds_username)) {
        $ds_senha = base64_encode(trim($_POST['ds_senha']));
        $cd_categoria_usuario = $cat_usu->retornaCategoriaUsuarioInicial();
        $dados_categoria = $cat_usu->selectDadosCategoriaUsuario($cd_categoria_usuario);
        $ds_permissoes = $dados_categoria['ds_permissoes_padrao'];
        $dt_ultimo_acesso = date('y-m-d');
        $eh_ativo = '1';
        $nr_tentativas_login = '0';
        $ds_chave = substr(md5(uniqid(time())),0,15).substr(md5(uniqid(time())),0,15);
        $nm_usuario = $nm_pessoa;

        $cd_endereco = $end->inserirEndereco('5508', '', '', '', '', '');
        $cd_contato = $con->insereContato('', '', '', '', '', '', '', '', '', $ds_username, '', '', '', '', '', '');
        $cd_usuario = $usu->inserirUsuario($ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, '0', $cd_categoria_usuario);

        if (($cd_endereco > 0) && ($cd_contato > 0) && ($cd_usuario > 0)) {

          $cd_area_formacao = "0";
          $ds_dados_complementares = "";
          $ds_mini_curriculo = "";
          $ds_link_lattes = "";

          if ($this->inserirPessoa($cd_usuario, $nm_pessoa, $nr_cpf, $cd_area_formacao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo)) {
            $_SESSION['life_cadastro_proprio_usuario_sucesso'] = "<p class=\"fontConteudoSucesso\">Cadastro realizado com sucesso!</p>\n";
            $login->loginAutomaticoUsuario($cd_usuario);
            $this->retornaFuncoesUsuario($cd_categoria_usuario);
            $_SESSION['life_cadastro_proprio_usuario_acao'] = 's';
            return true;
          } else {
            $_SESSION['life_cadastro_proprio_usuario_erro'] = "<p class=\"fontConteudoAlerta\">Problemas ao realizar seu cadastro. Tente novamente e se o erro persistir entre em contato conosco!</p>\n";
            $usu->alteraStatusUsuario($cd_usuario, '0');
            $_SESSION['life_cadastro_proprio_usuario_acao'] = 'a';
            return false;
          }
        }
        $_SESSION['life_cadastro_proprio_usuario_acao'] = 's';
      }
      $_SESSION['life_cadastro_proprio_usuario_acao'] = 'a';
    }
    
    public function retornaFuncoesUsuario($cd_categoria_usuario) {
      require_once 'menu/menu.php';                                             $menu = new Menu();
      $subsecao = '0';
      $item = '0';
      if ($cd_categoria_usuario == '1') {
        $secao = '17';
      } elseif ($cd_categoria_usuario == '2') {
        $secao = '18';
      } elseif ($cd_categoria_usuario == '3') {
        $secao = '19';
      }
      $menu->retornaMenuAdministrativoInterno($secao, $subsecao, $item);
    }
//******************************SENHA*******************************************
    public function controleExibicaoPublicaEsqueceuSenha($pagina, $lista_paginas) {
      if (isset($lista_paginas[1])) {
        if ($lista_paginas[1] == 'enviar') {
          if (isset($_SESSION['life_edicao'])) {
            require_once 'usuarios/usuarios.php';                               $usu = new Usuario();
            if ($usu->lembrarSenha()) {
              unset($_SESSION['life_edicao']);
            } else {
              $this->imprimeFormularioEsqueceuSenha('');
            }
          } else {
            $this->imprimeFormularioEsqueceuSenha('');
          }
        }
      } else {
         $this->imprimeFormularioEsqueceuSenha('');
      } 
    
    }
    

    public function imprimeFormularioEsqueceuSenha($ds_username) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      $nm_site = $conf->retornaNomeSite();
      $_SESSION['life_edicao'] = '1';
      echo "  <div class=\"divEsqueceuSenha\">\n";
      echo "    <h2>Esqueci a senha de acesso ao Portal ".$nm_site."</h2>\n";
      include "js/js_lembrar_senha_proprio.js";
      echo "    <form class=\"fontConteudoLogin\" id=\"logar\" name=\"logar\" method=\"post\" action=\"".$_SESSION['life_link_completo']."esqueceu-a-senha/enviar\" onSubmit=\"return validaEnvioSenha(this);\">\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "        E-mail:<br />\n";
      echo "        <input type=\"text\" maxlength=\"100\" name=\"e_mail_senha\" id=\"e_mail_senha\"  class=\"fonInputExterno\" value=\"".$ds_username."\"><br />\n";
      echo "      </p>\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "  		  <input type=\"submit\" class=\"botao\" value=\"Envie a senha\" alt=\"Envie a senha\" title=\"Envie a senha\">\n";
      //echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      //echo "        <a href=\"".$_SESSION['life_link_completo']."entre\" class=\"fontLinkLogin\">Entre</a>\n";
      echo "      </p>\n";
      echo "  </form>\n";
      echo "  </div>\n";
    }           


              /*
    public function imprimeFormularioDadosCadastraisPessoa() {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      
      $cd_usuario = $_SESSION['life_codigo'];
      $usuario = $usu->selectDadosUsuario($cd_usuario);
      $dados = $this->selectDadosPessoaUsuario($cd_usuario);
      $endereco = $end->selectEnderecoCompleto($dados['cd_endereco']);
      $contato = $con->selectDadosContato($dados['cd_contato']); 
      
      echo "    <div class=\"featured-content\">\n";
      echo "			<div class=\"substrate\">\n";
      echo "				<img src=\"".$_SESSION['life_link_completo']."index_files/site_bg.jpg\" class=\"fullwidth\" alt=\"\">\n";
      echo "			</div>\n";
      echo "      <div class=\"row\">\n";
      echo "				<div class=\"page-title\">\n";
      echo "          <h1 class=\"nomargin\">Atualiza��o Cadastral</h1>\n";
      echo "        </div>\n";
      echo "      </div>\n";
      echo "    </div>\n";
      echo "    <div class=\"main-content\">\n";
      echo "      <div class=\"row\">\n";
      echo "        <div align=\"center\">\n";
      echo "          <h1>Dados Cadastrais</h1>\n";
      include "js/js_cadastro_dados.js";
      echo "          <form class=\"formatted-form\" action=\"".$_SESSION['life_link_completo']."meus-dados/salvar\" method=\"POST\" onSubmit=\"return valida(this);\">\n";
      echo "            <input type=\"hidden\" name=\"eh_cpf_valido\" id=\"eh_cpf_valido\" value=\"1\" />\n";    
      echo "            <input type=\"hidden\" name=\"cd_pessoa\" id=\"cd_pessoa\" value=\"".$dados['cd_pessoa']."\" />\n";
      echo "            <input type=\"hidden\" name=\"cd_usuario\" id=\"cd_usuario\" value=\"".$cd_usuario."\" />\n";    
      echo "            <input type=\"hidden\" name=\"cd_contato\" id=\"cd_contato\" value=\"".$dados['cd_contato']."\" />\n";    
      echo "            <input type=\"hidden\" name=\"cd_endereco\" id=\"cd_endereco\" value=\"".$dados['cd_endereco']."\" />\n";    
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      if (isset($_SESSION['life_retorno_atualizacao'])) {
  	    echo "            <div class=\"message\">".$_SESSION['life_retorno_atualizacao']."</div><br />\n";
        unset($_SESSION['life_retorno_atualizacao']);
      } else {
        echo "            <div class=\"message\"></div>\n";
      } 
      echo "            </div>\n";								
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "              <input type=\"text\" name=\"nome\" id=\"nome\" placeholder=\"Nome\" value=\"".$dados['nm_pessoa']."\">\n";
      echo "            </div>\n";								
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "              <input type=\"text\" name=\"e_mail_cadastro\" id=\"e_mail_cadastro\" disabled placeholder=\"E-mail\" value=\"".$usuario['ds_email']."\">\n";
      echo "            </div>\n";								
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "              <input type=\"text\" name=\"cpf\" id=\"cpf\" placeholder=\"Cpf\" maxlength=\"14\" value=\"".$dados['nr_cpf']."\" onKeyPress=\"mascaraCpf('cpf')\"; onBlur=\"validaCpf('cpf')\";>\n";
      echo "            </div>\n";								
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "               <select name=\"sexo\" id=\"sexo\">\n";
      if ($dados['ds_sexo'] == '') {
        echo "      			    <option selected value=\"\">Sexo</option>\n";
        echo "  			        <option value=\"M\">Masculino</option>\n";
        echo "  			        <option value=\"F\">Feminino</option>\n";
      } elseif ($dados['ds_sexo'] == 'M') {
        echo "  	    		    <option selected value=\"M\">Masculino</option>\n";
        echo "      			    <option value=\"F\">Feminino</option>\n";
      } elseif ($dados['ds_sexo'] == 'F') {
        echo "  			        <option value=\"M\">Masculino</option>\n";
        echo "  			        <option selected value=\"F\">Feminino</option>\n";
      }
      echo "              </select>\n";
      echo "            </div>\n";								
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "              <input type=\"text\" name=\"atuacao\" id=\"atuacao\" placeholder=\"Atua��o/Forma��o\" value=\"".$dados['ds_area_formacao']."\">\n";     
      echo "            </div>\n";								
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "              <input type=\"text\" name=\"cep\" id=\"cep\" placeholder=\"Cep\" maxlength=\"9\" value=\"".$endereco['ds_cep']."\" onKeyPress=\"mascaraCep('cep')\"; onBlur=\"validaCep('cep')\";>\n";
      echo "            </div>\n";								
      if ($endereco['ds_rua'] != '') {
        echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
        echo "              <input type=\"text\" name=\"ds_rua\" id=\"ds_rua\" placeholder=\"Rua\" value=\"".$endereco['ds_rua']."\">\n";     
        echo "            </div>\n";								
        echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
        echo "              <input type=\"text\" name=\"ds_numero\" id=\"ds_numero\" placeholder=\"N�mero\" value=\"".$endereco['ds_numero']."\">\n";     
        echo "            </div>\n";								
        echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
        echo "              <input type=\"text\" name=\"ds_complemento\" id=\"ds_complemento\" placeholder=\"Complemento\" value=\"".$endereco['ds_complemento']."\">\n";     
        echo "            </div>\n";								
        echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
        echo "              <input type=\"text\" name=\"ds_bairro\" id=\"ds_bairro\" placeholder=\"Bairro\" value=\"".$endereco['ds_bairro']."\">\n";     
        echo "            </div>\n";								
        echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
        echo "              <input type=\"text\" name=\"cidade\" id=\"cidade\" disabled placeholder=\"Estado / Cidade\" value=\"".$endereco['sg_estado']." / ".$endereco['nm_cidade']."\">\n"; 
        echo "            <input type=\"hidden\" name=\"cd_cidade\" id=\"cd_cidade\" value=\"".$endereco['cd_cidade']."\" />\n";    
        echo "            </div>\n";								
      } 
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "              <input type=\"text\" name=\"telefone\" id=\"telefone\" placeholder=\"Telefone Celular\" maxlength=\"14\" value=\"".$contato['nr_telefone_celular_01']."\" onKeyPress=\"mascaraTelefone('telefone')\"; onBlur=\"validaTelefone('telefone')\";>\n";
      echo "            </div>\n";								
      echo "            <div class=\"clear\"></div>\n";
      echo "            <div class=\"field-wrapper\" style=\"width:250px;\">\n";
      echo "              <input name=\"botao\" type=\"submit\" class=\"button submit-button left register\" value=\"Atualizar Dados Cadastrais\">\n";
      echo "            </div>\n";			
      echo "          </form>\n";
      echo "        <div class=\"clear\"></div>\n";
      echo "		 	</div>\n";
      echo "    </div>\n";    
    }
    
  
    private function salvarDadosCadastraisPessoa() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/cep.php';                                          $cep = new Cep();

      $cd_pessoa = addslashes($_POST['cd_pessoa']);
      $cd_usuario = addslashes($_POST['cd_usuario']);

      $cd_endereco = addslashes($_POST['cd_endereco']);
      $ds_cep = $util->limparVariavel($_POST['cep']);
      $dados = $cep->recuperaEnderecoCEP($ds_cep);
      
      if (strpos($dados, "falha")) {
        $_SESSION['life_retorno_atualizacao'] = "Cep inv�lido!<br /><br />";
        $cd_cidade = '5508';
        $ds_rua = '';
        $ds_numero = '';
        $ds_complemento = '';
        $ds_bairro = '';
      } else {
        $dados = explode(',', $dados);
        $estado = $dados['5'];
        $estado = str_replace(":","",$estado);
        $estado = str_replace("\"","",$estado);
        $estado = str_replace("uf","",$estado);
        $cd_estado = $end->selectCodigoEstadoSigla($estado);

        $cidade = $dados['6'];
        $cidade = str_replace(":","",$cidade);
        $cidade = str_replace("\"","",$cidade);
        $cidade = str_replace("cidade","",$cidade);
        $cd_cidade = $end->selectCodigoCidadeEstado($cidade, $cd_estado);

        $bairro = $dados['7'];
        $bairro = str_replace(":","",$bairro);
        $bairro = str_replace("\"","",$bairro);
        $ds_bairro = str_replace("bairro","",$bairro);
            
        $logradouro = $dados['9'];
        $logradouro = str_replace(":","",$logradouro);
        $logradouro = str_replace("\"","",$logradouro);
        $ds_rua = str_replace("logradouro","",$logradouro);
      }            
      if (isset($_POST['ds_rua'])) {
        $ds_rua = $util->limparVariavel($_POST['ds_rua']);
        $ds_numero = $util->limparVariavel($_POST['ds_numero']);
        $ds_complemento = $util->limparVariavel($_POST['ds_complemento']);
        $ds_bairro = $util->limparVariavel($_POST['ds_bairro']);
      } else {
        $ds_numero = '';
        $ds_complemento = '';
      }
      $alterou_endereco = $end->alterarEndereco($cd_endereco, $cd_cidade, $ds_rua, $ds_numero, $ds_complemento, $ds_bairro, $ds_cep);

      $cd_contato = addslashes($_POST['cd_contato']);
      $contato = $con->selectDadosContato($cd_contato); 
      $nr_telefone_comercial_01 = $contato['nr_telefone_comercial_01'];
      $nr_telefone_comercial_02 = $contato['nr_telefone_comercial_02'];
      $nr_telefone_comercial_03 = $contato['nr_telefone_comercial_03'];
      $nr_telefone_residencial_01 = $contato['nr_telefone_residencial_01'];
      $nr_telefone_residencial_02 = $contato['nr_telefone_residencial_02'];
      $nr_telefone_residencial_03 = $contato['nr_telefone_residencial_03'];
      $nr_telefone_celular_01 = $util->limparVariavel($_POST['telefone']);
      $nr_telefone_celular_02 = $contato['nr_telefone_celular_02'];
      $nr_telefone_celular_03 = $contato['nr_telefone_celular_03'];
      $ds_email_01 = $contato['ds_email_01'];
      $ds_email_02 = $contato['ds_email_02'];
      $ds_email_03 = $contato['ds_email_03'];
      $ds_skype = $contato['ds_skype'];
      $ds_messenger = $contato['ds_messenger'];
      $ds_gtalk = $contato['ds_gtalk'];

      $alterou_contato = $con->alteraContato($cd_contato, $nr_telefone_comercial_01, $nr_telefone_comercial_02, $nr_telefone_comercial_03, $nr_telefone_residencial_01, $nr_telefone_residencial_02, $nr_telefone_residencial_03, $nr_telefone_celular_01, $nr_telefone_celular_02, $nr_telefone_celular_03, $ds_email_01, $ds_email_02, $ds_email_03, $ds_skype, $ds_messenger, $ds_gtalk);
      
      $dados = $this->selectDadosPessoa($cd_pessoa);
      $nm_pessoa = $util->limparVariavel($_POST['nome']);
      $ds_sexo = addslashes($_POST['sexo']);
      $nr_cpf = $util->limparVariavel($_POST['cpf']);
      $ds_nacionalidade = $dados['ds_nacionalidade'];
      $cd_nivel_escolaridade = $dados['cd_nivel_escolaridade'];
      $ds_area_formacao = $util->limparVariavel($_POST['atuacao']);
      $eh_ativo = '1';

      $lk_seo = $util->retornaLinkSEO($nm_pessoa, 'life_pessoas', 'lk_seo', '150', $cd_pessoa);

      $alterou_pessoa = $this->alterarPessoa($cd_pessoa, $cd_usuario, $nm_pessoa, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $lk_seo);
      
      if (($alterou_endereco) || ($alterou_contato) || ($alterou_pessoa)) {
        $_SESSION['life_cadastro_ds_username'] = $ds_email_01;    
        $_SESSION['life_cadastro_nm_usuario'] = $nome;
        $_SESSION['life_cadastro_ds_email'] = $ds_email_01;      
        return true;
      } else {
        return false;
      }
    }     

                */
//**************BANCO DE DADOS**************************************************
    public function selectPessoas($eh_ativo, $campo, $termo, $inicial, $cd_categoria_usuario) {
      $sql  = "SELECT p.*, u.cd_categoria_usuario, u.cd_origem, c.nm_categoria_usuario  ".
              "FROM life_pessoas p, life_usuarios u, life_categorias_usuarios c ".
              "WHERE p.cd_usuario = u.cd_usuario ".
              "AND u.cd_categoria_usuario = c.cd_categoria_usuario ";
      if ($eh_ativo != 2) {
        $sql.= "AND p.eh_ativo = '$eh_ativo' ";
      }
      if ($termo != '') {
        $termo = '%'.$termo.'%';
        $sql.= "AND ";
        if ($campo == 'N') {
          $sql.= " UPPER(p.nm_pessoa) like UPPER('$termo') ";
        } elseif ($campo == 'C') {
          $sql.= " UPPER(p.nr_cpf) like UPPER('$termo') ";
        } else {
          $sql.= " ( ".
                 "    UPPER(p.nm_pessoa) like UPPER('$termo') ".
                 "    OR UPPER(p.nr_cpf) like UPPER('$termo') ".
                 " ) ";
        }
      } 
      if ($cd_categoria_usuario != '') {
        $sql.= "AND c.cd_categoria_usuario = '$cd_categoria_usuario' ";
      }
      if ($inicial != '0') {
        $inicial.= "%";
        $sql.= " AND UPPER(p.nm_pessoa) like UPPER('$inicial') ";
      }
      $sql.= "ORDER BY p.nm_pessoa";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosPessoa($cd_pessoa) {
      $sql  = "SELECT * ".
              "FROM life_pessoas ".
              "WHERE cd_pessoa = '$cd_pessoa' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
/*
    public function selectDadosPessoaSEO($lk_seo) {
      $sql  = "SELECT * ".
              "FROM life_pessoas ".
              "WHERE lk_seo = '$lk_seo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function selectDadosPessoaChave($ds_chave) {
      $sql  = "SELECT * ".
              "FROM life_pessoas ".
              "WHERE ds_chave = '$ds_chave' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
                                                  */
    public function selectDadosPessoaUsuario($cd_usuario) {
      $sql  = "SELECT * ".
              "FROM life_pessoas ".
              "WHERE cd_usuario = '$cd_usuario' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function inserirPessoa($cd_usuario, $nm_pessoa, $nr_cpf, $cd_area_formacao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_pessoas ".
             "(cd_usuario, nm_pessoa, nr_cpf, cd_area_formacao, cd_endereco, cd_contato, ds_dados_complementares, ds_mini_curriculo, ds_link_lattes, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_usuario\", \"$nm_pessoa\", \"$nr_cpf\", \"$cd_area_formacao\", \"$cd_endereco\", \"$cd_contato\", \"$ds_dados_complementares\", \"$ds_mini_curriculo\", \"$ds_link_lattes\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pessoas');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT cd_pessoa codigo ".
                "FROM life_pessoas ".
                "WHERE cd_usuario = '$cd_usuario' ".
                "AND nm_pessoa = '$nm_pessoa' ".
                "AND nr_cpf = '$nr_cpf' ".
                "AND cd_area_formacao = '$cd_area_formacao' ".
                "AND cd_endereco = '$cd_endereco' ".
                "AND cd_contato = '$cd_contato' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
        $dados = mysql_fetch_assoc($result_id);
        return $dados['codigo'];        
      }
      return $saida;     
    }
                           /*

    private function inserirPessoa($cd_pessoa, $cd_usuario, $nm_pessoa, $nr_cpf, $cd_nivel_educacional, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_pessoas ".
             "(cd_usuario, cd_contato, cd_endereco, nm_pessoa, ds_sexo, nr_cpf, ds_nacionalidade, cd_nivel_escolaridade, ds_area_formacao, eh_ativo, lk_seo, ds_chave, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_usuario\", \"$cd_contato\", \"$cd_endereco\", \"$nm_pessoa\", \"$ds_sexo\", \"$nr_cpf\", \"$ds_nacionalidade\", \"$cd_nivel_escolaridade\", \"$ds_area_formacao\", \"$eh_ativo\", \"$lk_seo\", \"$ds_chave\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pessoas');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT cd_pessoa codigo ".
                "FROM life_pessoas ".
                "WHERE nm_pessoa = '$nm_pessoa' ".
                "AND ds_sexo = '$ds_sexo' ".
                "AND cd_usuario = '$cd_usuario' ".
                "AND cd_contato = '$cd_contato' ".
                "AND cd_endereco = '$cd_endereco' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];        
      }
      return $saida;     
    }
                                   */
    private function alterarPessoa($cd_pessoa, $nm_pessoa, $nr_cpf, $cd_area_formacao, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_pessoas SET ".
             "nm_pessoa = \"$nm_pessoa\", ".
             "nr_cpf = \"$nr_cpf\", ".
             "cd_area_formacao = \"$cd_area_formacao\", ".
             "ds_dados_complementares = \"$ds_dados_complementares\", ".
             "ds_mini_curriculo = \"$ds_mini_curriculo\", ".
             "ds_link_lattes = \"$ds_link_lattes\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "eh_ficha_atualizada = \"1\", ".
             "eh_informado_atualizacao = \"1\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_pessoa = '$cd_pessoa' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pessoas');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

    private function registrarNecessidadeAtualizacao() {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_pessoas SET ".
             "eh_ficha_atualizada = \"0\", ".
             "eh_informado_atualizacao = \"0\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_pessoa > '0' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pessoas');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $saida = mysql_affected_rows();
      return $saida;
    }

    private function registrarNotificadoAtualizacao() {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_pessoas SET ".
             "eh_informado_atualizacao = \"1\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_pessoa > '0' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pessoas');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $saida = mysql_affected_rows();
      return $saida;
    }

  }
?>