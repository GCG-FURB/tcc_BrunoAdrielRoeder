<script>
<!--
function valida(f) {
  if (f.nm_objeto_aprendizagem.value == '') {
    alert('Informe o Nome do Objeto de Aprendizagem!');
    return false;                                  
  }

  if (f.eh_obrigatorio_general.value == '1') {
    if (f.eh_obrigatorio_general_identifier.value == '1') {
      if (f.ds_general_identifier.value == '') {
        alert('Informe o Identificador, em Informa��es Gerais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_general_title.value == '1') {
      if (f.ds_general_title.value == '') {
        alert('Informe o T�tulo, em Informa��es Gerais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_general_language.value == '1') {
      if (f.cd_general_language.value == '0') {
        alert('Informe o Idioma, em Informa��es Gerais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_general_description.value == '1') {
      if (f.ds_general_description.value == '') {
        alert('Informe a Descri��o, em Informa��es Gerais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_general_keyword.value == '1') {
      if (f.ds_general_keyword.value == '') {
        alert('Informe as Palavras Chave, em Informa��es Gerais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_general_coverage.value == '1') {
      if (f.cd_general_coverage.value == '0') {
        alert('Informe a �rea de Conhecimento, em Informa��es Gerais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_general_structure.value == '1') {
      if (f.ds_general_structure.value == '') {
        alert('Informe a Estrutura, em Informa��es Gerais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_general_agregation_level.value == '1') {
      if (f.ds_general_agregation_level.value == '') {
        alert('Informe o N�vel de agrega��o, em Informa��es Gerais!');
        return false;
      }
    }
  }
  
  if (f.eh_obrigatorio_lyfe_cycle.value == '1') {
    if (f.eh_obrigatorio_lyfe_cycle_version.value == '1') {
      if (f.ds_lyfe_cycle_version.value == '') {
        alert('Informe a Vers�o, em Ciclo de Vida!');
        return false;
      }
    }
    if (f.eh_obrigatorio_lyfe_cycle_status.value == '1') {
      if (f.cd_lyfe_cycle_status.value == '0') {
        alert('Informe o Status, em Ciclo de Vida!');
        return false;
      }
    }
    if (f.eh_obrigatorio_lyfe_cycle_contribute.value == '1') {
      if (f.ds_lyfe_cycle_contribute.value == '') {
        alert('Informe a Contribui��o, em Ciclo de Vida!');
        return false;
      }
    }
  }
  
  
  if (f.eh_obrigatorio_meta_metadata.value == '1') {   
    if (f.eh_obrigatorio_meta_metadata_identifier.value == '1') {
      if (f.ds_meta_metadata_identifier.value == '') {
        alert('Informe o Identificador, em Meta-Metadata!');
        return false;
      }
    }
    if (f.eh_obrigatorio_meta_metadata_contribute.value == '1') {
      if (f.ds_meta_metadata_contribute.value == '') {
        alert('Informe a Contribui��o, em Meta-Metadata!');
        return false;
      }
    }
    if (f.eh_obrigatorio_meta_metadata_metadata_schema.value == '1') {
      if (f.ds_meta_metadata_metadata_schema.value == '') {
        alert('Informe o Esquema de Metadados, em Meta-Metadata!');
        return false;
      }
    }
    if (f.eh_obrigatorio_meta_metadata_language.value == '1') {
      if (f.cd_meta_metadata_language.value == '0') {
        alert('Informe o Idioma, em Meta-Metadata!');
        return false;
      }
    }
  }

  if (f.eh_obrigatorio_technical.value == '1') {

    if (f.cd_technical_format.value == '0') {
      alert('Informe o Formato, em Informa��es T�cnicas!');
      return false;
    } else {
      var cd_formato = f.cd_technical_format.value;
    }

    if (f.eh_obrigatorio_technical_size.value == '1') {
      if (f.ds_technical_size.value == '') {
        alert('Informe o Tamanho, em Informa��es T�cnicas!');
        return false;
      }
    }
//quando arquivo, validar tamanho e validar formato    
    if (cd_formato == '1') {
      if (f.ds_technical_location.value == '') {
        alert('Informe a Localiza��o do Arquivo de �udio, em Informa��es T�cnicas!');
        return false;
      }                                                     
    } else {
      if (cd_formato == '2') {
        if (f.ds_technical_location.value == '') {
          alert('Informe a Localiza��o do Arquivo de V�deo, em Informa��es T�cnicas!');
          return false;
        }
      } else {
        if (cd_formato == '3') {
          if ((f.ds_technical_location.value == '') && (f.ds_technical_location_antigo.value == '')) {
            alert('Selecione o Arquivo de Texto, em Informa��es T�cnicas!');
            return false;
          } else {
            if (f.ds_technical_location.value != '') {
              var ds_limite_tamanho_arquivos = f.ds_limite_tamanho_arquivos.value;
              var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",","); 
              ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
              var ds_tamanho_arquivo = eval(f.ds_technical_location.files[0].size);
              var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
              if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                alert('O Arquivo de Texto n�o pode exceder o limite de '+tamanho_escrita+'MB!');
                return false;
              }

              var ds_location = f.ds_technical_location.value;
              var tamanho = f.ds_technical_location.value.length - 1;
              var posicao = 0;
              for (i=tamanho; i>0; i--) { 
                if (ds_location[i] == '.') {
                  if (posicao == '0') {
                    var posicao = i;
                  }
                }            
              }
              var extensao = '';
              if (posicao != 0) {
                posicao += 1;
                for (i = posicao; i <= tamanho; i++) {
                  extensao = extensao+ds_location[i]; 
                }
              }  
              
              var achou_extensao = false;
              var quantidade = f.quantidade.value;
              var formatos = '';
              for (i=1;i<=quantidade;i++) {
                var campo = 'extensao_'+i;
                var esta_extensao = document.getElementById(campo).value;
                if (formatos == '') {
                  formatos = esta_extensao;
                } else {
                  formatos = formatos+' - '+esta_extensao;
                }
                if (esta_extensao.toUpperCase() == extensao.toUpperCase()) {
                  achou_extensao = true;
                }
              }                       
              
              if (!achou_extensao) {
                alert('Arquivo de Texto em formato inv�lido! Formatos permitidos [ '+formatos+' ]');
                return false;            
              }
            }                                                                               
          }                                            
        } else {
          if (cd_formato == '4') {
            if ((f.ds_technical_location.value == '') && (f.ds_technical_location_antigo.value == '')) {
              alert('Selecione o Arquivo de Imagem, em Informa��es T�cnicas!');
              return false;
            } else {
              if (f.ds_technical_location.value != '') {
                var ds_limite_tamanho_arquivos = f.ds_limite_tamanho_arquivos.value;
                var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",","); 
                ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
                var ds_tamanho_arquivo = eval(f.ds_technical_location.files[0].size);
                var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
                if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                  alert('O Arquivo de Imagem n�o pode exceder o limite de '+tamanho_escrita+'MB!');
                  return false;
                }
    
                var ds_location = f.ds_technical_location.value;
                var tamanho = f.ds_technical_location.value.length - 1;
                var posicao = 0;
                for (i=tamanho; i>0; i--) { 
                  if (ds_location[i] == '.') {
                    if (posicao == '0') {
                      var posicao = i;
                    }
                  }            
                }
                var extensao = '';
                if (posicao != 0) {
                  posicao += 1;
                  for (i = posicao; i <= tamanho; i++) {
                    extensao = extensao+ds_location[i]; 
                  }
                }  
              
                var achou_extensao = false;
                var quantidade = f.quantidade.value;
                var formatos = '';
                for (i=1;i<=quantidade;i++) {
                  var campo = 'extensao_'+i;
                  var esta_extensao = document.getElementById(campo).value;
                  if (formatos == '') {
                    formatos = esta_extensao;
                  } else {
                    formatos = formatos+' - '+esta_extensao;
                  }
                  if (esta_extensao.toUpperCase() == extensao.toUpperCase()) {
                    achou_extensao = true;
                  }
                }                       
            
                if (!achou_extensao) {
                  alert('Arquivo de Imagem em formato inv�lido! Formatos permitidos [ '+formatos+' ]');
                  return false;            
                }
              }                   
            }                                            
          } else {
            if (cd_formato == '5') {
              if ((f.ds_technical_location.value == '') && (f.ds_technical_location_antigo.value == '')) {
                alert('Selecione o Arquivo do Aplicativo, em Informa��es T�cnicas!');
                return false;
              } else {
                if (f.ds_technical_location.value != '') {
                  var ds_limite_tamanho_arquivos = f.ds_limite_tamanho_arquivos.value;
                  var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",","); 
                  ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
                  var ds_tamanho_arquivo = eval(f.ds_technical_location.files[0].size);
                  var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
                  if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                    alert('O Arquivo do Aplicativo n�o pode exceder o limite de '+tamanho_escrita+'MB!');
                    return false;
                  }

                  var ds_location = f.ds_technical_location.value;
                  var tamanho = f.ds_technical_location.value.length - 1;
                  var posicao = 0;
                  for (i=tamanho; i>0; i--) { 
                    if (ds_location[i] == '.') {
                      if (posicao == '0') {
                        var posicao = i;
                      }
                    }            
                  }
                  var extensao = '';
                  if (posicao != 0) {
                    posicao += 1;
                    for (i = posicao; i <= tamanho; i++) {
                        extensao = extensao+ds_location[i]; 
                    }
                  }  
            
                  var achou_extensao = false;
                  var quantidade = f.quantidade.value;
                  var formatos = '';
                  for (i=1;i<=quantidade;i++) {
                    var campo = 'extensao_'+i;
                    var esta_extensao = document.getElementById(campo).value;
                    if (formatos == '') {
                      formatos = esta_extensao;
                    } else {
                      formatos = formatos+' - '+esta_extensao;
                    }   
                    if (esta_extensao.toUpperCase() == extensao.toUpperCase()) {
                      achou_extensao = true;
                    }
                  }                       
              
                  if (!achou_extensao) {
                    alert('Arquivo do Aplicativo em formato inv�lido! Formatos permitidos [ '+formatos+' ]');
                    return false;            
                  }
                }     
              }                                            
            } else {
              if (cd_formato == '6') {
                if (f.ds_technical_location.value == '') {
                  alert('Informe o Link Externo, em Informa��es T�cnicas!');
                  return false;
                }
              } else {
                if (cd_formato == '7') {
                  if ((f.ds_technical_location.value == '') && (f.ds_technical_location_antigo.value == "")) {
                    alert('Selecione o Arquivo da Apresenta��o, em Informa��es T�cnicas!');
                    return false;
                  } else {
                    if (f.ds_technical_location.value != '') { 
                      var ds_limite_tamanho_arquivos = f.ds_limite_tamanho_arquivos.value;
                      var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",","); 
                      ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
                      var ds_tamanho_arquivo = eval(f.ds_technical_location.files[0].size);
                      var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
                      if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                        alert('O Arquivo da Apresenta��o n�o pode exceder o limite de '+tamanho_escrita+'MB!');
                        return false;
                      }

                      var ds_location = f.ds_technical_location.value;
                      var tamanho = f.ds_technical_location.value.length - 1;
                      var posicao = 0;
                      for (i=tamanho; i>0; i--) { 
                        if (ds_location[i] == '.') {
                          if (posicao == '0') {
                            var posicao = i;
                          }
                        }            
                      }
                      var extensao = '';
                      if (posicao != 0) {
                        posicao += 1;
                        for (i = posicao; i <= tamanho; i++) {
                          extensao = extensao+ds_location[i]; 
                        }
                      }  
              
                      var achou_extensao = false;
                      var quantidade = f.quantidade.value;
                      var formatos = '';
                      for (i=1;i<=quantidade;i++) {
                        var campo = 'extensao_'+i;
                        var esta_extensao = document.getElementById(campo).value;
                        if (formatos == '') {
                          formatos = esta_extensao;
                        } else {
                          formatos = formatos+' - '+esta_extensao;
                        }
                        if (esta_extensao.toUpperCase() == extensao.toUpperCase()) {
                          achou_extensao = true;
                        }
                      }                       
            
                      if (!achou_extensao) {
                        alert('Arquivo da Apresenta��o em formato inv�lido! Formatos permitidos [ '+formatos+' ]');
                        return false;            
                      }
                    }                       
                  }                                            
                }
              }
            }          
          }
        }
      }
    } 
    
    if (f.eh_obrigatorio_technical_requirement.value == '1') {
      if (f.ds_technical_requirement.value == '') {
        alert('Informe os Requerimentos, em Informa��es T�cnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_composite.value == '1') {
      if (f.ds_technical_composite.value == '') {
        alert('Informe a Composi��o, em Informa��es T�cnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_installation_remarks.value == '1') {
      if (f.ds_technical_installation_remarks.value == '') {
        alert('Informe as Observa��es para a instala��o, em Informa��es T�cnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_other_plataforms_requirements.value == '1') {
      if (f.ds_technical_other_plataforms_requirements.value == '') {
        alert('Informe Outros Requerimentos da plataforma, em Informa��es T�cnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_duration.value == '1') {
      if (f.ds_technical_duration.value == '') {
        alert('Informe a Dura��o, em Informa��es T�cnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_supported_plataform.value == '1') {
      if (f.ds_technical_supported_plataform.value == '') {
        alert('Informe as Plataformas Suportadas, em Informa��es T�cnicas!');
        return false;
      }
    }

    if (f.eh_obrigatorio_technical_plataform_specific_features.value == '1') {
      if (f.eh_obrigatorio_technical_plataform_specific_features_plataform_type.value == '1') {
        if (f.ds_technical_plataform_specific_features_plataform_type.value == '') {
          alert('Informe o Tipo de Plataforma, em Informa��es T�cnicas - Caracter�sticas Espec�ficas!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_format.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_format.value == '') {
          alert('Informe o Formato espec�fico, em Informa��es T�cnicas - Caracter�sticas Espec�ficas!');
          return false;
        }
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_size.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_size.value == '') {
          alert('Informe o Tamanho, em Informa��es T�cnicas - Caracter�sticas Espec�ficas!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_location.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_location.value == '') {
          alert('Informe a Localiza��o Espec�fica, em Informa��es T�cnicas - Caracter�sticas Espec�ficas!');
          return false;
        }
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_requeriments.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_requeriments.value == '') {
          alert('Informe os Requerimentos espec�ficos, em Informa��es T�cnicas - Caracter�sticas Espec�ficas!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_instalation_remarks.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_instalation_remarks.value == '') {
          alert('Informe as Observa��es para Instala��o, em Informa��es T�cnicas - Caracter�sticas Espec�ficas!');
          return false;
        }
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_other_plataform_requeriments.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_other_plataform_requeriments.value == '') {
          alert('Informe os Requerimentos para outras Plataformas, em Informa��es T�cnicas - Caracter�sticas Espec�ficas!');
          return false;
        }
      }
    }

    if (f.eh_obrigatorio_technical_service.value == '1') {
      if (f.eh_obrigatorio_service_name.value == '1') {
        if (f.ds_service_name.value == '') {
          alert('Informe o Nome, em Informa��es T�cnicas - Servi�o!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_type.value == '1') {
        if (f.cd_service_type.value == '0') {
          alert('Informe o Tipo, em Informa��es T�cnicas - Servi�o!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_provides.value == '1') {
        if (f.ds_service_provides.value == '') {
          alert('Informe quem Fornece, em Informa��es T�cnicas - Servi�o!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_essential.value == '1') {
        if (f.ds_service_essential.value == '') {
          alert('Informe dados Essenciais, em Informa��es T�cnicas - Servi�o!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_protocol.value == '1') {
        if (f.ds_service_protocol.value == '') {
          alert('Informe o Protocolo, em Informa��es T�cnicas - Servi�o!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_service_ontology.value == '1') {
        if (f.ds_service_ontology.value == '') {
          alert('Informe a Ontologia, em Informa��es T�cnicas - Servi�o!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_language.value == '1') {
        if (f.cd_service_language.value == '0') {
          alert('Informe o Idioma, em Informa��es T�cnicas - Servi�o!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_details.value == '1') {
        if (f.ds_service_details.value == '') {
          alert('Informe Detalhes, em Informa��es T�cnicas - Servi�o!');
          return false;
        }
      }
    }
  }

  if (f.eh_obrigatorio_educational.value == '1') {
    if (f.eh_obrigatorio_educational_interactivity_type.value == '1') {
      if (f.ds_educational_interactivity_type.value == '') {
        alert('Informe o Tipo de Interatividade, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_learning_resource_type.value == '1') {
      if (f.ds_educational_learning_resource_type.value == '') {
        alert('Informe os Recursos do Tipo de Aprendizagem , em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_interactivity_level.value == '1') {
      if (f.ds_educational_interactivity_level.value == '') {
        alert('Informe o N�vel de Interatividade, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_sem_antic_density.value == '1') {
      if (f.ds_educational_sem_antic_density.value == '') {
        alert('Informe a Densidade Sem�ntica, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_intended_end_user_role.value == '1') {
      if (f.ds_educational_intended_end_user_role.value == '') {
        alert('Informe a Destina��o e Fun��o do Usu�rio, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_context.value == '1') {
      if (f.ds_educational_context.value == '') {
        alert('Informe o Contexto, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_typical_age_range.value == '1') {
      if (f.ds_educational_typical_age_range.value == '') {
        alert('Informe a Faixa Et�ria, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_difficulty.value == '1') {
      if (f.ds_educational_difficulty.value == '') {
        alert('Informe as Dificuldades, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_typical_learning_time.value == '1') {
      if (f.ds_educational_typical_learning_time.value == '') {
        alert('Informe o Tempo de Aprendizagem, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_description.value == '1') {
      if (f.ds_educational_description.value == '') {
        alert('Informe a Descri��o, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_language.value == '1') {
      if (f.cd_educational_language.value == '0') {
        alert('Informe o Idioma, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_learning_content_type.value == '1') {
      if (f.ds_educational_learning_content_type.value == '') {
        alert('Informe o Tipo de Conte�do, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_interaction.value == '1') {
      if (f.ds_educational_interaction.value == '') {
        alert('Informe a Intera��o, em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_didatic_strategy.value == '1') {
      if (f.ds_educational_didatic_strategy.value == '') {
        alert('Informe a Estrat�gia Did�tica, em dados Educacionais!');
        return false;
      }
    }
  }


  if (f.eh_obrigatorio_rights.value == '1') {
    if (f.eh_obrigatorio_rights_cost.value == '1') {
      if (f.ds_rights_cost.value == '') {
        alert('Informe o Custo, em Direitos Autorais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_rights_copyright_and_other_restrictions.value == '1') {
      if (f.ds_rights_copyright_and_other_restrictions.value == '') {
        alert('Informe os Direitos de autor e outras restri��es, em Direitos Autorais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_rights_description.value == '1') {
      if (f.ds_rights_description.value == '') {
        alert('Informe a Descri��o, em Direitos Autorais!');
        return false;
      }
    }
  }


  if (f.eh_obrigatorio_relation.value == '1') {
    if (f.eh_obrigatorio_relation_kind.value == '1') {
      if (f.ds_relation_kind.value == '') {
        alert('Informe o Tipo, em Rela��o!');
        return false;
      }
    }
    if (f.eh_obrigatorio_relation_resource.value == '1') {
      if (f.ds_relation_resource.value == '') {
        alert('Informe os Recursos, em Rela��o!');
        return false;
      }
    }
  }


  if (f.eh_obrigatorio_annotation.value == '1') {
    if (f.eh_obrigatorio_annotation_entity.value == '1') {
      if (f.ds_annotation_entity.value == '') {
        alert('Informe Entidade, em Anota��o!');
        return false;
      }
    }
    if (f.eh_obrigatorio_annotation_date.value == '1') {
      if (f.ds_annotation_date.value == '') {
        alert('Informe a Data, em Anota��o!');
        return false;
      }
    }
    if (f.eh_obrigatorio_annotation_description.value == '1') {
      if (f.ds_annotation_description.value == '') {
        alert('Informe a Descri��o, em Anota��o!');
        return false;
      }
    }
  }


  if (f.eh_obrigatorio_classification.value == '1') {
    if (f.eh_obrigatorio_classification_purpose.value == '1') {
      if (f.ds_classification_purpose.value == '') {
        alert('Informe o Prop�sito, em Classifica��o!');
        return false;
      }
    }
    if (f.eh_obrigatorio_classification_taxon_path.value == '1') {
      if (f.ds_classification_taxon_path.value == '') {
        alert('Informe o Caminho, em Classifica��o!');
        return false;
      }
    }
    if (f.eh_obrigatorio_classification_description.value == '1') {
      if (f.ds_classification_description.value == '') {
        alert('Informe a Descri��o, em Classifica��o!');
        return false;
      }
    }
    if (f.eh_obrigatorio_classification_keyword.value == '1') {
      if (f.ds_classification_keyword.value == '') {
        alert('Informe as Palavras chave, em Classifica��o!');
        return false;
      }
    }
  }


  if (f.eh_obrigatorio_acessibility.value == '1') {
    if (f.eh_obrigatorio_acessibility_has_visual.value == '1') {
      if (f.ds_acessibility_has_visual.value == '') {
        alert('Informe os Elementos Visuais, em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_has_audititory.value == '1') {
      if (f.ds_acessibility_has_audititory.value == '') {
        alert('Informe os Elementos Sonoros, em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_has_text.value == '1') {
      if (f.ds_acessibility_has_text.value == '') {
        alert('Informe os Elementos de Texto, em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_has_tactible.value == '1') {
      if (f.ds_acessibility_has_tactible.value == '') {
        alert('Informe os Elementos T�teis, em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_earl_statment.value == '1') {
      if (f.ds_acessibility_earl_statment.value == '') {
        alert('Informe o Padr�o EARL, em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_equivalent_resource.value == '1') {
      if (f.ds_acessibility_equivalent_resource.value == '') {
        alert('Informe os Recursos Equivalentes, em Acessibilidade!');
        return false;
      }
    }
  }


  if (f.eh_obrigatorio_segment_information_table.value == '1') {
    if (f.eh_obrigatorio_segment_information_table_segment_list.value == '1') {
      if (f.ds_segment_information_table_segment_list.value == '') {
        alert('Informe os Segmentos, em Segmentos!');
        return false;
      }
    }
    if (f.eh_obrigatorio_segment_information_table_segmente_group_list.value == '1') {
      if (f.ds_segment_information_table_segmente_group_list.value == '') {
        alert('Informe os Grupos, em Segmentos!');
        return false;
      }
    }
  }

  return true; 
}

-->    
</script>