<?php
  class ObjetoAprendizagemPesquisa {
    
    public function __construct () {
    }

    public function listarOpcoesPesquisaSimples($secao, $subsecao, $item, $ativas, $ordem, $eh_permitir_avancada) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if (isset($_SESSION['life_c_termo']))           {     $termo = $_SESSION['life_c_termo'];                      } else {        $_SESSION['life_c_termo'] = '';               $termo = '';              }
      if (isset($_SESSION['life_c_campos']))          {     $campos = $_SESSION['life_c_campos'];                    } else {        $_SESSION['life_c_campos'] = '';              $campos = array();        }
      if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_pesquisa_oa_simples.js";
      $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=pesq";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."\" onSubmit=\"return valida(this);\">\n";

      echo "    <table class=\"tabConteudo\">\n";
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" colspan=\"2\">\n";
      echo "          <h2>Pesquisa</h2>\n";
      echo "        </td>\n";
      echo "      </tr>\n";

      $this->retornaCamposPesquisaSimples(1, $campos, $termo, $eh_proprietario);

      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"2\">\n";
      echo "  		    <input type=\"submit\" class=\"celConteudoBotao\" value=\"Pesquisar\" alt=\"Pesquisar\" title=\"Pesquisar\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      echo "    </table>\n";      

      echo "  </form>\n";
    }

  
    public function retornaCamposPesquisaSimples($id, $campos, $termo, $eh_proprietario) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"padding-left:1%; width:60%;\">\n";
      echo "          <input type=\"text\" maxlength=\"150\" name=\"termo\" id=\"termo\" value=\"".$termo."\" style=\"width:95%;\" alt=\"Campo para informação de Termo de pesquisa\" title=\"Campo para informação de Termo de pesquisa\" class=\"fontConteudoCampoTextHintFiltro\" placeholder=\"Termo de pesquisa\" tabindex=\"1\"/>\n";
      echo "          <a href=\"#\" class=\"dcontexto\">\n";
      echo "            <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
      echo "            <span class=\"fontdDetalhar\">\n";
      echo "              Além de informar o termo de pesquisa, você pode selecionar os filtros ao lado. Também pode filtrar a pesquisa de forma a retornar apenas os seus Objetos de Aprendizagem, ou todos os Objetos, através do seletor abaixo!\n";
      echo "            </span>\n";
      echo "          </a>\n";
      echo "        </td>\n";

      echo "        <td class=\"celConteudo\" style=\"padding-left:3%; width:40%;\" rowspan=\"2\">\n";
      if ($conf->retornaEhFiltrarLinguagem() == '1') {
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $cd_linguagem = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'language') {
              $cd_linguagem = $campo['cd_campo'];
            }
          }
        }
        $lin->retornaSeletorLinguagemFiltro($cd_linguagem, 'cd_linguagem', '90%', '1', 'Filtrar Idioma');
      }
      if ($conf->retornaEhFiltrarAreasConhecimento() == '1') {
        require_once 'conteudos/areas_conhecimento.php';                        $are_con = new AreaConhecimento();
        $cd_coverage = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'coverage') {
              $cd_coverage = $campo['cd_campo'];
            }
          }
        }
        $are_con->retornaSeletorAreasConhecimentoFiltro($cd_coverage, 'cd_coverage', '90%', '1', 'Filtrar Área do Conhecimento');
      }
      if ($conf->retornaEhFiltrarStatusCicloVida() == '1') {
        require_once 'conteudos/status_ciclo_vida.php';                         $sta = new StatusCicloVida();
        $cd_status_ciclo_vida = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'status') {
              $cd_status_ciclo_vida = $campo['cd_campo'];
            }
          }
        }
        $sta->retornaSeletorStatusCicloVidaFiltro($cd_status_ciclo_vida, 'cd_status_ciclo_vida', '90%', '1', 'Filtrar Status');
      }
      if ($conf->retornaEhFiltrarTipo() == '1') {
        require_once 'conteudos/tipos.php';                                     $tip = new Tipo();
        $cd_tipo = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'type') {
              $cd_tipo = $campo['cd_campo'];
            }
          }
        }
        $tip->retornaSeletorTipoFiltro($cd_tipo, 'cd_tipo', '90%', '1', 'Filtrar Tipos');
      }

      echo "        </td>\n";
      echo "      </tr>\n";

      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"padding-left:1%; width:60%;\">\n";
      $this->retornaCelulaPropriedade($eh_proprietario);
      echo "        </td>\n";
      echo "      </tr>\n";
    }
    
    public function retornaCelulaPropriedade($eh_proprietario) {
      echo "          <input type=\"radio\" name=\"eh_proprietario\" id=\"eh_proprietario\" ";
      if ($eh_proprietario == '1') {          echo " checked=\"checked\" ";        } 
      echo " value=\"1\" class=\"fontConteudoPesquisa\" /> Pesquisar apenas entre os meus Objetos de Aprendizagem\n";
      echo "<br />\n";
      echo "          <input type=\"radio\" name=\"eh_proprietario\" id=\"eh_proprietario\" ";
      if ($eh_proprietario == '0') {          echo " checked=\"checked\" ";        } 
      echo " value=\"0\" class=\"fontConteudoPesquisa\" /> Pesquisar entre todos os Objetos de Aprendizagem do Portal\n";
    }         

    public function retornaCelulaPesquisaSimples($eh_radio, $cabecalho, $campo, $valor_comparativo, $valor, $descricao) {
      if ($cabecalho == 1) {        $principal = 'Principal';      } else {        $principal = '';      }
      if ($eh_radio) {
        echo "          <input type=\"radio\" name=\"".$campo."\" id=\"".$campo."\" ";
        if ($valor_comparativo == $valor) {
          echo " checked=\"checked\" ";
        } 
        echo " value=\"".$valor."\" class=\"fontConteudoPesquisa\" /> \n";
      } else {
      
      }
      echo "        <b>".$descricao."</b>\n";
      echo "        <br />\n";    
    }         

    public function pesquisarSimples($secao, $subsecao, $item, $ativas, $ordem) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $termo = $util->limparVariavel($_POST['termo']);                          $_SESSION['life_c_termo'] = $termo;

      $campos = array();
      if (isset($_POST['cd_linguagem'])) {
        $campo['campo'] = 'language';        $campo['cd_campo'] = addslashes($_POST['cd_linguagem']);                $campos[] = $campo;
      }
      if (isset($_POST['cd_coverage'])) {
        $campo['campo'] = 'coverage';        $campo['cd_campo'] = addslashes($_POST['cd_coverage']);                 $campos[] = $campo;
      }
      if (isset($_POST['cd_status_ciclo_vida'])) {
        $campo['campo'] = 'status';          $campo['cd_campo'] = addslashes($_POST['cd_status_ciclo_vida']);        $campos[] = $campo;
      }
      if (isset($_POST['cd_tipo'])) {
        $campo['campo'] = 'type';            $campo['cd_campo'] = addslashes($_POST['cd_tipo']);                     $campos[] = $campo;
      }
      $_SESSION['life_c_campos'] = $campos;

      $eh_proprietario = addslashes($_POST['eh_proprietario']);                 $_SESSION['life_c_eh_proprietario'] = $eh_proprietario;
    }

    public function retornaDetalhesPesquisa() {
      if (isset($_SESSION['life_c_termo']))           {     $termo = $_SESSION['life_c_termo'];                      } else {        $_SESSION['life_c_termo'] = '';               $termo = '';              }
      if (isset($_SESSION['life_c_campos']))          {     $campos = $_SESSION['life_c_campos'];                    } else {        $_SESSION['life_c_campos'] = '';              $campos = array();        }
      if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }

      $saida = "";

      if ($termo != '') {
        $saida.= "Termo pesquisado: ".$termo."\n";
      }

      if (count($campos) > 1) {
        foreach ($campos as $campo) {
          if (($campo['campo'] == 'language') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/linguagens.php';                            $lin = new Linguagem();
            $cd_linguagem = $campo['cd_campo'];
            $dados = $lin->selectDadosLinguagem($cd_linguagem);
            $saida.= "<br />Idioma: ".$dados['nm_linguagem']."\n";
          } elseif (($campo['campo'] == 'coverage') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/areas_conhecimento.php';                    $are_con = new AreaConhecimento();
            $cd_coverage = $campo['cd_campo'];
            $dados = $are_con->selectDadosAreaConhecimento($cd_coverage);
            $saida.= "<br />Área do Conhecimento: ".$dados['nm_area_conhecimento']."\n";
          } elseif (($campo['campo'] == 'status') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/status_ciclo_vida.php';                     $sta = new StatusCicloVida();
            $cd_status_ciclo_vida = $campo['cd_campo'];
            $dados = $sta->selectDadosStatusCicloVida($cd_status_ciclo_vida);
            $saida.= "<br />Status: ".$dados['nm_status_ciclo_vida']."\n";
          } elseif (($campo['campo'] == 'type') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/tipos.php';                                 $tip = new Tipo();
            $cd_tipo = $campo['cd_campo'];
            $dados = $tip->selectDadosTipo($cd_tipo);
            $saida.= "<br />Tipo: ".$dados['nm_tipo']."\n";
          }
        }
      }

      if ($eh_proprietario) {
        $saida.= "<br />Considerando apenas os meus Objetos de Aprendizagem\n";
      } else {
        $saida.= "<br />Considerando todos os Objetos de Aprendizagem cadastrados no Portal\n";
      }
      
      echo "<h4>".$saida."</h4>\n";
    }

//**************BANCO DE DADOS**************************************************

  }
?>                   