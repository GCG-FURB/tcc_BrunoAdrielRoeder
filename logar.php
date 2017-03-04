<?php
session_start();
  include_once "includes/conectar.php";
  require_once 'includes/configuracoes.php';                                    $conf= new Configuracao();

  require_once 'login/login.php';                                               $login = new Login();

  require_once 'usuarios/usuarios.php';                                         $usu = new Usuario();

  require_once 'menu/menu.php';                                                 $menu = new Menu();

  require_once 'conteudos/contatos.php';                                        $con = new Contato();
  require_once 'conteudos/enderecos.php';                                       $end = new Endereco();
  require_once 'conteudos/categorias_usuarios.php';                             $cat_usu = new CategoriaUsuario();
  require_once 'conteudos/pessoas.php';                                         $pes = new Pessoa();

  if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])){
    // Informe o seu App ID abaixo
    $appId = '1711998152372582';
    // Digite o App Secret do seu aplicativo abaixo:
    $appSecret = '019dec5759cfba7019597f6a51108aea';
    // Url informada no campo "Site URL"
    $redirectUri = urlencode('http://www.inversos.com.br/logar.php');
    // Obtém o código da query string
    $code = $_GET['code'];
    // Monta a url para obter o token de acesso e assim obter os dados do usuário
    $token_url = "https://graph.facebook.com/oauth/access_token?"."client_id=".$appId."&redirect_uri=".$redirectUri."&client_secret=".$appSecret."&code=".$code;

    //pega os dados
    $response = @file_get_contents($token_url);
    if($response){
      $params = null;
      parse_str($response, $params);
      if(isset($params['access_token']) && $params['access_token']){
        $graph_url = "https://graph.facebook.com/me?access_token=".$params['access_token'];
        $user = json_decode(file_get_contents($graph_url));

        $ds_username = "F".$user->id;
        $nm_pessoa = $user->name;
        $nr_cpf = '';
        if (!$usu->existeUsername($ds_username)) {
          echo "<br />\n";
          $ds_senha = '';
          $cd_categoria_usuario = $cat_usu->retornaCategoriaUsuarioInicial();
          $dados_categoria = $cat_usu->selectDadosCategoriaUsuario($cd_categoria_usuario);
          $ds_permissoes = $dados_categoria['ds_permissoes_padrao'];
          $dt_ultimo_acesso = date('y-m-d');
          $eh_ativo = '1';
          $nr_tentativas_login = '0';
          $ds_chave = substr(md5(uniqid(time())),0,15).substr(md5(uniqid(time())),0,15);
          $nm_usuario = $nm_pessoa;

          $cd_endereco = $end->inserirEndereco('5508', '', '', '', '', '');
          $cd_contato = $con->insereContato('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
          $cd_usuario = $usu->inserirUsuario($ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, '1', $cd_categoria_usuario);

          if (($cd_endereco > 0) && ($cd_contato > 0) && ($cd_usuario > 0)) {

            $cd_nivel_instrucao = "0";
            $ds_dados_complementares = "";
            $ds_mini_curriculo = "";
            $ds_link_lattes = "";

            if ($pes->inserirPessoa($cd_usuario, $nm_pessoa, $nr_cpf, $cd_nivel_instrucao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo)) {
              $_SESSION['life_cadastro_proprio_usuario'] = "<p class=\"fontConteudoSucesso\">Conexão realizada com sucesso!</p>\n";
              if ($login->loginAutomaticoUsuario($cd_usuario)) {
                $pes->retornaFuncoesUsuario($cd_categoria_usuario);
//                return true;
              } else {
                $_SESSION['life_cadastro_proprio_usuario'] = "<p class=\"fontConteudoSucesso\">Usuário bloqueado!</p>\n";
//                return false;
              }
            } else {
              $_SESSION['life_cadastro_proprio_usuario'] = "<p class=\"fontConteudoAlerta\">Problemas ao realizar sua conexão. Tente novamente e se o erro persistir entre em contato conosco!</p>\n";
              $usu->alteraStatusUsuario($cd_usuario, '0');
//              return false;
            }
          }
        } else {
          $ds_username = "F".$user->id;
          $dados_usuario = $usu->selectDadosUsuarioUsername($ds_username);
          if (!$login->loginAutomaticoUsuario($dados_usuario['cd_usuario'])) {
            $_SESSION['life_cadastro_proprio_usuario'] = "<p class=\"fontConteudoSucesso\">Usuário bloqueado!</p>\n";
          }
        }
      } else {
        $_SESSION['life_cadastro_proprio_usuario'] = "<p class=\"fontConteudoAlerta\">Problemas ao realizar sua conexão. Tente novamente e se o erro persistir entre em contato conosco!</p>\n";
      }
    } else {
      $_SESSION['life_cadastro_proprio_usuario'] = "<p class=\"fontConteudoAlerta\">Problemas ao realizar sua conexão. Tente novamente e se o erro persistir entre em contato conosco!</p>\n";
    }
  } elseif($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])) {
    $_SESSION['life_cadastro_proprio_usuario'] = "<p class=\"fontConteudoAlerta\">Conexão não permitida!</p>\n";
  }

  $atual = array();       $atual[0] = 'home';          $pagina = 'home';
  $secao = '0';           $subsecao = '0';             $item = '0';

  $_SESSION['life_link_completo']  = $conf->retornaLinkCompletoAplicacao();

  //para utilizar ajax
  require("sajax/sajax.php");
  include "sajax/ajax_funcoes.php";

  //inclusao de arquivo para montagem do cabecalho
  include "includes/cabecalho.php";
  //criacao do cabecalho
  $cabecalho= new Cabecalho($conf->retornaNomeSite(), $secao, $subsecao, $item, $atual);
  $cabecalho->setLink($_SESSION['life_link_completo']);
  $cabecalho->setDescricao($conf->retornaDescricaoSite());
  $cabecalho->setPalavrasChave($conf->retornaPalavrasChave());
  //inclusao do arquivo de css
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/divisoes.css");
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/fontes.css");
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/tabelas.css");
  $cabecalho->insereArquivoJavaScript('js/funcoes.js');
  //ordem de impressao do cabecalho
  $cabecalho->imprimeCabecalhoHTML($no_index, $nm_site);

  $dados_menu = $menu->retornaDadosMenu($secao, $subsecao, $item);
  $permissao = array();
  $permissao[0]= $dados_menu['tp_permissao'];
  $permissao[1]= $dados_menu['id_permissao'];

  $cd_menu = '0';

  require_once "html_index.php";                                                $html = new HTML();
  $html->retornaControleConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual, $cd_menu);

  if (!isset($_SESSION['life_tamanho_fonte'])) {      $_SESSION['life_tamanho_fonte'] = 4;     }
  $tamanho = $_SESSION['life_tamanho_fonte'];
  echo "<script>\n";
  echo "mudaTamanho('$tamanho');\n";
  echo "</script>\n";
?>