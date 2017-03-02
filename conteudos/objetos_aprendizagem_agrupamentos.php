<?php
  class ObjetoAprendizagemAgrupamento {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item, $cd_objeto_aprendizagem, $acao) {

      if (isset($_GET['atv']))    {      $ativas = addslashes($_GET['atv']);          } else {      $ativas = 1;          }
      if (isset($_GET['cdc']))    {      $codigo = addslashes($_GET['cdc']);          } else {      $codigo = '';         }
      if (isset($_GET['tp']))     {      $tipo = addslashes($_GET['tp']);             } else {      $tipo = '';           }
      if (isset($_GET['tl']))     {      $tipo_link = addslashes($_GET['tl']);        } else {      $tipo_link = '';      }
      
      $_SESSION['life_pesquisa_secao'] = $secao;
      $_SESSION['life_pesquisa_subsecao'] = $subsecao;
      $_SESSION['life_pesquisa_item'] = $item;
      $_SESSION['life_pesquisa_cd_objeto_aprendizagem'] = $cd_objeto_aprendizagem;
      $_SESSION['life_pesquisa_acao'] = $acao;

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
        break;

        case "cadastrar":
          $link = $_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
          $this->montarFormularioCadastro($link, $cd_objeto_aprendizagem);
        break;
        
        case "pesquisar":
          require_once 'conteudos/objetos_aprendizagem_pesquisa.php';           $oap = new ObjetoAprendizagemPesquisa();
          $_SESSION['life_c_pesquisando'] = '1';
          $oap->pesquisarSimples($secao, $subsecao, $item, $ativas, 'oa');
          $link = $_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
          $this->montarFormularioCadastro($link, $cd_objeto_aprendizagem);
        break;

        case "limpar_pesquisa":
          unset($_SESSION['life_c_termo_1']);
          unset($_SESSION['life_c_tabela_1']);
          unset($_SESSION['life_c_campo_1']);
          unset($_SESSION['life_c_eh_proprietario']);
        
          $link = $_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
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

      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= $_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=1";           if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                    $opcao['descricao']= "Ativos";                                        $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= $_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=0";           if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                    $opcao['descricao']= "Inativos";                                      $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= $_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=2";           if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                    $opcao['descricao']= "Ativos/Inativos";                               $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&acao=cadastrar\"><img src=\"".$_SESSION['life_link_completo']."icones/novo_agrupamento.png\" alt=\"Novo agrupamento de objetos de aprendizagem\" title=\"Novo agrupamento de objetos de aprendizagem\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas) {
      $itens = $this->selectObjetosAprendizagemAgrupamentos($ativas, $cd_objeto_aprendizagem, '');    

      $mensagem = "Conteúdos ";
      echo "<h2>".$mensagem."</h2>\n";
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" style=\"width: 50px;\">\n";
      echo "        <img src=\"".$_SESSION['life_link_completo']."icones/seta_sobe.png\" alt=\"Ordem de apresentação\" title=\"Ordem de apresentação\" border=\"0\">\n";
      echo "        <img src=\"".$_SESSION['life_link_completo']."icones/seta_desce.png\" alt=\"Ordem de apresentação\" title=\"Ordem de apresentação\" border=\"0\">\n";
      echo "      </td>\n";
      echo "      <td class=\"celConteudo\" style=\"width: 60px;\">Ordem</td>\n";
      echo "      <td class=\"celConteudo\">Conteúdo</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
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
          echo "        <img src=\"".$_SESSION['life_link_completo']."icones/vaziop.png\">\n";
        } else {
          echo "        <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_agrupamento']."&acao=subir\"><img src=\"".$_SESSION['life_link_completo']."icones/ordem_subir.png\" alt=\"Subir na Ordem de Apresentação\" title=\"Subir na Ordem de Apresentação\" border=\"0\"></a>\n";
        }
        if ($id < count($itens)) {
          echo "        <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_agrupamento']."&acao=descer\"><img src=\"".$_SESSION['life_link_completo']."icones/ordem_descer.png\" alt=\"Descer na Ordem de Apresentação\" title=\"Descer na Ordem de Apresentação\" border=\"0\"></a>\n";
        } else {
          echo "        <img src=\"".$_SESSION['life_link_completo']."icones/vaziop.png\">\n";
        }
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_objeto_aprendizagem']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharObjetoAprendizagemAgrupamento($it['cd_agrupamento']);
        echo "        </span></a>\n";
        echo "        <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=agrupamento&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_agrupamento']."&acao=alt_status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"".$_SESSION['life_link_completo']."icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"".$_SESSION['life_link_completo']."icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
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
      $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data da Última Atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
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

      echo "<h2>Agrupamento de objetos de aprendizagem</h2>\n";
      $_SESSION['life_edicao']= 1;
      $this->imprimeFormularioCadastro($link, $cd_objeto_aprendizagem, $cd_agrupamento, $nr_ordem, $eh_ativo, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado);
    }
    
    private function imprimeFormularioCadastro($link, $cd_objeto_aprendizagem, $cd_agrupamento, $nr_ordem, $eh_ativo, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      require_once 'conteudos/objetos_aprendizagem_pesquisa.php';               $oap = new ObjetoAprendizagemPesquisa();
      
      echo "<div class=\"divDetalhesOACadastro\" id=\"detalhamentoOA\">\n";
      echo "  <div class=\"divDetalhesOACadastroConteudo\" id=\"detalhamentoOAConteudo\">\n";
      echo "  </div>\n";
      echo "</div>\n";
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_cadastro_agrupamento.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida_agrupamento(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_objeto_aprendizagem_agrupador', $cd_objeto_aprendizagem_agrupador);

      $itens = $oa->selectObjetosAprendizagem('1', '1', 'oa', '', '', 'c');
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
          echo "        <td class=\"celConteudo\" style=\"width:5%;text-align:center;\">\n";
          echo "          <a href=\"#\" class=\"dcontextoOA\">\n";
          echo "            <img src=\"".$_SESSION['life_link_completo']."icones/informacoes_o_a.png\" border=\"0\">\n";
          echo "            <span class=\"fontDetalhar\">\n";
          echo $oa->retornadetalhamentoObjetoAprendizagemBasico($it['cd_objeto_aprendizagem']);
          echo "            </span>\n";
          echo "          </a>\n";
          echo "        </td>\n";
          echo "      </tr>\n";
        }
      }     

      $util->campoHidden('nr_objetos_aprendizagem', $i);

      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"3\">\n";
      echo "  		    <input type=\"button\" class=\"botao\" value=\"Salvar\" onClick=\"valida_agrupamento(cadastro);\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      echo "    </table>\n";
      echo "  </form>\n";

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

      $nr_ordem = $this->selectMaiorNumeroOrdemObjetoAprendizagem($cd_objeto_aprendizagem_agrupador) + 1;

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
            if (!$this->inserirObjetoAprendizagemAgrupamento($cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupar, $eh_ativo, $nr_ordem)) {
              $erros = false;
            } else {
              $nr_ordem += 1;
            }
          }
        }      
      }
      
      if ($erros) {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas no agrupamento de objetos de aprendizagem!</p>\n";
      } else {
        echo "<p class=\"fontAgrupamentoSucesso\">Objetos de aprendizagem agrupados com sucesso!</p>\n";
      }
    } 

    public function alterarStatusItem($cd_agrupamento, $cd_objeto_aprendizagem_agrupador) {
      $dados = $this->selectDadosAgrupamento($cd_agrupamento);

      if ($dados['cd_objeto_aprendizagem_agrupador'] != $cd_objeto_aprendizagem_agrupador) {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao relacionar objeto de aprendizagem!</p>\n";
        return false;            
      }
      
      $cd_objeto_aprendizagem_agrupado = $dados['cd_objeto_aprendizagem_agrupado'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      
      if ($eh_ativo == 1) {        $eh_ativo= 0;      } else {        $eh_ativo= 1;      }
      if ($this->editarObjetoAprendizagemAgrupamento($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado, $nr_ordem, $eh_ativo)) {
        $this->ajustarOrdemObjetosAgrupados($cd_objeto_aprendizagem_agrupador);
        echo "<p class=\"fontAgrupamentoSucesso\">Status do agrupamento dos objetos de aprendizagem alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao alterar status do agrupamento de objetos de aprendizagem!</p>\n";
      }
    } 

    private function ajustarOrdemObjetosAgrupados($cd_objeto_aprendizagem_agrupador) {
      $itens = $this->selectObjetosAprendizagemAgrupamentos('1', $cd_objeto_aprendizagem_agrupador, '');

      $nr_ordem = 1;
      foreach ($itens as $it) {
        $this->editarObjetoAprendizagemAgrupamento($it['cd_agrupamento'], $cd_objeto_aprendizagem_agrupador, $it['cd_objeto_aprendizagem_agrupado'], $nr_ordem, '1');
        $nr_ordem += 1;
      }
    }

    public function alterarOrdemItem($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $acao) {
      $dados = $this->selectDadosAgrupamento($cd_agrupamento);

      if ($dados['cd_objeto_aprendizagem_agrupador'] != $cd_objeto_aprendizagem_agrupador) {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao relacionar objeto de aprendizagem!</p>\n";
        return false;            
      }
      
      $cd_objeto_aprendizagem_agrupado = $dados['cd_objeto_aprendizagem_agrupado'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      
      if ($acao == 'i') {
        $nr_ordem += 1;
        $dados = $this->selectObjetoAprendizagemNumeroOrdem($cd_objeto_aprendizagem_agrupador, $nr_ordem);
        $nr_ordem_outro = $dados['nr_ordem'] - 1;
      } elseif ($acao == 'd') {
        $nr_ordem -= 1;
        $dados = $this->selectObjetoAprendizagemNumeroOrdem($cd_objeto_aprendizagem_agrupador, $nr_ordem);
        $nr_ordem_outro = $dados['nr_ordem'] + 1;
      }

      if ($this->editarObjetoAprendizagemAgrupamento($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado, $nr_ordem, $eh_ativo)) {
        if ($this->editarObjetoAprendizagemAgrupamento($cd_agrupamento, $cd_objeto_aprendizagem_agrupador, $dados['cd_objeto_aprendizagem_agrupado'], $nr_ordem_outro, '1')) {
          echo "<p class=\"fontAgrupamentoSucesso\">Ordem do objeto de aprendizagem ajustada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao alterar a ordem do objeto de aprendizagem!</p>\n";
        }
      } else {
        echo "<p class=\"fontAgrupamentoAlerta\">Problemas ao alterar a ordem do objeto de aprendizagem!</p>\n";
      }
    }    
//**********************************EXIBIÇÃO************************************
    public function retornaListaAgrupamentosObjetoAprendizagem($cd_objeto_aprendizagem) {
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();

      $agrupamentos = $this->selectObjetosAprendizagemAgrupamentos('1', $cd_objeto_aprendizagem, '');
      
      if (count($agrupamentos) > 0) {
        echo "<hr>\n";
        echo "<div class=\"divTituloObjetoAprendizagem\">\n";
        echo "  <div class=\"divChamadaTitulo\">\n";
        echo "  <h1>objetos de aprendizagem Agrupados</h1>\n";
        echo "  </div>\n";
        echo "</div>\n";

        foreach ($agrupamentos as $ag) {
          $item = $oa->selectDadosCompletosObjetoAprendizagem($ag['cd_objeto_aprendizagem_agrupado']);
          echo "  <a href=\"".$_SESSION['life_link_completo']."objetos/".$item['lk_seo']."\">\n";
          echo "    <div class=\"divUmElementoCapaTopo\" style=\"background-color:".$item['ds_cor'].";\">\n";
          if ($item['ds_arquivo_imagem_especifica'] != '') {
            echo "      <img src=\"".$_SESSION['life_link_completo'].$item['ds_pasta_arquivo_imagem'].$item['ds_arquivo_imagem_especifica']."\" alt=\"".$item['nm_objeto_aprendizagem']."\" title=\"".$item['nm_objeto_aprendizagem']."\" border=\"0\" width=\"185\" height=\"185\" style=\"margin-top:1px;\">\n";
          } else {
            echo "      <img src=\"".$_SESSION['life_link_completo'].$item['ds_arquivo_imagem']."\" alt=\"".$item['nm_objeto_aprendizagem']."\" title=\"".$item['nm_objeto_aprendizagem']."\" border=\"0\" width=\"100%\">\n";
          }
          echo "      <p class=\"fontTituloItemTopoCapa\">".$item['ds_identifier']."</p>\n";
          echo "      <p class=\"fontChamadaItemTopoCapa\">".$item['nm_area_conhecimento']."</p>\n";
          echo "    </div>\n";
          echo "  </a>\n";
        }
        echo "  <div class=\"clear\"></div>\n";
      }

      $agrupamentos = $this->selectObjetosAprendizagemAgrupamentos('1', '', $cd_objeto_aprendizagem);

      if (count($agrupamentos) > 0) {
        echo "  <hr>\n";
        echo "<div class=\"divTituloObjetoAprendizagem\">\n";
        echo "  <div class=\"divChamadaTitulo\">\n";
        echo "  <h1>Agrupado nos objetos de aprendizagem</h1>\n";
        echo "  </div>\n";
        echo "</div>\n";

        foreach ($agrupamentos as $ag) {
          $item = $oa->selectDadosCompletosObjetoAprendizagem($ag['cd_objeto_aprendizagem_agrupador']);
          echo "  <a href=\"".$_SESSION['life_link_completo']."objetos/".$item['lk_seo']."\">\n";
          echo "    <div class=\"divUmElementoCapaTopo\" style=\"background-color:".$item['ds_cor'].";\">\n";
          if ($item['ds_arquivo_imagem_especifica'] != '') {
            echo "      <img src=\"".$_SESSION['life_link_completo'].$item['ds_pasta_arquivo_imagem'].$item['ds_arquivo_imagem_especifica']."\" alt=\"".$item['nm_objeto_aprendizagem']."\" title=\"".$item['nm_objeto_aprendizagem']."\" border=\"0\" width=\"185\" height=\"185\" style=\"margin-top:1px;\">\n";
          } else {
            echo "      <img src=\"".$_SESSION['life_link_completo'].$item['ds_arquivo_imagem']."\" alt=\"".$item['nm_objeto_aprendizagem']."\" title=\"".$item['nm_objeto_aprendizagem']."\" border=\"0\" width=\"100%\">\n";
          }
          echo "      <p class=\"fontTituloItemTopoCapa\">".$item['ds_identifier']."</p>\n";
          echo "      <p class=\"fontChamadaItemTopoCapa\">".$item['nm_area_conhecimento']."</p>\n";
          echo "    </div>\n";
          echo "  </a>\n";
        }
        echo "  <div class=\"clear\"></div>\n";
      }
    }
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

    public function selectDadosAgrupamento($cd_agrupamento) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_agrupamentos ".
              "WHERE cd_agrupamento= '$cd_agrupamento' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }    

    public function selectObjetoAprendizagemNumeroOrdem($cd_objeto_aprendizagem, $nr_ordem) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_agrupamentos ".
              "WHERE cd_objeto_aprendizagem_agrupador = '$cd_objeto_aprendizagem' ".
              "AND nr_ordem = '$nr_ordem' ".
              "AND eh_ativo = '1' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;
    }

    public function selectMaiorNumeroOrdemObjetoAprendizagem($cd_objeto_aprendizagem) {
      $sql  = "SELECT MAX(nr_ordem) numero ".
              "FROM life_objetos_aprendizagem_agrupamentos ".
              "WHERE cd_objeto_aprendizagem_agrupador = '$cd_objeto_aprendizagem' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM AGRUPAMENTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['numero'];        
    } 

    public function inserirObjetoAprendizagemAgrupamento($cd_objeto_aprendizagem_agrupador, $cd_objeto_aprendizagem_agrupado, $eh_ativo, $nr_ordem) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_objetos_aprendizagem_agrupamentos ".
             "(cd_objeto_aprendizagem_agrupador, cd_objeto_aprendizagem_agrupado, eh_ativo, nr_ordem, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_objeto_aprendizagem_agrupador\", \"$cd_objeto_aprendizagem_agrupado\", \"$eh_ativo\", \"$nr_ordem\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
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