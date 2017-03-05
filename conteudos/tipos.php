<?php
  class Tipo {
    
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
          $this->alterarSituacaoAtivoTipo($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";           if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Ativos";                                $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";           if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Inativos";                              $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";           if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Ativos/Inativos";                       $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo tipo\" title=\"Novo tipo\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Tipos";

      $itens = $this->selectTipo($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Tipo</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_tipo']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharTipo($it['cd_tipo']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_tipo']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_tipo']."&acao=status\">";
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
    
    public function detalharTipo($cd_tipo) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosTipo($cd_tipo);
      
      $retorno = "";
      if ($dados['cd_usuario_cadastro'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      } else {
        $retorno.= "Cadastro automático<br />";
      }
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última atualização por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do última atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
     
    private function montarFormularioCadastro($link) {
      $cd_tipo = "";
      $nm_tipo = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de tipos</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_tipo, $nm_tipo, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_tipo) {
      $dados = $this->selectDadosTipo($cd_tipo);

      $nm_tipo = $dados['nm_tipo'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de tipo</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_tipo, $nm_tipo, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_tipo, $nm_tipo, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/cores.php';                                       $cor = new Cor();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_tipo.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_tipo', $cd_tipo);
      $util->linhaUmCampoText(1, 'Tipo ', 'nm_tipo', '150', '100', $nm_tipo);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativo ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_tipo'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_tipo = addslashes($_POST['cd_tipo']);
      $nm_tipo = $util->limparVariavel($_POST['nm_tipo']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_tipo > 0) {
        if ($this->alteraTipo($cd_tipo, $nm_tipo, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Tipo editado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do tipo, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereTipo($nm_tipo, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Tipo cadastrado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do tipo!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoTipo($cd_tipo) {
      $dados = $this->selectDadosTipo($cd_tipo);

      $nm_tipo = $dados['nm_tipo'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraTipo($cd_tipo, $nm_tipo, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do tipo alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do tipo!</p>\n";
      }
    }

    public function retornaSeletorTipo($cd_tipo, $campo, $tamanho, $exibir_ajuda, $descricao, $denominacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectTipo('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                     $opcao[]= $descricao;                  $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_tipo'];          $opcao[]= $it['nm_tipo'];              $opcoes[]= $opcao;
      }
      $util->linhaSeletorHint($descricao, $denominacao, $campo, $cd_tipo, $opcoes, $tamanho, false, $exibir_ajuda);
    }
    
    public function retornaDados($cd_tipo, $descricao) {
      $dados = $this->selectDadosTipo($cd_tipo);
      
      return "<b>".$descricao."</b> ".$dados['nm_tipo'];
    } 

    public function retornaSeletorTipoFiltro($cd_tipo, $nome, $tamanho, $exibir_ajuda, $descricao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectTipo('1');

      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" alt=\"".$descricao."\" title=\"".$descricao."\" class=\"fontConteudoCampoSeletorHintFiltro\" placeholder=\"".$descricao."\" tabindex=\"1\">\n";
      echo "  			    <option ";
      if ($cd_tipo == '') {          echo " selected ";        }
      echo " value=\"0\">$descricao</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option ";
        if ($it['cd_tipo'] == $cd_tipo) {          echo " selected ";        }
        echo " value=\"".$it['cd_tipo']."\">".$it['nm_tipo']."</option>\n";
      }
      echo "          </select>\n";
      if ($exibir_ajuda == '1') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Para utilizar o filtro por tipos, selecione o tipo desejado\n";
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
    }

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************    
    public function selectTipo($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_tipos ".
              "WHERE cd_tipo > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_tipo ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA TIPO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
       
    public function selectDadosTipo($cd_tipo) {
      $sql  = "SELECT * ".
              "FROM life_tipos ".
              "WHERE cd_tipo = '$cd_tipo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA TIPO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereTipo($nm_tipo, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_tipos ".
             "(nm_tipo, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_tipo\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'tipos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA TIPO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraTipo($cd_tipo, $nm_tipo, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_tipos SET ".
             "nm_tipo = \"$nm_tipo\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_tipo= '$cd_tipo' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'tipos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA TIPO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>