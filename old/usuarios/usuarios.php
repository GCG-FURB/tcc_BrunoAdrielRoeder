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
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_senha_atual\" id=\"ds_senha_atual\" placeholder=\"Senha atual\" value=\"\"/><br />\n";
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_senha\" id=\"ds_senha\" placeholder=\"Senha nova\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaSenha();\"><br />\n";
      echo "      <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_confirma_senha\" id=\"ds_confirma_senha\" placeholder=\"Confirmação da senha\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaConfirmacaoSenha();\"><br />\n";
      echo "      <input type=\"submit\" class=\"botao\" value=\"Altere a senha\">\n";
      echo "    </p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'ds_senha_atual');
    }
    
    public function alterarSenha() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario= $_SESSION['life_codigo'];
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Não há usuário Logado!</p>\n";
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
      if ($this->alterarSenhaUsuario($cd_usuario, $ds_senha)) {
        echo "  <p class=\"fontConteudoSucesso\">Senha alterada com sucesso!</p>\n";
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Problemas ao alterar senha, ou nova senha igual a antiga!</p>\n";
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
/*
    
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
            $ds_senha_gerada = $this->geraSenha('6', true, true, true, false);

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