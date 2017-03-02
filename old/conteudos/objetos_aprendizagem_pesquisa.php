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

      $this->retornaCamposPesquisaSimples($campos, $termo, $eh_proprietario);

      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"2\">\n";
      echo "  		    <input type=\"submit\" class=\"celConteudoBotao\" value=\"Pesquisar\" alt=\"Pesquisar\" title=\"Pesquisar\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      echo "    </table>\n";      

      echo "  </form>\n";
    }

    public function listarOpcoesPesquisaCapa() {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if (isset($_SESSION['life_c_termo']))           {     $termo = $_SESSION['life_c_termo'];                      } else {        $_SESSION['life_c_termo'] = '';               $termo = '';              }
      if (isset($_SESSION['life_c_campos']))          {     $campos = $_SESSION['life_c_campos'];                    } else {        $_SESSION['life_c_campos'] = '';              $campos = array();        }
      if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }

      //include "js/js_pesquisa_oa_simples.js";
      echo "  <p style=\"width:97%; text-align:right;\"><img src=\"".$_SESSION['life_link_completo']."icones/fechar_formulario.png\" alt=\"Fechar formul�rio de pesquisa\" title=\"Fechar formul�rio de pesquisa\" border=\"0\" onMouseOver=\"this.style.cursor='pointer';\" onClick=\"ocultarPesquisa();\"></p>\n";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo']."pesquisar\">\n";// onSubmit=\"return validaPesquisaOASimples(this);\">\n";

      echo "  <div class=\"divPesquisaTermo\">\n";
      echo "    <h1>Pesquisa</h1>\n";
      echo "    <input type=\"text\" maxlength=\"150\" name=\"termo\" id=\"termo\" value=\"".$termo."\" style=\"width:95%;\" alt=\"Informe o termo de pesquisa e/ou use os campos de pesquisa ao lado\" title=\"Informe o termo de pesquisa e/ou use os campos de pesquisa ao lado\" class=\"fontConteudoCampoTextHintFiltroCapa\" placeholder=\"Informe o termo de pesquisa e/ou use os campos de pesquisa ao lado\" tabindex=\"1\"/>\n";
/*
      echo "    <a href=\"#\" class=\"dcontexto\">\n";
      echo "      <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
      echo "      <span class=\"fontdDetalhar\">\n";
      echo "        Informe o termo de pesquisa e/ou use os campos de pesquisa ao lado.\n";
      echo "      </span>\n";
      echo "    </a>\n";
*/
      echo "    <p class=\"fontConteudoCentralizado\">\n";
      echo "  		<input type=\"image\" src=\"".$_SESSION['life_link_completo']."icones/pesquisar_capa.png\" alt=\"Pesquisar\" title=\"Pesquisar\">\n";
      echo "    </p>\n";
      echo "  </div>\n";
      echo "  <div class=\"divPesquisaFiltros\">\n";
      if ($conf->retornaEhFiltrarNiveisEducacionais() == '1'){
        require_once 'conteudos/niveis_educacionais.php';                       $niv_edu = new NivelEducacional();
        $cd_nivel_educacional = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'nivel_educacional') {
              $cd_nivel_educacional = $campo['cd_campo'];
            }
          }
        }
        $niv_edu->retornaSeletorNiveisEducacionaisFiltro($cd_nivel_educacional, 'cd_nivel_educacional', '90%', '0', 'Pesquisar por n�vel educacional', 'Selecione o n�vel educacional desejado para pesquisar por Objetos de Aprendizagem');
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
        $are_con->retornaSeletorAreasConhecimentoFiltro($cd_coverage, 'cd_coverage', '90%', '0', 'Pesquisar por �rea do conhecimento', 'Selecione a �rea de conhecimento desejada para pesquisar por Objetos de Aprendizagem');
      }
      if ($conf->retornaEhFiltrarFormato() == '1') {
        require_once 'conteudos/formatos.php';                                  $for = new Formato();
        $cd_formato = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'formato') {
              $cd_formato = $campo['cd_campo'];
            }
          }
        }
        $for->retornaSeletorFormatoFiltro($cd_formato, 'cd_formato', '90%', '0', 'Pesquisar por formato', 'Selecione o formato desejado para pesquisar por Objetos de Aprendizagem');
      }
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
        $lin->retornaSeletorLinguagemFiltro($cd_linguagem, 'cd_linguagem', '90%', '0', 'Pesquisar por idioma', 'Selecione o idioma desejado para pesquisar por Objetos de Aprendizagem');
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
        $sta->retornaSeletorStatusCicloVidaFiltro($cd_status_ciclo_vida, 'cd_status_ciclo_vida', '90%', '0', 'Pesquisar por status', 'Selecione o status desejado para pesquisar por Objetos de Aprendizagem');
      }
      echo "  </div>\n";
      echo "  </form>\n";
    }

  
    public function retornaCamposPesquisaSimples($campos, $termo, $eh_proprietario) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"padding-left:1%; width:60%;\" rowspan=\"2\">\n";
      echo "          <input type=\"text\" maxlength=\"150\" name=\"termo\" id=\"termo\" value=\"".$termo."\" style=\"width:95%;\" alt=\"Campo para informa��o de Termo de pesquisa\" title=\"Campo para informa��o de Termo de pesquisa\" class=\"fontConteudoCampoTextHintFiltro\" placeholder=\"Informe o termo de pesquisa e/ou use os campos de pesquisa ao lado\" tabindex=\"1\"/>\n";
