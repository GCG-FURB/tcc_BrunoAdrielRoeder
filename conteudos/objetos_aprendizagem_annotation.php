<?php
  class ObjetoAprendizagemAnnotation {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_annotation > 0) {
        $this->montarFormularioEdicao($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_entity = $conf->retornaInformarAnnotationEntity($tipo);
      $eh_informar_date = $conf->retornaInformarAnnotationDate($tipo);
      $eh_informar_description = $conf->retornaInformarAnnotationDescription($tipo);

      $cd_annotation = "";
      $ds_entity = "";
      $ds_date = date('Y-m-d');
      $ds_description = "";

      $this->imprimeFormularioCadastro($eh_informar_annotation, $eh_manter_configuracoes_originais, $cd_annotation, $ds_entity, $eh_informar_entity, $ds_date, $eh_informar_date, $ds_description, $eh_informar_description);
    }
    
    private function montarFormularioEdicao($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemAnnotation($cd_annotation);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_entity = $dados['eh_informar_entity'];
        $eh_informar_date = $dados['eh_informar_date'];
        $eh_informar_description = $dados['eh_informar_description'];
      } else {
        $eh_informar_entity = $conf->retornaInformarAnnotationEntity($tipo);
        $eh_informar_date = $conf->retornaInformarAnnotationDate($tipo);
        $eh_informar_description = $conf->retornaInformarAnnotationDescription($tipo);
      }

      $ds_entity = $dados['ds_entity'];
      $ds_date = $dados['ds_date'];
      $ds_description = $dados['ds_description'];
      
      $this->imprimeFormularioCadastro($eh_informar_annotation, $eh_manter_configuracoes_originais, $cd_annotation, $ds_entity, $eh_informar_entity, $ds_date, $eh_informar_date, $ds_description, $eh_informar_description);
    }
    
    public function imprimeFormularioCadastro($eh_informar_annotation, $eh_manter_configuracoes_originais, $cd_annotation, $ds_entity, $eh_informar_entity, $ds_date, $eh_informar_date, $ds_description, $eh_informar_description) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      include_once 'js/funcoes_cadastro_data.js';
      $util->campoHidden('cd_annotation', $cd_annotation);
      $util->campoHidden('eh_informar_annotation_entity', $eh_informar_entity);
      $util->campoHidden('eh_informar_annotation_date', $eh_informar_date);
      $util->campoHidden('eh_informar_annotation_description', $eh_informar_description);

      $eh_obrigatorio_entity = $conf->retornaInformarAnnotationEntity('b');
      $eh_obrigatorio_date = $conf->retornaInformarAnnotationDate('b');
      $eh_obrigatorio_description = $conf->retornaInformarAnnotationDescription('b');
      $util->campoHidden('eh_obrigatorio_annotation_entity', $eh_obrigatorio_entity);
      $util->campoHidden('eh_obrigatorio_annotation_date', $eh_obrigatorio_date);
      $util->campoHidden('eh_obrigatorio_annotation_description', $eh_obrigatorio_description);

      if ($eh_obrigatorio_entity || $eh_obrigatorio_date || $eh_obrigatorio_description) {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Anotações');
      
        if ($eh_informar_entity == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_entity, $conf->retornaDescricaoCampoAnnotationEntidade(), $conf->retornaNomeCampoAnnotationEntidade(), 'ds_annotation_entity', '250', '100', $ds_entity, 1);
          $util->campoHidden('nm_annotation_entity', $conf->retornaNomeCampoAnnotationEntidade());
        } else {
          $util->campoHidden('ds_annotation_entity', $ds_entity);
        }
        if ($eh_informar_date == '1') {
          $util->linhaUmCampoDataHint($eh_obrigatorio_date, $conf->retornaDescricaoCampoAnnotationData(), $conf->retornaNomeCampoAnnotationData(), 'ds_annotation_date', '10', '96', $ds_date, 1);
          $util->campoHidden('nm_annotation_date', $conf->retornaNomeCampoAnnotationData());
        } else {
          $util->campoHidden('ds_annotation_date', $ds_date);
        }
        if ($eh_informar_description == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_description, $conf->retornaDescricaoCampoAnnotationDescricao(), $conf->retornaNomeCampoAnnotationNome(), 'ds_annotation_description', '250', '100', $ds_description, 1);
          $util->campoHidden('nm_annotation_description', $conf->retornaNomeCampoAnnotationNome());
        } else {
          $util->campoHidden('ds_annotation_description', $ds_description);
        }
      } else {
        $util->campoHidden('ds_annotation_entity', $ds_entity);
        $util->campoHidden('ds_annotation_date', $ds_date);
        $util->campoHidden('ds_annotation_description', $ds_description);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $cd_annotation = addslashes($_POST['cd_annotation']);
      $eh_informar_entity = addslashes($_POST['eh_informar_annotation_entity']);
      $eh_informar_date = addslashes($_POST['eh_informar_annotation_date']);
      $eh_informar_description = addslashes($_POST['eh_informar_annotation_description']);
      $ds_entity = $util->limparVariavel($_POST['ds_annotation_entity']);
      $ds_date = $util->limparVariavel($_POST['ds_annotation_date']);
      $ds_date = $dh->inverterData($ds_date);
      $ds_description = $util->limparVariavel($_POST['ds_annotation_description']);

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_entity." | ".$ds_date." | ".$ds_description;

      if ($cd_annotation > 0) {
        return $this->alteraAnnotation($cd_annotation, $ds_entity, $eh_informar_entity, $ds_date, $eh_informar_date, $ds_description, $eh_informar_description);
      } else {
        return $this->insereAnnotation($ds_entity, $eh_informar_entity, $ds_date, $eh_informar_date, $ds_description, $eh_informar_description);
      }
    } 

    public function imprimeDados($cd_annotation) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $dados = $this->selectDadosObjetoAprendizagemAnnotation($cd_annotation);

      $eh_informar_entity = $dados['eh_informar_entity'];
      $eh_informar_date = $dados['eh_informar_date'];
      $eh_informar_description = $dados['eh_informar_description'];

      $ds_entity = $dados['ds_entity'];
      $ds_date = $dados['ds_date'];
      $ds_description = $dados['ds_description'];

      $retorno = '';
      if ($eh_informar_entity || $eh_informar_date || $eh_informar_description) {
      $retorno.= "<div class=\"divConteudoUnicoObjetoAprendizagem\">\n";
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= "<b>Anotações</b>";
      $retorno.= "</p>\n";

      if (($eh_informar_entity == '1') && ($ds_entity != "")) {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoAnnotationEntidade()."</b> ".$ds_entity;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_date == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoAnnotationData()."</b> ".$dh->imprimirData($ds_date);
        $retorno.= "</p>\n";
      }
      if (($eh_informar_description == '1') && ($ds_description != "")) {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoAnnotationDescricao()."</b> ".$ds_description;
        $retorno.= "</p>\n";
      }
      $retorno.= "<div class=\"clear\"></div>\n";
      $retorno.= "</div>\n";
      }
      return $retorno;
    }

    public function imprimeDadosRetornoPesquisa($cd_annotation, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $dados = $this->selectDadosObjetoAprendizagemAnnotation($cd_annotation);

      $eh_informar_entity = $conf->retornaInformarAnnotationEntity($tipo);
      $eh_informar_date = $conf->retornaInformarAnnotationDate($tipo);
      $eh_informar_description = $conf->retornaInformarAnnotationDescription($tipo);

      $ds_entity = $dados['ds_entity'];
      $ds_date = $dados['ds_date'];
      $ds_description = $dados['ds_description'];

      $retorno = '';
      if (($eh_informar_entity == '1') && ($ds_entity != "")) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoAnnotationEntidade()."</b> ".$ds_entity."<br />\n";
      }
      if ($eh_informar_date == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoAnnotationData()."</b> ".$dh->imprimirData($ds_date)."<br />\n";
      }
      if (($eh_informar_description == '1') && ($ds_description != "")) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoAnnotationDescricao()."</b> ".$ds_description."<br />\n";
      }
      return $retorno;
    }
