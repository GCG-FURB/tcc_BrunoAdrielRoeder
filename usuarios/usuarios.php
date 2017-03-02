<?php
  class Usuario {

    //construtor
    public function __construct() {

    }
    
    public function controleExibicaoAlteracaoSenha($pagina, $lista_paginas) {
      if (isset($lista_paginas[3])) {
        $acao = addslashes($lista_paginas[3]);
        if ($acao == 'salvar') {
          $this->alterarSenha();
        } 
      }
      $this->formularioAlteracaoSenha($pagina, $lista_paginas);
    }
                                 
    
    public function formularioAlteracaoSenha($pagina, $lista_paginas) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
    
      echo "  <h2>Alteração de senha</h2>\n";
      
      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario= $_SESSION['life_codigo'];
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Não há usuário logado!</p>\n";
        return false;
      }
      
      $dados_usuario= $this->selectDadosUsuario($cd_usuario);
    
      $ds_username = $dados_usuario['ds_username'];
      $ds_senha = $dados_usuario['ds_senha'];
    
      include 'js/js_altera_senha.js';
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro_senha\" action=\"".$_SESSION['life_link_completo'].$lista_paginas[0]."/".$lista_paginas[1]."/".$lista_paginas[2]."/salvar\" onSubmit=\"return valida(this);\">\n";
      $util->campoHidden('eh_form', '1');

      echo "    <p class=\"fontConteudoCadastroExterno\">\n";
      echo "      <input type=\"hidden\" name=\"validar\" id=\"validar\" value=\"0\">\n";
      echo "      <input type=\"hidden\" name=\"nr_letras_maiusculas_senha\" id=\"nr_letras_maiusculas_senha\" value=\"".$conf->verificarNumeroLetrasMaiusculasSenha()."\">\n";      
      echo "      <input type=\"hidden\" name=\"nr_letras_minusculas_senha\" id=\"nr_letras_minusculas_senha\" value=\"".$conf->verificarNumeroLetrasMinusculasSenha()."\">\n";
      echo "      <input type=\"hidden\" name=\"nr_numeros_senha\" id=\"nr_numeros_senha\" value=\"".$conf->verificarNumeroNumerosSenha()."\">\n";
      echo "      <input type=\"hidden\" name=\"nr_caracteres_senha\" id=\"nr_caracteres_senha\" value=\"".$conf->verificarNumeroCaracteresSenha()."\">\n";
      echo "      <input type=\"hidden\" name=\"eh_senha_valido\" id=\"eh_senha_valido\" value=\"0\">\n";
      echo "      <input type=\"text\" disabled maxlength=\"100\" class=\"fonInputExterno\" name=\"ds_username\" id=\"ds_username\" placeholder=\"E-mail\" value=\"".$ds_username."\"><br />\n";
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_senha_atual\" id=\"ds_senha_atual\" placeholder=\"Senha atual\" value=\"\"/><br />\n";
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_senha\" id=\"ds_senha\" placeholder=\"Senha nova\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaSenha();\"><br />\n";
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_confirma_senha\" id=\"ds_confirma_senha\" placeholder=\"Confirmação da senha\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaConfirmacaoSenha();\"><br />\n";
      echo "      <input type=\"button\" class=\"botao\" value=\"Altere a senha\" onClick=\"valida_senha(cadastro_senha);\">\n";
      echo "    </p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'ds_senha_atual');
    }
    
    public function alterarSenha() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario= $_SESSION['life_codigo'];
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Não há usuário logado!</p>\n";
        return false;
      }

      $ds_senha_atual = base64_encode(trim($_POST['ds_senha_atual']));
      $ds_senha = base64_encode(trim($_POST['ds_senha']));
      $ds_confirma_senha = base64_encode(trim($_POST['ds_confirma_senha']));

      $dados = $this->selectDadosUsuario($cd_usuario);
      if (!$this->comparaUsuarioSenha($dados['ds_username'], $ds_senha_atual)) {
        echo "  <p class=\"fontConteudoAlerta\">A senha atual não confere com a senha do usuário! Processo cancelado!</p>\n";
        return false;
      }

      if ($ds_senha != $ds_confirma_senha) {
        echo "  <p class=\"fontConteudoAlerta\">Senha diferente de confirmação de senha! Processo cancelado!</p>\n";
        return false;
      }

      if ($ds_senha == $ds_senha_atual) {
        echo "  <p class=\"fontConteudoAlerta\">A nova senha informada é igual a senha atual! Processo cancelado!</p>\n";
        return false;
      }

      //editar usuario
      if ($this->alterarSenhaUsuario($cd_usuario, $ds_senha, '0')) {
        echo "  <p class=\"fontConteudoSucesso\">Senha alterada com sucesso!</p>\n";
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Problemas ao alterar senha, ou nova senha igual a antiga!</p>\n";
      }          
    }                                  

    public function existeUsername($ds_username) {
      $dados = $this->selectDadosUsuarioUsername($ds_username);
      if ($dados['cd_usuario'] != '') {
        return true;
      } else {
        return false;
      }                                                
    }

    public function atualizarNumeroTentativas($codigo) {    
      $dados= $this->selectDadosUsuario($codigo);
      $acessos= $dados['nr_tentativas_login'] + 1;
      $this->atualizaAcessos($codigo, $acessos);
    }    

    public function exibirFormularioPermissoes($permissoes) {

      $lista_permissoes_nivel_01 = $this->selectListaPermissoes('0');
      foreach ($lista_permissoes_nivel_01 as $lp1) {
        echo "  <table class=\"tabConteudo\">\n";
        $style= "linhaOn";
        $this->imprimeStatusPermissao($lp1, $permissoes, $style, '1');

        $lista_permissoes_nivel_02 = $this->selectListaPermissoes($lp1['cd_permissao']);
        foreach ($lista_permissoes_nivel_02 as $lp2) {
          $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
          $this->imprimeStatusPermissao($lp2, $permissoes, $style, '2');

          $lista_permissoes_nivel_03 = $this->selectListaPermissoes($lp2['cd_permissao']);
          foreach ($lista_permissoes_nivel_03 as $lp3) {
            $this->imprimeStatusPermissao($lp3, $permissoes, $style, '3');
          }
        }
        echo "  <br /><br />\n";
      }
      echo "  <table class=\"tabConteudo\">\n";
      echo "    <tr>\n";
      echo "      <td class=\"celConteudo\" style=\"text-align:center;\"><input type=\"submit\" class=\"celConteudoBotao\" value=\"Editar\"></td>\n";
      echo "    </tr>\n";
      echo "  </table>\n";

      echo "  <p class=\"fontConteudoAlerta\">Permissões de níveis secundários e terciários somente serão consideradas no salvamento, se suas permissões de níveis anteriores tiverem sido marcadas!</p>\n";

    }

    private function imprimeStatusPermissao($permissao, $permissoes, $style, $nivel) {
      $posicao = $permissao['nr_posicao'];
      $eh_permitido = $permissoes[$posicao];
      echo "    <tr class=\"".$style."\">\n";
      if ($nivel == '1') {
        echo "      <td class=\"celConteudo\" style=\"width:25px;\">\n";
        echo "      <input type=\"checkbox\" name=\"pos_".$permissao['nr_posicao']."\" ";
        if ($eh_permitido == "1") {          echo " checked=\"true\" ";      }
        echo "value=\"on\" class=\"celConteudo\">\n";
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" colspan=\"3\">".$permissao['ds_permissao']."</td>\n";
      } elseif ($nivel == '2') {
        echo "      <td class=\"celConteudo\" style=\"width:25px;\">&nbsp;</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:25px;\">\n";
        echo "      <input type=\"checkbox\" name=\"pos_".$permissao['nr_posicao']."\" ";
        if ($eh_permitido == "1") {          echo " checked=\"true\" ";      }
        echo "value=\"on\" class=\"celConteudo\">\n";
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" colspan=\"2\">".$permissao['ds_permissao']."</td>\n";
      } elseif ($nivel == '3') {
        echo "      <td class=\"celConteudo\" style=\"width:25px;\">&nbsp;</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:25px;\">&nbsp;</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:25px;\">\n";
        echo "      <input type=\"checkbox\" name=\"pos_".$permissao['nr_posicao']."\" ";
        if ($eh_permitido == "1") {          echo " checked=\"true\" ";      }
        echo "value=\"on\" class=\"celConteudo\">\n";
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">".$permissao['ds_permissao']."</td>\n";
      }
      echo "    </tr>\n";
    }

    public function retornaStringLeituraPermissoes() {
      $saida = array();
      for ($i=0; $i<100; $i++) {
      	$saida[$i] = 0;
      }
      $lista_permissoes_nivel_01 = $this->selectListaPermissoes('0');
      foreach ($lista_permissoes_nivel_01 as $lp1) {
        $posicao = $lp1['nr_posicao'];
        $variavel = 'pos_'.$posicao;

        $lista_permissoes_nivel_02 = $this->selectListaPermissoes($lp1['cd_permissao']);

        if ((isset($_POST[$variavel])) && ($_POST[$variavel])) {
          $saida[$posicao] = '1';

          foreach ($lista_permissoes_nivel_02 as $lp2) {
            $posicao = $lp2['nr_posicao'];
            $variavel = 'pos_'.$posicao;

            $lista_permissoes_nivel_03 = $this->selectListaPermissoes($lp2['cd_permissao']);

            if ((isset($_POST[$variavel])) && ($_POST[$variavel])) {
              $saida[$posicao] = '1';

              foreach ($lista_permissoes_nivel_03 as $lp3) {
                $posicao = $lp3['nr_posicao'];
                $variavel = 'pos_'.$posicao;

                if ((isset($_POST[$variavel])) && ($_POST[$variavel])) {
                  $saida[$posicao] = '1';
                }
              }
            }
          }
        }
      }
      $permissao = '';
      foreach ($saida as $s) {
        $permissao.= $s;
      }
      return $permissao;
    }

    public function ehBloqueadoTentativasAcesso($cd_usuario) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      $dados_usuario = $this->selectDadosUsuario($cd_usuario);
      $nr_tentativas = $conf->verificarNumeroMaximoTentativasLogin();
      if ($dados_usuario['nr_tentativas_login'] > $nr_tentativas) {
        return true;
      } else {
        return false;
      }
    }
    
    public function lembrarSenha() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      
      if (isset($_POST['e_mail_senha'])) {
        $ds_email = $util->limparVariavel($_POST['e_mail_senha']);

        if ($ds_email != '') {
          if ($this->existeUsername($ds_email)) {
            $dados = $this->selectDadosUsuarioUsername($ds_email);
            if ($dados['cd_origem'] == '0') {
              $ds_senha_gerada = $this->geraSenha('6', true, true, true, false);

              $ds_senha = base64_encode($ds_senha_gerada);
              $ds_senha_antiga = $dados['ds_senha'];
              if ($this->alterarSenhaUsuario($dados['cd_usuario'], $ds_senha, '0')) {
                require_once 'conteudos/email.php';                                 $email = new Email();
                if ($email->notificarEsqueceuSenha($dados['nm_usuario'], $ds_email, $ds_senha_gerada)) {
                  echo "<p class=\"fontConteudoSucesso\">Sua senha foi alterada e encaminhada por e-mail!<br />Caso não receba a mensagem, verifique sua caixa de Spam!</p>\n";
                } else {
                  echo "<p class=\"fontConteudoAlerta\">Problemas na alteração da senha e encaminhamento do e-mail!<br />Tente novamente em alguns instantes!</p>\n";
                  $this->alterarSenhaUsuario($dados['cd_usuario'], $ds_senha_antiga, '0');
                }
              }
            } else {
              echo "<p class=\"fontConteudoAlerta\">Sua senha não está cadastrada em nossos servidores, pois utiliza uma Rede Social para acessar sua conta de Usuário!</p>\n";
            }
          } else {
            echo "<p class=\"fontConteudoAlerta\">O E-mail informado não está cadastrado!</p>\n";
          }
        } else {
          echo "<p class=\"fontConteudoAlerta\">E-mail não informado!</p>\n";
        }
      }
    }

    public function gerarSenhaDesbloqueio($cd_usuario) {
      if ($cd_usuario > 0) {
        $dados = $this->selectDadosUsuario($cd_usuario);
        if ($dados['cd_origem'] == '0') {
          $ds_senha_gerada = $this->geraSenha('6', true, true, true, false);
          $ds_senha = base64_encode($ds_senha_gerada);
          $ds_senha_antiga = $dados['ds_senha'];
          if ($this->alterarSenhaUsuario($dados['cd_usuario'], $ds_senha, '1')) {
            require_once 'conteudos/email.php';                                 $email = new Email();
            require_once 'conteudos/contatos.php';                              $con = new Contato();
            $emails = $con->retornaListaEmailsUsuario($cd_usuario);
            if (count($emails) > 0) {
              if ($email->notificarSenhaDesbloqueio($dados['nm_usuario'], $emails, $ds_senha_gerada)) {
                return true;
              } else {
                $this->alterarSenhaUsuario($dados['cd_usuario'], $ds_senha_antiga, '0');
                return false;
              }
            } else {
              $this->alterarSenhaUsuario($dados['cd_usuario'], $ds_senha_antiga, '0');
              return false;
            }
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    
    private function geraSenha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos) {
      // Caracteres de cada tipo
      $lmin = 'abcdefghijklmnopqrstuvwxyz';
      $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $num = '1234567890';
      $simb = '!@#$%*-';
      // Variaveis internas
      $retorno = '';
      $caracteres = '';
      // Agrupamos todos os caracteres que poderão ser utilizados
      if ($minusculas) {      $caracteres .= $lmin;       }
      if ($maiusculas) {      $caracteres .= $lmai;       }
      if ($numeros)    {      $caracteres .= $num;        }
      if ($simbolos)   {      $caracteres .= $simb;       }

      // Calculamos o total de caracteres possíveis
      $len = strlen($caracteres) -1;

      for ($n = 1; $n <= $tamanho; $n++) {
        $retorno .= $caracteres[rand(0, $len)];
      }
      return $retorno;
    }

    public function ajustarCategoriaUsuario($cd_usuario, $cd_categoria_usuario) {
      require_once 'conteudos/categorias_usuarios.php';                         $cat_usu = new CategoriaUsuario();

      $dados_categoria = $cat_usu->selectDadosCategoriaUsuario($cd_categoria_usuario);
      $ds_permissoes = $dados_categoria['ds_permissoes_padrao'];

      if ($this->setarCategoriaPermissao($cd_usuario, $cd_categoria_usuario, $ds_permissoes)) {
        return true;
      } else {
        return false;
      }
    }
                               
//****************************BANCO DE DADOS************************************
    //funcao para ver se existem usuarios com o username indicado
    public function existeUsuario($usuario) {
      $sql = "SELECT * ".
             "FROM life_usuarios ".
             "WHERE ds_username = '$usuario'";                        
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      //associar saida dos dados
      $dados= @mysql_fetch_array($result_id);
      //se o numero de linhas for maior que zero, indica que foi encontrado pelo menos um username igual ao indicado
      if (@mysql_num_rows($result_id) > 0) {
        return $dados['cd_usuario'];
      } else {
        return 0;
      }   
    }    

    public function comparaUsuarioSenha($usuario, $senha) {
      $sql = "SELECT * ".
             "FROM life_usuarios ".
             "WHERE ds_username = '$usuario'";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      //associar saida dos dados
      $dados= @mysql_fetch_array($result_id);
      //comparar senha informada, como senha do banco de dados
      if (!strcmp($senha, $dados['ds_senha'])) {
        return true;
      } else {
        return false;
      }          
    }

    public function selectUsuarios($eh_ativo, $cd_categoria_usuario) {
      $sql  = "SELECT * ".
              "FROM life_usuarios ";
      if ($eh_ativo != '2') {
        $sql.= "WHERE eh_ativo = '$eh_ativo' ";
      }               
      if ($cd_categoria_usuario != '2') {
        $sql.= "WHERE cd_categoria_usuario = '$cd_categoria_usuario' ";
      } 
      $sql.= "ORDER BY nm_usuario";                     
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;                 
    }

    public function selectQuantidadeUsuarios($eh_ativo) {
      $sql  = "SELECT COUNT(cd_usuario) qtd ".
              "FROM life_usuarios ".
              "WHERE eh_ativo = '$eh_ativo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $dados = mysql_fetch_assoc($result_id);
      return $dados['qtd'];
    }

    public function selectDadosUsuario($usuario) {
      $sql  = "SELECT * ".
              "FROM life_usuarios ".
              "WHERE cd_usuario = '$usuario' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $dados= @mysql_fetch_array($result_id);
      return $dados;                 
    }

    public function selectDadosUsuarioUsername($ds_username) {
      $sql  = "SELECT * ".
              "FROM life_usuarios ".
              "WHERE ds_username = '$ds_username' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $dados= @mysql_fetch_array($result_id);
      return $dados;                 
    }

    public function atualizarDataUltimoAcesso($codigo) {
      $hoje = date('Y-m-d');
      $sql  = "UPDATE life_usuarios SET ".
              "dt_ultimo_acesso= '$hoje', ".
              "nr_tentativas_login= '0' ".
              "WHERE cd_usuario= '$codigo'";
      @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;    
    }
                                   
    private function atualizaAcessos($codigo, $acessos) {
      $sql  = "UPDATE life_usuarios SET ".
              "nr_tentativas_login= '$acessos' ".
              "WHERE cd_usuario= '$codigo'"; 
      @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;    
    }
                                   
    public function inserirUsuario($ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, $cd_origem, $cd_categoria_usuario) {
      if (isset($_SESSION['life_codigo'])) {       $cd_usuario_cadastro = $_SESSION['life_codigo'];    } else {      $cd_usuario_cadastro = '';       }
      $dt_cadastro = date('Y-m-d');                                                                                                                   
      $sql  = "INSERT INTO life_usuarios ".
              "(cd_categoria_usuario, ds_username, ds_senha, ds_permissoes, dt_ultimo_acesso, eh_ativo, nr_tentativas_login, ds_chave, nm_usuario, cd_origem, cd_usuario_cadastro, dt_cadastro) ".
              "VALUES ".
              "(\"$cd_categoria_usuario\", \"$ds_username\", \"$ds_senha\", \"$ds_permissoes\", \"$dt_ultimo_acesso\", \"$eh_ativo\", \"$nr_tentativas_login\", \"$ds_chave\", \"$nm_usuario\", \"$cd_origem\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'usuario');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT cd_usuario codigo ".
                "FROM life_usuarios ".
                "WHERE ds_username = '$ds_username' ".
                "AND ds_senha = '$ds_senha' "; 
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
        $dados= @mysql_fetch_array($result_id);
        return $dados['codigo'];            
      }
      return $saida;
    }

    public function alterarUsuario($cd_usuario, $nr_tentativas_login, $eh_ativo, $nm_usuario) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql  = "UPDATE life_usuarios SET ".
              "nr_tentativas_login = \"$nr_tentativas_login\", ".
              "eh_ativo = \"$eh_ativo\", ".
              "nm_usuario = \"$nm_usuario\", ".
              "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
              "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
              "WHERE cd_usuario= '$cd_usuario'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'usuario');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;
    }

    public function alterarSenhaUsuario($cd_usuario, $senha, $eh_desbloquear) {
      $sql  = "UPDATE life_usuarios SET ".
              "eh_desbloquear = \"$eh_desbloquear\", ".
              "ds_senha= \"$senha\" ".
              "WHERE cd_usuario= '$cd_usuario'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'usuario');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;
    }       

    public function setarPermissao($cd_usuario, $ds_permissoes) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_usuarios SET ".
             "ds_permissoes= \"$ds_permissoes\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_usuario= '$cd_usuario'"; 
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'usuario');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;    
    }

    public function setarCategoriaPermissao($cd_usuario, $cd_categoria_usuario, $ds_permissoes) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_usuarios SET ".
             "ds_permissoes= \"$ds_permissoes\", ".
             "cd_categoria_usuario = \"$cd_categoria_usuario\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_usuario= '$cd_usuario'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'usuario');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;
    }

    public function alteraStatusUsuario($cd_usuario, $eh_ativo) {
      $sql  = "UPDATE life_usuarios SET ".
              "eh_ativo= '$eh_ativo' ".
              "WHERE cd_usuario= '$cd_usuario'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'usuario');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;
    }     

//*************************************PERMISSOES
    public function selectListaPermissoes($cd_agrupador) {
      $sql  = "SELECT * ".
              "FROM life_permissoes ".
              "WHERE eh_ativo = '1' ".
              "AND cd_agrupador = '$cd_agrupador' ".
              "ORDER BY nr_ordem ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA PERMISSÕES");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;     
    }
  }
?>