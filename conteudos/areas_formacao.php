<?php
  class AreaFormacao {
    
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
          $this->alterarSituacaoAtivoAreaFormacao($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";       if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }          $opcao['descricao']= "Ativas";                                            $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";       if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }          $opcao['descricao']= "Inativas";                                          $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";       if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }          $opcao['descricao']= "Ativas/Inativas";                                   $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }

      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova área de formação\" title=\"Nova área de formação\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Áreas de formação ";
      $itens = $this->selectAreasFormacao($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Área de formação</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_area_formacao']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharAreaFormacao($it['cd_area_formacao']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_area_formacao']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_area_formacao']."&acao=status\">";
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
    
    public function detalharAreaFormacao($cd_area_formacao) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosAreaFormacao($cd_area_formacao);
      
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
      $cd_area_formacao = "";
      $nm_area_formacao = "";
      $ds_area_formacao = "";
      $cd_area_formacao_destino = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de áreas de formação</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_area_formacao) {
      $dados = $this->selectDadosAreaFormacao($cd_area_formacao);

      $nm_area_formacao = $dados['nm_area_formacao'];
      $ds_area_formacao = $dados['ds_area_formacao'];
      $cd_area_formacao_destino = $dados['cd_area_formacao_destino'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de área de formação</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_area_formacao.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_area_formacao', $cd_area_formacao);
      $util->campoHidden('cd_area_formacao_destino', $cd_area_formacao_destino);
      $util->linhaUmCampoText(1, 'Área de formação ', 'nm_area_formacao', '150', '100', $nm_area_formacao);
      $util->linhaTexto(0, 'Descrição ', 'ds_area_formacao', $ds_area_formacao, '5', '100');

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativa ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_area_formacao'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_area_formacao = addslashes($_POST['cd_area_formacao']);
      $nm_area_formacao = $util->limparVariavel($_POST['nm_area_formacao']);
      $ds_area_formacao = $util->limparVariavel($_POST['ds_area_formacao']);
      $cd_area_formacao_destino = addslashes($_POST['cd_area_formacao_destino']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_area_formacao > 0) {
        if ($this->alteraAreaFormacao($cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Área de formação editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da área de formação, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereAreaFormacao($nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Área de formação cadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da área de formação!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoAreaFormacao($cd_area_formacao) {
      $dados = $this->selectDadosAreaFormacao($cd_area_formacao);

      $nm_area_formacao = $dados['nm_area_formacao'];
      $ds_area_formacao = $dados['ds_area_formacao'];
      $cd_area_formacao_destino = $dados['cd_area_formacao_destino'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraAreaFormacao($cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da área de formação alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da área de formação!</p>\n";
      }
    }

    public function retornaSeletorOutrasAreasFormacao($cd_area_formacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectAreasFormacao('1', '1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione uma área de formação';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        if ($cd_area_formacao != $it['cd_area_formacao']) { 
          $opcao= array();      $opcao[] = $it['cd_area_formacao'];          $opcao[]= $it['nm_area_formacao'];                   $opcoes[]= $opcao;
        }
      }
      $util->linhaSeletor('Áreas de formação ', 'cd_area_formacao_destino', $cd_area_formacao, $opcoes, '100');
    }      

    public function retornaSeletorAreasFormacao($cd_area_formacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectAreasFormacao('1', '1');

      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                                   $opcao[]= 'Selecione uma área de formação';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_area_formacao'];               $opcao[]= $it['nm_area_formacao'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Áreas de formação ', 'cd_area_formacao', $cd_area_formacao, $opcoes, '100');
    }

//**************BANCO DE DADOS**************************************************
    public function selectAreasFormacao($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_areas_formacao ".
              "WHERE cd_area_formacao > '0' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_area_formacao ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO 01!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
        
    public function selectDadosAreaFormacao($cd_area_formacao) {
      $sql  = "SELECT * ".
              "FROM life_areas_formacao ".
              "WHERE cd_area_formacao = '$cd_area_formacao' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO 02!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereAreaFormacao($nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_areas_formacao ".
             "(nm_area_formacao, ds_area_formacao, cd_area_formacao_destino, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_area_formacao\", \"$ds_area_formacao\", \"$cd_area_formacao_destino\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_formacao');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO 03!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraAreaFormacao($cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $cd_area_formacao_destino, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_areas_formacao SET ".
             "nm_area_formacao = \"$nm_area_formacao\", ".
             "ds_area_formacao = \"$ds_area_formacao\", ".
             "cd_area_formacao_destino = \"$cd_area_formacao_destino\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_area_formacao= '$cd_area_formacao' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_formacao');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO 04!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  }
?>