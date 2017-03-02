<?php
  class ObjetoAprendizagemLyfeCycle {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_lyfe_cycle > 0) {
        $this->montarFormularioEdicao($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_version = $conf->retornaInformarLyfeCycleVersion($tipo);
      $eh_informar_status = $conf->retornaInformarLyfeCycleStatus($tipo);
      $eh_informar_contribute = $conf->retornaInformarLyfeCycleContribute($tipo);

      $cd_lyfe_cycle = "";
      $ds_version = "";
      $cd_status = "";
      $ds_contribute = "";

      $this->imprimeFormularioCadastro($eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $cd_lyfe_cycle, $ds_version, $eh_informar_version, $cd_status, $eh_informar_status, $ds_contribute, $eh_informar_contribute);
    }
    
    private function montarFormularioEdicao($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $dados = $this->selectDadosObjetoAprendizagemLyfeCycle($cd_lyfe_cycle);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_version = $dados['eh_informar_version'];
        $eh_informar_status = $dados['eh_informar_status'];
        $eh_informar_contribute = $dados['eh_informar_contribute'];
      } else {
        $eh_informar_version = $conf->retornaInformarLyfeCycleVersion($tipo);
        $eh_informar_status = $conf->retornaInformarLyfeCycleStatus($tipo);
        $eh_informar_contribute = $conf->retornaInformarLyfeCycleContribute($tipo);
      }
      
      $ds_version = $dados['ds_version'];
      $cd_status = $dados['cd_status'];
      $ds_contribute = $dados['ds_contribute'];
      
      $this->imprimeFormularioCadastro($eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $cd_lyfe_cycle, $ds_version, $eh_informar_version, $cd_status, $eh_informar_status, $ds_contribute, $eh_informar_contribute);
    }
    
    public function imprimeFormularioCadastro($eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $cd_lyfe_cycle, $ds_version, $eh_informar_version, $cd_status, $eh_informar_status, $ds_contribute, $eh_informar_contribute) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_lyfe_cycle', $cd_lyfe_cycle);
      $util->campoHidden('eh_informar_lyfe_cycle_version', $eh_informar_version);
      $util->campoHidden('eh_informar_lyfe_cycle_status', $eh_informar_status);
      $util->campoHidden('eh_informar_lyfe_cycle_contribute', $eh_informar_contribute);  

      $eh_obrigatorio_version = $conf->retornaInformarLyfeCycleVersion('b');
      $eh_obrigatorio_status = $conf->retornaInformarLyfeCycleStatus('b');
      $eh_obrigatorio_contribute = $conf->retornaInformarLyfeCycleContribute('b');
      $util->campoHidden('eh_obrigatorio_lyfe_cycle_version', $eh_obrigatorio_version);
      $util->campoHidden('eh_obrigatorio_lyfe_cycle_status', $eh_obrigatorio_status);
      $util->campoHidden('eh_obrigatorio_lyfe_cycle_contribute', $eh_obrigatorio_contribute);  
            
      if ($eh_informar_lyfe_cycle == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações sobre Ciclo de Vida');
        if ($eh_informar_version == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_version, $conf->retornaDescricaoCampoLyfeCycleVersao(), $conf->retornaNomeCampoLyfeCycleVersao(), 'ds_lyfe_cycle_version', '250', '100', $ds_version, 1);
          $util->campoHidden('nm_lyfe_cycle_version', $conf->retornaNomeCampoLyfeCycleVersao());
        } else {
          $util->campoHidden('ds_lyfe_cycle_version', $ds_version);
        }
        if ($eh_informar_status == '1') {        
          require_once 'conteudos/status_ciclo_vida.php';                       $scv = new StatusCicloVida();
          $scv->retornaSeletorStatusCicloVida($cd_status, 'cd_lyfe_cycle_status', '100', 1, $conf->retornaDescricaoCampoLyfeCycleStatus(), $conf->retornaNomeCampoLyfeCycleStatus());
          $util->campoHidden('nm_lyfe_cycle_status', $conf->retornaNomeCampoLyfeCycleStatus());
        } else {
          $util->campoHidden('cd_lyfe_cycle_status', $cd_status);
        }
        if ($eh_informar_contribute == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_contribute, $conf->retornaDescricaoCampoLyfeCycleContribuir(), $conf->retornaNomeCampoLyfeCycleContribuir(), 'ds_lyfe_cycle_contribute', '250', '100', $ds_contribute, 1);
          $util->campoHidden('nm_lyfe_cycle_contribute', $conf->retornaNomeCampoLyfeCycleContribuir());
        } else {
          $util->campoHidden('ds_lyfe_cycle_contribute', $ds_contribute);
        }
      } else {
        $util->campoHidden('ds_lyfe_cycle_version', $ds_version);
        $util->campoHidden('cd_lyfe_cycle_status', $cd_status);
        $util->campoHidden('ds_lyfe_cycle_contribute', $ds_contribute);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_lyfe_cycle = addslashes($_POST['cd_lyfe_cycle']);
      $cd_status = addslashes($_POST['cd_lyfe_cycle_status']);
      $eh_informar_status = addslashes($_POST['eh_informar_lyfe_cycle_status']);
      $eh_informar_contribute = addslashes($_POST['eh_informar_lyfe_cycle_contribute']);
      $eh_informar_version = addslashes($_POST['eh_informar_lyfe_cycle_version']);
      $ds_version = $util->limparVariavel($_POST['ds_lyfe_cycle_version']);
      $ds_contribute = $util->limparVariavel($_POST['ds_lyfe_cycle_contribute']);

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$ds_version." | ".$ds_contribute;

      if ($cd_lyfe_cycle > 0) {
        return $this->alteraLyfeCycle($cd_lyfe_cycle, $ds_version, $eh_informar_version, $cd_status, $eh_informar_status, $ds_contribute, $eh_informar_contribute);
      } else {
        return $this->insereLyfeCycle($ds_version, $eh_informar_version, $cd_status, $eh_informar_status, $ds_contribute, $eh_informar_contribute);
      }
    } 

    public function imprimeDados($cd_lyfe_cycle) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemLyfeCycle($cd_lyfe_cycle);

      $eh_informar_version = $dados['eh_informar_version'];
      $eh_informar_status = $dados['eh_informar_status'];
      $eh_informar_contribute = $dados['eh_informar_contribute'];

      $ds_version = $dados['ds_version'];
      $cd_status = $dados['cd_status'];
      $ds_contribute = $dados['ds_contribute'];
            
      $retorno = '';
      $retorno.= "<div class=\"divConteudoUnicoObjetoAprendizagem\">\n";
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= "<b>Informações sobre Ciclo de Vida</b>";
      $retorno.= "</p>\n";

      if ($eh_informar_version == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoLyfeCycleVersao()."</b> ".$ds_version;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_status == '1') {
        require_once 'conteudos/status_ciclo_vida.php';                         $scv = new StatusCicloVida();
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= $scv->retornaDados($cd_status, $conf->retornaInformacaoCampoLyfeCycleStatus());
        $retorno.= "</p>\n";
      }
      if ($eh_informar_contribute == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoLyfeCycleContribuir()."</b> ".$ds_contribute;
        $retorno.= "</p>\n";
      }
      $retorno.= "<div class=\"clear\"></div>\n";
      $retorno.= "</div>\n";
      return $retorno;
    }

    public function imprimeDadosRetornoPesquisa($cd_lyfe_cycle, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemLyfeCycle($cd_lyfe_cycle);

      $eh_informar_version = $conf->retornaInformarLyfeCycleVersion($tipo);
      $eh_informar_status = $conf->retornaInformarLyfeCycleStatus($tipo);
      $eh_informar_contribute = $conf->retornaInformarLyfeCycleContribute($tipo);

      $ds_version = $dados['ds_version'];
      $cd_status = $dados['cd_status'];
      $ds_contribute = $dados['ds_contribute'];

      $retorno = '';

      if ($eh_informar_version == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoLyfeCycleVersao()."</b> ".$ds_version."<br />\n";
      }
      if ($eh_informar_status == '1') {
        require_once 'conteudos/status_ciclo_vida.php';                         $scv = new StatusCicloVida();
        $retorno.= $scv->retornaDados($cd_status, $conf->retornaInformacaoCampoLyfeCycleStatus())."<br />\n";
      }
      if ($eh_informar_contribute == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoLyfeCycleContribuir()."</b> ".$ds_contribute."<br />\n";
      }
      return $retorno;
    }

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemLyfeCycle($cd_lyfe_cycle) {
      $sql  = "SELECT * ".
              "FROM life_lyfe_cycle ".
              "WHERE cd_lyfe_cycle = '$cd_lyfe_cycle' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA LYFE CYCLE!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereLyfeCycle($ds_version, $eh_informar_version, $cd_status, $eh_informar_status, $ds_contribute, $eh_informar_contribute) {
      $sql = "INSERT INTO life_lyfe_cycle ".
             "(ds_version, eh_informar_version, cd_status, eh_informar_status, ds_contribute, eh_informar_contribute) ".
             "VALUES ".
             "(\"$ds_version\", \"$eh_informar_version\", \"$cd_status\", \"$eh_informar_status\", \"$ds_contribute\", \"$eh_informar_contribute\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'lyfe_cycle');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA LYFE CYCLE!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_lyfe_cycle) codigo ".
                "FROM life_lyfe_cycle ".
                "WHERE ds_version = '$ds_version' ".
                "AND cd_status = '$cd_status' ".
                "AND ds_contribute = '$ds_contribute' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA LYFE CYCLE!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraLyfeCycle($cd_lyfe_cycle, $ds_version, $eh_informar_version, $cd_status, $eh_informar_status, $ds_contribute, $eh_informar_contribute) {
      $sql = "UPDATE life_lyfe_cycle SET ".
             "ds_version = \"$ds_version\", ".
             "eh_informar_version = \"$eh_informar_version\", ".
             "cd_status = \"$cd_status\", ".
             "eh_informar_status = \"$eh_informar_status\", ".
             "ds_contribute = \"$ds_contribute\", ".
             "eh_informar_contribute = \"$eh_informar_contribute\" ".
             "WHERE cd_lyfe_cycle = '$cd_lyfe_cycle' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'lyfe_cycle');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA LYFE CYCLE!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>