//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemAnnotation($cd_annotation) {
      $sql  = "SELECT * ".
              "FROM life_annotation ".
              "WHERE cd_annotation = '$cd_annotation' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ANNOTATION!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereAnnotation($ds_entity, $eh_informar_entity, $ds_date, $eh_informar_date, $ds_description, $eh_informar_description) {
      $sql = "INSERT INTO life_annotation ".
             "(ds_entity, eh_informar_entity, ds_date, eh_informar_date, ds_description, eh_informar_description) ".
             "VALUES ".
             "(\"$ds_entity\", \"$eh_informar_entity\", \"$ds_date\", \"$eh_informar_date\", \"$ds_description\", \"$eh_informar_description\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'annotation');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ANNOTATION!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_annotation) codigo ".
                "FROM life_annotation ".
                "WHERE ds_entity = '$ds_entity' ".
                "AND ds_date = '$ds_date' ".
                "AND ds_description = '$ds_description' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ANNOTATION!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraAnnotation($cd_annotation, $ds_entity, $eh_informar_entity, $ds_date, $eh_informar_date, $ds_description, $eh_informar_description) {
      $sql = "UPDATE life_annotation SET ".
             "ds_entity = \"$ds_entity\", ".
             "eh_informar_entity = \"$eh_informar_entity\", ".
             "ds_date = \"$ds_date\", ".
             "eh_informar_date = \"$eh_informar_date\", ".
             "ds_description = \"$ds_description\", ".
             "eh_informar_description = \"$eh_informar_description\" ".
             "WHERE cd_annotation = '$cd_annotation' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'annotation');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ANNOTATION!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>