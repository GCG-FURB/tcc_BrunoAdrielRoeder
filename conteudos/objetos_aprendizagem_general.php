<?php
  class ObjetoAprendizagemGeneral {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_general > 0) {
        $this->montarFormularioEdicao($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/fotos.php';                                       $foto = new Fotos();

      $eh_informar_identifier = '1';
      $eh_informar_title = $conf->retornaInformarGeneralTitle($tipo);
      $eh_informar_language = $conf->retornaInformarGeneralLanguage($tipo);
      $eh_informar_description = $conf->retornaInformarGeneralDescription($tipo);
      $eh_informar_keyword = $conf->retornaInformarGeneralKeyword($tipo);
      $eh_informar_nivel_educacional = '1';
      $eh_informar_coverage = '1';
      $eh_informar_structure = $conf->retornaInformarGeneralStructure($tipo);
      $eh_informar_agregation_level = $conf->retornaInformarGeneralAgregationLevel($tipo);
      $eh_informar_arquivo_imagem = $conf->retornaInformarGeneralImagem($tipo);

      $cd_general = "";
      $ds_identifier = "";
      $ds_title = "";
      $cd_language = "";
      $ds_description = "";
      $ds_keyword = "";
      $ds_structure = "";
      $ds_agregation_level = "";
      $ds_arquivo_imagem = "";
      $pasta = $foto->selectDadosTiposAssociacoesFotos('OA');
      $ds_pasta_arquivo_imagem = $pasta['ds_pasta_tipo_associacao_foto'];


      $this->imprimeFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $eh_informar_nivel_educacional, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level, $ds_arquivo_imagem, $ds_pasta_arquivo_imagem, $eh_informar_arquivo_imagem);
    }
    
    private function montarFormularioEdicao($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemGeneral($cd_general);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_identifier = '1';
        $eh_informar_title = $dados['eh_informar_title'];
        $eh_informar_language = $dados['eh_informar_language'];
        $eh_informar_description = $dados['eh_informar_description'];
        $eh_informar_keyword = $dados['eh_informar_keyword'];
        $eh_informar_nivel_educacional = '1';
        $eh_informar_coverage = '1';
        $eh_informar_structure = $dados['eh_informar_structure'];
        $eh_informar_agregation_level = $dados['eh_informar_agregation_level'];
        $eh_informar_arquivo_imagem = $dados['eh_informar_arquivo_imagem'];
      } else {
        $eh_informar_identifier = '1';
        $eh_informar_title = $conf->retornaInformarGeneralTitle($tipo);
        $eh_informar_language = $conf->retornaInformarGeneralLanguage($tipo);
        $eh_informar_description = $conf->retornaInformarGeneralDescription($tipo);
        $eh_informar_keyword = $conf->retornaInformarGeneralKeyword($tipo);
        $eh_informar_nivel_educacional = '1';
        $eh_informar_coverage = '1';
        $eh_informar_structure = $conf->retornaInformarGeneralStructure($tipo);
        $eh_informar_agregation_level = $conf->retornaInformarGeneralAgregationLevel($tipo);
        $eh_informar_arquivo_imagem = $conf->retornaInformarGeneralImagem($tipo);
      }
      
      $ds_identifier = $dados['ds_identifier'];
      $ds_title = $dados['ds_title'];
      $cd_language = $dados['cd_language'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];
      $ds_structure = $dados['ds_structure'];
      $ds_agregation_level = $dados['ds_agregation_level'];
      $ds_arquivo_imagem = $dados['ds_arquivo_imagem'];
      $ds_pasta_arquivo_imagem = $dados['ds_pasta_arquivo_imagem'];
      if ($ds_pasta_arquivo_imagem == '') {
        require_once 'conteudos/fotos.php';                                     $foto = new Fotos();
        $pasta = $foto->selectDadosTiposAssociacoesFotos('OA');
        $ds_pasta_arquivo_imagem = $pasta['ds_pasta_tipo_associacao_foto'];
      }

      $this->imprimeFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $eh_informar_nivel_educacional, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level, $ds_arquivo_imagem, $ds_pasta_arquivo_imagem, $eh_informar_arquivo_imagem);
    }
    
    public function imprimeFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $eh_informar_nivel_educacional, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level, $ds_arquivo_imagem, $ds_pasta_arquivo_imagem, $eh_informar_arquivo_imagem) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/arquivos_extensao.php';                           $arq = new ArquivoExtensao();

      $util->campoHidden('cd_general', $cd_general);
      $util->campoHidden('eh_informar_general', $eh_informar_general);
      $util->campoHidden('eh_informar_general_identifier', $eh_informar_identifier);
      $util->campoHidden('eh_informar_general_title', $eh_informar_title);
      $util->campoHidden('eh_informar_general_language', $eh_informar_language);
      $util->campoHidden('eh_informar_general_description', $eh_informar_description);
      $util->campoHidden('eh_informar_general_keyword', $eh_informar_keyword);
      $util->campoHidden('eh_informar_general_nivel_educacional', $eh_informar_nivel_educacional);
      $util->campoHidden('eh_informar_general_coverage', $eh_informar_coverage);
      $util->campoHidden('eh_informar_general_structure', $eh_informar_structure);
      $util->campoHidden('eh_informar_general_agregation_level', $eh_informar_agregation_level);
      $util->campoHidden('eh_informar_arquivo_imagem', $eh_informar_arquivo_imagem);

      $eh_obrigatorio_identifier = '1';
      $eh_obrigatorio_title = $conf->retornaInformarGeneralTitle('b');
      $eh_obrigatorio_language = $conf->retornaInformarGeneralLanguage('b');
      $eh_obrigatorio_description = $conf->retornaInformarGeneralDescription('b');
      $eh_obrigatorio_keyword = $conf->retornaInformarGeneralKeyword('b');
      $eh_obrigatorio_nivel_educacional = '1';
      $eh_obrigatorio_coverage = '1';
      $eh_obrigatorio_structure = $conf->retornaInformarGeneralStructure('b');
      $eh_obrigatorio_agregation_level = $conf->retornaInformarGeneralAgregationLevel('b');
      $eh_obrigatorio_arquivo_imagem = $conf->retornaInformarGeneralImagem('b');
      $util->campoHidden('eh_obrigatorio_general_identifier', $eh_obrigatorio_identifier);
      $util->campoHidden('eh_obrigatorio_general_title', $eh_obrigatorio_title);
      $util->campoHidden('eh_obrigatorio_general_language', $eh_obrigatorio_language);
      $util->campoHidden('eh_obrigatorio_general_description', $eh_obrigatorio_description);
      $util->campoHidden('eh_obrigatorio_general_keyword', $eh_obrigatorio_keyword);
      $util->CampoHidden('eh_obrigatorio_general_nivel_educacional', $eh_obrigatorio_nivel_educacional);
      $util->campoHidden('eh_obrigatorio_general_coverage', $eh_obrigatorio_coverage);
      $util->campoHidden('eh_obrigatorio_general_structure', $eh_obrigatorio_structure);
      $util->campoHidden('eh_obrigatorio_general_agregation_level', $eh_obrigatorio_agregation_level);
      $util->campoHidden('eh_obrigatorio_arquivo_imagem', '0');
      
      if ($eh_obrigatorio_identifier || $eh_obrigatorio_title || $eh_obrigatorio_language || $eh_obrigatorio_description || $eh_obrigatorio_keyword ||$eh_obrigatorio_nivel_educacional || $eh_obrigatorio_coverage || $eh_obrigatorio_structure || $eh_obrigatorio_agregation_level || $eh_obrigatorio_arquivo_imagem) {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações gerais');
        if ($eh_informar_title == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_title, $conf->retornaDescricaoCampoGeneralTitulo(), $conf->retornaNomeCampoGeneralTitulo(), 'ds_general_title', '250', '100', $ds_title, 1);
          $util->campoHidden('nm_general_title', $conf->retornaNomeCampoGeneralTitulo());
        } else {
          $util->campoHidden('ds_general_title', $ds_title);
        }
        if ($eh_informar_identifier == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_identifier, $conf->retornaDescricaoCampoGeneralIdentificador(), $conf->retornaNomeCampoGeneralIdentificador(), 'ds_general_identifier', $conf->retornaNumeroCaracteresResumoNomeObjetoAprendizagem(), '100', $ds_identifier, 1);
          $util->campoHidden('nm_general_identifier', $conf->retornaNomeCampoGeneralIdentificador());
        } else {
          $util->campoHidden('ds_general_identifier', $ds_identifier);
        }
        if ($eh_informar_arquivo_imagem == '1') {
          $util->linhaUmCampoArquivoHintAcao('0', $conf->retornaDescricaoCampoGeneralImagem()."\nNão há imagem cadastrada.\nPara melhor apresentação, a imagem deve possuir medidas de altura e largura iguais.\n", $conf->retornaNomeCampoGeneralImagem(), 'ds_arquivo_imagem', '150', '100', '', 1, " onBlur=\"validarImagemIdentificacaoOA();\" onChange=\"validarImagemIdentificacaoOA();\" ");
          if ($ds_arquivo_imagem != '') {
            echo "      <tr>\n";
            echo "		    <td class=\"celConteudoChamada\" colspan=\"2\" style=\"text-align:center;\">\n";
            echo "          <img src=\"".$_SESSION['life_link_completo'].$ds_pasta_arquivo_imagem.$ds_arquivo_imagem."\" border=\"0\" width=\"500px;\">\n";
            echo "          <br />Selecione nova imagem para alterar a imagem atual do Objeto de Aprendizagem.<br />Para manter a mesma imagem não selecione nenhum arquivo.\n";
            echo "        </td>\n";
            echo "      </tr>\n";
          }
          $util->campoHidden('ds_pasta_arquivo_imagem', $ds_pasta_arquivo_imagem);
          $util->campoHidden('ds_pasta_arquivo_imagem_original', $ds_pasta_arquivo_imagem);
          $util->campoHidden('ds_arquivo_imagem_original', $ds_arquivo_imagem);

          $extensoes = $arq->selectExtensoes('1', '2', '2');
          $util->campoHidden('qt_extensoes_imagem', count($extensoes));
          $i = 1;
          foreach ($extensoes as $e) {
            $util->campoHidden('ds_extensao_'.$i, $e['ds_extensao']);
            $i+=1;
          }
          $util->campoHidden('nm_arquivo_imagem', $conf->retornaNomeCampoGeneralImagem());
        } else {
          $util->campoHidden('ds_arquivo_imagem', $ds_arquivo_imagem);
          $util->campoHidden('ds_pasta_arquivo_imagem', $ds_pasta_arquivo_imagem);
        }
        if ($eh_informar_language == '1') {        
          require_once 'conteudos/linguagens.php';                              $lin = new Linguagem();
          $lin->retornaSeletorLinguagem($cd_language, 'cd_general_language', '100', 1, $conf->retornaDescricaoCampoGeneralIdioma(), $conf->retornaNomeCampoGeneralIdioma());
          $util->campoHidden('nm_general_language', $conf->retornaNomeCampoGeneralIdioma());
        } else {
          $util->campoHidden('cd_general_language', $cd_language);
        }
        if ($eh_informar_description == '1') {
          $util->linhaTextoHint($eh_obrigatorio_description, $conf->retornaDescricaoCampoGeneralDescricao(), $conf->retornaNomeCampoGeneralNome(), 'ds_general_description', $ds_description, '5', '100', 1);
          $util->campoHidden('nm_general_description', $conf->retornaNomeCampoGeneralNome());
        } else {
          $util->campoHidden('ds_general_description', $ds_description);
        }
        if ($eh_informar_keyword == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_keyword, $conf->retornaDescricaoCampoGeneralPalavraChave().' (Separadas por ponto (.))', $conf->retornaNomeCampoGeneralPalavraChave(), 'ds_general_keyword', '250', '100', $ds_keyword, 1);
          $util->campoHidden('nm_general_keyword', $conf->retornaNomeCampoGeneralPalavraChave());
        } else {
          $util->campoHidden('ds_general_keyword', $ds_keyword);
        }
        if ($eh_informar_nivel_educacional == '1') {
          require_once 'conteudos/objetos_aprendizagem_general_niveis_educacionais.php';                     $gne = new ObjetoAprendizagemGeneralNiveiEducacional();
          $gne->retornaSeletorNiveisEducacionaisObjetoAprendizagem($cd_general, $conf->retornaDescricaoCampoNivelEducacional(), $conf->retornaNomeCampoNivelEducacional());
          $util->campoHidden('nm_general_nivel_educacional', $conf->retornaNomeCampoNivelEducacional());
        }
        if ($eh_informar_coverage == '1') {
          //cobertura - entendido como areas do conhecimento
          require_once 'conteudos/objetos_aprendizagem_general_areas_conhecimento.php'; $gac = new ObjetoAprendizagemGeneralAreaConhecimento();
          $gac->retornaSeletorAreasConhecimentoObjetoAprendizagem($cd_general, $conf->retornaDescricaoCampoGeneralCobertura(), $conf->retornaNomeCampoGeneralCobertura());
          $util->campoHidden('nm_general_coverage', $conf->retornaNomeCampoGeneralCobertura());
        }
        if ($eh_informar_structure == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_structure, $conf->retornaDescricaoCampoGeneralEstrutura(), $conf->retornaNomeCampoGeneralEstrutura(), 'ds_general_structure', '250', '100', $ds_structure, 1);
          $util->campoHidden('nm_general_structure', $conf->retornaNomeCampoGeneralEstrutura());
        } else {
          $util->campoHidden('ds_general_structure', $ds_structure);
        }
        if ($eh_informar_agregation_level == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_agregation_level, $conf->retornaDescricaoCampoGeneralNivelAgregacao(), $conf->retornaNomeCampoGeneralNivelAgregacao(), 'ds_general_agregation_level', '250', '100', $ds_agregation_level, 1);
          $util->campoHidden('nm_general_agregation_level', $conf->retornaNomeCampoGeneralNivelAgregacao());
        } else {
          $util->campoHidden('ds_general_agregation_level', $ds_agregation_level);
        }                                                                                                                 
      } else {
        $util->campoHidden('ds_general_identifier', $ds_identifier);
        $util->campoHidden('ds_general_title', $ds_title);
        $util->campoHidden('cd_general_language', $cd_language);
        $util->campoHidden('ds_general_description', $ds_description);
        $util->campoHidden('ds_general_keyword', $ds_keyword);
        $util->campoHidden('ds_general_structure', $ds_structure);
        $util->campoHidden('ds_general_agregation_level', $ds_agregation_level);
        $util->campoHidden('ds_arquivo_imagem', $ds_arquivo_imagem);
        $util->campoHidden('ds_pasta_arquivo_imagem', $ds_pasta_arquivo_imagem);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/fotos.php';                                       $fot = new Fotos();
      require_once 'conteudos/objetos_aprendizagem_general_niveis_educacionais.php';$gne = new ObjetoAprendizagemGeneralNiveiEducacional();
      require_once 'conteudos/objetos_aprendizagem_general_areas_conhecimento.php'; $gac = new ObjetoAprendizagemGeneralAreaConhecimento();
      require_once 'conteudos/sub_areas_conhecimento_general.php';              $sacg = new SubAreaConhecimentoGeneral();

      $cd_general = addslashes($_POST['cd_general']);
      $eh_informar_identifier = addslashes($_POST['eh_informar_general_identifier']);
      $eh_informar_title = addslashes($_POST['eh_informar_general_title']);
      $cd_language = addslashes($_POST['cd_general_language']);
      $eh_informar_language = addslashes($_POST['eh_informar_general_language']);
      $eh_informar_description = addslashes($_POST['eh_informar_general_description']);
      $eh_informar_keyword = addslashes($_POST['eh_informar_general_keyword']);
      $eh_informar_nivel_educacional = addslashes($_POST['eh_informar_general_nivel_educacional']);
      $eh_informar_coverage = addslashes($_POST['eh_informar_general_coverage']);
      $eh_informar_structure = addslashes($_POST['eh_informar_general_structure']);
      $eh_informar_agregation_level = addslashes($_POST['eh_informar_general_agregation_level']);
      $eh_informar_arquivo_imagem = addslashes($_POST['eh_informar_arquivo_imagem']);

      $ds_identifier = $util->limparVariavel($_POST['ds_general_identifier']);
      $ds_title = $util->limparVariavel($_POST['ds_general_title']);
      $ds_description = $util->limparVariavel($_POST['ds_general_description']);
      $ds_keyword = $util->limparVariavel($_POST['ds_general_keyword']);
      $ds_structure = $util->limparVariavel($_POST['ds_general_structure']);
      $ds_agregation_level = $util->limparVariavel($_POST['ds_general_agregation_level']);

      $tp_associacao = 'NE';
      $ds_pasta_arquivo_imagem = addslashes($_POST['ds_pasta_arquivo_imagem']);

      if (isset($_FILES['ds_arquivo_imagem'])) {
        $arquivo = $_FILES['ds_arquivo_imagem'];
        if ($arquivo['name'] != '') {
          $foto = $fot->enviarFoto('ds_arquivo_imagem', $ds_pasta_arquivo_imagem, 'OA', $tp_associacao, '');
          if ($foto[0] != '') {
            echo "<p class=\"fontConteudoAlerta\">Erro - ".$foto[0]."</p>\n";
            $ds_arquivo_imagem = '';
          } else {
            $ds_arquivo_imagem = $foto[1];
          }
        } else {
          $ds_arquivo_imagem = addslashes($_POST['ds_arquivo_imagem_original']);
          $ds_pasta_arquivo_imagem = addslashes($_POST['ds_pasta_arquivo_imagem_original']);
        }
      } else {
        $ds_arquivo_imagem = '';
        $ds_pasta_arquivo_imagem = '';
      }

      if ($cd_general > 0) {
        $gac->salvarCadastroAlteracao($cd_general);
        $gne->salvarCadastroAlteracao($cd_general);
        $niveis = $gne->retornaRelacaoNiveis($cd_general);
        $areas = $gac->retornaRelacaoAreas($cd_general);
        $sub_areas = $sacg->retornaRelacaoSubAreasConhecimentoGeneralAreasConhecimento($cd_general);
        $cd_general = $this->alteraGeneral($cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $eh_informar_nivel_educacional, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level, $ds_arquivo_imagem, $ds_pasta_arquivo_imagem, $eh_informar_arquivo_imagem);
        $_SESSION['life_agrupador_termos_cadastro'] .= $ds_identifier." | ".$ds_title." | ".$ds_description." | ".$ds_keyword." | ".$niveis.$areas.$sub_areas.$ds_structure." | ".$ds_agregation_level;
        return $cd_general;
      } else {
        $cd_general = $this->insereGeneral($ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $eh_informar_nivel_educacional, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level, $ds_arquivo_imagem, $ds_pasta_arquivo_imagem, $eh_informar_arquivo_imagem);
        if ($cd_general > 0) {
          $gac->salvarCadastroAlteracao($cd_general);
          $gne->salvarCadastroAlteracao($cd_general);
        }
        $areas = $gac->retornaRelacaoAreas($cd_general);
        $sub_areas = $sacg->retornaRelacaoSubAreasConhecimentoGeneralAreasConhecimento($cd_general);
        $niveis = $gne->retornaRelacaoNiveis($cd_general);
        $_SESSION['life_agrupador_termos_cadastro'] .= $ds_identifier." | ".$ds_title." | ".$ds_description." | ".$ds_keyword." | ".$niveis.$areas.$sub_areas.$ds_structure." | ".$ds_agregation_level;
        return $cd_general;
      }
    } 

    public function imprimeDados($cd_general) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      require_once 'conteudos/objetos_aprendizagem_general_niveis_educacionais.php';$gne = new ObjetoAprendizagemGeneralNiveiEducacional();
      require_once 'conteudos/objetos_aprendizagem_general_areas_conhecimento.php'; $gac = new ObjetoAprendizagemGeneralAreaConhecimento();

      $dados = $this->selectDadosObjetoAprendizagemGeneral($cd_general);

      $eh_informar_identifier = '0';
      $eh_informar_title = $dados['eh_informar_title'];
      $eh_informar_language = $dados['eh_informar_language'];
      $eh_informar_description = $dados['eh_informar_description'];
      $eh_informar_keyword = $dados['eh_informar_keyword'];
      $eh_informar_nivel_educacional = '1';
      $eh_informar_coverage = '1';
      $eh_informar_structure = $dados['eh_informar_structure'];
      $eh_informar_agregation_level = $dados['eh_informar_agregation_level'];
      $eh_informar_arquivo_imagem = $dados['eh_informar_arquivo_imagem'];

      $ds_identifier = $dados['ds_identifier'];
      $ds_title = $dados['ds_title'];
      $cd_language = $dados['cd_language'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];
      $ds_structure = $dados['ds_structure'];
      $ds_agregation_level = $dados['ds_agregation_level'];
      $ds_arquivo_imagem = $dados['ds_arquivo_imagem'];
      $ds_pasta_arquivo_imagem = $dados['ds_pasta_arquivo_imagem'];

      $retorno = '';

      $area = $gac->retornaUmaAreaConhecimento($cd_general);
      $dados = $are_con->selectDadosCorAreaConhecimento($area['cd_area_conhecimento']);

      if ($eh_informar_identifier || $eh_informar_title || $eh_informar_language || $eh_informar_description || $eh_informar_keyword ||$eh_informar_nivel_educacional || $eh_informar_coverage || $eh_informar_structure || $eh_informar_agregation_level || $eh_informar_arquivo_imagem) {
      $retorno.= "<div class=\"divConteudoUnicoObjetoAprendizagem\">\n";
      $retorno.= "  <div class=\"divImagemObjetoAprendizagem\">\n";
      if ($ds_arquivo_imagem != '') {
        $retorno.= "  <img src=\"".$_SESSION['life_link_completo'].$ds_pasta_arquivo_imagem.$ds_arquivo_imagem."\" alt=\"".$ds_identifier."\" title=\"".$ds_identifier."\" border=\"0\" width=\"100%\">\n";
      } else {
        $dados = $gne->selectDadosMaiorNivelEducacional($cd_general);
        $retorno.= "  <img src=\"".$_SESSION['life_link_completo'].$dados['ds_arquivo_imagem']."\" alt=\"".$ds_identifier."\" title=\"".$ds_identifier."\" border=\"0\" width=\"100%\">\n";
      }
      $retorno.= "  </div>\n";

      if (($eh_informar_identifier == '1') && ($ds_identifier != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralIdentificador()."</b> ".$ds_identifier;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_title == '1') && ($ds_title != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralTitulo()."</b> ".$ds_title;
        $retorno.= "</p>\n";
      }
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= $gac->retornaDadosAreas($cd_general, $conf->retornaNomeCampoGeneralCobertura());
      $retorno.= "</p>\n";
      if (($eh_informar_language == '1') && ($cd_language > 0)) {
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= $lin->retornaDados($cd_language, $conf->retornaInformacaoCampoGeneralIdioma());
        $retorno.= "</p>\n";
      }
      if (($eh_informar_description == '1') && ($ds_description != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralDescricao()."</b> ".$ds_description;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_keyword == '1') && ($ds_keyword != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralPalavraChave()."</b> ".$ds_keyword;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_nivel_educacional == '1') {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= $gne->retornaDadosNiveis($cd_general, $conf->retornaInformacaoCampoNivelEducacional());
        $retorno.= "</p>\n";
      }
      if (($eh_informar_structure == '1') && ($ds_structure != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralEstrutura()."</b> ".$ds_structure;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_agregation_level == '1') && ($ds_agregation_level != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralNivelAgregacao()."</b> ".$ds_agregation_level;
        $retorno.= "</p>\n";
      }
      $retorno.= "<div class=\"clear\"></div>\n";
      $retorno.= "</div>\n";
      }
      return $retorno;
    }

    public function imprimeDadosResumidos($cd_general) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      require_once 'conteudos/objetos_aprendizagem_general_niveis_educacionais.php';$gne = new ObjetoAprendizagemGeneralNiveiEducacional();
      require_once 'conteudos/objetos_aprendizagem_general_areas_conhecimento.php'; $gac = new ObjetoAprendizagemGeneralAreaConhecimento();

      $dados = $this->selectDadosObjetoAprendizagemGeneral($cd_general);

      $eh_informar_identifier = '1';
      $eh_informar_title = $dados['eh_informar_title'];
      $eh_informar_language = $dados['eh_informar_language'];
      $eh_informar_description = $dados['eh_informar_description'];
      $eh_informar_keyword = $dados['eh_informar_keyword'];
      $eh_informar_nivel_educacional = '1';
      $eh_informar_coverage = '1';
      $eh_informar_structure = $dados['eh_informar_structure'];
      $eh_informar_agregation_level = $dados['eh_informar_agregation_level'];
      $eh_informar_arquivo_imagem = $dados['eh_informar_arquivo_imagem'];

      $ds_identifier = $dados['ds_identifier'];
      $ds_title = $dados['ds_title'];
      $cd_language = $dados['cd_language'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];
      $ds_structure = $dados['ds_structure'];
      $ds_agregation_level = $dados['ds_agregation_level'];
      $ds_arquivo_imagem = $dados['ds_arquivo_imagem'];
      $ds_pasta_arquivo_imagem = $dados['ds_pasta_arquivo_imagem'];

      $retorno = '';
      if ($eh_informar_identifier || $eh_informar_title || $eh_informar_language || $eh_informar_description || $eh_informar_keyword ||$eh_informar_nivel_educacional || $eh_informar_coverage || $eh_informar_structure || $eh_informar_agregation_level || $eh_informar_arquivo_imagem) {

      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= "<b>Informações gerais</b>";
      $retorno.= "</p>\n";

      if (($eh_informar_title == '1') && ($ds_title != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralTitulo()."</b> ".$ds_title;
        $retorno.= "</p>\n";
      }
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= $gac->retornaDadosAreas($cd_general, $conf->retornaNomeCampoGeneralCobertura());
      $retorno.= "</p>\n";
      if (($eh_informar_language == '1') && ($cd_language > 0)) {
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= $lin->retornaDados($cd_language, $conf->retornaInformacaoCampoGeneralIdioma());
        $retorno.= "</p>\n";
      }
      if (($eh_informar_description == '1') && ($ds_description != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralDescricao()."</b> ".$ds_description;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_keyword == '1') && ($ds_keyword != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralPalavraChave()."</b> ".$ds_keyword;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_nivel_educacional == '1') {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= $gne->retornaDadosNiveis($cd_general, $conf->retornaInformacaoCampoNivelEducacional());
        $retorno.= "</p>\n";
      }
      if (($eh_informar_structure == '1') && ($ds_structure != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralEstrutura()."</b> ".$ds_structure;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_agregation_level == '1') && ($ds_agregation_level != '')) {
        $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralNivelAgregacao()."</b> ".$ds_agregation_level;
        $retorno.= "</p>\n";
      }
      }
      return $retorno;
    }



    public function imprimeDadosRetornoPesquisa($cd_general, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      require_once 'conteudos/objetos_aprendizagem_general_niveis_educacionais.php';$gne = new ObjetoAprendizagemGeneralNiveiEducacional();
      require_once 'conteudos/objetos_aprendizagem_general_areas_conhecimento.php'; $gac = new ObjetoAprendizagemGeneralAreaConhecimento();

      $dados = $this->selectDadosObjetoAprendizagemGeneral($cd_general);

      $eh_informar_identifier = '1';
      $eh_informar_title = $conf->retornaInformarGeneralTitle($tipo);
      $eh_informar_language = $conf->retornaInformarGeneralLanguage($tipo);
      $eh_informar_description = $conf->retornaInformarGeneralDescription($tipo);
      $eh_informar_keyword = $conf->retornaInformarGeneralKeyword($tipo);
      $eh_informar_nivel_educacional = '1';
      $eh_informar_coverage = '1';
      $eh_informar_structure = $conf->retornaInformarGeneralStructure($tipo);
      $eh_informar_agregation_level = $conf->retornaInformarGeneralAgregationLevel($tipo);
      $eh_informar_arquivo_imagem = $conf->retornaInformarGeneralImagem($tipo);

      $ds_identifier = $dados['ds_identifier'];
      $ds_title = $dados['ds_title'];
      $cd_language = $dados['cd_language'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];
      $ds_structure = $dados['ds_structure'];
      $ds_agregation_level = $dados['ds_agregation_level'];
      $ds_arquivo_imagem = $dados['ds_arquivo_imagem'];
      $ds_pasta_arquivo_imagem = $dados['ds_pasta_arquivo_imagem'];

      $retorno = '';
      if (($eh_informar_title == '1') && ($ds_title != '')) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralTitulo()."</b> ".$ds_title."<br />\n";
      }
      $retorno.= $gac->retornaDadosAreas($cd_general, $conf->retornaNomeCampoGeneralCobertura())."<br />\n";
      if (($eh_informar_language == '1') && ($cd_language > 0)) {
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $retorno.= $lin->retornaDados($cd_language, $conf->retornaInformacaoCampoGeneralIdioma())."<br />\n";
      }
      if (($eh_informar_description == '1') && ($ds_description != '')) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralDescricao()."</b> ".$ds_description."<br />\n";
      }
      if (($eh_informar_keyword == '1') && ($ds_keyword != '')) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralPalavraChave()."</b> ".$ds_keyword."<br />\n";
      }
      if ($eh_informar_nivel_educacional == '1') {
        $retorno.= $gne->retornaDadosNiveis($cd_general, $conf->retornaInformacaoCampoNivelEducacional())."<br />\n";
      }
      if (($eh_informar_structure == '1') && ($ds_structure != '')) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralEstrutura()."</b> ".$ds_structure."<br />\n";
      }
      if (($eh_informar_agregation_level == '1') && ($ds_agregation_level != '')) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoGeneralNivelAgregacao()."</b> ".$ds_agregation_level."<br />\n";
      }
      return $retorno;
    }
