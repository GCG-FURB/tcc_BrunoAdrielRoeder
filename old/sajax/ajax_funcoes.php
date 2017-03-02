<?php

  function incluirAreaConhecimentoRelacao($cd_area_conhecimento) {
    require_once 'conteudos/areas_conhecimento.php';                            $are_con = new AreaConhecimento();
    require_once 'includes/configuracoes.php';                                  $conf = new Configuracao();

    if (!isset($_SESSION['life_codigo_areas_conhecimento_capa'])) {
      $_SESSION['life_codigo_areas_conhecimento_capa'] = array();
    }
    $achou = false;
    $termo = "<p class=\"fontChamadaRelacaoAreaConhecimento\">";
    $tamanho = 0;
    $limite = $conf->retornaNumeroLimiteCaracteresRelacaoChamadaAreasConhecimentoCapaSite();
    $concatenar = true;
    foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
      if ($it == $cd_area_conhecimento) {
        $achou = true;
      } 
    }

    if (!$achou) {
      $itens = $_SESSION['life_codigo_areas_conhecimento_capa'];
      unset($_SESSION['life_codigo_areas_conhecimento_capa']);
      $_SESSION['life_codigo_areas_conhecimento_capa'] = array();
      $_SESSION['life_codigo_areas_conhecimento_capa'][] = $cd_area_conhecimento;
      foreach ($itens as $it) {
        $_SESSION['life_codigo_areas_conhecimento_capa'][] = $it;
      }
    }
    
    foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
      $dados = $are_con->selectDadosAreaConhecimento($it);
      $tamanho_nome = strlen($dados['nm_area_conhecimento']);
      $tamanho += $tamanho_nome + 6;
      if ($tamanho < $limite) {
        $termo.= $dados['nm_area_conhecimento']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Retirar a Área de Conhecimento ".$dados['nm_area_conhecimento']." da Relação de Itens à serem Pesquisados\" title=\"Retirar a Área de Conhecimento ".$dados['nm_area_conhecimento']." da Relação de Itens à serem Pesquisados\">[x]</a>&nbsp;&nbsp;";
      } else {     
        if ($concatenar) {
          $termo.= "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Exibir Relação Completa de Itens à serem Pesquisados\" title=\"Exibir Relação Completa de Itens à serem Pesquisados\">[+]</a> ";
          $concatenar = false;
        }
      }
    }                 
    $termo.= "</p>";
    
    return utf8_encode($termo);
  }
  
  function retirarRelacao($cd_area_conhecimento) {
    require_once 'conteudos/areas_conhecimento.php';                            $are_con = new AreaConhecimento();
    require_once 'includes/configuracoes.php';                                  $conf = new Configuracao();

    if (!isset($_SESSION['life_codigo_areas_conhecimento_capa'])) {
      $_SESSION['life_codigo_areas_conhecimento_capa'] = array();
    }
    $termo = "<p class=\"fontChamadaRelacaoAreaConhecimento\">";
    $tamanho = 0;
    $limite = $conf->retornaNumeroLimiteCaracteresRelacaoChamadaAreasConhecimentoCapaSite();
    $concatenar = true;
    $itens = $_SESSION['life_codigo_areas_conhecimento_capa'];
    unset($_SESSION['life_codigo_areas_conhecimento_capa']);
    $_SESSION['life_codigo_areas_conhecimento_capa'] = array();
    foreach ($itens as $it) {
      if ($it != $cd_area_conhecimento) {
        $_SESSION['life_codigo_areas_conhecimento_capa'][] = $it;
        $dados = $are_con->selectDadosAreaConhecimento($it);
        $tamanho_nome = strlen($dados['nm_area_conhecimento']);
        $tamanho += $tamanho_nome + 6;
        if ($tamanho < $limite) {
          $termo.= $dados['nm_area_conhecimento']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Retirar a Área de Conhecimento ".$dados['nm_area_conhecimento']." da Relação de Itens à serem Pesquisados\" title=\"Retirar a Área de Conhecimento ".$dados['nm_area_conhecimento']." da Relação de Itens à serem Pesquisados\">[x]</a>&nbsp;&nbsp;";
        } else {     
          if ($concatenar) {
            $termo.= "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Exibir Relação Completa de Itens à serem Pesquisados\" title=\"Exibir Relação Completa de Itens à serem Pesquisados\">[+]</a> ";
            $concatenar = false;
          }
        }
      }
    }
    $termo.= "</p>";
    
    return utf8_encode($termo);
  }
  
  function atualizarCampoTipoArquivo($cd_formato, $cd_formato_antigo, $eh_setado, $ds_location) {
    require_once 'includes/configuracoes.php';                                  $conf = new Configuracao();

    $ajuda = "\n\n";
    $retorno = "";
    switch ($cd_formato) {
      case "1":
        $ds_sugestao_locais_audio = $conf->retornaSugestaoLocaisAudio();
        if ($eh_setado == '1') {   
          $ajuda .= "Para alterar o Link de arquivo de Áudio atual, informe novo link de Áudio!\n";
        } else {
          $ajuda .= "Informe link de Áudio!\n";
        }
        $ajuda.= nl2br($ds_sugestao_locais_audio)."\n".
                 "Este campo é de preenchimento obrigatório!\n";
      break;   

      case "2":   
        $ds_sugestao_locais_video = $conf->retornaSugestaoLocaisVideo();
        if ($eh_setado == '1') {   
          $ajuda .= "Para alterar o Link de arquivo de Vídeo atual, informe novo link de Vídeo!\n";
        } else {
          $ajuda .= "Informe link de Vídeo!\n";
        }
        $ajuda .= nl2br($ds_sugestao_locais_video)."\n".
                  "Este campo é de preenchimento obrigatório!\n";
      break;
            
      case "3":                     
        require_once 'conteudos/arquivos_extensao.php';                         $arq_ext = new ArquivoExtensao();
        $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
        $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_formato);
        if ($eh_setado == '1') {   
          $ajuda .= "Para alterar o arquivo de Texto, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n";
        } else {
          $ajuda .= "Selecione Arquivo de Texto!\n";
        }
        $ajuda .= "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                  "\nFormatos: \n".$ds_extensoes_arquivos."\n".
                  "Este campo é de preenchimento obrigatório!\n";
      break;
              
      case "4":
        require_once 'conteudos/arquivos_extensao.php';                         $arq_ext = new ArquivoExtensao();
        $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
        $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_formato);
        if ($eh_setado == '1') {
          $ajuda .= "Para alterar o arquivo de Imagem, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n";
        } else {
          $ajuda .= "Selecione Arquivo de Imagem!\n";
        }
        $ajuda .= "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                  "\nFormatos: \n".$ds_extensoes_arquivos."\n".
                  "Este campo é de preenchimento obrigatório!\n";
      break;
      

      case "5":
