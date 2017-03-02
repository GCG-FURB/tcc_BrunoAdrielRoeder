<?php
  class ObjetoAprendizagemMetaMetadata {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_meta_metadata > 0) {
        $this->montarFormularioEdicao($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_identifier = $conf->retornaInformarMetaMetadataIdentifier($tipo);
      $eh_informar_contribute = $conf->retornaInformarMetaMetadataContribute($tipo);
      $eh_informar_metadata_schema = $conf->retornaInformarMetaMetadataMetadataSchema($tipo);
      $eh_informar_language = $conf->retornaInformarMetaMetadataLanguage($tipo);

      $cd_meta_metadata = "";
      $ds_identifier = "";
      $ds_contribute = "";
      $ds_metadata_schema = "";
      $cd_language = "";

      $this->imprimeFormularioCadastro($eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $cd_meta_metadata, $ds_identifier, $eh_informar_identifier, $ds_contribute, $eh_informar_contribute, $ds_metadata_schema, $eh_informar_metadata_schema, $cd_language, $eh_informar_language);
    }
    
    private function montarFormularioEdicao($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemMetaMetadata($cd_meta_metadata);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_identifier = $dados['eh_informar_identifier'];
        $eh_informar_contribute = $dados['eh_informar_contribute'];
        $eh_informar_metadata_schema = $dados['eh_informar_metadata_schema'];
        $eh_informar_language = $dados['eh_informar_language'];
      } else {
        $eh_informar_identifier = $conf->retornaInformarMetaMetadataIdentifier($tipo);
        $eh_informar_contribute = $conf->retornaInformarMetaMetadataContribute($tipo);
        $eh_informar_metadata_schema = $conf->retornaInformarMetaMetadataMetadataSchema($tipo);
        $eh_informar_language = $conf->retornaInformarMetaMetadataLanguage($tipo);
      }
      
      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $ds_identifier = $dados['ds_identifier'];
      $ds_contribute = $dados['ds_contribute'];
      $ds_metadata_schema = $dados['ds_metadata_schema'];
      $cd_language = $dados['cd_language'];
      
      $this->imprimeFormularioCadastro($eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $cd_meta_metadata, $ds_identifier, $eh_informar_identifier, $ds_contribute, $eh_informar_contribute, $ds_metadata_schema, $eh_informar_metadata_schema, $cd_language, $eh_informar_language);
    }
    
    public function imprimeFormularioCadastro($eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $cd_meta_metadata, $ds_identifier, $eh_informar_identifier, $ds_contribute, $eh_informar_contribute, $ds_metadata_schema, $eh_informar_metadata_schema, $cd_language, $eh_informar_language) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_meta_metadata', $cd_meta_metadata);
      $util->campoHidden('eh_informar_meta_metadata', $eh_informar_meta_metadata);
      $util->campoHidden('eh_informar_meta_metadata_identifier', $eh_informar_identifier);
      $util->campoHidden('eh_informar_meta_metadata_contribute', $eh_informar_contribute);
      $util->campoHidden('eh_informar_meta_metadata_metadata_schema', $eh_informar_metadata_schema);
      $util->campoHidden('eh_informar_meta_metadata_language', $eh_informar_language);

      $eh_obrigatorio_identifier = $conf->retornaInformarMetaMetadataIdentifier('b');
      $eh_obrigatorio_contribute = $conf->retornaInformarMetaMetadataContribute('b');
      $eh_obrigatorio_metadata_schema = $conf->retornaInformarMetaMetadataMetadataSchema('b');
      $eh_obrigatorio_language = $conf->retornaInformarMetaMetadataLanguage('b');
      $util->campoHidden('eh_obrigatorio_meta_metadata_identifier', $eh_obrigatorio_identifier);
      $util->campoHidden('eh_obrigatorio_meta_metadata_contribute', $eh_obrigatorio_contribute);
      $util->campoHidden('eh_obrigatorio_meta_metadata_metadata_schema', $eh_obrigatorio_metadata_schema);
      $util->campoHidden('eh_obrigatorio_meta_metadata_language', $eh_obrigatorio_language);


      if ($eh_informar_meta_metadata == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações de Metadados');
      
        if ($eh_informar_identifier == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_identifier, $conf->retornaDescricaoCampoMetaMetadataIdentificador(), $conf->retornaNomeCampoMetaMetadataIdentificador(), 'ds_meta_metadata_identifier', '250', '100', $ds_identifier, 1);
          $util->campoHidden('nm_meta_metadata_identifier', $conf->retornaNomeCampoMetaMetadataIdentificador());
        } else {
          $util->campoHidden('ds_meta_metadata_identifier', $ds_identifier);
        }                                                                                                                 

        if ($eh_informar_contribute == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_contribute, $conf->retornaDescricaoCampoMetaMetadataContribuicao(), $conf->retornaNomeCampoMetaMetadataContribuicao(), 'ds_meta_metadata_contribute', '250', '100', $ds_contribute, 1);
          $util->campoHidden('nm_meta_metadata_contribute', $conf->retornaNomeCampoMetaMetadataContribuicao());
        } else {
          $util->campoHidden('ds_meta_metadata_contribute', $ds_contribute);
        }                                                                                                                 

        if ($eh_informar_metadata_schema == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_metadata_schema, $conf->retornaDescricaoCampoMetaMetadataEsquemaMetadados(), $conf->retornaNomeCampoMetaMetadataEsquemaMetadados(), 'ds_meta_metadata_metadata_schema', '250', '100', $ds_metadata_schema, 1);
          $util->campoHidden('nm_meta_metadata_metadata_schema', $conf->retornaNomeCampoMetaMetadataEsquemaMetadados());
        } else {
          $util->campoHidden('ds_meta_metadata_metadata_schema', $ds_metadata_schema);
        }                                                                                                                 

        if ($eh_informar_language == '1') {        
          require_once 'conteudos/linguagens.php';                              $lin = new Linguagem();
          $lin->retornaSeletorLinguagem($cd_language, 'cd_meta_metadata_language', '100', 1, $conf->retornaDescricaoCampoMetaMetadataIdioma(), $conf->retornaNomeCampoMetaMetadataIdioma());
          $util->campoHidden('nm_meta_metadata_language', $conf->retornaNomeCampoMetaMetadataIdioma());
        } else {
          $util->campoHidden('cd_meta_metadata_language', $cd_language);
        }

      } else {
        $util->campoHidden('ds_meta_metadata_identifier', $ds_identifier);
        $util->campoHidden('ds_meta_metadata_contribute', $ds_contribute);
        $util->campoHidden('ds_meta_metadata_metadata_schema', $ds_metadata_schema);
        $util->campoHidden('cd_meta_metadata_language', $cd_language);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/linguagens.php';                                  $lin = new Linguagem();

      $cd_meta_metadata = addslashes($_POST['cd_meta_metadata']);
      $eh_informar_identifier = addslashes($_POST['eh_informar_meta_metadata_identifier']);
      $eh_informar_contribute = addslashes($_POST['eh_informar_meta_metadata_contribute']);
      $eh_informar_metadata_schema = addslashes($_POST['eh_informar_meta_metadata_metadata_schema']);
      $cd_language = addslashes($_POST['cd_meta_metadata_language']);
      $eh_informar_language = addslashes($_POST['eh_informar_meta_metadata_language']);
      $ds_identifier = $util->limparVariavel($_POST['ds_meta_metadata_identifier']);
      $ds_contribute = $util->limparVariavel($_POST['ds_meta_metadata_contribute']);
      $ds_metadata_schema = $util->limparVariavel($_POST['ds_meta_metadata_metadata_schema']);


      $dados = $lin->selectDadosLinguagem($cd_language);
      $linguagem = $dados['nm_linguagem'];

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$linguagem." | ".$ds_identifier." | ".$ds_contribute." | ".$ds_metadata_schema;

      if ($cd_meta_metadata > 0) {
        return $this->alteraMetaMetadata($cd_meta_metadata, $ds_identifier, $eh_informar_identifier, $ds_contribute, $eh_informar_contribute, $ds_metadata_schema, $eh_informar_metadata_schema, $cd_language, $eh_informar_language);
      } else {
        return $this->insereMetaMetadata($ds_identifier, $eh_informar_identifier, $ds_contribute, $eh_informar_contribute, $ds_metadata_schema, $eh_informar_metadata_schema, $cd_language, $eh_informar_language);
      }
    } 

    public function imprimeDados($cd_meta_metadata) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemMetaMetadata($cd_meta_metadata);

      $eh_informar_identifier = $dados['eh_informar_identifier'];
      $eh_informar_contribute = $dados['eh_informar_contribute'];
      $eh_informar_metadata_schema = $dados['eh_informar_metadata_schema'];
      $eh_informar_language = $dados['eh_informar_language'];

      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $ds_identifier = $dados['ds_identifier'];
      $ds_contribute = $dados['ds_contribute'];
      $ds_metadata_schema = $dados['ds_metadata_schema'];
      $cd_language = $dados['cd_language'];

      $retorno = '';
      $retorno.= "<div class=\"divConteudoUnicoObjetoAprendizagem\">\n";
      $retorno.= "<p class=\"fontConteudoObjetosAprendizagem\">";
      $retorno.= "<b>Informações de Metadados</b>";
      $retorno.= "</p>\n";
      if ($eh_informar_identifier == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoMetaMetadataIdentificador()."</b> ".$ds_identifier;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_contribute == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoMetaMetadataContribuicao()."</b> ".$ds_contribute;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_metadata_schema == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        $retorno.= "<b>".$conf->retornaInformacaoCampoMetaMetadataEsquemaMetadados()."</b> ".$ds_metadata_schema;
        $retorno.= "</p>\n";
      }
      if ($eh_informar_language == '1') {
        $retorno.= "<p class=\"fontConteudoDuploObjetosAprendizagem\">";
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $retorno.= $lin->retornaDados($cd_language, $conf->retornaInformacaoCampoMetaMetadataIdioma());
        $retorno.= "</p>\n";
      }
      $retorno.= "<div class=\"clear\"></div>\n";
      $retorno.= "</div>\n";
      return $retorno;
    }

    public function imprimeDadosRetornoPesquisa($cd_meta_metadata, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemMetaMetadata($cd_meta_metadata);

      $eh_informar_identifier = $conf->retornaInformarMetaMetadataIdentifier($tipo);
      $eh_informar_contribute = $conf->retornaInformarMetaMetadataContribute($tipo);
      $eh_informar_metadata_schema = $conf->retornaInformarMetaMetadataMetadataSchema($tipo);
      $eh_informar_language = $conf->retornaInformarMetaMetadataLanguage($tipo);

      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $ds_identifier = $dados['ds_identifier'];
      $ds_contribute = $dados['ds_contribute'];
      $ds_metadata_schema = $dados['ds_metadata_schema'];
      $cd_language = $dados['cd_language'];

      $retorno = '';
      if ($eh_informar_identifier == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoMetaMetadataIdentificador()."</b> ".$ds_identifier."<br />\n";
      }
      if ($eh_informar_contribute == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoMetaMetadataContribuicao()."</b> ".$ds_contribute."<br />\n";
      }
      if ($eh_informar_metadata_schema == '1') {
        $retorno.= "<b>".$conf->retornaInformacaoCampoMetaMetadataEsquemaMetadados()."</b> ".$ds_metadata_schema."<br />\n";
      }
      if ($eh_informar_language == '1') {
        require_once 'conteudos/linguagens.php';                                $lin = new Linguagem();
        $retorno.= $lin->retornaDados($cd_language, $conf->retornaInformacaoCampoMetaMetadataIdioma())."<br />\n";
      }
      return $retorno;
    }

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemMetaMetadata($cd_meta_metadata) {
      $sql  = "SELECT * ".
              "FROM life_meta_metadata ".
              "WHERE cd_meta_metadata = '$cd_meta_metadata' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA META METADATA!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereMetaMetadata($ds_identifier, $eh_informar_identifier, $ds_contribute, $eh_informar_contribute, $ds_metadata_schema, $eh_informar_metadata_schema, $cd_language, $eh_informar_language) {
      $sql = "INSERT INTO life_meta_metadata ".
             "(ds_identifier, eh_informar_identifier, ds_contribute, eh_informar_contribute, ds_metadata_schema, eh_informar_metadata_schema, cd_language, eh_informar_language) ".
             "VALUES ".
             "(\"$ds_identifier\", \"$eh_informar_identifier\", \"$ds_contribute\", \"$eh_informar_contribute\", \"$ds_metadata_schema\", \"$eh_informar_metadata_schema\", \"$cd_language\", \"$eh_informar_language\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'meta_metadata');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA META METADATA!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_meta_metadata) codigo ".
                "FROM life_meta_metadata ".
                "WHERE ds_identifier = '$ds_identifier' ".
                "AND ds_contribute = '$ds_contribute' ".
                "AND ds_metadata_schema = '$ds_metadata_schema' ".
                "AND cd_language = '$cd_language' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA META METADATA!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraMetaMetadata($cd_meta_metadata, $ds_identifier, $eh_informar_identifier, $ds_contribute, $eh_informar_contribute, $ds_metadata_schema, $eh_informar_metadata_schema, $cd_language, $eh_informar_language) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_meta_metadata SET ".
             "ds_identifier = \"$ds_identifier\", ".
             "eh_informar_identifier = \"$eh_informar_identifier\", ".
             "ds_contribute = \"$ds_contribute\", ".
             "eh_informar_contribute = \"$eh_informar_contribute\", ".
             "ds_metadata_schema = \"$ds_metadata_schema\", ".
             "eh_informar_metadata_schema = \"$eh_informar_metadata_schema\", ".
             "cd_language = \"$cd_language\", ".
             "eh_informar_language = \"$eh_informar_language\" ".
             "WHERE cd_meta_metadata = '$cd_meta_metadata' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'meta_metadata');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA META METADATA!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>