//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemGeneral($cd_general) {
      $sql  = "SELECT * ".
              "FROM life_general ".
              "WHERE cd_general = '$cd_general' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereGeneral($ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $eh_informar_nivel_educacional, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level, $ds_arquivo_imagem, $ds_pasta_arquivo_imagem, $eh_informar_arquivo_imagem) {
      $sql = "INSERT INTO life_general ".
             "(ds_identifier, eh_informar_identifier, ds_title, eh_informar_title, cd_language, eh_informar_language, ds_description, eh_informar_description, ds_keyword, eh_informar_keyword, eh_informar_nivel_educacional, eh_informar_coverage, ds_structure, eh_informar_structure, ds_agregation_level, eh_informar_agregation_level, ds_arquivo_imagem, ds_pasta_arquivo_imagem, eh_informar_arquivo_imagem) ".
             "VALUES ".
             "(\"$ds_identifier\", \"$eh_informar_identifier\", \"$ds_title\", \"$eh_informar_title\", \"$cd_language\", \"$eh_informar_language\", \"$ds_description\", \"$eh_informar_description\", \"$ds_keyword\", \"$eh_informar_keyword\", \"$eh_informar_nivel_educacional\", \"$eh_informar_coverage\", \"$ds_structure\", \"$eh_informar_structure\", \"$ds_agregation_level\", \"$eh_informar_agregation_level\", \"$ds_arquivo_imagem\", \"$ds_pasta_arquivo_imagem\", \"$eh_informar_arquivo_imagem\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_general) codigo ".
                "FROM life_general ".
                "WHERE ds_identifier = '$ds_identifier' ".
                "AND ds_title = '$ds_title' ".
                "AND cd_language = '$cd_language' ".
                "AND ds_description = '$ds_description' ".
                "AND ds_keyword = '$ds_keyword' ".
                "AND ds_structure = '$ds_structure' ".
                "AND ds_agregation_level = '$ds_agregation_level' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraGeneral($cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $eh_informar_nivel_educacional, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level, $ds_arquivo_imagem, $ds_pasta_arquivo_imagem, $eh_informar_arquivo_imagem) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_general SET ".
             "ds_identifier = \"$ds_identifier\", ".
             "eh_informar_identifier = \"$eh_informar_identifier\", ".
             "ds_title = \"$ds_title\", ".
             "eh_informar_title = \"$eh_informar_title\", ".
             "cd_language = \"$cd_language\", ".
             "eh_informar_language = \"$eh_informar_language\", ".
             "ds_description = \"$ds_description\", ".
             "eh_informar_description = \"$eh_informar_description\", ".
             "ds_keyword = \"$ds_keyword\", ".
             "eh_informar_keyword = \"$eh_informar_keyword\", ".
             "eh_informar_nivel_educacional = \"$eh_informar_nivel_educacional\", ".
             "eh_informar_coverage = \"$eh_informar_coverage\", ".
             "ds_structure = \"$ds_structure\", ".
             "eh_informar_structure = \"$eh_informar_structure\", ".
             "ds_agregation_level = \"$ds_agregation_level\", ".
             "eh_informar_agregation_level = \"$eh_informar_agregation_level\", ".
             "ds_arquivo_imagem = \"$ds_arquivo_imagem\", ".
             "ds_pasta_arquivo_imagem = \"$ds_pasta_arquivo_imagem\", ".
             "eh_informar_arquivo_imagem = \"$eh_informar_arquivo_imagem\" ".
             "WHERE cd_general = '$cd_general' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>