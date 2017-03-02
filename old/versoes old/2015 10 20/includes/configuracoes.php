<?php
  class Configuracao {

    //construtor
    public function __construct() {
    }

    public function controleExibicao($secao, $subsecao, $item, $tipo) {
      if (isset($_GET['acao'])) {        $acao= addslashes($_GET['acao']);      } else {        $acao= '';      }
      
      if ($acao == 'editar') {
        $this->salvarEdicaoConfiguracoes($secao, $subsecao, $item, $tipo);
        $this->montarFormularioEdicaoConfiguracoes($secao, $subsecao, $item, $tipo);
      } else {
        $this->montarFormularioEdicaoConfiguracoes($secao, $subsecao, $item, $tipo);
      }
    }

    public function montarFormularioEdicaoConfiguracoes($secao, $subsecao, $item, $tipo) {
      if ($tipo != '') {
        require_once 'includes/utilitarios.php';                                $util = new Utilitario();

        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "</p>\n";

        echo "  <form method=\"POST\" action=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&acao=editar\">\n";
        echo "    <table class=\"tabConteudo\">\n";

        $quantidade = '0';

        switch ($tipo) {
          case "obba":                                                          //campos padrao obba - basico                                                    
            $quantidade = $this->montarFormularioConfiguracaoCamposPadraoObba();
          break;

          case "obba_basico":                                                   //campos padrao obba - basico                                                    
            $quantidade = $this->montarFormularioConfiguracaoCamposPadraoObbaBasico();
          break;

          case "obba_requisitar":
            $quantidade = $this->montarFormularioConfiguracaoCamposPadraoObbaRequisitar();
          break;

          case "pesq_campos":
            $quantidade = $this->montarFormularioConfiguracaoCamposPesquisa();
          break;

          case "campos_obba":
            $quantidade = $this->montarFormularioConfiguracaoDescricaoCamposObba();
          break;

        }

        $util->linhaComentario('<hr>');
        $util->campoHidden('quantidade', $quantidade);
        $util->linhaBotao('Salvar');
        echo "    </table>\n";
        echo "  </form>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Nenhuma �rea de configura��o definida!</p>\n";
        echo "<br /><br /><br /><br /><br /><br />\n";
      }            
    } 

    private function montarFormularioConfiguracaoCamposPesquisa() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';          $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'N�o';          $opcoes[]= $opcao;

      $plataforma = 'todas';

      $this->tiluloSecao('Configura��es de Filtros para os Formul�rios de Pesquisa');

      $util->linhaComentario('<hr>');

      $indice = $this->configuradorCampoBoolean('eh_filtrar_language', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_filtrar_coverage', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_filtrar_status', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_filtrar_type', $opcoes, $indice, '0', $plataforma);

      return $indice;
    }
    
    private function montarFormularioConfiguracaoDescricaoCamposObba() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $plataforma = 'todas';

      $this->tiluloSecao('Configura��es de Descri��o dos Campos do Padr�o OBBA');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es Gerais do Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_general_identifier_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_general_title_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_general_language_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_general_description_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_general_keyword_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_general_coverage_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_general_structure_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_general_agregation_level_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Ciclo de Vida do Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_lyfe_cycle_version_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_lyfe_cycle_status_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_lyfe_cycle_contribute_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Meta-Metadata sobre o Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_meta_metadata_identifier_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_meta_metadata_contribute_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_meta_metadata_metadata_schema_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_meta_metadata_language_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_technical_format_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_size_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_location_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_requirement_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_composite_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_installation_remarks_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_other_plataforms_requirements_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_duration_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_supported_plataform_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<br />');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional referentes � Plataformas e Caracter�sticas Espec�ficas');
      $indice = $this->configuradorCampoString('cp_technical_plataform_specific_features_plataform_type_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_plataform_specific_features_specific_format_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_plataform_specific_features_specific_size_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_plataform_specific_features_specific_location_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_plataform_specific_features_specific_requeriments_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_plataform_specific_features_specific_instalation_remarks_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_plataform_specific_features_specific_other_plataform_requeriments_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<br />');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional referentes � Servi�os');
      $indice = $this->configuradorCampoString('cp_technical_service_name_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_service_type_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_service_provides_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_service_essential_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_service_protocol_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_service_ontology_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_service_language_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_technical_service_details_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es Educacionais sobre o Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_educational_interactivity_type_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_learning_resource_type_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_interactivity_level_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_sem_antic_density_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_intended_end_user_role_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_context_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_typical_age_range_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_difficulty_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_typical_learning_time_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_description_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_language_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_learning_content_type_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_interaction_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_educational_didatic_strategy_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Direitos  sobre o Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_rights_cost_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_rights_copyright_and_other_restrictions_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_rights_description_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Rela��es do Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_relation_kind_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_relation_resource_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Anota��es sobre o Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_annotation_entity_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_annotation_date_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_annotation_description_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Classifica��es do Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_classification_purpose_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_classification_taxon_path_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_classification_description_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_classification_keyword_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Acessibilidade para o Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_acessibility_has_visual_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_acessibility_has_audititory_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_acessibility_has_text_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_acessibility_has_tactible_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_acessibility_earl_statment_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_acessibility_equivalent_resource_descricao', '80', '250', $indice, '0', 'todas');

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es por Segmento sobre o Objeto Educacional');
      $indice = $this->configuradorCampoString('cp_segment_information_table_segment_list_descricao', '80', '250', $indice, '0', 'todas');
      $indice = $this->configuradorCampoString('cp_segment_information_table_segmente_group_list_descricao', '80', '250', $indice, '0', 'todas');

      return $indice;
    }


    private function montarFormularioConfiguracaoCamposPadraoObba() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';          $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'N�o';          $opcoes[]= $opcao;

      $plataforma = 'todas';
      
      $this->tiluloSecao('Configura��es de Campos de Cadastro do Padr�o OBBA');

      $this->tiluloSecao('Configura��es para Edi��o');
      
      $util->linhaComentario('<hr>');
      $indice = $this->configuradorCampoBoolean('eh_manter_configuracoes_originais', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_liberacao_automatica', $opcoes, $indice, '0', $plataforma);

      return $indice;
    }

    private function montarFormularioConfiguracaoCamposPadraoObbaBasico() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';          $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'N�o';          $opcoes[]= $opcao;
      
      $plataforma = 'todas';
      
      $this->tiluloSecao('Configura��es de Campos de Exibi��o do Padr�o OBBA - Cadastro B�sico');
      $util->linhaComentario("Todos os campos selecionados para o cadastro b�sico ser�o considerados de preenchimento obrigat�rio'.");

      $this->tiluloSecao('Configura��es para Edi��o');
      
      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es Gerais do Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_general_title_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_general_language_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_general_description_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_general_keyword_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_general_structure_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_general_agregation_level_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Ciclo de Vida do Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_version_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_status_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_contribute_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Meta-Metadata sobre o Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_identifier_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_contribute_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_metadata_schema_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_language_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_technical_size_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_requirement_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_composite_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_installation_remarks_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_other_plataforms_requirements_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_duration_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_supported_plataform_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<br />');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional referentes � Plataformas e Caracter�sticas Espec�ficas');

      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_format_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_size_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_location_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<br />');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional referentes � Servi�os');

      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_name_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_type_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_provides_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_essential_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_protocol_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_ontology_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_language_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_details_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es Educacionais sobre o Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_educational_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_interactivity_type_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_learning_resource_type_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_interactivity_level_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_sem_antic_density_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_intended_end_user_role_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_context_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_typical_age_range_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_difficulty_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_typical_learning_time_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_description_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_language_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_learning_content_type_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_interaction_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_educational_didatic_strategy_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Direitos  sobre o Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_rights_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_rights_cost_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_rights_copyright_and_other_restrictions_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_rights_description_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Rela��es do Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_relation_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_relation_kind_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_relation_resource_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Anota��es sobre o Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_annotation_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_annotation_entity_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_annotation_date_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_annotation_description_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Classifica��es do Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_classification_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_classification_purpose_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_classification_taxon_path_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_classification_description_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_classification_keyword_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Acessibilidade para o Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_visual_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_audititory_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_text_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_tactible_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_earl_statment_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_equivalent_resource_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es por Segmento sobre o Objeto Educacional');

      $indice = $this->configuradorCampoBoolean('eh_informar_segment_information_table_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      $indice = $this->configuradorCampoBoolean('eh_informar_segment_information_table_segment_list_cadastro_basico', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_informar_segment_information_table_segmente_group_list_cadastro_basico', $opcoes, $indice, '0', $plataforma);

      return $indice;    
    }
        
    private function montarFormularioConfiguracaoCamposPadraoObbaRequisitar() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';          $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'N�o';          $opcoes[]= $opcao;
      
      $plataforma = 'todas';
      
      $this->tiluloSecao('Configura��es de Campos de Exibi��o do Padr�o OBBA - Cadastro � Requisitar');
      $util->linhaComentario("Os campos do Cadastro B�sico n�o s�o configur�veis para o Cadastro � Requisitar.");
      
      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es Gerais do Objeto Educacional');

