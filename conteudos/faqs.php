<?php
  class FAQ {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['ct']))    {      $categoria = addslashes($_GET['ct']);       } else {      $categoria = '';      }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $categoria, $ativas);
          $this->listarItens($secao, $subsecao, $item, $categoria, $ativas);
        break;

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas;
          $this->montarFormularioCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $categoria, $ativas);
          $this->listarItens($secao, $subsecao, $item, $categoria, $ativas);
        break;  

        case "status":
          $this->alterarStatus($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $categoria, $ativas);
          $this->listarItens($secao, $subsecao, $item, $categoria, $ativas);
        break;
        
        case "secao":
          $this->listarAcoes($secao, $subsecao, $item, $categoria, $ativas);
          require_once 'menu/menu.php';                                         $obj = new Menu();
          $item_edicao = '2'; //item que é exibido ao público
          $_SESSION['life_edicao'] = '1';
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas;
          $obj->montarFormularioEdicao($link, $item_edicao, 'salvar_secao');
        break;

        case "salvar_secao":
          require_once 'menu/menu.php';                                         $obj = new Menu();
          if (isset($_SESSION['life_edicao'])) {
            $obj->salvarEdicaoItemMenu();
            unset($_SESSION['life_edicao']);
          }
          $this->listarAcoes($secao, $subsecao, $item, $categoria, $ativas);
          $this->listarItens($secao, $subsecao, $item, $categoria, $ativas);
        break;
      }
    }

    private function listarAcoes($secao, $subsecao, $item, $categoria, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/faqs_categorias.php';                             $faq_cat = new FAQCategoria();
      
      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=1";               if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                $opcao['descricao']= "Ativas";                                  $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=0";               if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                $opcao['descricao']= "Inativas";                                $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=2";               if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }                $opcao['descricao']= "Ativas/Inativas";                         $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }


      $opcoes_2= array();
      $id = 1;
      $itens = $faq_cat->selectCategorias('1');
      if (count($itens) > 0) {
        foreach ($itens as $it) {
          $opcao= array();
          $opcao['indice']= $id; $id+=1;
          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$it['cd_categoria']."&at=".$ativas;
          if($it['cd_categoria'] == $categoria) { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }
          $opcao['descricao']= $it['nm_categoria'];
          $opcoes_2[]= $opcao;
        }
        $opcao= array();
        $opcao['indice']= $id; $id+=1;
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=0&at=".$ativas;
        if($categoria == '') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }
        $opcao['descricao']= "Todas as seções";
        $opcoes_2[]= $opcao;
      }
      foreach ($opcoes_2 as $op) {        $nome = 'comandos_filtros_2_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&acao=secao\"><img src=\"icones/secao_capa.png\" alt=\"Editar introdução da seção como usar\" title=\"Editar introdução da seção como usar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova pergunta para a seção como usar\" title=\"Nova pergunta para a seção como usar\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "  <select name=\"comandos_filtros_2\" id=\"comandos_filtros_2\" class=\"fontComandosFiltros\" onChange=\"navegar(2);\" alt=\"Filtro de seção\" title=\"Filtro de seção\">\n";
      foreach ($opcoes_2 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";

      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $categoria, $ativas) {
      require_once 'conteudos/faqs_categorias.php';                             $cat = new FAQCategoria();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      
      $nr_caracteres_abreviar_pergunta_faq_restrita = $conf->retornaNumeroCaracteresResumoPerguntasComoUsarRestrito();
      $nr_caracteres_abreviar_resposta_faq_restrita = $conf->retornaNumeroCaracteresResumoRespostasComoUsarRestrito();
      
      $itens = $this->selectFAQS($ativas, $categoria);    

      $mensagem = "Perguntas ";
      echo "<h2>".$mensagem."</h2>\n";
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Pergunta</td>\n";
      echo "      <td class=\"celConteudo\">Ordem</td>\n";
      echo "      <td class=\"celConteudo\">Seção</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
      echo "    </tr>\n";
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\"><b>";
        if ($nr_caracteres_abreviar_pergunta_faq_restrita > 0) {          echo $util->abreviar($it['ds_pergunta'], $nr_caracteres_abreviar_pergunta_faq_restrita);        } else {          echo $it['ds_pergunta'];        }
        echo "</b><br />";
        if ($nr_caracteres_abreviar_resposta_faq_restrita > 0) {          echo $util->abreviar($it['ds_resposta'], $nr_caracteres_abreviar_resposta_faq_restrita);        } else {          echo $it['ds_resposta'];        }
        echo "</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_categoria']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharFAQ($it['cd_faq']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&cd=".$it['cd_faq']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\"border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&cd=".$it['cd_faq']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\"border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\"border=\"0\"></a>\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
    }

    private function detalharFAQ($cd_faq) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosFAQ($cd_faq);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do sadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última atualização por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data da última atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }

    private function montarFormularioCadastro($link) {
      $cd_faq = "";
      $cd_categoria = "";
      $ds_pergunta = "";
      $ds_resposta = "";
      $eh_ativo = "1";
      $nr_ordem = $this->selectMaiorNumeroOrdem();
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de perguntas</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $nr_ordem);
    }

    private function montarFormularioEdicao($link, $cd_faq) {
      $dados= $this->selectDadosFAQ($cd_faq);

      $cd_categoria = $dados['cd_categoria'];
      $ds_pergunta = $dados['ds_pergunta'];
      $ds_resposta = $dados['ds_resposta'];
      $eh_ativo = $dados['eh_ativo'];
      $nr_ordem = $dados['nr_ordem'];

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de pergunta</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $nr_ordem);
    }    

    private function imprimeFormularioCadastro($link, $cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $nr_ordem) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/faqs_categorias.php';                             $faq_cat = new FAQCategoria();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_faq.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_faq', $cd_faq);

      $util->linhaTexto(1, 'Pergunta ', 'ds_pergunta', $ds_pergunta, '5', '100');
      $util->linhaTexto(1, 'Resposta ', 'ds_resposta', $ds_resposta, '10', '100');

      $faq_cat->retornaSeletorCategorias($cd_categoria);

      $opcoes= array();
      for ($i=1; $i<($this->selectMaiorNumeroOrdem()+50); $i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;      $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Ordem ', 'nr_ordem', $nr_ordem, $opcoes, '100');

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativa ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      
      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'ds_pergunta'); 
    }
                         
    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_faq = addslashes($_POST['cd_faq']);
      $cd_categoria = addslashes($_POST['cd_categoria']);
      $ds_pergunta = $util->limparVariavel($_POST['ds_pergunta']);
      $ds_resposta = $util->limparVariavel($_POST['ds_resposta']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $nr_ordem = addslashes($_POST['nr_ordem']);

      $lk_seo = $util->retornaLinkSEO($ds_pergunta, 'life_faqs', 'lk_seo', '150', $cd_faq);

      if ($cd_faq > 0) {
        if ($this->alterarFAQ($cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo, $nr_ordem)) {
          echo "<p class=\"fontConteudoSucesso\">Pergunta editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da pergunta, ou nenhuma informação alterada!</p>\n";
        }  
      } else {
        if ($this->inserirFAQ($cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo, $nr_ordem)) {
          echo "<p class=\"fontConteudoSucesso\">Pergunta cadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da pergunta!</p>\n";
        }
      }
    } 

    private function alterarStatus($cd_faq) {
      $dados= $this->selectDadosFAQ($cd_faq);

      $cd_categoria = $dados['cd_categoria'];
      $ds_pergunta = $dados['ds_pergunta'];
      $ds_resposta = $dados['ds_resposta'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];
      $nr_ordem = $dados['nr_ordem'];
                
      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
    
      if ($this->alterarFAQ($cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo, $nr_ordem)) {
        echo "<p class=\"fontConteudoSucesso\">Status da pergunta alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar status da pergunta!</p>\n";
      }                                                                                               
    }