/*
        require_once 'conteudos/arquivos_extensao.php';                         $arq_ext = new ArquivoExtensao();
        $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
        $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_formato);
        if ($eh_setado == '1') {
          $ajuda .= "Para alterar o arquivo do Aplicativo, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n";
        } else {
          $ajuda .= "Selecione Arquivo de Aplicativo!\n";
        }
        $ajuda .= "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                  "Formatos: \n".$ds_extensoes_arquivos."\n".
                  "Este campo é de preenchimento obrigatório!\n";
*/
        $ds_sugestao_locais_aplicativos = $conf->retornaSugestaoLocaisAplicativos();
        if ($eh_setado == '1') {
          $ajuda .= "Para alterar o Link de arquivo do Aplicativo atual, informe novo link do Aplicativo!\n";
        } else {
          $ajuda .= "Informe link do arquivo do Aplicativo!\n";
        }
        $ajuda.= nl2br($ds_sugestao_locais_aplicativos)."\n".
                 "Este campo é de preenchimento obrigatório!\n";
      break;
            
      case "6":   
        if ($eh_setado == '1') {
          $ajuda .= "Para alterar o Link Externo atual, informe novo link externo!\n";
        } else {
          $ajuda .= "Informe Link Externo!\n";
        }
        $ajuda .= "Este campo é de preenchimento obrigatório!\n";
      break;
            
      case "7":
        require_once 'conteudos/arquivos_extensao.php';                         $arq_ext = new ArquivoExtensao();
        $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
        $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_formato);
        if ($eh_setado == '1') {
          $ajuda .= "Para alterar o arquivo de Apresentação, selecione novo Arquivo, ou deixe este campo em branco para manter o arquivo atual!\n";
        } else {
          $ajuda .= "Selecione o arquivo de Apresentação!\n";
        }
        $ajuda .= "São aceitos arquivos nos formatos abaixo descritos, considerando limite de tamanho de cada arquivo em ".$ds_limite_tamanho_arquivos." MB.\n".
                  "\nFormatos: \n".$ds_extensoes_arquivos."\n".
                  "Este campo é de preenchimento obrigatório!\n";
      break;                                                                         
    }
      
    $retorno.= "          <input type=\"hidden\" name=\"eh_setado\" id=\"eh_setado\" value=\"eh_setado\" />\n";
      
    $substituicao = "";
    if ($cd_formato == '0') {
      $retorno.= "          <input type=\"text\" maxlength=\"0\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"\" style=\"width:100%;\" alt=\"Localização do Arquivo contendo o Objeto de Aprendizagem".$ajuda."\" title=\"Localização do Arquivo contendo o Objeto de Aprendizagem".$ajuda."\" class=\"fontConteudoCampoTextHint\" placeholder=\"Localização\" tabindex=\"1\" onFocus=\"alert('Selecione antes o formato do Objeto de Aprendizagem!');\" />\n";
    } elseif (($cd_formato == '1') || ($cd_formato == '2') || ($cd_formato == '5') || ($cd_formato == '6')) {
      $ajuda.= 'Campo do Tipo Texto com capacidade para até 250 caracteres';
      $retorno.= "          <input type=\"text\" maxlength=\"250\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"".$ds_location."\" style=\"width:100%;\" alt=\"Localização do Arquivo contendo o Objeto de Aprendizagem".$ajuda."\" title=\"Localização do Arquivo contendo o Objeto de Aprendizagem".$ajuda."\" class=\"fontConteudoCampoTextHintObrigatorio\" placeholder=\"Localização\" tabindex=\"1\" onBlur=\"validarOA();\" onChange=\"validarOA();\" />\n";
    } elseif (($cd_formato == '3') || ($cd_formato == '4') || ($cd_formato == '7')) {
      $retorno.= "          <input type=\"file\" maxlength=\"150\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"\" style=\"width:100%;\" alt=\"Localização do Arquivo contendo o Objeto de Aprendizagem".$ajuda."\" title=\"Localização do Arquivo contendo o Objeto de Aprendizagem".$ajuda."\" class=\"fontConteudoCampoTextHintObrigatorio\" placeholder=\"Localização\" tabindex=\"1\" onBlur=\"validarOA();\" onChange=\"validarOA();\" />\n";
      if ($cd_formato == $cd_formato_antigo) {
        if ($ds_location != '') {
          $substituicao = "<br />Para alterar o arquivo atual, selecione novo Arquivo, ou deixe este campo em branco mantendo o existente!\n";
        }
        $retorno.= "          <input type=\"hidden\" name=\"ds_technical_location_antigo\" id=\"ds_technical_location_antigo\" value=\"".$ds_location."\" />\n";
      } else {
        $retorno.= "          <input type=\"hidden\" name=\"ds_technical_location_antigo\" id=\"ds_technical_location_antigo\" value=\"\" />\n";
      }
      $retorno.= $arq_ext->retornaRelacaoExtensoesHidden($cd_formato);
      $ajuda.= 'Campo do Tipo Arquivo';
    }
