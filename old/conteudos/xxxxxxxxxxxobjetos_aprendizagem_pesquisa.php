<?php
  class ObjetoAprendizagemPesquisa {
    
    public function __construct () {
    }
    
    public function listarOpcoesPesquisaAvancada($secao, $subsecao, $item, $ativas, $ordem, $eh_permitir_simples) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if (isset($_SESSION['life_c_termo_1']))         {     $termo_1 = $_SESSION['life_c_termo_1'];                  } else {        $_SESSION['life_c_termo_1'] = '';             $termo_1 = '';            }
      if (isset($_SESSION['life_c_tabela_1']))        {     $tabela_1 = $_SESSION['life_c_tabela_1'];                } else {        $_SESSION['life_c_tabela_1'] = '';            $tabela_1 = '';           }
      if (isset($_SESSION['life_c_campo_1']))         {     $campo_1 = $_SESSION['life_c_campo_1'];                  } else {        $_SESSION['life_c_campo_1'] = '';             $campo_1 = '';            }
      if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_pesquisa_oa_avancada.js";
      $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=pesq_ava";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."\" onSubmit=\"return valida(this);\">\n";

      echo "    <table class=\"tabConteudo\">\n";
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" colspan=\"4\">\n";
      echo "          Formulário de Pesquisa Avançada:\n";
      echo "        </td>\n";
      echo "      </tr>\n";

      $this->retornaCamposPesquisaAvancada(1, $tabela_1, $campo_1, $termo_1, $eh_proprietario);

      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\">&nbsp;</td>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"2\">\n";
      echo "  		    <input type=\"submit\" class=\"celConteudoBotao\" value=\"Pesquisar\">\n";
      echo "        </td>\n";
      echo "        <td class=\"celConteudoCentralizado\">\n";
      if ($eh_permitir_simples) {
        echo "  		    <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=pesquisar\" class=\"fontLinkReduzido\">[... Pesquisa simples ...]</a>\n";
      } else {
        echo "          &nbsp;\n";
      }
      echo "        </td>\n";
      echo "      </tr>\n";
      echo "    </table>\n";      

      echo "  </form>\n";

    }  


    public function listarOpcoesPesquisaSimples($secao, $subsecao, $item, $ativas, $ordem, $eh_permitir_avancada) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if (isset($_SESSION['life_c_termo_1']))         {     $termo_1 = $_SESSION['life_c_termo_1'];                  } else {        $_SESSION['life_c_termo_1'] = '';             $termo_1 = '';            }
      if (isset($_SESSION['life_c_tabela_1']))        {     $tabela_1 = $_SESSION['life_c_tabela_1'];                } else {        $_SESSION['life_c_tabela_1'] = '';            $tabela_1 = '';           }
      if (isset($_SESSION['life_c_campo_1']))         {     $campo_1 = $_SESSION['life_c_campo_1'];                  } else {        $_SESSION['life_c_campo_1'] = '';             $campo_1 = '';            }
      if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_pesquisa_oa_simples.js";
      $link= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=pesq";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."\" onSubmit=\"return valida(this);\">\n";

      echo "    <table class=\"tabConteudo\">\n";
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" colspan=\"4\">\n";
      echo "          Formulário de Pesquisa Simples:\n";
      echo "        </td>\n";
      echo "      </tr>\n";

      $this->retornaCamposPesquisaSimples(1, $tabela_1, $campo_1, $termo_1, $eh_proprietario);

      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\">&nbsp;</td>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"2\">\n";
      echo "  		    <input type=\"submit\" class=\"celConteudoBotao\" value=\"Pesquisar\">\n";
      echo "        </td>\n";
      echo "        <td class=\"celConteudoCentralizado\">\n";
      if ($eh_permitir_avancada) {
        echo "  		    <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=pesquisa_avancada\" class=\"fontLinkReduzido\">[... Pesquisa Avançada ...]</a>\n";
      } else {
        echo "          &nbsp;\n";
      }
      echo "        </td>\n";
      echo "      </tr>\n";
      echo "    </table>\n";      

      echo "  </form>\n";

    }  
    
    public function retornaCamposPesquisaAvancada($id, $tabela, $campo, $termo, $eh_proprietario) {
      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"4\">\n";
      echo "          <input type=\"text\" maxlength=\"50\" name=\"termo_".$id."\" id=\"termo_".$id."\" value=\"".$termo."\" style=\"width:840px;\" alt=\"Campo para informação de Termo de pesquisa\" title=\"Campo para informação de Termo de pesquisa\" class=\"fontConteudoCampoTextHint\" placeholder=\"Termo de pesquisa\" tabindex=\"1\"/>\n";
      echo "          <a href=\"#\" class=\"dcontexto\">\n";
      echo "            <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
      echo "            <span class=\"fontdDetalhar\">\n";
      echo "              Após informar o termo de pesquisa, marque a seção na qual deseja que seja realizada a busca\n";
      echo "            </span>\n";
      echo "          </a>\n";      
      echo "        </td>\n";
      echo "      </tr>\n";

      echo "      <tr class=\"linhaOf\">\n";
      echo "        <td class=\"celConteudo\" colspan=\"4\">\n";
      $this->retornaCelulaPropriedade($eh_proprietario);
      echo "        </td>\n";
      echo "      </tr>\n";
      
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'objetos_aprendizagem', 'Objetos de Aprendizagem');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'technical', 'Informações Técnicas');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'service', 'Informações Técnicas - Serviço');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'educational', 'Educacional');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_objetos_aprendizagem'.$id, $campo, 'nm_objeto_aprendizagem', 'Nome');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'cd_format', 'Formato');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'ds_name', 'Nome');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_interactivity_type', 'Tipo de Interatividade');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_size', 'Tamanho');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'cd_type', 'Tipo');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_learning_resource_type', 'Recursos do Tipo de Aprendizagem ');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'general', 'Dados Gerais');
      echo "        </td>\n";
