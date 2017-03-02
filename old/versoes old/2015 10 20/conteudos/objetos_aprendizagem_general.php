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

      $eh_informar_identifier = '1';
      $eh_informar_title = $conf->retornaInformarGeneralTitle($tipo);
      $eh_informar_language = $conf->retornaInformarGeneralLanguage($tipo);
      $eh_informar_description = $conf->retornaInformarGeneralDescription($tipo);
      $eh_informar_keyword = $conf->retornaInformarGeneralKeyword($tipo);
      $eh_informar_coverage = '1';
      $eh_informar_structure = $conf->retornaInformarGeneralStructure($tipo);
      $eh_informar_agregation_level = $conf->retornaInformarGeneralAgregationLevel($tipo);

      $cd_general = "";
      $ds_identifier = "";
      $ds_title = "";
      $cd_language = "";
      $ds_description = "";
      $ds_keyword = "";
      $cd_coverage = "";
      $ds_structure = "";
      $ds_agregation_level = "";

      $this->imprimeFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $cd_coverage, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level);
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
        $eh_informar_coverage = '1';
        $eh_informar_structure = $dados['eh_informar_structure'];
        $eh_informar_agregation_level = $dados['eh_informar_agregation_level'];
      } else {
        $eh_informar_identifier = '1';
        $eh_informar_title = $conf->retornaInformarGeneralTitle($tipo);
        $eh_informar_language = $conf->retornaInformarGeneralLanguage($tipo);
        $eh_informar_description = $conf->retornaInformarGeneralDescription($tipo);
        $eh_informar_keyword = $conf->retornaInformarGeneralKeyword($tipo);
        $eh_informar_coverage = '1';
        $eh_informar_structure = $conf->retornaInformarGeneralStructure($tipo);
        $eh_informar_agregation_level = $conf->retornaInformarGeneralAgregationLevel($tipo);
      }
      
      $ds_identifier = $dados['ds_identifier'];
      $ds_title = $dados['ds_title'];
      $cd_language = $dados['cd_language'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];
      $cd_coverage = $dados['cd_coverage'];
      $ds_structure = $dados['ds_structure'];
      $ds_agregation_level = $dados['ds_agregation_level'];
      
      $this->imprimeFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $cd_coverage, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level);
    }
    
    public function imprimeFormularioCadastro($eh_informar_general, $eh_manter_configuracoes_originais, $cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $cd_coverage, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_general', $cd_general);
      $util->campoHidden('eh_informar_general', $eh_informar_general);
      $util->campoHidden('eh_informar_general_identifier', $eh_informar_identifier);
      $util->campoHidden('eh_informar_general_title', $eh_informar_title);
      $util->campoHidden('eh_informar_general_language', $eh_informar_language);
      $util->campoHidden('eh_informar_general_description', $eh_informar_description);
      $util->campoHidden('eh_informar_general_keyword', $eh_informar_keyword);
      $util->campoHidden('eh_informar_general_coverage', $eh_informar_coverage);
      $util->campoHidden('eh_informar_general_structure', $eh_informar_structure);
      $util->campoHidden('eh_informar_general_agregation_level', $eh_informar_agregation_level);
      
      $eh_obrigatorio_identifier = '1';
      $eh_obrigatorio_title = $conf->retornaInformarGeneralTitle('b');
      $eh_obrigatorio_language = $conf->retornaInformarGeneralLanguage('b');
      $eh_obrigatorio_description = $conf->retornaInformarGeneralDescription('b');
      $eh_obrigatorio_keyword = $conf->retornaInformarGeneralKeyword('b');
      $eh_obrigatorio_coverage = '1';
      $eh_obrigatorio_structure = $conf->retornaInformarGeneralStructure('b');
      $eh_obrigatorio_agregation_level = $conf->retornaInformarGeneralAgregationLevel('b');
      $util->campoHidden('eh_obrigatorio_general_identifier', $eh_obrigatorio_identifier);
      $util->campoHidden('eh_obrigatorio_general_title', $eh_obrigatorio_title);
      $util->campoHidden('eh_obrigatorio_general_language', $eh_obrigatorio_language);
      $util->campoHidden('eh_obrigatorio_general_description', $eh_obrigatorio_description);
      $util->campoHidden('eh_obrigatorio_general_keyword', $eh_obrigatorio_keyword);
      $util->campoHidden('eh_obrigatorio_general_coverage', $eh_obrigatorio_coverage);
      $util->campoHidden('eh_obrigatorio_general_structure', $eh_obrigatorio_structure);
      $util->campoHidden('eh_obrigatorio_general_agregation_level', $eh_obrigatorio_agregation_level);
      
      if ($eh_informar_general == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações Gerais');      
        if ($eh_informar_identifier == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_identifier, $conf->retornaDescricaoCampoGeneralIdentificador(), 'ds_general_identifier', '250', '840', $ds_identifier, 1);
        } else {
          $util->campoHidden('ds_general_identifier', $ds_identifier);
        }
        if ($eh_informar_title == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_title, $conf->retornaDescricaoCampoGeneralTitulo(), 'ds_general_title', '250', '840', $ds_title, 1);
        } else {
          $util->campoHidden('ds_general_title', $ds_title);
        }
        if ($eh_informar_language == '1') {        
          require_once 'conteudos/linguagens.php';                              $lin = new Linguagem();
          $lin->retornaSeletorLinguagem($cd_language, 'cd_general_language', '840', 1, $conf->retornaDescricaoCampoGeneralIdioma());
        } else {
          $util->campoHidden('cd_general_language', $cd_language);
        }
        if ($eh_informar_description == '1') {
          $util->linhaTextoHint($eh_obrigatorio_description, $conf->retornaDescricaoCampoGeneralDescricao(), 'ds_general_description', $ds_description, '5', '840', 1);
        } else {
          $util->campoHidden('ds_general_description', $ds_description);
        }
        if ($eh_informar_keyword == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_keyword, $conf->retornaDescricaoCampoGeneralPalavraChave().' (Separadas por ponto e vírgula (;))', 'ds_general_keyword', '250', '840', $ds_keyword, 1);
        } else {
          $util->campoHidden('ds_general_keyword', $ds_keyword);
        }
        if ($eh_informar_coverage == '1') {
          //cobertura - entendido como areas do conhecimento
          require_once 'conteudos/areas_conhecimento.php';                      $are_con = new AreaConhecimento();
          $are_con->retornaSeletorAreasConhecimentoObjetoAprendizagem($cd_coverage, 'cd_general_coverage', '840', 1, $cd_general, $conf->retornaDescricaoCampoGeneralCobertura());
        } else {
          $util->campoHidden('cd_general_coverage', $cd_coverage);
        }
        if ($eh_informar_structure == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_structure, $conf->retornaDescricaoCampoGeneralEstrutura(), 'ds_general_structure', '250', '840', $ds_structure, 1);
        } else {
          $util->campoHidden('ds_general_structure', $ds_structure);
        }
        if ($eh_informar_agregation_level == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_agregation_level, $conf->retornaDescricaoCampoGeneralNivelAgregacao(), 'ds_general_agregation_level', '250', '840', $ds_agregation_level, 1);
        } else {
          $util->campoHidden('ds_general_agregation_level', $ds_agregation_level);
        }                                                                                                                 
      } else {
        $util->campoHidden('ds_general_identifier', $ds_identifier);
        $util->campoHidden('ds_general_title', $ds_title);
        $util->campoHidden('cd_general_language', $cd_language);
        $util->campoHidden('ds_general_description', $ds_description);
        $util->campoHidden('ds_general_keyword', $ds_keyword);
        $util->campoHidden('cd_general_coverage', $cd_coverage);
        $util->campoHidden('ds_general_structure', $ds_structure);
        $util->campoHidden('ds_general_agregation_level', $ds_agregation_level);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/sub_areas_conhecimento_general.php';              $sacg = new SubAreaConhecimentoGeneral();
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();

      $cd_general = addslashes($_POST['cd_general']);
      $eh_informar_identifier = addslashes($_POST['eh_informar_general_identifier']);
      $eh_informar_title = addslashes($_POST['eh_informar_general_title']);
      $cd_language = addslashes($_POST['cd_general_language']);
      $eh_informar_language = addslashes($_POST['eh_informar_general_language']);
      $eh_informar_description = addslashes($_POST['eh_informar_general_description']);
      $eh_informar_keyword = addslashes($_POST['eh_informar_general_keyword']);
      $eh_informar_coverage = addslashes($_POST['eh_informar_general_coverage']);
      $eh_informar_structure = addslashes($_POST['eh_informar_general_structure']);
      $eh_informar_agregation_level = addslashes($_POST['eh_informar_general_agregation_level']);

      $ds_identifier = $util->limparVariavel($_POST['ds_general_identifier']);
      $ds_title = $util->limparVariavel($_POST['ds_general_title']);
      $ds_description = $util->limparVariavel($_POST['ds_general_description']);
      $ds_keyword = $util->limparVariavel($_POST['ds_general_keyword']);
      $cd_coverage = $util->limparVariavel($_POST['cd_general_coverage']);
      $ds_structure = $util->limparVariavel($_POST['ds_general_structure']);
      $ds_agregation_level = $util->limparVariavel($_POST['ds_general_agregation_level']);

      if ($cd_general > 0) {
        $sacg->salvarSubAreasConhecimentoGeneral($cd_general, $cd_coverage);
        $cd_general = $this->alteraGeneral($cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $cd_coverage, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level);        

        $dados = $are_con->selectDadosAreaConhecimento($cd_coverage);
        $area = $dados['nm_area_conhecimento'];
        $sub_areas = $sacg->retornaRelacaoSubAreasConhecimentoGeneralAreasConhecimento($cd_coverage, $cd_general);
        $_SESSION['life_agrupador_termos_cadastro'] .= $ds_identifier." | ".$ds_title." | ".$ds_description." | ".$ds_keyword." | ".$area." | ".$sub_areas.$ds_structure." | ".$ds_agregation_level;

        return $cd_general;
      } else {
        $cd_general = $this->insereGeneral($ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $cd_coverage, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level);
        if ($cd_general > 0) {
          $sacg->salvarSubAreasConhecimentoGeneral($cd_general, $cd_coverage);
        }

        $dados = $are_con->selectDadosAreaConhecimento($cd_coverage);
        $area = $dados['nm_area_conhecimento'];
        $sub_areas = $sacg->retornaRelacaoSubAreasConhecimentoGeneralAreasConhecimento($cd_coverage, $cd_general);
        $_SESSION['life_agrupador_termos_cadastro'] .= $ds_identifier." | ".$ds_title." | ".$ds_description." | ".$ds_keyword." | ".$area." | ".$sub_areas.$ds_structure." | ".$ds_agregation_level;

        return $cd_general;
      }
    } 

    public function imprimeDados($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemGeneral($cd_general);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_identifier = '1';
        $eh_informar_title = $dados['eh_informar_title'];
        $eh_informar_language = $dados['eh_informar_language'];
        $eh_informar_description = $dados['eh_informar_description'];
        $eh_informar_keyword = $dados['eh_informar_keyword'];
        $eh_informar_coverage = '1';
        $eh_informar_structure = $dados['eh_informar_structure'];
        $eh_informar_agregation_level = $dados['eh_informar_agregation_level'];
      } else {
        $eh_informar_identifier = '1';
        $eh_informar_title = $conf->retornaInformarGeneralTitle($tipo);
        $eh_informar_language = $conf->retornaInformarGeneralLanguage($tipo);
        $eh_informar_description = $conf->retornaInformarGeneralDescription($tipo);
        $eh_informar_keyword = $conf->retornaInformarGeneralKeyword($tipo);
        $eh_informar_coverage = '1';
        $eh_informar_structure = $conf->retornaInformarGeneralStructure($tipo);
        $eh_informar_agregation_level = $conf->retornaInformarGeneralAgregationLevel($tipo);
      }
      
      $ds_identifier = $dados['ds_identifier'];
      $ds_title = $dados['ds_title'];
      $cd_language = $dados['cd_language'];
      $ds_description = $dados['ds_description'];
      $ds_keyword = $dados['ds_keyword'];
      $cd_coverage = $dados['cd_coverage'];
      $ds_structure = $dados['ds_structure'];
      $ds_agregation_level = $dados['ds_agregation_level'];

      $retorno = '';
            
      if ($eh_informar_general == '1') {
        if ($eh_informar_identifier == '1') {
          $retorno.= $conf->retornaDescricaoCampoGeneralIdentificador().': '.$ds_identifier.'<br />';
        }
        if ($eh_informar_title == '1') {
          $retorno.= $conf->retornaDescricaoCampoGeneralTitulo().': '.$ds_title.'<br />';
        }
        if ($eh_informar_language == '1') {        
          require_once 'conteudos/linguagens.php';                              $lin = new Linguagem();
          $retorno.= $lin->retornaDados($cd_language, $conf->retornaDescricaoCampoGeneralIdioma());
        }
        if ($eh_informar_description == '1') {
          $retorno.= $conf->retornaDescricaoCampoGeneralDescricao().': '.$ds_description.'<br />';
        }
        if ($eh_informar_keyword == '1') {
          $retorno.= $conf->retornaDescricaoCampoGeneralPalavraChave().': '.$ds_keyword.'<br />';
        }
        if ($eh_informar_coverage == '1') {
          require_once 'conteudos/areas_conhecimento.php';                      $are_con = new AreaConhecimento();
          $retorno = $are_con->retornaDados($cd_coverage, $cd_general, $conf->retornaDescricaoCampoGeneralCobertura());
        }
        if ($eh_informar_structure == '1') {
          $retorno.= $conf->retornaDescricaoCampoGeneralEstrutura().': '.$ds_structure.'<br />';
        }
        if ($eh_informar_agregation_level == '1') {
          $retorno.= $conf->retornaDescricaoCampoGeneralNivelAgregacao().': '.$ds_agregation_level.'<br />';
        }                                                                                                                 
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

    public function insereGeneral($ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $cd_coverage, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level) {
      $sql = "INSERT INTO life_general ".
             "(ds_identifier, eh_informar_identifier, ds_title, eh_informar_title, cd_language, eh_informar_language, ds_description, eh_informar_description, ds_keyword, eh_informar_keyword, cd_coverage, eh_informar_coverage, ds_structure, eh_informar_structure, ds_agregation_level, eh_informar_agregation_level) ".
             "VALUES ".
             "(\"$ds_identifier\", \"$eh_informar_identifier\", \"$ds_title\", \"$eh_informar_title\", \"$cd_language\", \"$eh_informar_language\", \"$ds_description\", \"$eh_informar_description\", \"$ds_keyword\", \"$eh_informar_keyword\", \"$cd_coverage\", \"$eh_informar_coverage\", \"$ds_structure\", \"$eh_informar_structure\", \"$ds_agregation_level\", \"$eh_informar_agregation_level\")";
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
                "AND cd_coverage = '$cd_coverage' ".
                "AND ds_structure = '$ds_structure' ".
                "AND ds_agregation_level = '$ds_agregation_level' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraGeneral($cd_general, $ds_identifier, $eh_informar_identifier, $ds_title, $eh_informar_title, $cd_language, $eh_informar_language, $ds_description, $eh_informar_description, $ds_keyword, $eh_informar_keyword, $cd_coverage, $eh_informar_coverage, $ds_structure, $eh_informar_structure, $ds_agregation_level, $eh_informar_agregation_level) {
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
             "cd_coverage = \"$cd_coverage\", ".
             "eh_informar_coverage = \"$eh_informar_coverage\", ".
             "ds_structure = \"$ds_structure\", ".
             "eh_informar_structure = \"$eh_informar_structure\", ".
             "ds_agregation_level = \"$ds_agregation_level\", ".
             "eh_informar_agregation_level = \"$eh_informar_agregation_level\" ".
             "WHERE cd_general = '$cd_general' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>