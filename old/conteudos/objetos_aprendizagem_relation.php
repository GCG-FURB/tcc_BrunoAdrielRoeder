<?php
  class ObjetoAprendizagemRelation {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_relation > 0) {
        $this->montarFormularioEdicao($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_relation, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_kind = $conf->retornaInformarRelationKind($tipo);
      $eh_informar_resource = $conf->retornaInformarRelationResource($tipo);

      $cd_relation = "";
      $ds_kind = "";
      $ds_resource = "";

      $this->imprimeFormularioCadastro($eh_informar_relation, $eh_manter_configuracoes_originais, $cd_relation, $ds_kind, $eh_informar_kind, $ds_resource, $eh_informar_resource);
    }
    
    private function montarFormularioEdicao($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemRelation($cd_relation);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_kind = $dados['eh_informar_kind'];
        $eh_informar_resource = $dados['eh_informar_resource'];
      } else {
        $eh_informar_kind = $conf->retornaInformarRelationKind($tipo);
        $eh_informar_resource = $conf->retornaInformarRelationResource($tipo);
      }
      
      $ds_kind = $dados['ds_kind'];
      $ds_resource = $dados['ds_resource'];

      $this->imprimeFormularioCadastro($eh_informar_relation, $eh_manter_configuracoes_originais, $cd_relation, $ds_kind, $eh_informar_kind, $ds_resource, $eh_informar_resource);
    }
    
    public function imprimeFormularioCadastro($eh_informar_relation, $eh_manter_configuracoes_originais, $cd_relation, $ds_kind, $eh_informar_kind, $ds_resource, $eh_informar_resource) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_relation', $cd_relation);
      $util->campoHidden('eh_informar_relation_kind', $eh_informar_kind);
      $util->campoHidden('eh_informar_relation_resource', $eh_informar_resource);

      $eh_obrigatorio_kind = $conf->retornaInformarRelationKind('b');
      $eh_obrigatorio_resource = $conf->retornaInformarRelationResource('b');
      $util->campoHidden('eh_obrigatorio_relation_kind', $eh_obrigatorio_kind);
      $util->campoHidden('eh_obrigatorio_relation_resource', $eh_obrigatorio_resource);
            
      if ($eh_informar_relation == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações sobre Relação');
      
        if ($eh_informar_kind == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_kind, $conf->retornaDescricaoCampoRelationTipo(), $conf->retornaNomeCampoRelationTipo(), 'ds_relation_kind', '250', '100', $ds_kind, 1);
          $util->campoHidden('nm_relation_kind', $conf->retornaNomeCampoRelationTipo());
        } else {
          $util->campoHidden('ds_relation_kind', $ds_kind);
        }                                                                                       
        if ($eh_informar_resource == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_resource, $conf->retornaDescricaoCampoRelationRecurso(), $conf->retornaNomeCampoRelationRecurso(), 'ds_relation_resource', '250', '100', $ds_resource, 1);
          $util->campoHidden('nm_relation_resource', $conf->retornaNomeCampoRelationRecurso());
        } else {
          $util->campoHidden('ds_relation_resource', $ds_resource);
        }                                                                                       
      } else {
        $util->campoHidden('ds_relation_kind', $ds_kind);
        $util->campoHidden('ds_relation_resource', $ds_resource);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_relation = addslashes($_POST['cd_relation']);
      $eh_informar_kind = addslashes($_POST['eh_informar_relation_kind']);
      $eh_informar_resource = addslashes($_POST['eh_informar_relation_resource']);
      $ds_kind = $util->limparVariavel($_POST['ds_relation_kind']);
      $ds_resource = $util->limparVariavel($_POST['ds_relation_resource']);

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_kind." | ".$ds_resource;

      if ($cd_relation > 0) {
        return $this->alteraRelation($cd_relation, $ds_kind, $eh_informar_kind, $ds_resource, $eh_informar_resource);
      } else {
        return $this->insereRelation($ds_kind, $eh_informar_kind, $ds_resource, $eh_informar_resource);
      }
    } 

    public function imprimeDados($cd_relation) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemRelation($cd_relation);

      $eh_informar_kind = $dados['eh_informar_kind'];
      $eh_informar_resource = $dados['eh_informar_resource'];

      $ds_kind = $dados['ds_kind'];
      $ds_resource = $dados['ds_resource'];
            
      $retorno = '';      
      $retorno.= "<div class=\"divConteudoUnicoObjetoAprendizagem\">\n";
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= "<b>Informações sobre Relação</b>";
      $retorno.= "</p>\n";

      if (($eh_informar_kind == '1') && ($ds_kind != "")) {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoRelationTipo()."</b> ".$ds_kind;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_resource == '1') && ($ds_resource != "")) {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoRelationRecurso()."</b> ".$ds_resource;
        $retorno.= "</p>\n";
      }
      $retorno.= "<div class=\"clear\"></div>\n";
      $retorno.= "</div>\n";
      return $retorno;
    }

    public function imprimeDadosRetornoPesquisa($cd_relation, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemRelation($cd_relation);

      $eh_informar_kind = $conf->retornaInformarRelationKind($tipo);
      $eh_informar_resource = $conf->retornaInformarRelationResource($tipo);

      $ds_kind = $dados['ds_kind'];
      $ds_resource = $dados['ds_resource'];

      $retorno = '';
      if (($eh_informar_kind == '1') && ($ds_kind != "")) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoRelationTipo()."</b> ".$ds_kind."<br />\n";
      }
      if (($eh_informar_resource == '1') && ($ds_resource != "")) {
        $retorno.= "<b>".$conf->retornaInformacaoCampoRelationRecurso()."</b> ".$ds_resource."<br />\n";
      }
      return $retorno;
    }
//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemRelation($cd_relation) {
      $sql  = "SELECT * ".
              "FROM life_relation ".
              "WHERE cd_relation = '$cd_relation' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA RELATION!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereRelation($ds_kind, $eh_informar_kind, $ds_resource, $eh_informar_resource) {
      $sql = "INSERT INTO life_relation ".
             "(ds_kind, eh_informar_kind, ds_resource, eh_informar_resource) ".
             "VALUES ".
             "(\"$ds_kind\", \"$eh_informar_kind\", \"$ds_resource\", \"$eh_informar_resource\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'relation');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA RELATION!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_relation) codigo ".
                "FROM life_relation ".
                "WHERE ds_kind = '$ds_kind' ".
                "AND ds_resource = '$ds_resource' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA RELATION!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraRelation($cd_relation, $ds_kind, $eh_informar_kind, $ds_resource, $eh_informar_resource) {
      $sql = "UPDATE life_relation SET ".
             "ds_kind = \"$ds_kind\", ".
             "eh_informar_kind = \"$eh_informar_kind\", ".
             "ds_resource = \"$ds_resource\", ".
             "eh_informar_resource = \"$eh_informar_resource\" ".
             "WHERE cd_relation = '$cd_relation' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'relation');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA RELATION!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>