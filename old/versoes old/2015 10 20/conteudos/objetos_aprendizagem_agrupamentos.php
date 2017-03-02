<?php
  class ObjetoAprendizagemAgrupamento {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item, $cd_objeto_aprendizagem, $acao) {

      if (isset($_GET['atv']))    {      $ativas = addslashes($_GET['atv']);          } else {      $ativas = 1;          }
      if (isset($_GET['cdc']))    {      $codigo = addslashes($_GET['cdc']);          } else {      $codigo = '';         }
      if (isset($_GET['tp']))     {      $tipo = addslashes($_GET['tp']);             } else {      $tipo = '';           }
      if (isset($_GET['tl']))     {      $tipo_link = addslashes($_GET['tl']);        } else {      $tipo_link = '';      }
      
      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
        break;

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
          $this->montarFormularioCadastro($link, $cd_objeto_aprendizagem);
        break;
        
        case "limpar_pesquisa":
          unset($_SESSION['life_c_termo_1']);
          unset($_SESSION['life_c_tabela_1']);
          unset($_SESSION['life_c_campo_1']);
          unset($_SESSION['life_c_eh_proprietario']);
        
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
          $this->montarFormularioCadastro($link, $cd_objeto_aprendizagem);
        break;

        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao($cd_objeto_aprendizagem);
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
        break;        

/*
          case "editar":
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
            $this->montarFormularioEdicao($link, $cd_objeto_aprendizagem, $codigo);
          break;

                         */
          case "alt_status":
            $this->alterarStatusItem($codigo, $cd_objeto_aprendizagem);
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;

          case "subir":
            $this->alterarOrdemItem($codigo, $cd_objeto_aprendizagem, 'd');
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;

          case "descer":
            $this->alterarOrdemItem($codigo, $cd_objeto_aprendizagem, 'i');
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;
      }              
    }                                                     
    
    private function listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=1";                               $opcao['descricao']= "Ativos";                                        $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=0";                               $opcao['descricao']= "Inativos";                                      $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=2";                               $opcao['descricao']= "Ativos/Inativos";                               $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                                                         $opcao['descricao']= "----------------------------------------";      $opcoes[]= $opcao;

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&acao=cadastrar\"><img src=\"icones/novo_agrupamento.png\" alt=\"Novo Agrupamento de Objetos de Aprendizagem\" title=\"Novo Agrupamento de Objetos de Aprendizagem\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";    
    }

    private function listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas) {
      $itens = $this->selectObjetosAprendizagemAgrupamentos($ativas, $cd_objeto_aprendizagem, '');    

      $mensagem = "Conteúdos ";
      if ($ativas == 1) {        $mensagem.= "Ativos";      } elseif ($ativas == 0) {        $mensagem.= "Inativos";      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" style=\"width: 50px;\">\n";
      echo "        <img src=\"icones/seta_sobe.png\" alt=\"Ordem de Apresentação\" title=\"Ordem de Apresentação\" border=\"0\">\n";
      echo "        <img src=\"icones/seta_desce.png\" alt=\"Ordem de Apresentação\" title=\"Ordem de Apresentação\" border=\"0\">\n";
      echo "      </td>\n";
      echo "      <td class=\"celConteudo\" style=\"width: 60px;\">Ordem:</td>\n";
      echo "      <td class=\"celConteudo\">Conteúdo:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";  
      $primeiro = true;
      $id = 0;    
      foreach ($itens as $it) {
        $id += 1;
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">\n";
        if ($primeiro) {
          $primeiro = false;
          echo "        <img src=\"icones/vaziop.png\">\n";
        } else {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_agrupamento']."&acao=subir\"><img src=\"icones/ordem_subir.png\" alt=\"Subir na Ordem de Apresentação\" title=\"Subir na Ordem de Apresentação\" border=\"0\"></a>\n";
        }
        if ($id < count($itens)) {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_agrupamento']."&acao=descer\"><img src=\"icones/ordem_descer.png\" alt=\"Descer na Ordem de Apresentação\" title=\"Descer na Ordem de Apresentação\" border=\"0\"></a>\n";
        } else {
          echo "        <img src=\"icones/vaziop.png\">\n";
        }
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_objeto_aprendizagem']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharObjetoAprendizagemAgrupamento($it['cd_agrupamento']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_agrupamento']."&acao=alt_status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
echo "            <img src=\"icones/visualizar_o_a.png\" border=\"0\">\n";                     
        echo "      </td>\n";
        echo "    </tr>\n";              
      }
      echo "  </table>\n";
      echo "  <br /><br />\n";       
    }
    
    private function detalharObjetoAprendizagemAgrupamento($cd_agrupamento) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $dados = $this->selectDadosAgrupamento($cd_agrupamento);
     
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data da Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
    
    
    private function utilizarNumeroOrdem($cd_objeto_aprendizagem) {
      $ultimo_numero_ordem = $this->selectMaiorNumeroOrdemObjetoAprendizagem($cd_objeto_aprendizagem);
      return $ultimo_numero_ordem + 1; 
    }

    public function montarFormularioCadastro($link, $cd_objeto_aprendizagem) {
      $cd_agrupamento = "";
      $nr_ordem = $this->utilizarNumeroOrdem($cd_objeto_aprendizagem);
      $eh_ativo = "1";
      $cd_objeto_aprendizagem_agrupador = $cd_objeto_aprendizagem;
      $cd_objeto_aprendizagem_agrupado = "";

      echo "<h2>Agrupamento de Objetos de Aprendizagem</h2>\n";
      $_SESSION['life_edicao']= 1;
      $this->imprimeFormularioCadastro($link, $cd_objeto_aprendizagem, $cd_agrupamento, $nr_ordem, $eh_ativo, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado);
    }
    
    private function imprimeFormularioCadastro($link, $cd_objeto_aprendizagem, $cd_agrupamento, $nr_ordem, $eh_ativo, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      require_once 'conteudos/objetos_aprendizagem_pesquisa.php';               $oap = new ObjetoAprendizagemPesquisa();
      
      echo "<div class=\"divDetalhesOACadastro\" id=\"detalhamentoOA\">\n";
      echo "  <div class=\"divDetalhesOACadastroFechar\">\n";
      echo "    <br /><img src=\"icones/fechar_detalhes.png\" border=\"0\" alt=\"Fechar\" title=\"Fechar\" onMouseOver=\"this.style.cursor='hand';\" onClick=\"fecharDadosObjetoAprendizagem();\">\n";
      echo "  </div>\n";
      echo "  <div class=\"divDetalhesOACadastroConteudo\" id=\"detalhamentoOAConteudo\">\n";
      echo "  </div>\n";
      echo "</div>\n";
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_pesquisa_oa_simples.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=cadastrar\" onSubmit=\"return valida(this);\">\n";

      if (isset($_POST['termo_1']))         {        $termo_1 = $util->limparVariavel($_POST['termo_1']);                      $_SESSION['life_c_termo_1'] = $termo_1;                    } else {        if (isset($_SESSION['life_c_termo_1']))         {     $termo_1 = $_SESSION['life_c_termo_1'];                  } else {        $_SESSION['life_c_termo_1'] = '';             $termo_1 = '';            }      }
      if (isset($_POST['campo_1']))         {        $campo_1 = addslashes($_POST['campo_1']);                                 $_SESSION['life_c_campo_1'] = $campo_1;                    } else {        if (isset($_SESSION['life_c_campo_1']))         {     $campo_1 = $_SESSION['life_c_campo_1'];                  } else {        $_SESSION['life_c_campo_1'] = '';             $campo_1 = '';            }      }
      if (isset($_POST['tabela_1']))        {        $tabela_1 = addslashes($_POST['tabela_1']);                               $_SESSION['life_c_tabela_1'] = $tabela_1;                  } else {        if (isset($_SESSION['life_c_tabela_1']))        {     $tabela_1 = $_SESSION['life_c_tabela_1'];                } else {        $_SESSION['life_c_tabela_1'] = '';            $tabela_1 = '';           }      }
      if (isset($_POST['eh_proprietario'])) {        $eh_proprietario = addslashes($_POST['eh_proprietario']);                 $_SESSION['life_c_eh_proprietario'] = $eh_proprietario;    } else {        if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }      }

      echo "    <table class=\"tabConteudo\">\n";
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" colspan=\"4\">\n";
      echo "          Formulário para Pesquisa de Objetos de Aprendizagem (utilize para filtrar Objetos de Aprendizagem para agrupamento):\n";
      echo "        </td>\n";
      echo "      </tr>\n";                       
      $oap->retornaCamposPesquisaSimples(1, $tabela_1, $campo_1, $termo_1, $eh_proprietario);
      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\">&nbsp;</td>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"2\">\n";
      echo "  		    <input type=\"submit\" class=\"celConteudoBotao\" value=\"Pesquisar\">\n";
      echo "        </td>\n";
      echo "        <td class=\"celConteudoCentralizado\">\n";
      echo "  		    <a href=\"".$link."&acao=limpar_pesquisa\" class=\"fontLinkReduzido\">[... Limpar Pesquisa ...]</a>\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      echo "    </table>\n";
      echo "  </form>\n";      
      
      echo "    <hr>\n";

      include "js/js_cadastro_agrupamento.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida_agrupamento(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_objeto_aprendizagem_agrupador', $cd_objeto_aprendizagem_agrupador);

      $itens = $oa->selectObjetosAprendizagem('1', 'oa', '', '');
      $agrupados = $this->selectObjetosAprendizagemAgrupamentos('1', $cd_objeto_aprendizagem_agrupador, '');

      $i = 1;
      foreach ($agrupados as $a) {               
        $nome = 'cd_objeto_aprendizagem_agrupados_'.$i;
        $util->campoHidden($nome, $a['cd_objeto_aprendizagem_agrupado']);
        $i += 1;
      }
      $util->campoHidden('nr_objetos_aprendizagem_agrupados', $i);

      $i = 1;
      $style = "linhaOf"; 
      foreach ($itens as $it) {
        if ($it['cd_objeto_aprendizagem'] != $cd_objeto_aprendizagem_agrupador) {
          $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
          echo "      <tr class=\"".$style."\">\n";
          echo "        <td class=\"celConteudo\" style=\"width:5%;text-align:center;\">\n";
          $nome = 'cd_objeto_aprendizagem_agrupar_'.$i;
          $i += 1;
          echo "          <input type=\"checkbox\" name=\"".$nome."\" id=\"".$nome."\" ";
          $achou = false;
          foreach ($agrupados as $a) {
            if ($it['cd_objeto_aprendizagem'] == $a['cd_objeto_aprendizagem_agrupado']) {
              $achou = true;
            }
          }
          if ($achou) {
            echo " checked=\"checked\" disabled=\"false\" ";
          } 
          echo " value=\"".$it['cd_objeto_aprendizagem']."\" class=\"fontConteudo\" />\n";
          echo "        </td>\n";
          echo "        <td class=\"celConteudo\">\n";
          echo "          ".$it['nm_objeto_aprendizagem']."\n";
          echo "        </td>\n";
          echo "        <td class=\"celConteudo\" style=\"width:10%;text-align:center;\">\n";
          echo "          <a href=\"#\" class=\"dcontextoOA\">\n";
          echo "            <img src=\"icones/informacoes_o_a.png\" border=\"0\">\n";
          echo "            <span class=\"fontDetalhar\">\n";
          echo $oa->detalharObjetoAprendizagemBasico($it['cd_objeto_aprendizagem']);
          echo "            </span>\n";
          echo "          </a>\n";
          echo "            <img src=\"icones/detalhar_o_a.png\" border=\"0\" onMouseOver=\"this.style.cursor='hand';\" onClick=\"detalharDadosObjetoAprendizagem('".$it['cd_objeto_aprendizagem']."');\">\n";
echo "            <img src=\"icones/visualizar_o_a.png\" border=\"0\">\n";                     
          echo "        </td>\n";
          echo "      </tr>\n";
        }
      }     

      $util->campoHidden('nr_objetos_aprendizagem', $i);

      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"3\">\n";
      echo "  		    <input type=\"submit\" class=\"celConteudoBotao\" value=\"Salvar\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      echo "    </table>\n";
      echo "  </form>\n";

