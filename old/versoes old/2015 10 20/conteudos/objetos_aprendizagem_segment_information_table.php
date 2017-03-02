<?php
  class ObjetoAprendizagemSegmentInformationTable {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_segment_information_table > 0) {
        $this->montarFormularioEdicao($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_segment_list = $conf->retornaInformarSegmentInformationTableSegmentList($tipo);
      $eh_informar_segmente_group_list = $conf->retornaInformarSegmentInformationTableSegmenteGroupList($tipo);

      $cd_segment_information_table = "";
      $ds_segment_list = "";
      $ds_segmente_group_list = "";

      $this->imprimeFormularioCadastro($eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $cd_segment_information_table, $ds_segment_list, $eh_informar_segment_list, $ds_segmente_group_list, $eh_informar_segmente_group_list);
    }
    
    private function montarFormularioEdicao($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemSegmentInformationTable($cd_segment_information_table);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_segment_list = $dados['eh_informar_segment_list'];
        $eh_informar_segmente_group_list = $dados['eh_informar_segmente_group_list'];
      } else {
        $eh_informar_segment_list = $conf->retornaInformarSegmentInformationTableSegmentList($tipo);
        $eh_informar_segmente_group_list = $conf->retornaInformarSegmentInformationTableSegmenteGroupList($tipo);
      }
      
      $ds_segment_list = $dados['ds_segment_list'];
      $ds_segmente_group_list = $dados['ds_segmente_group_list'];

      $this->imprimeFormularioCadastro($eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $cd_segment_information_table, $ds_segment_list, $eh_informar_segment_list, $ds_segmente_group_list, $eh_informar_segmente_group_list);
    }
    
    public function imprimeFormularioCadastro($eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $cd_segment_information_table, $ds_segment_list, $eh_informar_segment_list, $ds_segmente_group_list, $eh_informar_segmente_group_list) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_segment_information_table', $cd_segment_information_table);
      $util->campoHidden('eh_informar_segment_information_table_segment_list', $eh_informar_segment_list);
      $util->campoHidden('eh_informar_segment_information_table_segmente_group_list', $eh_informar_segmente_group_list);
      
      $eh_obrigatorio_segment_list = $conf->retornaInformarSegmentInformationTableSegmentList('b');
      $eh_obrigatorio_segmente_group_list = $conf->retornaInformarSegmentInformationTableSegmenteGroupList('b');
      $util->campoHidden('eh_obrigatorio_segment_information_table_segment_list', $eh_obrigatorio_segment_list);
      $util->campoHidden('eh_obrigatorio_segment_information_table_segmente_group_list', $eh_obrigatorio_segmente_group_list);

      if ($eh_informar_segment_information_table == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Segmentos');
      
        if ($eh_informar_segment_list == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_segment_list, $conf->retornaDescricaoCampoSegmentInformation_tableListaSegmentos(), 'ds_segment_information_table_segment_list', '250', '840', $ds_segment_list, 1);
        } else {
          $util->campoHidden('ds_segment_information_table_segment_list', $ds_segment_list);
        }                        
        if ($eh_informar_segmente_group_list == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_segmente_group_list, $conf->retornaDescricaoCampoSegmentInformation_tableListaSegmentoGrupo(), 'ds_segment_information_table_segmente_group_list', '250', '840', $ds_segmente_group_list, 1);
        } else {
          $util->campoHidden('ds_segment_information_table_segmente_group_list', $ds_segmente_group_list);
        }                        
      } else {
        $util->campoHidden('ds_segment_information_table_segment_list', $ds_segment_list);
        $util->campoHidden('ds_segment_information_table_segmente_group_list', $ds_segmente_group_list);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
                          
      $cd_segment_information_table = addslashes($_POST['cd_segment_information_table']);
      $eh_informar_segment_list = addslashes($_POST['eh_informar_segment_information_table_segment_list']);
      $eh_informar_segmente_group_list = addslashes($_POST['eh_informar_segment_information_table_segmente_group_list']);
      $ds_segment_list = $util->limparVariavel($_POST['ds_segment_information_table_segment_list']);
      $ds_segmente_group_list = $util->limparVariavel($_POST['ds_segment_information_table_segmente_group_list']);                          

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_segment_list." | ".$ds_segmente_group_list;

      if ($cd_segment_information_table > 0) {
        return $this->alteraSegmentInformationTable($cd_segment_information_table, $ds_segment_list, $eh_informar_segment_list, $ds_segmente_group_list, $eh_informar_segmente_group_list);
      } else {
        return $this->insereSegmentInformationTable($ds_segment_list, $eh_informar_segment_list, $ds_segmente_group_list, $eh_informar_segmente_group_list);
      }
    } 

    public function imprimeDados($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemSegmentInformationTable($cd_segment_information_table);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_segment_list = $dados['eh_informar_segment_list'];
        $eh_informar_segmente_group_list = $dados['eh_informar_segmente_group_list'];
      } else {
        $eh_informar_segment_list = $conf->retornaInformarSegmentInformationTableSegmentList($tipo);
        $eh_informar_segmente_group_list = $conf->retornaInformarSegmentInformationTableSegmenteGroupList($tipo);
      }
      
      $ds_segment_list = $dados['ds_segment_list'];
      $ds_segmente_group_list = $dados['ds_segmente_group_list'];

      $retorno = '';
        
      if ($eh_informar_segment_information_table == '1') {
        if ($eh_informar_segment_list == '1') {
          $retorno.= $conf->retornaDescricaoCampoSegmentInformation_tableListaSegmentos().': '.$ds_segment_list.'<br />';
        }                        
        if ($eh_informar_segmente_group_list == '1') {
          $retorno.= $conf->retornaDescricaoCampoSegmentInformation_tableListaSegmentoGrupo().': '.$ds_segmente_group_list.'<br />';
        }                        
      }
      return $retorno;
    }

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemSegmentInformationTable($cd_segment_information_table) {
      $sql  = "SELECT * ".
              "FROM life_segment_information_table ".
              "WHERE cd_segment_information_table = '$cd_segment_information_table' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SEGMENT INFORMATION TABLE!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereSegmentInformationTable($ds_segment_list, $eh_informar_segment_list, $ds_segmente_group_list, $eh_informar_segmente_group_list) {
      $sql = "INSERT INTO life_segment_information_table ".
             "(ds_segment_list, eh_informar_segment_list, ds_segmente_group_list, eh_informar_segmente_group_list) ".
             "VALUES ".
             "(\"$ds_segment_list\", \"$eh_informar_segment_list\", \"$ds_segmente_group_list\", \"$eh_informar_segmente_group_list\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'segment_information_table');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SEGMENT INFORMATION TABLE!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_segment_information_table) codigo ".
                "FROM life_segment_information_table ".
                "WHERE ds_segment_list = '$ds_segment_list' ".
                "AND ds_segmente_group_list = '$ds_segmente_group_list' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SEGMENT INFORMATION TABLE!");                       
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraSegmentInformationTable($cd_segment_information_table, $ds_segment_list, $eh_informar_segment_list, $ds_segmente_group_list, $eh_informar_segmente_group_list) {
      $sql = "UPDATE life_segment_information_table SET ".
             "ds_segment_list = \"$ds_segment_list\", ".
             "eh_informar_segment_list = \"$eh_informar_segment_list\", ".
             "ds_segmente_group_list = \"$ds_segmente_group_list\", ".
             "eh_informar_segmente_group_list = \"$eh_informar_segmente_group_list\" ".
             "WHERE cd_segment_information_table = '$cd_segment_information_table' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'segment_information_table');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SEGMENT INFORMATION TABLE!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>