<?php
  class ObjetoAprendizagemRights {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_rights > 0) {
        $this->montarFormularioEdicao($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_rights, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_cost = $conf->retornaInformarRightsCost($tipo);
      $eh_informar_copyright_and_other_restrictions = $conf->retornaInformarRightsCopyrightAndOtherRestrictions($tipo);
      $eh_informar_description = $conf->retornaInformarRightsDescription($tipo);

      $cd_rights = "";
      $ds_cost = "";
      $ds_copyright_and_other_restrictions = "";
      $ds_description = "";

      $this->imprimeFormularioCadastro($eh_informar_rights, $eh_manter_configuracoes_originais, $cd_rights, $ds_cost, $eh_informar_cost, $ds_copyright_and_other_restrictions, $eh_informar_copyright_and_other_restrictions, $ds_description, $eh_informar_description);
    }
    
    private function montarFormularioEdicao($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemRights($cd_rights);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_cost = $dados['eh_informar_cost'];
        $eh_informar_copyright_and_other_restrictions = $dados['eh_informar_copyright_and_other_restrictions'];
        $eh_informar_description = $dados['eh_informar_description'];
      } else {
        $eh_informar_cost = $conf->retornaInformarRightsCost($tipo);
        $eh_informar_copyright_and_other_restrictions = $conf->retornaInformarRightsCopyrightAndOtherRestrictions($tipo);
        $eh_informar_description = $conf->retornaInformarRightsDescription($tipo);
      }

      $ds_cost = $dados['ds_cost'];
      $ds_copyright_and_other_restrictions = $dados['ds_copyright_and_other_restrictions'];
      $ds_description = $dados['ds_description'];
      
      $this->imprimeFormularioCadastro($eh_informar_rights, $eh_manter_configuracoes_originais, $cd_rights, $ds_cost, $eh_informar_cost, $ds_copyright_and_other_restrictions, $eh_informar_copyright_and_other_restrictions, $ds_description, $eh_informar_description);
    }
    
    public function imprimeFormularioCadastro($eh_informar_rights, $eh_manter_configuracoes_originais, $cd_rights, $ds_cost, $eh_informar_cost, $ds_copyright_and_other_restrictions, $eh_informar_copyright_and_other_restrictions, $ds_description, $eh_informar_description) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_rights', $cd_rights);
      $util->campoHidden('eh_informar_rights_cost', $eh_informar_cost);
      $util->campoHidden('eh_informar_rights_copyright_and_other_restrictions', $eh_informar_copyright_and_other_restrictions);
      $util->campoHidden('eh_informar_rights_description', $eh_informar_description);
      
      $eh_obrigatorio_cost = $conf->retornaInformarRightsCost('b');
      $eh_obrigatorio_copyright_and_other_restrictions = $conf->retornaInformarRightsCopyrightAndOtherRestrictions('b');
      $eh_obrigatorio_description = $conf->retornaInformarRightsDescription('b');
      $util->campoHidden('eh_obrigatorio_rights_cost', $eh_obrigatorio_cost);
      $util->campoHidden('eh_obrigatorio_rights_copyright_and_other_restrictions', $eh_obrigatorio_copyright_and_other_restrictions);
      $util->campoHidden('eh_obrigatorio_rights_description', $eh_obrigatorio_description);

      if ($eh_informar_rights == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações sobre Direitos Autorais');
      
        if ($eh_informar_cost == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_cost, $conf->retornaDescricaoCampoRightsCusto(), $conf->retornaNomeCampoRightsCusto(), 'ds_rights_cost', '250', '100', $ds_cost, 1);
          $util->campoHidden('nm_rights_cost', $conf->retornaNomeCampoRightsCusto());
        } else {
          $util->campoHidden('ds_rights_cost', $ds_cost);
        }
        if ($eh_informar_copyright_and_other_restrictions == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_copyright_and_other_restrictions, $conf->retornaDescricaoCampoRightsDireitosAutorOutrasRestricoes(), $conf->retornaNomeCampoRightsDireitosAutorOutrasRestricoes(), 'ds_rights_copyright_and_other_restrictions', '250', '100', $ds_copyright_and_other_restrictions, 1);
          $util->campoHidden('nm_rights_copyright_and_other_restrictions', $conf->retornaNomeCampoRightsDireitosAutorOutrasRestricoes());
        } else {
          $util->campoHidden('ds_rights_copyright_and_other_restrictions', $ds_copyright_and_other_restrictions);
        }
        if ($eh_informar_description == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_description, $conf->retornaDescricaoCampoRightsDescricao(), $conf->retornaNomeCampoRightsNome(), 'ds_rights_description', '250', '100', $ds_description, 1);
          $util->campoHidden('nm_rights_description', $conf->retornaNomeCampoRightsNome());
        } else {
          $util->campoHidden('ds_rights_description', $ds_description);
        }
      } else {
        $util->campoHidden('ds_rights_cost', $ds_cost);
        $util->campoHidden('ds_rights_copyright_and_other_restrictions', $ds_copyright_and_other_restrictions);
        $util->campoHidden('ds_rights_description', $ds_description);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_rights = addslashes($_POST['cd_rights']);
      $eh_informar_cost = addslashes($_POST['eh_informar_rights_cost']);
      $eh_informar_copyright_and_other_restrictions = addslashes($_POST['eh_informar_rights_copyright_and_other_restrictions']);
      $eh_informar_description = addslashes($_POST['eh_informar_rights_description']);
      $ds_cost = $util->limparVariavel($_POST['ds_rights_cost']);
      $ds_copyright_and_other_restrictions = $util->limparVariavel($_POST['ds_rights_copyright_and_other_restrictions']);
      $ds_description = $util->limparVariavel($_POST['ds_rights_description']);

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_cost." | ".$ds_copyright_and_other_restrictions." | ".$ds_description;

      if ($cd_rights > 0) {
        return $this->alteraRights($cd_rights, $ds_cost, $eh_informar_cost, $ds_copyright_and_other_restrictions, $eh_informar_copyright_and_other_restrictions, $ds_description, $eh_informar_description);
      } else {
        return $this->insereRights($ds_cost, $eh_informar_cost, $ds_copyright_and_other_restrictions, $eh_informar_copyright_and_other_restrictions, $ds_description, $eh_informar_description);
      }
    } 

    public function imprimeDados($cd_rights) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemRights($cd_rights);

      $eh_informar_cost = $dados['eh_informar_cost'];
      $eh_informar_copyright_and_other_restrictions = $dados['eh_informar_copyright_and_other_restrictions'];
      $eh_informar_description = $dados['eh_informar_description'];

      $ds_cost = $dados['ds_cost'];
      $ds_copyright_and_other_restrictions = $dados['ds_copyright_and_other_restrictions'];
      $ds_description = $dados['ds_description'];

      $retorno = '';
      $retorno.= "<div class=\"divConteudoUnicoObjetoAprendizagem\">\n";
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= "<b>Informações sobre Direitos Autorais</b>";
      $retorno.= "</p>\n";

      if (($eh_informar_cost == '1') && ($ds_cost != '')) {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaDescricaoCampoRightsCusto()."</b> ".$ds_cost;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_copyright_and_other_restrictions == '1') && ($ds_copyright_and_other_restrictions != '')) {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaDescricaoCampoRightsDireitosAutorOutrasRestricoes()."</b> ".$ds_copyright_and_other_restrictions;
        $retorno.= "</p>\n";
      }
      if (($eh_informar_description == '1') && ($ds_description != '')) {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaDescricaoCampoRightsDescricao()."</b> ".$ds_description;
        $retorno.= "</p>\n";
      }
      $retorno.= "<div class=\"clear\"></div>\n";
      $retorno.= "</div>\n";
      return $retorno;
    }

    public function imprimeDadosRetornoPesquisa($cd_rights, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemRights($cd_rights);

      $eh_informar_cost = $conf->retornaInformarRightsCost($tipo);
      $eh_informar_copyright_and_other_restrictions = $conf->retornaInformarRightsCopyrightAndOtherRestrictions($tipo);
      $eh_informar_description = $conf->retornaInformarRightsDescription($tipo);

      $ds_cost = $dados['ds_cost'];
      $ds_copyright_and_other_restrictions = $dados['ds_copyright_and_other_restrictions'];
      $ds_description = $dados['ds_description'];

      $retorno = '';
      if (($eh_informar_cost == '1') && ($ds_cost != '')) {
        $retorno.= "<b>".$conf->retornaDescricaoCampoRightsCusto()."</b> ".$ds_cost."<br />\n";
      }
      if (($eh_informar_copyright_and_other_restrictions == '1') && ($ds_copyright_and_other_restrictions != '')) {
        $retorno.= "<b>".$conf->retornaDescricaoCampoRightsDireitosAutorOutrasRestricoes()."</b> ".$ds_copyright_and_other_restrictions."<br />\n";
      }
      if (($eh_informar_description == '1') && ($ds_description != '')) {
        $retorno.= "<b>".$conf->retornaDescricaoCampoRightsDescricao()."</b> ".$ds_description."<br />\n";
      }
      return $retorno;
    }
