<?php
  class Pessoa {
    
    public function __construct () {
    }
            /*
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['in']))    {      $inicial = addslashes($_GET['in']);         } else {      $inicial = 'A';       }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $inicial);
        break;

        case "pesquisar":
          $this->listarOpcoesPesquisa($secao, $subsecao, $item, $ativas, $inicial);
        break;
        
        case "pesq":
          $_SESSION['life_c_pesquisando'] = '1';
          $this->pesquisar($secao, $subsecao, $item, $ativas, $inicial);
        break;
        
        case "zpesquisa":
          unset($_SESSION['life_c_pesquisando']);
          unset($_SESSION['life_c_termo']);
          unset($_SESSION['life_c_campo']);
          $inicial = 'A';
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $inicial);
        break;
        
        case "validar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&acao=cadastrar";
          $this->montarFormularioValidacaoEmail($link);
        break;
        
        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&acao=salvar";
          $this->validacaoEmailCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&acao=salvar";
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $inicial);
        break;  

        case "status":
          $this->alterarStatus($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $inicial);
          $this->listarItens($secao, $subsecao, $item, $ativas, $inicial);
        break;

        case "detalhar":
          echo "<p class=\"fontComandosFiltros\">\n";
          echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\"border=\"0\"></a> \n";
          echo "</p>\n";
          $this->apresentarInformacoesPessoa($codigo);
        break;      
      }
    }

    private function listarAcoes($secao, $subsecao, $item, $ativas, $inicial) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=1";                               $opcao['descricao']= "Ativos";                                  $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=0";                               $opcao['descricao']= "Inativos";                                $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=2";                               $opcao['descricao']= "Ativos/Inativos";                         $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                             $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "5";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&acao=pesquisar";      $opcao['descricao']= "Pesquisar";                               $opcoes[]= $opcao;
      if (isset($_SESSION['life_c_pesquisando'])) {
        $opcao= array();      $opcao['indice']= "6";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&acao=zpesquisa";      $opcao['descricao']= "Limpar Pesquisa";                         $opcoes[]= $opcao;
      } else {
        if (isset($_SESSION['life_c_termo'])) {        $termo = $_SESSION['life_c_termo'];            } else {        $_SESSION['life_c_termo'] = '';        $termo = $_SESSION['life_c_termo'];      }
        if (isset($_SESSION['life_c_campo'])) {        $campo = $_SESSION['life_c_campo'];            } else {        $_SESSION['life_c_campo'] = 'N';       $campo = $_SESSION['life_c_campo'];      }
      }      
      $opcao= array();      $opcao['indice']= "7";      $opcao['link']= "";                                                                                                             $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&acao=validar\"><img src=\"icones/novo.png\" alt=\"Novo Pessoa\" title=\"Novo Pessoa\"border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
      echo "<p class=\"fontComandosMenuCentrais\">\n";
      $alfabeto= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
      foreach ($alfabeto as $alfa) {
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&in=".$alfa."\" class=\"fontLink\">".$alfa."</a>&nbsp;&nbsp;\n";
      }
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&in=0\" class=\"fontLink\">A à Z</a>\n";
      echo "</p>\n";      
    }

    private function listarOpcoesPesquisa($secao, $subsecao, $item, $ativas, $inicial) {
      if (isset($_SESSION['life_c_termo'])) {        $termo = $_SESSION['life_c_termo'];            } else {        $_SESSION['life_c_termo'] = '';        $termo = $_SESSION['life_c_termo'];      }
      if (isset($_SESSION['life_c_campo'])) {        $campo = $_SESSION['life_c_campo'];            } else {        $_SESSION['life_c_campo'] = 'N';       $campo = $_SESSION['life_c_campo'];      }
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&acao=pesq";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."\" >\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->linhaComentario("Pesquisar:");
      $util->linhaUmCampoText(0, 'Nome do Pessoa: ', 'termo', '50', '100', $termo);
      $util->campoHidden('campo', 'N');           
/*
      $util->linhaComentario("Campo:");
      $lista= array();
      $opcao= array();    $opcao[]= 'X';    $opcao[]= 'Todos os Campos';                    $lista[]= $opcao;
      $opcao= array();    $opcao[]= 'N';    $opcao[]= 'Nome';                               $lista[]= $opcao;
      $opcao= array();    $opcao[]= 'C';    $opcao[]= 'CPF';                                $lista[]= $opcao;
      $util->linhaNCampoRadio($lista, 'campo', $campo);           
/
      $util->linhaBotao('Pesquisar');
      echo "    </table>\n";      
      echo "  </form>\n";
    }           

    private function pesquisar($secao, $subsecao, $item, $ativas, $inicial) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      if (isset($_POST['termo'])) {        $termo = $util->limparVariavel($_POST['termo']);      } else {        $termo = '';      }
      if (isset($_POST['campo'])) {        $campo = addslashes($_POST['campo']);                 } else {        $campo = 'I';     }
      $_SESSION['life_c_termo'] = $termo;        
      $_SESSION['life_c_campo'] = $campo;
      $inicial = '0';
      $this->listarAcoes($secao, $subsecao, $item, $ativas, $inicial);
      $this->listarItens($secao, $subsecao, $item, $ativas, $inicial);
    }

    private function listarItens($secao, $subsecao, $item, $ativas, $inicial) {
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      $termo = $_SESSION['life_c_termo'];
      $campo = $_SESSION['life_c_campo'];

      $itens = $this->selectPessoas($ativas, $campo, $termo, $inicial);    

      $mensagem = "Pessoas ";
      if ($ativas == 1) {        $mensagem.= "Ativos";      } elseif ($ativas == 0) {        $mensagem.= "Inativos";      }
      if ($termo != '') {
        $mensagem.= " - termo '".$termo."'";
        if ($campo == 'X')     {          $mensagem.= ' em todos os campos';        } 
        elseif ($campo == 'N') {          $mensagem.= ' no campo Nome';             } 
        elseif ($campo == 'C') {          $mensagem.= ' no campo CPF';              }                                 
      } 
      if ($inicial != '0')     {          $mensagem.= " - com nome iniciado em '".$inicial."'";      } 
      else                     {          $mensagem.= " - com nome iniciado com qualquer letra";     }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Nome do Pessoa:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_aluno']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharPessoa($it['cd_aluno']);
        echo "        </span></a>\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes_contatos.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $con->detalharContato($it['cd_contato']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_aluno']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\"border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_aluno']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\"border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\"border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&at=".$ativas."&cd=".$it['cd_aluno']."&acao=detalhar\"><img src=\"icones/detalhar.png\" alt=\"Detalhar Pessoa\" title=\"Detalhar Pessoa\"border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=25&it=".$item."&ca=".$it['cd_aluno']."&cc=0\"><img src=\"icones/cursos_pessoas.png\" alt=\"Inscrições (Cursos x Pessoas)\" title=\"Inscrições (Cursos x Pessoas)\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
    }

    private function detalharPessoa($cd_aluno) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosPessoa($cd_aluno);
      
      $retorno = "";
      if ($dados['cd_usuario_cadastro'] == '0') {
        $retorno.= "Cadastro realizado pelo aluno<br />\n";
      } else {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      }
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        if ($dados['cd_usuario_ultima_atualizacao'] == '0') {
          $retorno.= "Atualização realizada pelo aluno<br />\n";
        } else {
          $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
          $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        }
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }

    public function apresentarInformacoesPessoa($cd_aluno) {
      require_once 'conteudos/pessoas_niveis_escolaridade.php';                  $niv = new PessoaNivelEscolaridade();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $dados = $this->selectDadosPessoa($cd_aluno);
      echo "<h2>Pessoa: ".$dados['nm_aluno']."</h2>\n";
      echo "<p>\n";
      echo "  Sexo: ".$dados['ds_sexo']."<br />\n";
      echo "  CPF: ".$dados['nr_cpf']."<br />\n";
      echo "  Nacionalidade: ".$dados['ds_nacionalidade']."<br />\n";
      $dados_nivel = $niv->selectDadosNivelEscolaridade($dados['cd_nivel_escolaridade']);
      echo "  Nível de Instrução: ".$dados_nivel['nm_nivel_escolaridade']."<br />\n";  
      echo "  Curso de Formação: ".$dados['ds_area_formacao']."<br />\n";
      echo "  <br />Endereço: <br />".$end->retornaDetalhesEnderecoCompleto($dados['cd_endereco'])."\n";
      echo "  <br />Contatos: <br />".$con->detalharContato($dados['cd_contato'])."\n";
      echo "</p>\n";
    }
    
    private function montarFormularioValidacaoEmail($link) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      
      echo "    <h2>Validar E-mail para Cadastro de Pessoa</h2>\n";
      include "js/js_cadastro_e_mail.js";
      echo "    <form id=\"logar\" name=\"logar\" method=\"post\" action=\"".$_SESSION['life_link_completo'].$link."\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->linhaUmCampoText(1, 'E-mail: ', 'e_mail_cadastro', 150, 100, '');
      $util->linhaBotao('Validar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      $util->posicionarCursor('logar', 'e_mail_cadastro'); 
      echo "    </form>\n";    
    }
    
    private function validacaoEmailCadastro($link) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      
      $ds_email = $util->limparVariavel($_POST['e_mail_cadastro']);
      
      if ($ds_email != '') {
        if ($usu->existeUsername($ds_email)) {
          echo "<p class=\"fontConteudoAlerta\">E-mail informado já está cadastrado!</p>\n";
          $dados_usuario = $usu->selectDadosUsuarioUsername($ds_email);
          if ($dados_usuario['cd_categoria_usuario'] == '3') {
            $dados_aluno = $this->selectDadosPessoaUsuario($dados_usuario['cd_usuario']);
            if ($dados_aluno['cd_aluno'] != '') {
              echo "<p class=\"fontConteudoSucesso\">Dados do Pessoa carregados para edição!</p>\n";
              $this->montarFormularioEdicao($link, $dados_aluno['cd_aluno']);
            } else {
              echo "<p class=\"fontConteudoAlerta\">Problemas ao recuperar Dados do Pessoa para edição!</p>\n";
            }            
          } else {
            require_once 'usuarios/usuarios_categorias.php';                    $usu_cat = new UsuarioCategoria();
            $dados_categoria = $usu_cat->selectDadosUsuarioCategoria($dados_usuario['cd_categoria_usuario']);
            echo "<p class=\"fontConteudoAlerta\">O E-mail ".$ds_email." está sendo utilizado por um Usuário da Categoria ".$dados_categoria['nm_categoria']."!</p>\n";
          }
        } else {
          $this->montarFormularioCadastro($link, $ds_email);
        }      
      } else {
        echo "<p class=\"fontConteudoAlerta\">E-mail não informado!</p>\n";
        return false;
      }
    } 
    
    private function montarFormularioCadastro($link, $ds_email) {
      $cd_aluno = "";
      $cd_usuario = "";
      $cd_contato = "";
      $cd_endereco = "";
      
      if (isset($_POST['ds_senha'])) {
        $ds_senha = addslashes($_POST['ds_senha']);
      } else {
        $ds_senha = '';
      }
      
      $nm_aluno = "";
      $ds_sexo = "";
      $nr_cpf = "";
      $ds_nacionalidade = "";
      $cd_nivel_escolaridade = "";
      $ds_area_formacao = "";
      $eh_ativo = "1";
      
      $_SESSION['life_e_mail_em_cadastro'] = $ds_email;

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Pessoas</h2>\n";
      
      $this->imprimeFormularioCadastro($link, $cd_aluno, $cd_usuario, $cd_contato, $cd_endereco, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $ds_email, $ds_senha);
    }

    private function montarFormularioEdicao($link, $cd_aluno) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();

      $dados= $this->selectDadosPessoa($cd_aluno);

      $cd_usuario = $dados['cd_usuario'];
      $cd_contato = $dados['cd_contato'];
      $cd_endereco = $dados['cd_endereco'];
      $nm_aluno = $dados['nm_aluno'];
      $ds_sexo = $dados['ds_sexo'];
      $nr_cpf = $dados['nr_cpf'];
      $ds_nacionalidade = $dados['ds_nacionalidade'];
      $cd_nivel_escolaridade = $dados['cd_nivel_escolaridade'];
      $ds_area_formacao = $dados['ds_area_formacao'];
      $eh_ativo = $dados['eh_ativo'];
      
      $dados_usuario = $usu->selectDadosUsuario($cd_usuario);
      $ds_email = $dados_usuario['ds_username'];

      $_SESSION['life_edicao']= 1;      
      echo "  <h2>Edição de Pessoa</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_aluno, $cd_usuario, $cd_contato, $cd_endereco, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $ds_email, '');
    }    

    private function imprimeFormularioCadastro($link, $cd_aluno, $cd_usuario, $cd_contato, $cd_endereco, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $ds_email, $ds_senha) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/pessoas_niveis_escolaridade.php';                  $niv_esc = new PessoaNivelEscolaridade();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      include "js/js_cadastro_aluno.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_aluno', $cd_aluno);
      $util->campoHidden('cd_usuario', $cd_usuario);
      $util->campoHidden('e_mail_cadastro', $ds_email);
      if ($ds_senha != '') {
        $util->campoHidden('ds_senha', $ds_senha);
      } else {
        require_once 'includes/configuracoes.php';                              $conf = new Configuracao();
        $ds_senha = $conf->retornaSenhaPadrao();
        $util->campoHidden('ds_senha', $ds_senha);
      }
      
      $util->linhaUmCampoText(1, 'Nome Completo: ', 'nm_aluno', 150, 100, $nm_aluno);
      
      $util->linhaDuasColunasComentario('Username: ', $ds_email);

      if ($cd_aluno > 0) {
//        $util->linhaComentario('&nbsp;');
//        $util->linhaUmCampoSenha(0, 'Senha: ', 'ds_senha', '25', '100', '');
//        $util->linhaUmCampoSenha(0, 'Confirmação de Senha: ', 'ds_confirma_senha', '25', '100', '');     
//        $util->linhaObservacao('Para a Senha, utilizar Letras Maiúsculas, Minúsculas e Números. A senha deve possuir no mínimo 6 no máximo 25 caracteres.');
        $util->linhaComentario('&nbsp;');
        $util->campoHidden('nr_cpf', $nr_cpf);
        $util->linhaDuasColunasComentario('CPF: ', $nr_cpf);
        $util->campoHidden('eh_cpf_valido', 1);
        $util->campoHidden('eh_validar', 0);
      } else {
//        $util->linhaComentario('&nbsp;');
//        $util->linhaUmCampoSenha(1, 'Senha: ', 'ds_senha', '25', '100', '');
//        $util->linhaUmCampoSenha(1, 'Confirmação de Senha: ', 'ds_confirma_senha', '25', '100', '');     
//        $util->linhaObservacao('Para a Senha, utilizar Letras Maiúsculas, Minúsculas e Números. A senha deve possuir no mínimo 6 no máximo 25 caracteres.');
        $util->linhaComentario('&nbsp;');
        $util->linhaUmCampoTextAcao(0, 'CPF: ', 'nr_cpf', 14, 25, $nr_cpf, " onKeyPress=\"mascaraCpf('nr_cpf')\"; onBlur=\"validaCpf('nr_cpf');\" ");
         
        $util->campoHidden('eh_cpf_valido', 0);
        $util->campoHidden('eh_validar', 1);
      }
      
      $opcoes= array();
      $opcao= array();      $opcao[]= 'M';      $opcao[]= 'Masculino';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= 'F';      $opcao[]= 'Feminino';       $opcoes[]= $opcao;
      $util->linhaSeletor('Sexo: ', 'ds_sexo', $ds_sexo, $opcoes);
      $util->linhaUmCampoText(0, 'Nacionalidade: ', 'ds_nacionalidade', 50, 50, $ds_nacionalidade);
      $util->linhaComentario('&nbsp;');
      $niv_esc->retornaSeletorPessoasNiveisEscolaridade($cd_nivel_escolaridade);
      $util->linhaUmCampoText(0, 'Área de Formação: ', 'ds_area_formacao', 150, 100, $ds_area_formacao);
      $util->linhaComentario('&nbsp;');
      $end->apresentaFormularioEndereco('Endereço:', $cd_endereco, false);
      $util->linhaComentario('&nbsp;');
      $con->imprimeFormularioContatos('Contatos:', $cd_contato, true, true, true, true, true);
      $util->linhaComentario('&nbsp;');
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_aluno'); 
    }
                          
    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();


      $cd_aluno = addslashes($_POST['cd_aluno']);
      $cd_usuario = addslashes($_POST['cd_usuario']);

      $cd_endereco = $end->salvarCadastroEdicao();
      $cd_contato = $con->salvarCadastroEdicao();

      $nm_aluno = $util->limparVariavel($_POST['nm_aluno']);
      $ds_sexo = addslashes($_POST['ds_sexo']);
      $nr_cpf = $util->limparVariavel($_POST['nr_cpf']);
      $ds_nacionalidade = $util->limparVariavel($_POST['ds_nacionalidade']);
      $cd_nivel_escolaridade = addslashes($_POST['cd_nivel_escolaridade']);
      $ds_area_formacao = $util->limparVariavel($_POST['ds_area_formacao']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      $lk_seo = $util->retornaLinkSEO($nm_aluno, 'life_pessoas', 'lk_seo', '150', $cd_aluno);

      $ds_username = $util->limparVariavel($_POST['e_mail_cadastro']);
      $nm_usuario = $util->cortarStringAposPalavra(' ', $nm_aluno);
      $ds_email = $ds_username;
      $ds_senha = base64_encode(trim($_POST['ds_senha']));
      //$ds_confirma_senha = base64_encode(trim($_POST['ds_confirma_senha']));
      $cd_categoria_usuario = '3';
      $ds_permissoes = "0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
      $dt_ultimo_acesso = date('Y-m-d');
      $nr_tentativas_login = "0";
      $ds_chave = "C_O_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,14);

      if ($cd_usuario > 0) {
        if ($ds_senha != '') {
/*          if ($ds_senha != $ds_confirma_senha) {
            echo "  <p class=\"fontConteudoAlerta\">Senha diferente de Confirmação de Senha! Processo cancelado!</p>\n";
            return false;
          }*
        } else {
          $dados_usuario = $usu->selectDadosUsuario($cd_usuario);
          $ds_senha = $dados_usuario['ds_senha'];
        }
        if ($usu->alterarUsuario($cd_usuario, $ds_username, $nm_usuario, $ds_email, $ds_senha, $cd_categoria_usuario, $eh_ativo, $nr_tentativas_login)) {
          $alterou_usuario = true;
        } else {
          $alterou_usuario = false;
        }
        if ($this->alterarPessoa($cd_aluno, $cd_usuario, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $lk_seo)) {
          $alterou_aluno = true;
        } else {
          $alterou_aluno = false;
        }
        if (($cd_contato > 0) || ($cd_endereco > 0) || ($alterou_usuario) || ($alterou_aluno)) {
          echo "<p class=\"fontConteudoSucesso\">Pessoa editado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Pessoa, ou nenhuma informação alterada!</p>\n";
          return false;
        }
      } else {
        if (($cd_contato > 0) && ($cd_endereco > 0)) {
          if ($ds_senha == '') {
            require_once 'includes/configuracoes.php';                          $conf = new Configuracao();
            $ds_senha_padrao = $conf->retornaSenhaPadrao();
            $ds_senha = base64_encode(trim($ds_senha_padrao));
          }
          $cd_usuario = $usu->inserirUsuario($ds_username, $nm_usuario, $ds_email, $ds_senha, $cd_categoria_usuario, $ds_permissoes, $dt_ultimo_acesso, $eh_ativo, $nr_tentativas_login, $ds_chave);   
          if ($cd_usuario > 0) {
            $ds_chave_aluno = "C_O_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,10);        
            if ($this->inserirPessoa($cd_usuario, $cd_contato, $cd_endereco, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $lk_seo, $ds_chave)) {
              $_SESSION['life_cadastro_ds_username'] = $ds_username;
              $_SESSION['life_cadastro_nm_usuario'] = $nm_usuario;
              $_SESSION['life_cadastro_ds_email'] = $ds_email;
              $_SESSION['life_cadastro_ds_senha'] = $ds_senha;
              echo "<p class=\"fontConteudoSucesso\">Pessoa cadastrado com sucesso!</p>\n";            
            } else {
              echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Pessoa!</p>\n";
              return false;
            }
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Pessoa!</p>\n";
            return false;
          }
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Pessoa!</p>\n";
          return false;
        }                                                                                 
      }
      return true;
    } 

    private function alterarStatus($cd_aluno) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();

      $dados= $this->selectDadosPessoa($cd_aluno);

      $cd_usuario = $dados['cd_usuario'];
      $cd_contato = $dados['cd_contato'];
      $cd_endereco = $dados['cd_endereco'];
      $nm_aluno = $dados['nm_aluno'];
      $ds_sexo = $dados['ds_sexo'];
      $nr_cpf = $dados['nr_cpf'];
      $ds_nacionalidade = $dados['ds_nacionalidade'];
      $cd_nivel_escolaridade = $dados['cd_nivel_escolaridade'];
      $ds_area_formacao = $dados['ds_area_formacao'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];
    
      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
    
      if ($this->alterarPessoa($cd_aluno, $cd_usuario, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $lk_seo)) {
        if ($usu->alteraStatusUsuario($cd_usuario, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Status do Pessoa alterado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status do Pessoa!</p>\n";
        }
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status do Pessoa!</p>\n";
      }                                                                                               
    }

    private function validarEmailPessoa($pagina, $lista_paginas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      
      $ds_email = $util->limparVariavel($_POST['e_mail_cadastro']);
      
      if ($ds_email != '') {
        if ($usu->existeUsername($ds_email)) {
          $_SESSION['life_erro_cadastro_e_mail'] = "E-mail informado já está cadastrado!";
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
                $ds_chave_aluno = "C_O_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,10);  
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
        $_SESSION['life_erro_cadastro_e_mail'] = "E-mail não informado!";
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
            echo "<p class=\"fontConteudoSucesso\">Olá ".$_SESSION['life_nome']."!<br />Seja bem vindo(a) ao ".$nm_site."!</p>\n";
            require_once 'conteudos/cursos_pessoas_inscricoes.php';              $cur_alu_ins = new CursoPessoaInscricao();
            $cur_alu_ins->retornaProcedimentosInscricaoPessoaCurso($lista_paginas);
          } else {
            echo "<p class=\"fontConteudoSucesso\">Desculpe porém não foi possível efetuar seu login no ".$nm_site."!</p>\n";
            echo "<p class=\"fontConteudoSucesso\">Tente efetuar seu login com o Usuário (e-mail) e senha que utilizou no cadastro! Caso o erro se repita, entre em contato na seção <a href=\"".$_SESSION['life_link_completo']."fale-conosco\" class=\"fontLink\">Fale Conosco</a>!</p>\n";
          }
          
        }
        unset($_SESSION['life_edicao']);
            
            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Dados para inscrição já foram salvos anteriormente ou ocorreu um erro ao efetuar salvamento!</p>\n";
        return false;
      }    
    }
    
    public function validaPessoaRetornoDadosPagamentoPagSeguro() {
      if (isset($_GET['lka'])) {
        $lk_seo_aluno = addslashes($_GET['lka']);
        $dados_aluno_seo = $this->selectDadosPessoaSeo($lk_seo_aluno);
        if ($dados_aluno_seo['cd_aluno'] > 0) {
          if (isset($_SESSION['life_aluno_formulario_pagamento'])) {
            $cd_aluno = addslashes($_SESSION['life_aluno_formulario_pagamento']);
            $dados_aluno_secao = $this->selectDadosPessoa($cd_aluno);
            if ($dados_aluno_secao['cd_aluno'] > 0) {
              if (isset($_GET['cha'])) {
                $ds_chave_aluno = addslashes($_GET['cha']);
                $dados_aluno_retorno = $this->selectDadosPessoaChave($ds_chave_aluno);
                if (($dados_aluno_retorno['cd_aluno'] != $dados_aluno_secao['cd_aluno']) || ($dados_aluno_retorno['cd_aluno'] != $dados_aluno_seo['cd_aluno']) || ($dados_aluno_secao['cd_aluno'] != $dados_aluno_seo['cd_aluno'])) {
                  echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar aluno!</p>\n";
                  return '0';
                } else {    
                  echo "<h2>Pessoa: ".$dados_aluno_retorno['nm_aluno']."</h2>\n";
                  return $cd_aluno;
                }
              } else {
                echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar aluno!</p>\n";
                return '0';
              }
            } else {
              echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar aluno!</p>\n";
              return '0';
            }
          } else {
            echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar aluno!</p>\n";
            return '0';
          }
        } else {
          echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar aluno!</p>\n";
          return '0';
        }
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Erro ao identificar aluno!</p>\n";
        return '0';
      }
   }
    
    
            */
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
      
      $eh_mostrar_mensagem_cadastro_login_redes = $conf->exibirMensagemRedesSociaisCadastroUsuario();
      $ds_mensagem_cadastro_login_redes = $conf->retornaMensagemRedesSociaisCadastroUsuario();
      
      if ($eh_mostrar_mensagem_cadastro_login_redes == '1') {
        require_once 'conteudos/redes_sociais.php';                             $red_soc = new RedeSocial();
        echo "      <div class=\"divMensagemCadastro\">\n";
        echo "        <p>".nl2br($ds_mensagem_cadastro_login_redes)."</p>\n";  
        $itens = $red_soc->selectRedesSociais('1', '2', '1');
        if (count($itens) > 0) {
          echo "        <p class=\"fontConteudoMenuInterno\">\n";
          echo "          <img src=\"".$_SESSION['life_link_completo']."icones/espacador.png\" border=\"0\">\n"; 
          foreach ($itens as $it) {
            echo "          <a href=\"".$_SESSION['life_link_completo']."logar/".$it['lk_seo']."\"><img src=\"".$_SESSION['life_link_completo'].$it['ds_arquivo_logo_login']."\" alt=\"Efetuar Login utilizando ".$it['nm_rede_social']."\" title=\"Efetuar Login utilizando ".$it['nm_rede_social']."\" border=\"0\"></a>\n";
            echo "            <img src=\"".$_SESSION['life_link_completo']."icones/espacador.png\" border=\"0\">\n"; 
          }
          echo "        </p>\n";
        }
        echo "      </div>\n";
      }

      $eh_mostrar_mensagem_convite_cadastro = $conf->exibirMensagemConviteCadastroUsuario();
      $ds_mensagem_convite_cadastro = $conf->retornaMensagemConviteCadastroUsuario();
      
      if ($eh_mostrar_mensagem_convite_cadastro == '1') {
        echo "      <div class=\"divMensagemCadastro\">\n";
        echo "        <p>".nl2br($ds_mensagem_convite_cadastro)."</p>\n";  
        echo "      </div>\n";
      }
                     
                     
      $ds_username = "";
      $ds_senha = "";
      $nm_pessoa = "";
      $nr_cpf = "";

      $_SESSION['life_edicao'] = '1';

      $this->imprimeFormularioCadastroProprioPessoa($ds_username, $ds_senha, $nm_pessoa, $nr_cpf);
    }
    
    public function imprimeFormularioCadastroProprioPessoa($ds_username, $ds_senha, $nm_pessoa, $nr_cpf) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      include "js/js_cadastro_pessoa_proprio.js"; 
      echo "          <form  name=\"cadastro\" action=\"".$_SESSION['life_link_completo']."cadastrar/salvar\" method=\"POST\" onSubmit=\"return valida(this);\">\n";
      echo "            <p class=\"fontConteudoCadastroExterno\">\n";
      echo "              <input type=\"hidden\" name=\"validar\" id=\"validar\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"nr_letras_maiusculas_senha\" id=\"nr_letras_maiusculas_senha\" value=\"".$conf->verificarNumeroLetrasMaiusculasSenha()."\">\n";      
      echo "              <input type=\"hidden\" name=\"nr_letras_minusculas_senha\" id=\"nr_letras_minusculas_senha\" value=\"".$conf->verificarNumeroLetrasMinusculasSenha()."\">\n";
      echo "              <input type=\"hidden\" name=\"nr_numeros_senha\" id=\"nr_numeros_senha\" value=\"".$conf->verificarNumeroNumerosSenha()."\">\n";
      echo "              <input type=\"hidden\" name=\"nr_caracteres_senha\" id=\"nr_caracteres_senha\" value=\"".$conf->verificarNumeroCaracteresSenha()."\">\n";
      echo "              <input type=\"hidden\" name=\"eh_username_valido\" id=\"eh_username_valido\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"eh_senha_valido\" id=\"eh_senha_valido\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"eh_nome_valido\" id=\"eh_nome_valido\" value=\"0\">\n";
      echo "              <input type=\"hidden\" name=\"eh_cpf_valido\" id=\"eh_cpf_valido\" value=\"0\">\n";
      echo "              <input type=\"text\" maxlength=\"100\" class=\"fonInputExterno\" name=\"nm_pessoa\" id=\"nm_pessoa\" placeholder=\"Nome Completo\" value=\"".$nm_pessoa."\" onKeyPress=\"ehValidar('1'); ehValido('eh_nome_valido', '0');\" onBlur=\"ehNomeCompleto();\"><br />\n";
      echo "              <input type=\"text\" maxlength=\"100\" class=\"fonInputExterno\" name=\"ds_username\" id=\"ds_username\" placeholder=\"E-mail\" value=\"".$ds_username."\" onKeyPress=\"ehValidar('1'); ehValido('eh_username_valido', '0');\" onBlur=\"ehEmailValido();\"><br />\n";
      echo "              <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_senha\" id=\"ds_senha\" placeholder=\"Senha\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaSenha();\"><br />\n";
      echo "              <input type=\"password\" maxlength=\"50\" class=\"fonInputExterno\" name=\"ds_confirma_senha\" id=\"ds_confirma_senha\" placeholder=\"Confirmação da Senha\" value=\"\" onKeyPress=\"ehValidar('1'); ehValido('eh_senha_valido', '0');\" onBlur=\"validaConfirmacaoSenha();\"><br />\n";
      echo "              <input type=\"text\" maxlength=\"14\" class=\"fonInputExterno\" name=\"nr_cpf\" id=\"nr_cpf\" placeholder=\"CPF\" value=\"".$nr_cpf."\" onKeyPress=\"ehValidar('1'); ehValido('eh_cpf_valido', '0'); mascaraCpf('nr_cpf')\"; onBlur=\"validaCpf('nr_cpf');\"><br />\n";
      echo "  		        <input type=\"submit\" class=\"botao\" value=\"Cadastrar\">\n";
      echo "            </p>\n";
      echo "          </form>\n";
      $util->posicionarCursor('cadastro', 'nm_pessoa');    
    }

    private function salvarCadastroProprio() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      require_once 'conteudos/enderecos.php';                                   $end = new Endereco();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'conteudos/categorias_usuarios.php';                         $cat_usu = new CategoriaUsuario();
      require_once 'login/login.php';                                           $login = new Login();
    
      $ds_username = $util->limparVariavel($_POST['ds_username']);
      $nm_pessoa = $util->limparVariavel($_POST['nm_pessoa']);
      $nr_cpf = $util->limparVariavel($_POST['nr_cpf']);
      if ($usu->existeUsername($ds_username)) {
        $dados = $usu->selectDadosUsuarioUsername($ds_username);
        if ($dados['nm_usuario'] == $nm_pessoa) {
          echo "<p class=\"fontConteudoAlerta\">Já existe um usuário com este nome e e-mail cadastrado no sistema!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Já existe um usuário com este e-mail cadastrado no sistema!</p>\n";
        }
        echo "<p class=\"fontConteudoAlerta\">Esqueceu a sua senha! Utilize o formulário abaixo para recuperá-la!</p>\n";
        $this->imprimeFormularioEsqueceuSenha($ds_username);
        echo "<p class=\"fontConteudoAlerta\">O usuário cadastrado não é seu? Reinicie o cadastro com um e-mail próprio!</p>\n";
        $this->imprimeFormularioCadastroProprioPessoa($ds_username, '', $nm_pessoa, $nr_cpf);
        echo "    <script>\n";
        echo "      <!--\n";
        echo "        ehValido('eh_nome_valido', '1');\n";
        echo "        ehValido('eh_cpf_valido', '1');\n";
        echo "      -->\n";
        echo "    </script>\n";    
        return true;
      } else {
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
      
          $cd_nivel_instrucao = "0";
          $ds_dados_complementares = "";
          $ds_mini_curriculo = "";
          $ds_link_lattes = "";
      
          if ($this->inserirPessoa($cd_usuario, $nm_pessoa, $nr_cpf, $cd_nivel_instrucao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo)) {
            echo "<p class=\"fontConteudoSucesso\">Cadastro realizado com sucesso!</p>\n";
            $login->loginAutomaticoUsuario($cd_usuario);
            $this->retornaFuncoesUsuario($cd_categoria_usuario);
            return true;
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao realizar seu cadastro. Tente novamente e se o erro persistir entre em contato conosco!</p>\n";
            $usu->alteraStatusUsuario($cd_usuario, '0');
            return false;
          }
        }        
      }
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
      echo "    <h2>Esqueceu a Senha de Acesso ao Portal ".$nm_site."</h2>\n";
      include "js/js_lembrar_senha_proprio.js"; 
      echo "    <form class=\"fontConteudoLogin\" id=\"logar\" name=\"logar\" method=\"post\" action=\"".$_SESSION['life_link_completo']."esqueceu-a-senha/enviar\" onSubmit=\"return valida(this);\">\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "        E-mail:<br />\n";
      echo "        <input type=\"text\" maxlength=\"100\" name=\"e_mail_senha\" id=\"e_mail_senha\"  class=\"fonInputExterno\" value=\"".$ds_username."\" onBlur=\"ehEmailValido();\"><br />\n";
      echo "      </p>\n";
      echo "      <p class=\"fontConteudoLogin\">\n";
      echo "  		  <input type=\"submit\" class=\"botao\" value=\"Lembrar\" alt=\"Lembrar Senha\" title=\"=\"Lembrar Senha\">\n";
      echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      echo "        <a href=\"".$_SESSION['life_link_completo']."logar\" class=\"fontLinkLogin\">Logar</a>\n";

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
      echo "          <h1 class=\"nomargin\">Atualização Cadastral</h1>\n";
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
      echo "            <input type=\"hidden\" name=\"cd_aluno\" id=\"cd_aluno\" value=\"".$dados['cd_aluno']."\" />\n";    
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
      echo "              <input type=\"text\" name=\"nome\" id=\"nome\" placeholder=\"Nome\" value=\"".$dados['nm_aluno']."\">\n";
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
      echo "              <input type=\"text\" name=\"atuacao\" id=\"atuacao\" placeholder=\"Atuação/Formação\" value=\"".$dados['ds_area_formacao']."\">\n";     
      echo "            </div>\n";								
      echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
      echo "              <input type=\"text\" name=\"cep\" id=\"cep\" placeholder=\"Cep\" maxlength=\"9\" value=\"".$endereco['ds_cep']."\" onKeyPress=\"mascaraCep('cep')\"; onBlur=\"validaCep('cep')\";>\n";
      echo "            </div>\n";								
      if ($endereco['ds_rua'] != '') {
        echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
        echo "              <input type=\"text\" name=\"ds_rua\" id=\"ds_rua\" placeholder=\"Rua\" value=\"".$endereco['ds_rua']."\">\n";     
        echo "            </div>\n";								
        echo "            <div class=\"field-wrapper\" style=\"width:250px; color: black;\">\n";
        echo "              <input type=\"text\" name=\"ds_numero\" id=\"ds_numero\" placeholder=\"Número\" value=\"".$endereco['ds_numero']."\">\n";     
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

      $cd_aluno = addslashes($_POST['cd_aluno']);
      $cd_usuario = addslashes($_POST['cd_usuario']);

      $cd_endereco = addslashes($_POST['cd_endereco']);
      $ds_cep = $util->limparVariavel($_POST['cep']);
      $dados = $cep->recuperaEnderecoCEP($ds_cep);
      
      if (strpos($dados, "falha")) {
        $_SESSION['life_retorno_atualizacao'] = "Cep inválido!<br /><br />";
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
      
      $dados = $this->selectDadosPessoa($cd_aluno);
      $nm_aluno = $util->limparVariavel($_POST['nome']);
      $ds_sexo = addslashes($_POST['sexo']);
      $nr_cpf = $util->limparVariavel($_POST['cpf']);
      $ds_nacionalidade = $dados['ds_nacionalidade'];
      $cd_nivel_escolaridade = $dados['cd_nivel_escolaridade'];
      $ds_area_formacao = $util->limparVariavel($_POST['atuacao']);
      $eh_ativo = '1';

      $lk_seo = $util->retornaLinkSEO($nm_aluno, 'life_pessoas', 'lk_seo', '150', $cd_aluno);

      $alterou_aluno = $this->alterarPessoa($cd_aluno, $cd_usuario, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $lk_seo);
      
      if (($alterou_endereco) || ($alterou_contato) || ($alterou_aluno)) {
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
/*    
    public function selectPessoas($eh_ativo, $campo, $termo, $inicial) {
      $sql  = "SELECT *  ".
              "FROM life_pessoas ".
              "WHERE cd_aluno > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      if ($termo != '') {
        $termo = '%'.$termo.'%';
        $sql.= "AND ";
        if ($campo == 'N') {
          $sql.= " UPPER(nm_aluno) like UPPER('$termo') ";
        } elseif ($campo == 'C') {
          $sql.= " UPPER(nr_cpf) like UPPER('$termo') ";
        } else {
          $sql.= " ( ".
                 "    UPPER(nm_aluno) like UPPER('$termo') ".
                 "    OR UPPER(nr_cpf) like UPPER('$termo') ".
                 " ) ";
        }
      } 
      if ($inicial != '0') {
        $inicial.= "%";
        $sql.= " AND UPPER(nm_aluno) like UPPER('$inicial') ";
      }
      $sql.= "ORDER BY nm_aluno";           
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectDadosPessoa($cd_aluno) {
      $sql  = "SELECT * ".
              "FROM life_pessoas ".
              "WHERE cd_aluno = '$cd_aluno' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

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

    private function inserirPessoa($cd_usuario, $nm_pessoa, $nr_cpf, $cd_nivel_instrucao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_pessoas ".
             "(cd_usuario, nm_pessoa, nr_cpf, cd_nivel_instrucao, cd_endereco, cd_contato, ds_dados_complementares, ds_mini_curriculo, ds_link_lattes, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_usuario\", \"$nm_pessoa\", \"$nr_cpf\", \"$cd_nivel_instrucao\", \"$cd_endereco\", \"$cd_contato\", \"$ds_dados_complementares\", \"$ds_mini_curriculo\", \"$ds_link_lattes\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
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
                "AND cd_nivel_instrucao = '$cd_nivel_instrucao' ".
                "AND cd_endereco = '$cd_endereco' ".
                "AND cd_contato = '$cd_contato' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
        $dados = mysql_fetch_assoc($result_id);
        return $dados['codigo'];        
      }
      return $saida;     
    }
                           /*

    private function inserirPessoa($cd_pessoa, $cd_usuario, $nm_pessoa, $nr_cpf, $cd_nivel_instrucao, $cd_endereco, $cd_contato, $ds_dados_complementares, $ds_mini_curriculo, $ds_link_lattes, $eh_ativo) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_pessoas ".
             "(cd_usuario, cd_contato, cd_endereco, nm_aluno, ds_sexo, nr_cpf, ds_nacionalidade, cd_nivel_escolaridade, ds_area_formacao, eh_ativo, lk_seo, ds_chave, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_usuario\", \"$cd_contato\", \"$cd_endereco\", \"$nm_aluno\", \"$ds_sexo\", \"$nr_cpf\", \"$ds_nacionalidade\", \"$cd_nivel_escolaridade\", \"$ds_area_formacao\", \"$eh_ativo\", \"$lk_seo\", \"$ds_chave\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pessoas');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT cd_aluno codigo ".
                "FROM life_pessoas ".
                "WHERE nm_aluno = '$nm_aluno' ".
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

    private function alterarPessoa($cd_aluno, $cd_usuario, $nm_aluno, $ds_sexo, $nr_cpf, $ds_nacionalidade, $cd_nivel_escolaridade, $ds_area_formacao, $eh_ativo, $lk_seo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_pessoas SET ".
             "nm_aluno = \"$nm_aluno\", ".
             "ds_sexo = \"$ds_sexo\", ".
             "nr_cpf = \"$nr_cpf\", ".
             "ds_nacionalidade = \"$ds_nacionalidade\", ".
             "cd_nivel_escolaridade = \"$cd_nivel_escolaridade\", ".
             "ds_area_formacao = \"$ds_area_formacao\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_aluno= '$cd_aluno' ".
             "AND cd_usuario = '$cd_usuario' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'pessoas');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PESSOAS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                             
  */                           
  }
?>