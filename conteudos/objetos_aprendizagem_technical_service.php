<?php
  class ObjetoAprendizagemTechnicalService {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_service, $eh_informar_service, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_service > 0) {
        $this->montarFormularioEdicao($cd_service, $eh_informar_service, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_service, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_service, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_name = $conf->retornaInformarTechnicalServiceName($tipo);
      $eh_informar_type = $conf->retornaInformarTechnicalServiceType($tipo);
      $eh_informar_provides = $conf->retornaInformarTechnicalServiceProvides($tipo);
      $eh_informar_essential = $conf->retornaInformarTechnicalServiceEssential($tipo);
      $eh_informar_protocol = $conf->retornaInformarTechnicalServiceProtocol($tipo);
      $eh_informar_ontology = $conf->retornaInformarTechnicalServiceOntology($tipo);
      $eh_informar_language = $conf->retornaInformarTechnicalServiceLanguage($tipo);
      $eh_informar_details = $conf->retornaInformarTechnicalServiceDetails($tipo);

      $cd_service = "";
      $ds_name = "";
      $cd_type = "";
      $ds_provides = "";
      $ds_essential = "";
      $ds_protocol = "";
      $ds_ontology = "";
      $cd_language = "";
      $ds_details = "";

      $this->imprimeFormularioCadastro($eh_informar_service, $eh_manter_configuracoes_originais, $cd_service, $ds_name, $eh_informar_name, $cd_type, $eh_informar_type, $ds_provides, $eh_informar_provides, $ds_essential, $eh_informar_essential, $ds_protocol, $eh_informar_protocol, $ds_ontology, $eh_informar_ontology, $cd_language, $eh_informar_language, $ds_details, $eh_informar_details);
    }
    
    private function montarFormularioEdicao($cd_service, $eh_informar_service, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnicalService($cd_service);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_name = $dados['eh_informar_name'];
        $eh_informar_type = $dados['eh_informar_type'];
        $eh_informar_provides = $dados['eh_informar_provides'];
        $eh_informar_essential = $dados['eh_informar_essential'];
        $eh_informar_protocol = $dados['eh_informar_protocol'];
        $eh_informar_ontology = $dados['eh_informar_ontology'];
        $eh_informar_language = $dados['eh_informar_language'];
        $eh_informar_details = $dados['eh_informar_details'];
      } else {
        $eh_informar_name = $conf->retornaInformarTechnicalServiceName($tipo);
        $eh_informar_type = $conf->retornaInformarTechnicalServiceType($tipo);
        $eh_informar_provides = $conf->retornaInformarTechnicalServiceProvides($tipo);
        $eh_informar_essential = $conf->retornaInformarTechnicalServiceEssential($tipo);
        $eh_informar_protocol = $conf->retornaInformarTechnicalServiceProtocol($tipo);
        $eh_informar_ontology = $conf->retornaInformarTechnicalServiceOntology($tipo);
        $eh_informar_language = $conf->retornaInformarTechnicalServiceLanguage($tipo);
        $eh_informar_details = $conf->retornaInformarTechnicalServiceDetails($tipo);
      }
      
      $ds_name = $dados['ds_name'];
      $cd_type = $dados['cd_type'];
      $ds_provides = $dados['ds_provides'];
      $ds_essential = $dados['ds_essential'];
      $ds_protocol = $dados['ds_protocol'];
      $ds_ontology = $dados['ds_ontology'];
      $cd_language = $dados['cd_language'];
      $ds_details = $dados['ds_details'];

      $this->imprimeFormularioCadastro($eh_informar_service, $eh_manter_configuracoes_originais, $cd_service, $ds_name, $eh_informar_name, $cd_type, $eh_informar_type, $ds_provides, $eh_informar_provides, $ds_essential, $eh_informar_essential, $ds_protocol, $eh_informar_protocol, $ds_ontology, $eh_informar_ontology, $cd_language, $eh_informar_language, $ds_details, $eh_informar_details);
    }
    
    public function imprimeFormularioCadastro($eh_informar_service, $eh_manter_configuracoes_originais, $cd_service, $ds_name, $eh_informar_name, $cd_type, $eh_informar_type, $ds_provides, $eh_informar_provides, $ds_essential, $eh_informar_essential, $ds_protocol, $eh_informar_protocol, $ds_ontology, $eh_informar_ontology, $cd_language, $eh_informar_language, $ds_details, $eh_informar_details) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_service', $cd_service);
      $util->campoHidden('eh_informar_service_name', $eh_informar_name);
      $util->campoHidden('eh_informar_service_type', $eh_informar_type);
      $util->campoHidden('eh_informar_service_provides', $eh_informar_provides);
      $util->campoHidden('eh_informar_service_essential', $eh_informar_essential);
      $util->campoHidden('eh_informar_service_protocol', $eh_informar_protocol);
      $util->campoHidden('eh_informar_service_ontology', $eh_informar_ontology);
      $util->campoHidden('eh_informar_service_language', $eh_informar_language);
      $util->campoHidden('eh_informar_service_details', $eh_informar_details);
      
      $eh_obrigatorio_name = $conf->retornaInformarTechnicalServiceName('b');
      $eh_obrigatorio_type = $conf->retornaInformarTechnicalServiceType('b');
      $eh_obrigatorio_provides = $conf->retornaInformarTechnicalServiceProvides('b');
      $eh_obrigatorio_essential = $conf->retornaInformarTechnicalServiceEssential('b');
      $eh_obrigatorio_protocol = $conf->retornaInformarTechnicalServiceProtocol('b');
      $eh_obrigatorio_ontology = $conf->retornaInformarTechnicalServiceOntology('b');
      $eh_obrigatorio_language = $conf->retornaInformarTechnicalServiceLanguage('b');
      $eh_obrigatorio_details = $conf->retornaInformarTechnicalServiceDetails('b');
      $util->campoHidden('eh_obrigatorio_service_name', $eh_obrigatorio_name);
      $util->campoHidden('eh_obrigatorio_service_type', $eh_obrigatorio_type);
      $util->campoHidden('eh_obrigatorio_service_provides', $eh_obrigatorio_provides);
      $util->campoHidden('eh_obrigatorio_service_essential', $eh_obrigatorio_essential);
      $util->campoHidden('eh_obrigatorio_service_protocol', $eh_obrigatorio_protocol);
      $util->campoHidden('eh_obrigatorio_service_ontology', $eh_obrigatorio_ontology);
      $util->campoHidden('eh_obrigatorio_service_language', $eh_obrigatorio_language);
      $util->campoHidden('eh_obrigatorio_service_details', $eh_obrigatorio_details);

      if ($eh_obrigatorio_name || $eh_obrigatorio_type || $eh_obrigatorio_provides || $eh_obrigatorio_essential || $eh_obrigatorio_protocol || $eh_obrigatorio_ontology || $eh_obrigatorio_language || $eh_obrigatorio_details) {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações técnicas - serviços');
      
        if ($eh_informar_name == '1') {
          $util->linhaUmCampoTextHint($eh_informar_name, $conf->retornaDescricaoCampoTechnicalServiceNome(), $conf->retornaNomeCampoTechnicalServiceNome(), 'ds_service_name', '250', '100', $ds_name, 1);
          $util->campoHidden('nm_service_name', $conf->retornaNomeCampoTechnicalServiceNome());
        } else {
          $util->campoHidden('ds_service_name', $ds_name);
        }
        if ($eh_informar_type == '1') {        
          require_once 'conteudos/tipos.php';                                   $tip = new Tipo();
          $tip->retornaSeletorTipo($cd_type, 'cd_service_type', '100', 1, $conf->retornaDescricaoCampoTechnicalServiceTipo(), $conf->retornaNomeCampoTechnicalServiceTipo());
          $util->campoHidden('nm_service_type', $conf->retornaNomeCampoTechnicalServiceTipo());
        } else {
          $util->campoHidden('cd_service_type', $cd_type);
        }
        if ($eh_informar_provides == '1') {
          $util->linhaUmCampoTextHint($eh_informar_provides, $conf->retornaDescricaoCampoTechnicalServiceFornece(), $conf->retornaNomeCampoTechnicalServiceFornece(), 'ds_service_provides', '250', '100', $ds_provides, 1);
          $util->campoHidden('nm_service_provides', $conf->retornaNomeCampoTechnicalServiceFornece());
        } else {
          $util->campoHidden('ds_service_provides', $ds_provides);
        }
        if ($eh_informar_essential == '1') {
          $util->linhaUmCampoTextHint($eh_informar_essential, $conf->retornaDescricaoCampoTechnicalServiceEssencial(), $conf->retornaNomeCampoTechnicalServiceEssencial(), 'ds_service_essential', '250', '100', $ds_essential, 1);
          $util->campoHidden('nm_service_essential', $conf->retornaNomeCampoTechnicalServiceEssencial());
        } else {
          $util->campoHidden('ds_service_essential', $ds_essential);
        }
        if ($eh_informar_protocol == '1') {
          $util->linhaUmCampoTextHint($eh_informar_protocol, $conf->retornaDescricaoCampoTechnicalServiceProtocolo(), $conf->retornaNomeCampoTechnicalServiceProtocolo(), 'ds_service_protocol', '250', '100', $ds_protocol, 1);
          $util->campoHidden('nm_service_protocol', $conf->retornaNomeCampoTechnicalServiceProtocolo());
        } else {
          $util->campoHidden('ds_service_protocol', $ds_protocol);
        }
        if ($eh_informar_ontology == '1') {
          $util->linhaUmCampoTextHint($eh_informar_ontology, $conf->retornaDescricaoCampoTechnicalServiceOntologia(), $conf->retornaNomeCampoTechnicalServiceOntologia(), 'ds_service_ontology', '250', '100', $ds_ontology, 1);
          $util->campoHidden('nm_service_ontology', $conf->retornaNomeCampoTechnicalServiceOntologia());
        } else {
          $util->campoHidden('ds_service_ontology', $ds_ontology);
        }
        if ($eh_informar_language == '1') {        
          require_once 'conteudos/linguagens.php';                              $lin = new Linguagem();
          $lin->retornaSeletorLinguagem($cd_language, 'cd_service_language', '100', 1, $conf->retornaDescricaoCampoTechnicalServiceIdioma(), $conf->retornaNomeCampoTechnicalServiceIdioma());
          $util->campoHidden('nm_service_language', $conf->retornaNomeCampoTechnicalServiceIdioma());
        } else {
          $util->campoHidden('cd_service_language', $cd_language);
        }
        if ($eh_informar_details == '1') {
          $util->linhaUmCampoTextHint($eh_informar_details, $conf->retornaDescricaoCampoTechnicalServiceDetalhes(), $conf->retornaNomeCampoTechnicalServiceDetalhes(), 'ds_service_details', '250', '100', $ds_details, 1);
          $util->campoHidden('nm_service_details', $conf->retornaNomeCampoTechnicalServiceDetalhes());
        } else {
          $util->campoHidden('ds_service_details', $ds_details);
        }    
      } else {
        $util->campoHidden('ds_service_name', $ds_name);
        $util->campoHidden('cd_service_type', $cd_type);
        $util->campoHidden('ds_service_provides', $ds_provides);
        $util->campoHidden('ds_service_essential', $ds_essential);
        $util->campoHidden('ds_service_protocol', $ds_protocol);
        $util->campoHidden('ds_service_ontology', $ds_ontology);
        $util->campoHidden('cd_service_language', $cd_language);
        $util->campoHidden('ds_service_details', $ds_details);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/tipos.php';                                       $tip = new Tipo();
      require_once 'conteudos/linguagens.php';                                  $lin = new Linguagem();

      $cd_service = addslashes($_POST['cd_service']);
      $eh_informar_name = addslashes($_POST['eh_informar_service_name']);
      $cd_type = addslashes($_POST['cd_service_type']);
      $eh_informar_type = addslashes($_POST['eh_informar_service_type']);
      $eh_informar_provides = addslashes($_POST['eh_informar_service_provides']);
      $eh_informar_essential = addslashes($_POST['eh_informar_service_essential']);
      $eh_informar_protocol = addslashes($_POST['eh_informar_service_protocol']);
      $eh_informar_ontology = addslashes($_POST['eh_informar_service_ontology']);
      $cd_language = addslashes($_POST['cd_service_language']);
      $eh_informar_language = addslashes($_POST['eh_informar_service_language']);
      $eh_informar_details = addslashes($_POST['eh_informar_service_details']);
      $ds_name = $util->limparVariavel($_POST['ds_service_name']);
      $ds_provides = $util->limparVariavel($_POST['ds_service_provides']);
      $ds_essential = $util->limparVariavel($_POST['ds_service_essential']);
      $ds_protocol = $util->limparVariavel($_POST['ds_service_protocol']);
      $ds_ontology = $util->limparVariavel($_POST['ds_service_ontology']);
      $ds_details = $util->limparVariavel($_POST['ds_service_details']);

      $dados = $tip->selectDadosTipo($cd_type);
      $tipo = $dados['nm_tipo'];

      $dados = $lin->selectDadosLinguagem($cd_language);
      $linguagem = $dados['nm_linguagem'];

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$tipo." | ".$linguagem." | ".$ds_name." | ".$ds_provides." | ".$ds_essential." | ".$ds_protocol." | ".$ds_ontology." | ".$ds_details;

      if ($cd_service > 0) {
        return $this->alteraTechnicalService($cd_service, $ds_name, $eh_informar_name, $cd_type, $eh_informar_type, $ds_provides, $eh_informar_provides, $ds_essential, $eh_informar_essential, $ds_protocol, $eh_informar_protocol, $ds_ontology, $eh_informar_ontology, $cd_language, $eh_informar_language, $ds_details, $eh_informar_details);
      } else {
        return $this->insereTechnicalService($ds_name, $eh_informar_name, $cd_type, $eh_informar_type, $ds_provides, $eh_informar_provides, $ds_essential, $eh_informar_essential, $ds_protocol, $eh_informar_protocol, $ds_ontology, $eh_informar_ontology, $cd_language, $eh_informar_language, $ds_details, $eh_informar_details);
      }
    } 

    public function imprimeDados($cd_service) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnicalService($cd_service);

      $eh_informar_name = $dados['eh_informar_name'];
      $eh_informar_type = $dados['eh_informar_type'];
      $eh_informar_provides = $dados['eh_informar_provides'];
      $eh_informar_essential = $dados['eh_informar_essential'];
      $eh_informar_protocol = $dados['eh_informar_protocol'];
      $eh_informar_ontology = $dados['eh_informar_ontology'];
      $eh_informar_language = $dados['eh_informar_language'];
      $eh_informar_details = $dados['eh_informar_details'];

      $ds_name = $dados['ds_name'];
      $cd_type = $dados['cd_type'];
      $ds_provides = $dados['ds_provides'];
      $ds_essential = $dados['ds_essential'];
      $ds_protocol = $dados['ds_protocol'];
      $ds_ontology = $dados['ds_ontology'];
      $cd_language = $dados['cd_language'];
      $ds_details = $dados['ds_details'];

      $retorno ="";
      if ($eh_informar_name || $eh_informar_type || $eh_informar_provides || $eh_informar_essential || $eh_informar_protocol || $eh_informar_ontology || $eh_informar_language || $eh_informar_details) {
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= '<b><i>Informações técnicas - serviços</i></b>';
      $retorno.= "</p>\n";

      if ($eh_informar_name == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceNome()."</b> ".$ds_name;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_type == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        require_once 'conteudos/tipos.php';                                     $tip = new Tipo();
        $retorno.= $tip->retornaDados($cd_type, $conf->retornaInformacaoCampoTechnicalServiceTipo());
        $retorno.= "</p>\n";
      }
      if ($eh_informar_provides == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceFornece()."</b> ".$ds_provides;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_essential == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceEssencial()."</b> ".$ds_essential;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_protocol == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceProtocolo()."</b> ".$ds_protocol;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_ontology == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceOntologia()."</b> ".$ds_ontology;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_language == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $retorno.= $lin->retornaDados($cd_language, $conf->retornaInformacaoCampoTechnicalServiceIdioma());
        $retorno.= "</p>\n";
      }
      if ($eh_informar_details == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceDetalhes()."</b> ".$ds_details;
        $retorno.= "</p>\n";
      }
      }
      return $retorno;
    }

    public function imprimeDadosRetornoPesquisa($cd_service, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemTechnicalService($cd_service);

      $eh_informar_name = $conf->retornaInformarTechnicalServiceName($tipo);
      $eh_informar_type = $conf->retornaInformarTechnicalServiceType($tipo);
      $eh_informar_provides = $conf->retornaInformarTechnicalServiceProvides($tipo);
      $eh_informar_essential = $conf->retornaInformarTechnicalServiceEssential($tipo);
      $eh_informar_protocol = $conf->retornaInformarTechnicalServiceProtocol($tipo);
      $eh_informar_ontology = $conf->retornaInformarTechnicalServiceOntology($tipo);
      $eh_informar_language = $conf->retornaInformarTechnicalServiceLanguage($tipo);
      $eh_informar_details = $conf->retornaInformarTechnicalServiceDetails($tipo);

      $ds_name = $dados['ds_name'];
      $cd_type = $dados['cd_type'];
      $ds_provides = $dados['ds_provides'];
      $ds_essential = $dados['ds_essential'];
      $ds_protocol = $dados['ds_protocol'];
      $ds_ontology = $dados['ds_ontology'];
      $cd_language = $dados['cd_language'];
      $ds_details = $dados['ds_details'];

      $retorno = "";

      if ($eh_informar_name == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceNome()."</b> ".$ds_name."<br />\n";
      }
      if ($eh_informar_type == '1') {
        require_once 'conteudos/tipos.php';                                     $tip = new Tipo();
        $retorno.= $tip->retornaDados($cd_type, $conf->retornaInformacaoCampoTechnicalServiceTipo())."<br />\n";
      }
      if ($eh_informar_provides == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceFornece()."</b> ".$ds_provides."<br />\n";
      }
      if ($eh_informar_essential == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceEssencial()."</b> ".$ds_essential."<br />\n";
      }
      if ($eh_informar_protocol == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceProtocolo()."</b> ".$ds_protocol."<br />\n";
      }
      if ($eh_informar_ontology == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceOntologia()."</b> ".$ds_ontology."<br />\n";
      }
      if ($eh_informar_language == '1') {
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $retorno.= $lin->retornaDados($cd_language, $conf->retornaInformacaoCampoTechnicalServiceIdioma())."<br />\n";
      }
      if ($eh_informar_details == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoTechnicalServiceDetalhes()."</b> ".$ds_details."<br />\n";
      }
      return $retorno;
    }
