<?php
  class FormatoObjeto {
    
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
          $this->alterarSituacaoAtivoFormatoObjeto($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes_1= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";         if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }        $opcao['descricao']= "Ativos";                                            $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";         if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }        $opcao['descricao']= "Inativos";                                          $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";         if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }        $opcao['descricao']= "Ativos/Inativos";                                   $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo formato\" title=\"Novo formato\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Formatos ";
      $itens = $this->selectFormatoObjeto($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Formato</td>\n";
      echo "      <td class=\"celConteudo\">Tipo</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_formato_objeto']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_formato']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharFormatoObjeto($it['cd_formato_objeto']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_formato_objeto']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_formato_objeto']."&acao=status\">";
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
    
    public function detalharFormatoObjeto($cd_formato_objeto) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosFormatoObjeto($cd_formato_objeto);
      
      $retorno = "";
      if ($dados['cd_usuario_cadastro'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      } else {
        $retorno.= "Cadastro automático<br />";
      }
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
     
    private function montarFormularioCadastro($link) {
      $cd_formato_objeto = "";
      $nm_formato_objeto = "";
      $cd_formato = "0";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de formatos</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_formato_objeto, $nm_formato_objeto, $cd_formato, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_formato_objeto) {
      $dados = $this->selectDadosFormatoObjeto($cd_formato_objeto);

      $nm_formato_objeto = $dados['nm_formato_objeto'];
      $cd_formato = $dados['cd_formato'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de formato</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_formato_objeto, $nm_formato_objeto, $cd_formato, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_formato_objeto, $nm_formato_objeto, $cd_formato, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/formatos.php';                                    $for = new Formato();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_formato_objeto.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_formato_objeto', $cd_formato_objeto);
      $util->linhaUmCampoText(1, 'Formato ', 'nm_formato_objeto', '150', '100', $nm_formato_objeto);

      $for->retornaSeletorFormatoCadastro($cd_formato);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativo ', 'eh_ativo', $eh_ativo, $opcoes, '100');

      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_formato_objeto');
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_formato_objeto = addslashes($_POST['cd_formato_objeto']);
      $nm_formato_objeto = $util->limparVariavel($_POST['nm_formato_objeto']);
      $cd_formato = addslashes($_POST['cd_formato']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_formato_objeto > 0) {
        if ($this->alteraFormatoObjeto($cd_formato_objeto, $nm_formato_objeto, $cd_formato, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Formato editado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do formato, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereFormatoObjeto($nm_formato_objeto, $cd_formato, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Formato cadastrado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do formato!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoFormatoObjeto($cd_formato_objeto) {
      $dados = $this->selectDadosFormatoObjeto($cd_formato_objeto);

      $nm_formato_objeto = $dados['nm_formato_objeto'];
      $cd_formato = $dados['cd_formato'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraFormatoObjeto($cd_formato_objeto, $nm_formato_objeto, $cd_formato, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do formato alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do formato!</p>\n";
      }
    }

    public function imprimeDados($cd_formato_objeto, $descricao) {
      $dados = $this->selectDadosFormatoObjeto($cd_formato_objeto);

      return "<b>".$descricao."</b> ".$dados['nm_formato_objeto'];
    }

    public function retornaSeletorFormatoObjeto($cd_formato_objeto, $campo, $tamanho, $exibir_ajuda, $descricao, $denominacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectFormatoObjeto('1');

      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= $descricao;                        $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_formato_objeto'];         $opcao[]= $it['nm_formato_objeto'];                 $opcoes[]= $opcao;
      }
      $util->linhaSeletorAcaoHint($descricao, $denominacao, $campo, $cd_formato_objeto, $opcoes, $tamanho, false, $exibir_ajuda, " onChange=\"atualizarCampoTipoArquivo();\" ");
    }

    public function retornaSeletorFormatoFiltro($cd_formato_objeto, $nome, $tamanho, $exibir_ajuda, $descricao, $ajuda) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectFormatoObjeto('1');

      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" class=\"fontConteudoCampoSeletorHintFiltro\" placeholder=\"".$descricao."\" ";
      if ($exibir_ajuda == '1') {
        echo "alt=\"".$descricao."\" title=\"".$descricao."\" ";
      } else {
        echo "alt=\"".$ajuda."\" title=\"".$ajuda."\" ";
      }
      echo "tabindex=\"1\">\n";
      echo "  			    <option ";
      if ($cd_formato_objeto == '') {          echo " selected ";        }
      echo " value=\"0\">$descricao</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option ";
        if ($it['cd_formato_objeto'] == $cd_formato_objeto) {          echo " selected ";        }
        echo " value=\"".$it['cd_formato_objeto']."\">".$it['nm_formato_objeto']."</option>\n";
      }
      echo "          </select>\n";
      if ($exibir_ajuda == '1') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Selecione o formato desejado para pesquisar por objetos de aprendizagem.\n";
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
    }
//**************BANCO DE DADOS**************************************************    
    public function selectFormatoObjeto($eh_ativo) {
      $sql  = "SELECT f.nm_formato, fo.* ".
              "FROM life_formatos_objeto fo, life_formatos f ".
              "WHERE fo.cd_formato = f.cd_formato ";
      if ($eh_ativo != 2) {
        $sql.= "AND fo.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY fo.nm_formato_objeto, f.nm_formato ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA FORMATO OBJETO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
       
    public function selectDadosFormatoObjeto($cd_formato_objeto) {
      $sql  = "SELECT * ".
              "FROM life_formatos_objeto ".
              "WHERE cd_formato_objeto = '$cd_formato_objeto' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA FORMATO OBJETO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereFormatoObjeto($nm_formato_objeto, $cd_formato, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_formatos_objeto ".
             "(nm_formato_objeto, cd_formato, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_formato_objeto\", \"$cd_formato\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'formatos_objeto');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA FORMATO OBJETO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraFormatoObjeto($cd_formato_objeto, $nm_formato_objeto, $cd_formato, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_formatos_objeto SET ".
             "nm_formato_objeto = \"$nm_formato_objeto\", ".
             "cd_formato = \"$cd_formato\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_formato_objeto= '$cd_formato_objeto' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'formatos_objeto');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA FORMATO OBJETO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>