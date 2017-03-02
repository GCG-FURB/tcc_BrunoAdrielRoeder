<?php
  class AreaConhecimento {
    
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
          $this->alterarSituacaoAtivoAreaConhecimento($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                 $opcao['descricao']= "Ativas";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                 $opcao['descricao']= "Inativas";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                 $opcao['descricao']= "Ativas/Inativas";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                 $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Área de Conhecimento\" title=\"Nova Área de Conhecimento\" border=\"0\"></a> \n";
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

      $mensagem = "Áreas de Conhecimento ";
      if ($ativas == 1)    {      $mensagem.= "Ativas ";         } elseif ($ativas == 0)    {       $mensagem.= "Inativas ";       }

      $itens = $this->selectAreasConhecimento($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Conteúdo:</td>\n";
      echo "      <td class=\"celConteudo\">Cor:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_area_conhecimento']."</td>\n";
        echo "      <td class=\"celConteudo\" style=\"color:".$it['ds_cor'].";\">".$it['nm_cor']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharAreaConhecimento($it['cd_area_conhecimento']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_area_conhecimento']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_area_conhecimento']."&acao=status\">";
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
    
    public function detalharAreaConhecimento($cd_area_conhecimento) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);
      
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
      $cd_area_conhecimento = "";
      $nm_area_conhecimento = "";
      $ds_area_conhecimento = "";
      $cd_cor = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Áreas de Conhecimento</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_area_conhecimento) {
      $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);

      $nm_area_conhecimento = $dados['nm_area_conhecimento'];
      $ds_area_conhecimento = $dados['ds_area_conhecimento'];
      $cd_cor = $dados['cd_cor'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Área de Conhecimento</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/cores.php';                                       $cor = new Cor();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_area_conhecimento.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_area_conhecimento', $cd_area_conhecimento);
      $util->linhaUmCampoText(1, 'Área de Conhecimento: ', 'nm_area_conhecimento', '150', '70', $nm_area_conhecimento);
      $util->linhaTexto(0, 'Descrição: ', 'ds_area_conhecimento', $ds_area_conhecimento, '5', '965');

      $cor->retornaSeletorCores($cd_cor);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      if ($cd_area_conhecimento > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_area_conhecimento'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_area_conhecimento = addslashes($_POST['cd_area_conhecimento']);
      $nm_area_conhecimento = $util->limparVariavel($_POST['nm_area_conhecimento']);
      $ds_area_conhecimento = $util->limparVariavel($_POST['ds_area_conhecimento']);
      $cd_cor = addslashes($_POST['cd_cor']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_area_conhecimento > 0) {
        if ($this->alteraAreaConhecimento($cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Área de Conhecimento editada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Área de Conhecimento, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereAreaConhecimento($nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Área de Conhecimento cadastrada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Área de Conhecimento!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoAreaConhecimento($cd_area_conhecimento) {
      $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);

      $nm_area_conhecimento = $dados['nm_area_conhecimento'];
      $ds_area_conhecimento = $dados['ds_area_conhecimento'];
      $cd_cor = $dados['cd_cor'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraAreaConhecimento($cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Área de Conhecimento alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da Área de Conhecimento!</p>\n";
      }
    }
            

    public function retornaSeletorAreasConhecimento($cd_area_conhecimento) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectAreasConhecimento('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione uma Área de Conhecimento';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_area_conhecimento'];          $opcao[]= $it['nm_area_conhecimento'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Áreas de Conhecimento ', 'cd_area_conhecimento', $cd_area_conhecimento, $opcoes);      
    }
    
    public function retornaDados($cd_area_conhecimento, $cd_general, $descricao) {
      require_once 'conteudos/sub_areas_conhecimento_general.php';              $sacg = new SubAreaConhecimentoGeneral();
      
      $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);

      $retorno = $descricao.': '.$dados['nm_area_conhecimento'].'<br />';
      $retorno.= $sacg->retornaDados($cd_area_conhecimento, $cd_general);
  
      return $retorno.'<br />';
    } 
    
    public function retornaSeletorOutrasAreasConhecimento($cd_area_conhecimento) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectAreasConhecimento('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione uma Área de Conhecimento';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        if ($cd_area_conhecimento != $it['cd_area_conhecimento']) { 
          $opcao= array();      $opcao[] = $it['cd_area_conhecimento'];          $opcao[]= $it['nm_area_conhecimento'];                   $opcoes[]= $opcao;
        }
      }
      $util->linhaSeletor('Áreas de Conhecimento: ', 'cd_area_conhecimento', $cd_area_conhecimento, $opcoes);      
    }  
    
    private function tratarConteudoAreaConhecimento($area_conhecimento) {
      $conteudo = array();
      
      $string = $area_conhecimento['nm_area_conhecimento']; 
      $tamanho = strlen($string);
      $maior_string = '0';
      $posicao = 0;
      $nome = '';
      $quebras = 0;
      for ($i=0; $i<$tamanho; $i++) {
        $posicao += 1;
        if ($string[$i] == '') {
          if ($maior_string < $posicao) {
            $maior_string = $posicao;
            $posicao = 0;  
          }
          $nome.= "<br \>";
          $quebras += 1;
        } else {
          $nome.= $string[$i];
        }
      }
      if ($maior_string == 0) {
        $maior_string = $tamanho;
      }
      if ($maior_string <= 5) {
        $tamanho_letra = '22';              $tamanho_quebra = '9';
      } elseif ($maior_string <= 10) {
        $tamanho_letra = '18';              $tamanho_quebra = '11';
      } elseif ($maior_string <= 15) {
        $tamanho_letra = '14';              $tamanho_quebra = '11';
      } elseif ($maior_string <= 20) {
        $tamanho_letra = '12';              $tamanho_quebra = '12';
      }
      
      if ($quebras == '0') {
        $nome = "<label style=\"font-size: ".$tamanho_quebra."px;\"><br /><br /><br /></label>".$nome;      
      }

      $conteudo['identificador'] = $area_conhecimento['cd_area_conhecimento'];
      
      $conteudo['descricao'] = "<label style=\"font-size: ".$tamanho_letra."px;\" onMouseOver=\"JavaScript:this.style.cursor='pointer'\">".$nome."</label>";
      $conteudo['nome'] = $area_conhecimento['nm_area_conhecimento'];
      return $conteudo;
    } 
    
    
    public function retornaSeletorAreasConhecimentoObjetoAprendizagem($cd_coverage, $campo, $tamanho, $exibir_ajuda, $cd_general, $descricao) {
      $cd_area_conhecimento = $cd_coverage;
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectAreasConhecimento('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                                   $opcao[]= $descricao;                                    $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_area_conhecimento'];           $opcao[]= $it['nm_area_conhecimento'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletorAcaoHint($descricao, $campo, $cd_area_conhecimento, $opcoes, $tamanho, false, $exibir_ajuda, " onChange=\"atualizarCampoSubAreasConhecimento();\" ");
      
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoCentralizado\" colspan=\"2\" id=\"celula_sub_areas_conhecimento\">\n";
      if ($cd_area_conhecimento != 0) {
        require_once 'conteudos/sub_areas_conhecimento.php';                    $sac = new SubAreaConhecimento();
        echo $sac->retornaCadastroSubAreasConhecimentoObjetoAprendizagem($cd_area_conhecimento, $tamanho, $cd_general);
      } else {
        echo "          <input type=\"text\" maxlength=\"0\" name=\"cd_sub_area_conhecimento\" id=\"cd_sub_area_conhecimento\" value=\"\" alt=\"Sub Área de Conhecimento\" title=\"Sub Área de Conhecimento\" class=\"fontConteudoCampoTextHint\" placeholder=\"Sub Área do Conhecimento\" tabindex=\"1\" style=\"height: 120px; width:840px;\" />\n";
        echo "          <a href=\"#\" class=\"dcontexto\">\n";                                                                                                                                                          
        echo "            <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "            <span class=\"fontdDetalhar\">\n";
        echo "              Selecione primeiro a ".$descricao."!\n";
        echo "            </span>\n";
        echo "          </a>\n";
        echo "          <input type=\"text\" maxlength=\"150\" name=\"ds_sub_area_conhecimento\" id=\"ds_sub_area_conhecimento\" value=\"\" style=\"width:840px;\" alt=\"Sub Área de Conhecimento - descritivo\" title=\"Sub Área de Conhecimento - descritivo\" class=\"fontConteudoCampoTextHint\" placeholder=\"Sub Área de Conhecimento - descritivo\" tabindex=\"1\"/>\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Apenas utilizar este campo se a Sub Área de Conhecimento desejada não estiver cadastrada.<br />\n";
        echo "            Campo do Tipo Texto com capacidade para até 150 caracteres\n";
        echo "          </span>\n";
        echo "        </a>\n";            
      }
      echo "        </td>\n";
      echo "      </tr>\n";         
    }


    public function retornaSeletorAreasConhecimentoFiltro($cd_coverage, $nome, $tamanho, $exibir_ajuda, $descricao) {
      $cd_area_conhecimento = $cd_coverage;
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectAreasConhecimento('1');

      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" alt=\"".$descricao."\" title=\"".$descricao."\" class=\"fontConteudoCampoSeletorHintFiltro\" placeholder=\"".$descricao."\" tabindex=\"1\">\n";
      echo "  			    <option ";
      if ($cd_area_conhecimento == '') {          echo " selected ";        }
      echo " value=\"0\">$descricao</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option ";
        if ($it['cd_area_conhecimento'] == $cd_area_conhecimento) {          echo " selected ";        }
        echo " value=\"".$it['cd_area_conhecimento']."\">".$it['nm_area_conhecimento']."</option>\n";
      }
      echo "          </select>\n";
      if ($exibir_ajuda == '1') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Para utilizar o Filtro por Área do Conhecimento, selecione a Área desejada\n";
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\">\n";
      }
    }
    
    
//*********************EXIBICAO PUBLICA*****************************************
    public function controleExibicaoPublica($pagina, $lista_paginas) {

      if (isset($lista_paginas[1])) {
      
      } else {
        $this->relacionarAreasConhecimento();
      }    
    }
    
    public function relacionarAreasConhecimento() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $areas = $this->selectAreasConhecimento('1');
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
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Área de Conhecimento</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "        <td class=\"celConteudoCabecalho\"	style=\"background-color: transparent;\"></td>\n";
      echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Área de Conhecimento</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "      </tr>\n";
      echo "    </thead>\n"; 
      for ($i=0; $i<$qtd; $i++) {
        $a = $lista_1[$i];
        echo "    <tr>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
        $campo = 'cd_area_conhecimento_'.$a['cd_area_conhecimento'];
        echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
        foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
          if ($it == $a['cd_area_conhecimento']) {           echo " checked=\"checked\" ";      }
        } 
        echo " value=\"".$a['cd_area_conhecimento']."\" class=\"fontConteudo\" />\n";        
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
        echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorAreaConhecimento\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Área de Conhecimento ".$a['nm_area_conhecimento']."\" title=\"Área de Conhecimento ".$a['nm_area_conhecimento']."\" border=\"0\"></div>\n";
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_area_conhecimento']."</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">nr</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:16%;\"></td>\n";
        if (isset($lista_2[$i])) {
          $a = $lista_2[$i];
          echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
          $campo = 'cd_area_conhecimento_'.$a['cd_area_conhecimento'];
          echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
          foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
            if ($it == $a['cd_area_conhecimento']) {           echo " checked=\"checked\" ";      }
          } 
          echo " value=\"".$a['cd_area_conhecimento']."\" class=\"fontConteudo\" />\n";        
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
          echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorAreaConhecimento\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Área de Conhecimento ".$a['nm_area_conhecimento']."\" title=\"Área de Conhecimento ".$a['nm_area_conhecimento']."\" border=\"0\"></div>\n";
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_area_conhecimento']."</td>\n";
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
    
    public function retornaChamadaAreasConhecimentoCapa() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
    
      $cores = $cor->retornaCoresNaoUtilizadas();
      $cores_sorteadas = array();
      
      $areas = $this->selectAreasConhecimento('1');
      $qtd_areas = count($areas);
      $areas_sorteadas = array();
      
      echo "    <div class=\"divConteudoAreasConhecimento\">\n";
      echo "      <div class=\"divConteudoAreasConhecimentoAcesso\">\n";
      echo "        <div id=\"barra_areas_conhecimento\" class=\"divConteudoAreasConhecimentoAcessoChamada\">\n";
      if (!isset($_SESSION['life_codigo_areas_conhecimento_capa'])) {
        $_SESSION['life_codigo_areas_conhecimento_capa'] = $itens = array();
      }                                                                
      echo "          <p class=\"fontChamadaRelacaoAreaConhecimento\">";
      $tamanho = 0;
      $limite = $conf->retornaNumeroLimiteCaracteresRelacaoChamadaAreasConhecimentoCapaSite();
      $concatenar = true;
      foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
        $dados = $this->selectDadosAreaConhecimento($it);
        $tamanho_nome = strlen($dados['nm_area_conhecimento']);
        $tamanho += $tamanho_nome + 4;
        if ($tamanho < $limite) {
          echo $dados['nm_area_conhecimento']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Retirar a Área de Conhecimento ".$dados['nm_area_conhecimento']." da Relação de Itens à serem Pesquisados\" title=\"Retirar a Área de Conhecimento ".$dados['nm_area_conhecimento']." da Relação de Itens à serem Pesquisados\">[x]</a>&nbsp;&nbsp;";
        } else {     
          if ($concatenar) {
            echo "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Exibir Relação Completa de Itens à serem Pesquisados\" title=\"Exibir Relação Completa de Itens à serem Pesquisados\">[+]</a> ";
            $concatenar = false;
          }
        }
      }
      echo "          </p>\n";    
      echo "        </div>\n";
      echo "        <div class=\"divConteudoAreasConhecimentoAcessoAcao\">\n";
      echo "        <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento/pesquisar\"><img src=\"".$_SESSION['life_link_completo']."icones/selecionar_materiais.png\" alt=\"Pesquisar Conteúdos das Áreas de Conhecimento Selecionadas\" title=\"Pesquisar Conteúdos das Áreas de Conhecimento Selecionadas\" border=\"0\"></a>\n";
      echo "        </div>\n";
      echo "      </div>\n";
      echo "      <div class=\"divConteudoAreasConhecimentoContorno\">\n";
      echo "        <div class=\"divConteudoAreasConhecimentoCentro\">\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemAbreTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemFechaTopo\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemAbreLinha1\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=1; $i<=6; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoAreasConhecimentoItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemFechaLinha1\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemAbreLinha2\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=7; $i<=12; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoAreasConhecimentoItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemFechaLinha2\" style=\"background-color:".$cor.";\"></div>\n";      
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemAbreLinha3\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=13; $i<=17; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoAreasConhecimentoItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      if ($qtd_areas > 18) {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "          <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\"  alt=\"Listar Todas as Áreas de Conhecimento\" title=\"Listar Todas as Áreas de Conhecimento\" class=\"fontChamadaAreaConhecimento\">\n";
        echo "            <div class=\"divConteudoAreasConhecimentoItem\" style=\"background-color:".$cor.";\"><label style=\"font-size: 16px;\"><br />Todas as<br />Áreas do<br />Conhecimento</label></div>\n";
        echo "          </a>\n";                                                                                                                 
      } elseif ($qtd_areas == 18) {
        $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
        $this->retornaElementoArea($area);
      } else {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "            <div class=\"divConteudoAreasConhecimentoItem\" style=\"background-color:".$cor.";\"></div>\n";
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemFechaLinha3\" style=\"background-color:".$cor.";\"></div>\n";         
                     
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemAbreBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoAreasConhecimentoItemFechaBase\" style=\"background-color:".$cor.";\"></div>\n";                         
      
      echo "        </div>\n";
      echo "      </div>\n";
      echo "    </div>\n"; 
      
      echo "    <div class=\"divTodosConteudoAreasConhecimento\">\n";
      echo "      <a href=\"\"><img src=\"".$_SESSION['life_link_completo']."icones/todas_areas.png\" alt=\"Relacionar todas as Áreas de Conhecimento\" title=\"Relacionar todas as Áreas de Conhecimento\" border=\"0\"></a>\n";
      echo "    </div>\n";    
    }

    public function retornaElementoArea($area) {
      $conteudo = $this->tratarConteudoAreaConhecimento($area);
      echo "          <a href=\"#\"  alt=\"Incluir a Área de Conhecimento ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" title=\"Incluir a Área de Conhecimento ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" class=\"fontChamadaAreaConhecimento\" onClick=\"incluirAreaConhecimentoRelacao('".$conteudo['identificador']."');\">\n";
      echo "            <div class=\"divConteudoAreasConhecimentoItem\" style=\"background-color:".$area['ds_cor'].";\">\n";
      echo "              ".$conteudo['descricao']."\n";
      echo "            </div>\n";
      echo "          </a>\n";
    }
       

//**************BANCO DE DADOS**************************************************    
    public function selectAreasConhecimento($eh_ativo) {
      $sql  = "SELECT ac.*, c.* ".
              "FROM life_areas_conhecimento ac, life_cores c ".
              "WHERE ac.cd_cor = c.cd_cor ";
      if ($eh_ativo != 2) {
        $sql.= "AND ac.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ac.nm_area_conhecimento ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectCoresAreasConhecimento() {
      $sql  = "SELECT c.cd_cor ".
              "FROM life_areas_conhecimento ac, life_cores c ".
              "WHERE ac.cd_cor = c.cd_cor ".
              "AND ac.eh_ativo = '1' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }
        
    public function selectDadosAreaConhecimento($cd_area_conhecimento) {
      $sql  = "SELECT * ".
              "FROM life_areas_conhecimento ".
              "WHERE cd_area_conhecimento = '$cd_area_conhecimento' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereAreaConhecimento($nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_areas_conhecimento ".
             "(nm_area_conhecimento, ds_area_conhecimento, cd_cor, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_area_conhecimento\", \"$ds_area_conhecimento\", \"$cd_cor\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_conhecimento');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraAreaConhecimento($cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_areas_conhecimento SET ".
             "nm_area_conhecimento = \"$nm_area_conhecimento\", ".
             "ds_area_conhecimento = \"$ds_area_conhecimento\", ".
             "cd_cor = \"$cd_cor\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_area_conhecimento= '$cd_area_conhecimento' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_conhecimento');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>