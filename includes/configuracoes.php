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

        echo "  <form method=\"POST\" id=\"cadastro\" action=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&acao=editar\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');

        $quantidade = '0';

        switch ($tipo) {
          case "obaa":                                                          //campos padrao obaa - basico
            $quantidade = $this->montarFormularioConfiguracaoCamposPadraoObaa();
            $util->campoHidden('quantidade', $quantidade);
          break;

          case "padrao_obaa":                                                   //campos padrao obaa
            $quantidade = $this->montarFormularioConfiguracaoPadraoObaa();
          break;

          case "pesq_campos":
            $quantidade = $this->montarFormularioConfiguracaoCamposPesquisa();
            $util->campoHidden('quantidade', $quantidade);
          break;

          case "senha":
            $quantidade = $this->montarFormularioConfiguracaoSenha();
            $util->campoHidden('quantidade', $quantidade);
          break;

          case "acesso":
            $quantidade = $this->montarFormularioConfiguracaoAcesso();
            $util->campoHidden('quantidade', $quantidade);
          break;

          case "detalhes":
            $quantidade = $this->montarFormularioConfiguracaoDetalhes();
            $util->campoHidden('quantidade', $quantidade);
          break;

          case "exibicao":
            $quantidade = $this->montarFormularioConfiguracaoExibicao();
            $util->campoHidden('quantidade', $quantidade);
          break;
        }

        $util->linhaComentario('<hr>');
        $util->linhaBotao('Salvar', "desmarcarDigitou(); document.getElementById('cadastro').submit();");
        echo "    </table>\n";
        echo "  </form>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Nenhuma área de configuração definida!</p>\n";
        echo "<br /><br /><br /><br /><br /><br />\n";
      }            
    } 

    private function montarFormularioConfiguracaoCamposPesquisa() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';          $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';          $opcoes[]= $opcao;

      $plataforma = 'todas';

      $this->tiluloSecao('Configurações de filtros para os formulários de pesquisa');

      $util->linhaComentario('<hr>');

      $indice = $this->configuradorCampoBoolean('eh_filtrar_language', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_filtrar_coverage', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_filtrar_nivel_educacional', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoBoolean('eh_filtrar_type', $opcoes, $indice, '0', $plataforma);

      return $indice;
    }

    private function montarFormularioConfiguracaoSenha() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $opcoes= array();
      for ($i=5;$i<=15;$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes[]= $opcao;
      }
      $plataforma = 'todas';

      $this->tiluloSecao('Configurações de combinações de caracteres para formação de senha');

      $util->linhaComentario('<hr>');

      $indice = $this->configuradorCampoInteiroValorado('nr_caracteres_senha', $opcoes, $indice, '0', $plataforma);
      $util->linhaComentario('Importante que seja maior ou igual a soma de letras maiúsculas, minúsculas e números.');
      $util->linhaComentario(' ');

      $opcoes= array();
      for ($i=0;$i<=5;$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes[]= $opcao;
      }

      $indice = $this->configuradorCampoInteiroValorado('nr_letras_maiusculas_senha', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoInteiroValorado('nr_letras_minusculas_senha', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoInteiroValorado('nr_numeros_senha', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');

      $this->tiluloSecao('Configurações de notificações de senha');

      $indice = $this->configuradorCampoString('ds_titulo_notificacao_esqueceu_senha', '100', '150', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoString('ds_email_origem_esqueceu_senha', '100', '30', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoString('ds_senha_email_origem_esqueceu_senha', '100', '30', $indice, '0', $plataforma);

      return $indice;
    }

    private function montarFormularioConfiguracaoAcesso() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;
      $plataforma = 'todas';

      $this->tiluloSecao('Configurações de acesso ao Portal');

      $indice = $this->configuradorCampoString('ds_descricao_site', '100', '250', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoString('ds_palavras_chave', '100', '150', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoTexto('ds_mensagem_bloqueio', '5', '100', $indice, '0', $plataforma);

      $opcoes= array();
      for ($i=1;$i<=10;$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes[]= $opcao;
      }
      $indice = $this->configuradorCampoInteiroValorado('nr_tentativas_login', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');

      $this->tiluloSecao('Configurações de cadastro no Portal');

      $indice = $this->configuradorCampoTexto('ds_mensagem_armazenamento_dados_login_externo', '5', '100', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoTexto('ds_mensagem_boas_vindas', '5', '100', $indice, '0', $plataforma);

      return $indice;
    }

    private function montarFormularioConfiguracaoDetalhes() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;
      $plataforma = 'todas';

      $this->tiluloSecao('Orientações cadastrais');
      $indice = $this->configuradorCampoTexto('ds_sugestao_locais_audio', '5', '100', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoTexto('ds_sugestao_locais_video', '5', '100', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoTexto('ds_sugestao_locais_aplicativos', '5', '100', $indice, '0', $plataforma);

      $opcoes= array();
      for ($i=0.5;$i<=10;$i+=0.1) {
        $opcao= array();      $opcao[]= number_format($i, 2, '.', '');      $opcao[]= number_format($i, 2, ',', '.');          $opcoes[]= $opcao;
      }
      $indice = $this->configuradorCampoDecimalValorado('ds_limite_tamanho_arquivos', $opcoes, $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Orientações sobre denúncias');
      $indice = $this->configuradorCampoTexto('ds_explicacao_denucnia', '5', '100', $indice, '0', $plataforma);
      $opcoes= array();
      for ($i=1;$i<=10;$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes[]= $opcao;
      }
      $indice = $this->configuradorCampoInteiroValorado('nr_limite_denuncias', $opcoes, $indice, '0', $plataforma);
      $indice = $this->configuradorCampoString('ds_email_origem_denuncia_objeto_aprendizagem', '100', '30', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoString('ds_senha_email_origem_denuncia_objeto_aprendizagem', '100', '30', $indice, '0', $plataforma);

      $util->linhaComentario('<hr>');
      $this->tiluloSecao('Orientações sobre comentários');
      $indice = $this->configuradorCampoTexto('ds_explicacao_comentario', '5', '100', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoString('ds_email_origem_comentario_objeto_aprendizagem', '100', '30', $indice, '0', $plataforma);
      $indice = $this->configuradorCampoString('ds_senha_email_origem_comentario_objeto_aprendizagem', '100', '30', $indice, '0', $plataforma);

      return $indice;
    }

    private function montarFormularioConfiguracaoExibicao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;
      $plataforma = 'todas';

      $this->tiluloSecao('Configurações de exibição');

      $opcoes= array();
      for ($i=5;$i<=50;$i+=5) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes[]= $opcao;
      }
      $indice = $this->configuradorCampoInteiroValorado('nr_itens_topo_capa', $opcoes, $indice, '0', $plataforma);
      $opcoes= array();
      for ($i=3;$i<=30;$i+=3) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes[]= $opcao;
      }
      $indice = $this->configuradorCampoInteiroValorado('nr_itens_meio_capa', $opcoes, $indice, '0', $plataforma);
      $opcoes= array();
      for ($i=1;$i<=25;$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes[]= $opcao;
      }
      $indice = $this->configuradorCampoInteiroValorado('nr_linhas_pagina_exibicao_objetos_aprendizagem', $opcoes, $indice, '0', $plataforma);

      return $indice;
    }
    

    private function montarFormularioConfiguracaoCamposPadraoObaa() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $indice = 1;

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';          $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';          $opcoes[]= $opcao;

      $plataforma = 'todas';
      
      $this->tiluloSecao('Configurações de cadastro de Objetos de Aprendizagem');

      $this->tiluloSecao('Configurações para Edição');
      
      $util->linhaComentario('<hr>');


      return $indice;
    }

    private function montarFormularioConfiguracaoPadraoObaa() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $this->tiluloSecao('Padrão OBAA');

      $lista = array();
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações gerais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_identifier_cadastro_basico';       $elemento[] = 'eh_informar_general_identifier_cadastro_requisitar';       $elemento[] = 'cp_general_identifier_descricao';       $elemento[] = 'cp_general_identifier_nome';       $elemento[] = 'cp_general_identifier_informacao';       $elemento[] = 'eh_apresentar_general_identifier_retorno_pesquisa';       $elemento[] = 'nr_caracteres_resumo_nome_oa';        $titulo = 'Identificador';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_title_cadastro_basico';       $elemento[] = 'eh_informar_general_title_cadastro_requisitar';       $elemento[] = 'cp_general_title_descricao';       $elemento[] = 'cp_general_title_nome';       $elemento[] = 'cp_general_title_informacao';       $elemento[] = 'eh_apresentar_general_title_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Título';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_imagem_cadastro_basico';       $elemento[] = 'eh_informar_general_imagem_cadastro_requisitar';       $elemento[] = 'cp_general_imagem_descricao';       $elemento[] = 'cp_general_imagem_nome';       $elemento[] = 'cp_general_imagem_informacao';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Imagem';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_language_cadastro_basico';       $elemento[] = 'eh_informar_general_language_cadastro_requisitar';       $elemento[] = 'cp_general_language_descricao';       $elemento[] = 'cp_general_language_nome';       $elemento[] = 'cp_general_language_informacao';       $elemento[] = 'eh_apresentar_general_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_description_cadastro_basico';       $elemento[] = 'eh_informar_general_description_cadastro_requisitar';       $elemento[] = 'cp_general_description_descricao';       $elemento[] = 'cp_general_description_nome';       $elemento[] = 'cp_general_description_informacao';       $elemento[] = 'eh_apresentar_general_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_keyword_cadastro_basico';       $elemento[] = 'eh_informar_general_keyword_cadastro_requisitar';       $elemento[] = 'cp_general_keyword_descricao';       $elemento[] = 'cp_general_keyword_nome';       $elemento[] = 'cp_general_keyword_informacao';       $elemento[] = 'eh_apresentar_general_keyword_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Palavras-chave';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = 'cp_general_nivel_educacional_descricao';       $elemento[] = 'cp_general_nivel_educacional_nome';       $elemento[] = 'cp_general_nivel_educacional_informacao';       $elemento[] = 'eh_apresentar_general_nivel_educacional_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nível educacional';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_coverage_cadastro_basico';       $elemento[] = 'eh_informar_general_coverage_cadastro_requisitar';       $elemento[] = 'cp_general_coverage_descricao';       $elemento[] = 'cp_general_coverage_nome';       $elemento[] = 'cp_general_coverage_informacao';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Área de conhecimento';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_structure_cadastro_basico';       $elemento[] = 'eh_informar_general_structure_cadastro_requisitar';       $elemento[] = 'cp_general_structure_descricao';       $elemento[] = 'cp_general_structure_nome';       $elemento[] = 'cp_general_structure_informacao';       $elemento[] = 'eh_apresentar_general_structure_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Estrutura';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_agregation_level_cadastro_basico';       $elemento[] = 'eh_informar_general_agregation_level_cadastro_requisitar';       $elemento[] = 'cp_general_agregation_level_descricao';       $elemento[] = 'cp_general_agregation_level_nome';       $elemento[] = 'cp_general_agregation_level_informacao';       $elemento[] = 'eh_apresentar_general_agregation_level_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nível de agregação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre ciclo de vida';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_lyfe_cycle_version_cadastro_basico';       $elemento[] = 'eh_informar_lyfe_cycle_version_cadastro_requisitar';       $elemento[] = 'cp_lyfe_cycle_version_descricao';       $elemento[] = 'cp_lyfe_cycle_version_nome';       $elemento[] = 'cp_lyfe_cycle_version_informacao';       $elemento[] = 'eh_apresentar_lyfe_cycle_version_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Versão';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      //$elemento = array();       $elemento[] = 'eh_informar_lyfe_cycle_status_cadastro_basico';       $elemento[] = 'eh_informar_lyfe_cycle_status_cadastro_requisitar';       $elemento[] = 'cp_lyfe_cycle_status_descricao';       $elemento[] = 'cp_lyfe_cycle_status_nome';       $elemento[] = 'cp_lyfe_cycle_status_informacao';       $elemento[] = 'eh_apresentar_lyfe_cycle_status_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Status';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_lyfe_cycle_contribute_cadastro_basico';       $elemento[] = 'eh_informar_lyfe_cycle_contribute_cadastro_requisitar';       $elemento[] = 'cp_lyfe_cycle_contribute_descricao';       $elemento[] = 'cp_lyfe_cycle_contribute_nome';       $elemento[] = 'cp_lyfe_cycle_contribute_informacao';       $elemento[] = 'eh_apresentar_lyfe_cycle_contribute_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Contribuição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações de meta-metadata';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_identifier_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_identifier_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_identifier_descricao';       $elemento[] = 'cp_meta_metadata_identifier_nome';       $elemento[] = 'cp_meta_metadata_identifier_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_identifier_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Identificador';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_contribute_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_contribute_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_contribute_descricao';       $elemento[] = 'cp_meta_metadata_contribute_nome';       $elemento[] = 'cp_meta_metadata_contribute_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_contribute_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Contribuição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_metadata_schema_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_metadata_schema_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_metadata_schema_descricao';       $elemento[] = 'cp_meta_metadata_metadata_schema_nome';       $elemento[] = 'cp_meta_metadata_metadata_schema_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_metadata_schema_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Esquema de metadados';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_language_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_language_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_language_descricao';       $elemento[] = 'cp_meta_metadata_language_nome';       $elemento[] = 'cp_meta_metadata_language_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações Técnicas';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_format_cadastro_basico';       $elemento[] = 'eh_informar_technical_format_cadastro_requisitar';       $elemento[] = 'cp_technical_format_descricao';       $elemento[] = 'cp_technical_format_nome';       $elemento[] = 'cp_technical_format_informacao';       $elemento[] = 'eh_apresentar_technical_format_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Formato';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_size_cadastro_basico';       $elemento[] = 'eh_informar_technical_size_cadastro_requisitar';       $elemento[] = 'cp_technical_size_descricao';       $elemento[] = 'cp_technical_size_nome';       $elemento[] = 'cp_technical_size_informacao';       $elemento[] = 'eh_apresentar_technical_size_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tamanho';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = 'cp_technical_location_descricao';       $elemento[] = 'cp_technical_location_nome';       $elemento[] = 'cp_technical_location_informacao';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Localização';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_requirement_cadastro_basico';       $elemento[] = 'eh_informar_technical_requirement_cadastro_requisitar';       $elemento[] = 'cp_technical_requirement_descricao';       $elemento[] = 'cp_technical_requirement_nome';       $elemento[] = 'cp_technical_requirement_informacao';       $elemento[] = 'eh_apresentar_technical_requirement_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Requerimentos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_composite_cadastro_basico';       $elemento[] = 'eh_informar_technical_composite_cadastro_requisitar';       $elemento[] = 'cp_technical_composite_descricao';       $elemento[] = 'cp_technical_composite_nome';       $elemento[] = 'cp_technical_composite_informacao';       $elemento[] = 'eh_apresentar_technical_composite_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Composição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_installation_remarks_cadastro_basico';       $elemento[] = 'eh_informar_technical_installation_remarks_cadastro_requisitar';       $elemento[] = 'cp_technical_installation_remarks_descricao';       $elemento[] = 'cp_technical_installation_remarks_nome';       $elemento[] = 'cp_technical_installation_remarks_informacao';       $elemento[] = 'eh_apresentar_technical_installation_remarks_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Observações de instalação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_other_plataforms_requirements_cadastro_basico';       $elemento[] = 'eh_informar_technical_other_plataforms_requirements_cadastro_requisitar';       $elemento[] = 'cp_technical_other_plataforms_requirements_descricao';       $elemento[] = 'cp_technical_other_plataforms_requirements_nome';       $elemento[] = 'cp_technical_other_plataforms_requirements_informacao';       $elemento[] = 'eh_apresentar_technical_other_plataforms_requirements_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Outras plataformas Requisitos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_duration_cadastro_basico';       $elemento[] = 'eh_informar_technical_duration_cadastro_requisitar';       $elemento[] = 'cp_technical_duration_descricao';       $elemento[] = 'cp_technical_duration_nome';       $elemento[] = 'cp_technical_duration_informacao';       $elemento[] = 'eh_apresentar_technical_duration_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Duração';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_supported_plataform_cadastro_basico';       $elemento[] = 'eh_informar_technical_supported_plataform_cadastro_requisitar';       $elemento[] = 'cp_technical_supported_plataform_descricao';       $elemento[] = 'cp_technical_supported_plataform_nome';       $elemento[] = 'cp_technical_supported_plataform_informacao';       $elemento[] = 'eh_apresentar_technical_supported_plataform_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Plataforma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Características referentes as Informações técnicas';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_plataform_type_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_plataform_type_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_plataform_type_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_plataform_type_nome';       $elemento[] = 'cp_technical_plataform_specific_features_plataform_type_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_plataform_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo de Plataforma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_format_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_format_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_format_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_format_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_format_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_format_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Formato';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_size_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_size_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_size_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_size_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_size_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_size_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tamanho';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_location_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_location_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_location_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_location_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_location_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_location_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Localização';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_requeriments_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_requeriments_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_requeriments_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_requeriments_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Requerimentos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_intermediari';       $elemento[] = 'cp_technical_plataform_specific_features_specific_instalation_remarks_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_instalation_remarks_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_instalation_remarks_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_instalation_remarks_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Observações de instalação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_bas';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_int';       $elemento[] = 'cp_technical_plataform_specific_features_specific_other_plataform_requeriments_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_other_plataform_requeriments_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_other_plataform_requeriments_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_other_plataform_requeriments_retorno';       $elemento[] = '';        $titulo = 'Outros requerimentos da plataforma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Serviços referentes as informações técnicas';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_name_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_name_cadastro_requisitar';       $elemento[] = 'cp_technical_service_name_descricao';       $elemento[] = 'cp_technical_service_name_nome';       $elemento[] = 'cp_technical_service_name_informacao';       $elemento[] = 'eh_apresentar_technical_service_name_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nome';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_type_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_type_cadastro_requisitar';       $elemento[] = 'cp_technical_service_type_descricao';       $elemento[] = 'cp_technical_service_type_nome';       $elemento[] = 'cp_technical_service_type_informacao';       $elemento[] = 'eh_apresentar_technical_service_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_provides_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_provides_cadastro_requisitar';       $elemento[] = 'cp_technical_service_provides_descricao';       $elemento[] = 'cp_technical_service_provides_nome';       $elemento[] = 'cp_technical_service_provides_informacao';       $elemento[] = 'eh_apresentar_technical_service_provides_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Fornecedor';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_essential_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_essential_cadastro_requisitar';       $elemento[] = 'cp_technical_service_essential_descricao';       $elemento[] = 'cp_technical_service_essential_nome';       $elemento[] = 'cp_technical_service_essential_informacao';       $elemento[] = 'eh_apresentar_technical_service_essential_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Essencial';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_protocol_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_protocol_cadastro_requisitar';       $elemento[] = 'cp_technical_service_protocol_descricao';       $elemento[] = 'cp_technical_service_protocol_nome';       $elemento[] = 'cp_technical_service_protocol_informacao';       $elemento[] = 'eh_apresentar_technical_service_protocol_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Protocolo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_ontology_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_ontology_cadastro_requisitar';       $elemento[] = 'cp_technical_service_ontology_descricao';       $elemento[] = 'cp_technical_service_ontology_nome';       $elemento[] = 'cp_technical_service_ontology_informacao';       $elemento[] = 'eh_apresentar_technical_service_ontology_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Ontologia';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_language_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_language_cadastro_requisitar';       $elemento[] = 'cp_technical_service_language_descricao';       $elemento[] = 'cp_technical_service_language_nome';       $elemento[] = 'cp_technical_service_language_informacao';       $elemento[] = 'eh_apresentar_technical_service_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_details_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_details_cadastro_requisitar';       $elemento[] = 'cp_technical_service_details_descricao';       $elemento[] = 'cp_technical_service_details_nome';       $elemento[] = 'cp_technical_service_details_informacao';       $elemento[] = 'eh_apresentar_technical_service_details_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Detalhes';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações educacionais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_interactivity_type_cadastro_basico';       $elemento[] = 'eh_informar_educational_interactivity_type_cadastro_requisitar';       $elemento[] = 'cp_educational_interactivity_type_descricao';       $elemento[] = 'cp_educational_interactivity_type_nome';       $elemento[] = 'cp_educational_interactivity_type_informacao';       $elemento[] = 'eh_apresentar_educational_interactivity_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo de interatividade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_learning_resource_type_cadastro_basico';       $elemento[] = 'eh_informar_educational_learning_resource_type_cadastro_requisitar';       $elemento[] = 'cp_educational_learning_resource_type_descricao';       $elemento[] = 'cp_educational_learning_resource_type_nome';       $elemento[] = 'cp_educational_learning_resource_type_informacao';       $elemento[] = 'eh_apresentar_educational_learning_resource_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Recursos de aprendizagem';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_interactivity_level_cadastro_basico';       $elemento[] = 'eh_informar_educational_interactivity_level_cadastro_requisitar';       $elemento[] = 'cp_educational_interactivity_level_descricao';       $elemento[] = 'cp_educational_interactivity_level_nome';       $elemento[] = 'cp_educational_interactivity_level_informacao';       $elemento[] = 'eh_apresentar_educational_interactivity_level_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nível de interatividade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_sem_antic_density_cadastro_basico';       $elemento[] = 'eh_informar_educational_sem_antic_density_cadastro_requisitar';       $elemento[] = 'cp_educational_sem_antic_density_descricao';       $elemento[] = 'cp_educational_sem_antic_density_nome';       $elemento[] = 'cp_educational_sem_antic_density_informacao';       $elemento[] = 'eh_apresentar_educational_sem_antic_density_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Densidade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_intended_end_user_role_cadastro_basico';       $elemento[] = 'eh_informar_educational_intended_end_user_role_cadastro_requisitar';       $elemento[] = 'cp_educational_intended_end_user_role_descricao';       $elemento[] = 'cp_educational_intended_end_user_role_nome';       $elemento[] = 'cp_educational_intended_end_user_role_informacao';       $elemento[] = 'eh_apresentar_educational_intended_end_user_role_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Função';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_context_cadastro_basico';       $elemento[] = 'eh_informar_educational_context_cadastro_requisitar';       $elemento[] = 'cp_educational_context_descricao';       $elemento[] = 'cp_educational_context_nome';       $elemento[] = 'cp_educational_context_informacao';       $elemento[] = 'eh_apresentar_educational_context_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Contexto';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_typical_age_range_cadastro_basico';       $elemento[] = 'eh_informar_educational_typical_age_range_cadastro_requisitar';       $elemento[] = 'cp_educational_typical_age_range_descricao';       $elemento[] = 'cp_educational_typical_age_range_nome';       $elemento[] = 'cp_educational_typical_age_range_informacao';       $elemento[] = 'eh_apresentar_educational_typical_age_range_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Faixa etária';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_difficulty_cadastro_basico';       $elemento[] = 'eh_informar_educational_difficulty_cadastro_requisitar';       $elemento[] = 'cp_educational_difficulty_descricao';       $elemento[] = 'cp_educational_difficulty_nome';       $elemento[] = 'cp_educational_difficulty_informacao';       $elemento[] = 'eh_apresentar_educational_difficulty_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Dificuldade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_typical_learning_time_cadastro_basico';       $elemento[] = 'eh_informar_educational_typical_learning_time_cadastro_requisitar';       $elemento[] = 'cp_educational_typical_learning_time_descricao';       $elemento[] = 'cp_educational_typical_learning_time_nome';       $elemento[] = 'cp_educational_typical_learning_time_informacao';       $elemento[] = 'eh_apresentar_educational_typical_learning_time_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tempo de aprendizagem';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_description_cadastro_basico';       $elemento[] = 'eh_informar_educational_description_cadastro_requisitar';       $elemento[] = 'cp_educational_description_descricao';       $elemento[] = 'cp_educational_description_nome';       $elemento[] = 'cp_educational_description_informacao';       $elemento[] = 'eh_apresentar_educational_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_language_cadastro_basico';       $elemento[] = 'eh_informar_educational_language_cadastro_requisitar';       $elemento[] = 'cp_educational_language_descricao';       $elemento[] = 'cp_educational_language_nome';       $elemento[] = 'cp_educational_language_informacao';       $elemento[] = 'eh_apresentar_educational_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_learning_content_type_cadastro_basico';       $elemento[] = 'eh_informar_educational_learning_content_type_cadastro_requisitar';       $elemento[] = 'cp_educational_learning_content_type_descricao';       $elemento[] = 'cp_educational_learning_content_type_nome';       $elemento[] = 'cp_educational_learning_content_type_informacao';       $elemento[] = 'eh_apresentar_educational_learning_content_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo de conteúdo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_interaction_cadastro_basico';       $elemento[] = 'eh_informar_educational_interaction_cadastro_requisitar';       $elemento[] = 'cp_educational_interaction_descricao';       $elemento[] = 'cp_educational_interaction_nome';       $elemento[] = 'cp_educational_interaction_informacao';       $elemento[] = 'eh_apresentar_educational_interaction_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Interação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_didatic_strategy_cadastro_basico';       $elemento[] = 'eh_informar_educational_didatic_strategy_cadastro_requisitar';       $elemento[] = 'cp_educational_didatic_strategy_descricao';       $elemento[] = 'cp_educational_didatic_strategy_nome';       $elemento[] = 'cp_educational_didatic_strategy_informacao';       $elemento[] = 'eh_apresentar_educational_didatic_strategy_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Estratégia didática';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações de direitos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_rights_cost_cadastro_basico';       $elemento[] = 'eh_informar_rights_cost_cadastro_requisitar';       $elemento[] = 'cp_rights_cost_descricao';       $elemento[] = 'cp_rights_cost_nome';       $elemento[] = 'cp_rights_cost_informacao';       $elemento[] = 'eh_apresentar_rights_cost_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Custo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_rights_copyright_and_other_restrictions_cadastro_basico';       $elemento[] = 'eh_informar_rights_copyright_and_other_restrictions_cadastro_requisitar';       $elemento[] = 'cp_rights_copyright_and_other_restrictions_descricao';       $elemento[] = 'cp_rights_copyright_and_other_restrictions_nome';       $elemento[] = 'cp_rights_copyright_and_other_restrictions_informacao';       $elemento[] = 'eh_apresentar_rights_copyright_and_other_restrictions_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Direitos de autor e outras restrições';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_rights_description_cadastro_basico';       $elemento[] = 'eh_informar_rights_description_cadastro_requisitar';       $elemento[] = 'cp_rights_description_descricao';       $elemento[] = 'cp_rights_description_nome';       $elemento[] = 'cp_rights_description_informacao';       $elemento[] = 'eh_apresentar_rights_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre relações';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_relation_kind_cadastro_basico';       $elemento[] = 'eh_informar_relation_kind_cadastro_requisitar';       $elemento[] = 'cp_relation_kind_descricao';       $elemento[] = 'cp_relation_kind_nome';       $elemento[] = 'cp_relation_kind_informacao';       $elemento[] = 'eh_apresentar_relation_kind_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_relation_resource_cadastro_basico';       $elemento[] = 'eh_informar_relation_resource_cadastro_requisitar';       $elemento[] = 'cp_relation_resource_descricao';       $elemento[] = 'cp_relation_resource_nome';       $elemento[] = 'cp_relation_resource_informacao';       $elemento[] = 'eh_apresentar_relation_resource_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Recurso';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações de anotações';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_annotation_entity_cadastro_basico';       $elemento[] = 'eh_informar_annotation_entity_cadastro_requisitar';       $elemento[] = 'cp_annotation_entity_descricao';       $elemento[] = 'cp_annotation_entity_nome';       $elemento[] = 'cp_annotation_entity_informacao';       $elemento[] = 'eh_apresentar_annotation_entity_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Entidade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_annotation_date_cadastro_basico';       $elemento[] = 'eh_informar_annotation_date_cadastro_requisitar';       $elemento[] = 'cp_annotation_date_descricao';       $elemento[] = 'cp_annotation_date_nome';       $elemento[] = 'cp_annotation_date_informacao';       $elemento[] = 'eh_apresentar_annotation_date_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Data';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_annotation_description_cadastro_basico';       $elemento[] = 'eh_informar_annotation_description_cadastro_requisitar';       $elemento[] = 'cp_annotation_description_descricao';       $elemento[] = 'cp_annotation_description_nome';       $elemento[] = 'cp_annotation_description_informacao';       $elemento[] = 'eh_apresentar_annotation_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre classificações';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_purpose_cadastro_basico';       $elemento[] = 'eh_informar_classification_purpose_cadastro_requisitar';       $elemento[] = 'cp_classification_purpose_descricao';       $elemento[] = 'cp_classification_purpose_nome';       $elemento[] = 'cp_classification_purpose_informacao';       $elemento[] = 'eh_apresentar_classification_purpose_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Propósito';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_taxon_path_cadastro_basico';       $elemento[] = 'eh_informar_classification_taxon_path_cadastro_requisitar';       $elemento[] = 'cp_classification_taxon_path_descricao';       $elemento[] = 'cp_classification_taxon_path_nome';       $elemento[] = 'cp_classification_taxon_path_informacao';       $elemento[] = 'eh_apresentar_classification_taxon_path_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Táxon Path';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_description_cadastro_basico';       $elemento[] = 'eh_informar_classification_description_cadastro_requisitar';       $elemento[] = 'cp_classification_description_descricao';       $elemento[] = 'cp_classification_description_nome';       $elemento[] = 'cp_classification_description_informacao';       $elemento[] = 'eh_apresentar_classification_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_keyword_cadastro_basico';       $elemento[] = 'eh_informar_classification_keyword_cadastro_requisitar';       $elemento[] = 'cp_classification_keyword_descricao';       $elemento[] = 'cp_classification_keyword_nome';       $elemento[] = 'cp_classification_keyword_informacao';       $elemento[] = 'eh_apresentar_classification_keyword_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Palavras-chave';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre acessibilidade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_visual_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_visual_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_visual_descricao';       $elemento[] = 'cp_acessibility_has_visual_nome';       $elemento[] = 'cp_acessibility_has_visual_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_visual_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos visuais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_audititory_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_audititory_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_audititory_descricao';       $elemento[] = 'cp_acessibility_has_audititory_nome';       $elemento[] = 'cp_acessibility_has_audititory_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_audititory_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos sonoros';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_text_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_text_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_text_descricao';       $elemento[] = 'cp_acessibility_has_text_nome';       $elemento[] = 'cp_acessibility_has_text_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_text_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos textuais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_tactible_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_tactible_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_tactible_descricao';       $elemento[] = 'cp_acessibility_has_tactible_nome';       $elemento[] = 'cp_acessibility_has_tactible_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_tactible_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos táteis';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_earl_statment_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_earl_statment_cadastro_requisitar';       $elemento[] = 'cp_acessibility_earl_statment_descricao';       $elemento[] = 'cp_acessibility_earl_statment_nome';       $elemento[] = 'cp_acessibility_earl_statment_informacao';       $elemento[] = 'eh_apresentar_acessibility_earl_statment_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Declaração';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_equivalent_resource_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_equivalent_resource_cadastro_requisitar';       $elemento[] = 'cp_acessibility_equivalent_resource_descricao';       $elemento[] = 'cp_acessibility_equivalent_resource_nome';       $elemento[] = 'cp_acessibility_equivalent_resource_informacao';       $elemento[] = 'eh_apresentar_acessibility_equivalent_resource_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Recursos equivalentes';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações do segmento';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_segment_information_table_segment_list_cadastro_basico';       $elemento[] = 'eh_informar_segment_information_table_segment_list_cadastro_requisitar';       $elemento[] = 'cp_segment_information_table_segment_list_descricao';       $elemento[] = 'cp_segment_information_table_segment_list_nome';       $elemento[] = 'cp_segment_information_table_segment_list_informacao';       $elemento[] = 'eh_apresentar_segment_information_table_segment_list_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Lista de segmentos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_segment_information_table_segmente_group_list_cadastro_basico';       $elemento[] = 'eh_informar_segment_information_table_segmente_group_list_cadastro_requisitar';       $elemento[] = 'cp_segment_information_table_segmente_group_list_descricao';       $elemento[] = 'cp_segment_information_table_segmente_group_list_nome';       $elemento[] = 'cp_segment_information_table_segmente_group_list_informacao';       $elemento[] = 'eh_apresentar_segment_information_table_segmente_group_list_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Lista de grupo de segmento';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;

      $opcoes_2 = array();
      for ($i=0;$i<=100;$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;          $opcoes_2[]= $opcao;
      }

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';          $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';          $opcoes[]= $opcao;

      $util->linhaComentario("Sempre que o item 'Obrigatório (cadastro básico)' for marcado como 'SIM', o item 'Opcional (a requisitar)' automaticamente será marcado com a opção 'SIM'");

      foreach ($lista as $l) {
        $titulo = $l[0];
        if ($titulo != '<hr>') {
          $elemento = $l[2];
          if ($titulo != '') {
            $this->tiluloSecao($titulo);
          }

          if ($elemento[0] != '') {
            $variavel = $elemento[0];
            $dados = $this->selectDadosConfiguracao($variavel);
            $nome = $dados['ds_texto_configuracao'];
            $tipo = 'vl_boolean';
            $valor = $dados[$tipo];
            if ($elemento[1] != '') {
              $variavel_acao = $elemento[1];
              $util->linhaSeletorAcao($nome, $variavel, $valor, $opcoes, '100', " onChange=\"definirStatus('$variavel', '$variavel_acao');\" ");
            } else {
              $util->linhaSeletor($nome, $variavel, $valor, $opcoes, '100');
            }
          }
          if ($elemento[1] != '') {
            $variavel = $elemento[1];
            $dados = $this->selectDadosConfiguracao($variavel);
            $nome = $dados['ds_texto_configuracao'];
            $tipo = 'vl_boolean';
            $valor = $dados[$tipo];
            $util->linhaSeletor($nome, $variavel, $valor, $opcoes, '100');
          }
          if ($elemento[5] != '') {
            $variavel = $elemento[5];
            $dados = $this->selectDadosConfiguracao($variavel);
            $nome = $dados['ds_texto_configuracao'];
            $tipo = 'vl_boolean';
            $valor = $dados[$tipo];
            $util->linhaSeletor($nome, $variavel, $valor, $opcoes, '100');
          }
          if ($elemento[6] != '') {
            $variavel = $elemento[6];
            $dados = $this->selectDadosConfiguracao($variavel);
            $nome = $dados['ds_texto_configuracao'];
            $tipo = 'vl_inteiro';
            $valor = $dados[$tipo];
            $util->linhaSeletor($nome, $variavel, $valor, $opcoes_2, '100');
          }
          if ($elemento[3] != '') {
            $variavel = $elemento[3];
            $dados = $this->selectDadosConfiguracao($variavel);
            $nome = $dados['ds_texto_configuracao'];
            $tipo = 'vl_string';
            $valor = $dados[$tipo];
            $util->linhaUmCampoText('0', $nome, $variavel, 250, 100, $valor);
          }
          if ($elemento[2] != '') {
            $variavel = $elemento[2];
            $dados = $this->selectDadosConfiguracao($variavel);
            $nome = $dados['ds_texto_configuracao'];
            $tipo = 'vl_string';
            $valor = $dados[$tipo];
            $util->linhaUmCampoText('0', $nome, $variavel, 250, 100, $valor);
          }
          if ($elemento[4] != '') {
            $variavel = $elemento[4];
            $dados = $this->selectDadosConfiguracao($variavel);
            $nome = $dados['ds_texto_configuracao'];
            $tipo = 'vl_string';
            $valor = $dados[$tipo];
            $util->linhaUmCampoText('0', $nome, $variavel, 250, 100, $valor);
          }
          $comentario = $l[1];
          if ($comentario != '') {
            $util->linhaComentario($comentario);
          }
          $util->linhaComentario('&nbsp;');
        } else {
          $util->linhaComentario('<hr>');
        }
      }
      $util->linhaComentario("Sempre que o item 'Obrigatório (cadastro básico)' for marcado como 'SIM', o item 'Opcional (a requisitar)' automaticamente será marcado com a opção 'SIM'");
    }

    public function salvarEdicaoConfiguracoes($secao, $subsecao) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      if (isset($_POST['quantidade'])) {
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
      } else {
      $lista = array();
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações gerais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_identifier_cadastro_basico';       $elemento[] = 'eh_informar_general_identifier_cadastro_requisitar';       $elemento[] = 'cp_general_identifier_descricao';       $elemento[] = 'cp_general_identifier_nome';       $elemento[] = 'cp_general_identifier_informacao';       $elemento[] = 'eh_apresentar_general_identifier_retorno_pesquisa';       $elemento[] = 'nr_caracteres_resumo_nome_oa';        $titulo = 'Identificador';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_title_cadastro_basico';       $elemento[] = 'eh_informar_general_title_cadastro_requisitar';       $elemento[] = 'cp_general_title_descricao';       $elemento[] = 'cp_general_title_nome';       $elemento[] = 'cp_general_title_informacao';       $elemento[] = 'eh_apresentar_general_title_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Título';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_imagem_cadastro_basico';       $elemento[] = 'eh_informar_general_imagem_cadastro_requisitar';       $elemento[] = 'cp_general_imagem_descricao';       $elemento[] = 'cp_general_imagem_nome';       $elemento[] = 'cp_general_imagem_informacao';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Imagem';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_language_cadastro_basico';       $elemento[] = 'eh_informar_general_language_cadastro_requisitar';       $elemento[] = 'cp_general_language_descricao';       $elemento[] = 'cp_general_language_nome';       $elemento[] = 'cp_general_language_informacao';       $elemento[] = 'eh_apresentar_general_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_description_cadastro_basico';       $elemento[] = 'eh_informar_general_description_cadastro_requisitar';       $elemento[] = 'cp_general_description_descricao';       $elemento[] = 'cp_general_description_nome';       $elemento[] = 'cp_general_description_informacao';       $elemento[] = 'eh_apresentar_general_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_keyword_cadastro_basico';       $elemento[] = 'eh_informar_general_keyword_cadastro_requisitar';       $elemento[] = 'cp_general_keyword_descricao';       $elemento[] = 'cp_general_keyword_nome';       $elemento[] = 'cp_general_keyword_informacao';       $elemento[] = 'eh_apresentar_general_keyword_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Palavras-chave';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = 'cp_general_nivel_educacional_descricao';       $elemento[] = 'cp_general_nivel_educacional_nome';       $elemento[] = 'cp_general_nivel_educacional_informacao';       $elemento[] = 'eh_apresentar_general_nivel_educacional_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nível educacional';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_coverage_cadastro_basico';       $elemento[] = 'eh_informar_general_coverage_cadastro_requisitar';       $elemento[] = 'cp_general_coverage_descricao';       $elemento[] = 'cp_general_coverage_nome';       $elemento[] = 'cp_general_coverage_informacao';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Área de conhecimento';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_structure_cadastro_basico';       $elemento[] = 'eh_informar_general_structure_cadastro_requisitar';       $elemento[] = 'cp_general_structure_descricao';       $elemento[] = 'cp_general_structure_nome';       $elemento[] = 'cp_general_structure_informacao';       $elemento[] = 'eh_apresentar_general_structure_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Estrutura';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_general_agregation_level_cadastro_basico';       $elemento[] = 'eh_informar_general_agregation_level_cadastro_requisitar';       $elemento[] = 'cp_general_agregation_level_descricao';       $elemento[] = 'cp_general_agregation_level_nome';       $elemento[] = 'cp_general_agregation_level_informacao';       $elemento[] = 'eh_apresentar_general_agregation_level_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nível de agregação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre ciclo de vida';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_lyfe_cycle_version_cadastro_basico';       $elemento[] = 'eh_informar_lyfe_cycle_version_cadastro_requisitar';       $elemento[] = 'cp_lyfe_cycle_version_descricao';       $elemento[] = 'cp_lyfe_cycle_version_nome';       $elemento[] = 'cp_lyfe_cycle_version_informacao';       $elemento[] = 'eh_apresentar_lyfe_cycle_version_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Versão';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      //$elemento = array();       $elemento[] = 'eh_informar_lyfe_cycle_status_cadastro_basico';       $elemento[] = 'eh_informar_lyfe_cycle_status_cadastro_requisitar';       $elemento[] = 'cp_lyfe_cycle_status_descricao';       $elemento[] = 'cp_lyfe_cycle_status_nome';       $elemento[] = 'cp_lyfe_cycle_status_informacao';       $elemento[] = 'eh_apresentar_lyfe_cycle_status_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Status';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_lyfe_cycle_contribute_cadastro_basico';       $elemento[] = 'eh_informar_lyfe_cycle_contribute_cadastro_requisitar';       $elemento[] = 'cp_lyfe_cycle_contribute_descricao';       $elemento[] = 'cp_lyfe_cycle_contribute_nome';       $elemento[] = 'cp_lyfe_cycle_contribute_informacao';       $elemento[] = 'eh_apresentar_lyfe_cycle_contribute_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Contribuição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações de meta-metadata';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_identifier_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_identifier_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_identifier_descricao';       $elemento[] = 'cp_meta_metadata_identifier_nome';       $elemento[] = 'cp_meta_metadata_identifier_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_identifier_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Identificador';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_contribute_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_contribute_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_contribute_descricao';       $elemento[] = 'cp_meta_metadata_contribute_nome';       $elemento[] = 'cp_meta_metadata_contribute_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_contribute_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Contribuição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_metadata_schema_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_metadata_schema_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_metadata_schema_descricao';       $elemento[] = 'cp_meta_metadata_metadata_schema_nome';       $elemento[] = 'cp_meta_metadata_metadata_schema_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_metadata_schema_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Esquema de metadados';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_meta_metadata_language_cadastro_basico';       $elemento[] = 'eh_informar_meta_metadata_language_cadastro_requisitar';       $elemento[] = 'cp_meta_metadata_language_descricao';       $elemento[] = 'cp_meta_metadata_language_nome';       $elemento[] = 'cp_meta_metadata_language_informacao';       $elemento[] = 'eh_apresentar_meta_metadata_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações Técnicas';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_format_cadastro_basico';       $elemento[] = 'eh_informar_technical_format_cadastro_requisitar';       $elemento[] = 'cp_technical_format_descricao';       $elemento[] = 'cp_technical_format_nome';       $elemento[] = 'cp_technical_format_informacao';       $elemento[] = 'eh_apresentar_technical_format_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Formato';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_size_cadastro_basico';       $elemento[] = 'eh_informar_technical_size_cadastro_requisitar';       $elemento[] = 'cp_technical_size_descricao';       $elemento[] = 'cp_technical_size_nome';       $elemento[] = 'cp_technical_size_informacao';       $elemento[] = 'eh_apresentar_technical_size_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tamanho';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = 'eh_informar_technical_location_cadastro_requisitar';       $elemento[] = 'cp_technical_location_descricao';       $elemento[] = 'cp_technical_location_nome';       $elemento[] = 'cp_technical_location_informacao';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Localização';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_requirement_cadastro_basico';       $elemento[] = 'eh_informar_technical_requirement_cadastro_requisitar';       $elemento[] = 'cp_technical_requirement_descricao';       $elemento[] = 'cp_technical_requirement_nome';       $elemento[] = 'cp_technical_requirement_informacao';       $elemento[] = 'eh_apresentar_technical_requirement_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Requerimentos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_composite_cadastro_basico';       $elemento[] = 'eh_informar_technical_composite_cadastro_requisitar';       $elemento[] = 'cp_technical_composite_descricao';       $elemento[] = 'cp_technical_composite_nome';       $elemento[] = 'cp_technical_composite_informacao';       $elemento[] = 'eh_apresentar_technical_composite_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Composição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_installation_remarks_cadastro_basico';       $elemento[] = 'eh_informar_technical_installation_remarks_cadastro_requisitar';       $elemento[] = 'cp_technical_installation_remarks_descricao';       $elemento[] = 'cp_technical_installation_remarks_nome';       $elemento[] = 'cp_technical_installation_remarks_informacao';       $elemento[] = 'eh_apresentar_technical_installation_remarks_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Observações de instalação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_other_plataforms_requirements_cadastro_basico';       $elemento[] = 'eh_informar_technical_other_plataforms_requirements_cadastro_requisitar';       $elemento[] = 'cp_technical_other_plataforms_requirements_descricao';       $elemento[] = 'cp_technical_other_plataforms_requirements_nome';       $elemento[] = 'cp_technical_other_plataforms_requirements_informacao';       $elemento[] = 'eh_apresentar_technical_other_plataforms_requirements_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Outras plataformas Requisitos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_duration_cadastro_basico';       $elemento[] = 'eh_informar_technical_duration_cadastro_requisitar';       $elemento[] = 'cp_technical_duration_descricao';       $elemento[] = 'cp_technical_duration_nome';       $elemento[] = 'cp_technical_duration_informacao';       $elemento[] = 'eh_apresentar_technical_duration_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Duração';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_supported_plataform_cadastro_basico';       $elemento[] = 'eh_informar_technical_supported_plataform_cadastro_requisitar';       $elemento[] = 'cp_technical_supported_plataform_descricao';       $elemento[] = 'cp_technical_supported_plataform_nome';       $elemento[] = 'cp_technical_supported_plataform_informacao';       $elemento[] = 'eh_apresentar_technical_supported_plataform_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Plataforma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Características referentes as Informações técnicas';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_plataform_type_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_plataform_type_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_plataform_type_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_plataform_type_nome';       $elemento[] = 'cp_technical_plataform_specific_features_plataform_type_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_plataform_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo de Plataforma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_format_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_format_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_format_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_format_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_format_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_format_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Formato';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_size_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_size_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_size_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_size_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_size_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_size_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tamanho';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_location_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_location_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_location_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_location_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_location_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_location_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Localização';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_requeriments_cadastro_requisitar';       $elemento[] = 'cp_technical_plataform_specific_features_specific_requeriments_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_requeriments_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_requeriments_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_requeriments_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Requerimentos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_basico';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_instalation_remarks_cadastro_intermediari';       $elemento[] = 'cp_technical_plataform_specific_features_specific_instalation_remarks_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_instalation_remarks_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_instalation_remarks_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_instalation_remarks_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Observações de instalação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_bas';       $elemento[] = 'eh_informar_technical_plataform_specific_features_specific_other_plataform_requeriments_cadastro_int';       $elemento[] = 'cp_technical_plataform_specific_features_specific_other_plataform_requeriments_descricao';       $elemento[] = 'cp_technical_plataform_specific_features_specific_other_plataform_requeriments_nome';       $elemento[] = 'cp_technical_plataform_specific_features_specific_other_plataform_requeriments_informacao';       $elemento[] = 'eh_apresentar_technical_plataform_specific_features_specific_other_plataform_requeriments_retorno';       $elemento[] = '';        $titulo = 'Outros requerimentos da plataforma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Serviços referentes as informações técnicas';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_name_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_name_cadastro_requisitar';       $elemento[] = 'cp_technical_service_name_descricao';       $elemento[] = 'cp_technical_service_name_nome';       $elemento[] = 'cp_technical_service_name_informacao';       $elemento[] = 'eh_apresentar_technical_service_name_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nome';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_type_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_type_cadastro_requisitar';       $elemento[] = 'cp_technical_service_type_descricao';       $elemento[] = 'cp_technical_service_type_nome';       $elemento[] = 'cp_technical_service_type_informacao';       $elemento[] = 'eh_apresentar_technical_service_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_provides_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_provides_cadastro_requisitar';       $elemento[] = 'cp_technical_service_provides_descricao';       $elemento[] = 'cp_technical_service_provides_nome';       $elemento[] = 'cp_technical_service_provides_informacao';       $elemento[] = 'eh_apresentar_technical_service_provides_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Fornecedor';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_essential_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_essential_cadastro_requisitar';       $elemento[] = 'cp_technical_service_essential_descricao';       $elemento[] = 'cp_technical_service_essential_nome';       $elemento[] = 'cp_technical_service_essential_informacao';       $elemento[] = 'eh_apresentar_technical_service_essential_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Essencial';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_protocol_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_protocol_cadastro_requisitar';       $elemento[] = 'cp_technical_service_protocol_descricao';       $elemento[] = 'cp_technical_service_protocol_nome';       $elemento[] = 'cp_technical_service_protocol_informacao';       $elemento[] = 'eh_apresentar_technical_service_protocol_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Protocolo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_ontology_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_ontology_cadastro_requisitar';       $elemento[] = 'cp_technical_service_ontology_descricao';       $elemento[] = 'cp_technical_service_ontology_nome';       $elemento[] = 'cp_technical_service_ontology_informacao';       $elemento[] = 'eh_apresentar_technical_service_ontology_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Ontologia';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_language_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_language_cadastro_requisitar';       $elemento[] = 'cp_technical_service_language_descricao';       $elemento[] = 'cp_technical_service_language_nome';       $elemento[] = 'cp_technical_service_language_informacao';       $elemento[] = 'eh_apresentar_technical_service_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_technical_service_details_cadastro_basico';       $elemento[] = 'eh_informar_technical_service_details_cadastro_requisitar';       $elemento[] = 'cp_technical_service_details_descricao';       $elemento[] = 'cp_technical_service_details_nome';       $elemento[] = 'cp_technical_service_details_informacao';       $elemento[] = 'eh_apresentar_technical_service_details_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Detalhes';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações educacionais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_interactivity_type_cadastro_basico';       $elemento[] = 'eh_informar_educational_interactivity_type_cadastro_requisitar';       $elemento[] = 'cp_educational_interactivity_type_descricao';       $elemento[] = 'cp_educational_interactivity_type_nome';       $elemento[] = 'cp_educational_interactivity_type_informacao';       $elemento[] = 'eh_apresentar_educational_interactivity_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo de interatividade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_learning_resource_type_cadastro_basico';       $elemento[] = 'eh_informar_educational_learning_resource_type_cadastro_requisitar';       $elemento[] = 'cp_educational_learning_resource_type_descricao';       $elemento[] = 'cp_educational_learning_resource_type_nome';       $elemento[] = 'cp_educational_learning_resource_type_informacao';       $elemento[] = 'eh_apresentar_educational_learning_resource_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Recursos de aprendizagem';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_interactivity_level_cadastro_basico';       $elemento[] = 'eh_informar_educational_interactivity_level_cadastro_requisitar';       $elemento[] = 'cp_educational_interactivity_level_descricao';       $elemento[] = 'cp_educational_interactivity_level_nome';       $elemento[] = 'cp_educational_interactivity_level_informacao';       $elemento[] = 'eh_apresentar_educational_interactivity_level_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Nível de interatividade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_sem_antic_density_cadastro_basico';       $elemento[] = 'eh_informar_educational_sem_antic_density_cadastro_requisitar';       $elemento[] = 'cp_educational_sem_antic_density_descricao';       $elemento[] = 'cp_educational_sem_antic_density_nome';       $elemento[] = 'cp_educational_sem_antic_density_informacao';       $elemento[] = 'eh_apresentar_educational_sem_antic_density_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Densidade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_intended_end_user_role_cadastro_basico';       $elemento[] = 'eh_informar_educational_intended_end_user_role_cadastro_requisitar';       $elemento[] = 'cp_educational_intended_end_user_role_descricao';       $elemento[] = 'cp_educational_intended_end_user_role_nome';       $elemento[] = 'cp_educational_intended_end_user_role_informacao';       $elemento[] = 'eh_apresentar_educational_intended_end_user_role_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Função';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_context_cadastro_basico';       $elemento[] = 'eh_informar_educational_context_cadastro_requisitar';       $elemento[] = 'cp_educational_context_descricao';       $elemento[] = 'cp_educational_context_nome';       $elemento[] = 'cp_educational_context_informacao';       $elemento[] = 'eh_apresentar_educational_context_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Contexto';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_typical_age_range_cadastro_basico';       $elemento[] = 'eh_informar_educational_typical_age_range_cadastro_requisitar';       $elemento[] = 'cp_educational_typical_age_range_descricao';       $elemento[] = 'cp_educational_typical_age_range_nome';       $elemento[] = 'cp_educational_typical_age_range_informacao';       $elemento[] = 'eh_apresentar_educational_typical_age_range_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Faixa etária';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_difficulty_cadastro_basico';       $elemento[] = 'eh_informar_educational_difficulty_cadastro_requisitar';       $elemento[] = 'cp_educational_difficulty_descricao';       $elemento[] = 'cp_educational_difficulty_nome';       $elemento[] = 'cp_educational_difficulty_informacao';       $elemento[] = 'eh_apresentar_educational_difficulty_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Dificuldade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_typical_learning_time_cadastro_basico';       $elemento[] = 'eh_informar_educational_typical_learning_time_cadastro_requisitar';       $elemento[] = 'cp_educational_typical_learning_time_descricao';       $elemento[] = 'cp_educational_typical_learning_time_nome';       $elemento[] = 'cp_educational_typical_learning_time_informacao';       $elemento[] = 'eh_apresentar_educational_typical_learning_time_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tempo de aprendizagem';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_description_cadastro_basico';       $elemento[] = 'eh_informar_educational_description_cadastro_requisitar';       $elemento[] = 'cp_educational_description_descricao';       $elemento[] = 'cp_educational_description_nome';       $elemento[] = 'cp_educational_description_informacao';       $elemento[] = 'eh_apresentar_educational_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_language_cadastro_basico';       $elemento[] = 'eh_informar_educational_language_cadastro_requisitar';       $elemento[] = 'cp_educational_language_descricao';       $elemento[] = 'cp_educational_language_nome';       $elemento[] = 'cp_educational_language_informacao';       $elemento[] = 'eh_apresentar_educational_language_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Idioma';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_learning_content_type_cadastro_basico';       $elemento[] = 'eh_informar_educational_learning_content_type_cadastro_requisitar';       $elemento[] = 'cp_educational_learning_content_type_descricao';       $elemento[] = 'cp_educational_learning_content_type_nome';       $elemento[] = 'cp_educational_learning_content_type_informacao';       $elemento[] = 'eh_apresentar_educational_learning_content_type_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo de conteúdo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_interaction_cadastro_basico';       $elemento[] = 'eh_informar_educational_interaction_cadastro_requisitar';       $elemento[] = 'cp_educational_interaction_descricao';       $elemento[] = 'cp_educational_interaction_nome';       $elemento[] = 'cp_educational_interaction_informacao';       $elemento[] = 'eh_apresentar_educational_interaction_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Interação';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_educational_didatic_strategy_cadastro_basico';       $elemento[] = 'eh_informar_educational_didatic_strategy_cadastro_requisitar';       $elemento[] = 'cp_educational_didatic_strategy_descricao';       $elemento[] = 'cp_educational_didatic_strategy_nome';       $elemento[] = 'cp_educational_didatic_strategy_informacao';       $elemento[] = 'eh_apresentar_educational_didatic_strategy_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Estratégia didática';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações de direitos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_rights_cost_cadastro_basico';       $elemento[] = 'eh_informar_rights_cost_cadastro_requisitar';       $elemento[] = 'cp_rights_cost_descricao';       $elemento[] = 'cp_rights_cost_nome';       $elemento[] = 'cp_rights_cost_informacao';       $elemento[] = 'eh_apresentar_rights_cost_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Custo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_rights_copyright_and_other_restrictions_cadastro_basico';       $elemento[] = 'eh_informar_rights_copyright_and_other_restrictions_cadastro_requisitar';       $elemento[] = 'cp_rights_copyright_and_other_restrictions_descricao';       $elemento[] = 'cp_rights_copyright_and_other_restrictions_nome';       $elemento[] = 'cp_rights_copyright_and_other_restrictions_informacao';       $elemento[] = 'eh_apresentar_rights_copyright_and_other_restrictions_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Direitos de autor e outras restrições';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_rights_description_cadastro_basico';       $elemento[] = 'eh_informar_rights_description_cadastro_requisitar';       $elemento[] = 'cp_rights_description_descricao';       $elemento[] = 'cp_rights_description_nome';       $elemento[] = 'cp_rights_description_informacao';       $elemento[] = 'eh_apresentar_rights_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre relações';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_relation_kind_cadastro_basico';       $elemento[] = 'eh_informar_relation_kind_cadastro_requisitar';       $elemento[] = 'cp_relation_kind_descricao';       $elemento[] = 'cp_relation_kind_nome';       $elemento[] = 'cp_relation_kind_informacao';       $elemento[] = 'eh_apresentar_relation_kind_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Tipo';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_relation_resource_cadastro_basico';       $elemento[] = 'eh_informar_relation_resource_cadastro_requisitar';       $elemento[] = 'cp_relation_resource_descricao';       $elemento[] = 'cp_relation_resource_nome';       $elemento[] = 'cp_relation_resource_informacao';       $elemento[] = 'eh_apresentar_relation_resource_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Recurso';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações de anotações';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_annotation_entity_cadastro_basico';       $elemento[] = 'eh_informar_annotation_entity_cadastro_requisitar';       $elemento[] = 'cp_annotation_entity_descricao';       $elemento[] = 'cp_annotation_entity_nome';       $elemento[] = 'cp_annotation_entity_informacao';       $elemento[] = 'eh_apresentar_annotation_entity_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Entidade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_annotation_date_cadastro_basico';       $elemento[] = 'eh_informar_annotation_date_cadastro_requisitar';       $elemento[] = 'cp_annotation_date_descricao';       $elemento[] = 'cp_annotation_date_nome';       $elemento[] = 'cp_annotation_date_informacao';       $elemento[] = 'eh_apresentar_annotation_date_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Data';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_annotation_description_cadastro_basico';       $elemento[] = 'eh_informar_annotation_description_cadastro_requisitar';       $elemento[] = 'cp_annotation_description_descricao';       $elemento[] = 'cp_annotation_description_nome';       $elemento[] = 'cp_annotation_description_informacao';       $elemento[] = 'eh_apresentar_annotation_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre classificações';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_purpose_cadastro_basico';       $elemento[] = 'eh_informar_classification_purpose_cadastro_requisitar';       $elemento[] = 'cp_classification_purpose_descricao';       $elemento[] = 'cp_classification_purpose_nome';       $elemento[] = 'cp_classification_purpose_informacao';       $elemento[] = 'eh_apresentar_classification_purpose_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Propósito';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_taxon_path_cadastro_basico';       $elemento[] = 'eh_informar_classification_taxon_path_cadastro_requisitar';       $elemento[] = 'cp_classification_taxon_path_descricao';       $elemento[] = 'cp_classification_taxon_path_nome';       $elemento[] = 'cp_classification_taxon_path_informacao';       $elemento[] = 'eh_apresentar_classification_taxon_path_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Táxon Path';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_description_cadastro_basico';       $elemento[] = 'eh_informar_classification_description_cadastro_requisitar';       $elemento[] = 'cp_classification_description_descricao';       $elemento[] = 'cp_classification_description_nome';       $elemento[] = 'cp_classification_description_informacao';       $elemento[] = 'eh_apresentar_classification_description_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Descrição';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_classification_keyword_cadastro_basico';       $elemento[] = 'eh_informar_classification_keyword_cadastro_requisitar';       $elemento[] = 'cp_classification_keyword_descricao';       $elemento[] = 'cp_classification_keyword_nome';       $elemento[] = 'cp_classification_keyword_informacao';       $elemento[] = 'eh_apresentar_classification_keyword_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Palavras-chave';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações sobre acessibilidade';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_visual_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_visual_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_visual_descricao';       $elemento[] = 'cp_acessibility_has_visual_nome';       $elemento[] = 'cp_acessibility_has_visual_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_visual_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos visuais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_audititory_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_audititory_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_audititory_descricao';       $elemento[] = 'cp_acessibility_has_audititory_nome';       $elemento[] = 'cp_acessibility_has_audititory_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_audititory_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos sonoros';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_text_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_text_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_text_descricao';       $elemento[] = 'cp_acessibility_has_text_nome';       $elemento[] = 'cp_acessibility_has_text_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_text_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos textuais';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_has_tactible_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_has_tactible_cadastro_requisitar';       $elemento[] = 'cp_acessibility_has_tactible_descricao';       $elemento[] = 'cp_acessibility_has_tactible_nome';       $elemento[] = 'cp_acessibility_has_tactible_informacao';       $elemento[] = 'eh_apresentar_acessibility_has_tactible_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Elementos táteis';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_earl_statment_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_earl_statment_cadastro_requisitar';       $elemento[] = 'cp_acessibility_earl_statment_descricao';       $elemento[] = 'cp_acessibility_earl_statment_nome';       $elemento[] = 'cp_acessibility_earl_statment_informacao';       $elemento[] = 'eh_apresentar_acessibility_earl_statment_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Declaração';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_acessibility_equivalent_resource_cadastro_basico';       $elemento[] = 'eh_informar_acessibility_equivalent_resource_cadastro_requisitar';       $elemento[] = 'cp_acessibility_equivalent_resource_descricao';       $elemento[] = 'cp_acessibility_equivalent_resource_nome';       $elemento[] = 'cp_acessibility_equivalent_resource_informacao';       $elemento[] = 'eh_apresentar_acessibility_equivalent_resource_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Recursos equivalentes';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = '<hr>';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';       $elemento[] = '';        $titulo = 'Grupo: Informações do segmento';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_segment_information_table_segment_list_cadastro_basico';       $elemento[] = 'eh_informar_segment_information_table_segment_list_cadastro_requisitar';       $elemento[] = 'cp_segment_information_table_segment_list_descricao';       $elemento[] = 'cp_segment_information_table_segment_list_nome';       $elemento[] = 'cp_segment_information_table_segment_list_informacao';       $elemento[] = 'eh_apresentar_segment_information_table_segment_list_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Lista de segmentos';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;
      $elemento = array();       $elemento[] = 'eh_informar_segment_information_table_segmente_group_list_cadastro_basico';       $elemento[] = 'eh_informar_segment_information_table_segmente_group_list_cadastro_requisitar';       $elemento[] = 'cp_segment_information_table_segmente_group_list_descricao';       $elemento[] = 'cp_segment_information_table_segmente_group_list_nome';       $elemento[] = 'cp_segment_information_table_segmente_group_list_informacao';       $elemento[] = 'eh_apresentar_segment_information_table_segmente_group_list_retorno_pesquisa';       $elemento[] = '';        $titulo = 'Lista de grupo de segmento';        $comentario = '';      $este_elemento = array();         $este_elemento[] = $titulo;         $este_elemento[] = $comentario;          $este_elemento[] = $elemento;    $lista[] = $este_elemento;

        $plataforma = 'todas';
        foreach ($lista as $l) {
          $titulo = $l[0];
          if ($titulo != '<hr>') {
            $elemento = $l[2];
            if ($elemento[0] != '') {
              $variavel = $elemento[0];
              $tipo = 'vl_boolean';
              $valor = $util->limparVariavel($_POST[$variavel]);
              $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
            }
            if ($elemento[1] != '') {
              $variavel = $elemento[1];
              $tipo = 'vl_boolean';
              if (isset($_POST[$variavel])) {
                $valor = $util->limparVariavel($_POST[$variavel]);
              }
              $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
            }
            if ($elemento[3] != '') {
              $variavel = $elemento[3];
              $tipo = 'vl_string';
              $valor = $util->limparVariavel($_POST[$variavel]);
              $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
            }
            if ($elemento[2] != '') {
              $variavel = $elemento[2];
              $tipo = 'vl_string';
              $valor = $util->limparVariavel($_POST[$variavel]);
              $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
            }
            if ($elemento[4] != '') {
              $variavel = $elemento[4];
              $tipo = 'vl_string';
              $valor = $util->limparVariavel($_POST[$variavel]);
              $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
            }
            if ($elemento[5] != '') {
              $variavel = $elemento[5];
              $tipo = 'vl_boolean';
              $valor = $util->limparVariavel($_POST[$variavel]);
              $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
            }
            if ($elemento[6] != '') {
              $variavel = $elemento[6];
              $tipo = 'vl_inteiro';
              $valor = $util->limparVariavel($_POST[$variavel]);
              $this->editarConfiguracao($variavel, $tipo, $valor, $plataforma);
            }
          }
        }
      }
      echo "<p class=\"fontConteudoSucesso\">Configurações atualizadas!</p>\n";
    }

    public function retornaMensagemDadosArmazenadosLogin() {
      $dados = $this->selectDadosConfiguracao('ds_mensagem_armazenamento_dados_login_externo');
      return $dados['vl_texto'];
    }

    public function exibirMensagemRedesSociaisCadastroUsuario()  {
      $dados = $this->selectDadosConfiguracao('eh_mostrar_mensagem_cadastro_login_redes');
      return $dados['vl_boolean']; 
    }
    
    public function retornaMensagemRedesSociaisCadastroUsuario()  {
      $dados = $this->selectDadosConfiguracao('ds_mensagem_cadastro_login_redes');
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

    public function retornaSenhaEmailOrigemNotificacaoEmailEsqueceuSenha() {
      $dados = $this->selectDadosConfiguracao('ds_senha_email_origem_esqueceu_senha');
      return $dados['vl_string'];
    }

    public function retornaTituloNotificacaoEmailPedidoAlteracaoCategoria() {
      $dados = $this->selectDadosConfiguracao('ds_titulo_notificacao_pedido_alteracao_categoria');
      return $dados['vl_string'];
    }

    public function retornaEmailOrigemNotificacaoEmailPedidoAlteracaoCategoria() {
      $dados = $this->selectDadosConfiguracao('ds_email_origem_pedido_alteracao_categoria');
      return $dados['vl_string'];
    }

    public function retornaSenhaEmailOrigemNotificacaoEmailPedidoAlteracaoCategoria() {
      $dados = $this->selectDadosConfiguracao('ds_senha_email_origem_pedido_alteracao_categoria');
      return $dados['vl_string'];
    }

    public function retornaEmailOrigemDenunciaObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('ds_email_origem_denuncia_objeto_aprendizagem');
      return $dados['vl_string'];
    }

    public function retornaSenhaEmailOrigemDenunciaObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('ds_senha_email_origem_denuncia_objeto_aprendizagem');
      return $dados['vl_string'];
    }
    
    public function retornaEmailOrigemComentarioObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('ds_email_origem_comentario_objeto_aprendizagem');
      return $dados['vl_string'];
    }

    public function retornaSenhaEmailOrigemComentarioObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('ds_senha_email_origem_comentario_objeto_aprendizagem');
      return $dados['vl_string'];
    }

    public function retornaNumeroLimiteCaracteresRelacaoChamadaAreasConhecimentoCapaSite() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_chamada_areas_conhecimento_capa');
      return $dados['vl_inteiro']; 
    }                                             

    public function retornaNumeroItensPaginaExibicao() {
      $dados = $this->selectDadosConfiguracao('nr_noticias_pagina');
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

    public function retornaSugestaoLocaisAplicativos() {
      $dados = $this->selectDadosConfiguracao('ds_sugestao_locais_aplicativos');
      return $dados['vl_texto'];
    }

    public function retornaTamanhoLimiteUploadArquivos() {
      $dados = $this->selectDadosConfiguracao('ds_limite_tamanho_arquivos');
      return $dados['vl_decimal']; 
    }       

    public function retornaNumeroCaracteresResumoPerguntasComoUsarRestrito() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_resumo_pergunta_como_usar_restrito');
      return $dados['vl_inteiro'];
    }

    public function retornaNumeroCaracteresResumoRespostasComoUsarRestrito() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_resumo_resposta_como_usar_restrito');
      return $dados['vl_inteiro'];
    }

    public function retornaNumeroCaracteresResumoNoticiaCentroCapaPrincipal() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_resumo_noticia_centro_capa_principal');
      return $dados['vl_inteiro'];
    }
    public function retornaNumeroCaracteresResumoNoticiaCentroCapaSecundaria() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_resumo_noticia_centro_capa_secundaria');
      return $dados['vl_inteiro'];
    }
    public function retornaNumeroChamadasNoticiaisSecundariasCapa() {
      $dados = $this->selectDadosConfiguracao('nr_noticias_secundarias_capa');
      return $dados['vl_inteiro'];
    }

    public function retornaNumeroChamadasNoticiaisBlogLife() {
      $dados = $this->selectDadosConfiguracao('nr_noticias_blog_life');
      return $dados['vl_inteiro'];
    }

    public function ehPossivelSolicitarAlteracaoCategoriaUsuario() {
      $dados = $this->selectDadosConfiguracao('eh_possivel_alterar_categoria');
      return $dados['vl_boolean'];
    }

    public function retornaExplicacaoAlteracaoCategoriaUsuario() {
      $dados = $this->selectDadosConfiguracao('ds_explicacao_alteracao_categoria');
      return $dados['vl_texto'];
    }

    public function retornaTituloLogadoMeusObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('ds_titulo_logado_meus_objetos_aprendizagem');
      return $dados['vl_string'];
    }

    public function retornaTextoLogadoMeusObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('ds_texto_logado_meus_objetos_aprendizagem');
      return $dados['vl_texto'];
    }


             
    public function retornaLiberacaoAutomaticaObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('eh_liberacao_automatica');
      return $dados['vl_boolean']; 
    }  
              
