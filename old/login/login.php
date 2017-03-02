<?php 
  class Login {


    public function __construct() {
    }
    
    public function controleExibicaoPublica($pagina, $lista_paginas) {
      $this->imprimeFormularioLogin();

/*
      if (!isset($lista_paginas['1'])) {
        $this->imprimeFormularioLogin();
      } else {
        $acao = addslashes($lista_paginas['1']);
        if ($acao == 'entrar') {
          $this->imprimeFormularioLogin();
        }
      }
*/
    }

    public function executarProcedimentosLogin() {
      //verificar se campos foram setados e se sao diferentes de vazio
      //testa usuario
      if ((isset($_POST['usuario'])) && ($_POST['usuario'] != '')) {
        //testa senha
        if ((isset($_POST['senha'])) && ($_POST['senha'] != '')) {
          //incluir usuarios
          require_once "usuarios/usuarios.php";                                 $usu= new Usuario();
          //verificar se usuario existe 
          $codigo = $usu->existeUsuario($_POST['usuario']);
          if ($codigo > 0) { 
            //verificar se usuario nao esta bloqueado
            if (!$usu->ehBloqueadoTentativasAcesso($codigo)) {
              //testar se senha e usuario conferem
              if ($this->logar($_POST['usuario'], $_POST['senha'])) {
                $this->cadastroEstaAtualizado($codigo);
                return true;
              }
            } else {
              $_SESSION['life_erro_login']= "Usuário Bloqueado!";
            }
          } else {
            $_SESSION['life_erro_login']= "Username informado não existe!";
          }
        } else {
          $_SESSION['life_erro_login']= "Senha não informada!";
        }   
      } else {
        $_SESSION['life_erro_login']= "Username não informado!";
      }
      return false;
    }

    public function imprimeFormularioLogin() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      $nm_site = $conf->retornaNomeSite();
      

      echo "<div class=\"divAreaLogin\">\n";
      echo "  <p style=\"width:97%; text-align:right;\"><img src=\"".$_SESSION['life_link_completo']."icones/fechar_formulario.png\" alt=\"Fechar tela de login\" title=\"Fechar tela de login\" border=\"0\" onMouseOver=\"this.style.cursor='pointer';\" onClick=\"ocultarLogin();\"></p>\n";
      if (isset($_SESSION['life_erro_login'])) {
        echo "    <p class=\"fontConteudoAlerta\">".$_SESSION['life_erro_login']."</p>\n";
        unset($_SESSION['life_erro_login']);
      }
      echo "  <h2>Acesso ao Portal ".$nm_site."</h2>\n";
      echo "  <div class=\"divLoginAcesso\">\n";
      echo "    <h2>Acesse com sua conta do Portal ".$nm_site.":</h2>\n";
      echo "    <form class=\"fontConteudoLogin\" id=\"logar\" name=\"logar\" method=\"post\" action=\"".$_SESSION['life_link_completo']."entre\">\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "        E-mail:<br />\n";
      echo "        <input type=\"text\" maxlength=\"100\" name=\"usuario\" id=\"usuario\" class=\"fontCampoLogin\" /><br />\n";
      echo "      </p>\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "        Senha:<br />\n";
      echo "        <input type=\"password\" maxlength=\"50\" name=\"senha\" id=\"senha\" size=\"40%\" class=\"fontCampoLogin\" /><br />\n";
      echo "      </p>\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "  		  <input type=\"submit\" class=\"botao\" value=\"Entre\" alt=\"Efetuar Login\" title=\"=\"Efetuar Login\">\n";
      echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      echo "        <a href=\"".$_SESSION['life_link_completo']."esqueceu-a-senha\" class=\"fontLinkLogin\">Esqueci a senha</a>\n";
      echo "      </p>\n";
      echo "  </form>\n";
      echo "  </div>\n";

      echo "  <div class=\"divLoginRedesSociais\">\n";
      echo "    <h2>Acesse com sua conta do:</h2>\n";

      echo "    <br /><br /><br />\n";
      echo "    <p class=\"fontComandosCentralizados\">\n";
      echo "    <a href=\"https://www.facebook.com/dialog/oauth?client_id=1711998152372582&redirect_uri=http://www.inversos.com.br/logar.php\"><img src=\"".$_SESSION['life_link_completo']."icones/botao_login_facebook.png\" alt=\"Entrar com usuário do Facebook\" title=\"Entrar com usuário do Facebook\" border=\"0\"></a>\n";
      echo "    <img src=\"".$_SESSION['life_link_completo']."icones/espacador.png\" style=\"width:70px;\">\n";

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

      echo "    <a href=\"".$authUrl."\"><img src=\"".$_SESSION['life_link_completo']."icones/botao_login_google.png\" alt=\"Entrar com usuário do Google\" title=\"Entrar com usuário do Google\" border=\"0\"></a>\n";
      echo "    </p>\n";
      echo "    <br /><br /><br />\n";
      echo "    <p class=\"fontConteudoLogin\">\n";
      echo "      ".nl2br($conf->retornaMensagemDadosArmazenadosLogin())."\n";
      echo "    </p>\n";

      echo "  </div>\n";

      echo "  <div class=\"divLoginCadastro\">\n";
      echo "    <h2>Não possui conta no Portal ".$nm_site."?</h2>\n";
      echo "    <p class=\"fontConteudoLogin\">\n";
      echo "      Utilize seu usuário e senha de uma das redes sociais ao lado, ou ";
      echo "      <a href=\"".$_SESSION['life_link_completo']."cadastre-se\" class=\"fontLinkLogin\">cadastre-se</a>!\n";
      echo "    </p>\n";
      echo "  </div>\n";


      echo "</div>\n";
      $util->posicionarCursor('logar', 'usuario');
    }    
    
    public function retornaUsuarioLogado() {
      echo "<div class=\"divDadosUsuarioLogado\">\n";
      echo "  <div class=\"divNomeUsuarioLogado\">\n";
      echo "    <p class=\"fontNomeUsuarioLogado\">\n";
      if ($_SESSION['life_nome'] == '') {
        echo "      ".$_SESSION['life_usuario']."\n";
      } else {
        echo "      ".$_SESSION['life_nome']."\n";
      }
      echo "    </p>\n";
      echo "  </div>\n";
      echo "  <div class=\"divAcaoUsuarioLogado\">\n";
      echo "    <img src=\"".$_SESSION['life_link_completo']."icones/acesso_dados_logado.png\" alt=\"Gerenciamento de dados do usuário\" title=\"Gerenciamento de dados do usuário\" border=\"0\" onMouseOver=\"this.style.cursor='pointer';\" onClick=\"abrirGerenciamentoUsuario();\">\n";
      echo "  </div>\n";
      echo "</div>\n";

      /*
      echo "  <p class=\"fontConteudoLogado\">\n";
      if ($_SESSION['life_nome'] == '') {
        echo "    Usuário ".$_SESSION['life_usuario']."\n";
      } else {
        echo "    Olá ".$_SESSION['life_nome']."\n";
      }
      echo "     - \n";
      echo "    <a href=\"".$_SESSION['life_link_completo']."sair.php\" class=\"fontLinkLogin\">Sair</a>\n";
      */
      echo "  </p>\n";
    }    
                                    /*
    //testa se esta logado, retornando se deve pesquisar itens publicos ou privados de menu
    public function menuPublico() {
      //ver se ja logou
      if (isset($_SESSION['life_logado'])) {
        //se ja logou - ver se esta logado
        if ($_SESSION['life_logado'] == 'S') {
          return 'N';
        } else {
          return 'S';        
        }
      } else {
        return 'S';
      }    
    }
    
*/
    public function cadastroEstaAtualizado($cd_usuario) {
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();

      $dados = $pes->selectDadosPessoaUsuario($cd_usuario);

      if ($dados['eh_ficha_atualizada'] ==  '0') {
        $_SESSION['life_atualizacao_cadastral'] = "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=17&sub=20&it=32\"><img src=\"".$_SESSION['life_link_completo']."icones/informe_atualizacao.png\" alt=\"Atualize sua Ficha Cadastral\" title=\"Atualize sua Ficha Cadastral\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;\n".
                                                  "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=17&sub=20&it=32\" class=\"fontLinkMenuDadosLogin\" alt=\"Atualize sua Ficha Cadastral\" title=\"Atualize sua Ficha Cadastral\">Atualize seus dados</a><br />\n";
      }
    }

    public function estaLogado() {
      if (isset($_SESSION['life_codigo'])) {
        require_once 'usuarios/usuarios.php';                                   $usu = new Usuario();
        $dados_usuario= $usu->selectDadosUsuario($_SESSION['life_codigo']);
        $_SESSION['life_permissoes']= $dados_usuario['ds_permissoes']; 
        $_SESSION['life_categoria']= $dados_usuario['cd_categoria_usuario']; 
        return true;
      } else {
        return false;        
      }
    }