/*
    $retorno.= "          <a href=\"#\" class=\"dcontexto\">\n";
    $retorno.= "            <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
    $retorno.= "            <span class=\"fontdDetalhar\">\n";
    $retorno.= "              ".$ajuda."\n";
    $retorno.= "            </span>\n";
    $retorno.= "          </a>\n";
*/
    if ($substituicao != '') {
      $retorno.= $substituicao;
    }
  
      
    return utf8_encode($retorno);  
  }
  
  function atualizarCampoSubAreasConhecimento($cd_general, $cd_area_conhecimento) {
    require_once 'conteudos/sub_areas_conhecimento.php';                        $sac = new SubAreaConhecimento();
    
    $retorno = $sac->retornaCadastroSubAreasConhecimentoObjetoAprendizagem($cd_area_conhecimento, '100', $cd_general);
   
    return utf8_encode($retorno);
  }
  
  function detalharDadosObjetoAprendizagem($cd_objeto_aprendizagem) {
    require_once 'conteudos/objetos_aprendizagem.php';                          $oa = new ObjetoAprendizagem();
    
    $retorno = $oa->retornaInformacoesCompletasObjetoAprendizagem($cd_objeto_aprendizagem);
    
    return utf8_encode($retorno);
  }

	function buscaCidades($cd_estado) {
    require_once 'conteudos/cidades.php';                                       $cid = new Cidade();
    $retorno = $cid->apresentaSeletorCidadesEstado($cd_estado, '');
    return utf8_encode($retorno);
  }


  sajax_init();
	sajax_export("incluirAreaConhecimentoRelacao");
	sajax_export("retirarRelacao");
  sajax_export("atualizarCampoTipoArquivo");
  sajax_export("atualizarCampoSubAreasConhecimento");
  sajax_export("detalharDadosObjetoAprendizagem");
  sajax_export("buscaCidades");
	sajax_handle_client_request();
?>