/*      $dados = $this->selectDadosConfiguracao('eh_informar_general_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_general_identifier_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_identifier_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_identifier_cadastro_requisitar', '1', $indice, $plataforma);
      }*/
      $dados = $this->selectDadosConfiguracao('eh_informar_general_title_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_title_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_title_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_general_language_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_language_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_language_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_general_description_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_description_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_description_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_general_keyword_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_keyword_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_keyword_cadastro_requisitar', '1', $indice, $plataforma);
      }
/*      $dados = $this->selectDadosConfiguracao('eh_informar_general_coverage_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_coverage_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_coverage_cadastro_requisitar', '1', $indice, $plataforma);
      }*/
      $dados = $this->selectDadosConfiguracao('eh_informar_general_structure_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_structure_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_structure_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_general_agregation_level_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_general_agregation_level_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_general_agregation_level_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Ciclo de Vida do Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_lyfe_cycle_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_version_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_version_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_lyfe_cycle_version_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_status_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_status_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_lyfe_cycle_status_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_contribute_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_lyfe_cycle_contribute_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_lyfe_cycle_contribute_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Meta-Metadata sobre o Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_meta_metadata_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_identifier_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_identifier_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_meta_metadata_identifier_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_contribute_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_contribute_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_meta_metadata_contribute_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_metadata_schema_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_metadata_schema_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_meta_metadata_metadata_schema_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_language_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_meta_metadata_language_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_meta_metadata_language_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional');

