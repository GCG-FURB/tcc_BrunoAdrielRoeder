<?php
  class SubAreaConhecimento {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';               }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = '1';            }
      if (isset($_GET['li']))    {      $liberadas = addslashes($_GET['li']);       } else {      $liberadas = '2';         }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';             }
      if (isset($_GET['cda']))   {      $area = addslashes($_GET['cda']);           } else {      $area = '';               }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas, $liberadas);
        break;

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=".$ativas;
          $this->montarFormularioCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=".$ativas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas, $liberadas);
        break;        
               
        case "status":
          $this->alterarSituacaoAtivoSubAreaConhecimento($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas, $liberadas);
        break;
        
        case "liberacao":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=".$ativas;
          $this->montarFormularioLiberacaoBloqueio($link, $codigo);
        break;
        
        case "salvar_liberacao":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarLiberacao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas, $liberadas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas, $liberadas);
        break;            
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $area, $ativas, $liberadas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=1";                           $opcao['descricao']= "Ativas";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=0";                           $opcao['descricao']= "Inativas";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=2";                           $opcao['descricao']= "Ativas/Inativas";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";          $opcao['link']= "";                                                                                                                         $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;

      $opcao= array();      $opcao['indice']= "5";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas."&li=1";                              $opcao['descricao']= "Liberadas";                                         $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "6";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas."&li=0";                              $opcao['descricao']= "Bloqueadas";                                        $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "7";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas."&li=20";                             $opcao['descricao']= "Liberadas/Bloqueadas";                              $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "8";          $opcao['link']= "";                                                                                                                         $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;

      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      $areas = $are_con->selectAreasConhecimento('1');
      
      $i = 9;
      foreach ($areas as $a) {
        $opcao= array();      $opcao['indice']= $i; $i+=1;    $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$a['cd_area_conhecimento']."&at=".$ativas."&li=".$liberadas;    $opcao['descricao']= $a['nm_area_conhecimento'];                     $opcoes[]= $opcao;
      }
      if ($area != '') {
        $opcao= array();      $opcao['indice']= $i; $i+=1;    $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&li=".$liberadas;                                  $opcao['descricao']= 'Todas as Áreas de Conhecimento';                    $opcoes[]= $opcao;
      } 
      $opcao= array();      $opcao['indice']= $i; $i+=1;    $opcao['link']= "";                                                                                                                         $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Sub Área do Conhecimento\" title=\"Nova Sub Área do Conhecimento\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $area, $ativas, $liberadas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Sub Áreas do Conhecimento ";
      if ($ativas == 1)    {      $mensagem.= "Ativas ";         } elseif ($ativas == 0)    {       $mensagem.= "Inativas ";       }
      if ($liberadas == 1) {      $mensagem.= "Liberadas ";      } elseif ($liberadas == 0) {       $mensagem.= "Bloquadas ";      }

      $itens = $this->selectSubAreasConhecimento($ativas, $liberadas, $area);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Conteúdo:</td>\n";
      echo "      <td class=\"celConteudo\">Área do Conhecimento:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_sub_area_conhecimento']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_area_conhecimento']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharSubAreaConhecimento($it['cd_sub_area_conhecimento']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_sub_area_conhecimento']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_sub_area_conhecimento']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&li=".$liberadas."&at=".$ativas."&cd=".$it['cd_sub_area_conhecimento']."&acao=liberacao\">";
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
    
    public function detalharSubAreaConhecimento($cd_sub_area_conhecimento) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosSubAreaConhecimento($cd_sub_area_conhecimento);
      
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
      $cd_sub_area_conhecimento = "";
      $cd_area_conhecimento = "";
      $nm_sub_area_conhecimento = "";
      $ds_sub_area_conhecimento = "";
      $eh_liberado = "1";
      $cd_sub_area_conhecimento_destino = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Sub Áreas do Conhecimento</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_sub_area_conhecimento) {
      $dados = $this->selectDadosSubAreaConhecimento($cd_sub_area_conhecimento);

      $cd_area_conhecimento = $dados['cd_area_conhecimento'];
      $nm_sub_area_conhecimento = $dados['nm_sub_area_conhecimento'];
      $ds_sub_area_conhecimento = $dados['ds_sub_area_conhecimento'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_sub_area_conhecimento_destino = $dados['cd_sub_area_conhecimento_destino'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Sub Área do Conhecimento</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_sub_area_conhecimento.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_sub_area_conhecimento', $cd_sub_area_conhecimento);
      $util->campoHidden('eh_liberado', $eh_liberado);
      $util->campoHidden('cd_sub_area_conhecimento_destino', $cd_sub_area_conhecimento_destino);
      
      $util->linhaUmCampoText(1, 'Sub Área do Conhecimento: ', 'nm_sub_area_conhecimento', '150', '70', $nm_sub_area_conhecimento);
      $util->linhaTexto(0, 'Descrição: ', 'ds_sub_area_conhecimento', $ds_sub_area_conhecimento, '5', '965');
      $are_con->retornaSeletorAreasConhecimento($cd_area_conhecimento);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      if ($cd_sub_area_conhecimento > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_sub_area_conhecimento'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_sub_area_conhecimento = addslashes($_POST['cd_sub_area_conhecimento']);
      $nm_sub_area_conhecimento = $util->limparVariavel($_POST['nm_sub_area_conhecimento']);
      $ds_sub_area_conhecimento = $util->limparVariavel($_POST['ds_sub_area_conhecimento']);
      $eh_liberado = addslashes($_POST['eh_liberado']);
      $cd_sub_area_conhecimento_destino = addslashes($_POST['cd_sub_area_conhecimento_destino']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $cd_area_conhecimento = addslashes($_POST['cd_area_conhecimento']);

      if ($cd_sub_area_conhecimento > 0) {
        if ($this->alteraSubAreaConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Sub Área do Conhecimento editada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Sub Área do Conhecimento, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereSubAreaConhecimento($cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Sub Área do Conhecimento cadastrada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Sub Área do Conhecimento!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoSubAreaConhecimento($cd_sub_area_conhecimento) {
      $dados = $this->selectDadosSubAreaConhecimento($cd_sub_area_conhecimento);

      $nm_sub_area_conhecimento = $dados['nm_sub_area_conhecimento'];
      $ds_sub_area_conhecimento = $dados['ds_sub_area_conhecimento'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_sub_area_conhecimento_destino = $dados['cd_sub_area_conhecimento_destino'];
      $eh_ativo = $dados['eh_ativo'];
      $cd_area_conhecimento = $dados['cd_area_conhecimento'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraSubAreaConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Sub Área do Conhecimento alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da Sub Área do Conhecimento!</p>\n";
      }
    }
    
    public function montarFormularioLiberacaoBloqueio($link, $cd_sub_area_conhecimento) {
echo "<pre>";
print_r($cd_sub_area_conhecimento);
echo "</pre>";    
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $dados = $this->selectDadosSubAreaConhecimento($cd_sub_area_conhecimento);
echo "<pre>";
print_r($dados);
echo "</pre>";
      $nm_sub_area_conhecimento = $dados['nm_sub_area_conhecimento'];
      $ds_sub_area_conhecimento = $dados['ds_sub_area_conhecimento'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_sub_area_conhecimento_destino = $dados['cd_sub_area_conhecimento_destino'];
      $eh_ativo = $dados['eh_ativo'];
      $cd_area_conhecimento = $dados['cd_area_conhecimento'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Liberação/Bloqueio de Sub Área do Conhecimento</h2>\n";

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_liberacao_sub_area_conhecimento.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar_liberacao\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_sub_area_conhecimento', $cd_sub_area_conhecimento);
      $util->campoHidden('cd_area_conhecimento', $cd_area_conhecimento);
      $util->campoHidden('nm_sub_area_conhecimento', $nm_sub_area_conhecimento);
      $util->campoHidden('ds_sub_area_conhecimento', $ds_sub_area_conhecimento);
      $util->campoHidden('eh_ativo', $eh_ativo);

      $util->linhaDuasColunasComentario('Sub Área do Conhecimento: ', $nm_sub_area_conhecimento);

      if ($eh_liberado == '1') {
        $util->linhaComentario('A Sub Área do Conhecimento encontra-se Liberada!');
        $lista = array();
        $opcao= array();      $opcao[]= '1';      $opcao[]= 'Manter Liberada';      $lista[]= $opcao;
        $opcao= array();      $opcao[]= '0';      $opcao[]= 'Bloquear';             $lista[]= $opcao;        
        $util->linhaNCampoRadio($lista, 'eh_liberado', $eh_liberado, true);
        
        $util->linhaComentario('');
        $util->linhaComentario('Caso queira Bloquear a Sub Área do Conhecimento, aponte a Área para a qual deseja transferir os itens associados à área atual.');
        $this->retornaSeletorOutrasSubAreasConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento);
      } else {
        $util->linhaComentario('A Sub Área do Conhecimento encontra-se Bloqueada!');      
        $lista = array();
        $opcao= array();      $opcao[]= '1';      $opcao[]= 'Liberar';              $lista[]= $opcao;
        $opcao= array();      $opcao[]= '0';      $opcao[]= 'Manter Bloqueada';     $lista[]= $opcao;        
        $util->linhaNCampoRadio($lista, 'eh_liberado', $eh_liberado, true);
        if ($dados['cd_usuario_ultima_atualizacao'] != '') {
          $util->campoHidden('cd_sub_area_conhecimento_destino', $cd_sub_area_conhecimento_destino);
        } else {
          $util->linhaComentario('');
          $util->linhaComentario('Caso queira Manter Bloqueada a Sub Área do Conhecimento, aponte a Área para a qual deseja transferir os itens associados à área atual.');
          $this->retornaSeletorOutrasSubAreasConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento);
        }
      }

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'eh_liberado'); 
    }
                          
    public function salvarLiberacao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_sub_area_conhecimento = addslashes($_POST['cd_sub_area_conhecimento']);
      $nm_sub_area_conhecimento = $util->limparVariavel($_POST['nm_sub_area_conhecimento']);
      $ds_sub_area_conhecimento = $util->limparVariavel($_POST['ds_sub_area_conhecimento']);
      $eh_liberado = addslashes($_POST['eh_liberado']);
      if ($eh_liberado == '0') {
        $cd_sub_area_conhecimento_destino = addslashes($_POST['cd_sub_area_conhecimento_destino']);
      } else {
        $cd_sub_area_conhecimento_destino = '0'; 
      }
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $cd_area_conhecimento = addslashes($_POST['cd_area_conhecimento']);

      if ($this->alteraSubAreaConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo)) {
        if ($eh_liberado == '0') {
          require_once 'conteudos/sub_areas_conhecimento_general.php';          $sacg = new SubAreaConhecimentoGeneral();
          if ($sacg->transfereSubAreaConhecimento($cd_sub_area_conhecimento, $cd_sub_area_conhecimento_destino)) {
            echo "<p class=\"fontConteudoSucesso\">Relacionamentos da Sub Área do Conhecimento, transferidos para a Sub Área do Conhecimento de Destino transferidos com sucesso!</p>\n";
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao trasferir Relacionamentos da Sub Área do Conhecimento, ou nenhum relacionamento havia sido estabelecido!</p>\n";
          }
        }
        echo "<p class=\"fontConteudoSucesso\">Liberação/Bloqueio de Sub Área do Conhecimento realizada com sucesso!</p>\n";   
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas na Liberação/Bloqueio da Sub Área do Conhecimento, ou nenhuma informação alterada!</p>\n";
      }
    } 
                                                                       
    public function retornaSeletorOutrasSubAreasConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectSubAreasConhecimento('1', '1', $cd_area_conhecimento);
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione uma Sub Área do Conhecimento';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        if ($cd_sub_area_conhecimento != $it['cd_sub_area_conhecimento']) { 
          $opcao= array();      $opcao[] = $it['cd_sub_area_conhecimento'];          $opcao[]= $it['nm_sub_area_conhecimento'];                   $opcoes[]= $opcao;
        }
      }
      $util->linhaSeletor('Sub Áreas do Conhecimento: ', 'cd_sub_area_conhecimento_destino', $cd_sub_area_conhecimento, $opcoes);      
    }
    
    
    
    
    public function retornaCadastroSubAreasConhecimentoObjetoAprendizagem($cd_area_conhecimento, $tamanho, $cd_general) {
      require_once 'conteudos/sub_areas_conhecimento_general.php';              $sacg = new SubAreaConhecimentoGeneral();
      
      if ($cd_general > 0) {
        $itens = $this->selectSubAreasConhecimento('1', '2', $cd_area_conhecimento);
      } else {
        $itens = $this->selectSubAreasConhecimento('1', '1', $cd_area_conhecimento);
      }
      if ($cd_general > 0) {
        $areas = $sacg->selectSubAreasConhecimentoGeneral('', $cd_general, '1');
      } else {
        $areas = array();
      }
      $css = 'fontConteudoCampoSeletorHint';

      $retorno = "          <select name=\"cd_sub_area_conhecimento[]\" id=\"cd_sub_area_conhecimento\" alt=\"Sub Área do Conhecimento\" title=\"Sub Área do Conhecimento\" class=\"".$css."\" size=\"5\" multiple placeholder=\"Sub Área de Conhecimento\" tabindex=\"1\" style=\"height: 120px; width:".$tamanho."px;\" >\n";
      foreach ($itens as $it) {
        $retorno.= "  			    <option ";
        foreach ($areas as $a) {
          if ($it['cd_sub_area_conhecimento'] == $a['cd_sub_area_conhecimento']) {          $retorno.= " selected ";        }
        }
        $retorno.= " value=\"".$it['cd_sub_area_conhecimento']."\">".$it['nm_sub_area_conhecimento']."</option>\n";
      }
      $retorno.= "          </select>\n";
      $retorno.= "        <a href=\"#\" class=\"dcontexto\">\n";
      $retorno.= "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
      $retorno.= "          <span class=\"fontdDetalhar\">\n";
      $retorno.= "            Para selecionar mais de um item, mantenha a tecla Ctrl pressionada e selecione os itens com um clic do mouse!<br />\n";
      $retorno.= "            Se a Sub Área de Conhecimento desejada não estiver cadastrada, informe no campo abaixo!<br />\n";
      $retorno.= "            Campo do Tipo Seletor\n";
      $retorno.= "          </span>\n";
      $retorno.= "        </a>\n";      

      $retorno.= "          <input type=\"text\" maxlength=\"150\" name=\"ds_sub_area_conhecimento\" id=\"ds_sub_area_conhecimento\" value=\"\" style=\"width:840px;\" alt=\"Sub Área de Conhecimento - descritivo\" title=\"Sub Área de Conhecimento - descritivo\" class=\"".$css."\" placeholder=\"Sub Área de Conhecimento - descritivo\" tabindex=\"1\"/>\n";
      $retorno.= "        <a href=\"#\" class=\"dcontexto\">\n";
      $retorno.= "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
      $retorno.= "          <span class=\"fontdDetalhar\">\n";
      $retorno.= "            Apenas utilizar este campo se a Sub Área de Conhecimento desejada não estiver cadastrada.<br />\n";
      $retorno.= "            Campo do Tipo Texto com capacidade para até 150 caracteres\n";
      $retorno.= "          </span>\n";
      $retorno.= "        </a>\n";
    
      return $retorno;        
    }
    
    public function estaCadastrado($nm_sub_area_conhecimento, $cd_area_conhecimento) {
      $dados1 = $this->selectDadosSubAreaConhecimentoNome($nm_sub_area_conhecimento, $cd_area_conhecimento);
      if ($dados1['cd_sub_area_conhecimento'] != '') {
        if ($dados1['eh_liberado'] == '1') {
          return $dados1['cd_sub_area_conhecimento'];
        } else {
          if ($dados1['cd_sub_area_conhecimento_destino'] > 0) {
            $nome_original = $dados1['nm_sub_area_conhecimento'];
            $dados2 = $this->selectDadosSubAreaConhecimento($dados1['cd_sub_area_conhecimento_destino']);
            $nome_destino = $dados2['nm_sub_area_conhecimento'];
            if ($dados2['eh_ativo'] == '1') {
              echo "<p class=\"fontConteudoAlerta\">A Sub Área de Conhecimento '".$dados1['nm_sub_area_conhecimento']."', informada para cadastro, tem suas associações realizacas com a Sub Área de Conhecimento '".$dados2['nm_sub_area_conhecimento']."'</p>\n";
              return $dados2['cd_sub_area_conhecimento'];
            } else {
              echo "<p class=\"fontConteudoAlerta\">A Sub Área de Conhecimento '".$dados1['nm_sub_area_conhecimento']."', informada para cadastro ainda não foi avaliada. Momentaneamente não aparecerá nas pesquisas. Aguarde avaliação do Administrador!</p>\n";
              return $dados1['cd_sub_area_conhecimento'];
            }          
          } else {
            return $dados1['cd_sub_area_conhecimento'];
          }
        }
      } else {
        $ds_sub_area_conhecimento = '';
        $eh_liberado = '0';
        $cd_sub_area_conhecimento_destino = '';
        $eh_ativo = '1';        
        $cd_sub_area_conhecimento = $this->insereSubAreaConhecimento($cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo);
       
        if ($cd_sub_area_conhecimento > 0) {
          echo "<p class=\"fontConteudoSucesso\">A Sub Área de Conhecimento '".$nm_sub_area_conhecimento."' foi cadastrada. Momentaneamente não aparecerá nas pesquisas. Aguarde avaliação do Administrador!</p>\n";
          return $cd_sub_area_conhecimento;
        } else {
          return '';
        }
      }    
    }      
    

//**************BANCO DE DADOS**************************************************    
    public function selectSubAreasConhecimento($eh_ativo, $eh_liberado, $cd_area_conhecimento) {
      $sql  = "SELECT sac.*, ac.nm_area_conhecimento ".
              "FROM life_sub_areas_conhecimento sac, life_areas_conhecimento ac ".
              "WHERE sac.cd_area_conhecimento = ac.cd_area_conhecimento ";
      if ($cd_area_conhecimento != '') {
        $sql.= "AND sac.cd_area_conhecimento = '$cd_area_conhecimento' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND sac.eh_ativo = '$eh_ativo' ";
      }
      if ($eh_liberado != 2) {
        $sql.= "AND sac.eh_liberado = '$eh_liberado' ";
      }
      $sql.= "ORDER BY sac.nm_sub_area_conhecimento ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
        
    public function selectDadosSubAreaConhecimento($cd_sub_area_conhecimento) {
      $sql  = "SELECT sac.*, ac.nm_area_conhecimento ".
              "FROM life_sub_areas_conhecimento sac, life_areas_conhecimento ac ".
              "WHERE sac.cd_area_conhecimento = ac.cd_area_conhecimento ".
              "AND sac.cd_sub_area_conhecimento = '$cd_sub_area_conhecimento' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function selectDadosSubAreaConhecimentoNome($nm_sub_area_conhecimento, $cd_area_conhecimento) {
      $sql  = "SELECT * ".
              "FROM life_sub_areas_conhecimento ".
              "WHERE nm_sub_area_conhecimento = '$nm_sub_area_conhecimento' ".
              "AND cd_area_conhecimento = '$cd_area_conhecimento' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereSubAreaConhecimento($cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_sub_areas_conhecimento ".
             "(cd_area_conhecimento, nm_sub_area_conhecimento, ds_sub_area_conhecimento, eh_liberado, cd_sub_area_conhecimento_destino, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_area_conhecimento\", \"$nm_sub_area_conhecimento\", \"$ds_sub_area_conhecimento\", \"$eh_liberado\", \"$cd_sub_area_conhecimento_destino\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'sub_areas_conhecimento');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_sub_area_conhecimento) codigo ".
                "FROM life_sub_areas_conhecimento ".
                "WHERE nm_sub_area_conhecimento = '$nm_sub_area_conhecimento' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraSubAreaConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $eh_liberado, $cd_sub_area_conhecimento_destino, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_sub_areas_conhecimento SET ".
             "nm_sub_area_conhecimento = \"$nm_sub_area_conhecimento\", ".
             "ds_sub_area_conhecimento = \"$ds_sub_area_conhecimento\", ".
             "eh_liberado = \"$eh_liberado\", ".
             "cd_sub_area_conhecimento_destino = \"$cd_sub_area_conhecimento_destino\", ".
             "cd_area_conhecimento = \"$cd_area_conhecimento\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_sub_area_conhecimento= '$cd_sub_area_conhecimento' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'sub_areas_conhecimento');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>