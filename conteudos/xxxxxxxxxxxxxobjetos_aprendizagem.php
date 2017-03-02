<?php
  class ObjetoAprendizagem {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['od']))    {      $ordem = addslashes($_GET['od']);           } else {      $ordem = 'oa';        }
      if (isset($_GET['fc']))    {      $funcao = addslashes($_GET['fc']);          } else {      $funcao = '';         }
      if (isset($_GET['tp']))    {      $tipo = addslashes($_GET['tp']);            } else {      $tipo = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);   $_SESSION['life_oa_ativas'] = $ativas;     } elseif (isset($_SESSION['life_oa_ativas']))  {      $ativas = $_SESSION['life_oa_ativas'];    } else {      $ativas = '1';     }
      if (isset($_GET['od']))    {      $ordem = addslashes($_GET['od']);   $_SESSION['life_oa_ordem'] = $ordem;        } elseif (isset($_SESSION['life_oa_ordem']))   {      $ordem = $_SESSION['life_oa_ordem'];      } else {      $ordem = 'oa';     }

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 

      if ($codigo > 0) {
        require_once 'conteudos/pessoas.php';                                   $pes = new Pessoa();
        
        $dados = $this->selectDadosObjetoAprendizagem($codigo);
        $pessoa = $pes->selectDadosPessoaUsuario($dados['cd_usuario_proprietario']);

        if ($permissoes_usuario[21] == '0') {
          $cd_usuario = $_SESSION['life_codigo'];
          if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
            echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este Objeto de Aprendizagem!</p>\n";
            return false;
          }
        }      
      }
      
      if ($funcao == 'agrupamento') {
        echo "<h2>Objeto de Aprendizagem: ".$dados['nm_objeto_aprendizagem']."</h2>\n";
        echo "<h3>Responsável: ".$pessoa['nm_pessoa']."</h3>\n";                

        require_once 'conteudos/objetos_aprendizagem_agrupamentos.php';         $oaa = new ObjetoAprendizagemAgrupamento();
        $oaa->controleExibicao($secao, $subsecao, $item, $codigo, $acao, $dados['nm_objeto_aprendizagem'], $pessoa['nm_pessoa']);
      } else {
        switch ($acao) {
          case "":
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;

          case "pesquisar":
            require_once 'conteudos/objetos_aprendizagem_pesquisa.php';         $oap = new ObjetoAprendizagemPesquisa();
            $oap->listarOpcoesPesquisaSimples($secao, $subsecao, $item, $ativas, $ordem, true);
          break;

          case "pesq":
            require_once 'conteudos/objetos_aprendizagem_pesquisa.php';         $oap = new ObjetoAprendizagemPesquisa();
            $_SESSION['life_c_pesquisando'] = '1';
            $oap->pesquisarSimples($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;

          case "zpesquisa":
            unset($_SESSION['life_c_pesquisando']);
            unset($_SESSION['life_c_termo']);
            unset($_SESSION['life_c_campos']);
            unset($_SESSION['life_c_eh_proprietario']);
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;

          case "cadastrar":
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas;
            $this->montarFormularioCadastro($link, $tipo);
          break;
          
          case "editar":
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas;
            $this->montarFormularioEdicao($link, $codigo, $tipo);
          break;
        
          case "salvar":
            if (isset($_SESSION['life_edicao'])) {
              $this->salvarCadastroAlteracao();
              unset($_SESSION['life_edicao']);
            } 
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;  
      
          case "status":
            $this->alterarStatus($codigo);
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;
        }             
      }
    }
                                                        
    private function listarAcoes($secao, $subsecao, $item, $ativas, $ordem) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $opcoes= array();
      $id = 1;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=1";                               $opcao['descricao']= "Ativos";                                                               $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=0";                               $opcao['descricao']= "Inativos";                                                             $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=2";                               $opcao['descricao']= "Ativos/Inativos";                                                      $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "";                                                                                                           $opcao['descricao']= "-----------------------------------------------------------";          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=oa&at=".$ativas;                                $opcao['descricao']= "Ordenar por Objetos de Aprendizagem";                                  $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=pe&at=".$ativas;                                $opcao['descricao']= "Ordenar por Responsáveis";                                             $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "";                                                                                                           $opcao['descricao']= "-----------------------------------------------------------";          $opcoes[]= $opcao;

      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "";                                                                                              $opcao['descricao']= "-----------------------------------------------------------";                       $opcoes[]= $opcao;

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }

      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=pesquisar\"><img src=\"icones/pesquisar.png\" alt=\"Pesquisar Objeto(s) de Aprendizagem\" title=\"Pesquisar Objeto(s) de Aprendizagem\" border=\"0\"></a> \n";
      if (isset($_SESSION['life_c_pesquisando'])) {
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=zpesquisa\"><img src=\"icones/limpar_pesquisa.png\" alt=\"Limpar Pesquisa\" title=\"Limpar Pesquisa\" border=\"0\"></a> \n";
      }
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&tp=b&acao=cadastrar\"><img src=\"icones/novo_oa.png\" alt=\"Novo Objeto de Aprendizagem\" title=\"Novo Objeto de Aprendizagem\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">-----------------------------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }


    private function listarItens($secao, $subsecao, $item, $ativas, $ordem) {
      require_once 'conteudos/objetos_aprendizagem_pesquisa.php';               $oap = new ObjetoAprendizagemPesquisa();
      
      $itens = $this->selectObjetosAprendizagem($ativas, $ordem, '', '');    

      $mensagem = "Objetos de Aprendizagem ";
      if ($ativas == 1)   {        $mensagem.= "Ativos";      } elseif ($ativas == 0) {        $mensagem.= "Inativos";      }
      echo "<h2>".$mensagem."</h2>\n";      

      $oap->retornaDetalhesPesquisa();                  

      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Objeto de Aprendizagem:</td>\n";
      echo "      <td class=\"celConteudo\" rowspan=\"2\">Ações:</td>\n";
      echo "    </tr>\n";
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Responsável:</td>\n";
      echo "    </tr>\n";                                              

      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_objeto_aprendizagem']."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"2\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharObjetoAprendizagem($it['cd_objeto_aprendizagem']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&tp=b&&acao=editar\"><img src=\"icones/editar_basico.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Básico\" title=\"Editar Objeto de Aprendizagem - Cadastro Básico\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&tp=i&&acao=editar\"><img src=\"icones/editar_intermediario.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" title=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&tp=c&&acao=editar\"><img src=\"icones/editar_completo.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Completo\" title=\"Editar Objeto de Aprendizagem - Cadastro Completo\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\"border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\"border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&fc=agrupamento\"><img src=\"icones/conteudos.png\" alt=\"Agrupamento de Objetos de Aprendizagem\" title=\"Agrupamento de Objetos de Aprendizagem\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_pessoa']."</td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
    }

    private function detalharObjetoAprendizagem($cd_objeto_aprendizagem) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
     
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data da Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }

    private function montarFormularioCadastro($link, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
      $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
      $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
      $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
      $eh_informar_educational = $conf->retornaInformarEducational($tipo);
      $eh_informar_rights = $conf->retornaInformarRights($tipo);
      $eh_informar_relation = $conf->retornaInformarRelation($tipo);
      $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
      $eh_informar_classification = $conf->retornaInformarClassification($tipo);
      $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
      $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);

      $cd_objeto_aprendizagem = "";
      $nm_objeto_aprendizagem = "";
      $cd_general = "";
      $cd_lyfe_cycle = "";
      $cd_meta_metadata = "";
      $cd_technical = "";
      $cd_educational = "";
      $cd_rights = "";
      $cd_relation = "";
      $cd_annotation = "";
      $cd_classification = "";
      $cd_acessibility = "";
      $cd_segment_information_table = "";
      $eh_ativo = "1";
      
      $eh_liberado = $conf->retornaLiberacaoAutomaticaObjetosAprendizagem();
      $cd_usuario_proprietario = $_SESSION['life_codigo'];
      $_SESSION['life_edicao']= 1;

      echo "  <h2>Cadastro de Objetos de Aprendizagem</h2>\n";             
      $this->imprimeFormularioCadastro($link, $tipo, $cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario);
    }

    private function montarFormularioEdicao($link, $cd_objeto_aprendizagem, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);

      if ($permissoes_usuario[21] == '0') {
        $cd_usuario = $_SESSION['life_codigo'];
        if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
          echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este Objeto de Aprendizagem!</p>\n";
          return false;
        }
      }       

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();
      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_general = $dados['eh_informar_general'];
        $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
        $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
        $eh_informar_technical = '1'; //$dados['eh_informar_technical'];
        $eh_informar_educational = $dados['eh_informar_educational'];
        $eh_informar_rights = $dados['eh_informar_rights'];
        $eh_informar_relation = $dados['eh_informar_relation'];
        $eh_informar_annotation = $dados['eh_informar_annotation'];
        $eh_informar_classification = $dados['eh_informar_classification'];
        $eh_informar_acessibility = $dados['eh_informar_acessibility'];
        $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];      
      } else {
        $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
        $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
        $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
        $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
        $eh_informar_educational = $conf->retornaInformarEducational($tipo);
        $eh_informar_rights = $conf->retornaInformarRights($tipo);
        $eh_informar_relation = $conf->retornaInformarRelation($tipo);
        $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
        $eh_informar_classification = $conf->retornaInformarClassification($tipo);
        $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
        $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);
      }

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $cd_general = $dados['cd_general'];
      $cd_lyfe_cycle = $dados['cd_lyfe_cycle'];
      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $cd_technical = $dados['cd_technical'];
      $cd_educational = $dados['cd_educational'];
      $cd_rights = $dados['cd_rights'];
      $cd_relation = $dados['cd_relation'];
      $cd_annotation = $dados['cd_annotation'];
      $cd_classification = $dados['cd_classification'];
      $cd_acessibility = $dados['cd_acessibility'];
      $cd_segment_information_table = $dados['cd_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];

      $_SESSION['life_edicao']= 1;      
      echo "  <h2>Edição de Objeto de Aprendizagem</h2>\n";
      $this->imprimeFormularioCadastro($link, $tipo, $cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario);
    }                         

    private function imprimeFormularioCadastro($link, $tipo, $cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_cadastro_objeto_aprendizagem.js";                                                                           
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo'].$link."&acao=salvar\" enctype=\"multipart/form-data\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";

      $util->campoHidden('cd_objeto_aprendizagem', $cd_objeto_aprendizagem);
      $util->campoHidden('eh_informar_general', $eh_informar_general);
      $util->campoHidden('eh_informar_lyfe_cycle', $eh_informar_lyfe_cycle);
      $util->campoHidden('eh_informar_meta_metadata', $eh_informar_meta_metadata);
      $util->campoHidden('eh_informar_technical', $eh_informar_technical);
      $util->campoHidden('eh_informar_educational', $eh_informar_educational);
      $util->campoHidden('eh_informar_rights', $eh_informar_rights);
      $util->campoHidden('eh_informar_relation', $eh_informar_relation);
      $util->campoHidden('eh_informar_annotation', $eh_informar_annotation);
      $util->campoHidden('eh_informar_classification', $eh_informar_classification);
      $util->campoHidden('eh_informar_acessibility', $eh_informar_acessibility);
      $util->campoHidden('eh_informar_segment_information_table', $eh_informar_segment_information_table);
      $util->campoHidden('eh_ativo', $eh_ativo);
      $util->campoHidden('eh_liberado', $eh_liberado);
      $util->campoHidden('cd_usuario_proprietario', $cd_usuario_proprietario);

      $eh_obrigatorio_general = '1'; //$conf->retornaInformarGeneral('b');
      $eh_obrigatorio_lyfe_cycle = $conf->retornaInformarLyfeCycle('b');
      $eh_obrigatorio_meta_metadata = $conf->retornaInformarMetaMetadata('b');
      $eh_obrigatorio_technical = '1'; //$conf->retornaInformarTechnical('b');
      $eh_obrigatorio_educational = $conf->retornaInformarEducational('b');
      $eh_obrigatorio_rights = $conf->retornaInformarRights('b');
      $eh_obrigatorio_relation = $conf->retornaInformarRelation('b');
      $eh_obrigatorio_annotation = $conf->retornaInformarAnnotation('b');
      $eh_obrigatorio_classification = $conf->retornaInformarClassification('b');
      $eh_obrigatorio_acessibility = $conf->retornaInformarAcessibility('b');
      $eh_obrigatorio_segment_information_table = $conf->retornaInformarSegmentInformationTable('b');
      $util->campoHidden('eh_obrigatorio_general', $eh_obrigatorio_general);
      $util->campoHidden('eh_obrigatorio_lyfe_cycle', $eh_obrigatorio_lyfe_cycle);
      $util->campoHidden('eh_obrigatorio_meta_metadata', $eh_obrigatorio_meta_metadata);
      $util->campoHidden('eh_obrigatorio_technical', '1');
      $util->campoHidden('eh_obrigatorio_educational', $eh_obrigatorio_educational);
      $util->campoHidden('eh_obrigatorio_rights', $eh_obrigatorio_rights);
      $util->campoHidden('eh_obrigatorio_relation', $eh_obrigatorio_relation);
      $util->campoHidden('eh_obrigatorio_annotation', $eh_obrigatorio_annotation);
      $util->campoHidden('eh_obrigatorio_classification', $eh_obrigatorio_classification);
      $util->campoHidden('eh_obrigatorio_acessibility', $eh_obrigatorio_acessibility);
      $util->campoHidden('eh_obrigatorio_segment_information_table', $eh_obrigatorio_segment_information_table);

      require_once 'conteudos/objetos_aprendizagem_general.php';                $oa_gen = new ObjetoAprendizagemGeneral();
      $oa_gen->retornaFormularioCadastroEdicao($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';             $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
      $oa_lyf_cyc->retornaFormularioCadastroEdicao($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';          $oa_mtd = new ObjetoAprendizagemMetaMetadata();
      $oa_mtd->retornaFormularioCadastroEdicao($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_technical.php';              $oa_tec = new ObjetoAprendizagemTechnical();
      $oa_tec->retornaFormularioCadastroEdicao($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_educational.php';            $oa_edu = new ObjetoAprendizagemEducational();
      $oa_edu->retornaFormularioCadastroEdicao($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_rights.php';                 $oa_rig = new ObjetoAprendizagemRights();
      $oa_rig->retornaFormularioCadastroEdicao($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_relation.php';               $oa_rel = new ObjetoAprendizagemRelation();
      $oa_rel->retornaFormularioCadastroEdicao($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_annotation.php';             $oa_ann = new ObjetoAprendizagemAnnotation();
      $oa_ann->retornaFormularioCadastroEdicao($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_classification.php';         $oa_cla = new ObjetoAprendizagemClassification();
      $oa_cla->retornaFormularioCadastroEdicao($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_acessibility.php';           $oa_ace = new ObjetoAprendizagemAcessibility();
      $oa_ace->retornaFormularioCadastroEdicao($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
      $oa_sit->retornaFormularioCadastroEdicao($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_objeto_aprendizagem'); 
    }
                         

    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/objetos_aprendizagem_general.php';                $oa_gen = new ObjetoAprendizagemGeneral();
      require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';             $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
      require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';          $oa_mtd = new ObjetoAprendizagemMetaMetadata();
      require_once 'conteudos/objetos_aprendizagem_technical.php';              $oa_tec = new ObjetoAprendizagemTechnical();
      require_once 'conteudos/objetos_aprendizagem_educational.php';            $oa_edu = new ObjetoAprendizagemEducational();
      require_once 'conteudos/objetos_aprendizagem_rights.php';                 $oa_rig = new ObjetoAprendizagemRights();
      require_once 'conteudos/objetos_aprendizagem_relation.php';               $oa_rel = new ObjetoAprendizagemRelation();
      require_once 'conteudos/objetos_aprendizagem_annotation.php';             $oa_ann = new ObjetoAprendizagemAnnotation();
      require_once 'conteudos/objetos_aprendizagem_classification.php';         $oa_cla = new ObjetoAprendizagemClassification();
      require_once 'conteudos/objetos_aprendizagem_acessibility.php';           $oa_ace = new ObjetoAprendizagemAcessibility();
      require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();

      if (isset($_SESSION['life_agrupador_termos_cadastro'])) {
        unset($_SESSION['life_agrupador_termos_cadastro']);
      }
      $_SESSION['life_agrupador_termos_cadastro'] = '';

      $cd_objeto_aprendizagem = addslashes($_POST['cd_objeto_aprendizagem']);
      $nm_objeto_aprendizagem = $util->limparVariavel($_POST['ds_general_title']);
      $cd_general = $oa_gen->salvarCadastroAlteracao(); 
      $eh_informar_general = addslashes($_POST['eh_informar_general']);
      $cd_lyfe_cycle = $oa_lyf_cyc->salvarCadastroAlteracao(); 
      $eh_informar_lyfe_cycle = addslashes($_POST['eh_informar_lyfe_cycle']);
      $cd_meta_metadata = $oa_mtd->salvarCadastroAlteracao(); 
      $eh_informar_meta_metadata = addslashes($_POST['eh_informar_meta_metadata']);
      $cd_technical = $oa_tec->salvarCadastroAlteracao(); 
      $eh_informar_technical = addslashes($_POST['eh_informar_technical']);
      $cd_educational = $oa_edu->salvarCadastroAlteracao(); 
      $eh_informar_educational = addslashes($_POST['eh_informar_educational']);
      $cd_rights = $oa_rig->salvarCadastroAlteracao(); 
      $eh_informar_rights = addslashes($_POST['eh_informar_rights']);
      $cd_relation = $oa_rel->salvarCadastroAlteracao(); 
      $eh_informar_relation = addslashes($_POST['eh_informar_relation']);
      $cd_annotation = $oa_ann->salvarCadastroAlteracao(); 
      $eh_informar_annotation = addslashes($_POST['eh_informar_annotation']);
      $cd_classification = $oa_cla->salvarCadastroAlteracao(); 
      $eh_informar_classification = addslashes($_POST['eh_informar_classification']);
      $cd_acessibility = $oa_ace->salvarCadastroAlteracao(); 
      $eh_informar_acessibility = addslashes($_POST['eh_informar_acessibility']);
      $cd_segment_information_table = $oa_sit->salvarCadastroAlteracao(); 
      $eh_informar_segment_information_table = addslashes($_POST['eh_informar_segment_information_table']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $eh_liberado = addslashes($_POST['eh_liberado']);
      $cd_usuario_proprietario = addslashes($_POST['cd_usuario_proprietario']);

      $ds_informacoes_o_a = addslashes($_SESSION['life_agrupador_termos_cadastro']);
      unset($_SESSION['life_agrupador_termos_cadastro']);

      $lk_seo = $util->retornaLinkSEO($nm_objeto_aprendizagem, 'life_objetos_aprendizagem', 'lk_seo', '250', $cd_objeto_aprendizagem);
      
      if ($cd_objeto_aprendizagem > 0) {
        $cd_objeto_aprendizagem = $this->alterarObjetoAprendizagem($cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $eh_informar_general, $eh_informar_lyfe_cycle, $eh_informar_meta_metadata, $eh_informar_technical, $eh_informar_educational, $eh_informar_rights, $eh_informar_relation, $eh_informar_annotation, $eh_informar_classification, $eh_informar_acessibility, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a);
        if (($cd_objeto_aprendizagem > 0) || ($cd_general > 0) || ($cd_lyfe_cycle > 0) || ($cd_meta_metadata > 0) || ($cd_technical > 0) || ($cd_educational > 0) || ($cd_rights > 0) || ($cd_relation > 0) || ($cd_annotation > 0) || ($cd_classification > 0) || ($cd_acessibility > 0) || ($cd_segment_information_table > 0)) {
          echo "<p class=\"fontConteudoSucesso\">Objeto de Aprendizagem editado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Objeto de Aprendizagem, ou nenhuma informação alterada!</p>\n";
        }  
      } else {
        if (($cd_general > 0) && ($cd_lyfe_cycle > 0) && ($cd_meta_metadata > 0) && ($cd_technical > 0) && ($cd_educational > 0) && ($cd_rights > 0) && ($cd_relation > 0) && ($cd_annotation > 0) && ($cd_classification > 0) && ($cd_acessibility > 0) && ($cd_segment_information_table > 0)) {
          if ($this->inserirObjetoAprendizagem($nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a)) {
            echo "<p class=\"fontConteudoSucesso\">Objeto de Aprendizagem cadastrado com sucesso!</p>\n";
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Objeto de Aprendizagem!</p>\n";
          }
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Objeto de Aprendizagem!</p>\n";
        }
      }
      
    } 

    private function alterarStatus($cd_objeto_aprendizagem) {
      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);

      if ($permissoes_usuario[21] == '0') {
        $cd_usuario = $_SESSION['life_codigo'];
        if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
          echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este Objeto de Aprendizagem!</p>\n";
          return false;
        }
      }       

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $eh_informar_general = $dados['eh_informar_general'];
      $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
      $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
      $eh_informar_technical = $dados['eh_informar_technical'];
      $eh_informar_educational = $dados['eh_informar_educational'];
      $eh_informar_rights = $dados['eh_informar_rights'];
      $eh_informar_relation = $dados['eh_informar_relation'];
      $eh_informar_annotation = $dados['eh_informar_annotation'];
      $eh_informar_classification = $dados['eh_informar_classification'];
      $eh_informar_acessibility = $dados['eh_informar_acessibility'];
      $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];
      $lk_seo = $dados['lk_seo'];
      $ds_informacoes_o_a = $dados['ds_informacoes_o_a'];

      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }

      if ($this->alterarObjetoAprendizagem($cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $eh_informar_general, $eh_informar_lyfe_cycle, $eh_informar_meta_metadata, $eh_informar_technical, $eh_informar_educational, $eh_informar_rights, $eh_informar_relation, $eh_informar_annotation, $eh_informar_classification, $eh_informar_acessibility, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Objeto de Aprendizagem alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status do Objeto de Aprendizagem!</p>\n";
      }                                                                                               
    }

    public function detalharObjetoAprendizagemBasico($cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
      
      $tipo = 'b';

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_general = $dados['eh_informar_general'];
        $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
        $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
        $eh_informar_technical = '1'; //$dados['eh_informar_technical'];
        $eh_informar_educational = $dados['eh_informar_educational'];
        $eh_informar_rights = $dados['eh_informar_rights'];
        $eh_informar_relation = $dados['eh_informar_relation'];
        $eh_informar_annotation = $dados['eh_informar_annotation'];
        $eh_informar_classification = $dados['eh_informar_classification'];
        $eh_informar_acessibility = $dados['eh_informar_acessibility'];
        $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];      
      } else {
        $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
        $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
        $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
        $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
        $eh_informar_educational = $conf->retornaInformarEducational($tipo);
        $eh_informar_rights = $conf->retornaInformarRights($tipo);
        $eh_informar_relation = $conf->retornaInformarRelation($tipo);
        $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
        $eh_informar_classification = $conf->retornaInformarClassification($tipo);
        $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
        $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);
      }

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $cd_general = $dados['cd_general'];
      $cd_lyfe_cycle = $dados['cd_lyfe_cycle'];
      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $cd_technical = $dados['cd_technical'];
      $cd_educational = $dados['cd_educational'];
      $cd_rights = $dados['cd_rights'];
      $cd_relation = $dados['cd_relation'];
      $cd_annotation = $dados['cd_annotation'];
      $cd_classification = $dados['cd_classification'];
      $cd_acessibility = $dados['cd_acessibility'];
      $cd_segment_information_table = $dados['cd_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];

      $retorno = '<p class=\"fontDetalhar\">';
      $retorno.= 'Objeto de Aprendizagem: '.$nm_objeto_aprendizagem.'<br />';
      $retorno.= '</p>';
      
      if ($eh_informar_general == '1') {
        require_once 'conteudos/objetos_aprendizagem_general.php';              $oa_gen = new ObjetoAprendizagemGeneral();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Gerais</b><br />';      
        $retorno.= $oa_gen->imprimeDados($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_lyfe_cycle == '1') {
        require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';           $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Ciclo de Vida</b><br />';
        $retorno.= $oa_lyf_cyc->imprimeDados($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_meta_metadata == '1') {
        require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';        $oa_mtd = new ObjetoAprendizagemMetaMetadata();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações de Metadados</b><br />';
        $retorno.= $oa_mtd->imprimeDados($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_technical == '1') {
        require_once 'conteudos/objetos_aprendizagem_technical.php';            $oa_tec = new ObjetoAprendizagemTechnical();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Técnicas</b><br />';  
        $retorno.= $oa_tec->imprimeDados($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo, false);
        $retorno.= '</p>';
      }

      if ($eh_informar_educational == '1') {
        require_once 'conteudos/objetos_aprendizagem_educational.php';          $oa_edu = new ObjetoAprendizagemEducational();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Educacionais</b><br />';
        $retorno.= $oa_edu->imprimeDados($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_rights == '1') {
        require_once 'conteudos/objetos_aprendizagem_rights.php';               $oa_rig = new ObjetoAprendizagemRights();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Direitos Autorais</b><br />';
        $retorno.= $oa_rig->imprimeDados($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_relation == '1') {
        require_once 'conteudos/objetos_aprendizagem_relation.php';             $oa_rel = new ObjetoAprendizagemRelation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Relação</b><br />';
        $retorno.= $oa_rel->imprimeDados($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_annotation == '1') {
        require_once 'conteudos/objetos_aprendizagem_annotation.php';           $oa_ann = new ObjetoAprendizagemAnnotation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Anotações</b><br />';
        $retorno.= $oa_ann->imprimeDados($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_classification == '1') {
        require_once 'conteudos/objetos_aprendizagem_classification.php';       $oa_cla = new ObjetoAprendizagemClassification();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Classificação</b><br />';
        $retorno.= $oa_cla->imprimeDados($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_acessibility == '1') {
        require_once 'conteudos/objetos_aprendizagem_acessibility.php';         $oa_ace = new ObjetoAprendizagemAcessibility();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Acessibilidade</b><br />';
        $retorno.= $oa_ace->imprimeDados($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_segment_information_table == '1') {
        require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Segmentos</b><br />';
        $retorno.= $oa_sit->imprimeDados($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }
      return $retorno;
    }
    
    public function retornaInformacoesCompletasObjetoAprendizagem($cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
      
      $tipo = 'c';

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_general = $dados['eh_informar_general'];
        $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
        $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
        $eh_informar_technical = '1'; //$dados['eh_informar_technical'];
        $eh_informar_educational = $dados['eh_informar_educational'];
        $eh_informar_rights = $dados['eh_informar_rights'];
        $eh_informar_relation = $dados['eh_informar_relation'];
        $eh_informar_annotation = $dados['eh_informar_annotation'];
        $eh_informar_classification = $dados['eh_informar_classification'];
        $eh_informar_acessibility = $dados['eh_informar_acessibility'];
        $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];      
      } else {
        $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
        $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
        $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
        $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
        $eh_informar_educational = $conf->retornaInformarEducational($tipo);
        $eh_informar_rights = $conf->retornaInformarRights($tipo);
        $eh_informar_relation = $conf->retornaInformarRelation($tipo);
        $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
        $eh_informar_classification = $conf->retornaInformarClassification($tipo);
        $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
        $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);
      }

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $cd_general = $dados['cd_general'];
      $cd_lyfe_cycle = $dados['cd_lyfe_cycle'];
      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $cd_technical = $dados['cd_technical'];
      $cd_educational = $dados['cd_educational'];
      $cd_rights = $dados['cd_rights'];
      $cd_relation = $dados['cd_relation'];
      $cd_annotation = $dados['cd_annotation'];
      $cd_classification = $dados['cd_classification'];
      $cd_acessibility = $dados['cd_acessibility'];
      $cd_segment_information_table = $dados['cd_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];

      $retorno = '<p class=\"fontDetalhar\">';
      $retorno.= 'Objeto de Aprendizagem: '.$nm_objeto_aprendizagem.'<br />';
      $retorno.= '</p>';
      
      if ($eh_informar_general == '1') {
        require_once 'conteudos/objetos_aprendizagem_general.php';              $oa_gen = new ObjetoAprendizagemGeneral();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Gerais</b><br />';      
        $retorno.= $oa_gen->imprimeDados($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_lyfe_cycle == '1') {
        require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';           $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Ciclo de Vida</b><br />';
        $retorno.= $oa_lyf_cyc->imprimeDados($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_meta_metadata == '1') {
        require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';        $oa_mtd = new ObjetoAprendizagemMetaMetadata();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações de Metadados</b><br />';
        $retorno.= $oa_mtd->imprimeDados($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_technical == '1') {
        require_once 'conteudos/objetos_aprendizagem_technical.php';            $oa_tec = new ObjetoAprendizagemTechnical();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Técnicas</b><br />';  
        $retorno.= $oa_tec->imprimeDados($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo, false);
        $retorno.= '</p>';
      }

      if ($eh_informar_educational == '1') {
        require_once 'conteudos/objetos_aprendizagem_educational.php';          $oa_edu = new ObjetoAprendizagemEducational();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Educacionais</b><br />';
        $retorno.= $oa_edu->imprimeDados($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_rights == '1') {
        require_once 'conteudos/objetos_aprendizagem_rights.php';               $oa_rig = new ObjetoAprendizagemRights();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Direitos Autorais</b><br />';
        $retorno.= $oa_rig->imprimeDados($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_relation == '1') {
        require_once 'conteudos/objetos_aprendizagem_relation.php';             $oa_rel = new ObjetoAprendizagemRelation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Relação</b><br />';
        $retorno.= $oa_rel->imprimeDados($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_annotation == '1') {
        require_once 'conteudos/objetos_aprendizagem_annotation.php';           $oa_ann = new ObjetoAprendizagemAnnotation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Anotações</b><br />';
        $retorno.= $oa_ann->imprimeDados($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_classification == '1') {
        require_once 'conteudos/objetos_aprendizagem_classification.php';       $oa_cla = new ObjetoAprendizagemClassification();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Classificação</b><br />';
        $retorno.= $oa_cla->imprimeDados($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_acessibility == '1') {
        require_once 'conteudos/objetos_aprendizagem_acessibility.php';         $oa_ace = new ObjetoAprendizagemAcessibility();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Acessibilidade</b><br />';
        $retorno.= $oa_ace->imprimeDados($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_segment_information_table == '1') {
        require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Segmentos</b><br />';
        $retorno.= $oa_sit->imprimeDados($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }
      return $retorno;    
    }
//***************EXIBICAO PUBLICA***********************************************
/*
    public function retornaObjetosAprendizagemCapa($pagina, $lista_paginas) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $ordem = $conf->retornaOrdemObjetosAprendizagemCapa();
      $this->retornaRelacaoObjetosAprendizagem('', '', '', $ordem, '1');
    }



    public function retornaRelacaoObjetosAprendizagem($categoria, $profissao, $professor, $ordem, $pagina) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/cursos_categoria.php';                            $cur_cat = new CursoCategoria();
      require_once 'conteudos/profissoes.php';                                  $pro = new Profissao();
      require_once 'conteudos/professores.php';                                 $prf = new Professor();

      $filtro = '';
      if ($categoria != '')       {        $dados = $cur_cat->selectDadosCategoria($categoria);        $filtro = $dados['lk_seo']."/";
      } elseif ($profissao != '') {        $dados = $pro->selectDadosProfissao($profissao);            $filtro = $dados['lk_seo']."/";
      } elseif ($professor != '') {        $dados = $prf->selectDadosProfessor($professor);            $filtro = $dados['lk_seo']."/";    
      }
      if ($ordem == 'mais_recentes')       {      $nr_ordem = "1";      $titulo = "Objetos de Aprendizagem mais recentes";      }
      if ($ordem == 'mais_populares')      {      $nr_ordem = "2";      $titulo = "Objetos de Aprendizagem mais populares";     }

      echo "  <h1>".$titulo."</h1>\n";

      $quantidade = $conf->retornaNumeroObjetosAprendizagemRelacao();
      $inicio = ($quantidade * $pagina) - $quantidade;
      if ($inicio < 0) {        $inicio = 0;      }
  
      $itens = $this->selectObjetosAprendizagemApresentacao($categoria, $profissao, $professor, $ordem, $inicio, $quantidade);

      
      $this->listaObjetosAprendizagemSelecionados($itens);
      
      
    }
    
    public function listaObjetosAprendizagemSelecionados($cursos) {      
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/fotos.php';                                       $foto= new Fotos();
      require_once 'conteudos/cursos_categoria.php';                            $cur_cat = new CursoCategoria();
      require_once 'conteudos/profissoes.php';                                  $pro = new Profissao();
      require_once 'conteudos/cursos_professores.php';                          $cur_prof = new CursosProfessores();
      require_once 'conteudos/professores.php';                                 $prf = new Professor();

      $nr_professores_capa = $conf->retornaNumeroProfessoresObjetoAprendizagemCapa();
      $posicao = 1;
      
      $total = count($cursos);
      $id = 1;
      foreach ($objetos_aprendizagem as $it) {
        if ($posicao == '1') {
          echo "  <div class=\"courses-listing clearfix\">\n";  
        }
        $posicao += 1;
        $id += 1;

        if (isset($it['vl_curso'])) {
          $vl_curso = $it['vl_curso'] + $it['vl_certificado'];
        } else {
          $vl_curso = '';
        }
        $imagem = $foto->retornaUmaFotoCapa($it['cd_objeto_aprendizagem'], 'CS');     
        echo "    <div class=\"column fourcol\"><div class=\"course-preview premium-course\">\n";
        echo "      <div class=\"course-image\">\n";
        echo "        <a href=\"".$_SESSION['life_link_completo']."cursos/".$it['lk_seo']."\" class=\"fontLink\">\n";
        echo "          <img width=\"420\" height=\"420\" src=\"".$_SESSION['life_link_completo'].$_SESSION['life_link_arquivos'].$imagem['ds_pasta']."/".$imagem['ds_arquivo']."\" class=\"attachment-normal wp-post-image\" alt=\"Objeto de Aprendizagem de ".$it['nm_curso']."\" title=\"Objeto de Aprendizagem de ".$it['nm_curso']."\">\n";
        echo "        </a>\n";

        if ($vl_curso != '') {
          echo "        <div class=\"course-price product-price\">\n";
          echo "  			  <div class=\"price-text\"><span class=\"amount\">R$ ".number_format($vl_curso, 2, ',', '')."</span></div>\n";
        echo "            <div class=\"corner-wrap\">\n";
        echo "              <div class=\"corner\"></div>\n";
        echo "              <div class=\"corner-background\"></div>\n";
        echo "            </div>\n";
        echo "          </div>\n";
        }
        echo "        </div>\n";
        echo "        <div class=\"course-meta\">\n";
        echo "          <header class=\"course-header\">\n";
        echo "            <h5 class=\"nomargin\"><a href=\"".$_SESSION['life_link_completo']."cursos/".$it['lk_seo']."\">".$it['nm_curso']."</a></h5>\n"; 
        $lista_professores = $cur_prof->selectObjetosAprendizagemProfessores('1', $it['cd_objeto_aprendizagem'], 0);
        if (count($lista_professores) > 0) {
          echo "            <a href=\"\" class=\"author\">por </a>\n";
          $i = 0;
          foreach ($lista_professores as $lp) {
            if ($i < $nr_professores_capa) {
              if ($i > 0) {      echo ", ";         }
                echo "            <a href=\"".$_SESSION['life_link_completo']."instrutores/".$lp['lk_seo']."\" class=\"author\">".$lp['nm_professor']."</a> \n";
              } elseif ($i == $nr_professores_capa) {
                echo "            <a href=\"".$_SESSION['life_link_completo']."instrutores\" class=\"author\">(mais ".(count($lista_professores)-$nr_professores_capa).")</a>\n";
            }
            $i += 1;
          }
        }
        echo "          </header>\n";
        if (isset($it['nr_parcelas'])) {
          echo "          <footer class=\"course-footer clearfix\" align=\"center\">\n";
          if ($vl_curso > 0) {
            if ($it['nr_parcelas'] > 1) {
              echo "            Parcele <strong>R$ ".number_format($vl_curso, 2, ',', '')."</strong> em <strong>".$it['nr_parcelas']."x</strong> sem juros\n";
            } else {
              echo "            O valor do curso é de <strong>R$ ".number_format($vl_curso, 2, ',', '')."</strong>\n";
            }
          } else {
            echo "            Grátis\n";
          }
          echo "          </footer>\n";
        }
        echo "        </div>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        if ($posicao == '5') {
          if ($id < $total) {
            echo "  </div>\n";
            echo "  <div class=\"courses-listing clearfix\">\n";
            echo "<br /><br /><br />";
            echo "  </div>\n";
            $posicao = 1;
          }
        }
      }

    }

    

    public function controleExibicaoPublica($pagina, $lista_paginas) {

      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      require_once 'conteudos/cursos_categoria.php';                            $cur_cat = new CursoCategoria();

      require_once 'conteudos/profissoes.php';                                  $pro = new Profissao();

      require_once 'conteudos/professores.php';                                 $prf = new Professor();



      $pg = '1';

      $achou = false;

      $i = 1;            

      while (($i<10) && (!$achou)) {

        if (isset($lista_paginas[$i])) {

          if ($lista_paginas[$i] == 'pagina') {

            $pg = addslashes($lista_paginas[$i+1]);

            $achou = 1;

          } 

        }

        $i += 1;

      }



      $ordem = $conf->retornaOrdemObjetosAprendizagemCapa();

      $achou = false;

      $i = 1;            

      while (($i<10) && (!$achou)) {

        if (isset($lista_paginas[$i])) {

          if ($lista_paginas[$i] == 'ordem') {

            $ordem = addslashes($lista_paginas[$i+1]);

            $achou = 1;

          } 

        }

        $i += 1;

      }





      if (!isset($lista_paginas[1])) {

        $this->retornaRelacaoObjetosAprendizagem('', '', '', $ordem, $pg);

      } else {

        $lk_seo = $lista_paginas[1];

        $curso = $this->selectDadosObjetoAprendizagemSEO($lk_seo);

        if ($curso['cd_objeto_aprendizagem'] != '') {

          $this->retornaDadosObjetoAprendizagem($lista_paginas, $pagina, $curso);

        } else {

          $categoria = "";

          $profissao = "";

          $professor = "";

 

          $categoria = $cur_cat->linkEhCategoria($lk_seo);

          if ($categoria == 0) {

            $profissao = $pro->linkEhProfissao($lk_seo);

            if ($profissao == 0) {

              $professor = $prf->linkEhProfessor($lk_seo);

            }

          }

          $this->retornaRelacaoObjetosAprendizagem($categoria, $profissao, $professor, $ordem, $pg);

        }    

      } 

    }

                            /*

    private function retornaDadosObjetoAprendizagem($lista_paginas, $pagina, $curso) {

      require_once 'conteudos/fotos.php';                                       $foto = new Fotos();

      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new CursoAlunoInscricao();

      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new CursoAlunoAcessoConteudo();

      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();

      require_once 'conteudos/cursos_professores.php';                          $cur_pro = new CursosProfessores();

      



      if (!isset($lista_paginas[2])) {

        echo "  <div class=\"divTituloCapaObjetoAprendizagem\">\n";

        echo "    ".$curso['nm_curso']."\n";

        $professores = $cur_pro->selectObjetosAprendizagemProfessores('1', $curso['cd_objeto_aprendizagem'], '');

        $qtd_professores = count($professores); 

        if ($qtd_professores > 0) { 

          echo "    <spam style=\"color: #666d69; font-size: 14px;\">\n";

          echo "      com ";

          $id = 1;

          foreach ($professores as $p) {

            echo $p['nm_professor'];

            if ($qtd_professores > 1) {

              if ($id < ($qtd_professores - 1)) {

                echo ", ";

              } elseif ($id < $qtd_professores) {

                echo " e ";

              }

            }

            $id += 1;          

          }       

          echo "    </spam>\n";

        }

        echo "  </div>\n";        

        $con_lin->retornaRelacaoLinks('VI', $curso['cd_objeto_aprendizagem'], '1', true);

        $cur_alu_ins->retonarChamadaAssinaturaSimples($curso);

        $eh_titulo = false; 

      } else {

        $eh_titulo = true;

      }

      echo "  <div class=\"divInformacoesObjetoAprendizagemTitulo\">\n";

      

      if ((isset($_SESSION['life_acesso_curso_situacao'])) && ($_SESSION['life_acesso_curso_situacao'] == '1') && (!isset($lista_paginas['3']))) {

        $cur_alu_ace_con->retornaSituacaoAlunoAcessoConteudos($curso['cd_objeto_aprendizagem']);

      }

      if ($eh_titulo) {

        echo "    <h1>".$curso['nm_curso']."</h1>\n";

      }



      $outras_informacoes = false;

      if (isset($lista_paginas[2])) {

        $termo = addslashes($lista_paginas[2]);

        if ($termo == 'conteudo') {

          require_once 'conteudos/conteudos_cursos.php';                        $con_cur = new ConteudoCurso();

          $con_cur->retornaConteudoObjetoAprendizagem($lista_paginas, $curso['cd_objeto_aprendizagem']);

          echo "<hr>\n";

          $con_cur->retornaListaConteudosObjetoAprendizagem($lista_paginas, $curso);

        } else {

          if ((!isset($lista_paginas['3'])) && ($termo != 'extender-acesso') && ($termo != 'extensao')) {

          //  $cur_alu_ins->retonarChamadaAssinaturaSimples($curso);

          }

          if ($termo == 'extensao') {

            $_SESSION['life_extendendo'] = '1';

          }

          $outras_informacoes = $this->retornaItemObjetoAprendizagem($curso, $termo, $lista_paginas);

        }

      } else {

        if (!isset($lista_paginas['3'])) {

          //$cur_alu_ins->retonarChamadaAssinaturaSimples($curso);

        }    

        $outras_informacoes = true;

        require_once 'conteudos/cursos_professores.php';                      $cur_pro = new CursosProfessores();

        $cur_pro->retornaProfessoresObjetosAprendizagemCapa($curso['cd_objeto_aprendizagem']);             

        $this->retornaDetalhesIniciaisObjetoAprendizagem($lista_paginas, $curso);

        $this->contabilizarAcessoAoObjetoAprendizagem($curso['cd_objeto_aprendizagem']);

      }

//      if ($outras_informacoes) {

        echo "  </div>\n";

        $this->listarOutrosObjetosAprendizagemCategoria($curso['cd_objeto_aprendizagem']);

//      }      

      

    }

*

    public function retornaDadosObjetoAprendizagem($lista_paginas, $pagina, $curso) {

      require_once 'conteudos/fotos.php';                                       $foto = new Fotos();

      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new CursoAlunoInscricao();

      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new CursoAlunoAcessoConteudo();

      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();

      require_once 'conteudos/cursos_professores.php';                          $cur_pro = new CursosProfessores();

      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoCurso();





      echo "			<div class=\"substrate\">\n";

      echo "        <img src=\"".$_SESSION['life_link_completo']."index_files/curso/site_bg.jpg\" class=\"fullwidth\" alt=\"\">\n";

      echo "  		</div>\n";

      if (isset($lista_paginas[2])) {

        $termo = addslashes($lista_paginas[2]);

        if ($termo == 'conteudo') {

          require_once 'conteudos/conteudos_cursos.php';                        $con_cur = new ConteudoObjetoAprendizagem();

          echo "      <div class=\"row\" style=\"background-color:#FFF; position:relative; margin-top:-40px; padding-top:60px; padding-bottom:4%; margin-bottom:-4%; width:95%;\">\n";

          echo "        <h1 style=\"display:inline-block\">".$curso['nm_curso']."</h1>\n";

          echo "        <h3 style=\"display:inline;\">por \n";

          $professores = $cur_pro->selectObjetosAprendizagemProfessores('1', $curso['cd_objeto_aprendizagem'], '');

          $qtd_professores = count($professores); 

          if ($qtd_professores > 0) { 

            $id = 1;

            foreach ($professores as $p) {

              echo $p['nm_professor'];

              if ($qtd_professores > 1) {

                if ($id < ($qtd_professores - 1)) {              echo ", ";            } elseif ($id < $qtd_professores) {              echo " e ";            }

              }

              $id += 1;          

            }       

          }

          echo "</h3>\n";      

          $con_cur->retornaConteudoObjetoAprendizagem($lista_paginas, $curso['cd_objeto_aprendizagem']);

          echo "<hr>\n";

          $con_cur->retornaListaConteudosObjetoAprendizagem($lista_paginas, $curso);        

          echo "      </div>\n";

        } else {

          if ((!isset($lista_paginas['3'])) && ($termo != 'extender-acesso') && ($termo != 'extensao')) {

          //  $cur_alu_ins->retonarChamadaAssinaturaSimples($curso);

          }

          if ($termo == 'extensao') {

            $_SESSION['life_extendendo'] = '1';

          }

          $outras_informacoes = $this->retornaItemObjetoAprendizagem($curso, $termo, $lista_paginas);

        }

      } else {

        $this->retornaDetalhesInternosObjetoAprendizagem($curso, $lista_paginas);

        $this->listarOutrosObjetosAprendizagemCategoria($curso['cd_objeto_aprendizagem']);

      }      

    }





    public function retornaDetalhesInternosObjetoAprendizagem($curso, $lista_paginas) {

      require_once 'conteudos/fotos.php';                                       $foto = new Fotos();

      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new CursoAlunoInscricao();

      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new CursoAlunoAcessoConteudo();

      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();

      require_once 'conteudos/cursos_professores.php';                          $cur_pro = new CursosProfessores();

      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoCurso();



        echo "      <div class=\"row\" style=\"background-color:#FFF; position:relative; margin-top:-90px; padding-top:60px; padding-bottom:4%; margin-bottom:-4%; width:97%; margin-left: -1.5%; \">\n";

        echo "        <div class=\"threecol columa\" style=\"float:right; width:23%; margin-right:-0.2%; margin-top:57px;\">\n";

        echo "          <div class=\"course-preview premium-course\">\n";

        echo "            <table width=\"90%\" border=\"0\" align=\"center\" bgcolor=\"#e6e6e6\">\n";

        echo "              <tr>\n";

        echo "                <td height=\"43\" colspan=\"3\" align=\"center\">\n";

        echo "                  <div align=\"center\">\n";

        echo "                    <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/inscrever-se\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image13','',".$_SESSION['life_link_completo']."'index_files/iphoto_play_assistir.PNG',1)\"><img src=\"".$_SESSION['life_link_completo']."index_files/iphoto_play_agora.PNG\" name=\"Image13\" width=\"250\" height=\"55\" border=\"0\"></a>\n";

        echo "                  </div>\n";

        echo "                </td>\n";

        echo "              </tr>\n";

        echo "              <tr>\n";

        echo "                <td height=\"35\" background=\"".$_SESSION['life_link_completo']."index_files/icones/Chart-Bar1-UI.PNG\"  style=\"background-size: 20px 20px; background-position:center; background-repeat:no-repeat\"></td>\n";

        echo "                <td height=\"35\">Nível:</td>\n";

        echo "                <td width=\"49%\"><strong>".$curso['ds_nivel']."</strong></td>\n";

        echo "              </tr>\n";

        echo "              <tr>\n";

        echo "                <td height=\"35\"  background=\"".$_SESSION['life_link_completo']."index_files/icones/Clock-UI.PNG\" style=\"background-size: 20px 20px; background-position:center; background-repeat:no-repeat\"></td>\n";

        echo "                <td height=\"35\">Duração:</td>\n";

        echo "                <td><strong>".$con_cur->retornaTempoTotalObjetoAprendizagem($curso['cd_objeto_aprendizagem'])."</strong></td>\n";

        echo "              </tr>\n";

        echo "              <tr>\n";

        echo "                <td height=\"35\"  background=\"".$_SESSION['life_link_completo']."index_files/icones/Copy-UI.PNG\" style=\"background-size: 20px 20px; background-position:center; background-repeat:no-repeat\"></td>\n";

        echo "                <td height=\"35\">Lições:</td>\n";

        echo "                <td><strong>".$con_cur->retornaQuantidadesConteudos($curso['cd_objeto_aprendizagem'])."</strong></td>\n";

        echo "              </tr>\n";

        echo "              <tr>\n";

        echo "                <td height=\"35\" background=\"".$_SESSION['life_link_completo']."index_files/icones/Book-Bookmark-UI.PNG\"  style=\"background-size: 20px 20px; background-position:center; background-repeat:no-repeat\"></td>\n";

        echo "                <td height=\"35\">Pré-requisitos:</td>\n";

        echo "                <td><strong>".$curso['ds_requisitos']."</strong></td>\n";

        echo "              </tr>\n";

        echo "              <tr>\n";

        echo "                <td height=\"35\"  background=\"".$_SESSION['life_link_completo']."index_files/icones/Attach-UI.PNG\" style=\"background-size: 20px 20px; background-position:center; background-repeat:no-repeat\"></td>\n";

        echo "                <td height=\"35\">Arquivos:</td>\n";

        echo "                <td><strong>".$con_cur->retornaQuantidadeArquivosConteudos($curso['cd_objeto_aprendizagem'])." (baixar)</strong></td>\n";

        echo "              </tr>\n";

        echo "              <tr>\n";

        echo "                <td width=\"13%\" height=\"35\" background=\"".$_SESSION['life_link_completo']."index_files/icones/Man-UI.PNG\" style=\"background-size: 20px 20px; background-position:center; background-repeat:no-repeat\"></td>\n";

        echo "                <td width=\"38%\">Público-alvo:</td>\n";

        echo "                <td><strong>".$curso['ds_publico_alvo']."</strong></td>\n";

        echo "              </tr>\n";

        $cur_pro->retornaProfessoresObjetosAprendizagem($curso['cd_objeto_aprendizagem']);

        echo "            </table>\n";

        echo "          </div>\n";

        echo "        </div>\n";

        echo "      	<div class=\"columa videoporra\" style=\"padding-bottom:1.0%;\">\n";

        echo "          <h1 style=\"display:inline-block\">".$curso['nm_curso']."</h1>\n";

        echo "          <h3 style=\"display:inline;\">por \n";

        $professores = $cur_pro->selectObjetoAprendizagemsProfessores('1', $curso['cd_objeto_aprendizagem'], '');

        $qtd_professores = count($professores); 

        if ($qtd_professores > 0) { 

          $id = 1;

          foreach ($professores as $p) {

            echo $p['nm_professor'];

            if ($qtd_professores > 1) {

              if ($id < ($qtd_professores - 1)) {              echo ", ";            } elseif ($id < $qtd_professores) {              echo " e ";            }

            }

            $id += 1;          

          }       

        }

        echo "</h3>\n";

        echo "            <div class=\"course-preview\" style=\"width:100%;\">\n";

        $con_lin->retornaRelacaoLinks('VI', $curso['cd_objeto_aprendizagem'], '1', true);

        echo "            </div>\n";

        echo "          </div>\n";

        echo "          <div class=\"row\">\n";

        $cur_alu_ins->retonarPrimeiraChamadaAssinatura($curso);

        echo "            <div class=\"ninecol column\">\n";

        $cur_alu_ace_con->retornaSituacaoAlunoAcessoConteudos($curso['cd_objeto_aprendizagem']);

        echo "              <hr>\n";

  	    echo "              <div style=\"margin-left:5px;\">\n";

        echo "          			<h1 class=\"nomargin\">Conteúdo do curso</h1>\n";

        echo "            		<div class=\"widget-content\">\n";

        echo "                  <p>".nl2br($curso['ds_curso'])."</p>\n";

        echo "                </div>\n";

        $cur_alu_ins->retonarChamadaAssinaturaSimples($curso);

        echo "                <div class=\"course-content clearfix popup-container\">\n";

        $con_cur->retornaListaConteudosIniciaisObjetoAprendizagem($lista_paginas, $curso);      					

        echo "              </div>\n";

        echo "            </div>\n";      

        echo "          </div>\n";    

    }

    

    public function retornaDetalhesIniciaisObjetoAprendizagem($lista_paginas, $curso) {

      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoCurso();

      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new CursoAlunoAcessoConteudo();

      require_once 'conteudos/cursos_precos.php';                               $pre = new CursoPreco();

      

      if ($curso['ds_curso'] != '') {

        echo "  <div class=\"divConteudosCapa\">\n";

        echo "    <p>".nl2br($curso['ds_curso'])."</p>\n";

        echo "  </div>\n";

      }                                      

      $con_cur->retornaListaConteudosIniciaisObjetoAprendizagem($lista_paginas, $curso);

      if ($curso['ds_requisitos']) {

        echo "<div class=\"divConteudosCapa\">\n";

        echo "  <h2><img src=\"".$_SESSION['life_link_completo']."icones/seta.png\" alt=\"Seta - Marcador\" title=\"Seta - Marcador\" border=\"0\"> Pré-requisitos:</h2>\n";

        echo "  <p>".nl2br($curso['ds_requisitos'])."</p>\n";

        echo "</div>\n";

      }

      echo "<div class=\"divConteudosCapa\">\n";

      $preco = $pre->selectDadosObjetoAprendizagemPrecoValido($curso['cd_objeto_aprendizagem']);

      $vl_total = $preco['vl_curso'] + $preco['vl_certificado'];

      echo "      <p>\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/inscrever-se\" class=\"fontLinkChamadaCadastro\">Compre e Assista Agora</a>\n";

      echo "      </p>\n";

      echo "      <p>\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/inscrever-se\" class=\"fontLink\">\n";
      if ($vl_total > 0) {
        if ($preco['nr_parcelas'] > 1) {
          $vl_parcela = $vl_total / $preco['nr_parcelas'];
          echo "        apenas ".$preco['nr_parcelas']."x de R$ ".number_format($vl_parcela, 2, ',', '')." no cartão de crédito\n";
          echo "        <br />\n";
          echo "        ou por R$ ".number_format($vl_total, 2, ',', '')." à vista\n";
        } else {
          echo "        apenas R$ ".number_format($vl_total, 2, ',', '')."\n";        
        }
      } else {
        echo "        grátis\n";        
      }

      echo "      </a>\n";

      echo "      </p>\n";

      echo "      <p>\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/inscrever-se\" class=\"fontLink\"><img src=\"".$_SESSION['life_link_completo']."icones/comprar.png\" alt=\"Compre agora\" title=\"Compre agora\" border=\"0\"></a>\n";

      echo "      </p>\n";

      echo "</div>\n";

    }

        

                         /*

    public function retornaDetalhesIniciaisObjetoAprendizagem($lista_paginas, $curso) {

      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoCurso();

      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new CursoAlunoAcessoConteudo();

      require_once 'conteudos/cursos_precos.php';                               $pre = new CursoPreco();

      

      if ($curso['ds_curso'] != '') {

        echo "  <div class=\"divConteudosCapa\">\n";

        echo "    <p>".nl2br($curso['ds_curso'])."</p>\n";

        echo "  </div>\n";

      }                                      

      $con_cur->retornaListaConteudosIniciaisObjetoAprendizagem($lista_paginas, $curso);

      if ($curso['ds_requisitos']) {

        echo "<div class=\"divConteudosCapa\">\n";

        echo "  <h2><img src=\"".$_SESSION['life_link_completo']."icones/seta.png\" alt=\"Seta - Marcador\" title=\"Seta - Marcador\" border=\"0\"> Pré-requisitos:</h2>\n";

        echo "  <p>".nl2br($curso['ds_requisitos'])."</p>\n";

        echo "</div>\n";

      }

      echo "<div class=\"divConteudosCapa\">\n";

      $preco = $pre->selectDadosCursoPrecoValido($curso['cd_objeto_aprendizagem']);

      $vl_total = $preco['vl_curso'] + $preco['vl_certificado'];

      echo "      <p>\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/inscrever-se\" class=\"fontLinkChamadaCadastro\">Compre e Assista Agora</a>\n";

      echo "      </p>\n";

      echo "      <p>\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/inscrever-se\" class=\"fontLink\">\n";

      if ($preco['nr_parcelas'] > 1) {

        $vl_parcela = $vl_total / $preco['nr_parcelas'];

        echo "        apenas ".$preco['nr_parcelas']."x de R$ ".number_format($vl_parcela, 2, ',', '')." no cartão de crédito\n";

        echo "        <br />\n";

        echo "        ou por R$ ".number_format($vl_total, 2, ',', '')." à vista\n";

      } else {

        echo "        apenas R$ ".number_format($vl_total, 2, ',', '')."\n";        

      }

      echo "      </a>\n";

      echo "      </p>\n";

      echo "      <p>\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/inscrever-se\" class=\"fontLink\"><img src=\"".$_SESSION['life_link_completo']."icones/comprar.png\" alt=\"Compre agora\" title=\"Compre agora\" border=\"0\"></a>\n";

      echo "      </p>\n";

      echo "</div>\n";

    }    

*    

    public function retornaDetalhesObjetoAprendizagem($lista_paginas, $curso) {

      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoObjetoAprendizagem();

      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoConteudo();

      

      if ($curso['ds_curso'] != '') {

        //echo "    <h3>Descrição</h3>\n";

        echo "    <p>".nl2br($curso['ds_curso'])."</p>\n";

        echo "    <hr>\n";                                

      }

      $con_cur->retornaListaConteudosObjetoAprendizagem($lista_paginas, $curso);       

      echo "    <hr>\n";

      if ($curso['ds_objetivos'] != '') {

        echo "    <h3>Objetivos</h3>\n";

        echo "    <p>".nl2br($curso['ds_objetivos'])."</p>\n";

        echo "    <hr>\n";

      }

      if ($curso['ds_requisitos']) {

        echo "    <h3>Requisitos</h3>\n";

        echo "    <p>".nl2br($curso['ds_requisitos'])."</p>\n";

        echo "    <hr>\n";

      }

      echo "    <p>\n";

      $this->retornaInvestimento($curso);

      echo "    </p>\n";

    }

                                                              

    public function listarOutrosObjetosAprendizagemCategoria($cd_objeto_aprendizagem) {
      require_once 'conteudos/cursos_categorias.php';                           $cur_cat = new CursosCategorias();
      require_once 'conteudos/fotos.php';                                       $foto= new Fotos();
      require_once 'conteudos/cursos_professores.php';                          $cur_pro = new CursosProfessores();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      $nr_professores_capa = $conf->retornaNumeroProfessoresObjetoAprendizagemCapa();
      $categorias = $cur_cat->selectObjetosAprendizagemCategorias('1', $cd_objeto_aprendizagem, '');

      echo "				<div class=\"related-courses clearfix\">\n";
      echo "          <h1 style=\"padding-top:20px;\">Objetos de Aprendizagem relacionados</h1>\n";
      foreach ($categorias as $c) {
        $lista_objetos_aprendizagem = $cur_cat->selectObjetosAprendizagemCategorias('1', '', $c['cd_categoria']);
        $objetos_aprendizagem = array();
        foreach ($lista_objetos_aprendizagem as $c) {
          if ($c['cd_objeto_aprendizagem'] != $cd_objeto_aprendizagem) {
            $cursos[] = $c;
          }
        } 

        if (count($cursos) > 1) {
          echo "          <div class=\"courses-listing clearfix\">\n";
          echo "            <h2><a href=\"".$_SESSION['life_link_completo']."cursos/".$c['lk_seo_categoria']."\" class=\"fontLink\">".$c['nm_categoria']."</a></h2>\n";
          $objetos_aprendizagem = $cur_cat->selectObjetosAprendizagemCategorias('1', '', $c['cd_categoria']);
          $sorteados = array();
          $listar = array();
          $qt_objetos_aprendizagem = count($cursos);
          $nr_limite_objetos_aprendizagem = '4'; 
          if ($nr_limite_objetos_aprendizagem > $qt_cursos) {          $nr_limite_objetos_aprendizagem = $qt_cursos;        }
          for ($l=0; $l<$nr_limite_cursos; $l++) {
            $valeu= false;         
            while (!$valeu) {
              $nro_sorteado = rand(0, ($qt_cursos-1));
              if (!in_array($nro_sorteado, $sorteados)) {
                $curso = $cursos[$nro_sorteado];
                $sorteados[] = $nro_sorteado;
                $valeu= true;
                $listar[] = $curso;            
              }
                        
            }
          }
        
          foreach ($listar as $it) {
            $imagem = $foto->retornaUmaFotoCapa($it['cd_objeto_aprendizagem'], 'CS');     
            $vl_curso = $it['vl_curso'] + $it['vl_certificado'];
            echo "    <div class=\"column fourcol\">\n";
            echo "      <div class=\"course-preview premium-course\">\n";
            echo "        <div class=\"course-image\">\n";
            echo "          <a href=\"".$_SESSION['life_link_completo']."cursos/".$it['lk_seo_curso']."\" class=\"fontLink\">\n";
            echo "            <img width=\"420\" height=\"420\" src=\"".$_SESSION['life_link_completo'].$_SESSION['life_link_arquivos'].$imagem['ds_pasta']."/".$imagem['ds_arquivo']."\" class=\"attachment-normal wp-post-image\" alt=\"Objeto de Aprendizagem de ".$it['nm_curso']."\" title=\"Objeto de Aprendizagem de ".$it['nm_curso']."\">\n";
            echo "          </a>\n";
            echo "          <div class=\"course-price product-price\">\n";
            echo "    			  <div class=\"price-text\"><span class=\"amount\">R$ ".number_format($vl_curso, 2, ',', '')."</span></div>\n";
            echo "            <div class=\"corner-wrap\">\n";
            echo "              <div class=\"corner\"> </div>\n";
            echo "              <div class=\"corner-background\"> </div>\n";
            echo "            </div>\n";
            echo "          </div>\n";

            echo "          <div class=\"course-meta\">\n";
            echo "            <header class=\"course-header\">\n";
            echo "              <h5 class=\"nomargin\"><a href=\"".$_SESSION['life_link_completo']."cursos/".$it['lk_seo_curso']."\">".$it['nm_curso']."</a></h5>\n"; 
            $lista_professores = $cur_pro->selectObjetosAprendizagemProfessores('1', $it['cd_objeto_aprendizagem'], 0);
            if (count($lista_professores) > 0) {
              $i = 0;
              foreach ($lista_professores as $lp) {
                if ($i < $nr_professores_capa) {
                  if ($i > 0) {      echo ", ";         }
                  echo "              <a href=\"".$_SESSION['life_link_completo']."instrutores/".$lp['lk_seo']."\" class=\"author\">".$lp['nm_professor']."</a> \n";
                } elseif ($i == $nr_professores_capa) {
                  echo "              <a href=\"".$_SESSION['life_link_completo']."instrutores\" class=\"author\">(mais ".(count($lista_professores)-$nr_professores_capa).")</a>\n";
                }
                $i += 1;
              }
            }
            echo "            </header>\n";
            echo "          </div>\n";
            echo "        </div>\n";
            echo "      </div>\n";
            echo "    </div>\n";
          }
          echo "  </div>\n";
        }
      }      
      echo "  </div>\n";
    }

    



    private function retornaItemObjetoAprendizagem($curso, $termo, $lista_paginas) {

      echo "      <div class=\"row\" style=\"background-color:#FFF; position:relative; margin-top:-40px; padding-top:60px; padding-bottom:4%; margin-bottom:-4%; width:95%;\">\n";

      switch ($termo) {

        case "descricao":

          echo "    <h3>Descrição</h3>\n";

          echo "    <p>".nl2br($curso['ds_curso'])."</p>\n";

          return true;

        break;



        case "objetivos":

          echo "    <h3>Objetivos</h3>\n";

          echo "    <p>".nl2br($curso['ds_objetivos'])."</p>\n";

          return true;

        break;



        case "ementa":

          echo "    <h3>Ementa</h3>\n";

          echo "    <p>".nl2br($curso['ds_ementa'])."</p>\n";

          return true;

        break;



        case "regras":

          echo "    <h3>Regras</h3>\n";

          echo "    <p>".nl2br($curso['ds_regras'])."</p>\n";

          require_once 'conteudos/regras.php';                                  $reg = new Regra();

          $reg->retornaRegrasGerais();

          return true;

        break;



        case "requisitos":

          echo "    <h3>Requisitos</h3>\n";

          echo "    <p>".nl2br($curso['ds_requisitos'])."</p>\n";

          return true;

        break;



        case "investimento":

          echo "    <p>\n";

          $this->retornaInvestimento($curso);

          echo "    </p>\n";

          return true;

        break;

        

        case "inscrever-se":

          require_once 'conteudos/cursos_alunos_inscricoes.php';                $cur_alu_ins = new CursoAlunoInscricao();

          $cur_alu_ins->retornaInformacoesInscricao($curso, $termo, $lista_paginas);

          return false;

        break;

        

        case "extender-acesso":

          require_once 'conteudos/cursos_alunos_inscricoes.php';                $cur_alu_ins = new CursoAlunoInscricao();

          $cur_alu_ins->retornaInformacoesExtensaoPrazo($curso, $termo, $lista_paginas);          

          return false;

        break;

        

        case "extensao":

          require_once 'conteudos/cursos_alunos_inscricoes.php';                $cur_alu_ins = new CursoAlunoInscricao();

          require_once 'conteudos/cursos_professores.php';                      $cur_pro = new CursosProfessores();

          require_once 'conteudos/conteudos_cursos.php';                        $con_cur = new ConteudoCurso();

          $cur_pro->retornaProfessoresObjetosAprendizagem($curso['cd_objeto_aprendizagem']);             

          $cur_alu_ins->retornaAmbientePagamentoExtensao($lista_paginas);

          $this->retornaDetalhesObjetoAprendizagem($lista_paginas, $curso);  

          echo "  <div class=\"divConteudosProfessoresInformacoesObjeto de Aprendizagem\">\n";

          $con_cur->retornaListaConteudosObjetoAprendizagem($lista_paginas, $curso);       

          echo "  </div>\n";

          return false;

        break;

        

        case "logar":

          require_once 'conteudos/cursos_alunos_inscricoes.php';                $cur_alu_ins = new CursoAlunoInscricao();

          $cur_alu_ins->retornaInformacoesLoginInscricao($curso, $termo, $lista_paginas);

          return false;

        break;

        

        case "cadastre-se":

          require_once 'conteudos/alunos.php';                                  $alu = new Aluno();

          $alu->controleExibicaoPublica('', $lista_paginas);

          return false;

        break;

        

        case "cadastro":

          require_once 'conteudos/alunos.php';                                  $alu = new Aluno();

          $alu->salvarCadastroAlunoInscricaoObjetoAprendizagem($lista_paginas);

          return false;

        break;



        case "inscricao":

          require_once 'conteudos/cursos_alunos_inscricoes.php';                $cur_alu_ins = new CursoAlunoInscricao();

          require_once 'conteudos/cursos_professores.php';                      $cur_pro = new CursosProfessores();

          require_once 'conteudos/conteudos_cursos.php';                        $con_cur = new ConteudoCurso();

          //$cur_pro->retornaProfessoresObjetoAprendizagems($curso['cd_objeto_aprendizagem']);             

          $cur_alu_ins->retornaAmbientePagamento($lista_paginas);

          //$this->retornaDetalhesObjetoAprendizagem($lista_paginas, $curso);  

          //echo "  <div class=\"divConteudosProfessoresInformacoesObjetoAprendizagem\">\n";

          //$con_cur->retornaListaConteudosObjetoAprendizagem($lista_paginas, $curso);       

          //echo "  </div>\n";

          return false;

        break;



        case "certificado":

          require_once 'conteudos/cursos_alunos_certificados.php';              $cur_alu_cer = new CursoAlunoCertificado();

          $cur_alu_cer->retornaProcedimentosSolicitacaoCertificado($curso, $lista_paginas);

        break;

        

        case "avaliacao":

          require_once 'conteudos/cursos_alunos_avaliacoes.php';                $cur_alu_ava = new CursoAlunoAvaliacao();

          $cur_alu_ava->retornaProcedimentosAvaliacao($curso, $lista_paginas);

          if ((isset($lista_paginas[3])) && ($lista_paginas[3] == 'responder-avaliacao')) {

            $this->retornaDetalhesInternosObjetoAprendizagem($curso, $lista_paginas);

          }

        break;

      }

      echo "      </div>\n";

    }



    public function retornaInvestimento($curso) {

      echo "    <h3>Investimento</h3>\n"; 

      if ($curso['eh_valor_default'] == '1') {

        require_once 'includes/configuracoes.php';                              $conf = new Configuracao();

        $vl_curso = $conf->retornaValorPadraoObjetoAprendizagem();

        $vl_certificado = $conf->retornaValorPadraoCertificado();

      } else {

        require_once 'conteudos/cursos_precos.php';                             $pre = new ObjetoAprendizagemPreco();

        $dados = $pre->selectDadosObjetoAprendizagemPrecoValido($curso['cd_objeto_aprendizagem']);

        $vl_curso = $dados['vl_curso'];

        $vl_certificado = $dados['vl_certificado'];

      }   

      $vl_total = $vl_certificado + $vl_curso;

      echo "      <p>O investimento total para o acesso aos conteúdos e emissão de certificado é de R$ ".number_format($vl_total, 2, ',', '')."</p>\n";

    }

    

    private function retornaItensObjetoAprendizagem($lista_paginas, $curso) {

      echo "    <div class=\"divItensObjetosAprendizagem\">\n";       

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/descricao\" class=\"fontLink\">Descrição</a><br />\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/objetivos\" class=\"fontLink\">Objetivos</a><br />\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/ementa\" class=\"fontLink\">Ementa</a><br />\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/regras\" class=\"fontLink\">Regras</a><br />\n";

      echo "      <a href=\"".$_SESSION['life_link_completo']."cursos/".$curso['lk_seo']."/investimento\" class=\"fontLink\">Investimento</a>\n";

      echo "    </div>\n";

    } 

  

    public function controleExibicaoPublicaMeusObjetosAprendizagem($pagina, $lista_paginas) {

      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new CursoAlunoInscricao();

      require_once 'conteudos/alunos.php';                                      $alu = new Aluno();

      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      require_once 'conteudos/fotos.php';                                       $foto= new Fotos();

      require_once 'conteudos/cursos_professores.php';                          $cur_prof = new CursosProfessores();

      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new CursoAlunoAcessoConteudo();





      echo "  <h1>Meus Objetos de Aprendizagem</h1>\n";



      echo "  <div class=\"courses-listing clearfix\">\n";

      

      $cd_usuario = addslashes($_SESSION['life_codigo']);

      $dados_aluno = $alu->selectDadosAlunoUsuario($cd_usuario);

      $cd_aluno = $dados_aluno['cd_aluno'];

     

      $objetos_aprendizagem = $cur_alu_ins->selectObjetosAprendizagemAlunosInscricoes('1', '', $cd_aluno);



      $nr_professores_capa = $conf->retornaNumeroProfessoresObjetoAprendizagemCapa();

      

      if (count($cursos) > 0) {

        $posicao = 1;

        foreach ($objetos_aprendizagem as $c) {

          $it = $this->selectDadosObjetoAprendizagem($c['cd_objeto_aprendizagem']);

          $imagem = $foto->retornaUmaFotoCapa($it['cd_objeto_aprendizagem'], 'CS');     

          echo "    <div class=\"column fourcol\">\n";

          echo "      <div class=\"course-preview premium-course\">\n";

          echo "        <div class=\"course-image\">\n";

          echo "          <a href=\"".$_SESSION['life_link_completo']."cursos/".$it['lk_seo']."\" class=\"fontLink\">\n";

          echo "            <img width=\"420\" height=\"420\" src=\"".$_SESSION['life_link_completo'].$_SESSION['life_link_arquivos'].$imagem['ds_pasta']."/".$imagem['ds_arquivo']."\" class=\"attachment-normal wp-post-image\" alt=\"Objeto de Aprendizagem de ".$it['nm_curso']."\" title=\"Objeto de Aprendizagem de ".$it['nm_curso']."\">\n";

          echo "          </a>\n";

          echo "          <div class=\"course-meta\">\n";

          echo "            <header class=\"course-header\">\n";

          echo "              <h5 class=\"nomargin\"><a href=\"".$_SESSION['life_link_completo']."cursos/".$it['lk_seo']."\">".$it['nm_curso']."</a></h5>\n"; 

          echo "    <p>\n";

          echo "      Inscrito em: ".$dh->imprimirData($c['dt_inscricao'])."<br />\n";

          if ($c['dt_conclusao'] != '') {

            echo "      Concluído em: ".$dh->imprimirData($c['dt_conclusao'])."<br />\n";          

          }

          if ($c['eh_quitado_curso'] == '0') {

            echo "      <span style=\"color:red;\">Pendente de Pagamento</span>\n";

          }   

          echo "    </p>\n";

          echo "            </header>\n";

          echo "          </div>\n";

          echo "        </div>\n";

          echo "      </div>\n";

          echo "    </div>\n";
        }

      }

          echo "</div>\n";      

    }    

       
 */
//**************BANCO DE DADOS**************************************************    
    public function selectObjetosAprendizagem($eh_ativo, $ordem, $inicio, $limite) {
      if (isset($_SESSION['life_c_termo']))         {          $termo_1 = $_SESSION['life_c_termo'];          } else {        $_SESSION['life_c_termo'] = '';             $termo_1 = '';            }
      if (isset($_SESSION['life_c_campos']))         {          $campo_1 = $_SESSION['life_c_campos'];          } else {        $_SESSION['life_c_campos'] = '';             $campo_1 = '';            }
      if (isset($_SESSION['life_c_eh_proprietario'])) {  $eh_proprietario = $_SESSION['life_c_eh_proprietario'];  } else {        $_SESSION['life_c_eh_proprietario'] = '1';    $eh_proprietario = '1';   }
/*    
echo "<br />TERMO ".$termo_1;
echo "<br />CAMPO ".$campo_1;
echo "<br />TABELA ".$tabela_1;
echo "<br />PROPRIETARIO ".$eh_proprietario;
*/
      $sql  = "SELECT oa.*, pe.nm_pessoa ".
              "FROM life_objetos_aprendizagem oa, life_pessoas pe ".
              "WHERE oa.cd_usuario_proprietario = pe.cd_usuario ";
      if ($eh_proprietario == '1') {
        $cd_usuario = $_SESSION['life_codigo'];
        $sql.= " AND oa.cd_usuario_proprietario = '$cd_usuario' ";
      }
      if ($eh_ativo == 1) {
        $sql.= "AND oa.eh_ativo = '$eh_ativo' ";
      }   
      if ($termo_1 != '') {
        $termo_1_a = "%".$termo_1;
        $termo_1_b = "%".$termo_1."%";
        $termo_1_c =     $termo_1."%";
        $termo_1_d =     $termo_1;
        
        switch ($tabela_1) {
          case "objetos_aprendizagem":
            $sql.= "AND (UPPER(oa.nm_objeto_aprendizagem) LIKE UPPER('$termo_1_a') OR UPPER(oa.nm_objeto_aprendizagem) LIKE UPPER('$termo_1_b') OR UPPER(oa.nm_objeto_aprendizagem) LIKE UPPER('$termo_1_c') OR UPPER(oa.nm_objeto_aprendizagem) = UPPER('$termo_1_d'))";
          break;
          
          case "general":
            $sql.= "AND oa.cd_general IN ( SELECT cd_general FROM life_general ";
            switch ($campo_1) { 
              case "ds_identifier":           $sql.= "WHERE UPPER(ds_identifier) LIKE UPPER('$termo_1_a') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_b') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_c') OR UPPER(ds_identifier) = UPPER('$termo_1_d'))";                              break;
              case "ds_title":                $sql.= "WHERE UPPER(ds_title) LIKE UPPER('$termo_1_a') OR UPPER(ds_title) LIKE UPPER('$termo_1_b') OR UPPER(ds_title) LIKE UPPER('$termo_1_c') OR UPPER(ds_title) = UPPER('$termo_1_d'))";                                                  break;
              case "cd_language":             $sql.= "WHERE cd_language IN ( ".
                                                     "  SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d')) ".
                                                     ")";                                                                                                                                                                                                                                 break;
              case "ds_description":          $sql.= "WHERE UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))";                          break;
              case "ds_keyword":              $sql.= "WHERE UPPER(ds_keyword) LIKE UPPER('$termo_1_a') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_b') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_c') OR UPPER(ds_keyword) = UPPER('$termo_1_d'))";                                          break;
              case "cd_coverage":             $sql.= "WHERE cd_coverage IN ( ".
                                                     "  SELECT cd_area_conhecimento FROM life_areas_conhecimento WHERE UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_a') OR UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_b') OR UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_c') OR UPPER(nm_area_conhecimento) = UPPER('$termo_1_d') ".
                                                     "  OR cd_area_conhecimento IN ( ".
                                                     "    SELECT cd_area_conhecimento FROM life_sub_areas_conhecimento WHERE UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_a') OR UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_b') OR UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_c') OR UPPER(nm_sub_area_conhecimento) = UPPER('$termo_1_d')) ".
                                                     "  ) ".
                                                     ")";                                                                                                                                                                                                                                 break;
              case "ds_structure":            $sql.= "WHERE UPPER(ds_structure) LIKE UPPER('$termo_1_a') OR UPPER(ds_structure) LIKE UPPER('$termo_1_b') OR UPPER(ds_structure) LIKE UPPER('$termo_1_c') OR UPPER(ds_structure) = UPPER('$termo_1_d'))";                                  break;
              case "ds_agregation_level":     $sql.= "WHERE UPPER(ds_agregation_level) LIKE UPPER('$termo_1_a') OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_b') OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_c') OR UPPER(ds_agregation_level) = UPPER('$termo_1_d'))";      break;

              case "todos":        
                $sql.= "WHERE UPPER(ds_identifier) LIKE UPPER('$termo_1_a') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_b') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_c') OR UPPER(ds_identifier) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_title) LIKE UPPER('$termo_1_a') OR UPPER(ds_title) LIKE UPPER('$termo_1_b') OR UPPER(ds_title) LIKE UPPER('$termo_1_c') OR UPPER(ds_title) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_keyword) LIKE UPPER('$termo_1_a') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_b') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_c') OR UPPER(ds_keyword) = UPPER('$termo_1_d') ".
                       "OR cd_coverage IN ( SELECT cd_area_conhecimento FROM life_areas_conhecimento WHERE UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_a') OR UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_b') OR UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_c') OR UPPER(nm_area_conhecimento) = UPPER('$termo_1_d') OR cd_area_conhecimento IN ( SELECT cd_area_conhecimento FROM life_sub_areas_conhecimento WHERE UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_a') OR UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_b') OR UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_c') OR UPPER(nm_sub_area_conhecimento) = UPPER('$termo_1_d')) ) ".
                       "OR UPPER(ds_structure) LIKE UPPER('$termo_1_a') OR UPPER(ds_structure) LIKE UPPER('$termo_1_b') OR UPPER(ds_structure) LIKE UPPER('$termo_1_c') OR UPPER(ds_structure) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_a') OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_b') OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_c') OR UPPER(ds_agregation_level) = UPPER('$termo_1_d')) ";
              break;
            }
          break;

          case "lyfe_cycle":
            $sql.= "AND oa.cd_lyfe_cycle IN ( SELECT cd_lyfe_cycle FROM life_lyfe_cycle ";        
            switch ($campo_1) {  
              case "ds_version":          $sql.= "WHERE UPPER(ds_version) LIKE UPPER('$termo_1_a') OR UPPER(ds_version) LIKE UPPER('$termo_1_b') OR UPPER(ds_version) LIKE UPPER('$termo_1_c') OR UPPER(ds_version) = UPPER('$termo_1_d'))";                break;
              case "cd_status":           $sql.= "WHERE cd_status IN ( ".
                                                 "  SELECT cd_status_ciclo_vida FROM life_status_ciclos_vida WHERE UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_a') OR UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_b') OR UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_c') OR UPPER(nm_status_ciclo_vida) = UPPER('$termo_1_d')) ".
                                                 ") ";                                                                                                                                                                                                      break;
              case "ds_contribute":       $sql.= "WHERE UPPER(ds_contribute) LIKE UPPER('$termo_1_a') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_b') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_c') OR UPPER(ds_contribute) = UPPER('$termo_1_d'))";    break;

              case "todos":        
                $sql.= "WHERE UPPER(ds_version) LIKE UPPER('$termo_1_a') OR UPPER(ds_version) LIKE UPPER('$termo_1_b') OR UPPER(ds_version) LIKE UPPER('$termo_1_c') OR UPPER(ds_version) = UPPER('$termo_1_d') ".
                       "OR cd_status IN ( SELECT cd_status_ciclo_vida FROM life_status_ciclos_vida WHERE UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_a') OR UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_b') OR UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_c') OR UPPER(nm_status_ciclo_vida) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_contribute) LIKE UPPER('$termo_1_a') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_b') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_c') OR UPPER(ds_contribute) = UPPER('$termo_1_d')) ";
              break;
            }
          break;

          case "meta_metadata":
            $sql.= "AND oa.cd_meta_metadata IN ( SELECT cd_meta_metadata FROM life_meta_metadata ";        
            switch ($campo_1) {  
              case "ds_identifier":         $sql.= "WHERE UPPER(ds_identifier) LIKE UPPER('$termo_1_a') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_b') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_c') OR UPPER(ds_identifier) = UPPER('$termo_1_d'))";                        break;
              case "ds_contribute":         $sql.= "WHERE UPPER(ds_contribute) LIKE UPPER('$termo_1_a') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_b') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_c') OR UPPER(ds_contribute) = UPPER('$termo_1_d'))";                        break;
              case "ds_metadata_schema":    $sql.= "WHERE UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_a') OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_b') OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_c') OR UPPER(ds_metadata_schema) = UPPER('$termo_1_d'))";    break;
              case "cd_language":           $sql.= "WHERE cd_language IN ( ".
                                                   "  SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d')) ".
                                                   ")";                                                                                                                                                                                                                           break;    
              case "todos":         
                $sql.= "WHERE UPPER(ds_identifier) LIKE UPPER('$termo_1_a') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_b') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_c') OR UPPER(ds_identifier) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_contribute) LIKE UPPER('$termo_1_a') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_b') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_c') OR UPPER(ds_contribute) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_a') OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_b') OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_c') OR UPPER(ds_metadata_schema) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d') ) ) "; 
              break;
            }
          break;

          case "technical":
            $sql.= "AND oa.cd_technical IN ( SELECT cd_technical FROM life_technical ";        
            switch ($campo_1) {  
              case "cd_format":                         $sql.= "WHERE cd_format IN ( ".
                                                               "  SELECT cd_formato FROM life_formatos WHERE UPPER(nm_formato) LIKE UPPER('$termo_1_a') OR UPPER(nm_formato) LIKE UPPER('$termo_1_b') OR UPPER(nm_formato) LIKE UPPER('$termo_1_c') OR UPPER(nm_formato) = UPPER('$termo_1_d')) ".
                                                               ")";                                                                                                                                                                                                                                                                                   break;
              case "ds_size":                           $sql.= "WHERE UPPER(ds_size) LIKE UPPER('$termo_1_a') OR UPPER(ds_size) LIKE UPPER('$termo_1_b') OR UPPER(ds_size) LIKE UPPER('$termo_1_c') OR UPPER(ds_size) = UPPER('$termo_1_d'))";                                                                                                        break;
              case "ds_location":                       $sql.= "WHERE UPPER(ds_location) LIKE UPPER('$termo_1_a') OR UPPER(ds_location) LIKE UPPER('$termo_1_b') OR UPPER(ds_location) LIKE UPPER('$termo_1_c') OR UPPER(ds_location) = UPPER('$termo_1_d'))";                                                                                        break;
              case "ds_requirement":                    $sql.= "WHERE UPPER(ds_requirement) LIKE UPPER('$termo_1_a') OR UPPER(ds_requirement) LIKE UPPER('$termo_1_b') OR UPPER(ds_requirement) LIKE UPPER('$termo_1_c') OR UPPER(ds_requirement) = UPPER('$termo_1_d'))";                                                                            break;
              case "ds_composite":                      $sql.= "WHERE UPPER(ds_composite) LIKE UPPER('$termo_1_a') OR UPPER(ds_composite) LIKE UPPER('$termo_1_b') OR UPPER(ds_composite) LIKE UPPER('$termo_1_c') OR UPPER(ds_composite) = UPPER('$termo_1_d'))";                                                                                    break;
              case "ds_installation_remarks":           $sql.= "WHERE UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_a') OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_b') OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_c') OR UPPER(ds_installation_remarks) = UPPER('$termo_1_d'))";                                        break;
              case "ds_other_plataforms_requirements":  $sql.= "WHERE UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_a') OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_b') OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_c') OR UPPER(ds_other_plataforms_requirements) = UPPER('$termo_1_d'))";    break;
              case "ds_duration":                       $sql.= "WHERE UPPER(ds_duration) LIKE UPPER('$termo_1_a') OR UPPER(ds_duration) LIKE UPPER('$termo_1_b') OR UPPER(ds_duration) LIKE UPPER('$termo_1_c') OR UPPER(ds_duration) = UPPER('$termo_1_d'))";                                                                                        break;
              case "ds_supported_plataform":            $sql.= "WHERE UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_a') OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_b') OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_c') OR UPPER(ds_supported_plataform) = UPPER('$termo_1_d'))";                                            break;

              case "todos":
                $sql.= "WHERE cd_format IN ( SELECT cd_formato FROM life_formatos WHERE UPPER(nm_formato) LIKE UPPER('$termo_1_a') OR UPPER(nm_formato) LIKE UPPER('$termo_1_b') OR UPPER(nm_formato) LIKE UPPER('$termo_1_c') OR UPPER(nm_formato) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_size) LIKE UPPER('$termo_1_a') OR UPPER(ds_size) LIKE UPPER('$termo_1_b') OR UPPER(ds_size) LIKE UPPER('$termo_1_c') OR UPPER(ds_size) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_location) LIKE UPPER('$termo_1_a') OR UPPER(ds_location) LIKE UPPER('$termo_1_b') OR UPPER(ds_location) LIKE UPPER('$termo_1_c') OR UPPER(ds_location) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_requirement) LIKE UPPER('$termo_1_a') OR UPPER(ds_requirement) LIKE UPPER('$termo_1_b') OR UPPER(ds_requirement) LIKE UPPER('$termo_1_c') OR UPPER(ds_requirement) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_composite) LIKE UPPER('$termo_1_a') OR UPPER(ds_composite) LIKE UPPER('$termo_1_b') OR UPPER(ds_composite) LIKE UPPER('$termo_1_c') OR UPPER(ds_composite) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_a') OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_b') OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_c') OR UPPER(ds_installation_remarks) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_a') OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_b') OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_c') OR UPPER(ds_other_plataforms_requirements) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_duration) LIKE UPPER('$termo_1_a') OR UPPER(ds_duration) LIKE UPPER('$termo_1_b') OR UPPER(ds_duration) LIKE UPPER('$termo_1_c') OR UPPER(ds_duration) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_a') OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_b') OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_c') OR UPPER(ds_supported_plataform) = UPPER('$termo_1_d') )";
              break;
            }
          break;

          case "technical_plataform_specific_features":
            $sql.= "AND oa.cd_technical = ( SELECT cd_technical FROM life_technical WHERE cd_plataform_specific_features = (SELECT cd_plataform_specific_features FROM life_plataform_specific_features ";        
            switch ($campo_1) {  
              case "ds_plataform_type":                           $sql.= "WHERE UPPER(ds_plataform_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_plataform_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_plataform_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_plataform_type) = UPPER('$termo_1_d')))";                                                                                               break;
              case "ds_specific_format":                          $sql.= "WHERE UPPER(ds_specific_format) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_format) = UPPER('$termo_1_d')))";                                                                                           break;
              case "ds_specific_size":                            $sql.= "WHERE UPPER(ds_specific_size) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_size) = UPPER('$termo_1_d')))";                                                                                                   break;
              case "ds_specific_location":                        $sql.= "WHERE UPPER(ds_specific_location) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_location) = UPPER('$termo_1_d')))";                                                                                   break;
              case "ds_specific_requeriments":                    $sql.= "WHERE UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_requeriments) = UPPER('$termo_1_d')))";                                                                   break;
              case "ds_specific_instalation_remarks":             $sql.= "WHERE UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_instalation_remarks) = UPPER('$termo_1_d')))";                                       break;
              case "ds_specific_other_plataform_requeriments":    $sql.= "WHERE UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_other_plataform_requeriments) = UPPER('$termo_1_d')))";   break;

              case "todos":                           
                $sql.= "WHERE UPPER(ds_plataform_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_plataform_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_plataform_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_plataform_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_format) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_size) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_location) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_requeriments) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_instalation_remarks) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_other_plataform_requeriments) = UPPER('$termo_1_d')))";
              break;
            }
          break;

          case "technical_service":
            $sql.= "AND oa.cd_technical IN ( SELECT cd_technical FROM life_technical WHERE cd_service IN (SELECT cd_service FROM life_service ";        
            switch ($campo_1) {  
              case "ds_name":            $sql.= "WHERE UPPER(ds_name) LIKE UPPER('$termo_1_a') OR UPPER(ds_name) LIKE UPPER('$termo_1_b') OR UPPER(ds_name) LIKE UPPER('$termo_1_c') OR UPPER(ds_name) = UPPER('$termo_1_d')))";                      break;
              case "cd_type":            $sql.= "WHERE cd_type IN ( ".
                                                "  SELECT cd_tipo FROM life_tipos WHERE UPPER(nm_tipo) LIKE UPPER('$termo_1_a') OR UPPER(nm_tipo) LIKE UPPER('$termo_1_b') OR UPPER(nm_tipo) LIKE UPPER('$termo_1_c') OR UPPER(nm_tipo) = UPPER('$termo_1_d')) ".
                                                ")";                                                                                                                                                                                                  break;
              case "ds_provides":        $sql.= "WHERE UPPER(ds_provides) LIKE UPPER('$termo_1_a') OR UPPER(ds_provides) LIKE UPPER('$termo_1_b') OR UPPER(ds_provides) LIKE UPPER('$termo_1_c') OR UPPER(ds_provides) = UPPER('$termo_1_d')))";      break;
              case "ds_essential":       $sql.= "WHERE UPPER(ds_essential) LIKE UPPER('$termo_1_a') OR UPPER(ds_essential) LIKE UPPER('$termo_1_b') OR UPPER(ds_essential) LIKE UPPER('$termo_1_c') OR UPPER(ds_essential) = UPPER('$termo_1_d')))";  break;
              case "ds_protocol":        $sql.= "WHERE UPPER(ds_protocol) LIKE UPPER('$termo_1_a') OR UPPER(ds_protocol) LIKE UPPER('$termo_1_b') OR UPPER(ds_protocol) LIKE UPPER('$termo_1_c') OR UPPER(ds_protocol) = UPPER('$termo_1_d')))";      break;
              case "ds_ontology":        $sql.= "WHERE UPPER(ds_ontology) LIKE UPPER('$termo_1_a') OR UPPER(ds_ontology) LIKE UPPER('$termo_1_b') OR UPPER(ds_ontology) LIKE UPPER('$termo_1_c') OR UPPER(ds_ontology) = UPPER('$termo_1_d')))";      break;
              case "cd_language":        $sql.= "WHERE cd_language IN ( ".
                                                "  SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d') ) ".
                                                ") ";                                                                                                                                                                                                 break;
              case "ds_details":         $sql.= "WHERE UPPER(ds_details) LIKE UPPER('$termo_1_a') OR UPPER(ds_details) LIKE UPPER('$termo_1_b') OR UPPER(ds_details) LIKE UPPER('$termo_1_c') OR UPPER(ds_details) = UPPER('$termo_1_d')))";          break;

              case "todos":
                $sql.= "WHERE UPPER(ds_name) LIKE UPPER('$termo_1_a') OR UPPER(ds_name) LIKE UPPER('$termo_1_b') OR UPPER(ds_name) LIKE UPPER('$termo_1_c') OR UPPER(ds_name) = UPPER('$termo_1_d') ".
                       "OR cd_type IN ( SELECT cd_tipo FROM life_tipos WHERE UPPER(nm_tipo) LIKE UPPER('$termo_1_a') OR UPPER(nm_tipo) LIKE UPPER('$termo_1_b') OR UPPER(nm_tipo) LIKE UPPER('$termo_1_c') OR UPPER(nm_tipo) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_provides) LIKE UPPER('$termo_1_a') OR UPPER(ds_provides) LIKE UPPER('$termo_1_b') OR UPPER(ds_provides) LIKE UPPER('$termo_1_c') OR UPPER(ds_provides) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_essential) LIKE UPPER('$termo_1_a') OR UPPER(ds_essential) LIKE UPPER('$termo_1_b') OR UPPER(ds_essential) LIKE UPPER('$termo_1_c') OR UPPER(ds_essential) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_protocol) LIKE UPPER('$termo_1_a') OR UPPER(ds_protocol) LIKE UPPER('$termo_1_b') OR UPPER(ds_protocol) LIKE UPPER('$termo_1_c') OR UPPER(ds_protocol) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_ontology) LIKE UPPER('$termo_1_a') OR UPPER(ds_ontology) LIKE UPPER('$termo_1_b') OR UPPER(ds_ontology) LIKE UPPER('$termo_1_c') OR UPPER(ds_ontology) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d') ) ".
                       "OR UPPER(ds_details) LIKE UPPER('$termo_1_a') OR UPPER(ds_details) LIKE UPPER('$termo_1_b') OR UPPER(ds_details) LIKE UPPER('$termo_1_c') OR UPPER(ds_details) = UPPER('$termo_1_d')))";
              break;
            }
          break;

          case "educational":
            $sql.= "AND oa.cd_educational IN ( SELECT cd_educational FROM life_educational ";        
            switch ($campo_1) {  
              case "ds_interactivity_type":           $sql.= "WHERE UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_interactivity_type) = UPPER('$termo_1_d'))";                           break;
              case "ds_learning_resource_type":       $sql.= "WHERE UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_learning_resource_type) = UPPER('$termo_1_d'))";           break;
              case "ds_interactivity_level":          $sql.= "WHERE UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_a') OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_b') OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_c') OR UPPER(ds_interactivity_level) = UPPER('$termo_1_d'))";                       break;
              case "ds_sem_antic_density":            $sql.= "WHERE UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_a') OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_b') OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_c') OR UPPER(ds_sem_antic_density) = UPPER('$termo_1_d'))";                               break;
              case "ds_intended_end_user_role":       $sql.= "WHERE UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_a') OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_b') OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_c') OR UPPER(ds_intended_end_user_role) = UPPER('$termo_1_d'))";           break;
              case "ds_context":                      $sql.= "WHERE UPPER(ds_context) LIKE UPPER('$termo_1_a') OR UPPER(ds_context) LIKE UPPER('$termo_1_b') OR UPPER(ds_context) LIKE UPPER('$termo_1_c') OR UPPER(ds_context) = UPPER('$termo_1_d'))";                                                                       break;
              case "ds_typical_age_range":            $sql.= "WHERE UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_a') OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_b') OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_c') OR UPPER(ds_typical_age_range) = UPPER('$termo_1_d'))";                               break;
              case "ds_difficulty":                   $sql.= "WHERE UPPER(ds_difficulty) LIKE UPPER('$termo_1_a') OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_b') OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_c') OR UPPER(ds_difficulty) = UPPER('$termo_1_d'))";                                                           break;
              case "ds_typical_learning_time":        $sql.= "WHERE UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_a') OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_b') OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_c') OR UPPER(ds_typical_learning_time) = UPPER('$termo_1_d'))";               break;
              case "ds_description":                  $sql.= "WHERE UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))";                                                       break;
              case "cd_language":                     $sql.= "WHERE cd_language IN ( ".
                                                             "  SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d') ".
                                                             ")";                                                                                                                                                                                                                                                              break;
              case "ds_learning_content_type":        $sql.= "WHERE UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_learning_content_type) = UPPER('$termo_1_d'))";               break;
              case "ds_interaction":                  $sql.= "WHERE UPPER(ds_interaction) LIKE UPPER('$termo_1_a') OR UPPER(ds_interaction) LIKE UPPER('$termo_1_b') OR UPPER(ds_interaction) LIKE UPPER('$termo_1_c') OR UPPER(ds_interaction) = UPPER('$termo_1_d'))";                                                       break;
              case "ds_didatic_strategy":             $sql.= "WHERE UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_a') OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_b') OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_c') OR UPPER(ds_didatic_strategy) = UPPER('$termo_1_d'))";                                   break;

              case "todos":        
                $sql.= "WHERE UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_interactivity_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_learning_resource_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_a') OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_b') OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_c') OR UPPER(ds_interactivity_level) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_a') OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_b') OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_c') OR UPPER(ds_sem_antic_density) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_a') OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_b') OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_c') OR UPPER(ds_intended_end_user_role) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_context) LIKE UPPER('$termo_1_a') OR UPPER(ds_context) LIKE UPPER('$termo_1_b') OR UPPER(ds_context) LIKE UPPER('$termo_1_c') OR UPPER(ds_context) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_a') OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_b') OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_c') OR UPPER(ds_typical_age_range) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_a') OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_b') OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_c') OR UPPER(ds_difficulty) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_a') OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_b') OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_c') OR UPPER(ds_typical_learning_time) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d') ) ".
                       "OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_learning_content_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_interaction) LIKE UPPER('$termo_1_a') OR UPPER(ds_interaction) LIKE UPPER('$termo_1_b') OR UPPER(ds_interaction) LIKE UPPER('$termo_1_c') OR UPPER(ds_interaction) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_a') OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_b') OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_c') OR UPPER(ds_didatic_strategy) = UPPER('$termo_1_d') )";
              break;
            }
          break;

          case "rights":
            $sql.= "AND oa.cd_rights = ( SELECT cd_rights FROM life_rights ";        
            switch ($campo_1) {  
              case "ds_cost":                                   $sql.= "WHERE UPPER(ds_cost) LIKE UPPER('$termo_1_a') OR UPPER(ds_cost) LIKE UPPER('$termo_1_b') OR UPPER(ds_cost) LIKE UPPER('$termo_1_c') OR UPPER(ds_cost) = UPPER('$termo_1_d'))";                                                                                                                        break;
              case "ds_copyright_and_other_restrictions":       $sql.= "WHERE UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_a') OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_b') OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_c') OR UPPER(ds_copyright_and_other_restrictions) = UPPER('$termo_1_d'))";        break;
              case "ds_description":                            $sql.= "WHERE UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))";                                                                                            break;

              case "todos":
                $sql.= "WHERE UPPER(ds_cost) LIKE UPPER('$termo_1_a') OR UPPER(ds_cost) LIKE UPPER('$termo_1_b') OR UPPER(ds_cost) LIKE UPPER('$termo_1_c') OR UPPER(ds_cost) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_a') OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_b') OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_c') OR UPPER(ds_copyright_and_other_restrictions) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d') )"; 
              break;
            }
          break;

          case "relation":
            $sql.= "AND oa.cd_relation = ( SELECT cd_relation FROM life_relation ";        
            switch ($campo_1) {  
              case "ds_kind":             $sql.= "WHERE UPPER(ds_kind) LIKE UPPER('$termo_1_a') OR UPPER(ds_kind) LIKE UPPER('$termo_1_b') OR UPPER(ds_kind) LIKE UPPER('$termo_1_c') OR UPPER(ds_kind) = UPPER('$termo_1_d'))";                      break;
              case "ds_resource":         $sql.= "WHERE UPPER(ds_resource) LIKE UPPER('$termo_1_a') OR UPPER(ds_resource) LIKE UPPER('$termo_1_b') OR UPPER(ds_resource) LIKE UPPER('$termo_1_c') OR UPPER(ds_resource) = UPPER('$termo_1_d'))";      break;

              case "todos":        
                $sql.= "WHERE UPPER(ds_kind) LIKE UPPER('$termo_1_a') OR UPPER(ds_kind) LIKE UPPER('$termo_1_b') OR UPPER(ds_kind) LIKE UPPER('$termo_1_c') OR UPPER(ds_kind) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_resource) LIKE UPPER('$termo_1_a') OR UPPER(ds_resource) LIKE UPPER('$termo_1_b') OR UPPER(ds_resource) LIKE UPPER('$termo_1_c') OR UPPER(ds_resource) = UPPER('$termo_1_d'))";
              break;
            }
          break;

          case "annotation":
            $sql.= "AND oa.cd_annotation = ( SELECT cd_annotation FROM life_annotation ";        
            switch ($campo_1) {  
              case "ds_entity":         $sql.= "WHERE UPPER(ds_entity) LIKE UPPER('$termo_1_a') OR UPPER(ds_entity) LIKE UPPER('$termo_1_b') OR UPPER(ds_entity) LIKE UPPER('$termo_1_c') OR UPPER(ds_entity) = UPPER('$termo_1_d'))";                        break;
              case "ds_date":           $sql.= "WHERE UPPER(ds_date) LIKE UPPER('$termo_1_a') OR UPPER(ds_date) LIKE UPPER('$termo_1_b') OR UPPER(ds_date) LIKE UPPER('$termo_1_c') OR UPPER(ds_date) = UPPER('$termo_1_d'))";                                break;
              case "ds_description":    $sql.= "WHERE UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))";    break;

              case "todos":        
                $sql.= "WHERE UPPER(ds_entity) LIKE UPPER('$termo_1_a') OR UPPER(ds_entity) LIKE UPPER('$termo_1_b') OR UPPER(ds_entity) LIKE UPPER('$termo_1_c') OR UPPER(ds_entity) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_date) LIKE UPPER('$termo_1_a') OR UPPER(ds_date) LIKE UPPER('$termo_1_b') OR UPPER(ds_date) LIKE UPPER('$termo_1_c') OR UPPER(ds_date) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))";    
              break;
            }
          break;

          case "classification":
            $sql.= "AND oa.cd_classification = ( SELECT cd_classification FROM life_classification ";                
            switch ($campo_1) {  
              case "ds_purpose":          $sql.= "WHERE UPPER(ds_purpose) LIKE UPPER('$termo_1_a') OR UPPER(ds_purpose) LIKE UPPER('$termo_1_b') OR UPPER(ds_purpose) LIKE UPPER('$termo_1_c') OR UPPER(ds_purpose) = UPPER('$termo_1_d'))";                    break;
              case "ds_taxon_path":       $sql.= "WHERE UPPER(ds_taxon_path) LIKE UPPER('$termo_1_a') OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_b') OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_c') OR UPPER(ds_taxon_path) = UPPER('$termo_1_d'))";        break;
              case "ds_description":      $sql.= "WHERE UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))";    break;
              case "ds_keyword":          $sql.= "WHERE UPPER(ds_keyword) LIKE UPPER('$termo_1_a') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_b') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_c') OR UPPER(ds_keyword) = UPPER('$termo_1_d'))";                    break;
              
              case "todos":        
                $sql.= "WHERE UPPER(ds_purpose) LIKE UPPER('$termo_1_a') OR UPPER(ds_purpose) LIKE UPPER('$termo_1_b') OR UPPER(ds_purpose) LIKE UPPER('$termo_1_c') OR UPPER(ds_purpose) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_a') OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_b') OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_c') OR UPPER(ds_taxon_path) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_keyword) LIKE UPPER('$termo_1_a') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_b') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_c') OR UPPER(ds_keyword) = UPPER('$termo_1_d'))";
              break;
            }  
          break;

          case "acessibility":
            $sql.= "AND oa.cd_acessibility = ( SELECT cd_acessibility FROM life_acessibility ";        
            switch ($campo_1) {  
              case "ds_has_visual":               $sql.= "WHERE UPPER(ds_has_visual) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_visual) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_visual) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_visual) = UPPER('$termo_1_d'))";                                        break;
              case "ds_has_audititory":           $sql.= "WHERE UPPER(ds_has_audititory) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_audititory) = UPPER('$termo_1_d'))";                        break;
              case "ds_has_text":                 $sql.= "WHERE UPPER(ds_has_text) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_text) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_text) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_text) = UPPER('$termo_1_d'))";                                                break;
              case "ds_has_tactible":             $sql.= "WHERE UPPER(ds_has_tactible) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_tactible) = UPPER('$termo_1_d'))";                                break;
              case "ds_earl_statment":            $sql.= "WHERE UPPER(ds_earl_statment) LIKE UPPER('$termo_1_a') OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_b') OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_c') OR UPPER(ds_earl_statment) = UPPER('$termo_1_d'))";                            break;
              case "ds_equivalent_resource":      $sql.= "WHERE UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_a') OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_b') OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_c') OR UPPER(ds_equivalent_resource) = UPPER('$termo_1_d'))";    break;

              case "todos":
                $sql.= "WHERE UPPER(ds_has_visual) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_visual) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_visual) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_visual) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_audititory) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_has_text) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_text) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_text) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_text) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_tactible) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_a') OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_b') OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_c') OR UPPER(ds_earl_statment) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_a') OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_b') OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_c') OR UPPER(ds_equivalent_resource) = UPPER('$termo_1_d') )";
              break;
            }
          break;

          case "segment_information_table":
            $sql.= "AND oa.cd_segment_information_table = ( SELECT cd_segment_information_table FROM life_segment_information_table ";        
            switch ($campo_1) {  
              case "ds_segment_list":        $sql.= "WHERE UPPER(ds_segment_list) LIKE UPPER('$termo_1_a') OR UPPER(ds_segment_list) LIKE UPPER('$termo_1_b') OR UPPER(ds_segment_list) LIKE UPPER('$termo_1_c') OR UPPER(ds_segment_list) = UPPER('$termo_1_d'))";    break;
              case "ds_segmente_group_list":        $sql.= "WHERE UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_a') OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_b') OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_c') OR UPPER(ds_segmente_group_list) = UPPER('$termo_1_d'))";    break;

              case "ds_segment_list":
                $sql.= "WHERE UPPER(ds_segment_list) LIKE UPPER('$termo_1_a') OR UPPER(ds_segment_list) LIKE UPPER('$termo_1_b') OR UPPER(ds_segment_list) LIKE UPPER('$termo_1_c') OR UPPER(ds_segment_list) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_a') OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_b') OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_c') OR UPPER(ds_segmente_group_list) = UPPER('$termo_1_d') )";
              break;
            }
          break;
          
          case "eh_todas_tabelas":
            $sql.= "AND ((UPPER(oa.nm_objeto_aprendizagem) LIKE UPPER('$termo_1_a') OR UPPER(oa.nm_objeto_aprendizagem) LIKE UPPER('$termo_1_b') OR UPPER(oa.nm_objeto_aprendizagem) LIKE UPPER('$termo_1_c') OR UPPER(oa.nm_objeto_aprendizagem) = UPPER('$termo_1_d')) ".
                   "OR  (oa.cd_general IN ( SELECT cd_general FROM life_general ".
            		       "WHERE UPPER(ds_identifier) LIKE UPPER('$termo_1_a') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_b') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_c') OR UPPER(ds_identifier) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_title) LIKE UPPER('$termo_1_a') OR UPPER(ds_title) LIKE UPPER('$termo_1_b') OR UPPER(ds_title) LIKE UPPER('$termo_1_c') OR UPPER(ds_title) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_keyword) LIKE UPPER('$termo_1_a') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_b') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_c') OR UPPER(ds_keyword) = UPPER('$termo_1_d') ".
                       "OR cd_coverage IN ( SELECT cd_area_conhecimento FROM life_areas_conhecimento WHERE UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_a') OR UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_b') OR UPPER(nm_area_conhecimento) LIKE UPPER('$termo_1_c') OR UPPER(nm_area_conhecimento) = UPPER('$termo_1_d') OR cd_area_conhecimento IN ( SELECT cd_area_conhecimento FROM life_sub_areas_conhecimento WHERE UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_a') OR UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_b') OR UPPER(nm_sub_area_conhecimento) LIKE UPPER('$termo_1_c') OR UPPER(nm_sub_area_conhecimento) = UPPER('$termo_1_d')) ) ".
                       "OR UPPER(ds_structure) LIKE UPPER('$termo_1_a') OR UPPER(ds_structure) LIKE UPPER('$termo_1_b') OR UPPER(ds_structure) LIKE UPPER('$termo_1_c') OR UPPER(ds_structure) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_a') OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_b') OR UPPER(ds_agregation_level) LIKE UPPER('$termo_1_c') OR UPPER(ds_agregation_level) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_lyfe_cycle IN ( SELECT cd_lyfe_cycle FROM life_lyfe_cycle ".        
	                     "WHERE UPPER(ds_version) LIKE UPPER('$termo_1_a') OR UPPER(ds_version) LIKE UPPER('$termo_1_b') OR UPPER(ds_version) LIKE UPPER('$termo_1_c') OR UPPER(ds_version) = UPPER('$termo_1_d') ".
                       "OR cd_status IN ( SELECT cd_status_ciclo_vida FROM life_status_ciclos_vida WHERE UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_a') OR UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_b') OR UPPER(nm_status_ciclo_vida) LIKE UPPER('$termo_1_c') OR UPPER(nm_status_ciclo_vida) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_contribute) LIKE UPPER('$termo_1_a') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_b') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_c') OR UPPER(ds_contribute) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_meta_metadata IN ( SELECT cd_meta_metadata FROM life_meta_metadata ".
                       "WHERE UPPER(ds_identifier) LIKE UPPER('$termo_1_a') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_b') OR UPPER(ds_identifier) LIKE UPPER('$termo_1_c') OR UPPER(ds_identifier) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_contribute) LIKE UPPER('$termo_1_a') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_b') OR UPPER(ds_contribute) LIKE UPPER('$termo_1_c') OR UPPER(ds_contribute) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_a') OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_b') OR UPPER(ds_metadata_schema) LIKE UPPER('$termo_1_c') OR UPPER(ds_metadata_schema) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d')))) ".
                   "OR (oa.cd_technical IN ( SELECT cd_technical FROM life_technical ".
                       "WHERE cd_format IN ( SELECT cd_formato FROM life_formatos WHERE UPPER(nm_formato) LIKE UPPER('$termo_1_a') OR UPPER(nm_formato) LIKE UPPER('$termo_1_b') OR UPPER(nm_formato) LIKE UPPER('$termo_1_c') OR UPPER(nm_formato) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_size) LIKE UPPER('$termo_1_a') OR UPPER(ds_size) LIKE UPPER('$termo_1_b') OR UPPER(ds_size) LIKE UPPER('$termo_1_c') OR UPPER(ds_size) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_location) LIKE UPPER('$termo_1_a') OR UPPER(ds_location) LIKE UPPER('$termo_1_b') OR UPPER(ds_location) LIKE UPPER('$termo_1_c') OR UPPER(ds_location) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_requirement) LIKE UPPER('$termo_1_a') OR UPPER(ds_requirement) LIKE UPPER('$termo_1_b') OR UPPER(ds_requirement) LIKE UPPER('$termo_1_c') OR UPPER(ds_requirement) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_composite) LIKE UPPER('$termo_1_a') OR UPPER(ds_composite) LIKE UPPER('$termo_1_b') OR UPPER(ds_composite) LIKE UPPER('$termo_1_c') OR UPPER(ds_composite) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_a') OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_b') OR UPPER(ds_installation_remarks) LIKE UPPER('$termo_1_c') OR UPPER(ds_installation_remarks) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_a') OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_b') OR UPPER(ds_other_plataforms_requirements) LIKE UPPER('$termo_1_c') OR UPPER(ds_other_plataforms_requirements) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_duration) LIKE UPPER('$termo_1_a') OR UPPER(ds_duration) LIKE UPPER('$termo_1_b') OR UPPER(ds_duration) LIKE UPPER('$termo_1_c') OR UPPER(ds_duration) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_a') OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_b') OR UPPER(ds_supported_plataform) LIKE UPPER('$termo_1_c') OR UPPER(ds_supported_plataform) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_technical = ( SELECT cd_technical FROM life_technical WHERE cd_plataform_specific_features = (SELECT cd_plataform_specific_features FROM life_plataform_specific_features ".
                       "WHERE UPPER(ds_plataform_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_plataform_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_plataform_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_plataform_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_format) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_format) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_size) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_size) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_location) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_location) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_requeriments) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_requeriments) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_instalation_remarks) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_instalation_remarks) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_a') OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_b') OR UPPER(ds_specific_other_plataform_requeriments) LIKE UPPER('$termo_1_c') OR UPPER(ds_specific_other_plataform_requeriments) = UPPER('$termo_1_d')))) ".
                   "OR (oa.cd_technical IN ( SELECT cd_technical FROM life_technical WHERE cd_service IN (SELECT cd_service FROM life_service ".
             		       "WHERE UPPER(ds_name) LIKE UPPER('$termo_1_a') OR UPPER(ds_name) LIKE UPPER('$termo_1_b') OR UPPER(ds_name) LIKE UPPER('$termo_1_c') OR UPPER(ds_name) = UPPER('$termo_1_d') ".
                       "OR cd_type IN ( SELECT cd_tipo FROM life_tipos WHERE UPPER(nm_tipo) LIKE UPPER('$termo_1_a') OR UPPER(nm_tipo) LIKE UPPER('$termo_1_b') OR UPPER(nm_tipo) LIKE UPPER('$termo_1_c') OR UPPER(nm_tipo) = UPPER('$termo_1_d')) ".
                       "OR UPPER(ds_provides) LIKE UPPER('$termo_1_a') OR UPPER(ds_provides) LIKE UPPER('$termo_1_b') OR UPPER(ds_provides) LIKE UPPER('$termo_1_c') OR UPPER(ds_provides) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_essential) LIKE UPPER('$termo_1_a') OR UPPER(ds_essential) LIKE UPPER('$termo_1_b') OR UPPER(ds_essential) LIKE UPPER('$termo_1_c') OR UPPER(ds_essential) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_protocol) LIKE UPPER('$termo_1_a') OR UPPER(ds_protocol) LIKE UPPER('$termo_1_b') OR UPPER(ds_protocol) LIKE UPPER('$termo_1_c') OR UPPER(ds_protocol) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_ontology) LIKE UPPER('$termo_1_a') OR UPPER(ds_ontology) LIKE UPPER('$termo_1_b') OR UPPER(ds_ontology) LIKE UPPER('$termo_1_c') OR UPPER(ds_ontology) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d') ) ".
                       "OR UPPER(ds_details) LIKE UPPER('$termo_1_a') OR UPPER(ds_details) LIKE UPPER('$termo_1_b') OR UPPER(ds_details) LIKE UPPER('$termo_1_c') OR UPPER(ds_details) = UPPER('$termo_1_d')))) ".
                   "OR (oa.cd_educational IN ( SELECT cd_educational FROM life_educational ".
                       "WHERE UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_interactivity_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_interactivity_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_learning_resource_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_learning_resource_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_a') OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_b') OR UPPER(ds_interactivity_level) LIKE UPPER('$termo_1_c') OR UPPER(ds_interactivity_level) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_a') OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_b') OR UPPER(ds_sem_antic_density) LIKE UPPER('$termo_1_c') OR UPPER(ds_sem_antic_density) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_a') OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_b') OR UPPER(ds_intended_end_user_role) LIKE UPPER('$termo_1_c') OR UPPER(ds_intended_end_user_role) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_context) LIKE UPPER('$termo_1_a') OR UPPER(ds_context) LIKE UPPER('$termo_1_b') OR UPPER(ds_context) LIKE UPPER('$termo_1_c') OR UPPER(ds_context) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_a') OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_b') OR UPPER(ds_typical_age_range) LIKE UPPER('$termo_1_c') OR UPPER(ds_typical_age_range) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_a') OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_b') OR UPPER(ds_difficulty) LIKE UPPER('$termo_1_c') OR UPPER(ds_difficulty) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_a') OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_b') OR UPPER(ds_typical_learning_time) LIKE UPPER('$termo_1_c') OR UPPER(ds_typical_learning_time) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d') ".
                       "OR cd_language IN ( SELECT cd_linguagem FROM life_linguagens WHERE UPPER(nm_linguagem) LIKE UPPER('$termo_1_a') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_b') OR UPPER(nm_linguagem) LIKE UPPER('$termo_1_c') OR UPPER(nm_linguagem) = UPPER('$termo_1_d') ) ".
                       "OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_a') OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_b') OR UPPER(ds_learning_content_type) LIKE UPPER('$termo_1_c') OR UPPER(ds_learning_content_type) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_interaction) LIKE UPPER('$termo_1_a') OR UPPER(ds_interaction) LIKE UPPER('$termo_1_b') OR UPPER(ds_interaction) LIKE UPPER('$termo_1_c') OR UPPER(ds_interaction) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_a') OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_b') OR UPPER(ds_didatic_strategy) LIKE UPPER('$termo_1_c') OR UPPER(ds_didatic_strategy) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_rights = ( SELECT cd_rights FROM life_rights ".
                       "WHERE UPPER(ds_cost) LIKE UPPER('$termo_1_a') OR UPPER(ds_cost) LIKE UPPER('$termo_1_b') OR UPPER(ds_cost) LIKE UPPER('$termo_1_c') OR UPPER(ds_cost) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_a') OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_b') OR UPPER(ds_copyright_and_other_restrictions) LIKE UPPER('$termo_1_c') OR UPPER(ds_copyright_and_other_restrictions) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_relation = ( SELECT cd_relation FROM life_relation ".
                       "WHERE UPPER(ds_kind) LIKE UPPER('$termo_1_a') OR UPPER(ds_kind) LIKE UPPER('$termo_1_b') OR UPPER(ds_kind) LIKE UPPER('$termo_1_c') OR UPPER(ds_kind) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_resource) LIKE UPPER('$termo_1_a') OR UPPER(ds_resource) LIKE UPPER('$termo_1_b') OR UPPER(ds_resource) LIKE UPPER('$termo_1_c') OR UPPER(ds_resource) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_annotation = ( SELECT cd_annotation FROM life_annotation ".
                       "WHERE UPPER(ds_entity) LIKE UPPER('$termo_1_a') OR UPPER(ds_entity) LIKE UPPER('$termo_1_b') OR UPPER(ds_entity) LIKE UPPER('$termo_1_c') OR UPPER(ds_entity) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_date) LIKE UPPER('$termo_1_a') OR UPPER(ds_date) LIKE UPPER('$termo_1_b') OR UPPER(ds_date) LIKE UPPER('$termo_1_c') OR UPPER(ds_date) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_classification = ( SELECT cd_classification FROM life_classification ".
                       "WHERE UPPER(ds_purpose) LIKE UPPER('$termo_1_a') OR UPPER(ds_purpose) LIKE UPPER('$termo_1_b') OR UPPER(ds_purpose) LIKE UPPER('$termo_1_c') OR UPPER(ds_purpose) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_a') OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_b') OR UPPER(ds_taxon_path) LIKE UPPER('$termo_1_c') OR UPPER(ds_taxon_path) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_description) LIKE UPPER('$termo_1_a') OR UPPER(ds_description) LIKE UPPER('$termo_1_b') OR UPPER(ds_description) LIKE UPPER('$termo_1_c') OR UPPER(ds_description) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_keyword) LIKE UPPER('$termo_1_a') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_b') OR UPPER(ds_keyword) LIKE UPPER('$termo_1_c') OR UPPER(ds_keyword) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_acessibility = ( SELECT cd_acessibility FROM life_acessibility ".
                       "WHERE UPPER(ds_has_visual) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_visual) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_visual) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_visual) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_audititory) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_audititory) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_has_text) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_text) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_text) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_text) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_a') OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_b') OR UPPER(ds_has_tactible) LIKE UPPER('$termo_1_c') OR UPPER(ds_has_tactible) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_a') OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_b') OR UPPER(ds_earl_statment) LIKE UPPER('$termo_1_c') OR UPPER(ds_earl_statment) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_a') OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_b') OR UPPER(ds_equivalent_resource) LIKE UPPER('$termo_1_c') OR UPPER(ds_equivalent_resource) = UPPER('$termo_1_d'))) ".
                   "OR (oa.cd_segment_information_table = ( SELECT cd_segment_information_table FROM life_segment_information_table ".
                       "WHERE UPPER(ds_segment_list) LIKE UPPER('$termo_1_a') OR UPPER(ds_segment_list) LIKE UPPER('$termo_1_b') OR UPPER(ds_segment_list) LIKE UPPER('$termo_1_c') OR UPPER(ds_segment_list) = UPPER('$termo_1_d') ".
                       "OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_a') OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_b') OR UPPER(ds_segmente_group_list) LIKE UPPER('$termo_1_c') OR UPPER(ds_segmente_group_list) = UPPER('$termo_1_d')))) ";
          break;
        }
      } 
      if ($ordem == 'oa') {
        $sql.= " ORDER BY oa.nm_objeto_aprendizagem, pe.nm_pessoa ";
      } elseif ($ordem == 'pe') {
        $sql.= " ORDER BY pe.nm_pessoa, oa.nm_objeto_aprendizagem ";
      }
      if ($limite != "") {
        $sql.= "LIMIT ".$inicio.", ".$limite." ";
      }
//echo "<br /><hr><br />".$sql."<br /><hr><br />";      
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

/*

    public function selectObjetosAprendizagemApresentacao($categoria, $profissao, $professor, $ordem, $inicio, $quantidade) {

      if ($categoria > 0) {  $incluir_categoria = true;  } else {  $incluir_categoria = false;  }

      if ($profissao > 0) {  $incluir_profissao = true;  } else {  $incluir_profissao = false;  }

      if ($professor > 0) {  $incluir_professor = true;  } else {  $incluir_professor = false;  }

      

      $sql  = "SELECT cur.*, pre.* ".

              "FROM life_objetos_aprendizagem cur, life_cursos_precos pre ";

      if ($incluir_categoria) {

        $sql .= ", life_cursos_categoria cat, life_cursos_categorias cur_cat ";

      }

      if ($incluir_profissao) {

        $sql .= ", life_profissoes pro, life_cursos_profissoes cur_pro ";

      }  

      if ($incluir_professor) {

        $sql .= ", life_professores prf, life_cursos_professores cur_prf ";

      }  

          

      if ($incluir_categoria) {

        $sql .= "WHERE cur_cat.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                "AND cur_cat.cd_categoria = cat.cd_categoria ";

        if ($incluir_profissao) {        

          $sql .= "AND cur_pro.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                  "AND cur_pro.cd_profissao = pro.cd_profissao ";

        }

        if ($incluir_professor) {        

          $sql .= "AND cur_prf.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                  "AND cur_prf.cd_professor = prf.cd_professor ";

        }

      } elseif ($incluir_profissao) {

        $sql .= "WHERE cur_pro.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                "AND cur_pro.cd_profissao = pro.cd_profissao ";

        if ($incluir_professor) {        

          $sql .= "AND cur_prf.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                  "AND cur_prf.cd_professor = prf.cd_professor ";

        }

      } elseif ($incluir_professor) {        

        $sql .= "WHERE cur_prf.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                "AND cur_prf.cd_professor = prf.cd_professor ";

      } else {        

        $sql .= "WHERE cur.cd_objeto_aprendizagem > 0 ";

      }

      $sql.= "AND cur.eh_ativo = '1' ".

             "AND pre.eh_ativo = '1' ".

             "AND cur.cd_objeto_aprendizagem = pre.cd_objeto_aprendizagem ";

      if ($categoria > 0) {

        $sql.= "AND cat.cd_categoria = '$categoria' ";

      }   

      if ($profissao > 0) {

        $sql.= "AND pro.cd_profissao = '$profissao' ";

      }                                     

      if ($professor > 0) {

        $sql.= "AND prf.cd_professor = '$professor' ";

      }                                     

      if ($ordem == 'mais_recentes') {

        $sql.= "ORDER BY cur.dt_cadastro DESC ";

      } elseif ($ordem == 'mais_antigos') {

        $sql.= "ORDER BY cur.dt_cadastro ";

      } elseif ($ordem == 'mais_populares') {

        $sql.= "ORDER BY cur.nr_acessos_total DESC ";

      } elseif ($ordem == 'menos_populares') {

        $sql.= "ORDER BY cur.nr_acessos_total ";

      } else {

        $sql.= "ORDER BY cur.nm_curso ";

      } 

      $sql.= "LIMIT $inicio, $quantidade ";

      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");

      $dados = array();

      while ($linha = mysql_fetch_assoc($result_id) ) {

          $dados[] = $linha;

      }

      return $dados;    

    }

    

    public function selectQuantidadeObjetosAprendizagemApresentacao($categoria, $profissao, $professor) {

      if ($categoria > 0) {  $incluir_categoria = true;  } else {  $incluir_categoria = false;  }

      if ($profissao > 0) {  $incluir_profissao = true;  } else {  $incluir_profissao = false;  }

      if ($professor > 0) {  $incluir_professor = true;  } else {  $incluir_professor = false;  }

      

      $sql  = "SELECT COUNT(cur.cd_objeto_aprendizagem) qtd ".

              "FROM life_objetos_aprendizagem cur ";

      if ($incluir_categoria) {

        $sql .= ", life_cursos_categoria cat, life_cursos_categorias cur_cat ";

      }

      if ($incluir_profissao) {

        $sql .= ", life_profissoes pro, life_cursos_profissoes cur_pro ";

      }  

      if ($incluir_professor) {

        $sql .= ", life_professores prf, life_cursos_professores cur_prf ";

      }  

          

      if ($incluir_categoria) {

        $sql .= "WHERE cur_cat.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                "AND cur_cat.cd_categoria = cat.cd_categoria ";

        if ($incluir_profissao) {        

          $sql .= "AND cur_pro.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                  "AND cur_pro.cd_profissao = pro.cd_profissao ";

        }

        if ($incluir_professor) {        

          $sql .= "AND cur_prf.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                  "AND cur_prf.cd_professor = prf.cd_professor ";

        }

      } elseif ($incluir_profissao) {

        $sql .= "WHERE cur_pro.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                "AND cur_pro.cd_profissao = pro.cd_profissao ";

        if ($incluir_professor) {        

          $sql .= "AND cur_prf.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                  "AND cur_prf.cd_professor = prf.cd_professor ";

        }

      } elseif ($incluir_professor) {        

        $sql .= "WHERE cur_prf.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".

                "AND cur_prf.cd_professor = prf.cd_professor ";

      } else {        

        $sql .= "WHERE cur.cd_objeto_aprendizagem > 0 ";

      }

      $sql.= "AND cur.eh_ativo = '1' ";

      if ($categoria > 0) {

        $sql.= "AND cat.cd_categoria = '$categoria' ";

      }   

      if ($profissao > 0) {

        $sql.= "AND pro.cd_profissao = '$profissao' ";

      }                        

      if ($professor > 0) {

        $sql.= "AND prf.cd_professor = '$professor' ";

      }                                     

      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");

      $dados = mysql_fetch_assoc($result_id);

      return $dados['qtd'];

    }

     
                                                */
    public function selectDadosObjetoAprendizagem($cd_objeto_aprendizagem) {       
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem ".

              "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";

      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");

      $dados= mysql_fetch_assoc($result_id);

      return $dados;        

    }

                                                  /*

    public function selectDadosObjetoAprendizagemSEO($lk_seo) {

      $sql  = "SELECT * ".

              "FROM life_objetos_aprendizagem ".

              "WHERE lk_seo = '$lk_seo' ";

      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");

      $dados= mysql_fetch_assoc($result_id);

      return $dados;        

    }



    public function selectDadosObjetoAprendizagemChave($ds_chave) {

      $sql  = "SELECT * ".

              "FROM life_objetos_aprendizagem ".

              "WHERE ds_chave = '$ds_chave' ";

      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");

      $dados= mysql_fetch_assoc($result_id);

      return $dados;        

    }




                                             */
    private function inserirObjetoAprendizagem($nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');                   
      $sql = "INSERT INTO life_objetos_aprendizagem ".
             "(nm_objeto_aprendizagem, cd_general, eh_informar_general, cd_lyfe_cycle, eh_informar_lyfe_cycle, cd_meta_metadata, eh_informar_meta_metadata, cd_technical, eh_informar_technical, cd_educational, eh_informar_educational, cd_rights, eh_informar_rights, cd_relation, eh_informar_relation, cd_annotation, eh_informar_annotation, cd_classification, eh_informar_classification, cd_acessibility, eh_informar_acessibility, cd_segment_information_table, eh_informar_segment_information_table, eh_ativo, eh_liberado, cd_usuario_proprietario, lk_seo, ds_informacoes_o_a, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_objeto_aprendizagem\", \"$cd_general\", \"$eh_informar_general\", \"$cd_lyfe_cycle\", \"$eh_informar_lyfe_cycle\", \"$cd_meta_metadata\", \"$eh_informar_meta_metadata\", \"$cd_technical\", \"$eh_informar_technical\", \"$cd_educational\", \"$eh_informar_educational\", \"$cd_rights\", \"$eh_informar_rights\", \"$cd_relation\", \"$eh_informar_relation\", \"$cd_annotation\", \"$eh_informar_annotation\", \"$cd_classification\", \"$eh_informar_classification\", \"$cd_acessibility\", \"$eh_informar_acessibility\", \"$cd_segment_information_table\", \"$eh_informar_segment_information_table\", \"$eh_ativo\", \"$eh_liberado\", \"$cd_usuario_proprietario\", \"$lk_seo\", \"$ds_informacoes_o_a\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_objeto_aprendizagem) codigo ".
                "FROM life_objetos_aprendizagem ".
                "WHERE nm_objeto_aprendizagem  = '$nm_objeto_aprendizagem' ".
                "AND cd_general = '$cd_general' ".
                "AND cd_lyfe_cycle = '$cd_lyfe_cycle' ".
                "AND cd_meta_metadata = '$cd_meta_metadata' ".
                "AND cd_technical = '$cd_technical' ".
                "AND cd_educational = '$cd_educational' ".
                "AND cd_rights = '$cd_rights' ".
                "AND cd_relation = '$cd_relation' ".
                "AND cd_annotation = '$cd_annotation' ".
                "AND cd_classification = '$cd_classification' ".
                "AND cd_acessibility = '$cd_acessibility' ".
                "AND cd_segment_information_table = '$cd_segment_information_table' ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];        
      } else {
        return '0';
      }     
    }

    private function alterarObjetoAprendizagem($cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $eh_informar_general, $eh_informar_lyfe_cycle, $eh_informar_meta_metadata, $eh_informar_technical, $eh_informar_educational, $eh_informar_rights, $eh_informar_relation, $eh_informar_annotation, $eh_informar_classification, $eh_informar_acessibility, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_objetos_aprendizagem SET ".
             "nm_objeto_aprendizagem = \"$nm_objeto_aprendizagem\", ".
             "eh_informar_general = \"$eh_informar_general\", ".
             "eh_informar_lyfe_cycle = \"$eh_informar_lyfe_cycle\", ".
             "eh_informar_meta_metadata = \"$eh_informar_meta_metadata\", ".
             "eh_informar_technical = \"$eh_informar_technical\", ".
             "eh_informar_educational = \"$eh_informar_educational\", ".
             "eh_informar_rights = \"$eh_informar_rights\", ".
             "eh_informar_relation = \"$eh_informar_relation\", ".
             "eh_informar_annotation = \"$eh_informar_annotation\", ".
             "eh_informar_classification = \"$eh_informar_classification\", ".
             "eh_informar_acessibility = \"$eh_informar_acessibility\", ".
             "eh_informar_segment_information_table = \"$eh_informar_segment_information_table\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "eh_liberado = \"$eh_liberado\", ".
             "cd_usuario_proprietario = \"$cd_usuario_proprietario\", ".
             "lk_seo = \"$lk_seo\", ".
             "ds_informacoes_o_a = \"$ds_informacoes_o_a\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");
      $saida = mysql_affected_rows();
      return $saida;     
    }   
/*
    

    private function ajustarNumeroAcessos($cd_objeto_aprendizagem, $nr_acessos_publicos, $nr_acessos_pagos, $nr_acessos_total) {

      $sql = "UPDATE life_objetos_aprendizagem SET ".

             "nr_acessos_publicos = \"$nr_acessos_publicos\", ". 

             "nr_acessos_pagos = \"$nr_acessos_pagos\", ".           

             "nr_acessos_total = \"$nr_acessos_total\" ".             

             "WHERE cd_objeto_aprendizagem= '$cd_objeto_aprendizagem' ";

      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM");

      $saida = mysql_affected_rows();

      return $saida;     

    } 

                     */

  }
?>                   