//      $dados = $this->selectDadosConfiguracao('eh_informar_technical_cadastro_basico');
//      if ($dados['vl_boolean'] == '0' ) {
//        $indice = $this->configuradorCampoBoolean('eh_informar_technical_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
//        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
//      } else {
//        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_cadastro_requisitar', '1', $indice, $plataforma);
//      }
//      $dados = $this->selectDadosConfiguracao('eh_informar_technical_format_cadastro_basico');
//      if ($dados['vl_boolean'] == '0' ) {
//        $indice = $this->configuradorCampoBoolean('eh_informar_technical_format_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
//      } else {
//        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_format_cadastro_requisitar', '1', $indice, $plataforma);
//      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_size_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_size_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_size_cadastro_requisitar', '1', $indice, $plataforma);
      }
//      $dados = $this->selectDadosConfiguracao('eh_informar_technical_location_cadastro_basico');
//      if ($dados['vl_boolean'] == '0' ) {
//        $indice = $this->configuradorCampoBoolean('eh_informar_technical_location_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
//      } else {
//        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_location_cadastro_requisitar', '1', $indice, $plataforma);
//      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_requirement_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_requirement_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_requirement_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_composite_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_composite_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_composite_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_installation_remarks_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_installation_remarks_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_installation_remarks_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_other_plataforms_requirements_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_other_plataforms_requirements_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_other_plataforms_requirements_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_duration_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_duration_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_duration_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_supported_plataform_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_supported_plataform_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_supported_plataform_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<br />');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional referentes � Plataformas e Caracter�sticas Espec�ficas');

      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_format_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_format_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_specific_format_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_size_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_size_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_specific_size_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_location_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_location_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_specific_location_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<br />');
      $this->tiluloSecao('Configura��es de Informa��es T�cnicas sobre o Objeto Educacional referentes � Servi�os');

      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_name_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_name_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_name_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_type_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_type_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_type_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_provides_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_provides_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_provides_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_essential_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_essential_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_essential_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_protocol_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_protocol_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_protocol_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_ontology_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_ontology_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_ontology_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_language_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_language_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_language_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_details_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_technical_service_details_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_technical_service_details_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es Educacionais sobre o Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_educational_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_type_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_interactivity_type_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_interactivity_type_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_learning_resource_type_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_learning_resource_type_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_learning_resource_type_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_level_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_interactivity_level_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_interactivity_level_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_sem_antic_density_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_sem_antic_density_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_sem_antic_density_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_intended_end_user_role_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_intended_end_user_role_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_intended_end_user_role_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_context_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_context_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_context_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_typical_age_range_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_typical_age_range_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_typical_age_range_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_difficulty_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_difficulty_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_difficulty_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_typical_learning_time_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_typical_learning_time_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_typical_learning_time_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_description_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_description_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_description_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_language_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_language_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_language_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_learning_content_type_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_learning_content_type_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_learning_content_type_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_interaction_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_interaction_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_interaction_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_educational_didatic_strategy_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_educational_didatic_strategy_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_educational_didatic_strategy_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Direitos  sobre o Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_rights_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_rights_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_rights_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_rights_cost_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_rights_cost_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_rights_cost_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_rights_copyright_and_other_restrictions_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_rights_copyright_and_other_restrictions_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_rights_copyright_and_other_restrictions_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_rights_description_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_rights_description_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_rights_description_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Rela��es do Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_relation_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_relation_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_relation_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_relation_kind_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_relation_kind_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_relation_kind_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_relation_resource_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_relation_resource_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_relation_resource_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es de Anota��es sobre o Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_annotation_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_annotation_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_annotation_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_annotation_entity_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_annotation_entity_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_annotation_entity_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_annotation_date_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_annotation_date_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_annotation_date_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_annotation_description_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_annotation_description_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_annotation_description_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Classifica��es do Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_classification_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_classification_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_classification_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_classification_purpose_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_classification_purpose_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_classification_purpose_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_classification_taxon_path_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_classification_taxon_path_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_classification_taxon_path_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_classification_description_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_classification_description_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_classification_description_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_classification_keyword_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_classification_keyword_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_classification_keyword_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es sobre Acessibilidade para o Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_acessibility_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_visual_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_visual_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_acessibility_has_visual_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_audititory_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_audititory_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_acessibility_has_audititory_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_text_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_text_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_acessibility_has_text_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_tactible_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_has_tactible_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_acessibility_has_tactible_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_earl_statment_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_earl_statment_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_acessibility_earl_statment_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_equivalent_resource_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_acessibility_equivalent_resource_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_acessibility_equivalent_resource_cadastro_requisitar', '1', $indice, $plataforma);
      }

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Configura��es de Informa��es por Segmento sobre o Objeto Educacional');

      $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_segment_information_table_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
        $util->linhaComentarioDestaque("Os itens abaixo somente ser�o considerados no momento do cadastros, se o item acima estiver marcado com 'SIM'.");
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_segment_information_table_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segment_list_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_segment_information_table_segment_list_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_segment_information_table_segment_list_cadastro_requisitar', '1', $indice, $plataforma);
      }
      $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segmente_group_list_cadastro_basico');
      if ($dados['vl_boolean'] == '0' ) {
        $indice = $this->configuradorCampoBoolean('eh_informar_segment_information_table_segmente_group_list_cadastro_requisitar', $opcoes, $indice, '0', $plataforma);
      } else {
        $indice = $this->configuradorCampoBooleanHidden('eh_informar_segment_information_table_segmente_group_list_cadastro_requisitar', '1', $indice, $plataforma);
      }

      return $indice;    
    }

    public function salvarEdicaoConfiguracoes($secao, $subsecao) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $quantidade = addslashes($_POST['quantidade']);
      
      for ($i=1; $i<$quantidade; $i++) {
        $nome_variavel = 'variavel_'.$i;                                        $variavel = addslashes($_POST[$nome_variavel]);
        $tipo_variavel = 'tipo_'.$i;                                            $tipo = addslashes($_POST[$tipo_variavel]);    
        $campo_plataforma = 'plataforma_'.$i;                                   $plataforma = addslashes($_POST[$campo_plataforma]);
        $tipo_especial = 'especial_'.$i;
        if (isset($_POST[$tipo_especial])) {
          $especial = addslashes($_POST[$tipo_especial]);
          $valor = addslashes($_POST['hora_'.$especial]).":".addslashes($_POST['minuto_'.$especial]).":00";
        } else {
          if ($tipo == 'vl_decimal') {
            $valor = $util->limparVariavel(str_replace(',','.',$_POST[$variavel])); 
          } else {
            $valor = $util->limparVariavel($_POST[$variavel]);
          }
        }
        $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
      }
      echo "<p class=\"fontConteudoSucesso\">Configura��es atualizadas!</p>\n";
    }
                        /*
    

    //redes sociais
    public function ehExibirRedeSocial($rede) {
      $rede = 'eh_exibir_rede_social_'.$rede;
      $dados = $this->selectDadosConfiguracao($rede);
      return $dados['vl_boolean'];     
    }
    
    public function retornaIconeRedeSocial($rede) {
      $rede = 'ds_imagem_rede_social_'.$rede;
      $dados = $this->selectDadosConfiguracao($rede);
      return $dados['vl_string'];     
    }
    
    public function retornaNomeRedeSocial($rede) {
      $rede = 'nm_rede_social_'.$rede;
      $dados = $this->selectDadosConfiguracao($rede);
      return $dados['vl_string'];     
    }


    public function retornaLinkRedeSocial($rede) {
      $rede = 'ds_link_rede_social_'.$rede;
      $dados = $this->selectDadosConfiguracao($rede);
      return $dados['vl_string'];     
    }
        
                                                       */
    public function exibirMensagemRedesSociaisCadastroUsuario()  {
      $dados = $this->selectDadosConfiguracao('eh_mostrar_mensagem_cadastro_login_redes');
      return $dados['vl_boolean']; 
    }
    
    public function retornaMensagemRedesSociaisCadastroUsuario()  {
      $dados = $this->selectDadosConfiguracao('ds_mensagem_cadastro_login_redes');
      return $dados['vl_texto']; 
    }
    
    public function exibirMensagemConviteCadastroUsuario()  {
      $dados = $this->selectDadosConfiguracao('eh_mostrar_mensagem_convite_cadastro');
      return $dados['vl_boolean']; 
    }
    
    public function retornaMensagemConviteCadastroUsuario()  {
      $dados = $this->selectDadosConfiguracao('ds_mensagem_convite_cadastro');
      return $dados['vl_texto']; 
    }
                                                       
    public function retornaDescricaoTamanhoLogoCompartilhamentoRedesSociais() {
      $dados = $this->selectDadosConfiguracao('ds_tamanho_logo_compartilhamento_redes_sociais');
      return $dados['vl_string']; 
    }

    public function retornaDescricaoTamanhoLogoLoginRedesSociais() {
      $dados = $this->selectDadosConfiguracao('ds_tamanho_logo_login_redes_sociais');
      return $dados['vl_string']; 
    }
                                                           
    public function verificarNumeroMaximoTentativasLogin() {
      $dados = $this->selectDadosConfiguracao('nr_tentativas_login');
      return $dados['vl_inteiro']; 
    }    
                                                
    public function retornaLinkCompletoAplicacao() {
      $dados = $this->selectDadosConfiguracao('ds_link_completo');
      return $dados['vl_string']; 
    }

    public function retornaNomeSite()  {
      $dados = $this->selectDadosConfiguracao('nm_site');
      return $dados['vl_string']; 
    }
                                                  
    public function retornaDescricaoSite()  {
      $dados = $this->selectDadosConfiguracao('ds_descricao_site');
      return $dados['vl_string']; 
    }

    public function retornaPalavrasChave() {
      $dados = $this->selectDadosConfiguracao('ds_palavras_chave');
      return $dados['vl_string']; 
    }

    public function retornaLogo() {
      $dados = $this->selectDadosConfiguracao('ds_arquivo_logo');
      return $dados['vl_string']; 
    }
    
    public function retornaDescricaoLogo() {
      $dados = $this->selectDadosConfiguracao('ds_descricao_logo');
      return $dados['vl_string']; 
    }
    
    public function verificarNumeroLetrasMaiusculasSenha() {
      $dados = $this->selectDadosConfiguracao('nr_letras_maiusculas_senha');
      return $dados['vl_inteiro']; 
    }    

    public function verificarNumeroLetrasMinusculasSenha() {
      $dados = $this->selectDadosConfiguracao('nr_letras_minusculas_senha');
      return $dados['vl_inteiro']; 
    }    

    public function verificarNumeroNumerosSenha() {
      $dados = $this->selectDadosConfiguracao('nr_numeros_senha');
      return $dados['vl_inteiro']; 
    }    
                                               
    public function verificarNumeroCaracteresSenha() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_senha');
      return $dados['vl_inteiro']; 
    }    
    
    public function retornaTituloNotificacaoEmailEsqueceuSenha() {
      $dados = $this->selectDadosConfiguracao('ds_titulo_notificacao_esqueceu_senha');
      return $dados['vl_string']; 
    }
                                               
    public function retornaEmailOrigemNotificacaoEmailEsqueceuSenha() {
      $dados = $this->selectDadosConfiguracao('ds_email_origem_esqueceu_senha');
      return $dados['vl_string']; 
    }  
    
    public function retornaNumeroLimiteCaracteresRelacaoChamadaAreasConhecimentoCapaSite() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_chamada_areas_conhecimento_capa');
      return $dados['vl_inteiro']; 
    }                                             

    public function retornaSugestaoLocaisAudio() {
      $dados = $this->selectDadosConfiguracao('ds_sugestao_locais_audio');
      return $dados['vl_texto']; 
    }    
    
    public function retornaSugestaoLocaisVideo() {
      $dados = $this->selectDadosConfiguracao('ds_sugestao_locais_video');
      return $dados['vl_texto']; 
    }  
    
    public function retornaTamanhoLimiteUploadArquivos() {
      $dados = $this->selectDadosConfiguracao('ds_limite_tamanho_arquivos');
      return $dados['vl_decimal']; 
    }       


                                                  /*

    public function exibeIntroducaoCorpoClinico() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_introducao_corpo_clinico');
      return $dados['vl_boolean']; 
    }
    
    public function exibeIntroducaoExames() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_introducao_exames');
      return $dados['vl_boolean']; 
    }    
    
    public function exibeIntroducaoEspecialidades() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_introducao_especialidades');
      return $dados['vl_boolean']; 
    }
    
    public function exibeIntroducaoLinks() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_introducao_links');
      return $dados['vl_boolean']; 
    }
                                   
    public function exibeIntroducaoCategoriasLinks() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_categorias_links_capa');
      return $dados['vl_boolean']; 
    }       
    
    public function exibeIntroducaoLocalizacao() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_introducao_localizacao');
      return $dados['vl_boolean']; 
    }        
        
    public function retornaLimiteLinksCategoria() {
      $dados = $this->selectDadosConfiguracao('nr_limite_links_categoria');
      return $dados['vl_inteiro']; 
    }
     
    public function verificaOrdemMedicosCorpoClinico() {
      $dados = $this->selectDadosConfiguracao('tp_ordem_corpo_clinico');
      return $dados['vl_string'];               
    }
    
    public function verificarExibeFotoMedicos() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_foto_medico');
      return $dados['vl_boolean']; 
    } 
    
    public function verificarExibeContatosTelefoneMedicos() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_telefone_medico');
      return $dados['vl_boolean']; 
    } 
    
    public function verificarExibeContatosEmailMedicos() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_email_medico');
      return $dados['vl_boolean']; 
    }
    
    public function retornaNumeroChamadasNoticiaisCapa() {
      $dados = $this->selectDadosConfiguracao('nr_materias_capa');
      return $dados['vl_inteiro']; 
    }
    
    public function retornaNumeroItensPaginaExibicao() {
      $dados = $this->selectDadosConfiguracao('nr_itens_pagina');
      return $dados['vl_inteiro']; 
    }
    
    public function verificarExibirMateriaCompleta() {
      $dados = $this->selectDadosConfiguracao('ex_exibir_completa');
      return $dados['vl_boolean']; 
    }
    
    public function retornaNumeroCaracteresResumoMateria() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_resumo');
      return $dados['vl_inteiro']; 
    }

    public function exibeIntroducaoFaleConosco() {
      $dados = $this->selectDadosConfiguracao('eh_exibir_introducao_fale_conosco');
      return $dados['vl_boolean']; 
    }        

    public function retornaEmailOrigemRespostasMensagens() {
      $dados = $this->selectDadosConfiguracao('ds_email_origem_respostas_mensagens');
      return $dados['vl_string']; 
    } 


    public function retornaTituloPopUp() {
      $dados = $this->selectDadosConfiguracao('ds_titulo_pop_up');
      return $dados['vl_string']; 
    } 
    
    public function retornaTextoPopUp() {
      $dados = $this->selectDadosConfiguracao('ds_texto_pop_up');
      return $dados['vl_texto']; 
    } 
            
    public function retornaPopUp() {
      $titulo = $this->retornaTituloPopUp();
      $texto = $this->retornaTextoPopUp();
      
      if (($titulo != '') && ($texto != '')) { 
        echo "<div class=\"divPopUP\">\n";
        echo "  <h2>".$titulo."</h2>\n";
        echo "  <p class=\"fontConteudo\" style=\"text-align:center;\">".nl2br($texto)."</p>\n";
        echo "</div>\n";
      }
    }
    
    public function retornaAlturaMapa() {
      $dados = $this->selectDadosConfiguracao('nr_pixels_altura_mapa');
      return $dados['vl_inteiro'];
    }     
              */

             
    public function retornaLiberacaoAutomaticaObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('eh_liberacao_automatica');
      return $dados['vl_boolean']; 
    }  
              