//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemTechnicalService($cd_service) {
      $sql  = "SELECT * ".
              "FROM life_service ".
              "WHERE cd_service = '$cd_service' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICE!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereTechnicalService($ds_name, $eh_informar_name, $cd_type, $eh_informar_type, $ds_provides, $eh_informar_provides, $ds_essential, $eh_informar_essential, $ds_protocol, $eh_informar_protocol, $ds_ontology, $eh_informar_ontology, $cd_language, $eh_informar_language, $ds_details, $eh_informar_details) {
      $sql = "INSERT INTO life_service ".
             "(ds_name, eh_informar_name, cd_type, eh_informar_type, ds_provides, eh_informar_provides, ds_essential, eh_informar_essential, ds_protocol, eh_informar_protocol, ds_ontology, eh_informar_ontology, cd_language, eh_informar_language, ds_details, eh_informar_details) ".
             "VALUES ".
             "(\"$ds_name\", \"$eh_informar_name\", \"$cd_type\", \"$eh_informar_type\", \"$ds_provides\", \"$eh_informar_provides\", \"$ds_essential\", \"$eh_informar_essential\", \"$ds_protocol\", \"$eh_informar_protocol\", \"$ds_ontology\", \"$eh_informar_ontology\", \"$cd_language\", \"$eh_informar_language\", \"$ds_details\", \"$eh_informar_details\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'service');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICE!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_service) codigo ".
                "FROM life_service ".
                "WHERE ds_name = '$ds_name' ".
                "AND cd_type = '$cd_type' ".
                "AND ds_provides = '$ds_provides' ".
                "AND ds_essential = '$ds_essential' ".
                "AND ds_protocol = '$ds_protocol' ".
                "AND ds_ontology = '$ds_ontology' ".
                "AND cd_language = '$cd_language' ".
                "AND ds_details = '$ds_details' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICE!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraTechnicalService($cd_service, $ds_name, $eh_informar_name, $cd_type, $eh_informar_type, $ds_provides, $eh_informar_provides, $ds_essential, $eh_informar_essential, $ds_protocol, $eh_informar_protocol, $ds_ontology, $eh_informar_ontology, $cd_language, $eh_informar_language, $ds_details, $eh_informar_details) {
      $sql = "UPDATE life_service SET ".
             "ds_name = \"$ds_name\", ".
             "eh_informar_name = \"$eh_informar_name\", ".
             "cd_type = \"$cd_type\", ".
             "eh_informar_type = \"$eh_informar_type\", ".
             "ds_provides = \"$ds_provides\", ".
             "eh_informar_provides = \"$eh_informar_provides\", ".
             "ds_essential = \"$ds_essential\", ".
             "eh_informar_essential = \"$eh_informar_essential\", ".
             "ds_protocol = \"$ds_protocol\", ".
             "eh_informar_protocol = \"$eh_informar_protocol\", ".
             "ds_ontology = \"$ds_ontology\", ".
             "eh_informar_ontology = \"$eh_informar_ontology\", ".
             "cd_language = \"$cd_language\", ".
             "eh_informar_language = \"$eh_informar_language\", ".
             "ds_details = \"$ds_details\", ".
             "eh_informar_details = \"$eh_informar_details\" ".
             "WHERE cd_service = '$cd_service' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'service');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICE!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>