/*      echo "          <a href=\"#\" class=\"dcontexto\">\n";
      echo "            <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
      echo "            <span class=\"fontdDetalhar\">\n";
      echo "              Informe o termo de pesquisa e/ou use os campos de pesquisa ao lado. Voc� tamb�m pode filtrar a pesquisa de forma a retornar apenas os seus Objetos de Aprendizagem, ou de todos os autores, atrav�s do seletor abaixo!\n";
      echo "            </span>\n";
      echo "          </a>\n";     */
      echo "        </td>\n";

      echo "        <td class=\"celConteudo\" style=\"padding-left:3%; width:40%;\" rowspan=\"2\">\n";
      if ($conf->retornaEhFiltrarNiveisEducacionais() == '1'){
        require_once 'conteudos/niveis_educacionais.php';                       $niv_edu = new NivelEducacional();
        $cd_nivel_educacional = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'nivel_educacional') {
              $cd_nivel_educacional = $campo['cd_campo'];
            }
          }
        }
        $niv_edu->retornaSeletorNiveisEducacionaisFiltro($cd_nivel_educacional, 'cd_nivel_educacional', '90%', '0', 'Pesquisar por n�vel educacional', 'Selecione o n�vel educacional desejado para pesquisar por Objetos de Aprendizagem');
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
        $are_con->retornaSeletorAreasConhecimentoFiltro($cd_coverage, 'cd_coverage', '90%', '0', 'Pesquisar por �rea do conhecimento', 'Selecione a �rea de conhecimento desejada para pesquisar por Objetos de Aprendizagem');
      }
      if ($conf->retornaEhFiltrarFormato() == '1') {
        require_once 'conteudos/formatos.php';                                  $for = new Formato();
        $cd_formato = '';
        if (count($campos) > 1) {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'formato') {
              $cd_formato = $campo['cd_campo'];
            }
          }
        }
        $for->retornaSeletorFormatoFiltro($cd_formato, 'cd_formato', '90%', '0', 'Pesquisar por formato', 'Selecione o formato desejado para pesquisar por Objetos de Aprendizagem');
      }
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
        $lin->retornaSeletorLinguagemFiltro($cd_linguagem, 'cd_linguagem', '90%', '0', 'Pesquisar por idioma', 'Selecione o idioma desejado para pesquisar por Objetos de Aprendizagem');
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
        $sta->retornaSeletorStatusCicloVidaFiltro($cd_status_ciclo_vida, 'cd_status_ciclo_vida', '90%', '0', 'Pesquisar por status', 'Selecione o status desejado para pesquisar por Objetos de Aprendizagem');
      }

      echo "        </td>\n";
      echo "      </tr>\n";
/*
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"padding-left:1%; width:60%;\">\n";
      $this->retornaCelulaPropriedade($eh_proprietario);
      echo "        </td>\n";
      echo "      </tr>\n";
*/
    }
