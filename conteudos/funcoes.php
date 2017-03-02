<?php
  class AreaFormacao {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';               }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = '1';            }
      if (isset($_GET['li']))    {      $liberadas = addslashes($_GET['li']);       } else {      $liberadas = '2';         }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';             }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $ativas, $liberadas);
        break;

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas;
          $this->montarFormularioCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['aprof_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['aprof_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $ativas, $liberadas);
        break;        
               
        case "status":
          $this->alterarSituacaoAtivoAreaFormacao($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $ativas, $liberadas);
        break;
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas, $liberadas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=1";           if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Ativas";                                            $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=0";           if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Inativas";                                          $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=2";           if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Ativas/Inativas";                                   $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }

      $opcoes_2= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&li=1";              if($liberadas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Liberadas";                                         $opcoes_2[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&li=0";              if($liberadas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Bloqueadas";                                        $opcoes_2[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&li=2";              if($liberadas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Liberadas/Bloqueadas";                              $opcoes_2[]= $opcao;
      foreach ($opcoes_2 as $op) {        $nome = 'comandos_filtros_2_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova área de formação\" title=\"Nova área de formação\" border=\"0\"></a> \n";

      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";

      echo "  <select name=\"comandos_filtros_2\" id=\"comandos_filtros_2\" class=\"fontComandosFiltros\" onChange=\"navegar(2);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_2 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";

      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas, $liberadas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Áreas de formação ";
      if ($ativas == 1) {             $mensagem.= "Ativas ";      } elseif ($ativas == 0) {       $mensagem.= "Inativas ";      }

      $itens = $this->selectAreasFormacao('1');
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Conteúdo</td>\n";
      echo "      <td class=\"celConteudo\">Ordem</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_funcao']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharAreaFormacao($it['cd_funcao']);
        echo "        </span></a>\n";
        if ($it['eh_editavel'] == '1') {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_funcao']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_funcao']."&acao=status\">";
          if ($it['eh_ativo'] == 1) {
            echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
          } else {
            echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
          }
        } else {
          echo "          <img src=\"icones/vazio.png\" border=\"0\">\n";
          echo "          <img src=\"icones/vazio.png\" border=\"0\">\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
      echo "  <br /><br />\n"; 
    }
    
    public function detalharAreaFormacao($cd_funcao) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosAreaFormacao($cd_funcao);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última atualização por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do última atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
     
    private function montarFormularioCadastro($link) {
      $cd_funcao = "";
      $maior_nr_ordem = $this->selectMaiorNumeroOrdemAreaFormacao();
      $nr_ordem = $maior_nr_ordem + 1;
      $nm_funcao = "";
      $eh_ativo = "1";
      $_SESSION['aprof_edicao']= 1;
      echo "  <h2>Cadastro de áreas de formação</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_funcao, $nr_ordem, $eh_ativo, $nm_funcao);
    }
    
    private function montarFormularioEdicao($link, $cd_funcao) {
      $dados = $this->selectDadosAreaFormacao($cd_funcao);

      if ($dados['eh_editavel'] == '0') {
        return false;
      }
      
      $nr_ordem = $dados['nr_ordem'];
      $nm_funcao = $dados['nm_funcao'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['aprof_edicao']= 1;
      echo "  <h2>Edição de área de formação</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_funcao, $nr_ordem, $eh_ativo, $nm_funcao);
    }
    
    public function imprimeFormularioCadastro($link, $cd_funcao, $nr_ordem, $eh_ativo, $nm_funcao) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_funcao.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_funcao', $cd_funcao);
      $util->linhaUmCampoText(1, 'Área de formação ', 'nm_funcao', '150', '70', $nm_funcao);
      $util->linhaUmCampoText(1, 'Número de ordem ', 'nr_ordem', 5, 10, $nr_ordem);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativo ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_funcao'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_funcao = addslashes($_POST['cd_funcao']);
      $nm_funcao = $util->limparVariavel($_POST['nm_funcao']);
      $nr_ordem = addslashes($_POST['nr_ordem']);

      $dados = $this->selectNumeroOrdemAreaFormacao($nr_ordem);
      if (($dados['cd_funcao'] != '') && ($dados['cd_funcao'] != $cd_funcao)){
        $maior_nr_ordem = $this->selectMaiorNumeroOrdemAreaFormacao();
        $nr_ordem = $maior_nr_ordem + 1;
        echo "<p class=\"fontConteudoAlerta\">Número de ordem informado já estava cadastrado para outra área de formação, sendo assim foi alterado automaticamente!</p>\n";
      }
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_funcao > 0) {
        if ($this->alteraAreaFormacao($cd_funcao, $nr_ordem, $eh_ativo, $nm_funcao)) {
          echo "<p class=\"fontConteudoSucesso\">Área de formação editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Área de formação, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereAreaFormacao($nr_ordem, $eh_ativo, $nm_funcao)) {
          echo "<p class=\"fontConteudoSucesso\">Área de formação cadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Área de formação!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoAreaFormacao($cd_funcao) {
      $dados = $this->selectDadosAreaFormacao($cd_funcao);

      $nr_ordem = $dados['nr_ordem'];
      $nm_funcao = $dados['nm_funcao'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraAreaFormacao($cd_funcao, $nr_ordem, $eh_ativo, $nm_funcao)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Área de formação alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da Área de formação!</p>\n";
      }
    }
    
    private function existeNumeroOrdem($nr_ordem) {
      $dados = $this->selectNumeroOrdemAreaFormacao($nr_ordem);
      if ($dados['cd_funcao'] != '') {
        return true;
      } else {
        return false;
      }
    }
    


    public function retornaSeletorAreasFormacao($cd_funcao, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectAreasFormacao($eh_ativo);
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                       $opcao[]= 'Selecione uma área de formação';         $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_funcao'];          $opcao[]= $it['nm_funcao'];      $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Áreas de Formação ', 'cd_funcao', $cd_funcao, $opcoes);
    }      

//**************BANCO DE DADOS**************************************************    
    public function selectAreasFormacao($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM aprof_funcoes ".
              "WHERE cd_funcao > '0' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nr_ordem";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectMaiorNumeroOrdemAreaFormacao() {
      $sql  = "SELECT MAX(nr_ordem) nr ".
              "FROM aprof_funcoes ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['nr'];        
    }
    
    public function selectNumeroOrdemAreaFormacao($nr_ordem) {
      $sql  = "SELECT * ".
              "FROM aprof_funcoes ".
              "WHERE nr_ordem = '$nr_ordem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }    
    
    public function selectDadosAreaFormacao($cd_funcao) {
      $sql  = "SELECT * ".
              "FROM aprof_funcoes ".
              "WHERE cd_funcao = '$cd_funcao' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereAreaFormacao($nr_ordem, $eh_ativo, $nm_funcao) {
      $cd_usuario_cadastro = $_SESSION['aprof_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO aprof_funcoes ".
             "(nr_ordem, eh_ativo, nm_funcao, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nr_ordem\", \"$eh_ativo\", \"$nm_funcao\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'funcoes');            
      mysql_query($sql) or die ("Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraAreaFormacao($cd_funcao, $nr_ordem, $eh_ativo, $nm_funcao) {
      $cd_usuario_ultima_atualizacao = $_SESSION['aprof_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE aprof_funcoes SET ".
             "eh_ativo = \"$eh_ativo\", ".
             "nm_funcao = \"$nm_funcao\", ".
             "nr_ordem = \"$nr_ordem\", ".             
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_funcao= '$cd_funcao' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'funcoes');            
      mysql_query($sql) or die ("Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>
