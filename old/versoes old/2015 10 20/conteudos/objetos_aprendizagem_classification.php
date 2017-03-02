<?php
  class ObjetoAprendizagemClassification {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_classification > 0) {
        $this->montarFormularioEdicao($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_classification, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_purpose = $conf->retornaInformarClassificationPurpose($tipo);
      $eh_informar_taxon_path = $conf->retornaInformarClassificationTaxonPath($tipo);
      $eh_informar_description = $conf->retornaInformarClassificationDescription($tipo);
      $eh_informar_keyword = $conf->retornaInformarClassificationKeyword($tipo);

      $cd_classification = "";
      $ds_purpose = "";
      $ds_taxon_path = "";
      $ds_description = "";
      $ds_keyword = "";

      $this->imprimeFormularioCadastro($eh_informar_classification, $eh_manter_configuracoes_originais, $cd_classification, $ds_purpose, $eh_informar_purpose, $ds_taxon_path, $eh_informar_taxon_path, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword);
    }
    
    private function montarFormularioEdicao($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemClassification($cd_classification);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_purpose = $dados['eh_informar_purpose'];
        $eh_informar_taxon_path = $dados['eh_informar_taxon_path'];
        $eh_informar_description = $dados['eh_informar_description'];
        $eh_informar_keyword = $dados['eh_informar_keyword'];
      } else {
        $eh_informar_purpose = $conf->retornaInformarClassificationPurpose($tipo);
        $eh_informar_taxon_path = $conf->retornaInformarClassificationTaxonPath($tipo);
        $eh_informar_description = $conf->retornaInformarClassificationDescription($tipo);
        $eh_informar_keyword = $conf->retornaInformarClassificationKeyword($tipo);
      }

      $ds_purpose = $dados['ds_purpose'];
      $ds_taxon_path = $dados['ds_taxon_path'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];
      
      $this->imprimeFormularioCadastro($eh_informar_classification, $eh_manter_configuracoes_originais, $cd_classification, $ds_purpose, $eh_informar_purpose, $ds_taxon_path, $eh_informar_taxon_path, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword);
    }
    
    public function imprimeFormularioCadastro($eh_informar_classification, $eh_manter_configuracoes_originais, $cd_classification, $ds_purpose, $eh_informar_purpose, $ds_taxon_path, $eh_informar_taxon_path, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_classification', $cd_classification);
      $util->campoHidden('eh_informar_classification_purpose', $eh_informar_purpose);
      $util->campoHidden('eh_informar_classification_taxon_path', $eh_informar_taxon_path);
      $util->campoHidden('eh_informar_classification_description', $eh_informar_description);
      $util->campoHidden('eh_informar_classification_keyword', $eh_informar_keyword);

      $eh_obrigatorio_purpose = $conf->retornaInformarClassificationPurpose('b');
      $eh_obrigatorio_taxon_path = $conf->retornaInformarClassificationTaxonPath('b');
      $eh_obrigatorio_description = $conf->retornaInformarClassificationDescription('b');
      $eh_obrigatorio_keyword = $conf->retornaInformarClassificationKeyword('b');
      $util->campoHidden('eh_obrigatorio_classification_purpose', $eh_obrigatorio_purpose);
      $util->campoHidden('eh_obrigatorio_classification_taxon_path', $eh_obrigatorio_taxon_path);
      $util->campoHidden('eh_obrigatorio_classification_description', $eh_obrigatorio_description);
      $util->campoHidden('eh_obrigatorio_classification_keyword', $eh_obrigatorio_keyword);

            
      if ($eh_informar_classification == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Classificação');
      
        if ($eh_informar_purpose == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_purpose, $conf->retornaDescricaoCampoClassificationProposito(), 'ds_classification_purpose', '250', '840', $ds_purpose, 1);
        } else {
          $util->campoHidden('ds_classification_purpose', $ds_purpose);
        }         
        if ($eh_informar_taxon_path == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_taxon_path, $conf->retornaDescricaoCampoClassificationTaxonPath(), 'ds_classification_taxon_path', '250', '840', $ds_taxon_path, 1);
        } else {
          $util->campoHidden('ds_classification_taxon_path', $ds_taxon_path);
        }        
        if ($eh_informar_description == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_description, $conf->retornaDescricaoCampoClassificationDescricao(), 'ds_classification_description', '250', '840', $ds_description, 1);
        } else {
          $util->campoHidden('ds_classification_description', $ds_description);
        }        
        if ($eh_informar_keyword == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_keyword, $conf->retornaDescricaoCampoClassificationPalavraChave().' (Separadas por ponto e vírgula (;))', 'ds_classification_keyword', '250', '840', $ds_keyword, 1);
        } else {
          $util->campoHidden('ds_classification_keyword', $ds_keyword);
        }        
      } else {
        $util->campoHidden('ds_classification_purpose', $ds_purpose);
        $util->campoHidden('ds_classification_taxon_path', $ds_taxon_path);
        $util->campoHidden('ds_classification_description', $ds_description);
        $util->campoHidden('ds_classification_keyword', $ds_keyword);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_classification = addslashes($_POST['cd_classification']);
      $eh_informar_purpose = addslashes($_POST['eh_informar_classification_purpose']);
      $eh_informar_taxon_path = addslashes($_POST['eh_informar_classification_taxon_path']);
      $eh_informar_description = addslashes($_POST['eh_informar_classification_description']);
      $eh_informar_keyword = addslashes($_POST['eh_informar_classification_keyword']);
      $ds_purpose = $util->limparVariavel($_POST['ds_classification_purpose']);
      $ds_taxon_path = $util->limparVariavel($_POST['ds_classification_taxon_path']);
      $ds_description = $util->limparVariavel($_POST['ds_classification_description']);
      $ds_keyword = $util->limparVariavel($_POST['ds_classification_keyword']);


      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_purpose." | ".$ds_taxon_path." | ".$ds_description." | ".$ds_keyword;

      if ($cd_classification > 0) {
        return $this->alteraClassification($cd_classification, $ds_purpose, $eh_informar_purpose, $ds_taxon_path, $eh_informar_taxon_path, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword);
      } else {
        return $this->insereClassification($ds_purpose, $eh_informar_purpose, $ds_taxon_path, $eh_informar_taxon_path, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword);
      }
    } 

    public function imprimeDados($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemClassification($cd_classification);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_purpose = $dados['eh_informar_purpose'];
        $eh_informar_taxon_path = $dados['eh_informar_taxon_path'];
        $eh_informar_description = $dados['eh_informar_description'];
        $eh_informar_keyword = $dados['eh_informar_keyword'];
      } else {
        $eh_informar_purpose = $conf->retornaInformarClassificationPurpose($tipo);
        $eh_informar_taxon_path = $conf->retornaInformarClassificationTaxonPath($tipo);
        $eh_informar_description = $conf->retornaInformarClassificationDescription($tipo);
        $eh_informar_keyword = $conf->retornaInformarClassificationKeyword($tipo);
      }

      $ds_purpose = $dados['ds_purpose'];
      $ds_taxon_path = $dados['ds_taxon_path'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];

      $retorno = '';
            
      if ($eh_informar_classification == '1') {
        if ($eh_informar_purpose == '1') {
          $retorno.= $conf->retornaDescricaoCampoClassificationProposito().': '.$ds_purpose.'<br />';
        }         
        if ($eh_informar_taxon_path == '1') {
          $retorno.= $conf->retornaDescricaoCampoClassificationTaxonPath().': '.$ds_taxon_path.'<br />';
        }        
        if ($eh_informar_description == '1') {
          $retorno.= $conf->retornaDescricaoCampoClassificationDescricao().': '.$ds_description.'<br />';
        }        
        if ($eh_informar_keyword == '1') {
          $retorno.= $conf->retornaDescricaoCampoClassificationPalavraChave().': '.$ds_keyword.'<br />';
        }        
      }
      return $retorno;
    }

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemClassification($cd_classification) {
      $sql  = "SELECT * ".
              "FROM life_classification ".
              "WHERE cd_classification = '$cd_classification' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CLASSIFICATION!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereClassification($ds_purpose, $eh_informar_purpose, $ds_taxon_path, $eh_informar_taxon_path, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword) {
      $sql = "INSERT INTO life_classification ".
             "(ds_purpose, eh_informar_purpose, ds_taxon_path, eh_informar_taxon_path, ds_description, eh_informar_description, ds_keyword, eh_informar_keyword) ".
             "VALUES ".
             "(\"$ds_purpose\", \"$eh_informar_purpose\", \"$ds_taxon_path\", \"$eh_informar_taxon_path\", \"$ds_description\", \"$eh_informar_description\", \"$ds_keyword\", \"$eh_informar_keyword\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'classification');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CLASSIFICATION!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_classification) codigo ".
                "FROM life_classification ".
                "WHERE ds_purpose = '$ds_purpose' ".
                "AND ds_taxon_path = '$ds_taxon_path' ".
                "AND ds_description = '$ds_description' ".
                "AND ds_keyword = '$ds_keyword' ";      
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CLASSIFICATION!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraClassification($cd_classification, $ds_purpose, $eh_informar_purpose, $ds_taxon_path, $eh_informar_taxon_path, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword) {
      $sql = "UPDATE life_classification SET ".
             "ds_purpose = \"$ds_purpose\", ".
             "eh_informar_purpose = \"$eh_informar_purpose\", ".
             "ds_taxon_path = \"$ds_taxon_path\", ".
             "eh_informar_taxon_path = \"$eh_informar_taxon_path\", ".
             "ds_description = \"$ds_description\", ".
             "eh_informar_description = \"$eh_informar_description\", ".
             "ds_keyword = \"$ds_keyword\", ".
             "eh_informar_keyword = \"$eh_informar_keyword\" ".
             "WHERE cd_classification = '$cd_classification' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'classification');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CLASSIFICATION!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>