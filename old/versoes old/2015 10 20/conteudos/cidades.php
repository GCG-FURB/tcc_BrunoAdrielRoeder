<?php
  class Cidade {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['es']))    {      $estado = addslashes($_GET['es']);          } else {      $estado = '24';       }
      if (isset($_GET['in']))    {      $inicial = addslashes($_GET['in']);         } else {      $inicial = 'A';       }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $estado, $inicial, $ativas);
          $this->listarItens($secao, $subsecao, $item, $estado, $inicial, $ativas);
        break;

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=".$ativas;
          $this->montarFormularioCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=".$ativas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $estado, $inicial, $ativas);
          $this->listarItens($secao, $subsecao, $item, $estado, $inicial, $ativas);
        break;  

        case "status":
          $this->alterarStatus($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $estado, $inicial, $ativas);
          $this->listarItens($secao, $subsecao, $item, $estado, $inicial, $ativas);
        break;          
      }
    }

    private function listarAcoes($secao, $subsecao, $item, $estado, $inicial, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/estados.php';                                     $est = new Estado();
      
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=1";                         $opcao['descricao']= "Ativas";                                  $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=0";                         $opcao['descricao']= "Inativas";                                $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=2";                         $opcao['descricao']= "Ativas/Inativas";                         $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";                $opcao['link']= "";                                                                                                                      $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
      
      $id = 5;
      $itens = $est->selectEstados('1');
      if (count($itens) > 0) {
        foreach ($itens as $it) {
          $opcao= array();      $opcao['indice']= $id; $id+=1;        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$it['cd_estado']."&at=".$ativas;         $opcao['descricao']= $it['nm_estado'];                          $opcoes[]= $opcao;
        }
        if ($estado > 0) {
          $opcao= array();      $opcao['indice']= $id; $id+=1;        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=0&at=".$ativas;                            $opcao['descricao']= "Todos os Estados";                        $opcoes[]= $opcao;        
        }
        $opcao= array();      $opcao['indice']= $id; $id+=1;        $opcao['link']= "";                                                                                                                      $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;
      }

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Cidade\" title=\"Nova Cidade\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";

      echo "<p class=\"fontComandosCentralizados\">\n";
      $alfabeto= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
      foreach ($alfabeto as $alfa) {
        echo "  | <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$alfa."&es=".$estado."&at=".$ativas."\" class=\"fontLink\">".$alfa."</a>&nbsp;\n";
      }
      echo "  | <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=0&es=".$estado."&at=".$ativas."\" class=\"fontLink\">A-Z</a> |\n";
      echo "</p>\n";      
    }

    private function listarItens($secao, $subsecao, $item, $estado, $inicial, $ativas) {
      require_once 'conteudos/estados.php';                                     $est = new Estado();
      
      $itens = $this->selectCidades($ativas, $estado, $inicial);    

      $mensagem = "Cidades ";
      if ($ativas == 1)   {        $mensagem.= "Ativas";      } elseif ($ativas == 0) {        $mensagem.= "Inativas";      }
      if ($estado > 0) {
        $dados_estado = $est->selectDadosEstado($estado);
        $mensagem.= " do Estado de(o): ".$dados_estado['nm_estado'];      
      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Cidade:</td>\n";
      echo "      <td class=\"celConteudo\">Estado:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_cidade']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['sg_estado']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharCidade($it['cd_cidade']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=".$ativas."&cd=".$it['cd_cidade']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\"border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&in=".$inicial."&es=".$estado."&at=".$ativas."&cd=".$it['cd_cidade']."&acao=status\">";
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

    private function detalharCidade($cd_cidade) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosCidade($cd_cidade);
      
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
      $cd_cidade = "";
      $cd_estado = "";
      $nm_cidade = "";
      $eh_ativo = "1";
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Cidades</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_cidade, $cd_estado, $nm_cidade, $eh_ativo);
    }

    private function montarFormularioEdicao($link, $cd_cidade) {
      $dados= $this->selectDadosCidade($cd_cidade);

      $cd_estado = $dados['cd_estado'];
      $nm_cidade = $dados['nm_cidade'];
      $eh_ativo = $dados['eh_ativo'];

      $_SESSION['life_edicao']= 1;      
      echo "  <h2>Edição de Cidade</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_cidade, $cd_estado, $nm_cidade, $eh_ativo);
    }    

    private function imprimeFormularioCadastro($link, $cd_cidade, $cd_estado, $nm_cidade, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/estados.php';                                     $est = new Estado();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_cidade.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_cidade', $cd_cidade);

      $util->linhaUmCampoText(1, 'Nome: ', 'nm_cidade', 150, 100, $nm_cidade);

      $est->retornaSeletorEstados($cd_estado);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      
      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_cidade'); 
    }
                         
    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_cidade = addslashes($_POST['cd_cidade']);
      $cd_estado = addslashes($_POST['cd_estado']);
      $nm_cidade = $util->limparVariavel($_POST['nm_cidade']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_cidade > 0) {
        if ($this->alterarCidade($cd_cidade, $cd_estado, $nm_cidade, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Cidade editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Cidade, ou nenhuma informação alterada!</p>\n";
        }  
      } else {
        if ($this->inserirCidade($cd_estado, $nm_cidade, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Cidade cadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Cidade!</p>\n";
        }
      }
    } 

    private function alterarStatus($cd_cidade) {
      $dados= $this->selectDadosCidade($cd_cidade);

      $cd_estado = $dados['cd_estado'];
      $nm_cidade = $dados['nm_cidade'];
      $eh_ativo = $dados['eh_ativo'];
                    
      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
    
      if ($this->alterarCidade($cd_cidade, $cd_estado, $nm_cidade, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Cidade alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status da Cidade!</p>\n";
      }                                                                                               
    }
    
    public function apresentaSeletorCidadesEstado($cd_estado, $cd_cidade) {
      $cidades= $this->selectCidades('1', $cd_estado, '');
      $saida  = "<select id=\"cidades\" name=\"cidade\" class=\"fontConteudo\">\n";
      foreach ($cidades as $cidade) {
        $saida .= "<option value=\"".$cidade['cd_cidade']."\" ";
        if ($cidade['cd_cidade'] == $cd_cidade) {          echo " selected=\"selected\"";        }
        $saida .= ">".$cidade['nm_cidade']."</option>\n";
      }
      $saida .= "</select>\n";
      return $saida;
    }
    
//**************BANCO DE DADOS**************************************************    
    public function selectCidades($eh_ativo, $cd_estado, $inicial) {
      $sql  = "SELECT c.*, e.sg_estado ".
              "FROM life_cidades c, life_estados e ".
              "WHERE c.cd_estado = e.cd_estado ";
      if ($cd_estado > 0) {
        $sql.= "AND c.cd_estado = '$cd_estado' ";
      }
      if ($eh_ativo != '2') {
        $sql.= "AND c.eh_ativo = '$eh_ativo' ";
      }
      if ($inicial != '0') {
        $inicial= $inicial.'%';
        $sql.= "AND UPPER(c.nm_cidade) like UPPER('$inicial') ";
      } 
      $sql.= "ORDER BY c.nm_cidade ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CIDADES!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosCidade($cd_cidade) {
      $sql  = "SELECT * ".
              "FROM life_cidades ".
              "WHERE cd_cidade = '$cd_cidade' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CIDADES!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    private function inserirCidade($cd_estado, $nm_cidade, $eh_ativo) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_cidades ".
             "(cd_estado, nm_cidade, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_estado\", \"$nm_cidade\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'cidades');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CIDADES!");
      $saida = mysql_affected_rows();
      return $saida;     
    }
            
    private function alterarCidade($cd_cidade, $cd_estado, $nm_cidade, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_cidades SET ".
             "cd_estado = \"$cd_estado\", ".
             "nm_cidade = \"$nm_cidade\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_cidade = '$cd_cidade' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'cidades');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CIDADES!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  }
?>