//***************EXIBICAO PUBLICA***********************************************

    public function controleExibicaoPublica($pagina, $lista_paginas) {
      require_once 'conteudos/faqs_categorias.php';                             $faq_cat = new FaqCategoria();
      require_once 'menu/menu.php';                                             $menu = new Menu();

      $dados = $menu->selectConteudoMenuSEO($pagina);
      echo "    <div class=\"divCorpoSite\">\n";
      echo "      <div class=\"divConteudosSite\">\n";
      echo "        <p class=\"fontChamadaComoUsar\">\n";
      echo "          ".nl2br($dados['ds_informacoes'])."\n";
      echo "        </p>\n";
      echo "      </div>\n";
      echo "    </div>\n";

      $categorias = $faq_cat->selectCategorias('1');
      $par = false;

      $id_categoria = 1;
      foreach ($categorias as $c) {
        if ($par) {
          $abre = "  <div class=\"divComoUsarPar\">\n";
          $fecha= "  </div>\n";
          $par = false;
        } else {
          $abre = "  <div class=\"divComoUsarImpar\">\n";
          $fecha= "  </div>\n";
          $par = true;
        }
        $faqs = $this->selectFaqs('1', $c['cd_categoria']);

        if (count($faqs) > 0) {
          echo $abre;
          echo "      <h2>".$id_categoria." - ".$c['nm_categoria']."</h2>\n";
          echo "      <p class=\"fontDescricaoCategoriaComoUsar\">\n";
          echo "        ".nl2br($c['ds_categoria'])."\n";
          echo "      <p>\n";
          echo "      <br />\n";
          $id_pergunta = 1;
          foreach ($faqs as $r) {
            echo "      <p class=\"fontPerguntaComoUsar\">\n";
            echo "        ".$id_categoria.".".$id_pergunta." - ".nl2br($r['ds_pergunta'])."\n";
            echo "      <p>\n";
            echo "      <p class=\"fontRespostaComoUsar\">\n";
            echo "        ".nl2br($r['ds_resposta'])."\n";
            echo "      <p>\n";
            echo "      <br />\n";
            $id_pergunta += 1;
          }
          echo $fecha;
        }
        $id_categoria += 1;
      }



          echo "  <div class=\"divMeioCorpoSite\">\n";
          echo "  </div>\n";

      echo "<br /><br />\n";
    }