/*      
      include "js/js_cadastro_agrupamento.js";
      
      $oa->retornaCampoAgrupamentoObjetoAprendizagem($cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado);

*/             
      $util->posicionarCursor('cadastro', 'cd_objeto_aprendizagem_agrupador'); 
    }

    public function salvarCadastroAlteracao($cd_objeto_aprendizagem) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $erros = false;
      $cd_objeto_aprendizagem_agrupador = addslashes($_POST['cd_objeto_aprendizagem_agrupador']);
      $eh_ativo = '1';

      $nr_objetos_aprendizagem_agrupados = addslashes($_POST['nr_objetos_aprendizagem_agrupados']);
      $ignorar = array();
      for ($i=1; $i<$nr_objetos_aprendizagem_agrupados; $i++) {
        $nome = 'cd_objeto_aprendizagem_agrupados_'.$i;
        $ignorar[] = addslashes($_POST[$nome]);
      }
      
      $nr_objetos_aprendizagem = addslashes($_POST['nr_objetos_aprendizagem']);
      for ($i=1; $i<$nr_objetos_aprendizagem; $i++) {
        $nome = 'cd_objeto_aprendizagem_agrupar_'.$i;
        if (isset($_POST[$nome])) {
          $cd_objeto_aprendizagem_agrupar = addslashes($_POST[$nome]);
          $achou = false;
          foreach($ignorar as $ig) {
            if ($ig == $cd_objeto_aprendizagem_agrupar) {
              $achou = true;
            }
          }
          if (!$achou) {
            if (!$this->inserirObjetoAprendizagemAgrupamento($cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupar, $eh_ativo)) {
              $erros = false;
            }          
          }
        }      
      }
      
      if ($erros) {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas no agrupamento de Objetos de Aprendizagem!</p>\n";
      } else {
        echo "<p class=\"fontAgrupamentoSucesso\">Objetos de Aprendizagem agrupados com sucesso!</p>\n";
      }
    } 

    public function alterarStatusItem($cd_agrupamento, $cd_objeto_aprendizagem_agrupador) {
      $dados = $this->selectDadosAgrupamento($cd_agrupamento);

      if ($dados['cd_objeto_aprendizagem_agrupador'] != $cd_objeto_aprendizagem_agrupador) {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao relacionar Objeto de Aprendizagem!</p>\n";
        return false;            
      }
      
      $cd_objeto_aprendizagem_agrupado = $dados['cd_objeto_aprendizagem_agrupado'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      
      if ($eh_ativo == 1) {        $eh_ativo= 0;      } else {        $eh_ativo= 1;      }
      if ($this->editarObjetoAprendizagemAgrupamento($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado, $nr_ordem, $eh_ativo)) {
        echo "<p class=\"fontAgrupamentoSucesso\">Status do Agrupamento dos Objetos de Aprendizazem alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao alterar status do Agrupamento de Objetos de Aprendizagem!</p>\n";
      }
    } 

    public function alterarOrdemItem($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $acao) {
      $dados = $this->selectDadosAgrupamento($cd_agrupamento);

      if ($dados['cd_objeto_aprendizagem_agrupador'] != $cd_objeto_aprendizagem_agrupador) {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao relacionar Objeto de Aprendizagem!</p>\n";
        return false;            
      }
      
      $cd_objeto_aprendizagem_agrupado = $dados['cd_objeto_aprendizagem_agrupado'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      
      if ($acao == 'i') {        $nr_ordem += 1;      } elseif ($acao == 'd') {        $nr_ordem -= 1;      }

      if ($this->editarObjetoAprendizagemAgrupamento($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado, $nr_ordem, $eh_ativo)) {
        echo "<p class=\"fontAgrupamentoSucesso\">Ordem do Objeto de Aprendizagem ajustada com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao alterar a ordem do Objeto de Aprendizagem!</p>\n";
      }
    }    
                                      /*
    public function retornaTempoTotalObjetoAprendizagem($cd_objeto_aprendizagem) {    
      require_once 'conteudos/agrupamentos_links.php';                             $con_lin = new AgrupamentoLink();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      //require_once 'conteudos/agrupamentos_internos.php';                          $con_int = new AgrupamentoInterno();

      $agrupamentos = $this->selectObjetosAprendizagemAgrupamentosExibicao($cd_objeto_aprendizagem);
      $tm_total_video = '00:00:00';
      $videos = $con_lin->selectLinks('1', $cd_objeto_aprendizagem, 'VI', '1');
      foreach ($videos as $v) {
        $tm_total_video = $dh->somarHoras($tm_total_video, $v['tm_video']); 
      }
      foreach ($agrupamentos as $c) {
        $videos = $con_lin->selectLinks('1', $c['cd_agrupamento'], 'CC', '1');
        foreach ($videos as $v) {
          $tm_total_video = $dh->somarHoras($tm_total_video, $v['tm_video']);
        }        
        /*
        $agrupamentos_internos = $con_int->selectAgrupamentoInternos('1', $c['cd_agrupamento']);
        foreach ($agrupamentos_internos as $ci) {
          $videos = $con_lin->selectLinks('1', $ci['cd_agrupamento_interno'], 'CI', '1');
          foreach ($videos as $v) {
            $tm_total_video = $dh->somarHoras($tm_total_video, $v['tm_video']);
          }        
        }
        * 
      }      
      return $tm_total_video; 
    }  
    
    public function retornaQuantidadesAgrupamentos($cd_objeto_aprendizagem) {
      $agrupamentos = $this->selectObjetosAprendizagemAgrupamentosExibicao($cd_objeto_aprendizagem);
      return count($agrupamentos);
    }      
    
    public function retornaQuantidadeArquivosAgrupamentos($cd_objeto_aprendizagem) {
      require_once 'conteudos/arquivos.php';                                    $arq  = new Arquivo();
      $agrupamentos = $this->selectObjetosAprendizagemAgrupamentosExibicao($cd_objeto_aprendizagem);
      $total_arquivos = 0;
      foreach ($agrupamentos as $c) {
        $total_arquivos += $arq->retornaQuantidadeArquivosDownload('CC', $c['cd_agrupamento'], true);
      }
      return $total_arquivos;
    }
    
         */
//**********************************EXIBIÇÃO************************************
/*
    public function retornaListaAgrupamentosIniciaisObjetoAprendizagem($lista_paginas, $cd_objeto_aprendizagem) {
      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new ObjetoAprendizagemAlunoInscricao();
      require_once 'conteudos/agrupamentos_links.php';                             $con_lin = new AgrupamentoLink();
      //require_once 'conteudos/agrupamentos_internos.php';                          $con_int = new AgrupamentoInterno();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $acesso_liberado = $cur_alu_ins->validarInscricaoAlunoObjetoAprendizagem($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      $agrupamentos = $this->selectObjetosAprendizagemAgrupamentosExibicao($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      
      if (count($agrupamentos) > 0) {
        require_once 'conteudos/cursos_alunos_acessos_agrupamentos.php';           $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoAgrupamento();
        echo "          		    <p>&nbsp;</p>\n";
        echo "                  <div class=\"twelvecol column\">\n";
        echo "                    <h1>Aulas grátis</h1>\n";
        $qtd_aulas_gratis = $this->retornaListaGratisPago($lista_paginas, $cd_objeto_aprendizagem, $acesso_liberado, $agrupamentos, '1', '0');
        echo "          	        <br><br>\n";
        echo "                    <h1>Curso completo</h1>\n";
        $this->retornaListaGratisPago($lista_paginas, $cd_objeto_aprendizagem, $acesso_liberado, $agrupamentos, '0', $qtd_aulas_gratis);
        echo "                  </div>\n";
        if (isset($_SESSION['life_acesso_curso_situacao'])) {
          if ($_SESSION['life_acesso_curso_situacao'] == '1') {
            require_once 'login/login.php';                                         $log = new Login();
            if ($log->estaLogado()) {
              if (isset($_SESSION['life_acesso_curso_motivo'])) {
                if ($_SESSION['life_acesso_curso_motivo'] == '1') {
                  $mensagem = 'Prazo para finalização do curso expirado!';
                } elseif ($_SESSION['life_acesso_curso_motivo'] == '2') {
                  $mensagem = 'Nossos Sistema não identificou o Pagamento!';
                } elseif ($_SESSION['life_acesso_curso_motivo'] == '3') {
                  $mensagem = 'Não identificamos sua inscrição!';
                }
              } else {
                $mensagem = '';
              }
            } else {
              $mensagem = 'Você não está logado no sistema!';
            }
            echo "                  <div class=\"popup hidden\">\n";
            echo "                    <h2 class=\"popup-text\" style=\"background-color: rgba(0,0,0,0.4); padding: 10px 10px 10px 10px\">Acesso Bloqueado:<br />".$mensagem."</h2>\n";
            echo "                  </div>\n";
          }
        } else {
          echo "                  <div class=\"popup hidden\">\n";
          echo "                    <h2 class=\"popup-text\" style=\"background-color: rgba(0,0,0,0.4); padding: 10px 10px 10px 10px\">Acesso Bloqueado:<br />Você não está logado no sistema!</h2>\n";
          echo "                  </div>\n";
        }
        echo "                  <div>&nbsp;</div>\n";
        echo "                  <div>&nbsp;</div>\n";
        echo "                  <div>&nbsp;</div>\n";
        echo "          	    </div>\n";
      }
    }
    
    
    public function retornaListaGratisPago($lista_paginas, $cd_objeto_aprendizagem, $acesso_liberado, $agrupamentos, $eh_gratis, $qt_aulas_gratis) {
      require_once 'conteudos/agrupamentos_links.php';                             $con_lin = new AgrupamentoLink();
      //require_once 'conteudos/agrupamentos_internos.php';                          $con_int = new AgrupamentoInterno();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      require_once 'conteudos/cursos_alunos_acessos_agrupamentos.php';             $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoAgrupamento();
      
      
      $qtd = 0;
      foreach ($agrupamentos as $c) {
        $videos = $con_lin->selectLinks('1', $c['cd_agrupamento'], 'CC', '1');
        foreach ($videos as $v) {
          if ($v['eh_publico'] == $eh_gratis) {
            echo "                    <div class=\"lessons-listing\">\n";
            echo "                      <div class=\"lesson-item \">\n";
            echo "                        <div class=\"lesson-title\">\n";
            echo "                          <h4 class=\"nomargin\">&nbsp;</h4>\n";
            if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
              echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/agrupamento/".$c['lk_seo']."\" title=\"Assistir vídeo\">".$c['cd_objeto_aprendizagem_agrupador']."</a>\n";
            } else {
              echo "                          ".$c['cd_objeto_aprendizagem_agrupador']."\n";
            }
            if ((isset($_SESSION['life_acesso_curso_situacao'])) && ($_SESSION['life_acesso_curso_situacao'] == '1')) {
              echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">\n";
              echo "                          &nbsp;&nbsp;&nbsp;&nbsp;\n";
              $cur_alu_ace_con->retornaStatusAcessoAluno($cd_objeto_aprendizagem['cd_objeto_aprendizagem'], $c['cd_agrupamento']);
              echo "                          </div>\n";            
            }            
            echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">(".$v['tm_video'].")</div>\n";
            echo "                        </div>\n";
            echo "                        <div class=\"lesson-attachments\" style=\"width:25px;\">\n";
            if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
              echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/agrupamento/".$c['lk_seo']."\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
            } else {
              echo "                          <a href=\"\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
            }
            echo "                        </div>\n";
            echo "                      </div>\n";
            echo "                    </div>\n";        
            echo "          	        <br>\n";
            $qtd += 1;
          } 
        }        
        /*
        $agrupamentos_internos = $con_int->selectAgrupamentoInternos('1', $c['cd_agrupamento']);
        foreach ($agrupamentos_internos as $ci) {
          $videos = $con_lin->selectLinks('1', $ci['cd_agrupamento_interno'], 'CI', '1');
          foreach ($videos as $v) {
            if ($v['eh_publico'] == $eh_gratis) {
              echo "                    <div class=\"lessons-listing\">\n";
              echo "                      <div class=\"lesson-item \">\n";
              echo "                        <div class=\"lesson-title\">\n";
              echo "                          <h4 class=\"nomargin\">&nbsp;</h4>\n";
              if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
                echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/agrupamento/".$c['lk_seo']."\" title=\"Assistir vídeo\">".$v['nm_link']."</a>\n";
              } else {
                echo "                          ".$v['nm_link']."\n";
              }
              echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">(".$v['tm_video'].")</div>\n";
              echo "                        </div>\n";
              echo "                        <div class=\"lesson-attachments\" style=\"width:25px;\">\n";
              if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
                echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/agrupamento/".$c['lk_seo']."\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
              } else {
                echo "                          <a href=\"\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
              }
              echo "                        </div>\n";
              echo "                      </div>\n";
              echo "                    </div>\n";        
              echo "          	        <br>\n";
              $qtd += 1;
            } 
          }        
        }
        * 
      }    
      
      if (($eh_gratis == '1') && ($qtd == 0)) {
        echo "                    <div class=\"lessons-listing\">\n";
        echo "                      <div class=\"lesson-item \">\n";
        echo "                        <div class=\"lesson-title\">\n";
        echo "                          Não há aulas grátis para este curso\n";
        echo "                        </div>\n";
        echo "                      </div>\n";
        echo "                    </div>\n";        
        echo "          	        <br>\n";        
      } elseif (($eh_gratis == '0') && ($qtd == 0)) {
        echo "                    <div class=\"lessons-listing\">\n";
        echo "                      <div class=\"lesson-item \">\n";
        echo "                        <div class=\"lesson-title\">\n";
        if ($qt_aulas_gratis > 0) {
          echo "                          Todas as aulas deste curso são grátis\n";
        } else {
          echo "                          Não há aulas cadastradas para este curso\n";
        }
        echo "                        </div>\n";
        echo "                      </div>\n";
        echo "                    </div>\n";        
        echo "          	        <br>\n";        
      }
    }
    
    
    public function retornaListaAgrupamentosObjetoAprendizagem($lista_paginas, $cd_objeto_aprendizagem) {
      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new ObjetoAprendizagemAlunoInscricao();
      require_once 'conteudos/agrupamentos_links.php';                             $con_lin = new AgrupamentoLink();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $acesso_liberado = $cur_alu_ins->validarInscricaoAlunoObjetoAprendizagem($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      $agrupamentos = $this->selectObjetosAprendizagemAgrupamentosExibicao($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      
      if (count($agrupamentos) > 0) {
        require_once 'conteudos/cursos_alunos_acessos_agrupamentos.php';           $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoAgrupamento();
        echo "<h2>Conteúdos</h2>\n";
        foreach ($agrupamentos as $c) {
          $videos = $con_lin->selectLinks('1', $c['cd_agrupamento'], 'CC', '1');
          $tm_video = "00:00:00";
          foreach ($videos as $v) {
            $tm_video = $dh->somarHoras($tm_video, $v['tm_video']);
          }        
          echo "                    <div class=\"lessons-listing\">\n";
          echo "                      <div class=\"lesson-item \">\n";
          echo "                        <div class=\"lesson-title\">\n";
          echo "                          <h4 class=\"nomargin\">&nbsp;</h4>\n";
          if (($c['eh_publico'] == '1') || ($acesso_liberado)) {
            echo "<a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/agrupamento/".$c['lk_seo']."\">".$c['cd_objeto_aprendizagem_agrupador']."</a>\n";
          } else {
            echo $c['cd_objeto_aprendizagem_agrupador']."\n";
          }
          echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">\n";
          if ((isset($_SESSION['life_acesso_curso_situacao'])) && ($_SESSION['life_acesso_curso_situacao'] == '1')) {
            $cur_alu_ace_con->retornaStatusAcessoAluno($cd_objeto_aprendizagem['cd_objeto_aprendizagem'], $c['cd_agrupamento']);
          }            
          echo "                          </div>\n";
          echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">(".$tm_video.")&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
          echo "                        </div>\n";
          
          echo "                        <div class=\"lesson-attachments\" style=\"width:25px;\">\n";
          if (($c['eh_publico'] == '1') || ($acesso_liberado)) {
            echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/agrupamento/".$c['lk_seo']."\" class=\"video\"></a>\n";
          } else {
            echo "                          <a href=\"\" class=\"video\"></a>\n";
          }
          echo "                        </div>\n";
          echo "                      </div>\n";
          echo "                    </div>\n";        
          echo "          	        <br>\n";
        }                                 
      } else {
        echo "<br /><br />\n";
        echo "<p class=\"fontAgrupamentoAlerta\">Não há conteúdos cadastrados para este curso!</p>\n";
        echo "<br /><br />\n";
      }
    }

    public function retornaAgrupamentoObjetoAprendizagem($lista_paginas, $cd_objeto_aprendizagem) {
      if (isset($lista_paginas[3])) {
        require_once 'login/login.php';                                         $login = new Login();
        
        $lk_seo = addslashes($lista_paginas[3]);
        if ($login->estaLogado()) {
          $this->listarAgrupamentoCompletoObjetoAprendizagem($lk_seo, $cd_objeto_aprendizagem);
        } else {
          $this->retornaJustificativaAcesso($lk_seo, $cd_objeto_aprendizagem);
        } 
      
      }
    }
    
    public function listarAgrupamentoCompletoObjetoAprendizagem($lk_seo, $cd_objeto_aprendizagem) {
      $dados = $this->selectDadosObjetoAprendizagemAgrupamentoSEO($cd_objeto_aprendizagem, $lk_seo);

      if ($dados['cd_objeto_aprendizagem_agrupamento'] > 0) {
        require_once 'conteudos/cursos_alunos_inscricoes.php';                  $cur_alu_ins = new ObjetoAprendizagemAlunoInscricao();
        $acesso_liberado = $cur_alu_ins->validarInscricaoAlunoObjetoAprendizagem($cd_objeto_aprendizagem);
        if ($acesso_liberado) {
          require_once 'conteudos/cursos_alunos_acessos_agrupamentos.php';         $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoAgrupamento();
          $cur_alu_ace_con->alunoJaAcessouAgrupamento($cd_objeto_aprendizagem, $dados['cd_agrupamento']);
        }
        if (($acesso_liberado) || ($dados['eh_publico'])) {                               
          require_once 'conteudos/fotos.php';                                   $foto = new Fotos();
          require_once 'conteudos/arquivos.php';                                $arq  = new Arquivo();
          require_once 'conteudos/agrupamentos_links.php';                         $con_lin = new AgrupamentoLink();
          //require_once 'conteudos/agrupamentos_internos.php';                      $con_int = new AgrupamentoInterno();

          echo "  <h2>".$dados['cd_objeto_aprendizagem_agrupador']."</h2>\n";
          
          echo "  <p>".nl2br($dados['cd_objeto_aprendizagem_agrupado'])."</p>\n";

          $tp_link = '1'; //Vídeos
          $con_lin->retornaRelacaoLinks('CC', $dados['cd_agrupamento'], $tp_link, $acesso_liberado);
          
          $foto->exibeFotosAreaCentral($dados['cd_agrupamento'], 'CC');

          $tp_link = '2'; //Áudios
          $con_lin->retornaRelacaoLinks('CC', $dados['cd_agrupamento'], $tp_link, $acesso_liberado);
          $tp_link = '4'; //Referências
          $con_lin->retornaRelacaoLinks('CC', $dados['cd_agrupamento'], $tp_link, $acesso_liberado);

          $arq->retornaRelacaoArquivosDownload('CC', $dados['cd_agrupamento'], $acesso_liberado);

          //$con_int->listarAgrupamentoCompletoObjetoAprendizagem($dados['cd_agrupamento'], $acesso_liberado);
        } else {
          echo "<p class=\"fontAgrupamentoAlerta\">".
               "  Desculpe mas você não tem direito de acesso à este Conteúdo!<br />".
               "  Verifique se está inscrito no curso, se sua inscrição está quitada e,<br />".
               "  no caso de irregularidades ou dúvidas, ".
               "  utilize a seção <a href=\"".$_SESSION['life_link_completo']."fale-conosco\" class=\"fontLinkPublico\">Fale Conosco</a>!".               
               "</p>\n";
        }
      } else {
        echo "<p class=\"fontAgrupamentoAlerta\">Conteúdo não encontrado!</p>\n";
      }
    }    
    
    public function retornaJustificativaAcesso($lk_seo, $cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      
      $dados = $this->selectDadosObjetoAprendizagemAgrupamentoSEO($cd_objeto_aprendizagem, $lk_seo);
      
      if ($dados['eh_publico'] == '1') {
        $ds_justificativa_acesso_nao_logado_publico = $conf->retornaJustificativaAcessoNaoLogadoPublico();
        echo "<p class=\"fontAgrupamentoAlerta\">".nl2br($ds_justificativa_acesso_nao_logado_publico)."</p>\n";
      } else {
        $ds_justificativa_acesso_nao_logado_pago = $conf->retornaJustificativaAcessoNaoLogadoPago();
        echo "<p class=\"fontAgrupamentoAlerta\">".nl2br($ds_justificativa_acesso_nao_logado_pago)."</p>\n";
      }
      echo "<br />\n";
      echo "<p class=\"fontAgrupamentoAlerta\"><a href=\"".$_SESSION['life_link_completo']."efetuar-login\" class=\"fontLinkObjetoAprendizagem\">Efetue Login</a> para continuar!</p>\n"; 
    }
    
    public function contabilizarAcessoAlunoAgrupamento() {
    
    }
         */
//**************BANCO DE DADOS**************************************************    
    public function selectObjetosAprendizagemAgrupamentos($eh_ativo, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado) {
      $sql  = "SELECT a.*, oa.nm_objeto_aprendizagem ".
              "FROM life_objetos_aprendizagem_agrupamentos a, life_objetos_aprendizagem oa ".
              "WHERE a.cd_objeto_aprendizagem_agrupado = oa.cd_objeto_aprendizagem ";
      if ($cd_objeto_aprendizagem_agrupador != '') {
        $sql.= "AND a.cd_objeto_aprendizagem_agrupador = '$cd_objeto_aprendizagem_agrupador' ";
      }
      if ($cd_objeto_aprendizagem_agrupado != '') {
        $sql.= "AND a.cd_objeto_aprendizagem_agrupado = '$cd_objeto_aprendizagem_agrupado' ";
      }
      if ($eh_ativo == '1') {
        $sql.= "AND oa.eh_ativo = '$eh_ativo' ".
               "AND a.eh_ativo = '$eh_ativo' ";
      } elseif ($eh_ativo == '0') {
        $sql.= "AND a.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY a.nr_ordem, oa.nm_objeto_aprendizagem ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
                                    /*
    public function selectObjetosAprendizagemAgrupamentosExibicao($cd_objeto_aprendizagem) {
      $sql  = "SELECT c.cd_objeto_aprendizagem_agrupador, c.cd_agrupamento, cc.lk_seo ".
              "FROM life_objetos_aprendizagem_agrupamentos cc, life_agrupamentos c ".
              "WHERE cc.cd_agrupamento = c.cd_agrupamento ".
              "AND cc.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
              "AND cc.eh_ativo = '1' ".
              "ORDER BY cc.nr_ordem, c.cd_objeto_aprendizagem_agrupador ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
                                      *
    public function selectDadosObjetoAprendizagemAgrupamento($cd_agrupamento) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_agrupamentos ".
              "WHERE cd_agrupamento= '$cd_agrupamento' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
                                        */
    public function selectDadosAgrupamento($cd_agrupamento) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_agrupamentos ".
              "WHERE cd_agrupamento= '$cd_agrupamento' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }    
                                        /*
    public function selectDadosCompletosObjetoAprendizagemAgrupamento($cd_objeto_aprendizagem, $cd_agrupamento) {
      $sql  = "SELECT cur.*, con.*, cur_con.* ".
              "FROM life_objetos_aprendizagem_agrupamentos cur_con, life_agrupamentos con, life_cursos cur ".
              "WHERE cur_con.cd_agrupamento = con.cd_agrupamento ".
              "AND cur_con.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".
              "AND cur_con.cd_agrupamento= '$cd_agrupamento' ".
              "AND cur_con.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function selectDadosObjetoAprendizagemAgrupamentoSEO($cd_objeto_aprendizagem, $lk_seo) {
      $sql  = "SELECT con.*, cur_con.* ".
              "FROM life_objetos_aprendizagem_agrupamentos cur_con, life_agrupamentos con ".
              "WHERE cur_con.cd_agrupamento = con.cd_agrupamento ".
              "AND cur_con.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
              "AND cur_con.lk_seo = '$lk_seo' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    } 
                                                            */
    public function selectMaiorNumeroOrdemObjetoAprendizagem($cd_objeto_aprendizagem) {
      $sql  = "SELECT MAX(nr_ordem) numero ".
              "FROM life_objetos_aprendizagem_agrupamentos ".
              "WHERE cd_objeto_aprendizagem_agrupador = '$cd_objeto_aprendizagem' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['numero'];        
    } 

    public function inserirObjetoAprendizagemAgrupamento($cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_objetos_aprendizagem_agrupamentos ".
             "(cd_objeto_aprendizagem_agrupador, cd_objeto_aprendizagem_agrupado, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_objeto_aprendizagem_agrupador\", \"$cd_objeto_aprendizagem_agrupado\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_agrupamentos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $saida = mysql_affected_rows();
      return $saida;      
    }
                                    
    public function editarObjetoAprendizagemAgrupamento($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado, $nr_ordem, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_objetos_aprendizagem_agrupamentos SET ".
             "nr_ordem = \"$nr_ordem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_objeto_aprendizagem_agrupador = '$cd_objeto_aprendizagem_agrupador' ".
             "AND cd_objeto_aprendizagem_agrupado = '$cd_objeto_aprendizagem_agrupado' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_agrupamentos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

  }
?>