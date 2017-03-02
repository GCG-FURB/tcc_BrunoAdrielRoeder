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
    
      echo "  <h2>Alteração de Senha</h2>\n";  
      
      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario= $_SESSION['life_codigo'];
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Não há usuário Logado!</p>\n";
        return false;
      }
      
      $dados_usuario= $this->selectDadosUsuario($cd_usuario);
    
      $ds_username = $dados_usuario['ds_username'];
      $ds_senha = $dados_usuario['ds_senha'];
    
      include 'js/js_altera_senha.js';
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$lista_paginas[0]."/".$lista_paginas[1]."/".$lista_paginas[2]."/salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <p class=\"fontConteudoCadastroExterno\">\n";
      echo "      <input type=\"hidden\" name=\"validar\" id=\"validar\" value=\"0\">\n";
      echo "      <input type=\"hidden\" name=\"nr_letras_maiusculas_senha\" id=\"nr_letras_maiusculas_senha\" value=\"".$conf->verificarNumeroLetrasMaiusculasSenha()."\">\n";      
      echo "      <input type=\"hidden\" name=\"nr_letras_minusculas_senha\" id=\"nr_letras_minusculas_senha\" value=\"".$conf->verificarNumeroLetrasMinusculasSenha()."\">\n";
      echo "      <input type=\"hidden\" name=\"nr_numeros_senha\" id=\"nr_numeros_senha\" value=\"".$conf->verificarNumeroNumerosSenha()."\">\n";
      echo "      <input type=\"hidden\" name=\"nr_caracteres_senha\" id=\"nr_caracteres_senha\" value=\"".$conf->verificarNumeroCaracteresSenha()."\">\n";
      echo "      <input type=\"hidden\" name=\"eh_senha_valido\" id=\"eh_senha_valido\" value=\"0\">\n";
      echo "      <input type=\"text\" disabled maxlength=\"100\" class=\"fonInputExterno\" name=\"ds_username\" id=\"ds_username\" placeholder=\"E-mail\" value=\"".$ds_username."\"><br />\n";
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_senha\" id=\"ds_senha\" placeholder=\"Senha\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaSenha();\"><br />\n";
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_confirma_senha\" id=\"ds_confirma_senha\" placeholder=\"Confirmação da Senha\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaConfirmacaoSenha();\"><br />\n";
      echo "      <input type=\"submit\" class=\"botao\" value=\"Alterar Senha\">\n";
      echo "    </p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'ds_senha');    
    }
    
    public function alterarSenha() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario= $_SESSION['life_codigo'];
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Não há usuário Logado!</p>\n";
        return false;
      }

      $ds_senha = base64_encode(trim($_POST['ds_senha']));
      $ds_confirma_senha = base64_encode(trim($_POST['ds_confirma_senha']));

      if ($ds_senha != $ds_confirma_senha) {
        echo "  <p class=\"fontConteudoAlerta\">Senha diferente de Confirmação de Senha! Processo cancelado!</p>\n";
        return false;
      }
      //editar usuario
      if ($this->alterarSenhaUsuario($cd_usuario, $ds_senha)) {
        echo "  <p class=\"fontConteudoSucesso\">Senha alterada com sucesso!</p>\n";
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Problemas ao alterar Senha, ou nova senha igual a antiga!</p>\n";
      }          
    }                                  
      /*
//******************************************************************************
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['at']))    {      $ativos = addslashes($_GET['at']);          } else {      $ativos = '1';        }
      
      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $ativos);
          $this->listarItens($secao, $subsecao, $item, $ativos);
        break;

        case "cadastrar":
          $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&acao=salvar";
          $this->montarFormularioCadastro($link);
        break;
        
        case "editar":
          $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&acao=salvar";
          $this->montarFormularioEdicao($codigo, $link);
        break;        

        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&acao=salvar";
            if ($this->salvarCadastroAlteracao($link)) {
              unset($_SESSION['life_edicao']);
              $this->listarAcoes($secao, $subsecao, $item, $ativos);
              $this->listarItens($secao, $subsecao, $item, $ativos);
            }              
          } else {
            $this->listarAcoes($secao, $subsecao, $item, $ativos);
            $this->listarItens($secao, $subsecao, $item, $ativos);
          }          
        break;    

        case "alt_ativ":
          $this->alterarSituacaoAtivo($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativos);
          $this->listarItens($secao, $subsecao, $item, $ativos);
        break;

        case "permissoes":
          $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&acao=salvar_perm";
          $this->montarFormularioPermissoes($codigo, $link);
        break;
        
        case "salvar_perm":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarPermissoesUsuario();
            unset($_SESSION['life_edicao']);
          }
          $this->listarAcoes($secao, $subsecao, $item, $ativos);
          $this->listarItens($secao, $subsecao, $item, $ativos);
        break;
      }      
    } 

    private function listarAcoes($secao, $subsecao, $item, $ativos) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                               $opcao['descricao']= "Ativos";                              $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                               $opcao['descricao']= "Inativos";                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                               $opcao['descricao']= "Ativos/Inativos";                     $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                             $opcao['descricao']= "------------------------------";      $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Usuário\" title=\"Novo Usuário\" height=\"24\" width=\"24\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativos) {
      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 
    
      $usuarios= $this->selectUsuarios($ativos);
      $mensagem= "";
      if ($ativos == 1) {        $mensagem.= "Ativos";      } elseif ($ativos == 0) {        $mensagem.= "Inativos";      }
      echo "  <h2>Usuários ".$mensagem."</h2>\n";
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Usuário:</td>\n";
      echo "      <td class=\"celConteudo\">Username/Cpf:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";      
      echo "    </tr>\n";    
      foreach ($usuarios as $us) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">\n";
        if (strlen($us['nm_usuario']) >= 50)  {          echo substr($us['nm_usuario'],0,47)."...";        } else {          echo $us['nm_usuario'];        }        
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">".$us['ds_username']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&cd=".$us['cd_usuario']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&cd=".$us['cd_usuario']."&acao=alt_ativ\">";
        if ($us['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativos."&cd=".$us['cd_usuario']."&acao=permissoes\"><img src=\"icones/permissoes.png\" alt=\"Permissões do Usuário\" title=\"Permissões do Usuário\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";              
      }
      echo "  </table>\n";
      echo "<br /><br />\n";
    }
    

    
    private function montarFormularioCadastro($link) {
      $cd_usuario = "";
      $ds_username = "";
      $ds_senha = "";
      $ds_permissoes = "00000000000100000000000000000000000000000000000000";
      $dt_ultimo_acesso = date('Y-m-d');
      $eh_ativo = "1";
      $nr_tentativas_login = "0";
      $ds_chave = substr(md5(uniqid(time())),0,15);
      $nm_usuario = "";
      $ds_email = "";
                                  
      $_SESSION['life_edicao']= 1;
      echo "    <h2>Cadastro de Usuários</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_usuario, $ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, $ds_email);
    }

    private function montarFormularioEdicao($cd_usuario, $link) {
      $dados= $this->selectDadosUsuario($cd_usuario);

      $cd_usuario = $dados['cd_usuario'];
      $ds_username = $dados['ds_username'];
      $ds_senha = $dados['ds_senha'];
      $ds_permissoes = $dados['ds_permissoes'];
      $dt_ultimo_acesso = $dados['dt_ultimo_acesso'];
      $eh_ativo = $dados['eh_ativo'];
      $nr_tentativas_login = $dados['nr_tentativas_login'];
      $ds_chave = $dados['ds_chave'];
      $nm_usuario = $dados['nm_usuario'];
      $ds_email = $dados['ds_email'];

      $_SESSION['life_edicao']= 1;
      echo "    <h2>Edição de Usuários</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_usuario, $ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, $ds_email);
    }      

    private function imprimeFormularioCadastro($link, $cd_usuario, $ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, $ds_email) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" height=\"24\" width=\"24\" border=\"0\"></a>\n";
      echo "</p>\n";
            
      include 'js/js_cadastro_usuarios.js';
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_usuario', $cd_usuario);
      $util->campoHidden('ds_senha', $ds_senha);
      $util->campoHidden('ds_permissoes', $ds_permissoes);
      $util->campoHidden('dt_ultimo_acesso', $dt_ultimo_acesso);
      $util->campoHidden('nr_tentativas_login', '0');
      $util->campoHidden('ds_chave', $ds_chave);
      //gerar uma senha e mandar para o e-mail cadastrado
      if ($cd_usuario > 0) {
        $util->linhaUmCampoCheckBox('Reinicializar Senha?', 'eh_alterar_senha', 'alterar', 0);
      }
      $util->linhaUmCampoText(1, 'Nome: ', 'nm_usuario', '100', '70', $nm_usuario);
      $util->linhaUmCampoText(1, 'E-mail: ', 'ds_email', '150', '70', $ds_email);      
      $util->linhaUmCampoText(1, 'Username: ', 'ds_username', '20', '40', $ds_username);
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);

      if ($cd_usuario > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }      
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">";
      echo "      (*) Campos Obrigatórios\n";
      echo "    </p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'nm_usuario'); 
    }    
    
 
    private function salvarCadastroAlteracao($link) {
      require_once 'includes/utilitarios.php';                                 $util= new Utilitario();
      
      $cd_usuario = addslashes($_POST['cd_usuario']);
      $ds_username = $util->limparVariavel($_POST['ds_username']);
      $ds_senha = $util->limparVariavel($_POST['ds_senha']);
      $ds_permissoes = $util->limparVariavel($_POST['ds_permissoes']);
      $dt_ultimo_acesso = addslashes($_POST['dt_ultimo_acesso']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $nr_tentativas_login = addslashes($_POST['nr_tentativas_login']);
      $ds_chave = addslashes($_POST['ds_chave']);
      $nm_usuario = $util->limparVariavel($_POST['nm_usuario']);
      $ds_email = $util->limparVariavel($_POST['ds_email']);
                                                                                                                                                                                                      
      if ($cd_usuario > 0) {
        if (isset($_POST['eh_alterar_senha'])) {        
          $ds_senha= base64_encode(trim($ds_username));
          $alterou_senha = $this->alterarSenhaUsuario($cd_usuario, $ds_senha);
        } else {
          $dados= $this->selectDadosUsuario($cd_usuario);
          $ds_senha= $dados['ds_senha'];
        }
        $alterou_usuario = $this->alterarUsuario($cd_usuario, $ds_username, $ds_senha, $eh_ativo, $nm_usuario, $ds_email);
        if (($alterou_senha > 0) || ($alterou_usuario > 0)) {
          echo "<p class=\"fontConteudoSucesso\">Dados do Usuário editados com sucesso!</p>\n";        
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao editar Dados do Usuário, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->existeUsername($ds_username)) {
          echo "<p class=\"fontConteudoAlerta\">Já havia o cadastro de um Usuário com o Username indicado!<br />Informe outro Username!</p>\n";
          echo "    <h2>Edição de Usuários</h2>\n";
          $this->imprimeFormularioCadastro($link, $cd_usuario, $ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, $ds_email);
          return false;
        } else {
          $ds_senha= base64_encode(trim($ds_username));
          if ($this->inserirUsuario($ds_username, $ds_senha, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave, $nm_usuario, $ds_email, $cd_categoria_usuario)) {
            echo "<p class=\"fontConteudoSucesso\">Usuário cadastrado com sucesso!</p>\n";
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao cadastrar Dados do Usuário!</p>\n";
          }
        }     
      }
      return true;
    }     
                         */
    public function existeUsername($ds_username) {
      $dados = $this->selectDadosUsuarioUsername($ds_username);
      if ($dados['cd_usuario'] != '') {
        return true;
      } else {
        return false;
      }                                                
    }
                           /*

    public function alterarSituacaoAtivo($cd_usuario) {
      $dados= $this->selectDadosUsuario($cd_usuario);

      $eh_ativo = $dados['eh_ativo'];
      
      if ($eh_ativo == 1) {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
      
      if ($this->alteraStatusUsuario($cd_usuario, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Usuário alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar Status do Usuário!</p>\n";
      }
    }
                                           */
    public function atualizarNumeroTentativas($codigo) {    
      $dados= $this->selectDadosUsuario($codigo);
      $acessos= $dados['nr_tentativas_login'] + 1;
      $this->atualizaAcessos($codigo, $acessos);
    }    
                                            /*
    public function montarFormularioPermissoes($cd_usuario, $link) {
      require_once 'includes/utilitarios.php';                                 $util= new Utilitario();
    
      $dados = $this->selectDadosUsuario($cd_usuario);

      $permissoes= $dados['ds_permissoes'];
      $lista_permissoes= $this->selectListaPermissoes();

      echo "    <h2>Permissões do Usuários</h2>\n";
      echo "  <form id=\"cadastro\" method=\"post\" action=\"".$link."\">\n";
      $util->campoHidden('cd_usuario', $cd_usuario);
      echo "  <table class=\"tabConteudo\">\n";
      $style= "linhaOf";
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Usuário: </td>\n";
      echo "      <td class=\"celConteudo\">".$dados['nm_usuario']."</td>\n";
      echo "    </tr>\n";
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" colspan=\"2\">&nbsp;</td>\n";
      echo "    </tr>\n";

      if (count($lista_permissoes) > 0) {
        $style= "linhaOn";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\" style=\"width:15%;\"><b>Individual:</b></td>\n";
        echo "      <td class=\"celConteudo\"><b>Tipo:</b></td>\n";
        echo "    </tr>\n";


        foreach ($lista_permissoes as $lp) {
          $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
          $posicao= $lp['nr_posicao'];
          $this->imprimeStatusPermissao($posicao, $permissoes[$posicao], $lp['ds_permissao'], $style);
        }
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\" colspan=\"2\">&nbsp;</td>\n";
        echo "    </tr>\n";
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\" colspan=\"2\" style=\"text-align:center;\"><input type=\"submit\" class=\"celConteudoBotao\" value=\"Editar\"></td>\n";
        echo "    </tr>\n";        
      } else {
        $util->linhaComentario('Não há permissões cadastradas para esta Categoria de Usuários');
      }
      echo "  </table>\n";
      echo "  </form>\n";         
      $_SESSION['life_edicao'] = '1';   
    }

    private function imprimeStatusPermissao($posicao, $permissao, $acesso, $style) {
      echo "  <tr class=\"".$style."\">\n";
      echo "    <td class=\"celConteudo\" style=\"text-align:center;\">\n";
      echo "      <input type=\"checkbox\" name=\"pos_".$posicao."\" ";
      if ($permissao == "1") {          echo " checked=\"true\" ";      } 
      echo "value=\"on\" class=\"celConteudo\">\n";
      echo "    </td>\n";
      echo "    <td class=\"celConteudo\">".$acesso."</td>\n";
      echo "  </tr>\n";        
    }
                                              
    public function salvarPermissoesUsuario() {
      $cd_usuario= addslashes($_POST['cd_usuario']);
      if ((isset($_POST['pos_0']))   && ($_POST['pos_0']))   { $pos_0 = "1"; }   else { $pos_0 = "0";   }
      if ((isset($_POST['pos_1']))   && ($_POST['pos_1']))   { $pos_1 = "1"; }   else { $pos_1 = "0";   }
      if ((isset($_POST['pos_2']))   && ($_POST['pos_2']))   { $pos_2 = "1"; }   else { $pos_2 = "0";   }
      if ((isset($_POST['pos_3']))   && ($_POST['pos_3']))   { $pos_3 = "1"; }   else { $pos_3 = "0";   }
      if ((isset($_POST['pos_4']))   && ($_POST['pos_4']))   { $pos_4 = "1"; }   else { $pos_4 = "0";   }
      if ((isset($_POST['pos_5']))   && ($_POST['pos_5']))   { $pos_5 = "1"; }   else { $pos_5 = "0";   }
      if ((isset($_POST['pos_6']))   && ($_POST['pos_6']))   { $pos_6 = "1"; }   else { $pos_6 = "0";   }
      if ((isset($_POST['pos_7']))   && ($_POST['pos_7']))   { $pos_7 = "1"; }   else { $pos_7 = "0";   }
      if ((isset($_POST['pos_8']))   && ($_POST['pos_8']))   { $pos_8 = "1"; }   else { $pos_8 = "0";   }
      if ((isset($_POST['pos_9']))   && ($_POST['pos_9']))   { $pos_9 = "1"; }   else { $pos_9 = "0";   }
      if ((isset($_POST['pos_10']))  && ($_POST['pos_10']))  { $pos_10 = "1"; }  else { $pos_10 = "0";  }
      if ((isset($_POST['pos_11']))  && ($_POST['pos_11']))  { $pos_11 = "1"; }  else { $pos_11 = "0";  }
      if ((isset($_POST['pos_12']))  && ($_POST['pos_12']))  { $pos_12 = "1"; }  else { $pos_12 = "0";  }
      if ((isset($_POST['pos_13']))  && ($_POST['pos_13']))  { $pos_13 = "1"; }  else { $pos_13 = "0";  }
      if ((isset($_POST['pos_14']))  && ($_POST['pos_14']))  { $pos_14 = "1"; }  else { $pos_14 = "0";  }
      if ((isset($_POST['pos_15']))  && ($_POST['pos_15']))  { $pos_15 = "1"; }  else { $pos_15 = "0";  }
      if ((isset($_POST['pos_16']))  && ($_POST['pos_16']))  { $pos_16 = "1"; }  else { $pos_16 = "0";  }
      if ((isset($_POST['pos_17']))  && ($_POST['pos_17']))  { $pos_17 = "1"; }  else { $pos_17 = "0";  }
      if ((isset($_POST['pos_18']))  && ($_POST['pos_18']))  { $pos_18 = "1"; }  else { $pos_18 = "0";  }
      if ((isset($_POST['pos_19']))  && ($_POST['pos_19']))  { $pos_19 = "1"; }  else { $pos_19 = "0";  }
      if ((isset($_POST['pos_20']))  && ($_POST['pos_20']))  { $pos_20 = "1"; }  else { $pos_20 = "0";  }
      if ((isset($_POST['pos_21']))  && ($_POST['pos_21']))  { $pos_21 = "1"; }  else { $pos_21 = "0";  }
      if ((isset($_POST['pos_22']))  && ($_POST['pos_22']))  { $pos_22 = "1"; }  else { $pos_22 = "0";  }
      if ((isset($_POST['pos_23']))  && ($_POST['pos_23']))  { $pos_23 = "1"; }  else { $pos_23 = "0";  }
      if ((isset($_POST['pos_24']))  && ($_POST['pos_24']))  { $pos_24 = "1"; }  else { $pos_24 = "0";  }
      if ((isset($_POST['pos_25']))  && ($_POST['pos_25']))  { $pos_25 = "1"; }  else { $pos_25 = "0";  }
      if ((isset($_POST['pos_26']))  && ($_POST['pos_26']))  { $pos_26 = "1"; }  else { $pos_26 = "0";  }
      if ((isset($_POST['pos_27']))  && ($_POST['pos_27']))  { $pos_27 = "1"; }  else { $pos_27 = "0";  }
      if ((isset($_POST['pos_28']))  && ($_POST['pos_28']))  { $pos_28 = "1"; }  else { $pos_28 = "0";  }
      if ((isset($_POST['pos_29']))  && ($_POST['pos_29']))  { $pos_29 = "1"; }  else { $pos_29 = "0";  }
      if ((isset($_POST['pos_30']))  && ($_POST['pos_30']))  { $pos_30 = "1"; }  else { $pos_30 = "0";  }
      if ((isset($_POST['pos_31']))  && ($_POST['pos_31']))  { $pos_31 = "1"; }  else { $pos_31 = "0";  }
      if ((isset($_POST['pos_32']))  && ($_POST['pos_32']))  { $pos_32 = "1"; }  else { $pos_32 = "0";  }
      if ((isset($_POST['pos_33']))  && ($_POST['pos_33']))  { $pos_33 = "1"; }  else { $pos_33 = "0";  }
      if ((isset($_POST['pos_34']))  && ($_POST['pos_34']))  { $pos_34 = "1"; }  else { $pos_34 = "0";  }
      if ((isset($_POST['pos_35']))  && ($_POST['pos_35']))  { $pos_35 = "1"; }  else { $pos_35 = "0";  }
      if ((isset($_POST['pos_36']))  && ($_POST['pos_36']))  { $pos_36 = "1"; }  else { $pos_36 = "0";  }
      if ((isset($_POST['pos_37']))  && ($_POST['pos_37']))  { $pos_37 = "1"; }  else { $pos_37 = "0";  }
      if ((isset($_POST['pos_38']))  && ($_POST['pos_38']))  { $pos_38 = "1"; }  else { $pos_38 = "0";  }
      if ((isset($_POST['pos_39']))  && ($_POST['pos_39']))  { $pos_39 = "1"; }  else { $pos_39 = "0";  }
      if ((isset($_POST['pos_40']))  && ($_POST['pos_40']))  { $pos_40 = "1"; }  else { $pos_40 = "0";  }
      if ((isset($_POST['pos_41']))  && ($_POST['pos_41']))  { $pos_41 = "1"; }  else { $pos_41 = "0";  }
      if ((isset($_POST['pos_42']))  && ($_POST['pos_42']))  { $pos_42 = "1"; }  else { $pos_42 = "0";  }
      if ((isset($_POST['pos_43']))  && ($_POST['pos_43']))  { $pos_43 = "1"; }  else { $pos_43 = "0";  }
      if ((isset($_POST['pos_44']))  && ($_POST['pos_44']))  { $pos_44 = "1"; }  else { $pos_44 = "0";  }
      if ((isset($_POST['pos_45']))  && ($_POST['pos_45']))  { $pos_45 = "1"; }  else { $pos_45 = "0";  }
      if ((isset($_POST['pos_46']))  && ($_POST['pos_46']))  { $pos_46 = "1"; }  else { $pos_46 = "0";  }
      if ((isset($_POST['pos_47']))  && ($_POST['pos_47']))  { $pos_47 = "1"; }  else { $pos_47 = "0";  }
      if ((isset($_POST['pos_48']))  && ($_POST['pos_48']))  { $pos_48 = "1"; }  else { $pos_48 = "0";  }
      if ((isset($_POST['pos_49']))  && ($_POST['pos_49']))  { $pos_49 = "1"; }  else { $pos_49 = "0";  }
      if ((isset($_POST['pos_50']))  && ($_POST['pos_50']))  { $pos_50 = "1"; }  else { $pos_50 = "0";  }
      
      $permissao =  $pos_0 .  $pos_1  . $pos_2  . $pos_3  . $pos_4  . $pos_5  . $pos_6  . $pos_7  . $pos_8  . $pos_9  . 
                    $pos_10 . $pos_11 . $pos_12 . $pos_13 . $pos_14 . $pos_15 . $pos_16 . $pos_17 . $pos_18 . $pos_19 . 
                    $pos_20 . $pos_21 . $pos_22 . $pos_23 . $pos_24 . $pos_25 . $pos_26 . $pos_27 . $pos_28 . $pos_29 . 
                    $pos_30 . $pos_31 . $pos_32 . $pos_33 . $pos_34 . $pos_35 . $pos_36 . $pos_37 . $pos_38 . $pos_39 . 
                    $pos_40 . $pos_41 . $pos_42 . $pos_43 . $pos_44 . $pos_45 . $pos_46 . $pos_47 . $pos_48 . $pos_49 . 
                    $pos_50;
      
      if ($this->setarPermissao($cd_usuario, $permissao)) {
        echo "<p class=\"fontConteudoSucesso\">Permissões do Usuário alteradas com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar permissões do Usuário, ou nenhuma informação alterada!</p>\n";
      }
    }                      
                  
    
    public function ehAtivo($codigo) {
      $dados = $this->selectDadosUsuario($codigo);
      if ($dados['eh_ativo'] == '1') {
        return true;
      } else {
        return false;
      }
    }
                                             */
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
      
      $ds_email = $util->limparVariavel($_POST['e_mail_senha']);
      
      if ($ds_email != '') {
        if ($this->existeUsername($ds_email)) {
          $dados = $this->selectDadosUsuarioUsername($ds_email);
          if ($dados['cd_origem'] == '0') {
            $ds_senha_gerada = $this->geraSenha('15', true, true, true, false);
            $ds_senha = base64_encode($ds_senha_gerada);
            $ds_senha_antiga = $dados['ds_senha'];
            if ($this->alterarSenhaUsuario($dados['cd_usuario'], $ds_senha)) {
              require_once 'conteudos/email.php';                                 $email = new Email();
              if ($email->notificarEsqueceuSenha($dados['nm_usuario'], $ds_email, $ds_senha_gerada)) {
                echo "<p class=\"fontConteudoSucesso\">Sua senha foi alterada e encaminhada por e-mail!<br />Caso não receba a mensagem, verifique sua caixa de Spam!</p>\n";
              } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas na alteração da senha e encaminhamento do e-mail!<br />Tente novamente em alguns instantes!</p>\n";
                $this->alterarSenhaUsuario($dados['cd_usuario'], $ds_senha_antiga);
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
      $len = strlen($caracteres);

      for ($n = 1; $n <= $tamanho; $n++) {
        // Criamos um número aleatório de 1 até $len para pegar um dos caracteres
        $rand = mt_rand(1, $len);
        // Concatenamos um dos caracteres na variável $retorno
        $retorno .= $caracteres[$rand-1];
      }
      return $retorno;
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
/*                    
    public function alterarUsuario($cd_usuario, $ds_username, $ds_senha, $eh_ativo, $nm_usuario) {
      $cd_usuario_master= $_SESSION['life_codigo'];
      $sql  = "UPDATE life_usuarios SET ".
              "ds_username  = \"$ds_username\", ".
              "eh_ativo = \"$eh_ativo\", ".
              "cd_usuario_master = \"$cd_usuario_master\", ".
              "nm_usuario = \"$nm_usuario\", ".
              "ds_senha = \"$ds_senha\" ".
              "WHERE cd_usuario= '$cd_usuario'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'usuario');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA USUÁRIOS");
      $saida = mysql_affected_rows();
      return $saida;
    
    }
                             */
    public function alterarSenhaUsuario($cd_usuario, $senha) {
      $sql  = "UPDATE life_usuarios SET ".
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
                            /*
//*************************************PERMISSOES
    public function selectListaPermissoes() {
      $sql  = "SELECT * ".
              "FROM life_permissoes ".
              "WHERE eh_ativa = '1' ".
              "ORDER BY nr_ordem ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA PERMISSÕES");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;     
    }
*/
  }
?>
