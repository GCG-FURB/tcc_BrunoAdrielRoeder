<?php
  class Estado {
    
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
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
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

    private function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                         $opcao['descricao']= "Ativos";                                  $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                         $opcao['descricao']= "Inativos";                                $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";                $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                         $opcao['descricao']= "Ativos/Inativos";                         $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";                $opcao['link']= "";                                                                                       $opcao['descricao']= "------------------------------";          $opcoes[]= $opcao;

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Estado\" title=\"Novo Estado\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      $itens = $this->selectEstados($ativas);    

      $mensagem = "Estados ";
      if ($ativas == 1)   {        $mensagem.= "Ativas";      } elseif ($ativas == 0) {        $mensagem.= "Inativas";      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Estado:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_estado']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharEstado($it['cd_estado']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_estado']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\"border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_estado']."&acao=status\">";
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

    private function detalharEstado($cd_estado) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosEstado($cd_estado);
      
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
      $cd_estado = "";
      $nm_estado = "";
      $eh_ativo = "1";
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Estados</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_estado, $nm_estado, $eh_ativo);
    }

    private function montarFormularioEdicao($link, $cd_estado) {
      $dados= $this->selectDadosEstado($cd_estado);

      $nm_estado = $dados['nm_estado'];
      $eh_ativo = $dados['eh_ativo'];

      $_SESSION['life_edicao']= 1;      
      echo "  <h2>Edição de Estado</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_estado, $nm_estado, $eh_ativo);
    }    

    private function imprimeFormularioCadastro($link, $cd_estado, $nm_estado, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_estado.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_estado', $cd_estado);

      $util->linhaUmCampoText(1, 'Nome: ', 'nm_estado', 150, 100, $nm_estado);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      
      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_estado'); 
    }
                         
    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_estado = addslashes($_POST['cd_estado']);
      $nm_estado = $util->limparVariavel($_POST['nm_estado']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_estado > 0) {
        if ($this->alterarEstado($cd_estado, $nm_estado, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Estado editado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Estado, ou nenhuma informação alterada!</p>\n";
        }  
      } else {
        if ($this->inserirEstado($nm_estado, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Estado cadastrado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Estado!</p>\n";
        }
      }
    } 

    private function alterarStatus($cd_estado) {
      $dados= $this->selectDadosEstado($cd_estado);

      $nm_estado = $dados['nm_estado'];
      $eh_ativo = $dados['eh_ativo'];
                    
      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }
    
      if ($this->alterarEstado($cd_estado, $nm_estado, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Estado alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status do Estados!</p>\n";
      }                                                                                               
    }
    
    public function retornaSeletorEstados($cd_estado) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
    
      $itens = $this->selectEstados('1');
      
      $opcoes= array();
      $opcao= array();      $opcao[]= '0';                   $opcao[]= 'Selecione um Estado';      $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();    $opcao[]= $it['cd_estado'];      $opcao[]= $it['nm_estado'];           $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Estado: ', 'cd_estado', $cd_estado, $opcoes);        
    }    

    public function retornaSeletorEstadosAjax($cd_estado) {
      $estados = $this->selectEstados('1');
      $saida  = "<select name=\"estado\" class=\"fontConteudo\" id=\"estado\" onchange=\"buscaCidades()\">\n";
      if ($cd_estado == "") {
        $saida .= "<option value=\"\" selected=\"selected\">UF</option>\n";
      }
      foreach ($estados as $est) {
        $saida .= "<option value=\"".$est['cd_estado']."\" ";
        if ($est['cd_estado'] == $cd_estado) {           $saida.= "selected=\"selected\"";        }
        $saida.= ">".$est['sg_estado']."</option>\n";     
      }
      $saida .= "</select>\n";
      return $saida;
    }


//**************BANCO DE DADOS**************************************************    
    public function selectEstados($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_estados ".
              "WHERE cd_estado > 0 ";
      if ($eh_ativo != '2') {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_estado ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ESTADOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosEstado($cd_estado) {
      $sql  = "SELECT * ".
              "FROM life_estados ".
              "WHERE cd_estado = '$cd_estado' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ESTADOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    private function inserirEstado($nm_estado, $eh_ativo) {
      if (isset($_SESSION['life_codigo'])) {        $cd_usuario_cadastro = $_SESSION['life_codigo'];      } else {        $cd_usuario_cadastro = '0';      }
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_estados ".
             "(nm_estado, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_estado\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'estados');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ESTADOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }
            
    private function alterarEstado($cd_estado, $nm_estado, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_estados SET ".
             "nm_estado = \"$nm_estado\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_estado = '$cd_estado' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'estados');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ESTADOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  }
?>