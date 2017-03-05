<?php
  class StatusCicloVida {
    
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
          $this->alterarStatusCicloVidaAtivoStatusCicloVida($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";        if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }         $opcao['descricao']= "Ativos";                                            $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";        if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }         $opcao['descricao']= "Inativos";                                          $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";        if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }         $opcao['descricao']= "Ativos/Inativos";                                   $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo status do ciclo de vida\" title=\"Novo status do ciclo de vida\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Status do clico de vida ";

      $itens = $this->selectStatusCicloVida($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Status do ciclo de vida</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_status_ciclo_vida']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharStatusCicloVida($it['cd_status_ciclo_vida']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_status_ciclo_vida']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_status_ciclo_vida']."&acao=status\">";
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
    
    public function detalharStatusCicloVida($cd_status_ciclo_vida) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosStatusCicloVida($cd_status_ciclo_vida);
      
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
      $cd_status_ciclo_vida = "";
      $nm_status_ciclo_vida = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de status do ciclo de vida</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_status_ciclo_vida, $nm_status_ciclo_vida, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_status_ciclo_vida) {
      $dados = $this->selectDadosStatusCicloVida($cd_status_ciclo_vida);

      $nm_status_ciclo_vida = $dados['nm_status_ciclo_vida'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de status do ciclo de vida</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_status_ciclo_vida, $nm_status_ciclo_vida, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_status_ciclo_vida, $nm_status_ciclo_vida, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/cores.php';                                       $cor = new Cor();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_status_ciclo_vida.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_status_ciclo_vida', $cd_status_ciclo_vida);
      $util->linhaUmCampoText(1, 'Status do ciclo de vida ', 'nm_status_ciclo_vida', '150', '100', $nm_status_ciclo_vida);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativo ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_status_ciclo_vida'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_status_ciclo_vida = addslashes($_POST['cd_status_ciclo_vida']);
      $nm_status_ciclo_vida = $util->limparVariavel($_POST['nm_status_ciclo_vida']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_status_ciclo_vida > 0) {
        if ($this->alteraStatusCicloVida($cd_status_ciclo_vida, $nm_status_ciclo_vida, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Status do ciclo de vida editado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do status do ciclo de vida, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereStatusCicloVida($nm_status_ciclo_vida, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Status do ciclo de vida cadastrado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do status do ciclo de vida!</p>\n";
        }
      }
    } 


    private function alterarStatusCicloVidaAtivoStatusCicloVida($cd_status_ciclo_vida) {
      $dados = $this->selectDadosStatusCicloVida($cd_status_ciclo_vida);

      $nm_status_ciclo_vida = $dados['nm_status_ciclo_vida'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraStatusCicloVida($cd_status_ciclo_vida, $nm_status_ciclo_vida, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do status do ciclo de vida alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do status do ciclo de vida!</p>\n";
      }
    }
            

    public function retornaSeletorStatusCicloVida($cd_status_ciclo_vida, $campo, $tamanho, $exibir_ajuda, $descricao, $denominacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectStatusCicloVida('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                                   $opcao[]= $descricao;                              $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_status_ciclo_vida'];           $opcao[]= $it['nm_status_ciclo_vida'];             $opcoes[]= $opcao;
      }
      $util->linhaSeletorHint($descricao, $denominacao, $campo, $cd_status_ciclo_vida, $opcoes, $tamanho, false, $exibir_ajuda);
    }
    
    public function retornaDados($cd_status_ciclo_vida, $descricao) {
      $dados = $this->selectDadosStatusCicloVida($cd_status_ciclo_vida);
      
      return "<b>".$descricao."</b> ".$dados['nm_status_ciclo_vida'];
    } 

    public function retornaSeletorStatusCicloVidaFiltro($cd_status_ciclo_vida, $nome, $tamanho, $exibir_ajuda, $descricao, $ajuda) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectStatusCicloVida('1');

      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" class=\"fontConteudoCampoSeletorHintFiltro\" placeholder=\"".$descricao."\" ";
      if ($exibir_ajuda == '1') {
        echo "alt=\"".$descricao."\" title=\"".$descricao."\" ";
      } else {
        echo "alt=\"".$ajuda."\" title=\"".$ajuda."\" ";
      }
      echo "tabindex=\"1\">\n";
      echo "  			    <option ";
      if ($cd_status_ciclo_vida == '') {          echo " selected ";        }
      echo " value=\"0\">$descricao</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option ";
        if ($it['cd_status_ciclo_vida'] == $cd_status_ciclo_vida) {          echo " selected ";        }
        echo " value=\"".$it['cd_status_ciclo_vida']."\">".$it['nm_status_ciclo_vida']."</option>\n";
      }
      echo "          </select>\n";
      if ($exibir_ajuda == '1') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Selecione o status desejado para pesquisar por Objetos de Aprendizagem.\n";
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
    }

//**************BANCO DE DADOS**************************************************    
    public function selectStatusCicloVida($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_status_ciclos_vida ".
              "WHERE cd_status_ciclo_vida > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_status_ciclo_vida ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SITUACOES!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
       
    public function selectDadosStatusCicloVida($cd_status_ciclo_vida) {
      $sql  = "SELECT * ".
              "FROM life_status_ciclos_vida ".
              "WHERE cd_status_ciclo_vida = '$cd_status_ciclo_vida' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SITUACOES!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereStatusCicloVida($nm_status_ciclo_vida, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_status_ciclos_vida ".
             "(nm_status_ciclo_vida, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_status_ciclo_vida\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'status_ciclos_vida');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SITUACOES!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraStatusCicloVida($cd_status_ciclo_vida, $nm_status_ciclo_vida, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_status_ciclos_vida SET ".
             "nm_status_ciclo_vida = \"$nm_status_ciclo_vida\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_status_ciclo_vida= '$cd_status_ciclo_vida' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'status_ciclos_vida');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SITUACOES!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>