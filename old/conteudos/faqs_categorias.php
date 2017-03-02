<?php
  class FAQCategoria {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }

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
            unset($_SESSION['life_edicao']);
            $this->salvarCadastroAlteracao();
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;  
                
        case "status":
          $this->alterarStatus($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
               
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";               $opcao['descricao']= "Ativas";                                  $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";               $opcao['descricao']= "Inativas";                                $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";               $opcao['descricao']= "Ativas/Inativas";                         $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                             $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=59\"><img src=\"icones/como_usar_perguntas.png\" alt=\"Perguntas para a seção Como usar\" title=\"Perguntas para a seção Como usar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Categoria de Perguntas para a seção Como usar\" title=\"Nova Categoria de Perguntas para a seção Como usar\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }
    
    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $itens = $this->selectCategorias($ativas);    

      $mensagem = "Categorias ";
      if ($ativas == 1) {        $mensagem.= "Ativas";      } elseif ($ativas == 0) {        $mensagem.= "Inativas";      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Categoria:</td>\n";
      echo "      <td class=\"celConteudo\">Ordem:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_categoria']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharCategoria($it['cd_categoria']);
        echo "        </span></a>\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_categoria']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_categoria']."&acao=status\">";
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
    
    private function detalharCategoria($cd_categoria) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosCategoria($cd_categoria);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
    
    public function montarFormularioCadastro($link) {
      $cd_categoria = "";
      $nm_categoria = "";
      $ds_categoria = "";
      $maior_nr_ordem = $this->selectMaiorNumeroOrdem();
      $nr_ordem = $maior_nr_ordem + 1;
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Categorias</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_categoria, $nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo);
    }
    
    public function montarFormularioEdicao($link, $cd_categoria) {
      $dados= $this->selectDadosCategoria($cd_categoria);

      $nm_categoria = $dados['nm_categoria'];
      $ds_categoria = $dados['ds_categoria'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];  

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Categoria</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_categoria, $nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo);
    }    
    
    private function imprimeFormularioCadastro($link, $cd_categoria, $nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_categoria_como_usar.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" enctype=\"multipart/form-data\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_categoria', $cd_categoria);
      $util->linhaUmCampoText(1, 'Categoria: ', 'nm_categoria', 100, 100, $nm_categoria);
      $util->linhaTexto(0, 'Descrição: ', 'ds_categoria', $ds_categoria, '5', '100');
      $util->linhaUmCampoText(1, 'Número de Ordem: ', 'nr_ordem', 10, 100, $nr_ordem);
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes, '100');

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_categoria'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();
      
      $cd_categoria = addslashes($_POST['cd_categoria']);
      $nm_categoria = $util->limparVariavel($_POST['nm_categoria']);
      $ds_categoria = $util->limparVariavel($_POST['ds_categoria']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $nr_ordem = addslashes($_POST['nr_ordem']);
      
      $lk_seo = $util->retornaLinkSEO($nm_categoria, 'life_faqs_categorias', 'lk_seo', '50', $cd_categoria);

      if ($cd_categoria > 0) {
        if ($this->alteraCategoria($cd_categoria, $nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Categoria editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Categoria, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereCategoria($nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Categoria cadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Categoria!</p>\n";
        }
      }
    } 
   
    public function alterarStatus($cd_categoria) {
      $dados= $this->selectDadosCategoria($cd_categoria);

      $nm_categoria = $dados['nm_categoria'];
      $ds_categoria = $dados['ds_categoria'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];
      
      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
    
      if ($this->alteraCategoria($cd_categoria, $nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo, $lk_seo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Categoria alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status da Categoria!</p>\n";
      }                                                                                               
    }   
    
    public function retornaSeletorCategorias($cd_categoria) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
    
      $itens = $this->selectCategorias('1');
      
      $opcoes= array();
      $opcao= array();      $opcao[]= '0';                      $opcao[]= 'Selecione uma Categoria';      $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();    $opcao[]= $it['cd_categoria'];      $opcao[]= $it['nm_categoria'];            $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Categoria: ', 'cd_categoria', $cd_categoria, $opcoes, '100');
    }
  
//**************BANCO DE DADOS**************************************************    
    public function selectCategorias($eh_ativo) {
      $sql  = "SELECT *  ".
              "FROM life_faqs_categorias ".
              "WHERE cd_categoria > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_categoria";       
         
      $result_id = @mysql_query($sql) or die ("FAQS CATEGORIAS - Erro no banco de dados!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectDadosCategoria($cd_categoria) {
      $sql  = "SELECT * ".
              "FROM life_faqs_categorias ".
              "WHERE cd_categoria = '$cd_categoria' ";
      $result_id = @mysql_query($sql) or die ("FAQS CATEGORIAS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function selectDadosCategoriaSEO($lk_seo) {
      $sql  = "SELECT * ".
              "FROM life_faqs_categorias ".
              "WHERE lk_seo = '$lk_seo' ";
      $result_id = @mysql_query($sql) or die ("FAQS CATEGORIAS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;     
    }
    
    public function selectMaiorNumeroOrdem() {
      $sql  = "SELECT MAX(nr_ordem) nr ".
              "FROM life_faqs_categorias ";
      $result_id = @mysql_query($sql) or die ("FAQS CATEGORIAS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['nr'];        
    }

    public function selectDadosCategoriaCpf($nr_cpf) {
      $sql  = "SELECT * ".
              "FROM life_faqs_categorias ".
              "WHERE nr_cpf = '$nr_cpf' ";
      $result_id = @mysql_query($sql) or die ("FAQS CATEGORIAS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereCategoria($nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo, $lk_seo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_faqs_categorias ".
             "(nm_categoria, ds_categoria, nr_ordem, eh_ativo, lk_seo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_categoria\", \"$ds_categoria\", \"$nr_ordem\", \"$eh_ativo\", \"$lk_seo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'faqs_categorias');            
      mysql_query($sql) or die ("FAQS CATEGORIAS - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraCategoria($cd_categoria, $nm_categoria, $ds_categoria, $nr_ordem, $eh_ativo, $lk_seo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_faqs_categorias SET ".
             "nm_categoria= \"$nm_categoria\", ".
             "ds_categoria = \"$ds_categoria\", ".
             "nr_ordem = \"$nr_ordem\", ".
             "eh_ativo= \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_categoria= '$cd_categoria' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'faqs_categorias');            
      mysql_query($sql) or die ("FAQS CATEGORIAS - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
    

    
  }
?>