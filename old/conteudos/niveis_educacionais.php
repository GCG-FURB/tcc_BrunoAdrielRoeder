<?php
  class NivelEducacional {
    
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
          $this->alterarSituacaoAtivoNivelEducacional($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                 $opcao['descricao']= "Ativos";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                 $opcao['descricao']= "Inativos";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                 $opcao['descricao']= "Ativos/Inativos";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                               $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Nível Educacional\" title=\"Novo Nível Educacional\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Níveis Educacionais ";
      if ($ativas == 1)    {      $mensagem.= "Ativos ";         } elseif ($ativas == 0)    {       $mensagem.= "Inativos ";       }

      $itens = $this->selectNiveisEducacionais($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Conteúdo:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_nivel_educacional']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharNivelEducacional($it['cd_nivel_educacional']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_nivel_educacional']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_nivel_educacional']."&acao=status\">";
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
    
    public function detalharNivelEducacional($cd_nivel_educacional) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosNivelEducacional($cd_nivel_educacional);
      
      $retorno = "";
      if ($dados['cd_usuario_cadastro'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      } else {
        $retorno.= "Cadastro automático<br />";
      }
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
     
    private function montarFormularioCadastro($link) {
      $cd_nivel_educacional = "";
      $nm_nivel_educacional = "";
      $ds_nivel_educacional = "";
      $ds_arquivo_imagem = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Níveis Educacionais</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_nivel_educacional, $nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_nivel_educacional) {
      $dados = $this->selectDadosNivelEducacional($cd_nivel_educacional);

      $nm_nivel_educacional = $dados['nm_nivel_educacional'];
      $ds_nivel_educacional = $dados['ds_nivel_educacional'];
      $ds_arquivo_imagem = $dados['ds_arquivo_imagem'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Nível Educacional</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_nivel_educacional, $nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_nivel_educacional, $nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_nivel_educacional.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\" enctype=\"multipart/form-data\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_nivel_educacional', $cd_nivel_educacional);
      $util->campoHidden('ds_arquivo_imagem_antiga', $ds_arquivo_imagem);

      $util->linhaUmCampoText(1, 'Nível Educacional: ', 'nm_nivel_educacional', '150', '100', $nm_nivel_educacional);
      $util->linhaTexto(0, 'Descrição: ', 'ds_nivel_educacional', $ds_nivel_educacional, '5', '100');

      $util->linhaComentario('');
      if ($cd_nivel_educacional > 0) {
        $util->linhaUmCampoArquivo('0', 'Imagem para identificação do Nível Educacional: ', 'ds_arquivo_imagem', 100, 100, '');
        $util->linhaComentario('Para substituir a Imagem de identificação do Nível Educacional, selecione um novo arquivo de imagem!<br />Se não houver a seleção de novo arquivo será mantido o anterior!<br />O arquivo com a imagem deve estar no formato .png ou .jpg!');
      } else {
        $util->linhaUmCampoArquivo('1', 'Imagem para identificação do Nível Educacional: ', 'ds_arquivo_imagem', 100, 100, '');
        $util->linhaComentario('O arquivo com a imagem deve estar no formato .png ou .jpg!');
      }
      $util->linhaComentario('');

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_nivel_educacional');
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/fotos.php';                                       $fot = new Fotos();

      $cd_nivel_educacional = addslashes($_POST['cd_nivel_educacional']);
      $nm_nivel_educacional = $util->limparVariavel($_POST['nm_nivel_educacional']);
      $ds_nivel_educacional = $util->limparVariavel($_POST['ds_nivel_educacional']);
      $ds_arquivo_imagem_antiga = addslashes($_POST['ds_arquivo_imagem_antiga']);
      $eh_ativo = addslashes($_POST['eh_ativo']);


      $tp_associacao = 'NE';
      $dados_pasta = $fot->selectDadosTiposAssociacoesFotos($tp_associacao);
      $ds_pasta = $dados_pasta['ds_pasta_tipo_associacao_foto'];

      $arquivo = $_FILES['ds_arquivo_imagem'];
      if ($arquivo['name'] != '') {
        $foto = $fot->enviarFoto('ds_arquivo_imagem', $ds_pasta, $tp_associacao, '');
        if ($foto[0] != '') {
          echo "<p class=\"fontConteudoAlerta\">ERRO - ".$foto[0]."</p>\n";
          $ds_arquivo_imagem = '';
        } else {
          $ds_arquivo_imagem = $ds_pasta.$foto[1];
        }
      } else {
        if ($cd_nivel_educacional > 0) {
          $ds_arquivo_imagem = $ds_arquivo_imagem_antiga;
        } else {
          echo "<p class=\"fontConteudoAlerta\">ERRO - Não foi selecionado Arquivo com a Imagem de Identificação!</p>\n";
          $ds_arquivo_imagem = '';
        }
      }

      if ($cd_nivel_educacional > 0) {
        if ($this->alteraNivelEducacional($cd_nivel_educacional, $nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Nível Educacional editado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Nível Educacional, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereNivelEducacional($nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Nível Educacional cadastrado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Nível Educacional!</p>\n";
        }
      }
    }

    private function alterarSituacaoAtivoNivelEducacional($cd_nivel_educacional) {
      $dados = $this->selectDadosNivelEducacional($cd_nivel_educacional);

      $nm_nivel_educacional = $dados['nm_nivel_educacional'];
      $ds_nivel_educacional = $dados['ds_nivel_educacional'];
      $ds_arquivo_imagem = $dados['ds_arquivo_imagem'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraNivelEducacional($cd_nivel_educacional, $nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Nível Educacional alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do Nível Educacional!</p>\n";
      }
    }
            

    public function retornaSeletorNiveisEducacionais($cd_nivel_educacional) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectNiveisEducacionais('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                                  $opcao[]= 'Selecione um Nível de Instrução';             $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_nivel_educacional'];          $opcao[]= $it['nm_nivel_educacional'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Níveis de Instrução: ', 'cd_nivel_educacional', $cd_nivel_educacional, $opcoes, '800');
    }
    

    public function retornaDados($cd_nivel_educacional, $cd_general, $descricao) {

      $dados = $this->selectDadosNivelEducacional($cd_nivel_educacional);

      $retorno = "<b>".$descricao."</b> ".$dados['nm_nivel_educacional'];

      return $retorno;
    } 


    public function retornaSeletorNiveisEducacionaisFiltro($cd_nivel_educacional, $nome, $tamanho, $exibir_ajuda, $descricao, $ajuda) {
      $cd_nivel_educacional = $cd_nivel_educacional;
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectNiveisEducacionais('1');

      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" class=\"fontConteudoCampoSeletorHintFiltro\" placeholder=\"".$descricao."\" ";
      if ($exibir_ajuda == '1') {
        echo "alt=\"".$descricao."\" title=\"".$descricao."\" ";
      } else {
        echo "alt=\"".$ajuda."\" title=\"".$ajuda."\" ";
      }
      echo "tabindex=\"1\">\n";
      echo "  			    <option ";
      if ($cd_nivel_educacional == '') {          echo " selected ";        }
      echo " value=\"0\">$descricao</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option ";
        if ($it['cd_nivel_educacional'] == $cd_nivel_educacional) {          echo " selected ";        }
        echo " value=\"".$it['cd_nivel_educacional']."\">".$it['nm_nivel_educacional']."</option>\n";
      }
      echo "          </select>\n";
      if ($exibir_ajuda == '1') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Selecione o nível educacional desejado para pesquisar por Objetos de Aprendizagem.\n";
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\">\n";
      }
    }
//**************BANCO DE DADOS**************************************************
    public function selectNiveisEducacionais($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_niveis_educacionais ".
              "WHERE cd_nivel_educacional > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_nivel_educacional ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosNivelEducacional($cd_nivel_educacional) {
      $sql  = "SELECT * ".
              "FROM life_niveis_educacionais ".
              "WHERE cd_nivel_educacional = '$cd_nivel_educacional' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereNivelEducacional($nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_niveis_educacionais ".
             "(nm_nivel_educacional, ds_nivel_educacional, ds_arquivo_imagem, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_nivel_educacional\", \"$ds_nivel_educacional\", \"$ds_arquivo_imagem\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'niveis_educacionais');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraNivelEducacional($cd_nivel_educacional, $nm_nivel_educacional, $ds_nivel_educacional, $ds_arquivo_imagem, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_niveis_educacionais SET ".
             "nm_nivel_educacional = \"$nm_nivel_educacional\", ".
             "ds_nivel_educacional = \"$ds_nivel_educacional\", ".
             "ds_arquivo_imagem = \"$ds_arquivo_imagem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_nivel_educacional= '$cd_nivel_educacional' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'niveis_educacionais');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>