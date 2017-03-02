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
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $ativas, $liberadas);
        break;        
               
        case "status":
          $this->alterarSituacaoAtivoAreaFormacao($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $ativas, $liberadas);
        break;
        
        case "liberacao":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas;
          $this->montarFormularioLiberacaoBloqueio($link, $codigo);
        break;
        
        case "salvar_liberacao":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarLiberacao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $ativas, $liberadas);
        break;            
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas, $liberadas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=1";                 $opcao['descricao']= "Ativas";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=0";                 $opcao['descricao']= "Inativas";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=2";                 $opcao['descricao']= "Ativas/Inativas";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                 $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;

      $opcao= array();      $opcao['indice']= "5";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&li=1";                    $opcao['descricao']= "Liberadas";                                         $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "6";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&li=0";                    $opcao['descricao']= "Bloqueadas";                                        $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "7";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&li=20";                   $opcao['descricao']= "Liberadas/Bloqueadas";                              $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "8";      $opcao['link']= "";                                                                                                 $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Área de formação\" title=\"Nova Área de formação\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas, $liberadas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Áreas de formação ";
      if ($ativas == 1)    {      $mensagem.= "Ativas ";         } elseif ($ativas == 0)    {       $mensagem.= "Inativas ";       }
      if ($liberadas == 1) {      $mensagem.= "Liberadas ";      } elseif ($liberadas == 0) {       $mensagem.= "Bloquadas ";      }

      $itens = $this->selectAreasFormacao($ativas, $liberadas);
      
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
        echo "      <td class=\"celConteudo\">".$it['nm_area_formacao']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharAreaFormacao($it['cd_area_formacao']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_area_formacao']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_area_formacao']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_area_formacao']."&acao=liberacao\">";
        if ($it['eh_liberado'] == '1') {
          echo "          <img src=\"icones/bloquear.png\" alt=\"Bloquear\" title=\"Bloquear\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Liberar\" title=\"Liberar\" border=\"0\"></a>\n";
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
      $cd_area_formacao = "";
      $nm_area_formacao = "";
      $ds_area_formacao = "";
      $eh_liberado = "1";
      $cd_area_formacao_destino = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Áreas de formação</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_area_formacao) {
      $dados = $this->selectDadosAreaFormacao($cd_area_formacao);

      $nm_area_formacao = $dados['nm_area_formacao'];
      $ds_area_formacao = $dados['ds_area_formacao'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_area_formacao_destino = $dados['cd_area_formacao_destino'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Área de formação</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_area_formacao.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_area_formacao', $cd_area_formacao);
      $util->campoHidden('eh_liberado', $eh_liberado);
      $util->campoHidden('cd_area_formacao_destino', $cd_area_formacao_destino);
      $util->linhaUmCampoText(1, 'Área de formação: ', 'nm_area_formacao', '150', '100', $nm_area_formacao);
      $util->linhaTexto(0, 'Descrição: ', 'ds_area_formacao', $ds_area_formacao, '5', '100');

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_area_formacao'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_area_formacao = addslashes($_POST['cd_area_formacao']);
      $nm_area_formacao = $util->limparVariavel($_POST['nm_area_formacao']);
      $ds_area_formacao = $util->limparVariavel($_POST['ds_area_formacao']);
      $eh_liberado = addslashes($_POST['eh_liberado']);
      $cd_area_formacao_destino = addslashes($_POST['cd_area_formacao_destino']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_area_formacao > 0) {
        if ($this->alteraAreaFormacao($cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Área de formação editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Área de formação, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereAreaFormacao($nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Área de formação cadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Área de formação!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoAreaFormacao($cd_area_formacao) {
      $dados = $this->selectDadosAreaFormacao($cd_area_formacao);

      $nm_area_formacao = $dados['nm_area_formacao'];
      $ds_area_formacao = $dados['ds_area_formacao'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_area_formacao_destino = $dados['cd_area_formacao_destino'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraAreaFormacao($cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Área de formação alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da Área de formação!</p>\n";
      }
    }
    
    public function montarFormularioLiberacaoBloqueio($link, $cd_area_formacao) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $dados = $this->selectDadosAreaFormacao($cd_area_formacao);

      $nm_area_formacao = $dados['nm_area_formacao'];
      $ds_area_formacao = $dados['ds_area_formacao'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_area_formacao_destino = $dados['cd_area_formacao_destino'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Liberação/Bloqueio de Área de formação</h2>\n";

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_liberacao_area_formacao.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar_liberacao\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_area_formacao', $cd_area_formacao);
      $util->campoHidden('nm_area_formacao', $nm_area_formacao);
      $util->campoHidden('ds_area_formacao', $ds_area_formacao);
      $util->campoHidden('eh_ativo', $eh_ativo);

      $util->linhaDuasColunasComentario('Área de formação: ', $nm_area_formacao);

      if ($eh_liberado == '1') {
        $util->linhaComentario('A Área de formação encontra-se Liberada!');
        $lista = array();
        $opcao= array();      $opcao[]= '1';      $opcao[]= 'Manter Liberada';      $lista[]= $opcao;
        $opcao= array();      $opcao[]= '0';      $opcao[]= 'Bloquear';             $lista[]= $opcao;        
        $util->linhaNCampoRadio($lista, 'eh_liberado', $eh_liberado, true);
        
        $util->linhaComentario('');
        $util->linhaComentario('Caso queira Bloquear a Área de formação, aponte a Área para a qual deseja transferir os itens associados à área atual.');
        $this->retornaSeletorOutrasAreasFormacao($cd_area_formacao);
      } else {
        $util->linhaComentario('A Área de formação encontra-se Bloqueada!');
        $lista = array();
        $opcao= array();      $opcao[]= '1';      $opcao[]= 'Liberar';              $lista[]= $opcao;
        $opcao= array();      $opcao[]= '0';      $opcao[]= 'Manter Bloqueada';     $lista[]= $opcao;        
        $util->linhaNCampoRadio($lista, 'eh_liberado', $eh_liberado, true);
        if ($dados['cd_usuario_ultima_utilizacao'] != '') {
          $util->campoHidden('cd_area_formacao_destino', $cd_area_formacao_destino);
        } else {
          $util->linhaComentario('');
          $util->linhaComentario('Caso queira Manter Bloqueada a Área de formação, aponte a Área para a qual deseja transferir os itens associados à área atual.');
          $this->retornaSeletorAreasFormacao($cd_area_formacao);
        }
      }

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'eh_liberado'); 
    }
                          
    public function salvarLiberacao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_area_formacao = addslashes($_POST['cd_area_formacao']);
      $nm_area_formacao = $util->limparVariavel($_POST['nm_area_formacao']);
      $ds_area_formacao = $util->limparVariavel($_POST['ds_area_formacao']);
      $eh_liberado = addslashes($_POST['eh_liberado']);
      $cd_area_formacao_destino = addslashes($_POST['cd_area_formacao_destino']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      
//funcionalidade para ajustar todos os locais que usares area de formacao

      if ($this->alteraAreaFormacao($cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Liberação/Bloqueio de Área de formação realizada com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas na Liberação/Bloqueio da Área de formação, ou nenhuma informação alterada!</p>\n";
      }
    } 
        
                                         /*
    private function existeNumeroOrdem($nr_ordem) {
      $dados = $this->selectNumeroOrdemAreaFormacao($nr_ordem);
      if ($dados['cd_area_formacao'] != '') {
        return true;
      } else {
        return false;
      }
    }
    

                                    */
    public function retornaSeletorOutrasAreasFormacao($cd_area_formacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectAreasFormacao('1', '1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione uma Área de formação';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        if ($cd_area_formacao != $it['cd_area_formacao']) { 
          $opcao= array();      $opcao[] = $it['cd_area_formacao'];          $opcao[]= $it['nm_area_formacao'];                   $opcoes[]= $opcao;
        }
      }
      $util->linhaSeletor('Áreas de formação: ', 'cd_area_formacao_destino', $cd_area_formacao, $opcoes, '100');
    }      

    public function retornaSeletorAreasFormacao($cd_area_formacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectAreasFormacao('1', '1');

      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                                   $opcao[]= 'Selecione uma Área de formação';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_area_formacao'];               $opcao[]= $it['nm_area_formacao'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Áreas de formação: ', 'cd_area_formacao', $cd_area_formacao, $opcoes, '100');
    }

//**************BANCO DE DADOS**************************************************
    public function selectAreasFormacao($eh_ativo, $eh_liberado) {
      $sql  = "SELECT * ".
              "FROM life_areas_formacao ".
              "WHERE cd_area_formacao > '0' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      if ($eh_liberado != 2) {
        $sql.= "AND eh_liberado = '$eh_liberado' ";
      }
      $sql.= "ORDER BY nm_area_formacao ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
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
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereAreaFormacao($nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_areas_formacao ".
             "(nm_area_formacao, ds_area_formacao, eh_liberado, cd_area_formacao_destino, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_area_formacao\", \"$ds_area_formacao\", \"$eh_liberado\", \"$cd_area_formacao_destino\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_formacao');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraAreaFormacao($cd_area_formacao, $nm_area_formacao, $ds_area_formacao, $eh_liberado, $cd_area_formacao_destino, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_areas_formacao SET ".
             "nm_area_formacao = \"$nm_area_formacao\", ".
             "ds_area_formacao = \"$ds_area_formacao\", ".
             "eh_liberado = \"$eh_liberado\", ".
             "cd_area_formacao_destino = \"$cd_area_formacao_destino\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_area_formacao= '$cd_area_formacao' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_formacao');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>