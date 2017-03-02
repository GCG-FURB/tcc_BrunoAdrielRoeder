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
        $termo.= $dados['nm_area_conhecimento']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Retirar a área de conhecimento ".$dados['nm_area_conhecimento']." da relação de itens à serem pesquisados\" title=\"Retirar a área de conhecimento ".$dados['nm_area_conhecimento']." da relação de itens à serem pesquisados\">[x]</a>&nbsp;&nbsp;";
      } else {     
        if ($concatenar) {
          $termo.= "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Exibir relação completa de itens à serem pesquisados\" title=\"Exibir relação completa de itens à serem pesquisados\">[+]</a> ";
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
          $termo.= $dados['nm_area_conhecimento']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Retirar a área de conhecimento ".$dados['nm_area_conhecimento']." da relação de itens à serem pesquisados\" title=\"Retirar a área de conhecimento ".$dados['nm_area_conhecimento']." da relação de itens à serem pesquisados\">[x]</a>&nbsp;&nbsp;";
        } else {     
          if ($concatenar) {
            $termo.= "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoAreaConhecimentoLink\" alt=\"Exibir relação completa de itens à serem pesquisados\" title=\"Exibir relação completa de itens à serem pesquisados\">[+]</a> ";
            $concatenar = false;
          }
        }
      }
    }
    $termo.= "</p>";
    
    return utf8_encode($termo);
  }
  
  function atualizarCampoTipoArquivo($cd_formato_objeto, $cd_formato_antigo, $eh_setado, $ds_location) {
    require_once 'includes/configuracoes.php';                                  $conf = new Configuracao();
    require_once 'conteudos/formatos_objetos.php';                              $for = new FormatoObjeto();
    $dados = $for->selectDadosFormatoObjeto($cd_formato_objeto);
    $cd_formato = $dados['cd_formato'];

    $ajuda = $conf->retornaDescricaoCampoTechnicalLocalizacao()."\n\n";
    $retorno = "";
    $retorno.= "          <input type=\"hidden\" name=\"cd_formato_primario\" id=\"cd_formato_primario\" value=\"".$dados['cd_formato']."\" />\n";
    switch ($cd_formato) {
      case "1":
        //$ds_sugestao_locais_audio = $conf->retornaSugestaoLocaisAudio();
        if ($eh_setado == '1') {   
          $ajuda .= "Informe a nova localização (endereço / link) do ".$dados['nm_formato_objeto'].".\n";
        } else {
          $ajuda .= "Informe a localização (endereço / link) do ".$dados['nm_formato_objeto'].".\n";
        }
        //$ajuda.= nl2br($ds_sugestao_locais_audio)."\n".
        //         "Este campo é de preenchimento obrigatório!\n";
      break;   

      case "2":   
        //$ds_sugestao_locais_video = $conf->retornaSugestaoLocaisVideo();
        if ($eh_setado == '1') {   
          $ajuda .= "Informe a nova localização (endereço / link) do ".$dados['nm_formato_objeto'].".\n";
        } else {
          $ajuda .= "Informe a localização (endereço / link) do ".$dados['nm_formato_objeto'].".\n";
        }
        //$ajuda .= nl2br($ds_sugestao_locais_video)."\n".
        //          "Este campo é de preenchimento obrigatório!\n";
      break;
            
      case "3":                     
        require_once 'conteudos/arquivos_extensao.php';                         $arq_ext = new ArquivoExtensao();
        $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
        $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_formato);
        if ($eh_setado == '1') {   
          $ajuda .= "Selecione o novo arquivo com ".$dados['nm_formato_objeto'].".";
        } else {
          $ajuda .= "Selecione o arquivo com ".$dados['nm_formato_objeto'].".";
        }
        $ajuda .= "São aceitos arquivos nos seguintes formatos, com tamanho máximo de ".$ds_limite_tamanho_arquivos." MB:\n".
                  $ds_extensoes_arquivos."\n";
      break;
              
      case "4":
        require_once 'conteudos/arquivos_extensao.php';                         $arq_ext = new ArquivoExtensao();
        $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
        $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_formato);
        if ($eh_setado == '1') {
          $ajuda .= "Selecione o novo arquivo com ".$dados['nm_formato_objeto'].".";
        } else {
          $ajuda .= "Selecione o arquivo com ".$dados['nm_formato_objeto'].".";
        }
        $ajuda .= "São aceitos arquivos nos seguintes formatos, com tamanho máximo de ".$ds_limite_tamanho_arquivos." MB:\n".
                  $ds_extensoes_arquivos."\n";
      break;
      

      case "5":
        //$ds_sugestao_locais_aplicativos = $conf->retornaSugestaoLocaisAplicativos();
        if ($eh_setado == '1') {
          $ajuda .= "Informe a nova localização (endereço / link) do ".$dados['nm_formato_objeto'].".\n";
        } else {
          $ajuda .= "Informe a localização (endereço / link) do ".$dados['nm_formato_objeto'].".\n";
        }
        //$ajuda.= nl2br($ds_sugestao_locais_aplicativos)."\n".
        //         "Este campo é de preenchimento obrigatório!\n";
      break;
            
      case "6":   
        if ($eh_setado == '1') {
          $ajuda .= "Informe a nova localização (link externo) do OA.\n";
        } else {
          $ajuda .= "Informe a localização (link externo) do OA.\n";
        }
        //$ajuda .= "Este campo é de preenchimento obrigatório!\n";
      break;
            
      case "7":
        require_once 'conteudos/arquivos_extensao.php';                         $arq_ext = new ArquivoExtensao();
        $ds_limite_tamanho_arquivos = $conf->retornaTamanhoLimiteUploadArquivos();
        $ds_extensoes_arquivos = $arq_ext->retornaExtensoesArquivosObjetoAprendizagem($cd_formato);
        if ($eh_setado == '1') {
          $ajuda .= "Selecione o novo arquivo com ".$dados['nm_formato_objeto'].".";
        } else {
          $ajuda .= "Selecione o arquivo com ".$dados['nm_formato_objeto'].".";
        }
        $ajuda .= "São aceitos arquivos nos seguintes formatos, com tamanho máximo de ".$ds_limite_tamanho_arquivos." MB:\n".
                  $ds_extensoes_arquivos."\n";
      break;

      case "999":
        $ajuda .= "Nenhuma informação a ser inserida\n";
      break;
    }
      
    $retorno.= "          <input type=\"hidden\" name=\"eh_setado\" id=\"eh_setado\" value=\"eh_setado\" />\n";
      
    $substituicao = "";
    if ($cd_formato == '0') {
      $retorno.= "          <input type=\"text\" maxlength=\"0\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"\" style=\"width:100%;\" alt=\"".$ajuda."\" title=\"".$ajuda."\" class=\"fontConteudoCampoTextHint\" placeholder=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao()."\" class=\"fontConteudoCampoTextHint\" tabindex=\"1\" >\n";//onFocus=\"alert('Selecione antes o formato do Objeto de Aprendizagem!');\" />\n";
    } elseif (($cd_formato == '1') || ($cd_formato == '2') || ($cd_formato == '5') || ($cd_formato == '6')) {
      //$ajuda.= 'Campo do Tipo Texto com capacidade para até 250 caracteres';
      $retorno.= "          <input type=\"text\" maxlength=\"250\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"".$ds_location."\" style=\"width:100%;\" alt=\"".$ajuda."\" title=\"".$ajuda."\" class=\"fontConteudoCampoTextHint\" placeholder=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao()."\" class=\"fontConteudoCampoTextHintObrigatorio\"  tabindex=\"1\" onBlur=\"validarOA();\" onChange=\"validarOA();\" />\n";
    } elseif (($cd_formato == '3') || ($cd_formato == '4') || ($cd_formato == '7')) {
      $retorno.= "          <input type=\"file\" maxlength=\"150\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"\" style=\"width:100%;\" alt=\"".$ajuda."\" title=\"".$ajuda."\" class=\"fontConteudoCampoTextHint\" placeholder=\"".$conf->retornaDescricaoCampoTechnicalLocalizacao()."\" class=\"fontConteudoCampoTextHintObrigatorio\" tabindex=\"1\" onClick=\"marcarValidacao();\" onBlur=\"validarOA();\" onChange=\"validarOA();\" />\n";
      if ($cd_formato == $cd_formato_antigo) {
        if ($ds_location != '') {
          $substituicao = "<br />Para alterar o arquivo atual, selecione novo Arquivo, ou deixe este campo em branco mantendo o existente!\n";
        }
        $retorno.= "          <input type=\"hidden\" name=\"ds_technical_location_antigo\" id=\"ds_technical_location_antigo\" value=\"".$ds_location."\" />\n";
      } else {
        $retorno.= "          <input type=\"hidden\" name=\"ds_technical_location_antigo\" id=\"ds_technical_location_antigo\" value=\"\" />\n";
      }
      $retorno.= $arq_ext->retornaRelacaoExtensoesHidden($cd_formato);
      //$ajuda.= 'Campo do Tipo Arquivo';
    } else {
      $retorno.= "          <input type=\"hidden\" name=\"ds_technical_location\" id=\"ds_technical_location\" value=\"\" />\n".
                 "          Nenhuma informação a ser inserida\n";
    }
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

  function mudarTamanhoFonte($tamanho) {
    $_SESSION['life_tamanho_fonte'] = $tamanho;
  }

  sajax_init();
	sajax_export("incluirAreaConhecimentoRelacao");
	sajax_export("retirarRelacao");
  sajax_export("atualizarCampoTipoArquivo");
  sajax_export("atualizarCampoSubAreasConhecimento");
  sajax_export("detalharDadosObjetoAprendizagem");
  sajax_export("buscaCidades");
  sajax_export("mudarTamanhoFonte");
	sajax_handle_client_request();
?>