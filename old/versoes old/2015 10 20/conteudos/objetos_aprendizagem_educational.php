<?php
  class ObjetoAprendizagemEducational {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo) {
      if ($cd_educational > 0) {
        $this->montarFormularioEdicao($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);
      } else {
        $this->montarFormularioCadastro($eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_educational, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_interactivity_type = $conf->retornaInformarEducationalInteractivityType($tipo);
      $eh_informar_learning_resource_type = $conf->retornaInformarEducationalLearningResourceType($tipo);
      $eh_informar_interactivity_level = $conf->retornaInformarEducationalInteractivityLevel($tipo);
      $eh_informar_sem_antic_density = $conf->retornaInformarEducationalSemAnticDensity($tipo);
      $eh_informar_intended_end_user_role = $conf->retornaInformarEducationalIntendedEndUserRole($tipo);
      $eh_informar_context = $conf->retornaInformarEducationalContext($tipo);
      $eh_informar_typical_age_range = $conf->retornaInformarEducationalTypicalAgeRange($tipo);
      $eh_informar_difficulty = $conf->retornaInformarEducationalDifficulty($tipo);
      $eh_informar_typical_learning_time = $conf->retornaInformarEducationalTypicalLearningTime($tipo);
      $eh_informar_description = $conf->retornaInformarEducationalDescription($tipo);
      $eh_informar_language = $conf->retornaInformarEducationalLanguage($tipo);
      $eh_informar_learning_content_type = $conf->retornaInformarEducationalLearningContentType($tipo);
      $eh_informar_interaction = $conf->retornaInformarEducationalInteraction($tipo);
      $eh_informar_didatic_strategy = $conf->retornaInformarEducationalDidaticStrategy($tipo);

      $cd_educational = "";
      $ds_interactivity_type = "";
      $ds_learning_resource_type = "";
      $ds_interactivity_level = "";
      $ds_sem_antic_density = "";
      $ds_intended_end_user_role = "";
      $ds_context = "";
      $ds_typical_age_range = "";
      $ds_difficulty = "";
      $ds_typical_learning_time = "";
      $ds_description = "";
      $cd_language = "";
      $ds_learning_content_type = "";
      $ds_interaction = "";
      $ds_didatic_strategy = "";

      $this->imprimeFormularioCadastro($eh_informar_educational, $eh_manter_configuracoes_originais, $cd_educational, $ds_interactivity_type, $eh_informar_interactivity_type, $ds_learning_resource_type, $eh_informar_learning_resource_type, $ds_interactivity_level, $eh_informar_interactivity_level, $ds_sem_antic_density, $eh_informar_sem_antic_density, $ds_intended_end_user_role, $eh_informar_intended_end_user_role, $ds_context, $eh_informar_context, $ds_typical_age_range, $eh_informar_typical_age_range, $ds_difficulty, $eh_informar_difficulty, $ds_typical_learning_time, $eh_informar_typical_learning_time, $ds_description, $eh_informar_description, $cd_language, $eh_informar_language, $ds_learning_content_type, $eh_informar_learning_content_type, $ds_interaction, $eh_informar_interaction, $ds_didatic_strategy, $eh_informar_didatic_strategy);
    }
    
    private function montarFormularioEdicao($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemEducational($cd_educational);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_interactivity_type = $dados['eh_informar_interactivity_type'];
        $eh_informar_learning_resource_type = $dados['eh_informar_learning_resource_type'];
        $eh_informar_interactivity_level = $dados['eh_informar_interactivity_level'];
        $eh_informar_sem_antic_density = $dados['eh_informar_sem_antic_density'];
        $eh_informar_intended_end_user_role = $dados['eh_informar_intended_end_user_role'];
        $eh_informar_context = $dados['eh_informar_context'];
        $eh_informar_typical_age_range = $dados['eh_informar_typical_age_range'];
        $eh_informar_difficulty = $dados['eh_informar_difficulty'];
        $eh_informar_typical_learning_time = $dados['eh_informar_typical_learning_time'];
        $eh_informar_description = $dados['eh_informar_description'];
        $eh_informar_language = $dados['eh_informar_language'];
        $eh_informar_learning_content_type = $dados['eh_informar_learning_content_type'];
        $eh_informar_interaction = $dados['eh_informar_interaction'];
        $eh_informar_didatic_strategy = $dados['eh_informar_didatic_strategy'];
      } else {
        $eh_informar_interactivity_type = $conf->retornaInformarEducationalInteractivityType($tipo);
        $eh_informar_learning_resource_type = $conf->retornaInformarEducationalLearningResourceType($tipo);
        $eh_informar_interactivity_level = $conf->retornaInformarEducationalInteractivityLevel($tipo);
        $eh_informar_sem_antic_density = $conf->retornaInformarEducationalSemAnticDensity($tipo);
        $eh_informar_intended_end_user_role = $conf->retornaInformarEducationalIntendedEndUserRole($tipo);
        $eh_informar_context = $conf->retornaInformarEducationalContext($tipo);
        $eh_informar_typical_age_range = $conf->retornaInformarEducationalTypicalAgeRange($tipo);
        $eh_informar_difficulty = $conf->retornaInformarEducationalDifficulty($tipo);
        $eh_informar_typical_learning_time = $conf->retornaInformarEducationalTypicalLearningTime($tipo);
        $eh_informar_description = $conf->retornaInformarEducationalDescription($tipo);
        $eh_informar_language = $conf->retornaInformarEducationalLanguage($tipo);
        $eh_informar_learning_content_type = $conf->retornaInformarEducationalLearningContentType($tipo);
        $eh_informar_interaction = $conf->retornaInformarEducationalInteraction($tipo);
        $eh_informar_didatic_strategy = $conf->retornaInformarEducationalDidaticStrategy($tipo);
      }
      
      $ds_interactivity_type = $dados['ds_interactivity_type'];
      $ds_learning_resource_type = $dados['ds_learning_resource_type'];
      $ds_interactivity_level = $dados['ds_interactivity_level'];
      $ds_sem_antic_density = $dados['ds_sem_antic_density'];
      $ds_intended_end_user_role = $dados['ds_intended_end_user_role'];
      $ds_context = $dados['ds_context'];
      $ds_typical_age_range = $dados['ds_typical_age_range'];
      $ds_difficulty = $dados['ds_difficulty'];
      $ds_typical_learning_time = $dados['ds_typical_learning_time'];
      $ds_description = $dados['ds_description'];
      $cd_language = $dados['cd_language'];
      $ds_learning_content_type = $dados['ds_learning_content_type'];
      $ds_interaction = $dados['ds_interaction'];
      $ds_didatic_strategy = $dados['ds_didatic_strategy'];
      
      $this->imprimeFormularioCadastro($eh_informar_educational, $eh_manter_configuracoes_originais, $cd_educational, $ds_interactivity_type, $eh_informar_interactivity_type, $ds_learning_resource_type, $eh_informar_learning_resource_type, $ds_interactivity_level, $eh_informar_interactivity_level, $ds_sem_antic_density, $eh_informar_sem_antic_density, $ds_intended_end_user_role, $eh_informar_intended_end_user_role, $ds_context, $eh_informar_context, $ds_typical_age_range, $eh_informar_typical_age_range, $ds_difficulty, $eh_informar_difficulty, $ds_typical_learning_time, $eh_informar_typical_learning_time, $ds_description, $eh_informar_description, $cd_language, $eh_informar_language, $ds_learning_content_type, $eh_informar_learning_content_type, $ds_interaction, $eh_informar_interaction, $ds_didatic_strategy, $eh_informar_didatic_strategy);
    }
    
    public function imprimeFormularioCadastro($eh_informar_educational, $eh_manter_configuracoes_originais, $cd_educational, $ds_interactivity_type, $eh_informar_interactivity_type, $ds_learning_resource_type, $eh_informar_learning_resource_type, $ds_interactivity_level, $eh_informar_interactivity_level, $ds_sem_antic_density, $eh_informar_sem_antic_density, $ds_intended_end_user_role, $eh_informar_intended_end_user_role, $ds_context, $eh_informar_context, $ds_typical_age_range, $eh_informar_typical_age_range, $ds_difficulty, $eh_informar_difficulty, $ds_typical_learning_time, $eh_informar_typical_learning_time, $ds_description, $eh_informar_description, $cd_language, $eh_informar_language, $ds_learning_content_type, $eh_informar_learning_content_type, $ds_interaction, $eh_informar_interaction, $ds_didatic_strategy, $eh_informar_didatic_strategy) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $util->campoHidden('cd_educational', $cd_educational);
      $util->campoHidden('eh_informar_educational_interactivity_type', $eh_informar_interactivity_type);
      $util->campoHidden('eh_informar_educational_learning_resource_type', $eh_informar_learning_resource_type);
      $util->campoHidden('eh_informar_educational_interactivity_level', $eh_informar_interactivity_level);
      $util->campoHidden('eh_informar_educational_sem_antic_density', $eh_informar_sem_antic_density);
      $util->campoHidden('eh_informar_educational_intended_end_user_role', $eh_informar_intended_end_user_role);
      $util->campoHidden('eh_informar_educational_context', $eh_informar_context);
      $util->campoHidden('eh_informar_educational_typical_age_range', $eh_informar_typical_age_range);
      $util->campoHidden('eh_informar_educational_difficulty', $eh_informar_difficulty);
      $util->campoHidden('eh_informar_educational_typical_learning_time', $eh_informar_typical_learning_time);
      $util->campoHidden('eh_informar_educational_description', $eh_informar_description);
      $util->campoHidden('eh_informar_educational_language', $eh_informar_language);
      $util->campoHidden('eh_informar_educational_learning_content_type', $eh_informar_learning_content_type);
      $util->campoHidden('eh_informar_educational_interaction', $eh_informar_interaction);
      $util->campoHidden('eh_informar_educational_didatic_strategy', $eh_informar_didatic_strategy);
      
      $eh_obrigatorio_interactivity_type = $conf->retornaInformarEducationalInteractivityType('b');
      $eh_obrigatorio_learning_resource_type = $conf->retornaInformarEducationalLearningResourceType('b');
      $eh_obrigatorio_interactivity_level = $conf->retornaInformarEducationalInteractivityLevel('b');
      $eh_obrigatorio_sem_antic_density = $conf->retornaInformarEducationalSemAnticDensity('b');
      $eh_obrigatorio_intended_end_user_role = $conf->retornaInformarEducationalIntendedEndUserRole('b');
      $eh_obrigatorio_context = $conf->retornaInformarEducationalContext('b');
      $eh_obrigatorio_typical_age_range = $conf->retornaInformarEducationalTypicalAgeRange('b');
      $eh_obrigatorio_difficulty = $conf->retornaInformarEducationalDifficulty('b');
      $eh_obrigatorio_typical_learning_time = $conf->retornaInformarEducationalTypicalLearningTime('b');
      $eh_obrigatorio_description = $conf->retornaInformarEducationalDescription('b');
      $eh_obrigatorio_language = $conf->retornaInformarEducationalLanguage('b');
      $eh_obrigatorio_learning_content_type = $conf->retornaInformarEducationalLearningContentType('b');
      $eh_obrigatorio_interaction = $conf->retornaInformarEducationalInteraction('b');
      $eh_obrigatorio_didatic_strategy = $conf->retornaInformarEducationalDidaticStrategy('b');
      $util->campoHidden('eh_obrigatorio_educational_interactivity_type', $eh_obrigatorio_interactivity_type);
      $util->campoHidden('eh_obrigatorio_educational_learning_resource_type', $eh_obrigatorio_learning_resource_type);
      $util->campoHidden('eh_obrigatorio_educational_interactivity_level', $eh_obrigatorio_interactivity_level);
      $util->campoHidden('eh_obrigatorio_educational_sem_antic_density', $eh_obrigatorio_sem_antic_density);
      $util->campoHidden('eh_obrigatorio_educational_intended_end_user_role', $eh_obrigatorio_intended_end_user_role);
      $util->campoHidden('eh_obrigatorio_educational_context', $eh_obrigatorio_context);
      $util->campoHidden('eh_obrigatorio_educational_typical_age_range', $eh_obrigatorio_typical_age_range);
      $util->campoHidden('eh_obrigatorio_educational_difficulty', $eh_obrigatorio_difficulty);
      $util->campoHidden('eh_obrigatorio_educational_typical_learning_time', $eh_obrigatorio_typical_learning_time);
      $util->campoHidden('eh_obrigatorio_educational_description', $eh_obrigatorio_description);
      $util->campoHidden('eh_obrigatorio_educational_language', $eh_obrigatorio_language);
      $util->campoHidden('eh_obrigatorio_educational_learning_content_type', $eh_obrigatorio_learning_content_type);
      $util->campoHidden('eh_obrigatorio_educational_interaction', $eh_obrigatorio_interaction);
      $util->campoHidden('eh_obrigatorio_educational_didatic_strategy', $eh_obrigatorio_didatic_strategy);


      if ($eh_informar_educational == '1') {
        $util->linhaComentario('<hr>');
        $util->linhaComentarioChamada('Informações Educacionais');
      
        if ($eh_informar_interactivity_type == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_interactivity_type, $conf->retornaDescricaoCampoEducationalTipoInteratividade(), 'ds_educational_interactivity_type', '250', '840', $ds_interactivity_type, 1);
        } else {
          $util->campoHidden('ds_educational_interactivity_type', $ds_interactivity_type);
        }                                                                                                                                            
        if ($eh_informar_learning_resource_type == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_learning_resource_type, $conf->retornaDescricaoCampoEducationalRecursosAprendizagemTipo(), 'ds_educational_learning_resource_type', '250', '840', $ds_learning_resource_type, 1);
        } else {
          $util->campoHidden('ds_educational_learning_resource_type', $ds_learning_resource_type);
        }                                                                                                                             
        if ($eh_informar_interactivity_level == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_interactivity_level, $conf->retornaDescricaoCampoEducationalNivelInteratividade(), 'ds_educational_interactivity_level', '250', '840', $ds_interactivity_level, 1);
        } else {
          $util->campoHidden('ds_educational_interactivity_level', $ds_interactivity_level);
        }                                                                                                                             
        if ($eh_informar_sem_antic_density == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_sem_antic_density, $conf->retornaDescricaoCampoEducationalDensidade(), 'ds_educational_sem_antic_density', '250', '840', $ds_sem_antic_density, 1);
        } else {
          $util->campoHidden('ds_educational_sem_antic_density', $ds_sem_antic_density);
        }                                                                                                                     
        if ($eh_informar_intended_end_user_role == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_intended_end_user_role, $conf->retornaDescricaoCampoEducationalFuncaoDestinacaoUsuario(), 'ds_educational_intended_end_user_role', '250', '840', $ds_intended_end_user_role, 1);
        } else {
          $util->campoHidden('ds_educational_intended_end_user_role', $ds_intended_end_user_role);
        }                                                                                                                                          
        if ($eh_informar_context == '1') {
          $util->linhaTextoHint($eh_obrigatorio_context, $conf->retornaDescricaoCampoEducationalContexto(), 'ds_educational_context', $ds_context, 5, 840, 1);
        } else {
          $util->campoHidden('ds_educational_context', $ds_context);
        }                                                                                      
        if ($eh_informar_typical_age_range == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_typical_age_range, $conf->retornaDescricaoCampoEducationalFaixaTipicaIdade(), 'ds_educational_typical_age_range', '250', '840', $ds_typical_age_range, 1);
        } else {
          $util->campoHidden('ds_educational_typical_age_range', $ds_typical_age_range);
        }                       
        if ($eh_informar_difficulty == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_difficulty, $conf->retornaDescricaoCampoEducationalDificuldade(), 'ds_educational_difficulty', '250', '840', $ds_difficulty, 1);
        } else {
          $util->campoHidden('ds_educational_difficulty', $ds_difficulty);
        }                                                                                               
        if ($eh_informar_typical_learning_time == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_typical_learning_time, $conf->retornaDescricaoCampoEducationalTempoTipicoAprendizagem(), 'ds_educational_typical_learning_time', '250', '840', $ds_typical_learning_time, 1);
        } else {
          $util->campoHidden('ds_educational_typical_learning_time', $ds_typical_learning_time);
        }                                                                                                                               
        if ($eh_informar_description == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_description, $conf->retornaDescricaoCampoEducationalDescricao(), 'ds_educational_description', '250', '840', $ds_description, 1);
        } else {
          $util->campoHidden('ds_educational_description', $ds_description);
        }  
        if ($eh_informar_language == '1') {        
          require_once 'conteudos/linguagens.php';                              $lin = new Linguagem();
          $lin->retornaSeletorLinguagem($cd_language, 'cd_educational_language', '840', 1, $conf->retornaDescricaoCampoEducationalIdioma());
        } else {
          $util->campoHidden('cd_educational_language', $cd_language);
        }
        if ($eh_informar_learning_content_type == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_learning_content_type, $conf->retornaDescricaoCampoEducationalAprenderTipoConteudo(), 'ds_educational_learning_content_type', '250', '840', $ds_learning_content_type, 1);
        } else {
          $util->campoHidden('ds_educational_learning_content_type', $ds_learning_content_type);
        }                                                                                                                          
        if ($eh_informar_interaction == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_interaction, $conf->retornaDescricaoCampoEducationalInteracao(), 'ds_educational_interaction', '250', '840', $ds_interaction, 1);
        } else {
          $util->campoHidden('ds_educational_interaction', $ds_interaction);
        }                                                                                               
        if ($eh_informar_didatic_strategy == '1') {
          $util->linhaUmCampoTextHint($eh_obrigatorio_didatic_strategy, $conf->retornaDescricaoCampoEducationalEstrategiaDidatica(), 'ds_educational_didatic_strategy', '250', '840', $ds_didatic_strategy, 1);
        } else {
          $util->campoHidden('ds_educational_didatic_strategy', $ds_didatic_strategy);
        }                                                                                                                 
      } else {
        $util->campoHidden('ds_educational_interactivity_type', $ds_interactivity_type);
        $util->campoHidden('ds_educational_learning_resource_type', $ds_learning_resource_type);
        $util->campoHidden('ds_educational_interactivity_level', $ds_interactivity_level);
        $util->campoHidden('ds_educational_sem_antic_density', $ds_sem_antic_density);
        $util->campoHidden('ds_educational_intended_end_user_role', $ds_intended_end_user_role);
        $util->campoHidden('ds_educational_context', $ds_context);
        $util->campoHidden('ds_educational_typical_age_range', $ds_typical_age_range);
        $util->campoHidden('ds_educational_difficulty', $ds_difficulty);
        $util->campoHidden('ds_educational_typical_learning_time', $ds_typical_learning_time);
        $util->campoHidden('ds_educational_description', $ds_description);
        $util->campoHidden('cd_educational_language', $cd_language);
        $util->campoHidden('ds_educational_learning_content_type', $ds_learning_content_type);
        $util->campoHidden('ds_educational_interaction', $ds_interaction);
        $util->campoHidden('ds_educational_didatic_strategy', $ds_didatic_strategy);
      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/linguagens.php';                                  $lin = new Linguagem();

      $cd_educational = addslashes($_POST['cd_educational']);
      $eh_informar_interactivity_type = addslashes($_POST['eh_informar_educational_interactivity_type']);
      $eh_informar_learning_resource_type = addslashes($_POST['eh_informar_educational_learning_resource_type']);
      $eh_informar_interactivity_level = addslashes($_POST['eh_informar_educational_interactivity_level']);
      $eh_informar_sem_antic_density = addslashes($_POST['eh_informar_educational_sem_antic_density']);
      $eh_informar_intended_end_user_role = addslashes($_POST['eh_informar_educational_intended_end_user_role']);
      $eh_informar_context = addslashes($_POST['eh_informar_educational_context']);
      $eh_informar_typical_age_range = addslashes($_POST['eh_informar_educational_typical_age_range']);
      $eh_informar_difficulty = addslashes($_POST['eh_informar_educational_difficulty']);
      $eh_informar_typical_learning_time = addslashes($_POST['eh_informar_educational_typical_learning_time']);
      $eh_informar_description = addslashes($_POST['eh_informar_educational_description']);
      $cd_language = addslashes($_POST['cd_educational_language']);
      $eh_informar_language = addslashes($_POST['eh_informar_educational_language']);
      $eh_informar_learning_content_type = addslashes($_POST['eh_informar_educational_learning_content_type']);
      $eh_informar_interaction = addslashes($_POST['eh_informar_educational_interaction']);
      $eh_informar_didatic_strategy = addslashes($_POST['eh_informar_educational_didatic_strategy']);
      $ds_interactivity_type = $util->limparVariavel($_POST['ds_educational_interactivity_type']);
      $ds_learning_resource_type = $util->limparVariavel($_POST['ds_educational_learning_resource_type']);
      $ds_interactivity_level = $util->limparVariavel($_POST['ds_educational_interactivity_level']);
      $ds_sem_antic_density = $util->limparVariavel($_POST['ds_educational_sem_antic_density']);
      $ds_intended_end_user_role = $util->limparVariavel($_POST['ds_educational_intended_end_user_role']);
      $ds_context = $util->limparVariavel($_POST['ds_educational_context']);
      $ds_typical_age_range = $util->limparVariavel($_POST['ds_educational_typical_age_range']);
      $ds_difficulty = $util->limparVariavel($_POST['ds_educational_difficulty']);
      $ds_typical_learning_time = $util->limparVariavel($_POST['ds_educational_typical_learning_time']);
      $ds_description = $util->limparVariavel($_POST['ds_educational_description']);
      $ds_learning_content_type = $util->limparVariavel($_POST['ds_educational_learning_content_type']);
      $ds_interaction = $util->limparVariavel($_POST['ds_educational_interaction']);
      $ds_didatic_strategy = $util->limparVariavel($_POST['ds_educational_didatic_strategy']);

      $dados = $lin->selectDadosLinguagem($cd_language);
      $linguagem = $dados['nm_linguagem'];

      $_SESSION['life_agrupador_termos_cadastro'].= " | ".$linguagem." | ".$ds_interactivity_type." | ".$ds_learning_resource_type." | ".$ds_interactivity_level." | ".$ds_sem_antic_density." | ".$ds_intended_end_user_role." | ".$ds_context." | ".$ds_typical_age_range." | ".$ds_difficulty." | ".$ds_typical_learning_time." | ".$ds_description." | ".$ds_learning_content_type." | ".$ds_interaction." | ".$ds_didatic_strategy;

      if ($cd_educational > 0) {
        return $this->alteraEducational($cd_educational, $ds_interactivity_type, $eh_informar_interactivity_type, $ds_learning_resource_type, $eh_informar_learning_resource_type, $ds_interactivity_level, $eh_informar_interactivity_level, $ds_sem_antic_density, $eh_informar_sem_antic_density, $ds_intended_end_user_role, $eh_informar_intended_end_user_role, $ds_context, $eh_informar_context, $ds_typical_age_range, $eh_informar_typical_age_range, $ds_difficulty, $eh_informar_difficulty, $ds_typical_learning_time, $eh_informar_typical_learning_time, $ds_description, $eh_informar_description, $cd_language, $eh_informar_language, $ds_learning_content_type, $eh_informar_learning_content_type, $ds_interaction, $eh_informar_interaction, $ds_didatic_strategy, $eh_informar_didatic_strategy);
      } else {
        return $this->insereEducational($ds_interactivity_type, $eh_informar_interactivity_type, $ds_learning_resource_type, $eh_informar_learning_resource_type, $ds_interactivity_level, $eh_informar_interactivity_level, $ds_sem_antic_density, $eh_informar_sem_antic_density, $ds_intended_end_user_role, $eh_informar_intended_end_user_role, $ds_context, $eh_informar_context, $ds_typical_age_range, $eh_informar_typical_age_range, $ds_difficulty, $eh_informar_difficulty, $ds_typical_learning_time, $eh_informar_typical_learning_time, $ds_description, $eh_informar_description, $cd_language, $eh_informar_language, $ds_learning_content_type, $eh_informar_learning_content_type, $ds_interaction, $eh_informar_interaction, $ds_didatic_strategy, $eh_informar_didatic_strategy);
      }
    } 

    public function imprimeDados($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemEducational($cd_educational);

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_interactivity_type = $dados['eh_informar_interactivity_type'];
        $eh_informar_learning_resource_type = $dados['eh_informar_learning_resource_type'];
        $eh_informar_interactivity_level = $dados['eh_informar_interactivity_level'];
        $eh_informar_sem_antic_density = $dados['eh_informar_sem_antic_density'];
        $eh_informar_intended_end_user_role = $dados['eh_informar_intended_end_user_role'];
        $eh_informar_context = $dados['eh_informar_context'];
        $eh_informar_typical_age_range = $dados['eh_informar_typical_age_range'];
        $eh_informar_difficulty = $dados['eh_informar_difficulty'];
        $eh_informar_typical_learning_time = $dados['eh_informar_typical_learning_time'];
        $eh_informar_description = $dados['eh_informar_description'];
        $eh_informar_language = $dados['eh_informar_language'];
        $eh_informar_learning_content_type = $dados['eh_informar_learning_content_type'];
        $eh_informar_interaction = $dados['eh_informar_interaction'];
        $eh_informar_didatic_strategy = $dados['eh_informar_didatic_strategy'];
      } else {
        $eh_informar_interactivity_type = $conf->retornaInformarEducationalInteractivityType($tipo);
        $eh_informar_learning_resource_type = $conf->retornaInformarEducationalLearningResourceType($tipo);
        $eh_informar_interactivity_level = $conf->retornaInformarEducationalInteractivityLevel($tipo);
        $eh_informar_sem_antic_density = $conf->retornaInformarEducationalSemAnticDensity($tipo);
        $eh_informar_intended_end_user_role = $conf->retornaInformarEducationalIntendedEndUserRole($tipo);
        $eh_informar_context = $conf->retornaInformarEducationalContext($tipo);
        $eh_informar_typical_age_range = $conf->retornaInformarEducationalTypicalAgeRange($tipo);
        $eh_informar_difficulty = $conf->retornaInformarEducationalDifficulty($tipo);
        $eh_informar_typical_learning_time = $conf->retornaInformarEducationalTypicalLearningTime($tipo);
        $eh_informar_description = $conf->retornaInformarEducationalDescription($tipo);
        $eh_informar_language = $conf->retornaInformarEducationalLanguage($tipo);
        $eh_informar_learning_content_type = $conf->retornaInformarEducationalLearningContentType($tipo);
        $eh_informar_interaction = $conf->retornaInformarEducationalInteraction($tipo);
        $eh_informar_didatic_strategy = $conf->retornaInformarEducationalDidaticStrategy($tipo);
      }
      
      $ds_interactivity_type = $dados['ds_interactivity_type'];
      $ds_learning_resource_type = $dados['ds_learning_resource_type'];
      $ds_interactivity_level = $dados['ds_interactivity_level'];
      $ds_sem_antic_density = $dados['ds_sem_antic_density'];
      $ds_intended_end_user_role = $dados['ds_intended_end_user_role'];
      $ds_context = $dados['ds_context'];
      $ds_typical_age_range = $dados['ds_typical_age_range'];
      $ds_difficulty = $dados['ds_difficulty'];
      $ds_typical_learning_time = $dados['ds_typical_learning_time'];
      $ds_description = $dados['ds_description'];
      $cd_language = $dados['cd_language'];
      $ds_learning_content_type = $dados['ds_learning_content_type'];
      $ds_interaction = $dados['ds_interaction'];
      $ds_didatic_strategy = $dados['ds_didatic_strategy'];

      $retorno = '';

      if ($eh_informar_educational == '1') {
        if ($eh_informar_interactivity_type == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalTipoInteratividade().': '.$ds_interactivity_type.'<br />';
        }                                                                                                                                            
        if ($eh_informar_learning_resource_type == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalRecursosAprendizagemTipo().': '.$ds_learning_resource_type.'<br />';
        }                                                                                                                             
        if ($eh_informar_interactivity_level == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalNivelInteratividade().': '.$ds_interactivity_level.'<br />';
        }                                                                                                                             
        if ($eh_informar_sem_antic_density == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalDensidade().': '.$ds_sem_antic_density.'<br />';
        }                                                                                                                     
        if ($eh_informar_intended_end_user_role == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalFuncaoDestinacaoUsuario().': '.$ds_intended_end_user_role.'<br />';
        }                                                                                                                                          
        if ($eh_informar_context == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalContexto().': '.nl2br($ds_context).'<br />';
        }                                                                                      
        if ($eh_informar_typical_age_range == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalFaixaTipicaIdade().': '.$ds_typical_age_range.'<br />';
        }                       
        if ($eh_informar_difficulty == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalDificuldade().': '.$ds_difficulty.'<br />';
        }                                                                                               
        if ($eh_informar_typical_learning_time == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalTempoTipicoAprendizagem().': '.$ds_typical_learning_time.'<br />';
        }                                                                                                                               
        if ($eh_informar_description == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalDescricao().': '.$ds_description.'<br />';
        }  
        if ($eh_informar_language == '1') {        
          require_once 'conteudos/linguagens.php';                              $lin = new Linguagem();
          $retorno.= $lin->retornaDados($cd_language, $conf->retornaDescricaoCampoEducationalIdioma());
        }
        if ($eh_informar_learning_content_type == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalAprenderTipoConteudo().': '.$ds_learning_content_type.'<br />';
        }                                                                                                                          
        if ($eh_informar_interaction == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalInteracao().': '.$ds_interaction.'<br />';
        }                                                                                               
        if ($eh_informar_didatic_strategy == '1') {
          $retorno.= $conf->retornaDescricaoCampoEducationalEstrategiaDidatica().': '.$ds_didatic_strategy.'<br />';
        }                                                                                                                 
      }
      return $retorno;
    }