/*
    public function retornaCelulaPropriedade($eh_proprietario) {
      if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }

      echo "          <input type=\"radio\" name=\"eh_proprietario\" id=\"eh_proprietario\" ";
      if ($eh_proprietario == '1') {          echo " checked=\"checked\" ";        } 
      echo " value=\"1\" class=\"fontConteudoPesquisa\" /> Pesquisar apenas entre os meus Objetos de Aprendizagem\n";
      echo "<br />\n";
      echo "          <input type=\"radio\" name=\"eh_proprietario\" id=\"eh_proprietario\" ";
      if ($eh_proprietario == '0') {          echo " checked=\"checked\" ";        } 
      echo " value=\"0\" class=\"fontConteudoPesquisa\" /> Pesquisar entre todos os Objetos de Aprendizagem do Portal\n";
    }         
*/
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
        $campo['campo'] = 'language';              $campo['cd_campo'] = addslashes($_POST['cd_linguagem']);                $campos[] = $campo;
      }
      if (isset($_POST['cd_nivel_educacional'])) {
        $campo['campo'] = 'nivel_educacional';     $campo['cd_campo'] = addslashes($_POST['cd_nivel_educacional']);        $campos[] = $campo;
      }
      if (isset($_POST['cd_coverage'])) {
        $campo['campo'] = 'coverage';              $campo['cd_campo'] = addslashes($_POST['cd_coverage']);                 $campos[] = $campo;
      }
      if (isset($_POST['cd_status_ciclo_vida'])) {
        $campo['campo'] = 'status';                $campo['cd_campo'] = addslashes($_POST['cd_status_ciclo_vida']);        $campos[] = $campo;
      }
      if (isset($_POST['cd_formato'])) {
        $campo['campo'] = 'formato';                  $campo['cd_campo'] = addslashes($_POST['cd_formato']);                     $campos[] = $campo;
      }
      $_SESSION['life_c_campos'] = $campos;

      if (isset($_POST['eh_proprietario'])) {
        $eh_proprietario = addslashes($_POST['eh_proprietario']);
      } else {
        $eh_proprietario = '1';
      }
      $_SESSION['life_c_eh_proprietario'] = $eh_proprietario;
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
          if (($campo['campo'] == 'nivel_educacional') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/niveis_educacionais.php';                   $niv_edu = new NivelEducacional();
            $cd_nivel_educacional = $campo['cd_campo'];
            $dados = $niv_edu->selectDadosNivelEducacional($cd_nivel_educacional);
            $saida.= "<br />N�vel Educacional: ".$dados['nm_nivel_educacional']."\n";
          } elseif (($campo['campo'] == 'coverage') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/areas_conhecimento.php';                    $are_con = new AreaConhecimento();
            $cd_coverage = $campo['cd_campo'];
            $dados = $are_con->selectDadosAreaConhecimento($cd_coverage);
            $saida.= "<br />�rea do Conhecimento: ".$dados['nm_area_conhecimento']."\n";
          } elseif (($campo['campo'] == 'formato') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/formatos.php';                              $for = new Formato();
            $cd_formato = $campo['cd_campo'];
            $dados = $for->selectDadosFormato($cd_formato);
            $saida.= "<br />Formato: ".$dados['nm_formato']."\n";
          } elseif (($campo['campo'] == 'language') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/linguagens.php';                            $lin = new Linguagem();
            $cd_linguagem = $campo['cd_campo'];
            $dados = $lin->selectDadosLinguagem($cd_linguagem);
            $saida.= "<br />Idioma: ".$dados['nm_linguagem']."\n";
          } elseif (($campo['campo'] == 'status') && ($campo['cd_campo'] > 0)) {
            require_once 'conteudos/status_ciclo_vida.php';                     $sta = new StatusCicloVida();
            $cd_status_ciclo_vida = $campo['cd_campo'];
            $dados = $sta->selectDadosStatusCicloVida($cd_status_ciclo_vida);
            $saida.= "<br />Status: ".$dados['nm_status_ciclo_vida']."\n";
          }
        }
      }

/*      if ($eh_proprietario) {
        $saida.= "<br />Considerando apenas os meus Objetos de Aprendizagem\n";
      } else {
        $saida.= "<br />Considerando todos os Objetos de Aprendizagem cadastrados no Portal\n";
      }*/
      
      echo "<h4>".$saida."</h4>\n";
    }

