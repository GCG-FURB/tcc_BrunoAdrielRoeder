<script>
<!--
function valida(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');
//alert('A');

  if (f.eh_obrigatorio_general.value == '1') {
    if (f.eh_obrigatorio_general_title.value == '1') {
      if (f.ds_general_title.value == '') {
        alert('Informe o(a) '+f.nm_general_title.value+', em Informações Gerais!');
        return false;
      }
    }

    if (f.eh_obrigatorio_general_identifier.value == '1') {
      if (f.ds_general_identifier.value == '') {
        alert('Informe o(a) '+f.nm_general_identifier.value+', em Informações Gerais!');
        return false;
      }
    }

    //validar imagem
    if (f.ds_arquivo_imagem.value != '') {
      var arquivo = f.ds_arquivo_imagem.value;
      var tamanho = arquivo.length;
      var achou = false;
      var extensao = '';
      if (!achou) {
        var letra = arquivo.charAt(tamanho);
        if (letra == '.') {
          achou = true;
        } else {
          extensao = letra+extensao;
        }
      }
      if (!achou) {
        var letra = arquivo.charAt(tamanho-1);
        if (letra == '.') {
          achou = true;
        } else {
          extensao = letra+extensao;
        }
      }
      if (!achou) {
        var letra = arquivo.charAt(tamanho-2);
        if (letra == '.') {
          achou = true;
        } else {
          extensao = letra+extensao;
        }
      }
      if (!achou) {
        var letra = arquivo.charAt(tamanho-3);
        if (letra == '.') {
          achou = true;
        } else {
          extensao = letra+extensao;
        }
      }
      if (!achou) {
        var letra = arquivo.charAt(tamanho-4);
        if (letra == '.') {
          achou = true;
        } else {
          extensao = letra+extensao;
        }
      }

      if (extensao == '') {
        alert('Formato do arquivo de imagem de identificação do Objeto de Aprendizagem não detectado pelo sistema. Repita a seleção do arquivo da imagem e salve novamente!');
        return false;
      } else {
        var formatos = '';
        var tipo_aceito = false;
        var qtd = f.qt_extensoes_imagem.value;
        for (i=1; i<=qtd; i++) {
          var campo = 'ds_extensao_'+i;
          var tipo = document.getElementById(campo).value;
          if (extensao.toUpperCase() == tipo.toUpperCase()) {
            tipo_aceito = true;
          }
          if (formatos == '') {
            formatos = tipo;
          } else {
            formatos = formatos+' - '+tipo;
          }
        }

        if (!tipo_aceito) {
          alert('Arquivo de(a) '+f.nm_arquivo_imagem.value+' de identificação do Objeto de Aprendizagem em formato inválido! Formatos permitidos [ '+formatos+' ]');
          return false;
        }

      }
    }

    if (f.eh_obrigatorio_general_language.value == '1') {
      if (f.cd_general_language.value == '0') {
        alert('Informe o(a) '+f.nm_general_language.value+', em Informações Gerais!');
        return false;
      }
    }

    if (f.eh_obrigatorio_general_description.value == '1') {
      if (f.ds_general_description.value == '') {
        alert('Informe o(a) '+f.nm_general_description.value+', em Informações Gerais!');
        return false;
      }
    }

    if (f.eh_obrigatorio_general_keyword.value == '1') {
      if (f.ds_general_keyword.value == '') {
        alert('Informe o(a) '+f.nm_general_keyword.value+', em Informações Gerais!');
        return false;
      }
    }

    if (f.eh_obrigatorio_general_nivel_educacional.value == '1') {
      var qt_niveis_educacionais = document.getElementById('qt_niveis_educacionais').value;
      var achou = false;
      for (i=0; i<qt_niveis_educacionais; i++) {
        var campo = 'campo_'+i;
        var nome = document.getElementById(campo).value;
        if (document.getElementById(nome).checked) {
          achou = true;
        }
      }
      if (!achou) {
        alert('Informe o(a) '+f.nm_general_nivel_educacional.value+', em Informações Gerais!');
        return false;
      }
    }

    if (f.eh_obrigatorio_general_coverage.value == '1') {
      var qt_area_conhecimento = document.getElementById('qt_area_conhecimento').value;
      var achou = false;
      for (i=0; i < qt_area_conhecimento; i++) {
        var campo = 'campo_area_'+i;
        var nome = document.getElementById(campo).value;
        if (document.getElementById(nome).checked) {
          achou = true;
        }
      }
      if (!achou) {
        alert('Informe o(a) '+f.nm_general_coverage.value+', em Informações Gerais!');
        return false;
      }
    }

    if (f.eh_obrigatorio_general_structure.value == '1') {
      if (f.ds_general_structure.value == '') {
        alert('Informe o(a) '+f.nm_general_structure.value+', em Informações Gerais!');
        return false;
      }
    }

    if (f.eh_obrigatorio_general_agregation_level.value == '1') {
      if (f.ds_general_agregation_level.value == '') {
        alert('Informe o(a) '+f.nm_general_agregation_level.value+', em Informações Gerais!');
        return false;
      }
    }
  }

//alert('B');

  if (f.eh_obrigatorio_lyfe_cycle.value == '1') {
    if (f.eh_obrigatorio_lyfe_cycle_version.value == '1') {
      if (f.ds_lyfe_cycle_version.value == '') {
        alert('Informe o(a) '+f.nm_lyfe_cycle_version.value+', em Ciclo de Vida!');
        return false;
      }
    }
/*    if (f.eh_obrigatorio_lyfe_cycle_status.value == '1') {
      if (f.cd_lyfe_cycle_status.value == '0') {
        alert('Informe o(a) '+f.nm_lyfe_cycle_status.value+', em Ciclo de Vida!');
        return false;
      }
    }*/
    if (f.eh_obrigatorio_lyfe_cycle_contribute.value == '1') {
      if (f.ds_lyfe_cycle_contribute.value == '') {
        alert('Informe o(a) '+f.nm_lyfe_cycle_contribute.value+', em Ciclo de Vida!');
        return false;
      }
    }
  }
  
  if (f.eh_obrigatorio_meta_metadata.value == '1') {
    if (f.eh_obrigatorio_meta_metadata_identifier.value == '1') {
      if (f.ds_meta_metadata_identifier.value == '') {
        alert('Informe o(a) '+f.nm_meta_metadata_identifier.value+', em Meta-Metadata!');
        return false;
      }
    }
    if (f.eh_obrigatorio_meta_metadata_contribute.value == '1') {
      if (f.ds_meta_metadata_contribute.value == '') {
        alert('Informe o(a) '+f.nm_meta_metadata_contribute.value+', em Meta-Metadata!');
        return false;
      }
    }
    if (f.eh_obrigatorio_meta_metadata_metadata_schema.value == '1') {
      if (f.ds_meta_metadata_metadata_schema.value == '') {
        alert('Informe o(a) '+f.nm_meta_metadata_metadata_schema.value+', em Meta-Metadata!');
        return false;
      }
    }
    if (f.eh_obrigatorio_meta_metadata_language.value == '1') {
      if (f.cd_meta_metadata_language.value == '0') {
        alert('Informe o(a) '+f.nm_meta_metadata_language.value+', em Meta-Metadata!');
        return false;
      }
    }
  }

//alert('C');

  if (f.eh_obrigatorio_technical.value == '1') {
    if (f.cd_technical_format.value == '0') {
      alert('Informe o(a) '+f.nm_technical_format.value+', em Informações Técnicas!');
      return false;
    } else {
      var cd_formato = f.cd_technical_format.value;
    }
    var cd_formato_primario = f.cd_formato_primario.value;
    //quando arquivo, validar tamanho e validar formato
    if (cd_formato_primario == '1') {
      if (f.ds_technical_location.value == '') {
        alert('Informe a Localização do Arquivo de Áudio, em Informações Técnicas!');
        return false;
      }
    } else {
      if (cd_formato_primario == '2') {
        if (f.ds_technical_location.value == '') {
          alert('Informe a Localização do Arquivo de Vídeo, em Informações Técnicas!');
          return false;
        }
      } else {
        if (cd_formato_primario == '3') {
          if ((f.ds_technical_location.value == '') && (f.ds_technical_location_antigo.value == '')) {
            alert('Selecione o Arquivo de Texto, em Informações Técnicas!');
            return false;
          } else {
            if (f.ds_technical_location.value != '') {
              var ds_limite_tamanho_arquivos = f.ds_limite_tamanho_arquivos.value;
              var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",",");
              ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
              var ds_tamanho_arquivo = eval(f.ds_technical_location.files[0].size);
              var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
              if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                alert('O Arquivo de Texto não pode exceder o limite de '+tamanho_escrita+'MB!');
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
                alert('Arquivo de Texto em formato inválido! Formatos permitidos [ '+formatos+' ]');
                return false;
              }
            }
          }
        } else {
          if (cd_formato_primario == '4') {
            if ((f.ds_technical_location.value == '') && (f.ds_technical_location_antigo.value == '')) {
              alert('Selecione o Arquivo de Imagem, em Informações Técnicas!');
              return false;
            } else {
              if (f.ds_technical_location.value != '') {
                var ds_limite_tamanho_arquivos = f.ds_limite_tamanho_arquivos.value;
                var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",",");
                ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
                var ds_tamanho_arquivo = eval(f.ds_technical_location.files[0].size);
                var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
                if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                  alert('O Arquivo de Imagem não pode exceder o limite de '+tamanho_escrita+'MB!');
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
                  alert('Arquivo de Imagem em formato inválido! Formatos permitidos [ '+formatos+' ]');
                  return false;
                }
              }
            }
          } else {
            if (cd_formato_primario == '5') {
              if (f.ds_technical_location.value == '') {
                alert('Informe a Localização do Arquivo do Aplicativo, em Informações Técnicas!');
                return false;
              }
            } else {
              if (cd_formato_primario == '6') {
                if (f.ds_technical_location.value == '') {
                  alert('Informe o Link Externo, em Informações Técnicas!');
                  return false;
                }
              } else {
                if (cd_formato_primario == '7') {
                  if ((f.ds_technical_location.value == '') && (f.ds_technical_location_antigo.value == "")) {
                    alert('Selecione o Arquivo da Apresentação, em Informações Técnicas!');
                    return false;
                  } else {
                    if (f.ds_technical_location.value != '') {
                      var ds_limite_tamanho_arquivos = f.ds_limite_tamanho_arquivos.value;
                      var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",",");
                      ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
                      var ds_tamanho_arquivo = eval(f.ds_technical_location.files[0].size);
                      var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
                      if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                        alert('O Arquivo da Apresentação não pode exceder o limite de '+tamanho_escrita+'MB!');
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
                        alert('Arquivo da Apresentação em formato inválido! Formatos permitidos [ '+formatos+' ]');
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

    if (f.eh_obrigatorio_technical_size.value == '1') {
      if (f.ds_technical_size.value == '') {
        alert('Informe o Tamanho do(a) '+f.nm_technical_size.value+', em Informações Técnicas!');
        return false;
      }
    }

    if (f.eh_obrigatorio_technical_requirement.value == '1') {
      if (f.ds_technical_requirement.value == '') {
        alert('Informe o(a) '+f.nm_technical_requirement.value+', em Informações Técnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_composite.value == '1') {
      if (f.ds_technical_composite.value == '') {
        alert('Informe o(a) '+f.nm_technical_composite.value+', em Informações Técnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_installation_remarks.value == '1') {
      if (f.ds_technical_installation_remarks.value == '') {
        alert('Informe o(a) '+f.nm_technical_installation_remarks.value+', em Informações Técnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_other_plataforms_requirements.value == '1') {
      if (f.ds_technical_other_plataforms_requirements.value == '') {
        alert('Informe o(a) '+f.nm_technical_other_plataforms_requirements.value+' em Informações Técnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_duration.value == '1') {
      if (f.ds_technical_duration.value == '') {
        alert('Informe o(a) '+f.nm_technical_duration.value+', em Informações Técnicas!');
        return false;
      }
    }
    if (f.eh_obrigatorio_technical_supported_plataform.value == '1') {
      if (f.ds_technical_supported_plataform.value == '') {
        alert('Informe o(a) '+f.nm_technical_supported_plataform.value+', em Informações Técnicas!');
        return false;
      }
    }

    if (f.eh_obrigatorio_technical_plataform_specific_features.value == '1') {
      if (f.eh_obrigatorio_technical_plataform_specific_features_plataform_type.value == '1') {
        if (f.ds_technical_plataform_specific_features_plataform_type.value == '') {
          alert('Informe o(a) '+f.nm_technical_plataform_specific_features_plataform_type.value+', em Informações Técnicas - Características Específicas!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_format.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_format.value == '') {
          alert('Informe o(a) '+f.nm_technical_plataform_specific_features_specific_format.value+', em Informações Técnicas - Características Específicas!');
          return false;
        }
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_size.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_size.value == '') {
          alert('Informe o(a) '+f.nm_technical_plataform_specific_features_specific_size.value+', em Informações Técnicas - Características Específicas!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_location.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_location.value == '') {
          alert('Informe o(a) '+f.nm_technical_plataform_specific_features_specific_location.value+', em Informações Técnicas - Características Específicas!');
          return false;
        }
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_requeriments.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_requeriments.value == '') {
          alert('Informe o(a) '+f.nm_technical_plataform_specific_features_specific_requeriments.value+', em Informações Técnicas - Características Específicas!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_instalation_remarks.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_instalation_remarks.value == '') {
          alert('Informe o(a) '+f.nm_technical_plataform_specific_features_specific_instalation_remarks.value+', em Informações Técnicas - Características Específicas!');
          return false;
        }
      }
      if (f.eh_obrigatorio_technical_plataform_specific_features_specific_other_plataform_requeriments.value == '1') {
        if (f.ds_technical_plataform_specific_features_specific_other_plataform_requeriments.value == '') {
          alert('Informe o(a) '+f.nm_technical_plataform_specific_features_specific_other_plataform_requeriments.value+', em Informações Técnicas - Características Específicas!');
          return false;
        }
      }
    }

    if (f.eh_obrigatorio_technical_service.value == '1') {
      if (f.eh_obrigatorio_service_name.value == '1') {
        if (f.ds_service_name.value == '') {
          alert('Informe o(a) '+f.nm_service_name.value+', em Informações Técnicas - Serviço!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_type.value == '1') {
        if (f.cd_service_type.value == '0') {
          alert('Informe o(a) '+f.nm_service_type.value+', em Informações Técnicas - Serviço!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_provides.value == '1') {
        if (f.ds_service_provides.value == '') {
          alert('Informe o(a) '+f.nm_service_provides.value+', em Informações Técnicas - Serviço!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_essential.value == '1') {
        if (f.ds_service_essential.value == '') {
          alert('Informe o(a) '+f.nm_service_essential.value+', em Informações Técnicas - Serviço!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_protocol.value == '1') {
        if (f.ds_service_protocol.value == '') {
          alert('Informe o(a) '+f.nm_service_protocol.value+', em Informações Técnicas - Serviço!');
          return false;
        }   
      }
      if (f.eh_obrigatorio_service_ontology.value == '1') {
        if (f.ds_service_ontology.value == '') {
          alert('Informe o(a) '+f.nm_service_ontology.value+', em Informações Técnicas - Serviço!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_language.value == '1') {
        if (f.cd_service_language.value == '0') {
          alert('Informe o(a) '+f.nm_service_language.value+', em Informações Técnicas - Serviço!');
          return false;
        }
      }
      if (f.eh_obrigatorio_service_details.value == '1') {
        if (f.ds_service_details.value == '') {
          alert('Informe o(a) '+f.nm_service_details.value+', em Informações Técnicas - Serviço!');
          return false;
        }
      }
    }
  }

//alert('D');

  if (f.eh_obrigatorio_educational.value == '1') {
    if (f.eh_obrigatorio_educational_interactivity_type.value == '1') {
      if (f.ds_educational_interactivity_type.value == '') {
        alert('Informe o(a) '+f.nm_educational_interactivity_type.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_learning_resource_type.value == '1') {
      if (f.ds_educational_learning_resource_type.value == '') {
        alert('Informe o(a) '+f.nm_educational_learning_resource_type.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_interactivity_level.value == '1') {
      if (f.ds_educational_interactivity_level.value == '') {
        alert('Informe o(a) '+f.nm_educational_interactivity_level.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_sem_antic_density.value == '1') {
      if (f.ds_educational_sem_antic_density.value == '') {
        alert('Informe o(a) '+f.nm_educational_sem_antic_density.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_intended_end_user_role.value == '1') {
      if (f.ds_educational_intended_end_user_role.value == '') {
        alert('Informe o(a) '+f.nm_educational_intended_end_user_role.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_context.value == '1') {
      if (f.ds_educational_context.value == '') {
        alert('Informe o(a) '+f.nm_educational_context.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_typical_age_range.value == '1') {
      if (f.ds_educational_typical_age_range.value == '') {
        alert('Informe o(a) '+f.nm_educational_typical_age_range.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_difficulty.value == '1') {
      if (f.ds_educational_difficulty.value == '') {
        alert('Informe o(a) '+f.nm_educational_difficulty.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_typical_learning_time.value == '1') {
      if (f.ds_educational_typical_learning_time.value == '') {
        alert('Informe o(a) '+f.nm_educational_typical_learning_time.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_description.value == '1') {
      if (f.ds_educational_description.value == '') {
        alert('Informe o(a) '+f.nm_educational_description.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_language.value == '1') {
      if (f.cd_educational_language.value == '0') {
        alert('Informe o(a) '+f.nm_educational_language.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_learning_content_type.value == '1') {
      if (f.ds_educational_learning_content_type.value == '') {
        alert('Informe o(a) '+f.nm_educational_learning_content_type.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_interaction.value == '1') {
      if (f.ds_educational_interaction.value == '') {
        alert('Informe o(a) '+f.nm_educational_interaction.value+', em dados Educacionais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_educational_didatic_strategy.value == '1') {
      if (f.ds_educational_didatic_strategy.value == '') {
        alert('Informe o(a) '+f.nm_educational_didatic_strategy.value+', em dados Educacionais!');
        return false;
      }
    }
  }

//alert('E');

  if (f.eh_obrigatorio_rights.value == '1') {
    if (f.eh_obrigatorio_rights_cost.value == '1') {
      if (f.ds_rights_cost.value == '') {
        alert('Informe o(a) '+f.nm_rights_cost.value+', em Direitos Autorais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_rights_copyright_and_other_restrictions.value == '1') {
      if (f.ds_rights_copyright_and_other_restrictions.value == '') {
        alert('Informe o(a) '+f.nm_rights_copyright_and_other_restrictions.value+', em Direitos Autorais!');
        return false;
      }
    }
    if (f.eh_obrigatorio_rights_description.value == '1') {
      if (f.ds_rights_description.value == '') {
        alert('Informe o(a) '+f.nm_rights_description.value+', em Direitos Autorais!');
        return false;
      }
    }
  }

//alert('F');

  if (f.eh_obrigatorio_relation.value == '1') {
    if (f.eh_obrigatorio_relation_kind.value == '1') {
      if (f.ds_relation_kind.value == '') {
        alert('Informe o(a) '+f.nm_relation_kind.value+', em Relação!');
        return false;
      }
    }
    if (f.eh_obrigatorio_relation_resource.value == '1') {
      if (f.ds_relation_resource.value == '') {
        alert('Informe o(a) '+f.nm_relation_resource.value+', em Relação!');
        return false;
      }
    }
  }

//alert('G');

  if (f.eh_obrigatorio_annotation.value == '1') {
    if (f.eh_obrigatorio_annotation_entity.value == '1') {
      if (f.ds_annotation_entity.value == '') {
        alert('Informe o(a) '+f.nm_annotation_entity.value+', em Anotação!');
        return false;
      }
    }
    if (f.eh_obrigatorio_annotation_date.value == '1') {
      if (f.ds_annotation_date.value == '') {
        alert('Informe o(a) '+f.nm_annotation_date.value+', em Anotação!');
        return false;
      }
    }
    if (f.eh_obrigatorio_annotation_description.value == '1') {
      if (f.ds_annotation_description.value == '') {
        alert('Informe o(a) '+f.nm_annotation_description.value+', em Anotação!');
        return false;
      }
    }
  }

//alert('H');

  if (f.eh_obrigatorio_classification.value == '1') {
    if (f.eh_obrigatorio_classification_purpose.value == '1') {
      if (f.ds_classification_purpose.value == '') {
        alert('Informe o(a) '+f.nm_classification_purpose.value+', em Classificação!');
        return false;
      }
    }
    if (f.eh_obrigatorio_classification_taxon_path.value == '1') {
      if (f.ds_classification_taxon_path.value == '') {
        alert('Informe o(a) '+f.nm_classification_taxon_path.value+', em Classificação!');
        return false;
      }
    }
    if (f.eh_obrigatorio_classification_description.value == '1') {
      if (f.ds_classification_description.value == '') {
        alert('Informe o(a) '+f.nm_classification_description.value+', em Classificação!');
        return false;
      }
    }
    if (f.eh_obrigatorio_classification_keyword.value == '1') {
      if (f.ds_classification_keyword.value == '') {
        alert('Informe o(a) '+f.nm_classification_keyword.value+', em Classificação!');
        return false;
      }
    }
  }

//alert('I');

  if (f.eh_obrigatorio_acessibility.value == '1') {
    if (f.eh_obrigatorio_acessibility_has_visual.value == '1') {
      if (f.ds_acessibility_has_visual.value == '') {
        alert('Informe o(a) '+f.nm_acessibility_has_visual.value+', em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_has_audititory.value == '1') {
      if (f.ds_acessibility_has_audititory.value == '') {
        alert('Informe o(a) '+f.nm_acessibility_has_audititory.value+', em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_has_text.value == '1') {
      if (f.ds_acessibility_has_text.value == '') {
        alert('Informe o(a) '+f.nm_acessibility_has_text.value+', em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_has_tactible.value == '1') {
      if (f.ds_acessibility_has_tactible.value == '') {
        alert('Informe o(a) '+f.nm_acessibility_has_tactible.value+', em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_earl_statment.value == '1') {
      if (f.ds_acessibility_earl_statment.value == '') {
        alert('Informe o(a) '+f.nm_acessibility_earl_statment.value+', em Acessibilidade!');
        return false;
      }
    }
    if (f.eh_obrigatorio_acessibility_equivalent_resource.value == '1') {
      if (f.ds_acessibility_equivalent_resource.value == '') {
        alert('Informe o(a) '+f.nm_acessibility_equivalent_resource.value+', em Acessibilidade!');
        return false;
      }
    }
  }

//alert('J');

  if (f.eh_obrigatorio_segment_information_table.value == '1') {
    if (f.eh_obrigatorio_segment_information_table_segment_list.value == '1') {
      if (f.ds_segment_information_table_segment_list.value == '') {
        alert('Informe o(a) '+f.nm_segment_information_table_segment_list.value+', em Segmentos!');
        return false;
      }
    }
    if (f.eh_obrigatorio_segment_information_table_segmente_group_list.value == '1') {
      if (f.ds_segment_information_table_segmente_group_list.value == '') {
        alert('Informe o(a) '+f.nm_segment_information_table_segmente_group_list.value+', em Segmentos!');
        return false;
      }
    }
  }

//alert('K');

  document.getElementById('cadastro').submit();
}

function validarImagemIdentificacaoOA() {
  //validar imagem
  if (document.getElementById('ds_arquivo_imagem').value != '') {
    var arquivo = document.getElementById('ds_arquivo_imagem').value;
    var tamanho = arquivo.length;
    var achou = false;
    var extensao = '';
    if (!achou) {
      var letra = arquivo.charAt(tamanho);
      if (letra == '.') {
        achou = true;
      } else {
        extensao = letra+extensao;
      }
    }
    if (!achou) {
      var letra = arquivo.charAt(tamanho-1);
      if (letra == '.') {
        achou = true;
      } else {
        extensao = letra+extensao;
      }
    }
    if (!achou) {
      var letra = arquivo.charAt(tamanho-2);
      if (letra == '.') {
        achou = true;
      } else {
        extensao = letra+extensao;
      }
    }
    if (!achou) {
      var letra = arquivo.charAt(tamanho-3);
      if (letra == '.') {
        achou = true;
      } else {
        extensao = letra+extensao;
      }
    }
    if (!achou) {
      var letra = arquivo.charAt(tamanho-4);
      if (letra == '.') {
        achou = true;
      } else {
        extensao = letra+extensao;
      }
    }
    if (extensao != '') {
      var formatos = '';
      var tipo_aceito = false;
      var qtd = document.getElementById('qt_extensoes_imagem').value;
      for (i=1; i<=qtd; i++) {
        var campo = 'ds_extensao_'+i;
        var tipo = document.getElementById(campo).value;
        if (extensao.toUpperCase() == tipo.toUpperCase()) {
          tipo_aceito = true;
        }
        if (formatos == '') {
          formatos = tipo;
        } else {
          formatos = formatos+' - '+tipo;
        }
      }
      if (!tipo_aceito) {
        alert('Arquivo de imagem de identificação do Objeto de Aprendizagem em formato inválido! Formatos permitidos [ '+formatos+' ]');
        //setTimeout("document.getElementById('ds_arquivo_imagem').focus()",250);
      }
    }
  }
}

function validarOA() {
  if (document.getElementById('eh_validar').value == '0') {
    return false;
  } else {
    document.getElementById('eh_validar').value = '0';
  }
  var cd_formato = document.getElementById('cd_technical_format').value;
  if (cd_formato == '1') {
    if (document.getElementById('ds_technical_location').value == '') {
      alert('Informe a Localização do Arquivo de Áudio, em Informações Técnicas!');
      //setTimeout("document.getElementById('ds_technical_location').focus()",250);
    }
  } else {
    if (cd_formato == '2') {
      if (document.getElementById('ds_technical_location').value == '') {
        alert('Informe a Localização do Arquivo de Vídeo, em Informações Técnicas!');
        //setTimeout("document.getElementById('ds_technical_location').focus()",250);
      }
    } else {
      if (cd_formato == '3') {
        if ((document.getElementById('ds_technical_location').value == '') && (document.getElementById('ds_technical_location_antigo').value == '')) {
          alert('Selecione o Arquivo de Texto, em Informações Técnicas!');
          //setTimeout("document.getElementById('ds_technical_location').focus()",250);
        } else {
          if (document.getElementById('ds_technical_location').value != '') {
            var ds_limite_tamanho_arquivos = document.getElementById('ds_limite_tamanho_arquivos').value;
            var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",",");
            ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
            var ds_tamanho_arquivo = eval(document.getElementById('ds_technical_location').files[0].size);
            var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
            if (ds_tamanho_arquivo > ds_tamanho_convertido) {
              alert('O Arquivo de Texto não pode exceder o limite de '+tamanho_escrita+'MB!');
              //setTimeout("document.getElementById('ds_technical_location').focus()",250);
            }
            var ds_location = document.getElementById('ds_technical_location').value;
            var tamanho = document.getElementById('ds_technical_location').value.length - 1;
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
            var quantidade = document.getElementById('quantidade').value;
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
              alert('Arquivo de Texto em formato inválido! Formatos permitidos [ '+formatos+' ]');
              //setTimeout("document.getElementById('ds_technical_location').focus()",250);
            }
          }
        }
      } else {
        if (cd_formato == '4') {
          if ((document.getElementById('ds_technical_location').value == '') && (document.getElementById('ds_technical_location_antigo').value == '')) {
            alert('Selecione o Arquivo de Imagem, em Informações Técnicas!');
            //setTimeout("document.getElementById('ds_technical_location').focus()",250);
          } else {
            if (document.getElementById('ds_technical_location').value != '') {
              var ds_limite_tamanho_arquivos = document.getElementById('ds_limite_tamanho_arquivos').value;
              var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",",");
              ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
              var ds_tamanho_arquivo = eval(document.getElementById('ds_technical_location').files[0].size);
              var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
              if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                alert('O Arquivo de Imagem não pode exceder o limite de '+tamanho_escrita+'MB!');
                //setTimeout("document.getElementById('ds_technical_location').focus()",250);
              }
              var ds_location = document.getElementById('ds_technical_location').value;
              var tamanho = document.getElementById('ds_technical_location').value.length - 1;
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
              var quantidade = document.getElementById('quantidade').value;
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
                alert('Arquivo de Imagem em formato inválido! Formatos permitidos [ '+formatos+' ]');
                //setTimeout("document.getElementById('ds_technical_location').focus()",250);
              }
            }
          }
        } else {
          if (cd_formato == '5') {
            if (document.getElementById('ds_technical_location').value == '') {
              alert('Informe a Localização do Arquivo do Aplicativo, em Informações Técnicas!');
              //setTimeout("document.getElementById('ds_technical_location').focus()",250);
            }
          } else {
            if (cd_formato == '6') {
              if (document.getElementById('ds_technical_location').value == '') {
                alert('Informe o Link Externo, em Informações Técnicas!');
                //setTimeout("document.getElementById('ds_technical_location').focus()",250);
              }
            } else {
              if (cd_formato == '7') {
                if ((document.getElementById('ds_technical_location').value == '') && (document.getElementById('ds_technical_location_antigo').value == "")) {
                  alert('Selecione o Arquivo da Apresentação, em Informações Técnicas!');
                  //setTimeout("document.getElementById('ds_technical_location').focus()",250);
                } else {
                  if (document.getElementById('ds_technical_location').value != '') {
                    var ds_limite_tamanho_arquivos = document.getElementById('ds_limite_tamanho_arquivos').value;
                    var tamanho_escrita = ds_limite_tamanho_arquivos.replace(".",",");
                    ds_limite_tamanho_arquivos = eval(ds_limite_tamanho_arquivos);
                    var ds_tamanho_arquivo = eval(document.getElementById('ds_technical_location').files[0].size);
                    var ds_tamanho_convertido = ds_limite_tamanho_arquivos * 1048576;
                    if (ds_tamanho_arquivo > ds_tamanho_convertido) {
                      alert('O Arquivo da Apresentação não pode exceder o limite de '+tamanho_escrita+'MB!');
                      //setTimeout("document.getElementById('ds_technical_location').focus()",250);
                    }
                    var ds_location = document.getElementById('ds_technical_location').value;
                    var tamanho = document.getElementById('ds_technical_location').value.length - 1;
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
                    var quantidade = document.getElementById('quantidade').value;
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
                      alert('Arquivo da Apresentação em formato inválido! Formatos permitidos [ '+formatos+' ]');
                      //setTimeout("document.getElementById('ds_technical_location').focus()",250);
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
}

function marcarValidacao() {
  document.getElementById('eh_validar').value = '1';
}

-->    
</script>