<?php
  class Linguagem {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';               }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = '1';            }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';             }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
          $this->montarFormularioCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;        
               
        case "status":
          $this->alterarSituacaoAtivoLinguagem($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                 $opcao['descricao']= "Ativos";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                 $opcao['descricao']= "Inativos";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                 $opcao['descricao']= "Ativos/Inativos";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                               $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Idioma\" title=\"Novo Idioma\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Idiomas ";
      if ($ativas == 1)    {      $mensagem.= "Ativos ";         } elseif ($ativas == 0)    {       $mensagem.= "Inativos ";       }

      $itens = $this->selectLinguagem($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Idioma:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_linguagem']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharLinguagem($it['cd_linguagem']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_linguagem']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_linguagem']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
      echo "  <br /><br />\n"; 
    }
    
    public function detalharLinguagem($cd_linguagem) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosLinguagem($cd_linguagem);
      
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
     
    private function montarFormularioCadastro($link) {
      $cd_linguagem = "";
      $nm_linguagem = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Idiomas</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_linguagem, $nm_linguagem, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_linguagem) {
      $dados = $this->selectDadosLinguagem($cd_linguagem);

      $nm_linguagem = $dados['nm_linguagem'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Idioma</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_linguagem, $nm_linguagem, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_linguagem, $nm_linguagem, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/cores.php';                                       $cor = new Cor();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_linguagem.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_linguagem', $cd_linguagem);
      $util->linhaUmCampoText(1, 'Idioma: ', 'nm_linguagem', '150', '100', $nm_linguagem);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes, '100');

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_linguagem'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_linguagem = addslashes($_POST['cd_linguagem']);
      $nm_linguagem = $util->limparVariavel($_POST['nm_linguagem']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_linguagem > 0) {
        if ($this->alteraLinguagem($cd_linguagem, $nm_linguagem, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Idioma editado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Idioma, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereLinguagem($nm_linguagem, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Idioma cadastrado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Idioma!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoLinguagem($cd_linguagem) {
      $dados = $this->selectDadosLinguagem($cd_linguagem);

      $nm_linguagem = $dados['nm_linguagem'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraLinguagem($cd_linguagem, $nm_linguagem, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Idioma alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do Idioma!</p>\n";
      }
    }

    public function retornaSeletorLinguagem($cd_linguagem, $campo, $tamanho, $exibir_ajuda, $descricao, $denominacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectLinguagem('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= $descricao;                            $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_linguagem'];              $opcao[]= $it['nm_linguagem'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletorHint($descricao, $denominacao, $campo, $cd_linguagem, $opcoes, $tamanho, false, $exibir_ajuda);
    }
    
    public function retornaDados($cd_linguagem, $descricao) {
      $dados = $this->selectDadosLinguagem($cd_linguagem);
      
      return "<b>".$descricao."</b> ".$dados['nm_linguagem'];
    } 

    public function retornaSeletorLinguagemFiltro($cd_linguagem, $nome, $tamanho, $exibir_ajuda, $descricao, $ajuda) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectLinguagem('1');

      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" class=\"fontConteudoCampoSeletorHintFiltro\" placeholder=\"".$descricao."\" ";
      if ($exibir_ajuda == '1') {
        echo "alt=\"".$descricao."\" title=\"".$descricao."\" ";
      } else {
        echo "alt=\"".$ajuda."\" title=\"".$ajuda."\" ";
      }
      echo "tabindex=\"1\">\n";
      echo "  			    <option ";
      if ($cd_linguagem == '') {          echo " selected ";        }
      echo " value=\"0\">$descricao</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option ";
        if ($it['cd_linguagem'] == $cd_linguagem) {          echo " selected ";        }
        echo " value=\"".$it['cd_linguagem']."\">".$it['nm_linguagem']."</option>\n";
      }
      echo "          </select>\n";
      if ($exibir_ajuda == '1') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Selecione o idioma desejado para pesquisar por Objetos de Aprendizagem.\n";
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\">\n";
      }
    }

/*
//*********************EXIBICAO PUBLICA*****************************************
    public function controleExibicaoPublica($pagina, $lista_paginas) {

      if (isset($lista_paginas[1])) {
      
      } else {
        $this->relacionarLinguagem();
      }    
    }
    
    public function relacionarLinguagem() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $areas = $this->selectLinguagem('1');
      $lista_1 = array();
      $lista_2 = array();
      $par = true;
      foreach ($areas as $a) {
        if ($par) {
          $lista_1[] = $a;
          $par = false;
        } else {
          $lista_2[] = $a;
          $par = true;
        }
      }
      $qtd = count($lista_1);
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo']."areas-conhecimento/pesquisar\">\n";
    
      echo "  <table class=\"tabConteudo\">\n";
      echo "    <thead>\n";
      echo "    <tr>\n";
      echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Idioma</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "        <td class=\"celConteudoCabecalho\"	style=\"background-color: transparent;\"></td>\n";
      echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Idioma</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "      </tr>\n";
      echo "    </thead>\n"; 
      for ($i=0; $i<$qtd; $i++) {
        $a = $lista_1[$i];
        echo "    <tr>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
        $campo = 'cd_linguagem_'.$a['cd_linguagem'];
        echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
        foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
          if ($it == $a['cd_linguagem']) {           echo " checked=\"checked\" ";      }
        } 
        echo " value=\"".$a['cd_linguagem']."\" class=\"fontConteudo\" />\n";        
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
        echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorLinguagem\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Idioma ".$a['nm_linguagem']."\" title=\"Idioma ".$a['nm_linguagem']."\" border=\"0\"></div>\n";
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_linguagem']."</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">nr</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:16%;\"></td>\n";
        if (isset($lista_2[$i])) {
          $a = $lista_2[$i];
          echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
          $campo = 'cd_linguagem_'.$a['cd_linguagem'];
          echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
          foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
            if ($it == $a['cd_linguagem']) {           echo " checked=\"checked\" ";      }
          } 
          echo " value=\"".$a['cd_linguagem']."\" class=\"fontConteudo\" />\n";        
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
          echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorLinguagem\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Idioma ".$a['nm_linguagem']."\" title=\"Idioma ".$a['nm_linguagem']."\" border=\"0\"></div>\n";
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_linguagem']."</td>\n";
          echo "      <td class=\"celConteudo\" style=\"width:5%;\">nr</td>\n";
        } else {
          echo "      <td class=\"celConteudo\"></td>\n";
          echo "      <td class=\"celConteudo\"></td>\n";
          echo "      <td class=\"celConteudo\"></td>\n";
          echo "      <td class=\"celConteudo\"></td>\n";
        }
        echo "    </tr>\n";      
      }
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" colspan=\"9\" style=\"text-align:center;\">\n";
      echo "  		    <input type=\"submit\" class=\"botaoGrande\" value=\"Pesquisar\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      
      echo "  </table>\n";

      echo "  </form>\n";  
    }                   
    
    public function retornaChamadaLinguagemCapa() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
    
      $cores = $cor->retornaCoresNaoUtilizadas();
      $cores_sorteadas = array();
      
      $areas = $this->selectLinguagem('1');
      $qtd_areas = count($areas);
      $areas_sorteadas = array();
      
      echo "    <div class=\"divConteudoLinguagem\">\n";
      echo "      <div class=\"divConteudoLinguagemAcesso\">\n";
      echo "        <div id=\"barra_areas_conhecimento\" class=\"divConteudoLinguagemAcessoChamada\">\n";
      if (!isset($_SESSION['life_codigo_areas_conhecimento_capa'])) {
        $_SESSION['life_codigo_areas_conhecimento_capa'] = $itens = array();
      }                                                                
      echo "          <p class=\"fontChamadaRelacaoLinguagem\">";
      $tamanho = 0;
      $limite = $conf->retornaNumeroLimiteCaracteresRelacaoChamadaLinguagemCapaSite();
      $concatenar = true;
      foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
        $dados = $this->selectDadosLinguagem($it);
        $tamanho_nome = strlen($dados['nm_linguagem']);
        $tamanho += $tamanho_nome + 4;
        if ($tamanho < $limite) {
          echo $dados['nm_linguagem']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoLinguagemLink\" alt=\"Retirar o Idioma ".$dados['nm_linguagem']." da Relação de Itens à serem Pesquisados\" title=\"Retirar o Idioma ".$dados['nm_linguagem']." da Relação de Itens à serem Pesquisados\">[x]</a>&nbsp;&nbsp;";
        } else {     
          if ($concatenar) {
            echo "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoLinguagemLink\" alt=\"Exibir Relação Completa de Itens à serem Pesquisados\" title=\"Exibir Relação Completa de Itens à serem Pesquisados\">[+]</a> ";
            $concatenar = false;
          }
        }
      }
      echo "          </p>\n";    
      echo "        </div>\n";
      echo "        <div class=\"divConteudoLinguagemAcessoAcao\">\n";
      echo "        <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento/pesquisar\"><img src=\"".$_SESSION['life_link_completo']."icones/selecionar_materiais.png\" alt=\"Pesquisar Conteúdos das Idiomas Selecionadas\" title=\"Pesquisar Conteúdos das Idiomas Selecionadas\" border=\"0\"></a>\n";
      echo "        </div>\n";
      echo "      </div>\n";
      echo "      <div class=\"divConteudoLinguagemContorno\">\n";
      echo "        <div class=\"divConteudoLinguagemCentro\">\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemAbreTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemFechaTopo\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemAbreLinha1\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=1; $i<=6; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoLinguagemItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemFechaLinha1\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemAbreLinha2\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=7; $i<=12; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoLinguagemItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemFechaLinha2\" style=\"background-color:".$cor.";\"></div>\n";      
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemAbreLinha3\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=13; $i<=17; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoLinguagemItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      if ($qtd_areas > 18) {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "          <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\"  alt=\"Listar Todas as Idiomas\" title=\"Listar Todas as Idiomas\" class=\"fontChamadaLinguagem\">\n";
        echo "            <div class=\"divConteudoLinguagemItem\" style=\"background-color:".$cor.";\"><label style=\"font-size: 16px;\"><br />Todas as<br />Áreas do<br />Conhecimento</label></div>\n";
        echo "          </a>\n";                                                                                                                 
      } elseif ($qtd_areas == 18) {
        $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
        $this->retornaElementoArea($area);
      } else {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "            <div class=\"divConteudoLinguagemItem\" style=\"background-color:".$cor.";\"></div>\n";
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemFechaLinha3\" style=\"background-color:".$cor.";\"></div>\n";         
                     
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemAbreBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoLinguagemItemFechaBase\" style=\"background-color:".$cor.";\"></div>\n";                         
      
      echo "        </div>\n";
      echo "      </div>\n";
      echo "    </div>\n"; 
      
      echo "    <div class=\"divTodosConteudoLinguagem\">\n";
      echo "      <a href=\"\"><img src=\"".$_SESSION['life_link_completo']."icones/todas_areas.png\" alt=\"Relacionar todas as Idiomas\" title=\"Relacionar todas as Idiomas\" border=\"0\"></a>\n";
      echo "    </div>\n";    
    }

    public function retornaElementoArea($area) {
      $conteudo = $this->tratarConteudoLinguagem($area);
      echo "          <a href=\"#\"  alt=\"Incluir o Idioma ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" title=\"Incluir o Idioma ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" class=\"fontChamadaLinguagem\" onClick=\"incluirLinguagemRelacao('".$conteudo['identificador']."');\">\n";
      echo "            <div class=\"divConteudoLinguagemItem\" style=\"background-color:".$area['ds_cor'].";\">\n";
      echo "              ".$conteudo['descricao']."\n";
      echo "            </div>\n";
      echo "          </a>\n";
    }
       
                                                                 */
//**************BANCO DE DADOS**************************************************    
    public function selectLinguagem($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_linguagens ".
              "WHERE cd_linguagem > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_linguagem ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA LINGUAGEM!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
       
    public function selectDadosLinguagem($cd_linguagem) {
      $sql  = "SELECT * ".
              "FROM life_linguagens ".
              "WHERE cd_linguagem = '$cd_linguagem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA LINGUAGEM!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereLinguagem($nm_linguagem, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_linguagens ".
             "(nm_linguagem, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_linguagem\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'linguagens');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA LINGUAGEM!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraLinguagem($cd_linguagem, $nm_linguagem, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_linguagens SET ".
             "nm_linguagem = \"$nm_linguagem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_linguagem= '$cd_linguagem' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'linguagens');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA LINGUAGEM!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>