//*******************EXIBICAO PUBLICA*******************************************

    public function controleExibicaoPublica($pagina, $lista_paginas) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $_SESSION['life_c_eh_proprietario'] = '0';

      if (isset($_POST['termo'])) {
        $termo = $util->limparVariavel($_POST['termo']);                        $_SESSION['life_c_termo'] = $termo;
        $campos = array();
      } elseif (isset($_POST['nr_itens_pagina'])) {
        $nr_itens_pagina = addslashes($_POST['nr_itens_pagina']);               $_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina'] = $nr_itens_pagina;
        $termo = $_SESSION['life_c_termo'];
        $campos = $_SESSION['life_c_campos'];

        if (isset($_SESSION['life_objetos_aprendizagem_pesquisa_qtd']))           {       unset($_SESSION['life_objetos_aprendizagem_pesquisa_qtd']);             }
        if (isset($_SESSION['life_objetos_aprendizagem_pesquisa_paginas']))       {       unset($_SESSION['life_objetos_aprendizagem_pesquisa_paginas']);         }
        if (isset($_SESSION['life_objetos_aprendizagem_pesquisa_pagina']))        {       unset($_SESSION['life_objetos_aprendizagem_pesquisa_pagina']);          }

      } elseif (isset($_SESSION['life_c_termo'])) {
        $termo = $_SESSION['life_c_termo'];
        $campos = $_SESSION['life_c_campos'];
      } else {
        $termo = "";                                                            $_SESSION['life_c_termo'] = $termo;
      }

      if (isset($_POST['cd_nivel_educacional']))    {        $campo['campo'] = 'nivel_educacional';     $campo['cd_campo'] = addslashes($_POST['cd_nivel_educacional']);        $campos[] = $campo;      }
      if (isset($_POST['cd_coverage']))             {        $campo['campo'] = 'coverage';              $campo['cd_campo'] = addslashes($_POST['cd_coverage']);                 $campos[] = $campo;      }
      if (isset($_POST['cd_formato']))              {        $campo['campo'] = 'formato';               $campo['cd_campo'] = addslashes($_POST['cd_formato']);                  $campos[] = $campo;      }
      if (isset($_POST['cd_linguagem']))            {        $campo['campo'] = 'language';              $campo['cd_campo'] = addslashes($_POST['cd_linguagem']);                $campos[] = $campo;      }
      if (isset($_POST['cd_status_ciclo_vida']))    {        $campo['campo'] = 'status';                $campo['cd_campo'] = addslashes($_POST['cd_status_ciclo_vida']);        $campos[] = $campo;      }

      $acao = '';
      if (isset($lista_paginas[3])) {
        if ((($lista_paginas[1] == 'limpar-pesquisa') && ($lista_paginas[2] == 'campo')) && (($lista_paginas[3] == 'nivel-educacional') || ($lista_paginas[3] == 'area-conhecimento') || ($lista_paginas[3] == 'formato') || ($lista_paginas[3] == 'idioma') || ($lista_paginas[3] == 'status') || ($lista_paginas[3] == 'termo') || ($lista_paginas[3] == 'todos'))) {
          $acao = $lista_paginas[3];
        }
      }

      if ($acao == 'termo') {
        $_SESSION['life_c_termo'] = '';
        $termo = '';
      } elseif ($acao == 'todos') {
        $_SESSION['life_c_termo'] = '';
        $termo = '';
        $campos = array();
      } elseif ($acao != '') {
        $novos_campos = array();
        foreach ($campos as $campo) {
          if ($campo['campo'] == 'nivel-educacional') {          if ($acao != 'nivel_educacional') {     $novos_campos[] = $campo;       }          }
          if ($campo['campo'] == 'coverage')          {          if ($acao != 'area-conhecimento') {     $novos_campos[] = $campo;       }          }
          if ($campo['campo'] == 'formato')           {          if ($acao != 'formato')           {     $novos_campos[] = $campo;       }          }
          if ($campo['campo'] == 'language')          {          if ($acao != 'idioma')            {     $novos_campos[] = $campo;       }          }
          if ($campo['campo'] == 'status')            {          if ($acao != 'status')            {     $novos_campos[] = $campo;       }          }
        }
        $campos = $novos_campos;
      }
      $_SESSION['life_c_campos'] = $campos;
      $eh_proprietario = '2';                                                   $_SESSION['life_c_eh_proprietario'] = $eh_proprietario;


      $_SESSION['life_caminho_retorno'] = 'pesquisar';

      $this->retornaDadosCamposTermosPesquisados($pagina, $lista_paginas);

      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      $oa->retornaConteudoPesquisa($pagina, $lista_paginas);
    }

    public function retornaDadosCamposTermosPesquisados($pagina, $lista_paginas) {
      if (isset($_SESSION['life_c_termo'])) {
        $termo = $_SESSION['life_c_termo'];
        $campos = $_SESSION['life_c_campos'];

        $nr_filtros = 0;
        $link = $_SESSION['life_link_completo']."pesquisar/";

        $saida = "";
        if ($termo != '') {
          $nr_filtros += 1;
          $ajuda1 = "Termo pesquisado (".$termo.")";
          $ajuda2 = "Excluir termo pesquisado (".$termo.") dos filtros aplicados";
          $saida.= "<spam class=\"fontLinkItemPesquisado\" alt=\"".$ajuda1."\" title=\"".$ajuda1."\">".
                   $termo.
                   "</spam>".
                   "<a href=\"".$link."limpar-pesquisa/campo/termo\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\">".
                   "<img src=\"".$_SESSION['life_link_completo']."icones/fechar.png\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\" border=\"0\" onMouseOver=\"this.src='".$_SESSION['life_link_completo']."icones/fechars.png'\" onMouseOut=\"this.src='".$_SESSION['life_link_completo']."icones/fechar.png'\">".
                   "</a> \n";
        }

        if ($campos != '') {
          foreach ($campos as $campo) {
            if ($campo['campo'] == 'nivel_educacional') {
              if ($campo['cd_campo'] > 0) {
                $nr_filtros += 1;
                require_once 'conteudos/niveis_educacionais.php';               $niv_edu = new NivelEducacional();
                $dados = $niv_edu->selectDadosNivelEducacional($campo['cd_campo']);
                $ajuda1 = "N�vel educacional (".$dados['nm_nivel_educacional'].")";
                $ajuda2 = "Excluir n�vel educacional (".$dados['nm_nivel_educacional'].") dos filtros aplicados";
                $saida.= "<spam class=\"fontLinkItemPesquisado\" alt=\"".$ajuda1."\" title=\"".$ajuda1."\">".
                         $dados['nm_nivel_educacional'].
                         "</spam>".
                         "<a href=\"".$link."limpar-pesquisa/campo/nivel-educacional\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\">".
                         "<img src=\"".$_SESSION['life_link_completo']."icones/fechar.png\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\" border=\"0\" onMouseOver=\"this.src='".$_SESSION['life_link_completo']."icones/fechars.png'\" onMouseOut=\"this.src='".$_SESSION['life_link_completo']."icones/fechar.png'\">".
                         "</a> \n";
              }
            }
            if ($campo['campo'] == 'coverage') {
              if ($campo['cd_campo'] > 0) {
                $nr_filtros += 1;
                require_once 'conteudos/areas_conhecimento.php';                $are_con = new AreaConhecimento();
                $dados = $are_con->selectDadosAreaConhecimento($campo['cd_campo']);
                $ajuda1 = "�rea do conhecimento (".$dados['nm_area_conhecimento'].")";
                $ajuda2 = "Excluir �rea do conhecimento (".$dados['nm_area_conhecimento'].") dos filtros aplicados";
                $saida.= "<spam class=\"fontLinkItemPesquisado\" alt=\"".$ajuda1."\" title=\"".$ajuda1."\">".
                         $dados['nm_area_conhecimento'].
                         "</spam>".
                         "<a href=\"".$link."limpar-pesquisa/campo/area-conhecimento\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\">".
                         "<img src=\"".$_SESSION['life_link_completo']."icones/fechar.png\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\" border=\"0\" onMouseOver=\"this.src='".$_SESSION['life_link_completo']."icones/fechars.png'\" onMouseOut=\"this.src='".$_SESSION['life_link_completo']."icones/fechar.png'\">".
                         "</a> \n";
              }
            }
            if ($campo['campo'] == 'formato') {
              if ($campo['cd_campo'] > 0) {
                $nr_filtros += 1;
                require_once 'conteudos/formatos.php';                          $for = new Formato();
                $dados = $for->selectDadosFormato($campo['cd_campo']);
                $ajuda1 = "Formato (".$dados['nm_formato'].")";
                $ajuda2 = "Excluir formato (".$dados['nm_formato'].") dos filtros aplicados";
                $saida.= "<spam class=\"fontLinkItemPesquisado\" alt=\"".$ajuda1."\" title=\"".$ajuda1."\">".
                         $dados['nm_formato'].
                         "</spam>".
                         "<a href=\"".$link."limpar-pesquisa/campo/formato\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\">".
                         "<img src=\"".$_SESSION['life_link_completo']."icones/fechar.png\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\" border=\"0\" onMouseOver=\"this.src='".$_SESSION['life_link_completo']."icones/fechars.png'\" onMouseOut=\"this.src='".$_SESSION['life_link_completo']."icones/fechar.png'\">".
                         "</a> \n";
              }
            }
            if ($campo['campo'] == 'language') {
              if ($campo['cd_campo'] > 0) {
                $nr_filtros += 1;
                require_once 'conteudos/linguagens.php';                        $lin = new Linguagem();
                $dados = $lin->selectDadosLinguagem($campo['cd_campo']);
                $ajuda1 = "Idioma (".$dados['nm_linguagem'].")";
                $ajuda2 = "Excluir idioma (".$dados['nm_linguagem'].") dos filtros aplicados";
                $saida.= "<spam class=\"fontLinkItemPesquisado\" alt=\"".$ajuda1."\" title=\"".$ajuda1."\">".
                         $dados['nm_linguagem'].
                         "</spam>".
                         "<a href=\"".$link."limpar-pesquisa/campo/idioma\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\">".
                         "<img src=\"".$_SESSION['life_link_completo']."icones/fechar.png\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\" border=\"0\" onMouseOver=\"this.src='".$_SESSION['life_link_completo']."icones/fechars.png'\" onMouseOut=\"this.src='".$_SESSION['life_link_completo']."icones/fechar.png'\">".
                         "</a> \n";
              }
            }
            if ($campo['campo'] == 'status') {
              if ($campo['cd_campo'] > 0) {
                $nr_filtros += 1;
                require_once 'conteudos/status_ciclo_vida.php';                 $sta = new StatusCicloVida();
                $dados = $sta->selectDadosStatusCicloVida($campo['cd_campo']);
                $ajuda1 = "Status (".$dados['nm_status_ciclo_vida'].")";
                $ajuda2 = "Excluir status (".$dados['nm_status_ciclo_vida'].") dos filtros aplicados";
                $saida.= "<spam class=\"fontLinkItemPesquisado\" alt=\"".$ajuda1."\" title=\"".$ajuda1."\">".
                         $dados['nm_status_ciclo_vida'].
                         "</spam>".
                         "<a href=\"".$link."limpar-pesquisa/campo/status\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\">".
                         "<img src=\"".$_SESSION['life_link_completo']."icones/fechar.png\" alt=\"".$ajuda2."\" title=\"".$ajuda2."\" border=\"0\" onMouseOver=\"this.src='".$_SESSION['life_link_completo']."icones/fechars.png'\" onMouseOut=\"this.src='".$_SESSION['life_link_completo']."icones/fechar.png'\">".
                         "</a> \n";
              }
            }
          }
        }

        if ($nr_filtros > 1) {
          $ajuda = "Excluir filtros aplicados";
          $chamada = "<spam class=\"fontLinkItemPesquisado\">Filtros aplicados </spam>".
                     "<a href=\"".$link."limpar-pesquisa/campo/todos\" alt=\"".$ajuda."\" title=\"".$ajuda."\">".
                     "<img src=\"".$_SESSION['life_link_completo']."icones/fechar.png\" alt=\"".$ajuda."\" title=\"".$ajuda."\" border=\"0\" onMouseOver=\"this.src='".$_SESSION['life_link_completo']."icones/fechars.png'\" onMouseOut=\"this.src='".$_SESSION['life_link_completo']."icones/fechar.png'\">".
                     "</a> \n";
        } else {
          $chamada = "<spam class=\"fontLinkItemPesquisado\">Filtro aplicado </spam>\n";
        }


        echo "<div class=\"divLinhaTermosPesquisa\">\n";
        if ($saida != '') {
          echo "  ".$chamada.$saida."\n";
        }
        echo "  <form method=\"POST\" name=\"pesquisa\" action=\"".$_SESSION['life_link_completo']."pesquisar/itens\">\n";
        echo "    <p class=\"fontLinkItemPesquisado\" style=\"text-align:right;\">\n";
        echo "      Itens por p�gina: \n";
        echo "      <select name=\"nr_itens_pagina\" id=\"nr_itens_pagina\" alt=\"Selecione o n�mero de itens por p�gina\" title=\"Selecione o n�mero de itens por p�gina\" class=\"fontLinkItemPesquisado\" tabindex=\"1\" onChange=\"document.forms['pesquisa'].submit();\">\n";
        for ($i=5;$i<=25;$i+=5) {
          if ($i == $_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina']) {
            echo "  		  <option selected value=\"".$i."\">".$i."</option>\n";
          } else {
            echo "  		  <option value=\"".$i."\">".$i."</option>\n";
          }
        }
        echo "      </select>\n";
        echo "    </p>";
        echo "  </form>\n";
        echo "</div>\n";
      }
    }

//**************BANCO DE DADOS**************************************************

  }
?>