//**********************PADRÃO*OBAA*********************************************

    public function retornaManterConfiguracoesOriginais() {
      $dados = $this->selectDadosConfiguracao('eh_manter_configuracoes_originais');
      return $dados['vl_boolean']; 
    }  

    public function retornaNumeroCaracteresResumoNomeObjetoAprendizagem() {
      $dados = $this->selectDadosConfiguracao('nr_caracteres_resumo_nome_oa');
      return $dados['vl_inteiro'];
    }

    public function retornaInformarGeneral($tipo) {
      return '1';
    }

    public function retornaInformarGeneralTitle($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_title_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_title_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_general_title_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_general_language_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_general_description_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_general_keyword_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_general_structure_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_general_agregation_level_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarGeneralImagem($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_imagem_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_general_imagem_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          return '1';
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarLyfeCycle($tipo) {
      return '1';
    }

    public function retornaInformarLyfeCycleVersion($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_version_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_lyfe_cycle_version_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_lyfe_cycle_version_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_lyfe_cycle_status_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_lyfe_cycle_contribute_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarMetaMetadata($tipo) {
      return '1';
    }

    public function retornaInformarMetaMetadataIdentifier($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_identifier_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_meta_metadata_identifier_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_meta_metadata_identifier_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_meta_metadata_contribute_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_meta_metadata_metadata_schema_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_meta_metadata_language_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_size_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_requirement_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_composite_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_installation_remarks_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_other_plataforms_requirements_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_duration_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_supported_plataform_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalPlataformSpecificFeatures($tipo) {
      return '1';
    }

    public function retornaInformarTechnicalPlataformSpecificFeaturesPlataformType($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_plataform_specific_features_plataform_type_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_plataform_specific_features_plataform_type_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_plataform_specific_features_specific_format_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_plataform_specific_features_specific_size_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_plataform_specific_features_specific_location_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_plataform_specific_features_specific_requeriments_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_plataform_specific_features_specific_instalation_remarks_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_plataform_specific_features_specific_other_plataform_requeriments_retorno');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarTechnicalService($tipo) {
      return '1';
    }

    public function retornaInformarTechnicalServiceName($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_name_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_technical_service_name_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_name_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_type_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_provides_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_essential_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_protocol_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_ontology_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_language_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_technical_service_details_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarEducational($tipo) {
      return '1';
    }

    public function retornaInformarEducationalInteractivityType($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_type_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_educational_interactivity_type_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_interactivity_type_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_learning_resource_type_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_interactivity_level_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_sem_antic_density_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_intended_end_user_role_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_context_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_typical_age_range_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_difficulty_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_typical_learning_time_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_description_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_language_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_learning_content_type_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_interaction_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_educational_didatic_strategy_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRights($tipo) {
      return '1';
    }

    public function retornaInformarRightsCost($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_cost_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_rights_cost_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_rights_cost_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_rights_copyright_and_other_restrictions_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_rights_description_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarRelation($tipo) {
      return '1';
    }

    public function retornaInformarRelationKind($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_kind_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_relation_kind_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_relation_kind_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_relation_resource_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAnnotation($tipo) {
      return '1';
    }

    public function retornaInformarAnnotationEntity($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_entity_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_annotation_entity_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_annotation_entity_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_annotation_date_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_annotation_description_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarClassification($tipo) {
      return '1';
    }

    public function retornaInformarClassificationPurpose($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_purpose_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_classification_purpose_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_classification_purpose_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_classification_taxon_path_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_classification_description_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_classification_keyword_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarAcessibility($tipo) {
      return '1';
    }

    public function retornaInformarAcessibilityHasVisual($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_visual_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_acessibility_has_visual_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_acessibility_has_visual_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_acessibility_has_audititory_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_acessibility_has_text_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_acessibility_has_tactible_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_acessibility_earl_statment_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_acessibility_equivalent_resource_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }

    public function retornaInformarSegmentInformationTable($tipo) {
      return '1';
    }

    public function retornaInformarSegmentInformationTableSegmentList($tipo) {
      if ($tipo == 'c') {
        return '1';
      } else {
        if ($tipo == 'b') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segment_list_cadastro_basico');
        } elseif ($tipo == 'i') {
          $dados = $this->selectDadosConfiguracao('eh_informar_segment_information_table_segment_list_cadastro_requisitar');
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_segment_information_table_segment_list_retorno_pesquisa');
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
        } elseif ($tipo == 'p') {
          $dados = $this->selectDadosConfiguracao('eh_apresentar_segment_information_table_segmente_group_list_retorno_pesquisa');
        }
        return $dados['vl_boolean'];
      }
    }


//************************DESCRICAO*CAMPOS*OBAA*********************************
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

    public function retornaDescricaoCampoNivelEducacional() {
      $dados = $this->selectDadosConfiguracao('cp_general_nivel_educacional_descricao');
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

    public function retornaDescricaoCampoGeneralImagem() {
      $dados = $this->selectDadosConfiguracao('cp_general_imagem_descricao');
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

    public function retornaDescricaoCampoAcessibilityElementosTateis() {
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

//************************NOME*CAMPOS*OBAA*********************************
    public function retornaNomeCampoGeneralIdentificador() {
      $dados = $this->selectDadosConfiguracao('cp_general_identifier_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralTitulo() {
      $dados = $this->selectDadosConfiguracao('cp_general_title_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_general_language_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralNome() {
      $dados = $this->selectDadosConfiguracao('cp_general_description_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralPalavraChave() {
      $dados = $this->selectDadosConfiguracao('cp_general_keyword_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoNivelEducacional() {
      $dados = $this->selectDadosConfiguracao('cp_general_nivel_educacional_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralCobertura() {
      $dados = $this->selectDadosConfiguracao('cp_general_coverage_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralEstrutura() {
      $dados = $this->selectDadosConfiguracao('cp_general_structure_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralNivelAgregacao() {
      $dados = $this->selectDadosConfiguracao('cp_general_agregation_level_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoGeneralImagem() {
      $dados = $this->selectDadosConfiguracao('cp_general_imagem_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoLyfeCycleVersao() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_version_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoLyfeCycleStatus() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_status_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoLyfeCycleContribuir() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_contribute_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoMetaMetadataIdentificador() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_identifier_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoMetaMetadataContribuicao() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_contribute_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoMetaMetadataEsquemaMetadados() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_metadata_schema_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoMetaMetadataIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_language_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalFormato() {
      $dados = $this->selectDadosConfiguracao('cp_technical_format_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalTamanho() {
      $dados = $this->selectDadosConfiguracao('cp_technical_size_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalLocalizacao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_location_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalRequerimentos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_requirement_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalComposicao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_composite_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalObservacoesInstalacao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_installation_remarks_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalOutrasPlataformasRequisitos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_other_plataforms_requirements_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalDuracao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_duration_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformaApoiado() {
      $dados = $this->selectDadosConfiguracao('cp_technical_supported_plataform_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformSpecificFeaturesPlataformaTipo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_plataform_type_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformSpecificFeaturesFormatoEspecifico() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_format_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformSpecificFeaturesEspecificaTamanho() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_size_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformSpecificFeaturesLocalizacaoEspecifica() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_location_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformSpecificFeaturesRequerimentosEspecificos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_requeriments_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformSpecificFeaturesObservacoesInstalacaoEspecificas() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_instalation_remarks_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalPlataformSpecificFeaturesOutrosRequisitosPlataformaEspecificos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_other_plataform_requeriments_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceNome() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_name_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceTipo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_type_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceFornece() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_provides_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceEssencial() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_essential_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceProtocolo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_protocol_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceOntologia() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_ontology_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_language_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoTechnicalServiceDetalhes() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_details_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalTipoInteratividade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interactivity_type_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalRecursosAprendizagemTipo() {
      $dados = $this->selectDadosConfiguracao('cp_educational_learning_resource_type_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalNivelInteratividade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interactivity_level_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalDensidade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_sem_antic_density_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalFuncaoDestinacaoUsuario() {
      $dados = $this->selectDadosConfiguracao('cp_educational_intended_end_user_role_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalContexto() {
      $dados = $this->selectDadosConfiguracao('cp_educational_context_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalFaixaTipicaIdade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_typical_age_range_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalDificuldade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_difficulty_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalTempoTipicoAprendizagem() {
      $dados = $this->selectDadosConfiguracao('cp_educational_typical_learning_time_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalNome() {
      $dados = $this->selectDadosConfiguracao('cp_educational_description_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_educational_language_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalAprenderTipoConteudo() {
      $dados = $this->selectDadosConfiguracao('cp_educational_learning_content_type_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalInteracao() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interaction_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoEducationalEstrategiaDidatica() {
      $dados = $this->selectDadosConfiguracao('cp_educational_didatic_strategy_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoRightsCusto() {
      $dados = $this->selectDadosConfiguracao('cp_rights_cost_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoRightsDireitosAutorOutrasRestricoes() {
      $dados = $this->selectDadosConfiguracao('cp_rights_copyright_and_other_restrictions_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoRightsNome() {
      $dados = $this->selectDadosConfiguracao('cp_rights_description_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoRelationTipo() {
      $dados = $this->selectDadosConfiguracao('cp_relation_kind_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoRelationRecurso() {
      $dados = $this->selectDadosConfiguracao('cp_relation_resource_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAnnotationEntidade() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_entity_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAnnotationData() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_date_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAnnotationNome() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_description_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoClassificationProposito() {
      $dados = $this->selectDadosConfiguracao('cp_classification_purpose_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoClassificationTaxonPath() {
      $dados = $this->selectDadosConfiguracao('cp_classification_taxon_path_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoClassificationNome() {
      $dados = $this->selectDadosConfiguracao('cp_classification_description_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoClassificationPalavraChave() {
      $dados = $this->selectDadosConfiguracao('cp_classification_keyword_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAcessibilityElementosVisuais() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_visual_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAcessibilityElementosSonoros() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_audititory_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAcessibilityElementosTexto() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_text_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAcessibilityElementosTateis() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_tactible_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAcessibilityPadraoEARL() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_earl_statment_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoAcessibilityRecursosEquivalentes() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_equivalent_resource_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoSegmentInformation_tableListaSegmentos() {
      $dados = $this->selectDadosConfiguracao('cp_segment_information_table_segment_list_nome');
      return $dados['vl_string'];
    }

    public function retornaNomeCampoSegmentInformation_tableListaSegmentoGrupo() {
      $dados = $this->selectDadosConfiguracao('cp_segment_information_table_segmente_group_list_nome');
      return $dados['vl_string'];
    }


//************************INFORMACAO*CAMPOS*OBAA*********************************
    public function retornaInformacaoCampoGeneralIdentificador() {
      $dados = $this->selectDadosConfiguracao('cp_general_identifier_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralTitulo() {
      $dados = $this->selectDadosConfiguracao('cp_general_title_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_general_language_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_general_description_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralPalavraChave() {
      $dados = $this->selectDadosConfiguracao('cp_general_keyword_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoNivelEducacional() {
      $dados = $this->selectDadosConfiguracao('cp_general_nivel_educacional_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralCobertura() {
      $dados = $this->selectDadosConfiguracao('cp_general_coverage_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralEstrutura() {
      $dados = $this->selectDadosConfiguracao('cp_general_structure_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralNivelAgregacao() {
      $dados = $this->selectDadosConfiguracao('cp_general_agregation_level_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoGeneralImagem() {
      $dados = $this->selectDadosConfiguracao('cp_general_imagem_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoLyfeCycleVersao() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_version_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoLyfeCycleStatus() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_status_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoLyfeCycleContribuir() {
      $dados = $this->selectDadosConfiguracao('cp_lyfe_cycle_contribute_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoMetaMetadataIdentificador() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_identifier_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoMetaMetadataContribuicao() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_contribute_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoMetaMetadataEsquemaMetadados() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_metadata_schema_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoMetaMetadataIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_meta_metadata_language_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalFormato() {
      $dados = $this->selectDadosConfiguracao('cp_technical_format_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalTamanho() {
      $dados = $this->selectDadosConfiguracao('cp_technical_size_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalLocalizacao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_location_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalRequerimentos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_requirement_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalComposicao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_composite_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalObservacoesInstalacao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_installation_remarks_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalOutrasPlataformasRequisitos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_other_plataforms_requirements_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalDuracao() {
      $dados = $this->selectDadosConfiguracao('cp_technical_duration_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformaApoiado() {
      $dados = $this->selectDadosConfiguracao('cp_technical_supported_plataform_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformSpecificFeaturesPlataformaTipo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_plataform_type_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformSpecificFeaturesFormatoEspecifico() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_format_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformSpecificFeaturesEspecificaTamanho() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_size_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformSpecificFeaturesLocalizacaoEspecifica() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_location_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformSpecificFeaturesRequerimentosEspecificos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_requeriments_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformSpecificFeaturesObservacoesInstalacaoEspecificas() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_instalation_remarks_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalPlataformSpecificFeaturesOutrosRequisitosPlataformaEspecificos() {
      $dados = $this->selectDadosConfiguracao('cp_technical_plataform_specific_features_specific_other_plataform_requeriments_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceNome() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_name_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceTipo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_type_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceFornece() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_provides_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceEssencial() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_essential_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceProtocolo() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_protocol_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceOntologia() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_ontology_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_language_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoTechnicalServiceDetalhes() {
      $dados = $this->selectDadosConfiguracao('cp_technical_service_details_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalTipoInteratividade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interactivity_type_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalRecursosAprendizagemTipo() {
      $dados = $this->selectDadosConfiguracao('cp_educational_learning_resource_type_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalNivelInteratividade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interactivity_level_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalDensidade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_sem_antic_density_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalFuncaoDestinacaoUsuario() {
      $dados = $this->selectDadosConfiguracao('cp_educational_intended_end_user_role_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalContexto() {
      $dados = $this->selectDadosConfiguracao('cp_educational_context_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalFaixaTipicaIdade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_typical_age_range_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalDificuldade() {
      $dados = $this->selectDadosConfiguracao('cp_educational_difficulty_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalTempoTipicoAprendizagem() {
      $dados = $this->selectDadosConfiguracao('cp_educational_typical_learning_time_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_educational_description_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalIdioma() {
      $dados = $this->selectDadosConfiguracao('cp_educational_language_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalAprenderTipoConteudo() {
      $dados = $this->selectDadosConfiguracao('cp_educational_learning_content_type_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalInteracao() {
      $dados = $this->selectDadosConfiguracao('cp_educational_interaction_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoEducationalEstrategiaDidatica() {
      $dados = $this->selectDadosConfiguracao('cp_educational_didatic_strategy_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoRightsCusto() {
      $dados = $this->selectDadosConfiguracao('cp_rights_cost_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoRightsDireitosAutorOutrasRestricoes() {
      $dados = $this->selectDadosConfiguracao('cp_rights_copyright_and_other_restrictions_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoRightsNome() {
      $dados = $this->selectDadosConfiguracao('cp_rights_description_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoRelationTipo() {
      $dados = $this->selectDadosConfiguracao('cp_relation_kind_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoRelationRecurso() {
      $dados = $this->selectDadosConfiguracao('cp_relation_resource_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAnnotationEntidade() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_entity_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAnnotationData() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_date_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAnnotationDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_annotation_description_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoClassificationProposito() {
      $dados = $this->selectDadosConfiguracao('cp_classification_purpose_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoClassificationTaxonPath() {
      $dados = $this->selectDadosConfiguracao('cp_classification_taxon_path_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoClassificationDescricao() {
      $dados = $this->selectDadosConfiguracao('cp_classification_description_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoClassificationPalavraChave() {
      $dados = $this->selectDadosConfiguracao('cp_classification_keyword_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAcessibilityElementosVisuais() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_visual_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAcessibilityElementosSonoros() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_audititory_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAcessibilityElementosTexto() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_text_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAcessibilityElementosTateis() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_has_tactible_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAcessibilityPadraoEARL() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_earl_statment_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoAcessibilityRecursosEquivalentes() {
      $dados = $this->selectDadosConfiguracao('cp_acessibility_equivalent_resource_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoSegmentInformation_tableListaSegmentos() {
      $dados = $this->selectDadosConfiguracao('cp_segment_information_table_segment_list_informacao');
      return $dados['vl_string'];
    }

    public function retornaInformacaoCampoSegmentInformation_tableListaSegmentoGrupo() {
      $dados = $this->selectDadosConfiguracao('cp_segment_information_table_segmente_group_list_informacao');
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

    public function retornaEhFiltrarNiveisEducacionais() {
      $dados = $this->selectDadosConfiguracao('eh_filtrar_nivel_educacional');
      return $dados['vl_boolean'];
    }

    public function retornaEhFiltrarStatusCicloVida() {
      $dados = $this->selectDadosConfiguracao('eh_filtrar_status');
      return $dados['vl_boolean'];
    }

    public function retornaEhFiltrarFormato() {
      $dados = $this->selectDadosConfiguracao('eh_filtrar_type');
      return $dados['vl_boolean'];
    }

//*******************CAPA*******************************************************
    public function retornaNumeroItensCapaTopo()  {
      $dados = $this->selectDadosConfiguracao('nr_itens_topo_capa');
      return $dados['vl_inteiro'];
    }

    public function retornaNumeroItensCapaMeio()  {
      $dados = $this->selectDadosConfiguracao('nr_itens_meio_capa');
      return $dados['vl_inteiro'];
    }

    public function retornaNumeroLinhasPaginaExibicaoObjetosAprendizagem() {
      $dados = $this->selectDadosConfiguracao('nr_linhas_pagina_exibicao_objetos_aprendizagem');
      return $dados['vl_inteiro'];
    }




//***************************OBJETOS APRENDIZAGEM*******************************
    public function retornaExplicacaoProcessoDenuncia() {
      $dados = $this->selectDadosConfiguracao('ds_explicacao_denucnia');
      return $dados['vl_texto'];
    }

    public function retornaExplicacaoProcessoComentario() {
      $dados = $this->selectDadosConfiguracao('ds_explicacao_comentario');
      return $dados['vl_texto'];
    }

    public function retornaNumeroLimiteDenunciasReversaoProprietario() {
      $dados = $this->selectDadosConfiguracao('nr_limite_denuncias');
      return $dados['vl_inteiro'];
    }
//******************************************************************************
    public function retornaMensagemBoasVindas() {
      $dados = $this->selectDadosConfiguracao('ds_mensagem_boas_vindas');
      return $dados['vl_texto'];
    }

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

    private function configuradorCampoInteiroValorado($variavel, $lista, $indice, $obrigatorio, $plataforma) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_inteiro';
      $valor = $dados[$tipo];
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, $tipo);
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaSeletor($nome, $variavel, $valor, $lista, '100');
      return $indice + 1;
    }

    private function configuradorCampoDecimal($variavel, $tamanho, $limite, $indice, $obrigatorio, $plataforma) {
/*
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

    private function configuradorCampoDecimalValorado($variavel, $lista, $indice, $obrigatorio, $plataforma) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $dados = $this->selectDadosConfiguracao($variavel);
      $nome = $dados['ds_texto_configuracao'];
      $tipo = 'vl_decimal';
      $valor = $dados[$tipo];
      $nome_variavel = 'variavel_'.$indice;                                     $util->campoHidden($nome_variavel, $variavel);
      $tipo_variavel = 'tipo_'.$indice;                                         $util->campoHidden($tipo_variavel, $tipo);
      $campo_plataforma = 'plataforma_'.$indice;                                $util->campoHidden($campo_plataforma, $plataforma);
      $util->linhaSeletor($nome, $variavel, $valor, $lista, '100');
      return $indice + 1;
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
      $util->linhaSeletor($nome, $variavel, $valor, $lista, '100');
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
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONFIGURAÇÕES");
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
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONFIGURAÇÕES");
      $saida = mysql_affected_rows();
      return $saida;         
    }
/*                                          
    public function editarConfiguracaoSemLog($variavel, $tipo, $valor) {
      $sql = "UPDATE life_configuracoes SET ".
             $tipo ." = \"$valor\" ".
             "WHERE ds_configuracao = \"$variavel\" ";
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONFIGURAÇÕES");
      $saida = mysql_affected_rows();
      return $saida;         
    }
*/
  }    
?>