//**********************PADR�O*OBBA*********************************************

    public function retornaManterConfiguracoesOriginais() {
      $dados = $this->selectDadosConfiguracao('eh_manter_configuracoes_originais');
      return $dados['vl_boolean']; 
    }  

    public function retornaInformarGeneral($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarGeneralTitle($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_title_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_title_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarGeneralLanguage($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_language_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_language_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarGeneralDescription($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_description_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_description_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarGeneralKeyword($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_keyword_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_keyword_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarGeneralStructure($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_structure_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_structure_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarGeneralAgregationLevel($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_agregation_level_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_agregation_level_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarLyfeCycle($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarLyfeCycleVersion($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_version_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_version_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarLyfeCycleStatus($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_status_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_status_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarLyfeCycleContribute($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_contribute_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_contribute_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarMetaMetadata($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarMetaMetadataIdentifier($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_identifier_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_identifier_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarMetaMetadataContribute($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_contribute_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_contribute_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarMetaMetadataMetadataSchema($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_metadata_schema_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_metadata_schema_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarMetaMetadataLanguage($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_language_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_language_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalSize($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_size_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_size_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalRequirement($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_requirement_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_requirement_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalComposite($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_composite_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_composite_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalInstallationRemarks($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_installation_remarks_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_installation_remarks_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalOtherPlataformsRequirements($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_other_plataforms_requirements_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_other_plataforms_requirements_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalDuration($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_duration_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_duration_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalSupportedPlataform($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_supported_plataform_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_supported_plataform_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeatures($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesPlataformType($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesSpecificFormat($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_format_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_format_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesSpecificSize($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_size_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_size_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesSpecificLocation($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_location_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_location_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesSpecificRequeriments($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesSpecificInstalationRemarks($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesSpecificOtherPlataformRequeriments($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalService($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceName($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_name_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_name_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceType($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_type_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_type_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceProvides($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_provides_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_provides_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceEssential($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_essential_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_essential_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceProtocol($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_protocol_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_protocol_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceOntology($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_ontology_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_ontology_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceLanguage($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_language_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_language_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalServiceDetails($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_details_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_details_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducational($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalInteractivityType($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_type_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_type_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalLearningResourceType($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_learning_resource_type_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_learning_resource_type_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalInteractivityLevel($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_level_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_level_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalSemAnticDensity($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_sem_antic_density_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_sem_antic_density_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalIntendedEndUserRole($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_intended_end_user_role_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_intended_end_user_role_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalContext($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_context_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_context_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalTypicalAgeRange($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_typical_age_range_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_typical_age_range_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalDifficulty($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_difficulty_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_difficulty_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalTypicalLearningTime($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_typical_learning_time_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_typical_learning_time_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalDescription($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_description_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_description_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalLanguage($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_language_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_language_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalLearningContentType($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_learning_content_type_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_learning_content_type_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalInteraction($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interaction_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interaction_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducationalDidaticStrategy($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_didatic_strategy_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_didatic_strategy_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRights($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRightsCost($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_cost_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_cost_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRightsCopyrightAndOtherRestrictions($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_copyright_and_other_restrictions_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_copyright_and_other_restrictions_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRightsDescription($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_description_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_description_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRelation($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRelationKind($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_kind_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_kind_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRelationResource($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_resource_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_resource_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAnnotation($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAnnotationEntity($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_entity_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_entity_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAnnotationDate($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_date_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_date_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAnnotationDescription($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_description_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_description_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarClassification($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarClassificationPurpose($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_purpose_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_purpose_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarClassificationTaxonPath($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_taxon_path_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_taxon_path_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarClassificationDescription($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_description_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_description_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarClassificationKeyword($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_keyword_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_keyword_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibility($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibilityHasVisual($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_visual_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_visual_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibilityHasAudititory($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_audititory_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_audititory_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibilityHasText($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_text_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_text_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibilityHasTactible($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_tactible_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_tactible_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibilityEarlStatment($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_earl_statment_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_earl_statment_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibilityEquivalentResource($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_equivalent_resource_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_equivalent_resource_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarSegmentInformationTable($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarSegmentInformationTableSegmentList($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segment_list_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segment_list_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarSegmentInformationTableSegmenteGroupList($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segmente_group_list_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segmente_group_list_cadastro_requisitar');
        }
        return $dados['vl_boolean'];
      }
    }


//************************DESCRICAO*CAMPOS*OBBA*********************************
    public function retornaDescricaoCampoGeneralIdentificador() {
      $dados = $this->selectDadosConfiguracao('cp_general_identifier_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoGeneralTitulo() {
      $dados = $this->selectDadosConfiguracao('cp_general_title_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoGeneralIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_general_language_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoGeneralDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_general_description_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoGeneralPalavraChave() {
      $dados = $this->selectDadosConfiguracao('cp_general_keyword_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoGeneralCobertura() {
      $dados = $this->selectDadosConfiguracao('cp_general_coverage_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoGeneralEstrutura() {
      $dados = $this->selectDadosConfiguracao('cp_general_structure_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoGeneralNivelAgregacao() {
      $dados = $this->selectDadosConfiguracao('cp_general_agregation_level_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoLyfeCycleVersao() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_version_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoLyfeCycleStatus() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_status_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoLyfeCycleContribuir() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_contribute_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoMetaMetadataIdentificador() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_identifier_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoMetaMetadataContribuicao() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_contribute_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoMetaMetadataEsquemaMetadados() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_metadata_schema_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoMetaMetadataIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_language_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalFormato() {
      $dados = $this->selectDadosConfiguracao('cp_technical_format_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalTamanho() {
      $dados = $this->selectDadosConfiguracao('cp_technical_size_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalLocalizacao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_location_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalRequerimentos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_requirement_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalComposicao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_composite_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalObservacoesInstalacao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_installation_remarks_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalOutrasPlataformasRequisitos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_other_plataforms_requirements_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalDuracao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_duration_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformaApoiado() {
      $dados = $this->selectDadosConfiguracao('cp_technical_supported_plataform_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformSpecificFeaturesPlataformaTipo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_plataform_type_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformSpecificFeaturesFormatoEspecifico() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_format_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformSpecificFeaturesEspecificaTamanho() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_size_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformSpecificFeaturesLocalizacaoEspecifica() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_location_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformSpecificFeaturesRequerimentosEspecificos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_requeriments_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformSpecificFeaturesObservacoesInstalacaoEspecificas() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_instalation_remarks_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalPlataformSpecificFeaturesOutrosRequisitosPlataformaEspecificos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_other_plataform_requeriments_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceNome() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_name_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceTipo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_type_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceFornece() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_provides_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceEssencial() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_essential_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceProtocolo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_protocol_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceOntologia() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_ontology_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_language_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoTechnicalServiceDetalhes() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_details_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalTipoInteratividade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interactivity_type_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalRecursosAprendizagemTipo() {
      $dados = $this->selectDadosConfiguracao('cp_educational_learning_resource_type_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalNivelInteratividade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interactivity_level_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalDensidade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_sem_antic_density_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalFuncaoDestinacaoUsuario() {
      $dados = $this->selectDadosConfiguracao('cp_educational_intended_end_user_role_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalContexto() {
      $dados = $this->selectDadosConfiguracao('cp_educational_context_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalFaixaTipicaIdade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_typical_age_range_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalDificuldade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_difficulty_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalTempoTipicoAprendizagem() {
      $dados = $this->selectDadosConfiguracao('cp_educational_typical_learning_time_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_educational_description_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_educational_language_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalAprenderTipoConteudo() {
      $dados = $this->selectDadosConfiguracao('cp_educational_learning_content_type_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalInteracao() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interaction_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoEducationalEstrategiaDidatica() {
      $dados = $this->selectDadosConfiguracao('cp_educational_didatic_strategy_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoRightsCusto() {
      $dados = $this->selectDadosConfiguracao('cp_rights_cost_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoRightsDireitosAutorOutrasRestricoes() {
      $dados = $this->selectDadosConfiguracao('cp_rights_copyright_and_other_restrictions_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoRightsDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_rights_description_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoRelationTipo() {
      $dados = $this->selectDadosConfiguracao('cp_relation_kind_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoRelationRecurso() {
      $dados = $this->selectDadosConfiguracao('cp_relation_resource_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAnnotationEntidade() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_entity_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAnnotationData() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_date_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAnnotationDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_description_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoClassificationProposito() {
      $dados = $this->selectDadosConfiguracao('cp_classification_purpose_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoClassificationTaxonPath() {
      $dados = $this->selectDadosConfiguracao('cp_classification_taxon_path_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoClassificationDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_classification_description_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoClassificationPalavraChave() {
      $dados = $this->selectDadosConfiguracao('cp_classification_keyword_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAcessibilityElementosVisuais() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_visual_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAcessibilityElementosSonoros() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_audititory_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAcessibilityElementosTexto() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_text_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAcessibilityElementosT�teis() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_tactible_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAcessibilityPadraoEARL() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_earl_statment_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoAcessibilityRecursosEquivalentes() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_equivalent_resource_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoSegmentInformation_tableListaSegmentos() {
      $dados = $this->selectDadosConfiguracao('cp_segment_information_table_segment_list_descricao');
      return $dados['vl_string'];
    }

    public function retornaDescricaoCampoSegmentInformation_tableListaSegmentoGrupo() {
      $dados = $this->selectDadosConfiguracao('cp_segment_information_table_segmente_group_list_descricao');
      return $dados['vl_string'];
    }

//************************FILTORS*PESQUISA*O*A**********************************
    public function retornaEhFiltrarLinguagem() {
      $dados = $this->selectDadosConfiguracao('eh_filtrar_language');
      return $dados['vl_boolean'];
    }

    public function retornaEhFiltrarAreasConhecimento() {
      $dados = $this->selectDadosConfiguracao('eh_filtrar_coverage');
      return $dados['vl_boolean'];
    }

    public function retornaEhFiltrarStatusCicloVida() {
      $dados = $this->selectDadosConfiguracao('eh_filtrar_status');
      return $dados['vl_boolean'];
    }

    public function retornaEhFiltrarTipo() {
      $dados = $this->selectDadosConfiguracao('eh_filtrar_type');
      return $dados['vl_boolean'];
    }



//******************************************************************************              
              
    public function verificarMensagemBloqueio() {
      $dados = $this->selectDadosConfiguracao('ds_mensagem_bloqueio');
      return $dados['vl_texto']; 
    }
    
    public function retornaLogoSite() {
      $lk_completo = $this->retornaLinkCompletoAplicacao();
      $logo = $this->retornaLogo();
      $descricao = $this->retornaDescricaoLogo();
      echo "<a href=\"".$lk_completo."\"><img src=\"".$lk_completo.$logo."\" alt=\"".$descricao."\" title=\"".$descricao."\" border=\"0\" height=\"55\"></a>\n";
    }
    
  
//***************************CONFIGURADORES*************************************    
    private function configuradorCampoInteiro($variavel, $tamanho, $limite, $indice, $obrigatorio, $plataforma) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_inteiro';
      $valor = $dados[$tipo]; 
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, $tipo);
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaUmCampoText($obrigatorio, $nome, $variavel, $limite, $tamanho, $valor);  
      return $indice + 1;
    }    

    private function configuradorCampoDecimal($variavel, $tamanho, $limite, $indice, $obrigatorio, $plataforma) {
/*    aindicea nao utilizado
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_decimal';
      $valor = $dados[$tipo]; 
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, $tipo);
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaUmCampoText($obrigatorio, $nome, $variavel, $limite, $tamanho, $valor);  
      return $indice + 1;*/
    }
    
    private function configuradorCampoString($variavel, $tamanho, $limite, $indice, $obrigatorio, $plataforma) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_string';
      $valor = $dados[$tipo]; 
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, $tipo);
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaUmCampoText($obrigatorio, $nome, $variavel, $limite, $tamanho, $valor);  
      return $indice + 1;
    }    
        
    private function configuradorCampoTexto($variavel, $altura, $largura, $indice, $obrigatorio, $plataforma) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_texto';
      $valor = $dados[$tipo]; 
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, 'vl_texto');
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaTexto($obrigatorio, $nome, $variavel, $valor, $altura, $largura);    
      return $indice + 1;
    } 

    private function configuradorCampoBoolean($variavel, $lista, $indice, $obrigatorio, $plataforma) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_boolean';
      $valor = $dados[$tipo]; 
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, $tipo);
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaSeletor($nome, $variavel, $valor, $lista);
      return $indice + 1;
    }

    private function configuradorCampoBooleanHidden($variavel, $valor, $indice, $plataforma) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_boolean';
      if ($valor == '') {    $valor = $dados[$tipo];      } 
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, $tipo);
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaDuasColunasComentario($nome, 'Default');
      $util->campoHidden($variavel, $valor);
      return $indice + 1;
    }
    
    private function tiluloSecao($texto) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $util->linhaComentario('<b>'.$texto.'</b>');
    } 
     
//***********************************BANCO DE DADOS*****************************
    private function selectDadosConfiguracao($delimitador) {
      $plataforma = $_SESSION['life_tipo_equipamento_acesso'];
      $sql  = "SELECT * ".
              "FROM life_configuracoes ".
              "WHERE ds_configuracao= '$delimitador' ".
              "AND (ds_plataforma = '$plataforma' OR ds_plataforma = 'TODAS') ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONFIGURA��ES");
      //associar saida dos dados
      $dados= @mysql_fetch_array($result_id);
      return $dados;  
    }

    public function editarConfiguracao($variavel, $tipo, $valor, $plataforma) {
      $sql = "UPDATE life_configuracoes SET ".
             $tipo ." = \"$valor\" ".
             "WHERE ds_configuracao = \"$variavel\" ".
             "AND ds_plataforma = '$plataforma'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'configuracoes');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONFIGURA��ES");
      $saida = mysql_affected_rows();
      return $saida;         
    }
/*                                          
    public function editarConfiguracaoSemLog($variavel, $tipo, $valor) {
      $sql = "UPDATE life_configuracoes SET ".
             $tipo ." = \"$valor\" ".
             "WHERE ds_configuracao = \"$variavel\" ";
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONFIGURA��ES");
      $saida = mysql_affected_rows();
      return $saida;         
    }
*/
  }    
?>