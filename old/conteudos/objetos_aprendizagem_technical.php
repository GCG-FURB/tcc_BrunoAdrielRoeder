<?php
  class ObjetoAprendizagemTechnical {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_technical > 0) {
        $this->montarFormularioEdicao($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_technical, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_technical, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_plataform_specific_features = $conf->retornaInformarTechnicalPlataformSpecificFeatures($tipo);
      $eh_informar_service = $conf->retornaInformarTechnicalService($tipo);

      $eh_informar_format = '1';//$conf->retornaInformarTechnicalFormat($tipo);
      $eh_informar_size = $conf->retornaInformarTechnicalSize($tipo);
      $eh_informar_location = '1'; //$conf->retornaInformarTechnicalLocation($tipo);
      $eh_informar_requirement = $conf->retornaInformarTechnicalRequirement($tipo);
      $eh_informar_composite = $conf->retornaInformarTechnicalComposite($tipo);
      $eh_informar_installation_remarks = $conf->retornaInformarTechnicalInstallationRemarks($tipo);
      $eh_informar_other_plataforms_requirements = $conf->retornaInformarTechnicalOtherPlataformsRequirements($tipo);
      $eh_informar_duration = $conf->retornaInformarTechnicalDuration($tipo);
      $eh_informar_supported_plataform = $conf->retornaInformarTechnicalSupportedPlataform($tipo);

      $cd_technical = "";
      $cd_format = "";
      $ds_size = "";
      $ds_location = "";
      $ds_requirement = "";
      $ds_composite = "";
      $ds_installation_remarks = "";
      $ds_other_plataforms_requirements = "";
      $ds_duration = "";
      $ds_supported_plataform = "";
      $cd_plataform_specific_features = "";
      $cd_service = "";
      
      $this->imprimeFormularioCadastro($eh_informar_technical, $eh_manter_configuracoes_originais, $cd_technical, $cd_format, $eh_informar_format, $ds_size, $eh_informar_size, $ds_location, $eh_informar_location, $ds_requirement, $eh_informar_requirement, $ds_composite, $eh_informar_composite, $ds_installation_remarks, $eh_informar_installation_remarks, $ds_other_plataforms_requirements, $eh_informar_other_plataforms_requirements, $ds_duration, $eh_informar_duration, $ds_supported_plataform, $eh_informar_supported_plataform, $cd_plataform_specific_features, $eh_informar_plataform_specific_features, $cd_service, $eh_informar_service, $tipo);
    }
    
    private function montarFormularioEdicao($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnical($cd_technical);
      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_plataform_specific_features = $dados['eh_informar_plataform_specific_features'];
        $eh_informar_service = $dados['eh_informar_service'];

        $eh_informar_format = '1'; //$dados['eh_informar_format'];
        $eh_informar_size = $dados['eh_informar_size'];
        $eh_informar_location = '1'; //$dados['eh_informar_location'];
        $eh_informar_requirement = $dados['eh_informar_requirement'];
        $eh_informar_composite = $dados['eh_informar_composite'];
        $eh_informar_installation_remarks = $dados['eh_informar_installation_remarks'];
        $eh_informar_other_plataforms_requirements = $dados['eh_informar_other_plataforms_requirements'];
        $eh_informar_duration = $dados['eh_informar_duration'];
        $eh_informar_supported_plataform = $dados['eh_informar_supported_plataform'];
      } else {
        $eh_informar_plataform_specific_features = $conf->retornaInformarTechnicalPlataformSpecificFeatures($tipo);
        $eh_informar_service = $conf->retornaInformarTechnicalService($tipo);

        $eh_informar_format = '1'; //$conf->retornaInformarTechnicalFormat($tipo);
        $eh_informar_size = $conf->retornaInformarTechnicalSize($tipo);
        $eh_informar_location = '1'; //$conf->retornaInformarTechnicalLocation($tipo);
        $eh_informar_requirement = $conf->retornaInformarTechnicalRequirement($tipo);
        $eh_informar_composite = $conf->retornaInformarTechnicalComposite($tipo);
        $eh_informar_installation_remarks = $conf->retornaInformarTechnicalInstallationRemarks($tipo);
        $eh_informar_other_plataforms_requirements = $conf->retornaInformarTechnicalOtherPlataformsRequirements($tipo);
        $eh_informar_duration = $conf->retornaInformarTechnicalDuration($tipo);
        $eh_informar_supported_plataform = $conf->retornaInformarTechnicalSupportedPlataform($tipo);        
      }
      
      $cd_format = $dados['cd_format'];
      $ds_size = $dados['ds_size'];
      $ds_location = $dados['ds_location'];
      $ds_requirement = $dados['ds_requirement'];
      $ds_composite = $dados['ds_composite'];
      $ds_installation_remarks = $dados['ds_installation_remarks'];
      $ds_other_plataforms_requirements = $dados['ds_other_plataforms_requirements'];
      $ds_duration = $dados['ds_duration'];
      $ds_supported_plataform = $dados['ds_supported_plataform'];
      $cd_plataform_specific_features = $dados['cd_plataform_specific_features'];
      $cd_service = $dados['cd_service'];
      
      $this->imprimeFormularioCadastro($eh_informar_technical, $eh_manter_configuracoes_originais, $cd_technical, $cd_format, $eh_informar_format, $ds_size, $eh_informar_size, $ds_location, $eh_informar_location, $ds_requirement, $eh_informar_requirement, $ds_composite, $eh_informar_composite, $ds_installation_remarks, $eh_informar_installation_remarks, $ds_other_plataforms_requirements, $eh_informar_other_plataforms_requirements, $ds_duration, $eh_informar_duration, $ds_supported_plataform, $eh_informar_supported_plataform, $cd_plataform_specific_features, $eh_informar_plataform_specific_features, $cd_service, $eh_informar_service, $tipo);
    }
    
    public function imprimeFormularioCadastro($eh_informar_technical, $eh_manter_configuracoes_originais, $cd_technical, $cd_format, $eh_informar_format, $ds_size, $eh_informar_size, $ds_location, $eh_informar_location, $ds_requirement, $eh_informar_requirement, $ds_composite, $eh_informar_composite, $ds_installation_remarks, $eh_informar_installation_remarks, $ds_other_plataforms_requirements, $eh_informar_other_plataforms_requirements, $ds_duration, $eh_informar_duration, $ds_supported_plataform, $eh_informar_supported_plataform, $cd_plataform_specific_features, $eh_informar_plataform_specific_features, $cd_service, $eh_informar_service, $tipo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
      $util->campoHidden('ds_limite_tamanho_arquivos', $ds_limite_tamanho_arquivos);
      
      $util->campoHidden('cd_technical', $cd_technical);
      $util->campoHidden('eh_informar_technical_format', $eh_informar_format);
      $util->campoHidden('eh_informar_technical_size', $eh_informar_size);
      $util->campoHidden('eh_informar_technical_location', $eh_informar_location);
      $util->campoHidden('eh_informar_technical_requirement', $eh_informar_requirement);
      $util->campoHidden('eh_informar_technical_composite', $eh_informar_composite);
      $util->campoHidden('eh_informar_technical_installation_remarks', $eh_informar_installation_remarks);
      $util->campoHidden('eh_informar_technical_other_plataforms_requirements', $eh_informar_other_plataforms_requirements);
      $util->campoHidden('eh_informar_technical_duration', $eh_informar_duration);
      $util->campoHidden('eh_informar_technical_supported_plataform', $eh_informar_supported_plataform);
      $util->campoHidden('eh_informar_technical_plataform_specific_features', $eh_informar_plataform_specific_features);
      $util->campoHidden('eh_informar_technical_service', $eh_informar_service); 

      $eh_obrigatorio_technical = $eh_informar_technical;
      $eh_obrigatorio_plataform_specific_features = $conf->retornaInformarTechnicalPlataformSpecificFeatures('b');
      $eh_obrigatorio_service = $conf->retornaInformarTechnicalService('b');
      $eh_obrigatorio_format = '1'; //$conf->retornaInformarTechnicalFormat('b');
      $eh_obrigatorio_size = $conf->retornaInformarTechnicalSize('b');
      $eh_obrigatorio_location = '1'; //$conf->retornaInformarTechnicalLocation('b');
      $eh_obrigatorio_requirement = $conf->retornaInformarTechnicalRequirement('b');
      $eh_obrigatorio_composite = $conf->retornaInformarTechnicalComposite('b');
      $eh_obrigatorio_installation_remarks = $conf->retornaInformarTechnicalInstallationRemarks('b');
      $eh_obrigatorio_other_plataforms_requirements = $conf->retornaInformarTechnicalOtherPlataformsRequirements('b');
      $eh_obrigatorio_duration = $conf->retornaInformarTechnicalDuration('b');
      $eh_obrigatorio_supported_plataform = $conf->retornaInformarTechnicalSupportedPlataform('b');

      $util->campoHidden('eh_obrigatorio_technical_format', '1');
      $util->campoHidden('eh_obrigatorio_technical_size', $eh_obrigatorio_size);
      $util->campoHidden('eh_obrigatorio_technical_location', '1');
      $util->campoHidden('eh_obrigatorio_technical_requirement', $eh_obrigatorio_requirement);
      $util->campoHidden('eh_obrigatorio_technical_composite', $eh_obrigatorio_composite);
      $util->campoHidden('eh_obrigatorio_technical_installation_remarks', $eh_obrigatorio_installation_remarks);
      $util->campoHidden('eh_obrigatorio_technical_other_plataforms_requirements', $eh_obrigatorio_other_plataforms_requirements);
      $util->campoHidden('eh_obrigatorio_technical_duration', $eh_obrigatorio_duration);
      $util->campoHidden('eh_obrigatorio_technical_supported_plataform', $eh_obrigatorio_supported_plataform);
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features', $eh_obrigatorio_plataform_specific_features);
      $util->campoHidden('eh_obrigatorio_technical_service', $eh_obrigatorio_service); 
      
      
      if ($eh_informar_technical == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações Técnicas');  
      
        if ($eh_informar_format == '1') {
          require_once 'conteudos/formatos.php';                                $for = new Formato();
          $for->retornaSeletorFormato($cd_format, 'cd_technical_format', '100', 1, $conf->retornaDescricaoCampoTechnicalFormato(), $conf->retornaNomeCampoTechnicalFormato());
          $util->campoHidden('cd_technical_format_original', $cd_format);                                             
          $util->campoHidden('nm_technical_format', $conf->retornaNomeCampoTechnicalFormato());
        } else {
          $util->campoHidden('cd_technical_format', $cd_format);
        }
        if ($eh_informar_size == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_size, $conf->retornaDescricaoCampoTechnicalTamanho(), $conf->retornaNomeCampoTechnicalTamanho(), 'ds_technical_size', '12', '100', $ds_size, 2);
          $util->campoHidden('nm_technical_size', $conf->retornaNomeCampoTechnicalTamanho());
        } else {
          $util->campoHidden('ds_technical_size', $ds_size);
        }
        if ($eh_informar_location == '1') {        
          $ajuda = "\n\n";
          switch ($cd_format) {
            case "1":
              $ds_sugestao_locais_audio = $conf->retornaSugestaoLocaisAudio();   
              $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": para alterar o Link de arquivo de Áudio atual, informe novo link de Áudio!\n".
                        nl2br($ds_sugestao_locais_audio)."\n".
                        "Este campo é de preenchimento obrigatório!\n";
            break;   

            case "2":   
              $ds_sugestao_locais_video = $conf->retornaSugestaoLocaisVideo();
              $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": para alterar o Link de arquivo de Vídeo atual, informe novo link de Vídeo!\n".
                        nl2br($ds_sugestao_locais_video)."\n".
                        "Este campo é de preenchimento obrigatório!\n";
            break;
            
            case "3":                     
              require_once 'conteudos/arquivos_extensao.php';                   $arq_ext = new ArquivoExtensao();
              $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_format);
              $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": para alterar o arquivo de Texto, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n".
                        "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                        "\nFormatos: \n".$ds_extensoes_arquivos."\n".
                        "Este campo é de preenchimento obrigatório!\n";
            break;
              
            case "4":
              require_once 'conteudos/arquivos_extensao.php';                   $arq_ext = new ArquivoExtensao();
              $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_format);
              $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": para alterar o arquivo de Imagem, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n".
                        "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                        "\nFormatos: \n".$ds_extensoes_arquivos."\n".
                        "Este campo é de preenchimento obrigatório!\n";
            break;
            
            case "5":
/*
              require_once 'conteudos/arquivos_extensao.php';                   $arq_ext = new ArquivoExtensao();
              $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_format);
              $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": para alterar o arquivo do Aplicativo, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n".
                        "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                        "Formatos: \n".$ds_extensoes_arquivos."\n".
                        "Este campo é de preenchimento obrigatório!\n";
*/
              $ds_sugestao_locais_aplicativos = $conf->retornaSugestaoLocaisAplicativos();
              if ($ds_sugestao_locais_aplicativos != '') {
                $ajuda .= "Para alterar o Link de arquivo do Aplicativo atual, informe novo link do Aplicativo!\n";
              } else {
                $ajuda .= "Informe link do arquivo do Aplicativo!\n";
              }
              $ajuda.= nl2br($ds_sugestao_locais_aplicativos)."\n".
                       "Este campo é de preenchimento obrigatório!\n";
            break;
            
            case "6":   
              $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": para alterar o Link Externo atual, informe novo link externo!\n".
                        "Este campo é de preenchimento obrigatório!\n";
            break;
            
            case "7":
              require_once 'conteudos/arquivos_extensao.php';                   $arq_ext = new ArquivoExtensao();
              $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_format);
              $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": para alterar o arquivo de Apresentação, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n".
                        "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                        "\nFormatos: \n".$ds_extensoes_arquivos."\n".
                        "Este campo é de preenchimento obrigatório!\n";
            break;                                                                   
            
            default:    $ajuda .= $conf->retornaDescricaoCampoTechnicalLocalizacao().": primeiro selecione o Formato do Objeto de Aprendizagem!\n";    break;
          }
      
          $util->campoHidden('ds_technical_location_original', $ds_location);

          $substituicao = '';
          echo "      <tr>\n";
          echo "		    <td class=\"celConteudoChamada\">".$conf->retornaNomeCampoTechnicalLocalizacao()."</td>\n";
          echo "		    <td class=\"celConteudoCampo\" id=\"celula_arquivo_o_a\">\n";
          if ($cd_format == '') {
            echo "          <input type=\"text\" maxlength=\"0\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"\" style=\"width:100%;\" alt=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao().$ajuda."\" title=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao().$ajuda."\" class=\"fontConteudoCampoTextHint\" placeholder=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao()."\" tabindex=\"1\" onFocus=\"alert('Selecione antes o formato do Objeto de Aprendizagem!');\" />\n";
            $util->campoHidden('eh_setado', '0');
          } elseif (($cd_format == '1') || ($cd_format == '2') || ($cd_format == '5') || ($cd_format == '6')) {
            echo "          <input type=\"text\" maxlength=\"250\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"".$ds_location."\" style=\"width:100%;\" alt=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao().$ajuda."\" title=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao().$ajuda."\" class=\"fontConteudoCampoTextHintObrigatorio\" placeholder=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao()."\" tabindex=\"1\" onBlur=\"validarOA();\" onChange=\"validarOA();\" />\n";
            $ajuda.= 'Campo do Tipo Texto com capacidade para até 250 caracteres';
            $util->campoHidden('eh_setado', '1');
          } elseif (($cd_format == '3') || ($cd_format == '4') || ($cd_format == '7')) {
            echo "          <input type=\"file\" maxlength=\"150\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"\" style=\"width:100%;\" alt=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao().$ajuda."\" title=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao().$ajuda."\" class=\"fontConteudoCampoTextHintObrigatorio\" placeholder=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao()."\" tabindex=\"1\" onBlur=\"validarOA();\" onChange=\"validarOA();\" />\n";
            if ($ds_location != '') {
              $substituicao = "\nPara alterar o arquivo atual, selecione novo Arquivo, ou deixe este campo em branco mantendo o existente!\n";
            }
            echo $arq_ext->retornaRelacaoExtensoesHidden($cd_format);
            $util->campoHidden('ds_technical_location_antigo', $ds_location);
            $util->campoHidden('eh_setado', '1');
            $ajuda.= 'Campo do Tipo Arquivo';
          }
          /*
          echo "          <a href=\"#\" class=\"dcontexto\">\n";
          echo "            <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
          echo "            <span class=\"fontdDetalhar\">\n";
          echo "              ".$ajuda."\n";
          echo "            </span>\n";
          echo "          </a>\n";
          */
          if ($substituicao != '') {
            echo $substituicao;
          }
          echo "        </td>\n";
          echo "      </tr>\n";   
          $util->campoHidden('nm_technical_location', $conf->retornaNomeCampoTechnicalLocalizacao());
        } else {
          $util->campoHidden('ds_technical_location', $ds_location);
        }
        if ($eh_informar_requirement == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_requirement, $conf->retornaDescricaoCampoTechnicalRequerimentos(), $conf->retornaNomeCampoTechnicalRequerimentos(), 'ds_technical_requirement', '250', '100', $ds_requirement, 1);
          $util->campoHidden('nm_technical_requirement', $conf->retornaNomeCampoTechnicalRequerimentos());
        } else {
          $util->campoHidden('ds_technical_requirement', $ds_requirement);
        }
        if ($eh_informar_composite == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_composite, $conf->retornaDescricaoCampoTechnicalComposicao(), $conf->retornaNomeCampoTechnicalComposicao(), 'ds_technical_composite', '250', '100', $ds_composite, 1);
          $util->campoHidden('nm_technical_composite', $conf->retornaNomeCampoTechnicalComposicao());
        } else {
          $util->campoHidden('ds_technical_composite', $ds_composite);
        }
        if ($eh_informar_installation_remarks == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_installation_remarks, $conf->retornaDescricaoCampoTechnicalObservacoesInstalacao(), $conf->retornaNomeCampoTechnicalObservacoesInstalacao(), 'ds_technical_installation_remarks', '250', '100', $ds_installation_remarks, 1);
          $util->campoHidden('nm_technical_installation_remarks', $conf->retornaNomeCampoTechnicalObservacoesInstalacao());
        } else {
          $util->campoHidden('ds_technical_installation_remarks', $ds_installation_remarks);
        }
        if ($eh_informar_other_plataforms_requirements == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_other_plataforms_requirements, $conf->retornaDescricaoCampoTechnicalOutrasPlataformasRequisitos(), $conf->retornaNomeCampoTechnicalOutrasPlataformasRequisitos(), 'ds_technical_other_plataforms_requirements', '250', '100', $ds_other_plataforms_requirements, 1);
          $util->campoHidden('nm_technical_other_plataforms_requirements', $conf->retornaNomeCampoTechnicalOutrasPlataformasRequisitos());
        } else {
          $util->campoHidden('ds_technical_other_plataforms_requirements', $ds_other_plataforms_requirements);
        }
        if ($eh_informar_duration == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_duration, $conf->retornaDescricaoCampoTechnicalDuracao(), $conf->retornaNomeCampoTechnicalDuracao(), 'ds_technical_duration', '9', '100', $ds_duration, 3);
          $util->campoHidden('nm_technical_duration', $conf->retornaNomeCampoTechnicalDuracao());
        } else {
          $util->campoHidden('ds_technical_duration', $ds_duration);
        }
        if ($eh_informar_supported_plataform == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_supported_plataform, $conf->retornaDescricaoCampoTechnicalPlataformaApoiado(), $conf->retornaNomeCampoTechnicalPlataformaApoiado(), 'ds_technical_supported_plataform', '250', '100', $ds_supported_plataform, 1);
          $util->campoHidden('nm_technical_supported_plataform', $conf->retornaNomeCampoTechnicalPlataformaApoiado());
        } else {
          $util->campoHidden('ds_technical_supported_plataform', $ds_supported_plataform);
        }
      } else {
        $util->campoHidden('cd_technical_format', $cd_format);
        $util->campoHidden('ds_technical_size', $ds_size);
        $util->campoHidden('ds_technical_location', $ds_location);
        $util->campoHidden('ds_technical_requirement', $ds_requirement);
        $util->campoHidden('ds_technical_composite', $ds_composite);
        $util->campoHidden('ds_technical_installation_remarks', $ds_installation_remarks);
        $util->campoHidden('ds_technical_other_plataforms_requirements', $ds_other_plataforms_requirements);
        $util->campoHidden('ds_technical_duration', $ds_duration);
        $util->campoHidden('ds_technical_supported_plataform', $ds_supported_plataform);
      }

      require_once 'conteudos/objetos_aprendizagem_technical_plataform_specific_features.php';
      $oa_psf = new ObjetoAprendizagemTechnicalPlataformSpecificFeatures();
      $oa_psf->retornaFormularioCadastroEdicao($cd_plataform_specific_features, $eh_informar_plataform_specific_features, $eh_manter_configuracoes_originais, $tipo);
    
      require_once 'conteudos/objetos_aprendizagem_technical_service.php';   
      $oa_ser = new ObjetoAprendizagemTechnicalService();
      $oa_ser->retornaFormularioCadastroEdicao($cd_service, $eh_informar_service, $eh_manter_configuracoes_originais, $tipo);
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                                        $util = new Utilitario();
      require_once 'conteudos/objetos_aprendizagem_technical_plataform_specific_features.php';        $oa_psf = new ObjetoAprendizagemTechnicalPlataformSpecificFeatures();
      require_once 'conteudos/objetos_aprendizagem_technical_service.php';                            $oa_ser = new ObjetoAprendizagemTechnicalService();
      require_once 'conteudos/formatos.php';                                                          $for = new Formato();

      $cd_technical = addslashes($_POST['cd_technical']);

      $cd_plataform_specific_features = $oa_psf->salvarCadastroAlteracao();
      $cd_service = $oa_ser->salvarCadastroAlteracao();

      $eh_informar_format = addslashes($_POST['eh_informar_technical_format']);
      $eh_informar_size = addslashes($_POST['eh_informar_technical_size']);
      $eh_informar_location = addslashes($_POST['eh_informar_technical_location']);
      $eh_informar_requirement = addslashes($_POST['eh_informar_technical_requirement']);
      $eh_informar_composite = addslashes($_POST['eh_informar_technical_composite']);
      $eh_informar_installation_remarks = addslashes($_POST['eh_informar_technical_installation_remarks']);
      $eh_informar_other_plataforms_requirements = addslashes($_POST['eh_informar_technical_other_plataforms_requirements']);
      $eh_informar_duration = addslashes($_POST['eh_informar_technical_duration']);
      $eh_informar_supported_plataform = addslashes($_POST['eh_informar_technical_supported_plataform']);
      $eh_informar_plataform_specific_features = addslashes($_POST['eh_informar_technical_plataform_specific_features']);
      $eh_informar_service = addslashes($_POST['eh_informar_technical_service']);
      $cd_format = $util->limparVariavel($_POST['cd_technical_format']);
      $ds_size = $util->limparVariavel($_POST['ds_technical_size']);
      $ds_requirement = $util->limparVariavel($_POST['ds_technical_requirement']);
      $ds_composite = $util->limparVariavel($_POST['ds_technical_composite']);
      $ds_installation_remarks = $util->limparVariavel($_POST['ds_technical_installation_remarks']);
      $ds_other_plataforms_requirements = $util->limparVariavel($_POST['ds_technical_other_plataforms_requirements']);
      $ds_duration = $util->limparVariavel($_POST['ds_technical_duration']);
      $ds_supported_plataform = $util->limparVariavel($_POST['ds_technical_supported_plataform']);

      $local = '';
      if (($cd_format == '1') || ($cd_format == '2') || ($cd_format == '5')) {
        $ds_location = $_POST['ds_technical_location'];
        $ds_location = str_replace("\"","'",$ds_location);
        $ds_location = addslashes($ds_location);
        $ds_location = str_replace("\\","",$ds_location);

        $local = $ds_location;
      } elseif ($cd_format == '6') {
        $ds_location = addslashes($_POST['ds_technical_location']);
        if (($ds_location[0] != 'h') || ($ds_location[1] != 't') || ($ds_location[2] != 't') || ($ds_location[3] != 'p')) {
          if (($ds_location[0] != 'w') || ($ds_location[1] != 'w') || ($ds_location[2] != 'w') || ($ds_location[3] != '.')) {
            $ds_location = 'http://www.'.$ds_location;
          } else {
            $ds_location = 'http://'.$ds_location;
          }
        }
        $local = $ds_location;
      } elseif (($cd_format == '3') || ($cd_format == '4') || ($cd_format == '7')) {
        if ($_FILES['ds_technical_location']['name'] != '') {
          require_once 'includes/upload_arquivos.php';                          $upl_arq = new UploadArquivo();
          require_once 'conteudos/tipos_arquivos.php';                          $tip_arq = new TipoArquivo();
          require_once 'conteudos/formatos.php';                                $for = new Formato();
          
          $formato = $for->selectDadosFormato($cd_format);
          $tipo = $tip_arq->selectDadosTipoArquivo($formato['cd_tipo_arquivo']);
          
          $ds_pasta = $tipo['nm_pasta'];
          $tp_origem_arquivo = $tipo['nm_tipo_arquivo'];          
          $campo = 'ds_technical_location';

          $retorno = $upl_arq->uploadArquivoGenerico($campo, $ds_pasta, $tp_origem_arquivo);
          if ($retorno[0] != '') {
            echo "<p class=\"fontConteudoAlerta\">".$retorno[0]."</p>\n";
            return false;
          } else {
            $ds_location = $ds_pasta."/".$retorno[1];
          }
        } else {
          if (isset($_POST['ds_technical_location_antigo'])) {
            $ds_location = $util->limparVariavel($_POST['ds_technical_location_antigo']);
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas no Cadastro! Nenhum arquivo anexado!</p>\n";
            $ds_location = '';
            return false;
          }
        }
      }
      $dados = $for->selectDadosFormato($cd_format);
      $formato = $dados['nm_formato'];

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$formato." | ".$ds_size." | ".$ds_requirement." | ".$ds_composite." | ".$ds_installation_remarks." | ".$ds_other_plataforms_requirements." | ".$ds_duration." | ".$ds_supported_plataform." | ".$local;

      if ($cd_technical > 0) {
        $cd_technical = $this->alteraTechnical($cd_technical, $cd_format, $eh_informar_format, $ds_size, $eh_informar_size, $ds_location, $eh_informar_location, $ds_requirement, $eh_informar_requirement, $ds_composite, $eh_informar_composite, $ds_installation_remarks, $eh_informar_installation_remarks, $ds_other_plataforms_requirements, $eh_informar_other_plataforms_requirements, $ds_duration, $eh_informar_duration, $ds_supported_plataform, $eh_informar_supported_plataform, $cd_plataform_specific_features, $eh_informar_plataform_specific_features, $cd_service, $eh_informar_service);
        if (($cd_plataform_specific_features > 0) || ($cd_service > 0) || ($cd_technical > 0)) {
          return '1';
        } else {
          return '0';
        }            
      } else {
        if (($cd_plataform_specific_features > 0) && ($cd_service > 0)) { 
          return $this->insereTechnical($cd_format, $eh_informar_format, $ds_size, $eh_informar_size, $ds_location, $eh_informar_location, $ds_requirement, $eh_informar_requirement, $ds_composite, $eh_informar_composite, $ds_installation_remarks, $eh_informar_installation_remarks, $ds_other_plataforms_requirements, $eh_informar_other_plataforms_requirements, $ds_duration, $eh_informar_duration, $ds_supported_plataform, $eh_informar_supported_plataform, $cd_plataform_specific_features, $eh_informar_plataform_specific_features, $cd_service, $eh_informar_service);
        } else {
          return '0';
        }
      }

    }

    public function imprimeDados($cd_technical, $eh_exibir_conteudo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnical($cd_technical);

      $eh_informar_plataform_specific_features = $dados['eh_informar_plataform_specific_features'];
      $eh_informar_service = $dados['eh_informar_service'];
      $eh_informar_format = '1'; //$dados['eh_informar_format'];
      $eh_informar_size = $dados['eh_informar_size'];
      $eh_informar_location = '1'; //$dados['eh_informar_location'];
      $eh_informar_requirement = $dados['eh_informar_requirement'];
      $eh_informar_composite = $dados['eh_informar_composite'];
      $eh_informar_installation_remarks = $dados['eh_informar_installation_remarks'];
      $eh_informar_other_plataforms_requirements = $dados['eh_informar_other_plataforms_requirements'];
      $eh_informar_duration = $dados['eh_informar_duration'];
      $eh_informar_supported_plataform = $dados['eh_informar_supported_plataform'];

      $cd_format = $dados['cd_format'];
      $ds_size = $dados['ds_size'];
      $ds_location = $dados['ds_location'];
      $ds_requirement = $dados['ds_requirement'];
      $ds_composite = $dados['ds_composite'];
      $ds_installation_remarks = $dados['ds_installation_remarks'];
      $ds_other_plataforms_requirements = $dados['ds_other_plataforms_requirements'];
      $ds_duration = $dados['ds_duration'];
      $ds_supported_plataform = $dados['ds_supported_plataform'];
      $cd_plataform_specific_features = $dados['cd_plataform_specific_features'];
      $cd_service = $dados['cd_service'];

      $retorno = '';

      if (($eh_informar_location == '1') && ($eh_exibir_conteudo)) {
        switch ($cd_format) {
          case "1":
            //audio
            echo "<div class=\"divElementosAudioObjetoAprendizagem\">\n";
            echo "  <iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"".$ds_location."\"></iframe>\n";
            echo "  <br />\n";
            echo "  <p class=\"fontDownloadObjetosAprendizagem\">\n";
            echo "    Você também pode acessar ";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\">aqui</a> ";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/download_audio.png\" alt=\"Acessar Objeto de Aprendizagem de áudio\" title=\"Acessar Objeto de Aprendizagem de áudio\" border=\"0\"></a>";
            echo "  </p>\n";
            echo "</div>\n";
          break;

          case "2":
            //video
            echo "<div class=\"divElementosVideoObjetoAprendizagem\">\n";
            echo "  <iframe width=\"100%\" height=\"450px\" src=\"".$ds_location."\" frameborder=\"0\" allowfullscreen></iframe>\n";
            echo "  <br />\n";
            echo "  <p class=\"fontDownloadObjetosAprendizagem\">\n";
            echo "    Você também pode acessar ";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\">aqui</a> ";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/download_video.png\" alt=\"Acessar Objeto de Aprendizagem de vídeo\" title=\"Acessar Objeto de Aprendizagem de vídeo\" border=\"0\"></a>";
            echo "  </p>\n";
            echo "</div>\n";
          break;

          case "3":
            //texto
            echo "<div class=\"divElementosDownloadObjetoAprendizagem\">\n";
            echo "  <p class=\"fontDownloadObjetosAprendizagem\">\n";
            echo "    Realize o download do material clicando  ";
            echo "    <a href=\"".$_SESSION['life_link_completo'].$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\">aqui</a><br /><br />";
            echo "    <a href=\"".$_SESSION['life_link_completo'].$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/download_texto.png\" alt=\"Acessar Objeto de Aprendizagem de texto/planilha\" title=\"Acessar Objeto de Aprendizagem de texto/planilha\" border=\"0\"></a>";
            echo "  </p>\n";
            echo "</div>\n";
          break;

          case "4":
            //imagem
            echo "<div class=\"divElementosImagemObjetoAprendizagem\">\n";
            echo "  <img src=\"".$_SESSION['life_link_completo'].$ds_location."\" width=\"100%\" alt=\"Objeto de Aprendizagem de imagem\" title=\"Objeto de Aprendizagem de imagem\" border=\"0\">\n";
            echo "  <br />\n";
            echo "  <p class=\"fontDownloadObjetosAprendizagem\">\n";
            echo "    Você também pode acessar ";
            echo "    <a href=\"".$_SESSION['life_link_completo'].$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\">aqui</a> ";
            echo "    <a href=\"".$_SESSION['life_link_completo'].$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/download_imagem.png\" alt=\"Acessar Objeto de Aprendizagem de imagem\" title=\"Acessar Objeto de Aprendizagem de imagem\" border=\"0\"></a>";
            echo "  </p>\n";
            echo "</div>\n";
          break;

          case "5":
            //aplicativos
            echo "<div class=\"divElementosDownloadObjetoAprendizagem\">\n";
            echo "  <p class=\"fontDownloadObjetosAprendizagem\">\n";
            echo "    Realize o download do aplicativo clicando  ";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\">aqui</a><br /><br />";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/download_aplicativo.png\" alt=\"Acessar o download do aplicativo do Objeto de Aprendizagem\" title=\"Acessar o download do aplicativo do Objeto de Aprendizagem\" border=\"0\"></a>";
            echo "  </p>\n";
            echo "</div>\n";
          break;

          case "6":
            //externo
            echo "<div class=\"divElementosDownloadObjetoAprendizagem\">\n";
            echo "  <p class=\"fontDownloadObjetosAprendizagem\">\n";
            echo "    Acesse o link externo clicando  ";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\">aqui</a><br /><br />";
            echo "    <a href=\"".$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/download_link.png\" alt=\"Acessar o link externo do Objeto de Aprendizagem\" title=\"Acessar o link externo do Objeto de Aprendizagem\" border=\"0\"></a>";
            echo "  </p>\n";
            echo "</div>\n";
          break;

          case "7":
            //apresentacao
            echo "<div class=\"divElementosDownloadObjetoAprendizagem\">\n";
            echo "  <p class=\"fontDownloadObjetosAprendizagem\">\n";
            echo "    Realize o download da apresentação clicando  ";
            echo "    <a href=\"".$_SESSION['life_link_completo'].$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\">aqui</a><br /><br />";
            echo "    <a href=\"".$_SESSION['life_link_completo'].$ds_location."\" target=\"_blank\" class=\"fontLinkDownloadObjetosAprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/download_apresentacao.png\" alt=\"Acessar a apresentação do Objeto de Aprendizagem\" title=\"Acessar a apresentação do Objeto de Aprendizagem\" border=\"0\"></a>";
            echo "  </p>\n";
            echo "</div>\n";
          break;
        }
      }


      $retorno.= "<div class=\"divConteudoUnicoObjetoAprendizagem\">\n";
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= "<b>Informações Técnicas</b>";
      $retorno.= "</p>\n";

      if ($eh_informar_format == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        require_once 'conteudos/formatos.php';                                $for = new Formato();
        $retorno.= $for->imprimeDados($cd_format, $conf->retornaInformacaoCampoTechnicalFormato());
        $retorno.= "</p>\n";
      }
      if ($eh_informar_size == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalTamanho()."</b> ".$ds_size;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_requirement == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalRequerimentos()."</b> ".$ds_requirement;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_composite == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalComposicao()."</b> ".$ds_composite;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_installation_remarks == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalObservacoesInstalacao()."</b> ".$ds_installation_remarks;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_other_plataforms_requirements == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalOutrasPlataformasRequisitos()."</b> ".$ds_other_plataforms_requirements;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_duration == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalDuracao()."</b> ".$ds_duration;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_supported_plataform == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalPlataformaApoiado()."</b> ".$ds_supported_plataform;
        $retorno.= "</p>\n";
      }

      if ($eh_informar_plataform_specific_features == '1') {
        $retorno.= "<div class=\"divConteudoCentralObjetoAprendizagem\">\n";
        require_once 'conteudos/objetos_aprendizagem_technical_plataform_specific_features.php';
        $oa_psf = new ObjetoAprendizagemTechnicalPlataformSpecificFeatures();
        $retorno.= $oa_psf->imprimeDados($cd_plataform_specific_features);
        $retorno.= "</div>\n";
      }
    
      if ($eh_informar_service == '1') {
        $retorno.= "<div class=\"divConteudoCentralObjetoAprendizagem\">\n";
        require_once 'conteudos/objetos_aprendizagem_technical_service.php';
        $oa_ser = new ObjetoAprendizagemTechnicalService();
        $retorno.= $oa_ser->imprimeDados($cd_service);
        $retorno.= "</div>\n";
      }
      $retorno.= "<div class=\"clear\"></div>\n";
      $retorno.= "</div>\n";
      return $retorno;
    }

    public function imprimeDadosRetornoPesquisa($cd_technical, $tipo) {
     require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnical($cd_technical);

      $eh_informar_plataform_specific_features = $conf->retornaInformarTechnicalPlataformSpecificFeatures($tipo);
      $eh_informar_service = $conf->retornaInformarTechnicalService($tipo);

      $eh_informar_format = '1'; //$conf->retornaInformarTechnicalFormat($tipo);
      $eh_informar_size = $conf->retornaInformarTechnicalSize($tipo);
      $eh_informar_location = '1'; //$conf->retornaInformarTechnicalLocation($tipo);
      $eh_informar_requirement = $conf->retornaInformarTechnicalRequirement($tipo);
      $eh_informar_composite = $conf->retornaInformarTechnicalComposite($tipo);
      $eh_informar_installation_remarks = $conf->retornaInformarTechnicalInstallationRemarks($tipo);
      $eh_informar_other_plataforms_requirements = $conf->retornaInformarTechnicalOtherPlataformsRequirements($tipo);
      $eh_informar_duration = $conf->retornaInformarTechnicalDuration($tipo);
      $eh_informar_supported_plataform = $conf->retornaInformarTechnicalSupportedPlataform($tipo);

      $cd_format = $dados['cd_format'];
      $ds_size = $dados['ds_size'];
      $ds_location = $dados['ds_location'];
      $ds_requirement = $dados['ds_requirement'];
      $ds_composite = $dados['ds_composite'];
      $ds_installation_remarks = $dados['ds_installation_remarks'];
      $ds_other_plataforms_requirements = $dados['ds_other_plataforms_requirements'];
      $ds_duration = $dados['ds_duration'];
      $ds_supported_plataform = $dados['ds_supported_plataform'];
      $cd_plataform_specific_features = $dados['cd_plataform_specific_features'];
      $cd_service = $dados['cd_service'];

      $retorno = '';

      if ($eh_informar_format == '1') {
        require_once 'conteudos/formatos.php';                                $for = new Formato();
        $retorno.= $for->imprimeDados($cd_format, $conf->retornaInformacaoCampoTechnicalFormato())."<br />\n";
      }
      if ($eh_informar_size == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalTamanho()."</b> ".$ds_size."<br />\n";
      }
      if ($eh_informar_requirement == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalRequerimentos()."</b> ".$ds_requirement."<br />\n";
      }
      if ($eh_informar_composite == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalComposicao()."</b> ".$ds_composite."<br />\n";
      }
      if ($eh_informar_installation_remarks == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalObservacoesInstalacao()."</b> ".$ds_installation_remarks."<br />\n";
      }
      if ($eh_informar_other_plataforms_requirements == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalOutrasPlataformasRequisitos()."</b> ".$ds_other_plataforms_requirements."<br />\n";
      }
      if ($eh_informar_duration == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalDuracao()."</b> ".$ds_duration."<br />\n";
      }
      if ($eh_informar_supported_plataform == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalPlataformaApoiado()."</b> ".$ds_supported_plataform."<br />\n";
      }
      if ($eh_informar_plataform_specific_features == '1') {
        require_once 'conteudos/objetos_aprendizagem_technical_plataform_specific_features.php';        $oa_psf = new ObjetoAprendizagemTechnicalPlataformSpecificFeatures();
        $retorno.= $oa_psf->imprimeDadosRetornoPesquisa($cd_plataform_specific_features, $tipo);
      }
      if ($eh_informar_service == '1') {
        require_once 'conteudos/objetos_aprendizagem_technical_service.php';                            $oa_ser = new ObjetoAprendizagemTechnicalService();
        $retorno.= $oa_ser->imprimeDadosRetornoPesquisa($cd_service, $tipo);
      }
      return $retorno;
    }

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemTechnical($cd_technical) {
      $sql  = "SELECT * ".
              "FROM life_technical ".
              "WHERE cd_technical = '$cd_technical' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA TECHNICAL!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
                                    
    public function insereTechnical($cd_format, $eh_informar_format, $ds_size, $eh_informar_size, $ds_location, $eh_informar_location, $ds_requirement, $eh_informar_requirement, $ds_composite, $eh_informar_composite, $ds_installation_remarks, $eh_informar_installation_remarks, $ds_other_plataforms_requirements, $eh_informar_other_plataforms_requirements, $ds_duration, $eh_informar_duration, $ds_supported_plataform, $eh_informar_supported_plataform, $cd_plataform_specific_features, $eh_informar_plataform_specific_features, $cd_service, $eh_informar_service) {
      $sql = "INSERT INTO life_technical ".
             "(cd_format, eh_informar_format, ds_size, eh_informar_size, ds_location, eh_informar_location, ds_requirement, eh_informar_requirement, ds_composite, eh_informar_composite, ds_installation_remarks, eh_informar_installation_remarks, ds_other_plataforms_requirements, eh_informar_other_plataforms_requirements, ds_duration, eh_informar_duration, ds_supported_plataform, eh_informar_supported_plataform, cd_plataform_specific_features, eh_informar_plataform_specific_features, cd_service, eh_informar_service) ".
             "VALUES ".
             "(\"$cd_format\", \"$eh_informar_format\", \"$ds_size\", \"$eh_informar_size\", \"$ds_location\", \"$eh_informar_location\", \"$ds_requirement\", \"$eh_informar_requirement\", \"$ds_composite\", \"$eh_informar_composite\", \"$ds_installation_remarks\", \"$eh_informar_installation_remarks\", \"$ds_other_plataforms_requirements\", \"$eh_informar_other_plataforms_requirements\", \"$ds_duration\", \"$eh_informar_duration\", \"$ds_supported_plataform\", \"$eh_informar_supported_plataform\", \"$cd_plataform_specific_features\", \"$eh_informar_plataform_specific_features\", \"$cd_service\", \"$eh_informar_service\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'technical');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA TECHNICAL!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_technical) codigo ".
                "FROM life_technical ".
                "WHERE cd_format = '$cd_format' ".
                "AND ds_location = '$ds_location' ".
                "AND ds_requirement = '$ds_requirement' ".
                "AND ds_composite = '$ds_composite' ".
                "AND ds_installation_remarks = '$ds_installation_remarks' ".
                "AND ds_other_plataforms_requirements = '$ds_other_plataforms_requirements' ".
                "AND ds_supported_plataform = '$ds_supported_plataform' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA TECHNICAL!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraTechnical($cd_technical, $cd_format, $eh_informar_format, $ds_size, $eh_informar_size, $ds_location, $eh_informar_location, $ds_requirement, $eh_informar_requirement, $ds_composite, $eh_informar_composite, $ds_installation_remarks, $eh_informar_installation_remarks, $ds_other_plataforms_requirements, $eh_informar_other_plataforms_requirements, $ds_duration, $eh_informar_duration, $ds_supported_plataform, $eh_informar_supported_plataform, $cd_plataform_specific_features, $eh_informar_plataform_specific_features, $cd_service, $eh_informar_service) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_technical SET ".
             "cd_format = \"$cd_format\", ".
             "eh_informar_format = \"$eh_informar_format\", ".
             "ds_size = \"$ds_size\", ".
             "eh_informar_size = \"$eh_informar_size\", ".
             "ds_location = \"$ds_location\", ".
             "eh_informar_location = \"$eh_informar_location\", ".
             "ds_requirement = \"$ds_requirement\", ".
             "eh_informar_requirement = \"$eh_informar_requirement\", ".
             "ds_composite = \"$ds_composite\", ".
             "eh_informar_composite = \"$eh_informar_composite\", ".
             "ds_installation_remarks = \"$ds_installation_remarks\", ".
             "eh_informar_installation_remarks = \"$eh_informar_installation_remarks\", ".
             "ds_other_plataforms_requirements = \"$ds_other_plataforms_requirements\", ".
             "eh_informar_other_plataforms_requirements = \"$eh_informar_other_plataforms_requirements\", ".
             "ds_duration = \"$ds_duration\", ".
             "eh_informar_duration = \"$eh_informar_duration\", ".
             "ds_supported_plataform = \"$ds_supported_plataform\", ".
             "eh_informar_supported_plataform = \"$eh_informar_supported_plataform\", ".
             "eh_informar_plataform_specific_features = \"$eh_informar_plataform_specific_features\", ".
             "eh_informar_service = \"$eh_informar_service\" ".
             "WHERE cd_technical = '$cd_technical' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'technical');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA TECHNICAL!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>