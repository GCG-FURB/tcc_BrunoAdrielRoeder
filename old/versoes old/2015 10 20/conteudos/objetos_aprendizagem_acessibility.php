<?php
  class ObjetoAprendizagemAcessibility {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_acessibility > 0) {
        $this->montarFormularioEdicao($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_has_visual = $conf->retornaInformarAcessibilityHasVisual($tipo);
      $eh_informar_has_audititory = $conf->retornaInformarAcessibilityHasAudititory($tipo);
      $eh_informar_has_text = $conf->retornaInformarAcessibilityHasText($tipo);
      $eh_informar_has_tactible = $conf->retornaInformarAcessibilityHasTactible($tipo);
      $eh_informar_earl_statment = $conf->retornaInformarAcessibilityEarlStatment($tipo);
      $eh_informar_equivalent_resource = $conf->retornaInformarAcessibilityEquivalentResource($tipo);

      $cd_acessibility = "";
      $ds_has_visual = "";
      $ds_has_audititory = "";
      $ds_has_text = "";
      $ds_has_tactible = "";
      $ds_earl_statment = "";
      $ds_equivalent_resource = "";

      $this->imprimeFormularioCadastro($eh_informar_acessibility, $eh_manter_configuracoes_originais, $cd_acessibility, $ds_has_visual, $eh_informar_has_visual, $ds_has_audititory, $eh_informar_has_audititory, $ds_has_text, $eh_informar_has_text, $ds_has_tactible, $eh_informar_has_tactible, $ds_earl_statment, $eh_informar_earl_statment, $ds_equivalent_resource, $eh_informar_equivalent_resource);
    }
    
    private function montarFormularioEdicao($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemAcessibility($cd_acessibility);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_has_visual = $dados['eh_informar_has_visual'];
        $eh_informar_has_audititory = $dados['eh_informar_has_audititory'];
        $eh_informar_has_text = $dados['eh_informar_has_text'];
        $eh_informar_has_tactible = $dados['eh_informar_has_tactible'];
        $eh_informar_earl_statment = $dados['eh_informar_earl_statment'];
        $eh_informar_equivalent_resource = $dados['eh_informar_equivalent_resource'];
      } else {
        $eh_informar_has_visual = $conf->retornaInformarAcessibilityHasVisual($tipo);
        $eh_informar_has_audititory = $conf->retornaInformarAcessibilityHasAudititory($tipo);
        $eh_informar_has_text = $conf->retornaInformarAcessibilityHasText($tipo);
        $eh_informar_has_tactible = $conf->retornaInformarAcessibilityHasTactible($tipo);
        $eh_informar_earl_statment = $conf->retornaInformarAcessibilityEarlStatment($tipo);
        $eh_informar_equivalent_resource = $conf->retornaInformarAcessibilityEquivalentResource($tipo);
      }

      $ds_has_visual = $dados['ds_has_visual'];
      $ds_has_audititory = $dados['ds_has_audititory'];
      $ds_has_text = $dados['ds_has_text'];
      $ds_has_tactible = $dados['ds_has_tactible'];
      $ds_earl_statment = $dados['ds_earl_statment'];
      $ds_equivalent_resource = $dados['ds_equivalent_resource'];
      
      $this->imprimeFormularioCadastro($eh_informar_acessibility, $eh_manter_configuracoes_originais, $cd_acessibility, $ds_has_visual, $eh_informar_has_visual, $ds_has_audititory, $eh_informar_has_audititory, $ds_has_text, $eh_informar_has_text, $ds_has_tactible, $eh_informar_has_tactible, $ds_earl_statment, $eh_informar_earl_statment, $ds_equivalent_resource, $eh_informar_equivalent_resource);
    }
    
    public function imprimeFormularioCadastro($eh_informar_acessibility, $eh_manter_configuracoes_originais, $cd_acessibility, $ds_has_visual, $eh_informar_has_visual, $ds_has_audititory, $eh_informar_has_audititory, $ds_has_text, $eh_informar_has_text, $ds_has_tactible, $eh_informar_has_tactible, $ds_earl_statment, $eh_informar_earl_statment, $ds_equivalent_resource, $eh_informar_equivalent_resource) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_acessibility', $cd_acessibility);
      $util->campoHidden('eh_informar_acessibility_has_visual', $eh_informar_has_visual);
      $util->campoHidden('eh_informar_acessibility_has_audititory', $eh_informar_has_audititory);
      $util->campoHidden('eh_informar_acessibility_has_text', $eh_informar_has_text);
      $util->campoHidden('eh_informar_acessibility_has_tactible', $eh_informar_has_tactible);
      $util->campoHidden('eh_informar_acessibility_earl_statment', $eh_informar_earl_statment);
      $util->campoHidden('eh_informar_acessibility_equivalent_resource', $eh_informar_equivalent_resource);

      $eh_obrigatorio_has_visual = $conf->retornaInformarAcessibilityHasVisual('b');
      $eh_obrigatorio_has_audititory = $conf->retornaInformarAcessibilityHasAudititory('b');
      $eh_obrigatorio_has_text = $conf->retornaInformarAcessibilityHasText('b');
      $eh_obrigatorio_has_tactible = $conf->retornaInformarAcessibilityHasTactible('b');
      $eh_obrigatorio_earl_statment = $conf->retornaInformarAcessibilityEarlStatment('b');
      $eh_obrigatorio_equivalent_resource = $conf->retornaInformarAcessibilityEquivalentResource('b');
      $util->campoHidden('eh_obrigatorio_acessibility_has_visual', $eh_obrigatorio_has_visual);
      $util->campoHidden('eh_obrigatorio_acessibility_has_audititory', $eh_obrigatorio_has_audititory);
      $util->campoHidden('eh_obrigatorio_acessibility_has_text', $eh_obrigatorio_has_text);
      $util->campoHidden('eh_obrigatorio_acessibility_has_tactible', $eh_obrigatorio_has_tactible);
      $util->campoHidden('eh_obrigatorio_acessibility_earl_statment', $eh_obrigatorio_earl_statment);
      $util->campoHidden('eh_obrigatorio_acessibility_equivalent_resource', $eh_obrigatorio_equivalent_resource);
      
      if ($eh_informar_acessibility == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Acessibilidade');
      
        if ($eh_informar_has_visual == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_has_visual, $conf->retornaDescricaoCampoAcessibilityElementosVisuais(), 'ds_acessibility_has_visual', '250', '840', $ds_has_visual, 1);
        } else {
          $util->campoHidden('ds_acessibility_has_visual', $ds_has_visual);
        }                                                  
        if ($eh_informar_has_audititory == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_has_audititory, $conf->retornaDescricaoCampoAcessibilityElementosSonoros(), 'ds_acessibility_has_audititory', '250', '840', $ds_has_audititory, 1);
        } else {
          $util->campoHidden('ds_acessibility_has_audititory', $ds_has_audititory);
        }     
        if ($eh_informar_has_text == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_has_text, $conf->retornaDescricaoCampoAcessibilityElementosTexto(), 'ds_acessibility_has_text', '250', '840', $ds_has_text, 1);
        } else {
          $util->campoHidden('ds_acessibility_has_text', $ds_has_text);
        }                                                   
        if ($eh_informar_has_tactible == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_has_tactible, $conf->retornaDescricaoCampoAcessibilityElementosTáteis(), 'ds_acessibility_has_tactible', '250', '840', $ds_has_tactible, 1);
        } else {
          $util->campoHidden('ds_acessibility_has_tactible', $ds_has_tactible);
        }     
        if ($eh_informar_earl_statment == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_earl_statment, $conf->retornaDescricaoCampoAcessibilityPadraoEARL(), 'ds_acessibility_earl_statment', '250', '840', $ds_earl_statment, 1);
        } else {
          $util->campoHidden('ds_acessibility_earl_statment', $ds_earl_statment);
        }                                            
        if ($eh_informar_equivalent_resource == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_equivalent_resource, $conf->retornaDescricaoCampoAcessibilityRecursosEquivalentes(), 'ds_acessibility_equivalent_resource', '250', '840', $ds_equivalent_resource, 1);
        } else {
          $util->campoHidden('ds_acessibility_equivalent_resource', $ds_equivalent_resource);
        }                                                      
      } else {
        $util->campoHidden('ds_acessibility_has_visual', $ds_has_visual);
        $util->campoHidden('ds_acessibility_has_audititory', $ds_has_audititory);
        $util->campoHidden('ds_acessibility_has_text', $ds_has_text);
        $util->campoHidden('ds_acessibility_has_tactible', $ds_has_tactible);
        $util->campoHidden('ds_acessibility_earl_statment', $ds_earl_statment);
        $util->campoHidden('ds_acessibility_equivalent_resource', $ds_equivalent_resource);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
                                  
      $cd_acessibility = addslashes($_POST['cd_acessibility']);
      $eh_informar_has_visual = addslashes($_POST['eh_informar_acessibility_has_visual']);
      $eh_informar_has_audititory = addslashes($_POST['eh_informar_acessibility_has_audititory']);
      $eh_informar_has_text = addslashes($_POST['eh_informar_acessibility_has_text']);
      $eh_informar_has_tactible = addslashes($_POST['eh_informar_acessibility_has_tactible']);
      $eh_informar_earl_statment = addslashes($_POST['eh_informar_acessibility_earl_statment']);
      $eh_informar_equivalent_resource = addslashes($_POST['eh_informar_acessibility_equivalent_resource']);
      $ds_has_visual = $util->limparVariavel($_POST['ds_acessibility_has_visual']);
      $ds_has_audititory = $util->limparVariavel($_POST['ds_acessibility_has_audititory']);
      $ds_has_text = $util->limparVariavel($_POST['ds_acessibility_has_text']);
      $ds_has_tactible = $util->limparVariavel($_POST['ds_acessibility_has_tactible']);
      $ds_earl_statment = $util->limparVariavel($_POST['ds_acessibility_earl_statment']);
      $ds_equivalent_resource = $util->limparVariavel($_POST['ds_acessibility_equivalent_resource']);                                  


      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_has_visual." | ".$ds_has_audititory." | ".$ds_has_text." | ".$ds_has_tactible." | ".$ds_earl_statment." | ".$ds_equivalent_resource;

      if ($cd_acessibility > 0) {
        return $this->alteraAcessibility($cd_acessibility, $ds_has_visual, $eh_informar_has_visual, $ds_has_audititory, $eh_informar_has_audititory, $ds_has_text, $eh_informar_has_text, $ds_has_tactible, $eh_informar_has_tactible, $ds_earl_statment, $eh_informar_earl_statment, $ds_equivalent_resource, $eh_informar_equivalent_resource);
      } else {
        return $this->insereAcessibility($ds_has_visual, $eh_informar_has_visual, $ds_has_audititory, $eh_informar_has_audititory, $ds_has_text, $eh_informar_has_text, $ds_has_tactible, $eh_informar_has_tactible, $ds_earl_statment, $eh_informar_earl_statment, $ds_equivalent_resource, $eh_informar_equivalent_resource);
      }
    } 

    public function imprimeDados($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemAcessibility($cd_acessibility);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_has_visual = $dados['eh_informar_has_visual'];
        $eh_informar_has_audititory = $dados['eh_informar_has_audititory'];
        $eh_informar_has_text = $dados['eh_informar_has_text'];
        $eh_informar_has_tactible = $dados['eh_informar_has_tactible'];
        $eh_informar_earl_statment = $dados['eh_informar_earl_statment'];
        $eh_informar_equivalent_resource = $dados['eh_informar_equivalent_resource'];
      } else {
        $eh_informar_has_visual = $conf->retornaInformarAcessibilityHasVisual($tipo);
        $eh_informar_has_audititory = $conf->retornaInformarAcessibilityHasAudititory($tipo);
        $eh_informar_has_text = $conf->retornaInformarAcessibilityHasText($tipo);
        $eh_informar_has_tactible = $conf->retornaInformarAcessibilityHasTactible($tipo);
        $eh_informar_earl_statment = $conf->retornaInformarAcessibilityEarlStatment($tipo);
        $eh_informar_equivalent_resource = $conf->retornaInformarAcessibilityEquivalentResource($tipo);
      }

      $ds_has_visual = $dados['ds_has_visual'];
      $ds_has_audititory = $dados['ds_has_audititory'];
      $ds_has_text = $dados['ds_has_text'];
      $ds_has_tactible = $dados['ds_has_tactible'];
      $ds_earl_statment = $dados['ds_earl_statment'];
      $ds_equivalent_resource = $dados['ds_equivalent_resource'];

      $retorno = '';
            
      if ($eh_informar_acessibility == '1') {
        if ($eh_informar_has_visual == '1') {
          $retorno.= $conf->retornaDescricaoCampoAcessibilityElementosVisuais().': '.$ds_has_visual.'<br />';
        }                                                  
        if ($eh_informar_has_audititory == '1') {
          $retorno.= $conf->retornaDescricaoCampoAcessibilityElementosSonoros().': '.$ds_has_audititory.'<br />';
        }     
        if ($eh_informar_has_text == '1') {
          $retorno.= $conf->retornaDescricaoCampoAcessibilityElementosTexto().': '.$ds_has_text.'<br />';
        }                                                   
        if ($eh_informar_has_tactible == '1') {
          $retorno.= $conf->retornaDescricaoCampoAcessibilityElementosTáteis().': '.$ds_has_tactible.'<br />';
        }     
        if ($eh_informar_earl_statment == '1') {
          $retorno.= $conf->retornaDescricaoCampoAcessibilityPadraoEARL().': '.$ds_earl_statment.'<br />';
        }                                            
        if ($eh_informar_equivalent_resource == '1') {
          $retorno.= $conf->retornaDescricaoCampoAcessibilityRecursosEquivalentes().': '.$ds_equivalent_resource.'<br />';
        }                                                      
      }
      return $retorno;
    }


