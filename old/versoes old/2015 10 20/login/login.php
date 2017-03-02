<?php 
  class Login {


    public function __construct() {
    }
    
    public function controleExibicaoPublica($pagina, $lista_paginas) {
      if (!isset($lista_paginas['1'])) {
        $this->imprimeFormularioLogin();
      } else {
        $acao = addslashes($lista_paginas['1']);
        if ($acao == 'entrar') {
/*          if ($this->executarProcedimentosLogin()) {
          
          } else {*/
            $this->imprimeFormularioLogin();
//          }
        }
      
      }
    }
/*    
    //funcao para montar o conteudo de login
    public function montarLogin() {
      //testar se ja esta logado
      if ($this->estaLogado()) {
        //testar se e para deslogar
        if (isset($_POST['deslogar'])) {
          //deslogar
          $this->deslogar();
        } else {
          //se esta logado, mostrar dados do login
          $this->imprimeDadosLogin();
        }        
      } else {
        //imprimir formulario de login
        $this->imprimeFormularioLogin();
      }
    }

              */
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
      
      if (isset($_SESSION['life_erro_login'])) {
        echo "    <p class=\"fontConteudoAlerta\">".$_SESSION['life_erro_login']."</p>\n";
        unset($_SESSION['life_erro_login']);
      }

      echo "<div class=\"divAreaLogin\">\n";
      echo "  <h2>Acesso ao Portal ".$nm_site."</h2>\n";
      echo "  <div class=\"divLoginAcesso\">\n";
      echo "    <h2>Login com usuário do Portal ".$nm_site.":</h2>\n";
      echo "    <form class=\"fontConteudoLogin\" id=\"logar\" name=\"logar\" method=\"post\" action=\"".$_SESSION['life_link_completo']."logar/entrar\">\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "        E-mail:<br />\n";
      echo "        <input type=\"text\" maxlength=\"100\" name=\"usuario\" id=\"usuario\" class=\"fontCampoLogin\" /><br />\n";
      echo "      </p>\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "        Senha:<br />\n";
      echo "        <input type=\"password\" maxlength=\"50\" name=\"senha\" id=\"senha\" size=\"40%\" class=\"fontCampoLogin\" /><br />\n";
      echo "      </p>\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "  		  <input type=\"submit\" class=\"botao\" value=\"Logar\" alt=\"Efetuar Login\" title=\"=\"Efetuar Login\">\n";
      echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      echo "        <a href=\"".$_SESSION['life_link_completo']."esqueceu-a-senha\" class=\"fontLinkLogin\">Esqueceu a Senha</a>\n";
      echo "      </p>\n";
      echo "  </form>\n";
      echo "  </div>\n";

      echo "  <div class=\"divLoginRedesSociais\">\n";
      echo "    <h2>Fazer Login com:</h2>\n";

      echo "  </div>\n";

      echo "  <div class=\"divLoginCadastro\">\n";
      echo "    <h2>Não possui Conta no Portal ".$nm_site."?</h2>\n";
      echo "    <p class=\"fontConteudoLogin\">\n";
      echo "      Utilize seu usuário e senha de uma das redes sociais ao lado, ou ";
      echo "      <a href=\"".$_SESSION['life_link_completo']."cadastrar\" class=\"fontLinkLogin\">cadastre-se</a>!\n";
      echo "    </p>\n";
      echo "  </div>\n";


      echo "</div>\n";
      $util->posicionarCursor('logar', 'usuario');
      
    }    
    
                /*
    //imprime dados de login        
    public function imprimeDadosLogin() {
      if ($this->estaLogado()) {
        $saida = "";
        $saida.= "<div class=\"divLogin\">\n";
        $saida.= "  <h2>Dados do Usuário Logado:</h2>\n";
        $saida.= "  <p class=\"fontConteudoCentralizado\">Usuário: ".$_SESSION['life_nome']."</p>\n";
        $saida.= "<br /><br />\n";
        $saida.= "  <p class=\"fontConteudoCentralizado\"><a href=\"sair.php?acao=deslogar\"><img src=\"icones/logof.png\" alt=\"Efetuar Logof - Sair\" title=\"Efetuar Logof - Sair\" border=\"0\"></a></p>\n";
        $saida.= "<br /><br />\n";
        require_once 'includes/contador.php';                                       $cont = new Contador();
        $saida.= $cont->imprimeContador();
        $saida.= "</div>\n";
        echo $saida;
      }	
    }    
                                  */
    public function retornaUsuarioLogado() {
      echo "  <p class=\"fontConteudoLogado\">\n";
      if ($_SESSION['life_nome'] == '') {
        echo "    Usuário ".$_SESSION['life_usuario']."\n";
      } else {
        echo "    Olá ".$_SESSION['life_nome']."\n";
      }
      echo "     - \n";
      echo "    <a href=\"".$_SESSION['life_link_completo']."sair.php\" class=\"fontLinkLogin\">Sair</a>\n";
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
      $_SESSION['life_logado'] = 'S';
      $_SESSION['life_usuario'] = $dados_usuario['ds_username'];
      $_SESSION['life_nome'] = $dados_usuario['nm_usuario'];
      $_SESSION['life_codigo'] = $cd_usuario;
      $_SESSION['life_permissoes'] = $dados_usuario['ds_permissoes']; 
      $_SESSION['life_ultimo_acesso'] = $dados_usuario['dt_ultimo_acesso'];
      $_SESSION['life_categoria'] = $dados_usuario['cd_categoria_usuario'];
      $usu->atualizarDataUltimoAcesso($cd_usuario);
      //registrar acesso com senha válida
      $this->registrarAcesso($cd_usuario, 1);
      return true;
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
          $_SESSION['life_nome'] = $dados_usuario['nm_usuario'];
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