//**************BANCO DE DADOS**************************************************    
    public function selectFAQS($eh_ativo, $categoria) {
      $sql  = "SELECT f.*, fc.nm_categoria ".
              "FROM life_faqs f, life_faqs_categorias fc ".
              "WHERE fc.cd_categoria = f.cd_categoria ";
      if ($eh_ativo == 1) {
        $sql.= "AND f.eh_ativo = '$eh_ativo' ";
      } elseif ($eh_ativo == 0) {
        $sql.= "AND f.eh_ativo = '$eh_ativo' ";
      } 
      if ($categoria > 0) {
        $sql.= "AND fc.cd_categoria = '$categoria' ";
      }   
      $sql.= "ORDER BY fc.nm_categoria, f.nr_ordem, f.ds_pergunta ";
      $result_id = @mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectMaiorNumeroOrdem() {
      $sql  = "SELECT MAX(nr_ordem) nr ".
              "FROM life_faqs ";
      $result_id = @mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['nr'];
    }

    public function selectDadosFAQ($cd_faq) {
      $sql  = "SELECT * ".
              "FROM life_faqs ".
              "WHERE cd_faq = '$cd_faq' ";
      $result_id = @mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    private function inserirFAQ($cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo, $nr_ordem) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_faqs ".
             "(cd_categoria, ds_pergunta, ds_resposta, eh_ativo, lk_seo, nr_ordem, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_categoria\", \"$ds_pergunta\", \"$ds_resposta\", \"$eh_ativo\", \"$lk_seo\", \"$nr_ordem\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'faqs');            
      mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }
            
    private function alterarFAQ($cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo, $nr_ordem) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_faqs SET ".
             "cd_categoria = \"$cd_categoria\", ".
             "ds_pergunta = \"$ds_pergunta\", ".
             "ds_resposta = \"$ds_resposta\", ".
             "nr_ordem = \"$nr_ordem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_faq = '$cd_faq' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'faqs');            
      mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  }
?>