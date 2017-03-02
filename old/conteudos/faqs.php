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
      
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=1";                               $opcao['descricao']= "Ativas";                                  $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=0";                               $opcao['descricao']= "Inativas";                                $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=2";                               $opcao['descricao']= "Ativas/Inativas";                         $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";                $opcao['link']= "";                                                                                                               $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
      
      $id = 5;
      $itens = $faq_cat->selectCategorias('1');
      if (count($itens) > 0) {
        foreach ($itens as $it) {
          $opcao= array();      $opcao['indice']= $id; $id+=1;        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$it['cd_categoria']."&at=".$ativas;               $opcao['descricao']= $it['nm_categoria'];                       $opcoes[]= $opcao;
        }
        if ($categoria > 0) {
          $opcao= array();      $opcao['indice']= $id; $id+=1;        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=0&at=".$ativas;                                   $opcao['descricao']= "Todas as Categorias";                     $opcoes[]= $opcao;
        }
        $opcao= array();      $opcao['indice']= $id; $id+=1;        $opcao['link']= "";                                                                                                               $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
      }

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=58\"><img src=\"icones/como_usar_categorias.png\" alt=\"Categorias de Perguntas para a seção Como usar\" title=\"Categorias de Perguntas para a seção Como usar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&acao=secao\"><img src=\"icones/secao_capa.png\" alt=\"Editar introdução da seção Como usar\" title=\"Editar introdução da seção Como usar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&ct=".$categoria."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Pergunta para a seção Como usar\" title=\"Nova Pergunta para a seção Como usar\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
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
      if ($ativas == 1)   {        $mensagem.= "Ativas";      } elseif ($ativas == 0) {        $mensagem.= "Inativas";      }
      if ($categoria > 0) {        $dados_categoria = $cat->selectDadosCategoria($categoria);  $mensagem.= "<br />Categoria: ".$dados_categoria['nm_categoria'];      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Pergunta:</td>\n";
      echo "      <td class=\"celConteudo\">Categoria:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\"><b>";
        if ($nr_caracteres_abreviar_pergunta_faq_restrita > 0) {          echo $util->abreviar($it['ds_pergunta'], $nr_caracteres_abreviar_pergunta_faq_restrita);        } else {          echo $it['ds_pergunta'];        }
        echo "</b><br />";
        if ($nr_caracteres_abreviar_resposta_faq_restrita > 0) {          echo $util->abreviar($it['ds_resposta'], $nr_caracteres_abreviar_resposta_faq_restrita);        } else {          echo $it['ds_resposta'];        }
        echo "</td>\n";
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
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data da Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }

    private function montarFormularioCadastro($link) {
      $cd_faq = "";
      $cd_categoria = "";
      $ds_pergunta = "";
      $ds_resposta = "";
      $eh_ativo = "1";
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de FAQs</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo);
    }

    private function montarFormularioEdicao($link, $cd_faq) {
      $dados= $this->selectDadosFAQ($cd_faq);

      $cd_categoria = $dados['cd_categoria'];
      $ds_pergunta = $dados['ds_pergunta'];
      $ds_resposta = $dados['ds_resposta'];
      $eh_ativo = $dados['eh_ativo'];

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de FAQ</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo);
    }    

    private function imprimeFormularioCadastro($link, $cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/faqs_categorias.php';                             $faq_cat = new FAQCategoria();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_faq.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_faq', $cd_faq);

      $util->linhaTexto(1, 'Pergunta: ', 'ds_pergunta', $ds_pergunta, '5', '100');
      $util->linhaTexto(1, 'Resposta: ', 'ds_resposta', $ds_resposta, '10', '100');

      $faq_cat->retornaSeletorCategorias($cd_categoria);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      
      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
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

      $lk_seo = $util->retornaLinkSEO($ds_pergunta, 'life_faqs', 'lk_seo', '150', $cd_faq);

      if ($cd_faq > 0) {
        if ($this->alterarFAQ($cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Pergunta editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Pergunta, ou nenhuma informação alterada!</p>\n";
        }  
      } else {
        if ($this->inserirFAQ($cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Perguntacadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Pergunta!</p>\n";
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
                
      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
    
      if ($this->alterarFAQ($cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Pergunta alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status da Pergunta!</p>\n";
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
      $sql.= "ORDER BY fc.nm_categoria, f.ds_pergunta ";
      $result_id = @mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosFAQ($cd_faq) {
      $sql  = "SELECT * ".
              "FROM life_faqs ".
              "WHERE cd_faq = '$cd_faq' ";
      $result_id = @mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    private function inserirFAQ($cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_faqs ".
             "(cd_categoria, ds_pergunta, ds_resposta, eh_ativo, lk_seo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_categoria\", \"$ds_pergunta\", \"$ds_resposta\", \"$eh_ativo\", \"$lk_seo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'faqs');            
      mysql_query($sql) or die ("FAQs - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }
            
    private function alterarFAQ($cd_faq, $cd_categoria, $ds_pergunta, $ds_resposta, $eh_ativo, $lk_seo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_faqs SET ".
             "cd_categoria = \"$cd_categoria\", ".
             "ds_pergunta = \"$ds_pergunta\", ".
             "ds_resposta = \"$ds_resposta\", ".
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