/*
    //fechar area administrativa
    private function deslogar() {
      $_SESSION['life_logado']= '';
      session_destroy();
      session_start();
      header("Location: index.php");
    }
    
    //verificar autorizacao login
    public function checaLogin() {
      if(!$this->estaLogado()) {
        // Usuário não logado! Redireciona para a página de inicial
        header("Location: index.php");
        exit;
      }
    }
    
    public function ehBloqueadoTentativasAcesso($codigo) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'usuarios/usuarios.php';                                     $usuario= new Usuario();
      $dados_usuario= $usuario->selectDadosUsuario($codigo);
      $nr_tentativas= $conf->verificarNumeroMaximoTentativasLogin();
      if ($dados_usuario['nr_tentativas_login'] > $nr_tentativas) {
        return true;
      } else {
        return false;
      }
    }
                                  */
    public function loginAutomaticoUsuario($cd_usuario) {
      require_once 'usuarios/usuarios.php';                                     $usu= new Usuario();
      
      $dados_usuario = $usu->selectDadosUsuario($cd_usuario);
      if ($dados_usuario['eh_ativo'] == '1') {
        $_SESSION['life_logado'] = 'S';
        $_SESSION['life_usuario'] = $dados_usuario['ds_username'];
        $nome = explode(" ", $dados_usuario['nm_usuario']);
        $_SESSION['life_nome'] = $nome[0];
        $_SESSION['life_codigo'] = $cd_usuario;
        $_SESSION['life_permissoes'] = $dados_usuario['ds_permissoes'];
        $_SESSION['life_ultimo_acesso'] = $dados_usuario['dt_ultimo_acesso'];
        $_SESSION['life_categoria'] = $dados_usuario['cd_categoria_usuario'];
        $usu->atualizarDataUltimoAcesso($cd_usuario);
        //registrar acesso com senha válida
        $this->registrarAcesso($cd_usuario, 1);
        return true;
      } else {
        return false;
      }
    }                                  
                                  
    public function logar($username, $senha) {
      require_once 'usuarios/usuarios.php';                                     $usuario= new Usuario();

      $codigo = $usuario->existeUsuario($username);
      $senha = base64_encode(trim($senha));
      if ($usuario->comparaUsuarioSenha($username, $senha)) {
        $dados_usuario = $usuario->selectDadosUsuario($codigo);
        if ($dados_usuario['eh_ativo'] == 1) {
          $_SESSION['life_logado'] = 'S';
          $_SESSION['life_usuario'] = $dados_usuario['ds_username'];
          $nome = explode(" ", $dados_usuario['nm_usuario']);
          $_SESSION['life_nome'] = $nome[0];
          $_SESSION['life_codigo'] = $codigo;
          $_SESSION['life_permissoes'] = $dados_usuario['ds_permissoes']; 
          $_SESSION['life_ultimo_acesso'] = $dados_usuario['dt_ultimo_acesso'];
          $_SESSION['life_categoria']= $dados_usuario['cd_categoria_usuario'];
          $usuario->atualizarDataUltimoAcesso($codigo);
          //registrar acesso com senha válida
          $this->registrarAcesso($codigo, 1);
          return true;
        } else {
          $_SESSION['life_erro_login']= "Usuário Inativo!";
          return false;
        }      
      } else {
        $usuario->atualizarNumeroTentativas($codigo);
        $this->registrarAcesso($codigo, 0);
        $_SESSION['life_erro_login']= "Senha incorreta!";
        return false;
      }                      
    }

//********************BANCO DE DADOS********************************************

    public function registrarAcesso($codigo, $valido) {
      $ip= $_SERVER['REMOTE_ADDR'];
      $sql  = "INSERT INTO life_acessos ".
              "(cd_usuario, dt_acesso, ip_maquina, eh_valido) ".
              "VALUES ".
              "('$codigo', now(), '$ip', '$valido')";              
      @mysql_query($sql) or die ("Erro no banco de dados - TABELA ACESSOS!");
      $saida = mysql_affected_rows();
      return $saida;
    } 
        
  }
?>
