<?php
  class ObjetoAprendizagemTechnicalPlataformSpecificFeatures {

    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_plataform_specific_features, $eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_plataform_specific_features > 0) {
        $this->montarFormularioEdicao($cd_plataform_specific_features, $eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_plataform_type = $conf->retornaInformarTechnicalPlataformSpecificFeaturesPlataformType($tipo);
      $eh_informar_specific_format = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificFormat($tipo);
      $eh_informar_specific_size = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificSize($tipo);
      $eh_informar_specific_location = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificLocation($tipo);
      $eh_informar_specific_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificRequeriments($tipo);
      $eh_informar_specific_instalation_remarks = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificInstalationRemarks($tipo);
      $eh_informar_specific_other_plataform_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificOtherPlataformRequeriments($tipo);

      $cd_plataform_specific_features = "";
      $ds_plataform_type = "";
      $ds_specific_format = "";
      $ds_specific_size = "";
      $ds_specific_location = "";
      $ds_specific_requeriments = "";
      $ds_specific_instalation_remarks = "";
      $ds_specific_other_plataform_requeriments = "";

      $this->imprimeFormularioCadastro($eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $cd_plataform_specific_features, $ds_plataform_type, $eh_informar_plataform_type, $ds_specific_format, $eh_informar_specific_format, $ds_specific_size, $eh_informar_specific_size, $ds_specific_location, $eh_informar_specific_location, $ds_specific_requeriments, $eh_informar_specific_requeriments, $ds_specific_instalation_remarks, $eh_informar_specific_instalation_remarks, $ds_specific_other_plataform_requeriments, $eh_informar_specific_other_plataform_requeriments);
    }
    
    private function montarFormularioEdicao($cd_plataform_specific_features, $eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnicalPlataformSpecificFeatures($cd_plataform_specific_features);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_plataform_type = $dados['eh_informar_plataform_type'];
        $eh_informar_specific_format = $dados['eh_informar_specific_format'];
        $eh_informar_specific_size = $dados['eh_informar_specific_size'];
        $eh_informar_specific_location = $dados['eh_informar_specific_location'];
        $eh_informar_specific_requeriments = $dados['eh_informar_specific_requeriments'];
        $eh_informar_specific_instalation_remarks = $dados['eh_informar_specific_instalation_remarks'];
        $eh_informar_specific_other_plataform_requeriments = $dados['eh_informar_specific_other_plataform_requeriments'];
      } else {
        $eh_informar_plataform_type = $conf->retornaInformarTechnicalPlataformSpecificFeaturesPlataformType($tipo);
        $eh_informar_specific_format = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificFormat($tipo);
        $eh_informar_specific_size = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificSize($tipo);
        $eh_informar_specific_location = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificLocation($tipo);
        $eh_informar_specific_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificRequeriments($tipo);
        $eh_informar_specific_instalation_remarks = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificInstalationRemarks($tipo);
        $eh_informar_specific_other_plataform_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificOtherPlataformRequeriments($tipo);
      }
      
      $ds_plataform_type = $dados['ds_plataform_type'];
      $ds_specific_format = $dados['ds_specific_format'];
      $ds_specific_size = $dados['ds_specific_size'];
      $ds_specific_location = $dados['ds_specific_location'];
      $ds_specific_requeriments = $dados['ds_specific_requeriments'];
      $ds_specific_instalation_remarks = $dados['ds_specific_instalation_remarks'];
      $ds_specific_other_plataform_requeriments = $dados['ds_specific_other_plataform_requeriments'];

      $this->imprimeFormularioCadastro($eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $cd_plataform_specific_features, $ds_plataform_type, $eh_informar_plataform_type, $ds_specific_format, $eh_informar_specific_format, $ds_specific_size, $eh_informar_specific_size, $ds_specific_location, $eh_informar_specific_location, $ds_specific_requeriments, $eh_informar_specific_requeriments, $ds_specific_instalation_remarks, $eh_informar_specific_instalation_remarks, $ds_specific_other_plataform_requeriments, $eh_informar_specific_other_plataform_requeriments);
    }
    
    public function imprimeFormularioCadastro($eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $cd_plataform_specific_features, $ds_plataform_type, $eh_informar_plataform_type, $ds_specific_format, $eh_informar_specific_format, $ds_specific_size, $eh_informar_specific_size, $ds_specific_location, $eh_informar_specific_location, $ds_specific_requeriments, $eh_informar_specific_requeriments, $ds_specific_instalation_remarks, $eh_informar_specific_instalation_remarks, $ds_specific_other_plataform_requeriments, $eh_informar_specific_other_plataform_requeriments) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_technical_plataform_specific_features', $cd_plataform_specific_features);
      $util->campoHidden('eh_informar_technical_plataform_specific_features', $eh_informar_technical_plataform_specific_features);
      $util->campoHidden('eh_informar_technical_plataform_specific_features_plataform_type', $eh_informar_plataform_type);
      $util->campoHidden('eh_informar_technical_plataform_specific_features_specific_format', $eh_informar_specific_format);
      $util->campoHidden('eh_informar_technical_plataform_specific_features_specific_size', $eh_informar_specific_size);
      $util->campoHidden('eh_informar_technical_plataform_specific_features_specific_location', $eh_informar_specific_location);
      $util->campoHidden('eh_informar_technical_plataform_specific_features_specific_requeriments', $eh_informar_specific_requeriments);
      $util->campoHidden('eh_informar_technical_plataform_specific_features_specific_instalation_remarks', $eh_informar_specific_instalation_remarks);
      $util->campoHidden('eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments', $eh_informar_specific_other_plataform_requeriments);
      
      $eh_obrigatorio_plataform_type = $conf->retornaInformarTechnicalPlataformSpecificFeaturesPlataformType('b');
      $eh_obrigatorio_specific_format = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificFormat('b');
      $eh_obrigatorio_specific_size = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificSize('b');
      $eh_obrigatorio_specific_location = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificLocation('b');
      $eh_obrigatorio_specific_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificRequeriments('b');
      $eh_obrigatorio_specific_instalation_remarks = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificInstalationRemarks('b');
      $eh_obrigatorio_specific_other_plataform_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificOtherPlataformRequeriments('b');
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features_plataform_type', $eh_obrigatorio_plataform_type);
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features_specific_format', $eh_obrigatorio_specific_format);
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features_specific_size', $eh_obrigatorio_specific_size);
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features_specific_location', $eh_obrigatorio_specific_location);
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features_specific_requeriments', $eh_obrigatorio_specific_requeriments);
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features_specific_instalation_remarks', $eh_obrigatorio_specific_instalation_remarks);
      $util->campoHidden('eh_obrigatorio_technical_plataform_specific_features_specific_other_plataform_requeriments', $eh_obrigatorio_specific_other_plataform_requeriments);

      if ($eh_informar_technical_plataform_specific_features == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações Técnicas - Características Específicas');
      
        if ($eh_informar_plataform_type == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_plataform_type, $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesPlataformaTipo(), 'ds_technical_plataform_specific_features_plataform_type', '250', '840', $ds_plataform_type, 1);
        } else {
          $util->campoHidden('ds_technical_plataform_specific_features_plataform_type', $ds_plataform_type);
        }
        if ($eh_informar_specific_format == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_specific_format, $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesFormatoEspecifico(), 'ds_technical_plataform_specific_features_specific_format', '250', '840', $ds_specific_format, 1);
        } else {
          $util->campoHidden('ds_technical_plataform_specific_features_specific_format', $ds_specific_format);
        }
        if ($eh_informar_specific_size == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_specific_size, $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesEspecificaTamanho(), 'ds_technical_plataform_specific_features_specific_size', '250', '840', $ds_specific_size, 1);
        } else {
          $util->campoHidden('ds_technical_plataform_specific_features_specific_size', $ds_specific_size);
        }
        if ($eh_informar_specific_location == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_specific_location, $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesLocalizacaoEspecifica(), 'ds_technical_plataform_specific_features_specific_location', '250', '840', $ds_specific_location, 1);
        } else {
          $util->campoHidden('ds_technical_plataform_specific_features_specific_location', $ds_specific_location);
        }
        if ($eh_informar_specific_requeriments == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_specific_requeriments, $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesRequerimentosEspecificos(), 'ds_technical_plataform_specific_features_specific_requeriments', '250', '840', $ds_specific_requeriments, 1);
        } else {
          $util->campoHidden('ds_technical_plataform_specific_features_specific_requeriments', $ds_specific_requeriments);
        }
        if ($eh_informar_specific_instalation_remarks == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_specific_instalation_remarks, $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesObservacoesInstalacaoEspecificas(), 'ds_technical_plataform_specific_features_specific_instalation_remarks', '250', '840', $ds_specific_instalation_remarks, 1);
        } else {
          $util->campoHidden('ds_technical_plataform_specific_features_specific_instalation_remarks', $ds_specific_instalation_remarks);
        }
        if ($eh_informar_specific_other_plataform_requeriments == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_specific_other_plataform_requeriments, $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesOutrosRequisitosPlataformaEspecificos(), 'ds_technical_plataform_specific_features_specific_other_plataform_requeriments', '250', '840', $ds_specific_other_plataform_requeriments, 1);
        } else {
          $util->campoHidden('ds_technical_plataform_specific_features_specific_other_plataform_requeriments', $ds_specific_other_plataform_requeriments);
        }                                                                      
      } else {
        $util->campoHidden('ds_technical_plataform_specific_features_plataform_type', $ds_plataform_type);
        $util->campoHidden('ds_technical_plataform_specific_features_specific_format', $ds_specific_format);
        $util->campoHidden('ds_technical_plataform_specific_features_specific_size', $ds_specific_size);
        $util->campoHidden('ds_technical_plataform_specific_features_specific_location', $ds_specific_location);
        $util->campoHidden('ds_technical_plataform_specific_features_specific_requeriments', $ds_specific_requeriments);
        $util->campoHidden('ds_technical_plataform_specific_features_specific_instalation_remarks', $ds_specific_instalation_remarks);
        $util->campoHidden('ds_technical_plataform_specific_features_specific_other_plataform_requeriments', $ds_specific_other_plataform_requeriments);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_plataform_specific_features = addslashes($_POST['cd_technical_plataform_specific_features']);
      $eh_informar_plataform_type = addslashes($_POST['eh_informar_technical_plataform_specific_features_plataform_type']);
      $eh_informar_specific_format = addslashes($_POST['eh_informar_technical_plataform_specific_features_specific_format']);
      $eh_informar_specific_size = addslashes($_POST['eh_informar_technical_plataform_specific_features_specific_size']);
      $eh_informar_specific_location = addslashes($_POST['eh_informar_technical_plataform_specific_features_specific_location']);
      $eh_informar_specific_requeriments = addslashes($_POST['eh_informar_technical_plataform_specific_features_specific_requeriments']);
      $eh_informar_specific_instalation_remarks = addslashes($_POST['eh_informar_technical_plataform_specific_features_specific_instalation_remarks']);
      $eh_informar_specific_other_plataform_requeriments = addslashes($_POST['eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments']);
      $ds_plataform_type = $util->limparVariavel($_POST['ds_technical_plataform_specific_features_plataform_type']);
      $ds_specific_format = $util->limparVariavel($_POST['ds_technical_plataform_specific_features_specific_format']);
      $ds_specific_size = $util->limparVariavel($_POST['ds_technical_plataform_specific_features_specific_size']);
      $ds_specific_location = $util->limparVariavel($_POST['ds_technical_plataform_specific_features_specific_location']);
      $ds_specific_requeriments = $util->limparVariavel($_POST['ds_technical_plataform_specific_features_specific_requeriments']);
      $ds_specific_instalation_remarks = $util->limparVariavel($_POST['ds_technical_plataform_specific_features_specific_instalation_remarks']);
      $ds_specific_other_plataform_requeriments = $util->limparVariavel($_POST['ds_technical_plataform_specific_features_specific_other_plataform_requeriments']);


      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_plataform_type." | ".$ds_specific_format." | ".$ds_specific_size." | ".$ds_specific_location." | ".$ds_specific_requeriments." | ".$ds_specific_instalation_remarks." | ".$ds_specific_other_plataform_requeriments;


      if ($cd_plataform_specific_features > 0) {
        return $this->alteraTechnicalPlataformSpecificFeatures($cd_plataform_specific_features, $ds_plataform_type, $eh_informar_plataform_type, $ds_specific_format, $eh_informar_specific_format, $ds_specific_size, $eh_informar_specific_size, $ds_specific_location, $eh_informar_specific_location, $ds_specific_requeriments, $eh_informar_specific_requeriments, $ds_specific_instalation_remarks, $eh_informar_specific_instalation_remarks, $ds_specific_other_plataform_requeriments, $eh_informar_specific_other_plataform_requeriments);
      } else {
        return $this->insereTechnicalPlataformSpecificFeatures($ds_plataform_type, $eh_informar_plataform_type, $ds_specific_format, $eh_informar_specific_format, $ds_specific_size, $eh_informar_specific_size, $ds_specific_location, $eh_informar_specific_location, $ds_specific_requeriments, $eh_informar_specific_requeriments, $ds_specific_instalation_remarks, $eh_informar_specific_instalation_remarks, $ds_specific_other_plataform_requeriments, $eh_informar_specific_other_plataform_requeriments);
      }
    }
    
    public function imprimeDados($cd_plataform_specific_features, $eh_informar_technical_plataform_specific_features, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnicalPlataformSpecificFeatures($cd_plataform_specific_features);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_plataform_type = $dados['eh_informar_plataform_type'];
        $eh_informar_specific_format = $dados['eh_informar_specific_format'];
        $eh_informar_specific_size = $dados['eh_informar_specific_size'];
        $eh_informar_specific_location = $dados['eh_informar_specific_location'];
        $eh_informar_specific_requeriments = $dados['eh_informar_specific_requeriments'];
        $eh_informar_specific_instalation_remarks = $dados['eh_informar_specific_instalation_remarks'];
        $eh_informar_specific_other_plataform_requeriments = $dados['eh_informar_specific_other_plataform_requeriments'];
      } else {
        $eh_informar_plataform_type = $conf->retornaInformarTechnicalPlataformSpecificFeaturesPlataformType($tipo);
        $eh_informar_specific_format = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificFormat($tipo);
        $eh_informar_specific_size = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificSize($tipo);
        $eh_informar_specific_location = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificLocation($tipo);
        $eh_informar_specific_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificRequeriments($tipo);
        $eh_informar_specific_instalation_remarks = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificInstalationRemarks($tipo);
        $eh_informar_specific_other_plataform_requeriments = $conf->retornaInformarTechnicalPlataformSpecificFeaturesSpecificOtherPlataformRequeriments($tipo);
      }
      
      $ds_plataform_type = $dados['ds_plataform_type'];
      $ds_specific_format = $dados['ds_specific_format'];
      $ds_specific_size = $dados['ds_specific_size'];
      $ds_specific_location = $dados['ds_specific_location'];
      $ds_specific_requeriments = $dados['ds_specific_requeriments'];
      $ds_specific_instalation_remarks = $dados['ds_specific_instalation_remarks'];
      $ds_specific_other_plataform_requeriments = $dados['ds_specific_other_plataform_requeriments'];
      
      $retorno = "";

      if ($eh_informar_technical_plataform_specific_features == '1') {
        $retorno.= '<b><i>Informações Técnicas - Características Específicas</i></b><br />';
        if ($eh_informar_plataform_type == '1') {
          $retorno.= $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesPlataformaTipo().': '.$ds_plataform_type.'<br />';
        }
        if ($eh_informar_specific_format == '1') {
          $retorno.= $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesFormatoEspecifico().': '.$ds_specific_format.'<br />';
        }
        if ($eh_informar_specific_size == '1') {
          $retorno.= $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesEspecificaTamanho().': '.$ds_specific_size.'<br />';
        }
        if ($eh_informar_specific_location == '1') {
          $retorno.= $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesLocalizacaoEspecifica().': '.$ds_specific_location.'<br />';
        }
        if ($eh_informar_specific_requeriments == '1') {
          $retorno.= $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesRequerimentosEspecificos().': '.$ds_specific_requeriments.'<br />';
        }
        if ($eh_informar_specific_instalation_remarks == '1') {
          $retorno.= $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesObservacoesInstalacaoEspecificas().': '.$ds_specific_instalation_remarks.'<br />';
        }
        if ($eh_informar_specific_other_plataform_requeriments == '1') {
          $retorno.= $conf->retornaDescricaoCampoTechnicalPlataformSpecificFeaturesOutrosRequisitosPlataformaEspecificos().': '.$ds_specific_other_plataform_requeriments.'<br />';
        }                                                                      
      }
      return $retorno;
    }
     

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemTechnicalPlataformSpecificFeatures($cd_plataform_specific_features) {
      $sql  = "SELECT * ".
              "FROM life_plataform_specific_features ".
              "WHERE cd_plataform_specific_features = '$cd_plataform_specific_features' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORM SPECIFIC FEATURES!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereTechnicalPlataformSpecificFeatures($ds_plataform_type, $eh_informar_plataform_type, $ds_specific_format, $eh_informar_specific_format, $ds_specific_size, $eh_informar_specific_size, $ds_specific_location, $eh_informar_specific_location, $ds_specific_requeriments, $eh_informar_specific_requeriments, $ds_specific_instalation_remarks, $eh_informar_specific_instalation_remarks, $ds_specific_other_plataform_requeriments, $eh_informar_specific_other_plataform_requeriments) {
      $sql = "INSERT INTO life_plataform_specific_features ".
             "(ds_plataform_type, eh_informar_plataform_type, ds_specific_format, eh_informar_specific_format, ds_specific_size, eh_informar_specific_size, ds_specific_location, eh_informar_specific_location, ds_specific_requeriments, eh_informar_specific_requeriments, ds_specific_instalation_remarks, eh_informar_specific_instalation_remarks, ds_specific_other_plataform_requeriments, eh_informar_specific_other_plataform_requeriments) ".
             "VALUES ".
             "(\"$ds_plataform_type\", \"$eh_informar_plataform_type\", \"$ds_specific_format\", \"$eh_informar_specific_format\", \"$ds_specific_size\", \"$eh_informar_specific_size\", \"$ds_specific_location\", \"$eh_informar_specific_location\", \"$ds_specific_requeriments\", \"$eh_informar_specific_requeriments\", \"$ds_specific_instalation_remarks\", \"$eh_informar_specific_instalation_remarks\", \"$ds_specific_other_plataform_requeriments\", \"$eh_informar_specific_other_plataform_requeriments\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'plataform_specific_features');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORM SPECIFIC FEATURES!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_plataform_specific_features) codigo ".
                "FROM life_plataform_specific_features ".
                "WHERE ds_plataform_type = '$ds_plataform_type' ".
                "AND ds_specific_format = '$ds_specific_format' ".                                                           
                "AND ds_specific_size = '$ds_specific_size' ".
                "AND ds_specific_location = '$ds_specific_location' ".
                "AND ds_specific_requeriments = '$ds_specific_requeriments' ".
                "AND ds_specific_instalation_remarks = '$ds_specific_instalation_remarks' ".
                "AND ds_specific_other_plataform_requeriments = '$ds_specific_other_plataform_requeriments' ";    
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORM SPECIFIC FEATURES!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraTechnicalPlataformSpecificFeatures($cd_plataform_specific_features, $ds_plataform_type, $eh_informar_plataform_type, $ds_specific_format, $eh_informar_specific_format, $ds_specific_size, $eh_informar_specific_size, $ds_specific_location, $eh_informar_specific_location, $ds_specific_requeriments, $eh_informar_specific_requeriments, $ds_specific_instalation_remarks, $eh_informar_specific_instalation_remarks, $ds_specific_other_plataform_requeriments, $eh_informar_specific_other_plataform_requeriments) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_plataform_specific_features SET ".
             "ds_plataform_type = \"$ds_plataform_type\", ".
             "eh_informar_plataform_type = \"$eh_informar_plataform_type\", ".
             "ds_specific_format = \"$ds_specific_format\", ".
             "eh_informar_specific_format = \"$eh_informar_specific_format\", ".
             "ds_specific_size = \"$ds_specific_size\", ".
             "eh_informar_specific_size = \"$eh_informar_specific_size\", ".
             "ds_specific_location = \"$ds_specific_location\", ".
             "eh_informar_specific_location = \"$eh_informar_specific_location\", ".
             "ds_specific_requeriments = \"$ds_specific_requeriments\", ".
             "eh_informar_specific_requeriments = \"$eh_informar_specific_requeriments\", ".
             "ds_specific_instalation_remarks = \"$ds_specific_instalation_remarks\", ".
             "eh_informar_specific_instalation_remarks = \"$eh_informar_specific_instalation_remarks\", ".
             "ds_specific_other_plataform_requeriments = \"$ds_specific_other_plataform_requeriments\", ".
             "eh_informar_specific_other_plataform_requeriments = \"$eh_informar_specific_other_plataform_requeriments\" ".
             "WHERE cd_plataform_specific_features = '$cd_plataform_specific_features' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'plataform_specific_features');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORM SPECIFIC FEATURES!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>