//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemEducational($cd_educational) {
      $sql  = "SELECT * ".
              "FROM life_educational ".
              "WHERE cd_educational = '$cd_educational' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA EDUCATIONAL!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereEducational($ds_interactivity_type, $eh_informar_interactivity_type, $ds_learning_resource_type, $eh_informar_learning_resource_type, $ds_interactivity_level, $eh_informar_interactivity_level, $ds_sem_antic_density, $eh_informar_sem_antic_density, $ds_intended_end_user_role, $eh_informar_intended_end_user_role, $ds_context, $eh_informar_context, $ds_typical_age_range, $eh_informar_typical_age_range, $ds_difficulty, $eh_informar_difficulty, $ds_typical_learning_time, $eh_informar_typical_learning_time, $ds_description, $eh_informar_description, $cd_language, $eh_informar_language, $ds_learning_content_type, $eh_informar_learning_content_type, $ds_interaction, $eh_informar_interaction, $ds_didatic_strategy, $eh_informar_didatic_strategy) {
      $sql = "INSERT INTO life_educational ".
             "(ds_interactivity_type, eh_informar_interactivity_type, ds_learning_resource_type, eh_informar_learning_resource_type, ds_interactivity_level, eh_informar_interactivity_level, ds_sem_antic_density, eh_informar_sem_antic_density, ds_intended_end_user_role, eh_informar_intended_end_user_role, ds_context, eh_informar_context, ds_typical_age_range, eh_informar_typical_age_range, ds_difficulty, eh_informar_difficulty, ds_typical_learning_time, eh_informar_typical_learning_time, ds_description, eh_informar_description, cd_language, eh_informar_language, ds_learning_content_type, eh_informar_learning_content_type, ds_interaction, eh_informar_interaction, ds_didatic_strategy, eh_informar_didatic_strategy) ".
             "VALUES ".
             "(\"$ds_interactivity_type\", \"$eh_informar_interactivity_type\", \"$ds_learning_resource_type\", \"$eh_informar_learning_resource_type\", \"$ds_interactivity_level\", \"$eh_informar_interactivity_level\", \"$ds_sem_antic_density\", \"$eh_informar_sem_antic_density\", \"$ds_intended_end_user_role\", \"$eh_informar_intended_end_user_role\", \"$ds_context\", \"$eh_informar_context\", \"$ds_typical_age_range\", \"$eh_informar_typical_age_range\", \"$ds_difficulty\", \"$eh_informar_difficulty\", \"$ds_typical_learning_time\", \"$eh_informar_typical_learning_time\", \"$ds_description\", \"$eh_informar_description\", \"$cd_language\", \"$eh_informar_language\", \"$ds_learning_content_type\", \"$eh_informar_learning_content_type\", \"$ds_interaction\", \"$eh_informar_interaction\", \"$ds_didatic_strategy\", \"$eh_informar_didatic_strategy\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'educational');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA EDUCATIONAL!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_educational) codigo ".
                "FROM life_educational ".
                "WHERE ds_interactivity_type = '$ds_interactivity_type' ".
                "AND ds_learning_resource_type = '$ds_learning_resource_type' ".
                "AND ds_interactivity_level = '$ds_interactivity_level' ".
                "AND ds_sem_antic_density = '$ds_sem_antic_density' ".
                "AND ds_intended_end_user_role = '$ds_intended_end_user_role' ".
                "AND ds_context = '$ds_context' ".
                "AND ds_typical_age_range = '$ds_typical_age_range' ".
                "AND ds_difficulty = '$ds_difficulty' ".
                "AND ds_typical_learning_time = '$ds_typical_learning_time' ".
                "AND ds_description = '$ds_description' ".
                "AND cd_language = '$cd_language' ".
                "AND ds_learning_content_type = '$ds_learning_content_type' ".
                "AND ds_interaction = '$ds_interaction' ".
                "AND ds_didatic_strategy = '$ds_didatic_strategy' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA EDUCATIONAL!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraEducational($cd_educational, $ds_interactivity_type, $eh_informar_interactivity_type, $ds_learning_resource_type, $eh_informar_learning_resource_type, $ds_interactivity_level, $eh_informar_interactivity_level, $ds_sem_antic_density, $eh_informar_sem_antic_density, $ds_intended_end_user_role, $eh_informar_intended_end_user_role, $ds_context, $eh_informar_context, $ds_typical_age_range, $eh_informar_typical_age_range, $ds_difficulty, $eh_informar_difficulty, $ds_typical_learning_time, $eh_informar_typical_learning_time, $ds_description, $eh_informar_description, $cd_language, $eh_informar_language, $ds_learning_content_type, $eh_informar_learning_content_type, $ds_interaction, $eh_informar_interaction, $ds_didatic_strategy, $eh_informar_didatic_strategy) {
      $sql = "UPDATE life_educational SET ".
             "ds_interactivity_type = \"$ds_interactivity_type\", ".
             "eh_informar_interactivity_type = \"$eh_informar_interactivity_type\", ".
             "ds_learning_resource_type = \"$ds_learning_resource_type\", ".
             "eh_informar_learning_resource_type = \"$eh_informar_learning_resource_type\", ".
             "ds_interactivity_level = \"$ds_interactivity_level\", ".
             "eh_informar_interactivity_level = \"$eh_informar_interactivity_level\", ".
             "ds_sem_antic_density = \"$ds_sem_antic_density\", ".
             "eh_informar_sem_antic_density = \"$eh_informar_sem_antic_density\", ".
             "ds_intended_end_user_role = \"$ds_intended_end_user_role\", ".
             "eh_informar_intended_end_user_role = \"$eh_informar_intended_end_user_role\", ".
             "ds_context = \"$ds_context\", ".
             "eh_informar_context = \"$eh_informar_context\", ".
             "ds_typical_age_range = \"$ds_typical_age_range\", ".
             "eh_informar_typical_age_range = \"$eh_informar_typical_age_range\", ".
             "ds_difficulty = \"$ds_difficulty\", ".
             "eh_informar_difficulty = \"$eh_informar_difficulty\", ".
             "ds_typical_learning_time = \"$ds_typical_learning_time\", ".
             "eh_informar_typical_learning_time = \"$eh_informar_typical_learning_time\", ".
             "ds_description = \"$ds_description\", ".
             "eh_informar_description = \"$eh_informar_description\", ".
             "cd_language = \"$cd_language\", ".
             "eh_informar_language = \"$eh_informar_language\", ".
             "ds_learning_content_type = \"$ds_learning_content_type\", ".
             "eh_informar_learning_content_type = \"$eh_informar_learning_content_type\", ".
             "ds_interaction = \"$ds_interaction\", ".
             "eh_informar_interaction = \"$eh_informar_interaction\", ".
             "ds_didatic_strategy = \"$ds_didatic_strategy\", ".
             "eh_informar_didatic_strategy = \"$eh_informar_didatic_strategy\" ".
             "WHERE cd_educational = '$cd_educational' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'educational');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA EDUCATIONAL!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>