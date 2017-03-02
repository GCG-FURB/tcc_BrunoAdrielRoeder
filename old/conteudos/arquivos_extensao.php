<?php
  class ArquivoExtensao {
    
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
          $this->montarFormularioCadastro($secao, $subsecao, $item, $ativas);
        break;

        case "editar":
          $this->montarFormularioEdicao($secao, $subsecao, $item, $ativas, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao($secao, $subsecao, $item, $ativas);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;        
        
        case "alt_status":
          $this->alterarStatusItem($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
                
      }
    }
   
    private function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                               $opcao['descricao']= "Ativas";                              $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                               $opcao['descricao']= "Inativas";                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                               $opcao['descricao']= "Ativas/Inativas";                     $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                $opcao['descricao']= "------------------------------";      $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Extensão\" title=\"Nova Extensão\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";      
    }
    
    private function listarItens($secao, $subsecao, $item, $ativas) {
      $extensoes= $this->selectExtensoesArquivos($ativas);    

      echo "<h2>Extensões de Arquivos ";
      if ($ativas == 1) {        echo "  Ativas";      } elseif ($ativas == 0) {        echo "  Inativas";      }
      echo "</h2>\n";
      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Aplicações:</td>\n";
      echo "      <td class=\"celConteudo\">Extensões:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($extensoes as $ext) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$ext['ds_tipo_arquivo']."</td>\n";           
        echo "      <td class=\"celConteudo\">".$ext['nm_extensao']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharExtensao($ext['cd_arquivo_extensao']);;
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$ext['cd_arquivo_extensao']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$ext['cd_arquivo_extensao']."&acao=alt_status\">";
        if ($ext['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";              
      }
      echo "  </table>\n";       
    }
     
    private function detalharExtensao($cd_arquivo_extensao) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $extensao = $this->selectDadosExtensao($cd_arquivo_extensao);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($extensao['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($extensao['dt_cadastro'])."<br />\n";
      if ($extensao['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($extensao['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($extensao['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
         
    private function montarFormularioCadastro($secao, $subsecao, $item, $ativas) {
      $cd_arquivo_extensao= "";
      $nm_extensao= "";
      $ds_extensao= "";
      $eh_ativo= 1;
      $cd_tipo_arquivo= "";             
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Extensões de Arquivos para Upload</h2>\n";
      $this->imprimeFormularioCadastro($secao, $subsecao, $item, $ativas, $cd_arquivo_extensao, $nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo);
    }
    
    private function montarFormularioEdicao($secao, $subsecao, $item, $ativas, $cd_arquivo_extensao) {
      $dados= $this->selectDadosExtensao($cd_arquivo_extensao);
      $nm_extensao= $dados['nm_extensao'];
      $ds_extensao= $dados['ds_extensao'];
      $eh_ativo= $dados['eh_ativo'];
      $cd_tipo_arquivo= $dados['cd_tipo_arquivo'];

      $_SESSION['life_edicao']= 1;      
      echo "  <h2>Edição de Extensões de Arquivos para Upload</h2>\n";
      $this->imprimeFormularioCadastro($secao, $subsecao, $item, $ativas, $cd_arquivo_extensao, $nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo);
    }    
    
    private function imprimeFormularioCadastro($secao, $subsecao, $item, $ativas, $cd_arquivo_extensao, $nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'conteudos/tipos_arquivos.php';                              $tip = new TipoArquivo();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

                  
      include "js/js_cadastro_extensao_arquivos.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";

      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_arquivo_extensao', $cd_arquivo_extensao);
      $util->linhaUmCampoText(1, 'Descrição: ', 'nm_extensao', 150, 100, $nm_extensao);
      $util->linhaUmCampoText(1, 'Regra/Extensão: ', 'ds_extensao', 50, 100, $ds_extensao);

      $tipos = $tip->selectTiposArquivos('1');
      $opcoes= array();      
      $opcao= array();          $opcao[]= '0';                        $opcao[]= 'Selecione o Tipo do Arquivo';      $opcoes[]= $opcao;
      foreach ($tipos as $t) {
        $opcao= array();        $opcao[]= $t['cd_tipo_arquivo'];      $opcao[]= $t['ds_tipo_arquivo'];              $opcoes[]= $opcao;
      } 
      $util->linhaSeletor('Aplicação', 'cd_tipo_arquivo', $cd_tipo_arquivo, $opcoes, '100');
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'É Ativo';          $opcoes[]= $opcao; 
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não é Ativo';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo', 'eh_ativo', $eh_ativo, $opcoes, '100');

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_extensao'); 
    }
    
    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
            
      $cd_arquivo_extensao = addslashes($_POST['cd_arquivo_extensao']);
      $nm_extensao = $util->limparVariavel($_POST['nm_extensao']);
      $ds_extensao = addslashes($_POST['ds_extensao']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $cd_tipo_arquivo= $util->limparVariavel($_POST['cd_tipo_arquivo']);
      
      if ($cd_arquivo_extensao > 0) {
        if ($this->alteraExtensao($cd_arquivo_extensao, $nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo)) {
          echo "<p class=\"fontConteudoSucesso\">Extensão de Arquivo para Upload editada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Extensão de Arquivo para Upload ou nenhuma informação alterada!</p>\n";
        }        
      } else {
        if ($this->insereExtensao($nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo)) {
          echo "<p class=\"fontConteudoSucesso\">Extensão de Arquivo para Upload cadastrada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Extensão de Arquivo para Upload!</p>\n";
        }
      }
      unset($_SESSION['life_edicao']);      
    } 
    
    
    private function alterarStatusItem($cd_arquivo_extensao) {
      $dados= $this->selectDadosExtensao($cd_arquivo_extensao);
      $nm_extensao= $dados['nm_extensao'];
      $ds_extensao= $dados['ds_extensao'];
      $eh_ativo= $dados['eh_ativo'];
      $cd_tipo_arquivo= $dados['cd_tipo_arquivo'];
      
      if ($eh_ativo == 1) {        $eh_ativo= 0;      } else {        $eh_ativo= 1;      }
      if ($this->alteraExtensao($cd_arquivo_extensao, $nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Extensão de Arquivo para Upload alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar stautus da Extensão de Arquivo para Upload!</p>\n";
      }
    }    
    
    public function retornaExtensoesArquivosObjetoAprendizagem($cd_formato) {
      require_once 'conteudos/formatos.php';                                    $for = new Formato();
      
      $dados = $for->selectDadosFormato($cd_formato);
      
      $extensoes = $this->selectExtensoes('1', $dados['cd_tipo_arquivo'], '2');
      
      $retorno = '';
      foreach ($extensoes as $e) {
        $retorno.= $e['nm_extensao']." [.".$e['ds_extensao']."]\n";
      } 
      return $retorno;
    }
    
    public function retornaRelacaoExtensoesHidden($cd_formato) {
      require_once 'conteudos/formatos.php';                                    $for = new Formato();
      
      $dados = $for->selectDadosFormato($cd_formato);
      
      $extensoes = $this->selectExtensoes('1', $dados['cd_tipo_arquivo'], '2');
      
      $retorno = '';
      $retorno.= "      <input type=\"hidden\" name=\"quantidade\" id=\"quantidade\" value=\"".count($extensoes)."\" />\n";    

      $indexador = 1;
      foreach ($extensoes as $e) {
        $nome = 'extensao_'.$indexador;
        $retorno.= "      <input type=\"hidden\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$e['ds_extensao']."\" />\n";
        $indexador += 1;
      } 
      return $retorno;
    } 
    
//**************BANCO DE DADOS**************************************************    
    public function selectExtensoesArquivos($eh_ativo, $eh_editavel = '1') {
      $sql  = "SELECT ext.*, tip.nm_tipo_arquivo, tip.ds_tipo_arquivo  ".
              "FROM life_arquivos_extensao ext, life_tipos_associacoes_arquivos tip ".
              "WHERE tip.cd_tipo_arquivo = ext.cd_tipo_arquivo ";
      if ($eh_ativo != 2) {
        $sql.= "AND ext.eh_ativo = '$eh_ativo' ";
      }
      if ($eh_editavel != 2) {
        $sql.= "AND ext.eh_editavel = '$eh_editavel' ";
      }
      $sql.= "ORDER BY tip.ds_tipo_arquivo, ext.nm_extensao";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS EXTENSÃO");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectExtensoes($eh_ativo, $cd_tipo_arquivo, $eh_editavel = '1') {
      $sql  = "SELECT *  ".
              "FROM life_arquivos_extensao ".
              "WHERE cd_tipo_arquivo = '$cd_tipo_arquivo' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      if ($eh_editavel != '2') {
        $sql.= "AND eh_editavel = '$eh_editavel' ";
      }
      $sql.= "ORDER BY nm_extensao, ds_extensao ";  
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS EXTENSÃO");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }  
        
    public function selectDadosExtensao($cd_arquivo_extensao) {
      $sql  = "SELECT *  ".
              "FROM life_arquivos_extensao ".
              "WHERE cd_arquivo_extensao = '$cd_arquivo_extensao' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS EXTENSÃO");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereExtensao($nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');    
      $sql = "INSERT INTO life_arquivos_extensao ".
             "(nm_extensao, ds_extensao, eh_ativo, cd_tipo_arquivo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_extensao\", \"$ds_extensao\", '$eh_ativo', \"$cd_tipo_arquivo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'arquivos_extensao');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS EXTENSÃO");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraExtensao($cd_arquivo_extensao, $nm_extensao, $ds_extensao, $eh_ativo, $cd_tipo_arquivo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_arquivos_extensao SET ".
             "nm_extensao = \"$nm_extensao\", ".
             "ds_extensao = \"$ds_extensao\", ".
             "eh_ativo= '$eh_ativo', ".
             "cd_tipo_arquivo= \"$cd_tipo_arquivo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_arquivo_extensao= '$cd_arquivo_extensao' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'arquivos_extensao');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS EXTENSÃO");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
    
  }
?>