//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemAcessibility($cd_acessibility) {
      $sql  = "SELECT * ".
              "FROM life_acessibility ".
              "WHERE cd_acessibility = '$cd_acessibility' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ACESSIBILITY!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereAcessibility($ds_has_visual, $eh_informar_has_visual, $ds_has_audititory, $eh_informar_has_audititory, $ds_has_text, $eh_informar_has_text, $ds_has_tactible, $eh_informar_has_tactible, $ds_earl_statment, $eh_informar_earl_statment, $ds_equivalent_resource, $eh_informar_equivalent_resource) {
      $sql = "INSERT INTO life_acessibility ".
             "(ds_has_visual, eh_informar_has_visual, ds_has_audititory, eh_informar_has_audititory, ds_has_text, eh_informar_has_text, ds_has_tactible, eh_informar_has_tactible, ds_earl_statment, eh_informar_earl_statment, ds_equivalent_resource, eh_informar_equivalent_resource) ".
             "VALUES ".
             "(\"$ds_has_visual\", \"$eh_informar_has_visual\", \"$ds_has_audititory\", \"$eh_informar_has_audititory\", \"$ds_has_text\", \"$eh_informar_has_text\", \"$ds_has_tactible\", \"$eh_informar_has_tactible\", \"$ds_earl_statment\", \"$eh_informar_earl_statment\", \"$ds_equivalent_resource\", \"$eh_informar_equivalent_resource\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'acessibility');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ACESSIBILITY!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_acessibility) codigo ".
                "FROM life_acessibility ".
                "WHERE ds_has_visual = '$ds_has_visual' ".
                "AND ds_has_audititory = '$ds_has_audititory' ".
                "AND ds_has_text = '$ds_has_text' ".
                "AND ds_has_tactible = '$ds_has_tactible' ".
                "AND ds_earl_statment = '$ds_earl_statment' ".
                "AND ds_equivalent_resource = '$ds_equivalent_resource' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ACESSIBILITY!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraAcessibility($cd_acessibility, $ds_has_visual, $eh_informar_has_visual, $ds_has_audititory, $eh_informar_has_audititory, $ds_has_text, $eh_informar_has_text, $ds_has_tactible, $eh_informar_has_tactible, $ds_earl_statment, $eh_informar_earl_statment, $ds_equivalent_resource, $eh_informar_equivalent_resource) {
      $sql = "UPDATE life_acessibility SET ".
             "ds_has_visual = \"$ds_has_visual\", ".
             "eh_informar_has_visual = \"$eh_informar_has_visual\", ".
             "ds_has_audititory = \"$ds_has_audititory\", ".
             "eh_informar_has_audititory = \"$eh_informar_has_audititory\", ".
             "ds_has_text = \"$ds_has_text\", ".
             "eh_informar_has_text = \"$eh_informar_has_text\", ".
             "ds_has_tactible = \"$ds_has_tactible\", ".
             "eh_informar_has_tactible = \"$eh_informar_has_tactible\", ".
             "ds_earl_statment = \"$ds_earl_statment\", ".
             "eh_informar_earl_statment = \"$eh_informar_earl_statment\", ".
             "ds_equivalent_resource = \"$ds_equivalent_resource\", ".
             "eh_informar_equivalent_resource = \"$eh_informar_equivalent_resource\" ".
             "WHERE cd_acessibility = '$cd_acessibility' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'acessibility');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ACESSIBILITY!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>