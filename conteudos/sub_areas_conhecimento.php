<?php
  class SubAreaConhecimento {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';               }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = '1';            }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';             }
      if (isset($_GET['cda']))   {      $area = addslashes($_GET['cda']);           } else {      $area = '';               }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas);
        break;

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas;
          $this->montarFormularioCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas;
          $this->montarFormularioEdicao($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas);
        break;        
               
        case "status":
          $this->alterarSituacaoAtivoSubAreaConhecimento($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas);
        break;
        
        case "liberacao":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas;
          $this->montarFormularioLiberacaoBloqueio($link, $codigo);
        break;
        
        case "salvar_liberacao":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarLiberacao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $area, $ativas);
          $this->listarItens($secao, $subsecao, $item, $area, $ativas);
        break;            
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $area, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=1";              if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }             $opcao['descricao']= "Ativas";                                            $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=0";              if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }             $opcao['descricao']= "Inativas";                                          $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";          $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=2";              if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }             $opcao['descricao']= "Ativas/Inativas";                                   $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }

      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      $areas = $are_con->selectAreasConhecimento('1');
      $opcoes_3= array();
      $i = 1;
      foreach ($areas as $a) {
        $opcao= array();
        $opcao['indice']= $i; $i+=1;
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$a['cd_area_conhecimento']."&at=".$ativas;
        if($area == $a['cd_area_conhecimento']) { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }
        $opcao['descricao']= $a['nm_area_conhecimento'];
        $opcoes_3[]= $opcao;
      }
      $opcao= array();
      $opcao['indice']= $i; $i+=1;
      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
      if($area == '') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }
      $opcao['descricao']= 'Todas as Áreas de Conhecimento';
      $opcoes_3[]= $opcao;
      foreach ($opcoes_3 as $op) {        $nome = 'comandos_filtros_3_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova subárea do conhecimento\" title=\"Nova subárea do conhecimento\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "  <select name=\"comandos_filtros_3\" id=\"comandos_filtros_3\" class=\"fontComandosFiltros\" onChange=\"navegar(3);\" alt=\"Filtro de área do conhecimento\" title=\"Filtro de área do conhecimento\">\n";
      foreach ($opcoes_3 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";

      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $area, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Subáreas do conhecimento ";
      $itens = $this->selectSubAreasConhecimento($ativas, $area);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Subárea do conhecimento</td>\n";
      echo "      <td class=\"celConteudo\">Área do conhecimento</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
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
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas."&cd=".$it['cd_sub_area_conhecimento']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cda=".$area."&at=".$ativas."&cd=".$it['cd_sub_area_conhecimento']."&acao=status\">";
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
    
    public function detalharSubAreaConhecimento($cd_sub_area_conhecimento) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosSubAreaConhecimento($cd_sub_area_conhecimento);
      
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
      $cd_sub_area_conhecimento = "";
      $cd_area_conhecimento = "";
      $nm_sub_area_conhecimento = "";
      $ds_sub_area_conhecimento = "";
      $cd_sub_area_conhecimento_destino = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de subáreas do conhecimento</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_sub_area_conhecimento) {
      $dados = $this->selectDadosSubAreaConhecimento($cd_sub_area_conhecimento);

      $cd_area_conhecimento = $dados['cd_area_conhecimento'];
      $nm_sub_area_conhecimento = $dados['nm_sub_area_conhecimento'];
      $ds_sub_area_conhecimento = $dados['ds_sub_area_conhecimento'];
      $cd_sub_area_conhecimento_destino = $dados['cd_sub_area_conhecimento_destino'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de subárea do conhecimento</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_sub_area_conhecimento.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_sub_area_conhecimento', $cd_sub_area_conhecimento);
      $util->campoHidden('cd_sub_area_conhecimento_destino', $cd_sub_area_conhecimento_destino);
      
      $util->linhaUmCampoText(1, 'Subárea do conhecimento ', 'nm_sub_area_conhecimento', '150', '100', $nm_sub_area_conhecimento);
      $util->linhaTexto(0, 'Descrição ', 'ds_sub_area_conhecimento', $ds_sub_area_conhecimento, '5', '100');
      $are_con->retornaSeletorAreasConhecimento($cd_area_conhecimento);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativa ', 'eh_ativo', $eh_ativo, $opcoes, '100');
      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_sub_area_conhecimento'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_sub_area_conhecimento = addslashes($_POST['cd_sub_area_conhecimento']);
      $nm_sub_area_conhecimento = $util->limparVariavel($_POST['nm_sub_area_conhecimento']);
      $ds_sub_area_conhecimento = $util->limparVariavel($_POST['ds_sub_area_conhecimento']);
      $cd_sub_area_conhecimento_destino = addslashes($_POST['cd_sub_area_conhecimento_destino']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $cd_area_conhecimento = addslashes($_POST['cd_area_conhecimento']);

      if ($cd_sub_area_conhecimento > 0) {
        if ($this->alteraSubAreaConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Subárea do conhecimento editada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da subárea do conhecimento, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereSubAreaConhecimento($cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Subárea do conhecimento cadastrada com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da subárea do conhecimento!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoSubAreaConhecimento($cd_sub_area_conhecimento) {
      $dados = $this->selectDadosSubAreaConhecimento($cd_sub_area_conhecimento);

      $nm_sub_area_conhecimento = $dados['nm_sub_area_conhecimento'];
      $ds_sub_area_conhecimento = $dados['ds_sub_area_conhecimento'];
      $cd_sub_area_conhecimento_destino = $dados['cd_sub_area_conhecimento_destino'];
      $eh_ativo = $dados['eh_ativo'];
      $cd_area_conhecimento = $dados['cd_area_conhecimento'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraSubAreaConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da subárea do conhecimento alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da subárea do conhecimento!</p>\n";
      }
    }


    public function retornaSeletorOutrasSubAreasConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectSubAreasConhecimento('1', $cd_area_conhecimento);
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione uma subárea do conhecimento';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        if ($cd_sub_area_conhecimento != $it['cd_sub_area_conhecimento']) { 
          $opcao= array();      $opcao[] = $it['cd_sub_area_conhecimento'];          $opcao[]= $it['nm_sub_area_conhecimento'];                   $opcoes[]= $opcao;
        }
      }
      $util->linhaSeletor('Subáreas do Conhecimento ', 'cd_sub_area_conhecimento_destino', $cd_sub_area_conhecimento, $opcoes, 100);
    }
    
    
    
    
    public function retornaCadastroSubAreasConhecimentoObjetoAprendizagem($cd_area_conhecimento, $tamanho, $cd_general) {
      require_once 'conteudos/sub_areas_conhecimento_general.php';              $sacg = new SubAreaConhecimentoGeneral();
      
      $itens = $this->selectSubAreasConhecimento('1', $cd_area_conhecimento);
      if ($cd_general > 0) {
        $areas = $sacg->selectSubAreasConhecimentoGeneral('', $cd_general, '1');
      } else {
        $areas = array();
      }
      $css = 'fontConteudoCampoSeletorHint';

      $ajuda = "\n\nPara selecionar mais de um item, mantenha a tecla Ctrl pressionada e selecione os itens com um clic do mouse!\n".
               "Se a subárea de conhecimento desejada não estiver cadastrada, informe no campo abaixo!\n".
               "Campo do Tipo Seletor\n";

      $retorno = "          <select name=\"cd_sub_area_conhecimento[]\" id=\"cd_sub_area_conhecimento\" alt=\"Subárea do conhecimento".$ajuda."\" title=\"Subárea do conhecimento".$ajuda."\" class=\"".$css."\" size=\"5\" multiple placeholder=\"subárea de Conhecimento\" tabindex=\"1\" style=\"height: 120px; width:".$tamanho."%;\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">\n";
      foreach ($itens as $it) {
        $retorno.= "  			    <option ";
        foreach ($areas as $a) {
          if ($it['cd_sub_area_conhecimento'] == $a['cd_sub_area_conhecimento']) {          $retorno.= " selected ";        }
        }
        $retorno.= " value=\"".$it['cd_sub_area_conhecimento']."\">".$it['nm_sub_area_conhecimento']."</option>\n";
      }
      $retorno.= "          </select>\n";
      $css = 'fontConteudoCampoTextHint';

      $ajuda = "\n\nApenas utilizar este campo se a subárea de conhecimento desejada não estiver cadastrada.\n".
               "Campo do Tipo Texto com capacidade para até 150 caracteres";

      $retorno.= "          <input type=\"text\" maxlength=\"150\" name=\"ds_sub_area_conhecimento\" id=\"ds_sub_area_conhecimento\" value=\"\" style=\"width:".$tamanho."%;\" alt=\"subárea de Conhecimento - descritivo".$ajuda."\" title=\"subárea de Conhecimento - descritivo".$ajuda."\" class=\"".$css."\" placeholder=\"subárea de Conhecimento - descritivo\" tabindex=\"1\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\"/>\n";
      return $retorno;
    }

    public function retornaCadastroSubAreasConhecimentoObjetoAprendizagemMultiplos($cd_area_conhecimento, $tamanho, $cd_general) {
      require_once 'conteudos/sub_areas_conhecimento_general.php';              $sacg = new SubAreaConhecimentoGeneral();

      $itens = $this->selectSubAreasConhecimento('1', $cd_area_conhecimento);
      if ($cd_general > 0) {
        $areas = $sacg->selectSubAreasConhecimentoGeneral('', $cd_general, '1');
      } else {
        $areas = array();
      }
      $css = 'fontConteudoCampoSeletorHint';

      $ajuda = "Qual(is) a(s) subárea(s) de conhecimento do OA?\n\nPara selecionar mais de uma subárea, mantenha a tecla CTRL pressionada e selecione as subáreas desejadas clicando sobre as mesmas com o botão esquerdo do mouse. Caso a subárea do conhecimento desejada não esteja relacionada, informe-a no campo abaixo.\n";

      $retorno = "          <font style=\"line-height:25px; vertical-align:baseline;\">Subáreas do conhecimento</line>";
      $retorno.= "          <select name=\"cd_sub_area_conhecimento_".$cd_area_conhecimento."[]\" id=\"cd_sub_area_conhecimento_".$cd_area_conhecimento."\" alt=\"".$ajuda."\" title=\"".$ajuda."\" class=\"".$css."\" size=\"3\" multiple placeholder=\"subárea de Conhecimento\" tabindex=\"1\" style=\"height: 50px; width:".$tamanho."%;\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">\n";
      foreach ($itens as $it) {
        $retorno.= "  			    <option ";
        foreach ($areas as $a) {
          if ($it['cd_sub_area_conhecimento'] == $a['cd_sub_area_conhecimento']) {          $retorno.= " selected ";        }
        }
        $retorno.= " value=\"".$it['cd_sub_area_conhecimento']."\">".$it['nm_sub_area_conhecimento']."</option>\n";
      }
      $retorno.= "          </select>\n";

      $css = 'fontConteudoCampoTextHint';

      $ajuda = "Qual(is) a(s) subárea(s) de conhecimento do OA?\n\nInformar a subárea do conhecimento desejada se a mesma não estiver relacionada acima. Para mais de uma subárea do conhecimento, cada uma deve ser informada separadamente.\n";

      $retorno.= "          <input type=\"text\" maxlength=\"150\" name=\"ds_sub_area_conhecimento_".$cd_area_conhecimento."\" id=\"ds_sub_area_conhecimento_".$cd_area_conhecimento."\" value=\"\" style=\"width:".$tamanho."%;\" alt=\"".$ajuda."\" title=\"".$ajuda."\" class=\"".$css."\" placeholder=\"Utilizar este campo apenas se a subárea do conhecimento desejada não estiver relacionada acima.\" tabindex=\"1\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\"/>\n";
      return $retorno;
    }
    
    public function estaCadastrado($nm_sub_area_conhecimento, $cd_area_conhecimento) {
      $dados1 = $this->selectDadosSubAreaConhecimentoNome($nm_sub_area_conhecimento, $cd_area_conhecimento);
      if ($dados1['cd_sub_area_conhecimento'] != '') {
        if ($dados1['eh_ativo'] == '1') {
          return $dados1['cd_sub_area_conhecimento'];
        } else {
          echo "<p class=\"fontConteudoAlerta\">A subárea de conhecimento '".$nm_sub_area_conhecimento."', informada para cadastro já consta em nossa base de dados, porém não é aceita por nossos administradores! Seus dados serão salvos sem esta informação!</p>\n";
          return '';
        }
      } else {
        $ds_sub_area_conhecimento = '';
        $cd_sub_area_conhecimento_destino = '';
        $eh_ativo = '1';        
        $cd_sub_area_conhecimento = $this->insereSubAreaConhecimento($cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo);
       
        if ($cd_sub_area_conhecimento > 0) {
          echo "<p class=\"fontConteudoSucesso\">A subárea de conhecimento '".$nm_sub_area_conhecimento."' foi cadastrada!</p>\n";
          return $cd_sub_area_conhecimento;
        } else {
          return '';
        }
      }    
    }      

//**************BANCO DE DADOS**************************************************    
    public function selectSubAreasConhecimento($eh_ativo, $cd_area_conhecimento) {
      $sql  = "SELECT sac.*, ac.nm_area_conhecimento ".
              "FROM life_sub_areas_conhecimento sac, life_areas_conhecimento ac ".
              "WHERE sac.cd_area_conhecimento = ac.cd_area_conhecimento ";
      if ($cd_area_conhecimento != '') {
        $sql.= "AND sac.cd_area_conhecimento = '$cd_area_conhecimento' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND sac.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY sac.nm_sub_area_conhecimento ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUBÁREAS CONHECIMENTO!");
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
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUBÁREAS CONHECIMENTO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function selectDadosSubAreaConhecimentoNome($nm_sub_area_conhecimento, $cd_area_conhecimento) {
      $sql  = "SELECT * ".
              "FROM life_sub_areas_conhecimento ".
              "WHERE nm_sub_area_conhecimento = '$nm_sub_area_conhecimento' ".
              "AND cd_area_conhecimento = '$cd_area_conhecimento' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUBÁREAS CONHECIMENTO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereSubAreaConhecimento($cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_sub_areas_conhecimento ".
             "(cd_area_conhecimento, nm_sub_area_conhecimento, ds_sub_area_conhecimento, cd_sub_area_conhecimento_destino, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_area_conhecimento\", \"$nm_sub_area_conhecimento\", \"$ds_sub_area_conhecimento\", \"$cd_sub_area_conhecimento_destino\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'sub_areas_conhecimento');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SUBÁREAS CONHECIMENTO!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_sub_area_conhecimento) codigo ".
                "FROM life_sub_areas_conhecimento ".
                "WHERE nm_sub_area_conhecimento = '$nm_sub_area_conhecimento' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUBÁREAS CONHECIMENTO!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraSubAreaConhecimento($cd_sub_area_conhecimento, $cd_area_conhecimento, $nm_sub_area_conhecimento, $ds_sub_area_conhecimento, $cd_sub_area_conhecimento_destino, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_sub_areas_conhecimento SET ".
             "nm_sub_area_conhecimento = \"$nm_sub_area_conhecimento\", ".
             "ds_sub_area_conhecimento = \"$ds_sub_area_conhecimento\", ".
             "cd_sub_area_conhecimento_destino = \"$cd_sub_area_conhecimento_destino\", ".
             "cd_area_conhecimento = \"$cd_area_conhecimento\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_sub_area_conhecimento= '$cd_sub_area_conhecimento' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'sub_areas_conhecimento');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SUBÁREAS CONHECIMENTO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>