//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemRights($cd_rights) {
      $sql  = "SELECT * ".
              "FROM life_rights ".
              "WHERE cd_rights = '$cd_rights' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA RIGHTS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereRights($ds_cost, $eh_informar_cost, $ds_copyright_and_other_restrictions, $eh_informar_copyright_and_other_restrictions, $ds_description, $eh_informar_description) {
      $sql = "INSERT INTO life_rights ".
             "(ds_cost, eh_informar_cost, ds_copyright_and_other_restrictions, eh_informar_copyright_and_other_restrictions, ds_description, eh_informar_description) ".
             "VALUES ".
             "(\"$ds_cost\", \"$eh_informar_cost\", \"$ds_copyright_and_other_restrictions\", \"$eh_informar_copyright_and_other_restrictions\", \"$ds_description\", \"$eh_informar_description\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'rights');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA RIGHTS!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_rights) codigo ".
                "FROM life_rights ".
                "WHERE ds_cost = '$ds_cost' ".
                "AND ds_copyright_and_other_restrictions = '$ds_copyright_and_other_restrictions' ".
                "AND ds_description = '$ds_description' ";   
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA RIGHTS!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraRights($cd_rights, $ds_cost, $eh_informar_cost, $ds_copyright_and_other_restrictions, $eh_informar_copyright_and_other_restrictions, $ds_description, $eh_informar_description) {
      $sql = "UPDATE life_rights SET ".
             "ds_cost = \"$ds_cost\", ".
             "eh_informar_cost = \"$eh_informar_cost\", ".
             "ds_copyright_and_other_restrictions = \"$ds_copyright_and_other_restrictions\", ".
             "eh_informar_copyright_and_other_restrictions = \"$eh_informar_copyright_and_other_restrictions\", ".
             "ds_description = \"$ds_description\", ".
             "eh_informar_description = \"$eh_informar_description\" ".
             "WHERE cd_rights = '$cd_rights' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'rights');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA RIGHTS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>