/*      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_location', 'Localização');
      echo "        </td>\n";*/
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'ds_provides', 'Fornece');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_interactivity_level', 'Nível de Interatividade');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'ds_identifier', 'Identificador');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_requirement', 'Requerimentos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'ds_essential', 'Essencial');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_sem_antic_density', 'Densidade Semântica');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'ds_title', 'Título');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_composite', 'Composição');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'ds_protocol', 'Protocolo');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_intended_end_user_role', 'Destinação e Função do Usuário');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'cd_language', 'Idioma');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_installation_remarks', 'Observações para a instalação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'ds_ontology', 'Ontologia');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_context', 'Contexto');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'ds_description', 'Descrição');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_other_plataforms_requirements', 'Outros Requerimentos da plataforma');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'cd_language', 'Idioma');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_typical_age_range', 'Faixa Etária');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'ds_keyword', 'Palavra chave');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_duration', 'Duração');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'ds_details', 'Detalhes');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_difficulty', 'Dificuldade');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'cd_coverage', 'Área de Conhecimento');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'ds_supported_plataform', 'Plataformas Suportadas');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_service'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_typical_learning_time', 'Tempo de Aprendizagem');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'ds_structure', 'Estrutura');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_technical'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_description', 'Descrição');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'ds_agregation_level', 'Nível de agregação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'rights', 'Direitos Autorais');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'cd_language', 'Idioma');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_general'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'plataform_specific_features', 'Informações Técnicas - Características Específicas');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_rights'.$id, $campo, 'ds_cost', 'Custo');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_learning_content_type', 'Tipo de Conteúdo');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'ds_plataform_type', 'Tipo de Plataforma');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_rights'.$id, $campo, 'ds_copyright_and_other_restrictions', 'Direitos de autor e outras restrições');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_interaction', 'Interação');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'lyfe_cycle', 'Ciclo de Vida');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'ds_specific_format', 'Formato específico');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_rights'.$id, $campo, 'ds_description', 'Descrição');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'ds_didatic_strategy', 'Estratégia Didática');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_lyfe_cycle'.$id, $campo, 'ds_version', 'Versão');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'ds_specific_size', 'Tamanho');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_rights'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_educational'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_lyfe_cycle'.$id, $campo, 'cd_status', 'Status');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'ds_specific_location', 'Localização Específica');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_lyfe_cycle'.$id, $campo, 'ds_contribute', 'Contribuição');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'ds_specific_requeriments', 'Requerimentos específicos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'annotation', 'Anotação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'acessibility', 'Acessibilidade');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_lyfe_cycle'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'ds_specific_instalation_remarks', 'Observações para Instalação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_annotation'.$id, $campo, 'ds_entity', 'Entidade');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_acessibility'.$id, $campo, 'ds_has_visual', 'Elementos Visuais');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'ds_specific_other_plataform_requeriments', 'Requerimentos para outras Plataformas');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_annotation'.$id, $campo, 'ds_date', 'Data');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_acessibility'.$id, $campo, 'ds_has_audititory', 'Elementos Sonoros');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'segment_information_table', 'Segmentos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_plataform_specific_features'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_annotation'.$id, $campo, 'ds_description', 'Descrição');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_acessibility'.$id, $campo, 'ds_has_text', 'Elementos de Texto');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_segment_information_table'.$id, $campo, 'ds_segment_list', 'Segmentos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_annotation'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_acessibility'.$id, $campo, 'ds_has_tactible', 'Elementos Táteis');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_segment_information_table'.$id, $campo, 'ds_segmente_group_list', 'Grupos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'meta_metadata', 'Meta-Metadata');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_acessibility'.$id, $campo, 'ds_earl_statment', 'Padrão EARL');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_segment_information_table'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_meta_metadata'.$id, $campo, 'ds_identifier', 'Identificador');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'classification', 'Classificação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_acessibility'.$id, $campo, 'ds_equivalent_resource', 'Recursos Equivalentes');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_meta_metadata'.$id, $campo, 'ds_contribute', 'Contribuição');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_classification'.$id, $campo, 'ds_purpose', 'Propósito');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_acessibility'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(false, '1', 'tabela_'.$id, $tabela, 'relation', 'Relação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_meta_metadata'.$id, $campo, 'ds_metadata_schema', 'Esquema de Metadados');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_classification'.$id, $campo, 'ds_taxon_path', 'Caminho');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_relation'.$id, $campo, 'ds_kind', 'Tipo');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_meta_metadata'.$id, $campo, 'cd_language', 'Idioma');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_classification'.$id, $campo, 'ds_description', 'Descrição');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '1', 'tabela_'.$id, $tabela, 'eh_todas_tabelas', 'Todas as Seções ');
      echo "        </td>\n";
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_relation'.$id, $campo, 'ds_resource', 'Recurso');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_meta_metadata'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_classification'.$id, $campo, 'ds_keyword', 'Palavras chave');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "      </tr>\n";


      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_relation'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaAvancada(true, '0', 'campo_classification'.$id, $campo, 'todos', 'Todos os Campos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudoPesquisa\">&nbsp;</td>\n";    
      echo "      </tr>\n";
    }

  
  
  
    public function retornaCamposPesquisaSimples($id, $tabela, $campo, $termo, $eh_proprietario) {
      echo "      <tr>\n";
      echo "        <td class=\"celConteudoCentralizado\" colspan=\"4\">\n";
      echo "          <input type=\"text\" maxlength=\"50\" name=\"termo_".$id."\" id=\"termo_".$id."\" value=\"".$termo."\" style=\"width:840px;\" alt=\"Campo para informação de Termo de pesquisa\" title=\"Campo para informação de Termo de pesquisa\" class=\"fontConteudoCampoTextHint\" placeholder=\"Termo de pesquisa\" tabindex=\"1\"/>\n";
      echo "          <a href=\"#\" class=\"dcontexto\">\n";
      echo "            <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
      echo "            <span class=\"fontdDetalhar\">\n";
      echo "              Após informar o termo de pesquisa, marque a seção na qual deseja que seja realizada a busca\n";
      echo "            </span>\n";
      echo "          </a>\n";      
      echo "        </td>\n";
      echo "      </tr>\n";

      echo "      <tr class=\"linhaOf\">\n";
      echo "        <td class=\"celConteudo\" colspan=\"4\">\n";
      $this->retornaCelulaPropriedade($eh_proprietario);
      echo "        </td>\n";
      echo "      </tr>\n";

      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'objetos_aprendizagem', 'Objeto de Aprendizagem');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'general', 'Dados Gerais');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'lyfe_cycle', 'Ciclo de Vida');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'meta_metadata', 'Meta-Metadata');
      echo "        </td>\n";
      echo "      </tr>\n";
      
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'technical', 'Informações Técnicas');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'technical_plataform_specific_features', 'Características Específicas');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'technical_service', 'Serviço');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'educational', 'Educacional');
      echo "        </td>\n";
      echo "      </tr>\n";
      
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'rights', 'Direitos Autorais');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'relation', 'Relação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'annotation', 'Anotação');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'classification', 'Classificação');
      echo "        </td>\n";
      echo "      </tr>\n";
      
      echo "      <tr>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'acessibility', 'Acessibilidade');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'segment_information_table', 'Segmentos');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      $this->retornaCelulaPesquisaSimples(true, '1', 'tabela_'.$id, $tabela, 'eh_todas_tabelas', 'Todas as Seções');
      echo "        </td>\n";
      echo "        <td class=\"celConteudo\" style=\"width:25%;\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      
      echo "      <input type=\"hidden\" name=\"campo_".$id."\" id=\"campo_".$id."\" value=\"todos\" />\n";
    }                                                                                                          
    
    public function retornaCelulaPropriedade($eh_proprietario) {
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      echo "          <input type=\"radio\" name=\"eh_proprietario\" id=\"eh_proprietario\" ";
      if ($eh_proprietario == '1') {          echo " checked=\"checked\" ";        } 
      echo " value=\"1\" class=\"fontConteudoPesquisa\" /> Pesquisar apenas entre os meus Objetos de Aprendizagem\n";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      echo "          <input type=\"radio\" name=\"eh_proprietario\" id=\"eh_proprietario\" ";
      if ($eh_proprietario == '0') {          echo " checked=\"checked\" ";        } 
      echo " value=\"0\" class=\"fontConteudoPesquisa\" /> Pesquisar entre todos os Objetos de Aprendizagem do Portal\n";
    }         

    public function retornaCelulaPesquisaSimples($eh_radio, $cabecalho, $campo, $valor_comparativo, $valor, $descricao) {
      if ($cabecalho == 1) {        $principal = 'Principal';      } else {        $principal = '';      }
      if ($eh_radio) {
        echo "          <input type=\"radio\" name=\"".$campo."\" id=\"".$campo."\" ";
        if ($valor_comparativo == $valor) {
          echo " checked=\"checked\" ";
        } 
        echo " value=\"".$valor."\" class=\"fontConteudoPesquisa\" /> \n";
      } else {
      
      }
      echo "        <b>".$descricao."</b>\n";
      echo "        <br />\n";    
    }         

    public function retornaCelulaPesquisaAvancada($eh_radio, $cabecalho, $campo, $valor_comparativo, $valor, $descricao) {
      if ($eh_radio) {
        echo "          <input type=\"radio\" name=\"".$campo."\" id=\"".$campo."\" ";
        if ($valor_comparativo == $valor) {
          echo " checked=\"checked\" ";
        } 
        echo " value=\"".$valor."\" class=\"fontConteudoPesquisa\" onClick=\"desmarcarCampos('$campo');\" /> \n";
      } else {
      
      }
      if ($cabecalho == 1) {        echo "        <b>".$descricao."</b>\n";      } else {        echo "        ".$descricao."\n";      }
      echo "        <br />\n";    
    }
    
    public function pesquisarSimples($secao, $subsecao, $item, $ativas, $ordem) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $termo_1 = $util->limparVariavel($_POST['termo_1']);                      $_SESSION['life_c_termo_1'] = $termo_1;      
      $campo_1 = addslashes($_POST['campo_1']);                                 $_SESSION['life_c_campo_1'] = $campo_1;
      $tabela_1 = addslashes($_POST['tabela_1']);                               $_SESSION['life_c_tabela_1'] = $tabela_1;
      $eh_proprietario = addslashes($_POST['eh_proprietario']);                 $_SESSION['life_c_eh_proprietario'] = $eh_proprietario;
    }

    public function pesquisarAvancada($secao, $subsecao, $item, $ativas, $ordem) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $termo_1 = $util->limparVariavel($_POST['termo_1']);                      $_SESSION['life_c_termo_1'] = $termo_1;      
      
      if (isset($_POST['tabela_1'])) {
        $tabela_1 = addslashes($_POST['tabela_1']);                             $_SESSION['life_c_tabela_1'] = $tabela_1;
        if ($tabela_1 == 'eh_todas_tabelas') {
          $_SESSION['life_c_campo_1'] = 'todos';
        }
      } else {
        if (isset($_POST['campo_general1'])) {
          $campo_1 = addslashes($_POST['campo_general1']);                      $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'general';          
        }
        if (isset($_POST['campo_lyfe_cycle1'])) {
          $campo_1 = addslashes($_POST['campo_lyfe_cycle1']);                   $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'lyfe_cycle';          
        }
        if (isset($_POST['campo_meta_metadata1'])) {
          $campo_1 = addslashes($_POST['campo_meta_metadata1']);                $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'meta_metadata';          
        }
        if (isset($_POST['campo_technical1'])) {
          $campo_1 = addslashes($_POST['campo_technical1']);                    $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'technical';          
        }
        if (isset($_POST['campo_plataform_specific_features1'])) {
          $campo_1 = addslashes($_POST['campo_plataform_specific_features1']);  $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'technical_plataform_specific_features';          
        }
        if (isset($_POST['campo_service1'])) {
          $campo_1 = addslashes($_POST['campo_service1']);                      $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'technical_service';          
        }
        if (isset($_POST['campo_educational1'])) {
          $campo_1 = addslashes($_POST['campo_educational1']);                  $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'educational';          
        }
        if (isset($_POST['campo_rights1'])) {
          $campo_1 = addslashes($_POST['campo_rights1']);                       $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'rights';          
        }
        if (isset($_POST['campo_relation1'])) {
          $campo_1 = addslashes($_POST['campo_relation1']);                     $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'relation';          
        }
        if (isset($_POST['campo_annotation1'])) {
          $campo_1 = addslashes($_POST['campo_annotation1']);                   $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'annotation';          
        }
        if (isset($_POST['campo_classification1'])) {
          $campo_1 = addslashes($_POST['campo_classification1']);               $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'classification';          
        }
        if (isset($_POST['campo_acessibility1'])) {
          $campo_1 = addslashes($_POST['campo_acessibility1']);                 $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'acessibility';          
        }
        if (isset($_POST[''])) {
          $campo_1 = addslashes($_POST['']);                                    $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = '';          
        }
        if (isset($_POST['campo_segment_information_table1'])) {
          $campo_1 = addslashes($_POST['campo_segment_information_table1']);    $_SESSION['life_c_campo_1'] = $campo_1;
          $_SESSION['life_c_tabela_1'] = 'segment_information_table';          
        }                
      }
      $eh_proprietario = addslashes($_POST['eh_proprietario']);                 $_SESSION['life_c_eh_proprietario'] = $eh_proprietario;
    }

    public function retornaDetalhesPesquisa() {
      if (isset($_SESSION['life_c_termo_1']))         {     $termo_1 = $_SESSION['life_c_termo_1'];                  } else {        $_SESSION['life_c_termo_1'] = '';             $termo_1 = '';            }
      if (isset($_SESSION['life_c_tabela_1']))        {     $tabela_1 = $_SESSION['life_c_tabela_1'];                } else {        $_SESSION['life_c_tabela_1'] = '';            $tabela_1 = '';           }
      if (isset($_SESSION['life_c_campo_1']))         {     $campo_1 = $_SESSION['life_c_campo_1'];                  } else {        $_SESSION['life_c_campo_1'] = '';             $campo_1 = '';            }
      if (isset($_SESSION['life_c_eh_proprietario'])) {     $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }

      $saida = "";

      if ($termo_1 != '') {
        $saida.= "Termo pesquisado: ".$termo_1."\n";
        switch ($tabela_1) {
          case "general":                       $saida.= "<br />Seção: Dados Gerais\n";                                                    break;
          case "lyfe_cycle":                    $saida.= "<br />Seção: Ciclo de Vida\n";                                                   break;
          case "meta-metadata":                 $saida.= "<br />Seção: Meta-Metadata\n";                                                   break;
          case "technical":                     $saida.= "<br />Seção: Informações Técnicas\n";                                            break;
          case "plataform_specific_features":   $saida.= "<br />Seção: Informações Técnicas - Características Específicas\n";              break;
          case "service":                       $saida.= "<br />Seção: Informações Técnicas - Serviço\n";                                  break;
          case "educational":                   $saida.= "<br />Seção: Educacional\n";                                                     break;
          case "rights":                        $saida.= "<br />Seção: Direitos Autorais\n";                                               break;
          case "relation":                      $saida.= "<br />Seção: Relação\n";                                                         break;
          case "annotation":                    $saida.= "<br />Seção: Anotação\n";                                                        break;
          case "classification":                $saida.= "<br />Seção: Classificação\n";                                                   break;
          case "acessibility":                  $saida.= "<br />Seção: Acessibilidade\n";                                                  break;
          case "segment_information_table":     $saida.= "<br />Seção: Segmentos\n";                                                       break;
        }
        
        if ($campo_1 != 'todos') {
          switch ($campo_1) {
            case "ds_identifier":                                       $saida.= "<br />Campo: Identificador\n";                                         break;
            case "ds_title":                                            $saida.= "<br />Campo: Título\n";                                                break;
            case "cd_language":                                         $saida.= "<br />Campo: Idioma\n";                                                break;
            case "ds_description":                                      $saida.= "<br />Campo: Descrição\n";                                             break;
            case "ds_keyword":                                          $saida.= "<br />Campo: Palavra chave\n";                                         break;
            case "cd_coverage":                                         $saida.= "<br />Campo: Área de Conhecimento\n";                                  break;
            case "ds_structure":                                        $saida.= "<br />Campo: Estrutura\n";                                             break;
            case "ds_agregation_level":                                 $saida.= "<br />Campo: Nível de agregação\n";                                    break;
            case "ds_version":                                          $saida.= "<br />Campo: Versão\n";                                                break;
            case "cd_status":                                           $saida.= "<br />Campo: Status\n";                                                break;
            case "ds_contribute":                                       $saida.= "<br />Campo: Contribuição\n";                                          break;
            case "ds_identifier":                                       $saida.= "<br />Campo: Identificador\n";                                         break;
            case "ds_contribute":                                       $saida.= "<br />Campo: Contribuição\n";                                          break;
            case "ds_metadata_schema":                                  $saida.= "<br />Campo: Esquema de Metadados\n";                                  break;
            case "cd_language":                                         $saida.= "<br />Campo: Idioma\n";                                                break;
            case "cd_format":                                           $saida.= "<br />Campo: Formato\n";                                               break;
            case "ds_size":                                             $saida.= "<br />Campo: Tamanho\n";                                               break;
//            case "ds_location":                                         $saida.= "<br />Campo: Localização\n";                                           break;
            case "ds_requirement":                                      $saida.= "<br />Campo: Requerimentos\n";                                         break;
            case "ds_composite":                                        $saida.= "<br />Campo: Composição\n";                                            break;
            case "ds_installation_remarks":                             $saida.= "<br />Campo: Observações para a instalação\n";                         break;
            case "ds_other_plataforms_requirements":                    $saida.= "<br />Campo: Outros Requerimentos da plataforma\n";                    break;
            case "ds_duration":                                         $saida.= "<br />Campo: Duração\n";                                               break;
            case "ds_supported_plataform":                              $saida.= "<br />Campo: Plataformas Suportadas\n";                                break;
            case "ds_plataform_type":                                   $saida.= "<br />Campo: Tipo de Plataforma\n";                                    break;
            case "ds_specific_format":                                  $saida.= "<br />Campo: Formato específico\n";                                    break;
            case "ds_specific_size":                                    $saida.= "<br />Campo: Tamanho\n";                                               break;
            case "ds_specific_location":                                $saida.= "<br />Campo: Localização Específica\n";                                break;
            case "ds_specific_requeriments":                            $saida.= "<br />Campo: Requerimentos específicos\n";                             break;
            case "ds_specific_instalation_remarks":                     $saida.= "<br />Campo: Observações para Instalação\n";                           break;
            case "ds_specific_other_plataform_requeriments":            $saida.= "<br />Campo: Requerimentos para outras Plataformas\n";                 break;
            case "ds_name":                                             $saida.= "<br />Campo: Nome\n";                                                  break;
            case "cd_type":                                             $saida.= "<br />Campo: Tipo\n";                                                  break;
            case "ds_provides":                                         $saida.= "<br />Campo: Fornece\n";                                               break;
            case "ds_essential":                                        $saida.= "<br />Campo: Essencial\n";                                             break;
            case "ds_protocol":                                         $saida.= "<br />Campo: Protocolo\n";                                             break;
            case "ds_ontology":                                         $saida.= "<br />Campo: Ontologia\n";                                             break;
            case "cd_language":                                         $saida.= "<br />Campo: Idioma\n";                                                break;
            case "ds_details":                                          $saida.= "<br />Campo: Detalhes\n";                                              break;
            case "ds_interactivity_type":                               $saida.= "<br />Campo: Tipo de Interatividade\n";                                break;
            case "ds_learning_resource_type":                           $saida.= "<br />Campo: Recursos do Tipo de Aprendizagem \n";                     break;
            case "ds_interactivity_level":                              $saida.= "<br />Campo: Nível de Interatividade\n";                               break;
            case "ds_sem_antic_density":                                $saida.= "<br />Campo: Densidade Semântica\n";                                   break;
            case "ds_intended_end_user_role":                           $saida.= "<br />Campo: Destinação e Função do Usuário\n";                        break;
            case "ds_context":                                          $saida.= "<br />Campo: Contexto\n";                                              break;
            case "ds_typical_age_range":                                $saida.= "<br />Campo: Faixa Etária\n";                                          break;
            case "ds_difficulty":                                       $saida.= "<br />Campo: Dificuldade\n";                                           break;
            case "ds_typical_learning_time":                            $saida.= "<br />Campo: Tempo de Aprendizagem\n";                                 break;
            case "ds_description":                                      $saida.= "<br />Campo: Descrição\n";                                             break;
            case "cd_language":                                         $saida.= "<br />Campo: Idioma\n";                                                break;
            case "ds_learning_content_type":                            $saida.= "<br />Campo: Tipo de Conteúdo\n";                                      break;
            case "ds_interaction":                                      $saida.= "<br />Campo: Interação\n";                                             break;
            case "ds_didatic_strategy":                                 $saida.= "<br />Campo: Estratégia Didática\n";                                   break;
            case "ds_cost":                                             $saida.= "<br />Campo: Custo\n";                                                 break;
            case "ds_copyright_and_other_restrictions":                 $saida.= "<br />Campo: Direitos de autor e outras restrições\n";                 break;
            case "ds_description":                                      $saida.= "<br />Campo: Descrição\n";                                             break;
            case "ds_kind":                                             $saida.= "<br />Campo: Tipo\n";                                                  break;
            case "ds_resource":                                         $saida.= "<br />Campo: Recurso\n";                                               break;
            case "ds_entity":                                           $saida.= "<br />Campo: Entidade\n";                                              break;
            case "ds_date":                                             $saida.= "<br />Campo: Data\n";                                                  break;
            case "ds_description":                                      $saida.= "<br />Campo: Descrição\n";                                             break;
            case "ds_purpose":                                          $saida.= "<br />Campo: Propósito\n";                                             break;
            case "ds_taxon_path":                                       $saida.= "<br />Campo: Caminho\n";                                               break;
            case "ds_description":                                      $saida.= "<br />Campo: Descrição\n";                                             break;
            case "ds_keyword":                                          $saida.= "<br />Campo: Palavras chave\n";                                        break;
            case "ds_has_visual":                                       $saida.= "<br />Campo: Elementos Visuais\n";                                     break;
            case "ds_has_audititory":                                   $saida.= "<br />Campo: Elementos Sonoros\n";                                     break;
            case "ds_has_text":                                         $saida.= "<br />Campo: Elementos de Texto\n";                                    break;
            case "ds_has_tactible":                                     $saida.= "<br />Campo: Elementos Táteis\n";                                      break;
            case "ds_earl_statment":                                    $saida.= "<br />Campo: Padrão EARL\n";                                           break;
            case "ds_equivalent_resource":                              $saida.= "<br />Campo: Recursos Equivalentes\n";                                 break;
            case "ds_segment_list":                                     $saida.= "<br />Campo: Segmentos\n";                                             break;
            case "ds_segmente_group_list":                              $saida.= "<br />Campo: Grupos\n";                                                break;
          }
        }
      }                           
      if ($eh_proprietario) {
        $saida.= "<br />Considerando apenas os meus Objetos de Aprendizagem\n";
      } else {
        $saida.= "<br />Considerando todos os Objetos de Aprendizagem cadastrados no Portal\n";
      }
      
      echo "<h4>".$saida."</h4>\n";
    }
//**************BANCO DE DADOS**************************************************    

  }
?>                   