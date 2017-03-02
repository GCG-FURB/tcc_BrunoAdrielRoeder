<?php
  class ConteudoInterno {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item, $acao, $ativas, $curso, $cd_conteudo, $nm_tipo_associacao, $edicao_permitida) {
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['tl']))    {      $tipo_link = addslashes($_GET['tl']);       } else {      $tipo_link = '';      }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
          $this->listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
        break;

        case "cadastrar":
          if ($edicao_permitida) {
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas;
            $this->montarFormularioCadastro($link, $cd_conteudo);
          } else {
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
            $this->listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
          }
        break;

        case "editar":
          if ($edicao_permitida) {
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas;
            $this->montarFormularioEdicao($link, $cd_conteudo, $codigo);
          } else {
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
            $this->listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
          }
        break;
        
        case "salvar":
          if ((isset($_SESSION['c_o_edicao'])) && ($edicao_permitida)) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['c_o_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
          $this->listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
        break;        
               
        case "status":
          if ($edicao_permitida) {
            $this->alterarSituacaoAtivoConteudoInterno($codigo);
          }
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
          $this->listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
        break;

        case "subir":
          $this->alterarOrdemItem($codigo, $curso, $cd_conteudo, 'd');
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
          $this->listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
        break;

        case "descer":
          $this->alterarOrdemItem($codigo, $curso, $cd_conteudo, 'i');
          $this->listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
          $this->listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida);
        break;
        
        case "fotos":
          require_once 'conteudos/fotos.php';                                   $foto= new Fotos();
          $edicao = $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          $foto->listarFotos($link, $codigo, $nm_tipo_associacao, $edicao, false);
        break;  

        case "cad_foto":
          require_once 'conteudos/fotos.php';                                   $foto= new Fotos();
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          if ($this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo)) {
            $foto->montarFormularioCadastroFoto($link, $codigo, $nm_tipo_associacao);
          } else {
            $foto->listarFotos($link, $codigo, $nm_tipo_associacao, false, false);            
          }
        break;

        case "edi_foto":
          require_once 'conteudos/fotos.php';                                   $foto= new Fotos();
          $edicao = $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          if (isset($_GET['ft'])) { 
            $foto->montarFormularioEdicaoFoto($link, $codigo, $nm_tipo_associacao);
          } else {
            $foto->listarFotos($link, $codigo, $nm_tipo_associacao, false);
          }
        break;        

        case "salv_foto":
          require_once 'conteudos/fotos.php';                                   $foto= new Fotos();
          $edicao = $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          if ((isset($_SESSION['c_o_edicao'])) && ($edicao)) {
            $foto->salvarFoto();
            unset($_SESSION['c_o_edicao']);
          }
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          $foto->listarFotos($link, $codigo, $nm_tipo_associacao, $edicao, false);
        break;                
  
        case "exc_foto":
          require_once 'conteudos/fotos.php';                                   $foto= new Fotos();
          $edicao = $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          if ((isset($_GET['ft'])) && ($edicao)) {
            $foto->exclusaoFoto($link, $codigo, $nm_tipo_associacao);
          }      
          $foto->listarFotos($link, $codigo, $nm_tipo_associacao, $edicao, false);
        break;  
        

        case "arquivos":
          require_once 'conteudos/arquivos.php';                                $arq= new Arquivo();
          $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          $arq->listarArquivos($link, $codigo, $nm_tipo_associacao, $edicao);
        break;  

        case "cad_arquivo":
          require_once 'conteudos/arquivos.php';                                $arq= new Arquivo();
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          if ($this->listarAcoesArquivos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo)) {
            $arq->montarFormularioCadastro($link, $codigo, $nm_tipo_associacao);
          } else {
            $arq->listarArquivos($link, $codigo, $nm_tipo_associacao, $edicao);
          }
        break;
      
        case "edi_arquivo":
          require_once 'conteudos/arquivos.php';                                $arq= new Arquivo();
          $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          if ((isset($_GET['ar'])) && ($edicao)) { 
            $cd_arquivo = addslashes($_GET['ar']);
            $arq->montarFormularioEdicao($link, $codigo, $nm_tipo_associacao, $cd_arquivo);
          } else {
            $arq->listarArquivos($link, $codigo, $nm_tipo_associacao, $edicao);
          }
        break;

        case "salv_arquivo":
          require_once 'conteudos/arquivos.php';                                $arq= new Arquivo();
          $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          if ((isset($_SESSION['c_o_edicao'])) && ($edicao)) {
            $arq->salvarArquivo();
            unset($_SESSION['c_o_edicao']);
          }
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          $arq->listarArquivos($link, $codigo, $nm_tipo_associacao, $edicao);
        break;        

        case "exc_arquivo":
          require_once 'conteudos/arquivos.php';                                $arq= new Arquivo();
          $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo;
          if ((isset($_GET['ar'])) && ($edicao)) {
            $cd_arquivo = addslashes($_GET['ar']);
            $arq->excluirArquivo($codigo, $nm_tipo_associacao, $cd_arquivo);
          }      
          $arq->listarArquivos($link, $codigo, $nm_tipo_associacao, $edicao);
        break;   

        case "links":
          require_once 'conteudos/conteudos_links.php';                         $lk = new ConteudoLink();
          if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
          $edicao = $this->listarAcoesLinks($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo, $tipo_link, $lk_ativos);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
          $lk->listarLinks($link, $codigo, $nm_tipo_associacao, $tipo_link, $edicao, $lk_ativos);
        break;  
          
        case "cad_link":
          require_once 'conteudos/conteudos_links.php';                         $lk = new ConteudoLink();
          if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
          if ($this->listarAcoesLinks($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo, $tipo_link, $lk_ativos)) {
            $lk->montarFormularioCadastro($link, $codigo, $nm_tipo_associacao, $tipo_link);
          } else {
            $lk->listarLinks($link, $codigo, $nm_tipo_associacao, $tipo_link, $edicao, $lk_ativos);
          }
        break;
          
        case "edi_link":
          require_once 'conteudos/conteudos_links.php';                         $lk = new ConteudoLink();
          if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
          $edicao = $this->listarAcoesLinks($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo, $tipo_link, $lk_ativos);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
          if ((isset($_GET['cl'])) && ($edicao)) { 
            $cd_link = addslashes($_GET['cl']);
             $lk->montarFormularioEdicao($link, $codigo, $nm_tipo_associacao, $tipo_link, $cd_link);
          } else {
            $lk->listarLinks($link, $codigo, $nm_tipo_associacao, $tipo_link, $edicao, $lk_ativos);
          }
        break;

        case "salv_link":
          require_once 'conteudos/conteudos_links.php';                         $lk = new ConteudoLink();
          if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
          $edicao = $this->listarAcoesLinks($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo, $tipo_link, $lk_ativos);
          if ((isset($_SESSION['c_o_edicao'])) && ($edicao)) {
            $lk->salvarLink();
            unset($_SESSION['c_o_edicao']);
          }
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
          $lk->listarLinks($link, $codigo, $nm_tipo_associacao, $tipo_link, $edicao, $lk_ativos);
        break;        
          
        case "sta_link":
          require_once 'conteudos/conteudos_links.php';                         $lk = new ConteudoLink();
          if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
          $edicao = $this->listarAcoesLinks($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $codigo, $tipo_link, $lk_ativos);
          if ((isset($_GET['cl'])) && ($edicao)) {
            $cd_link = addslashes($_GET['cl']);
            $lk->alteraStatusLink($codigo, $nm_tipo_associacao, $tipo_link, $cd_link); 
          }      
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
          $lk->listarLinks($link, $codigo, $nm_tipo_associacao, $tipo_link, $edicao, $lk_ativos);
        break;  
 
      }
    }
                                  

    private function listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_curso, $cd_conteudo, $cd_conteudo_interno) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoCurso();
      
      $dados = $con_cur->selectDadosCompletosCursoConteudo($cd_curso, $cd_conteudo);

      $permite_edicao_conteudo_qualquer_usuario = $conf->permitirEdicaoConteudoQualquerUsuario();
      
      $retorno = true;
      if (!$permite_edicao_conteudo_qualquer_usuario) {
        $cd_usuario = $_SESSION['c_o_codigo'];
        if ($dados['cd_usuario_cadastro'] != $cd_usuario) {
          if (isset($_SESSION['c_o_permissoes'])) {
            $permissoes_usuario= $_SESSION['c_o_permissoes'];
          } else {
            $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
          } 
          if ($permissoes_usuario[7] == 0) {
            echo "<p class=\"fontConteudoAlerta\">Usuário Logado não tem permissão para Editar Fotos/Imagens do Conteúdo Interno cadastrado por outro usuário!</p>\n"; 
            $retorno = false;            
          }
        }
      }

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      if ($retorno) {
        echo "  <img src=\"icones/vazio.png\">\n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&acao=cad_foto\"><img src=\"icones/novo_foto.png\" alt=\"Nova Foto/Imagem\" title=\"Nova Foto/Imagem\" border=\"0\"></a> \n";
      }
      echo "</p>\n";

      echo "<h2>Conteúdo: ".$dados['tt_conteudo']."</h2>\n";
      $conteudo = $this->selectDadosConteudoInterno($cd_conteudo_interno);
      echo "<h3>Sub-Conteúdo: ".$conteudo['tt_conteudo']."</h3>\n";      

      return $retorno;
    }

    private function listarAcoesArquivos($secao, $subsecao, $item, $ativas, $cd_curso, $cd_conteudo, $cd_conteudo_interno) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoCurso();
      
      $dados = $con_cur->selectDadosCompletosCursoConteudo($cd_curso, $cd_conteudo);

      $permite_edicao_conteudo_qualquer_usuario = $conf->permitirEdicaoConteudoQualquerUsuario();
      
      $retorno = true;
      if (!$permite_edicao_conteudo_qualquer_usuario) {
        $cd_usuario = $_SESSION['c_o_codigo'];
        if ($dados['cd_usuario_cadastro'] != $cd_usuario) {
          if (isset($_SESSION['c_o_permissoes'])) {
            $permissoes_usuario= $_SESSION['c_o_permissoes'];
          } else {
            $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
          } 
          if ($permissoes_usuario[7] == 0) {
            echo "<p class=\"fontConteudoAlerta\">Usuário Logado não tem permissão para Editar Arquivos do Conteúdo de Curso cadastrado por outro usuário!</p>\n"; 
            $retorno = false;            
          }
        }
      }

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      if ($retorno) {
        echo "  <img src=\"icones/vazio.png\">\n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&acao=cad_arquivo\"><img src=\"icones/novo_arquivo.png\" alt=\"Novo Arquivo\" title=\"Novo Arquivo\" border=\"0\"></a> \n";
      }
      echo "</p>\n";

      echo "<h2>Conteúdo: ".$dados['tt_conteudo']."</h2>\n";
      $conteudo = $this->selectDadosConteudoInterno($cd_conteudo_interno);
      echo "<h3>Sub-Conteúdo: ".$conteudo['tt_conteudo']."</h3>\n";      

      return $retorno;
    }    

    private function listarAcoesLinks($secao, $subsecao, $item, $ativas, $cd_curso, $cd_conteudo, $cd_conteudo_interno, $tipo_link, $lk_ativos) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/conteudos_cursos.php';                            $con_cur = new ConteudoCurso();

      
      $dados = $con_cur->selectDadosCompletosCursoConteudo($cd_curso, $cd_conteudo);

      $permite_edicao_conteudo_qualquer_usuario = $conf->permitirEdicaoConteudoQualquerUsuario();
      
      $retorno = true;
      if (!$permite_edicao_conteudo_qualquer_usuario) {
        $cd_usuario = $_SESSION['c_o_codigo'];
        if ($dados['cd_usuario_cadastro'] != $cd_usuario) {
          if (isset($_SESSION['c_o_permissoes'])) {
            $permissoes_usuario= $_SESSION['c_o_permissoes'];
          } else {
            $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
          } 
          if ($permissoes_usuario[7] == 0) {
            echo "<p class=\"fontConteudoAlerta\">Usuário Logado não tem permissão para Editar Links do Conteúdo de Curso cadastrado por outro usuário!</p>\n"; 
            $retorno = false;            
          }
        }
      }
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&tl=".$tipo_link."&lat=1&acao=links";           $opcao['descricao']= "Ativos";                                        $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&tl=".$tipo_link."&lat=0&acao=links";           $opcao['descricao']= "Inativos";                                      $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&tl=".$tipo_link."&lat=2&acao=links";           $opcao['descricao']= "Ativos/Inativos";                               $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                                                                                                                     $opcao['descricao']= "----------------------------------------";      $opcoes[]= $opcao;

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      if ($retorno) {
        echo "  <img src=\"icones/vazio.png\">\n";
        if ($tipo_link == '1') {
          echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&tl=1&lat=".$lk_ativos."&acao=cad_link\"><img src=\"icones/novo_video.png\" alt=\"Novo Vídeo\" title=\"Novo Vídeo\" border=\"0\"></a> \n";
        } elseif ($tipo_link == '2') {
          echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&tl=2&lat=".$lk_ativos."&acao=cad_link\"><img src=\"icones/novo_audio.png\" alt=\"Novo Áudio\" title=\"Novo Áudio\" border=\"0\"></a> \n";
        } elseif ($tipo_link == '4') {
          echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$cd_curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$cd_conteudo_interno."&tl=4&lat=".$lk_ativos."&acao=cad_link\"><img src=\"icones/novo_referencia.png\" alt=\"Nova Referência\" title=\"Novo Referência\" border=\"0\"></a> \n";
        }
      }
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";

      echo "<h2>Conteúdo: ".$dados['tt_conteudo']."</h2>\n";
      $conteudo = $this->selectDadosConteudoInterno($cd_conteudo_interno);
      echo "<h3>Sub-Conteúdo: ".$conteudo['tt_conteudo']."</h3>\n";       
      return $retorno;
    }    
    
    public function listarAcoes($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&ct=".$cd_conteudo."&at=1";                 $opcao['descricao']= "Ativos";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&ct=".$cd_conteudo."&at=0";                 $opcao['descricao']= "Inativos";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&ct=".$cd_conteudo."&at=2";                 $opcao['descricao']= "Ativos/Inativos";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                                 $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."\"><img src=\"icones/voltar.png\" alt=\"Voltar ao Cadastro de Matérias\" title=\"Voltar ao Cadastro de Matérias\" border=\"0\"></a> \n";
      if ($edicao_permitida) {
        echo "  <img src=\"icones/vazio.png\">\n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Conteúdo para a Matéria\" title=\"Novo Conteúdo para a Matéria\" border=\"0\"></a> \n";
      }
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas, $curso, $cd_conteudo, $edicao_permitida) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $itens = $this->selectConteudoInternos($ativas, $cd_conteudo);    

      $mensagem = "Conteúdos Internos ";
      if ($ativas == 1) {             $mensagem.= "Ativos ";      } elseif ($ativas == 0) {       $mensagem.= "Inativos ";      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">\n";
      echo "        <img src=\"icones/seta_sobe.png\" alt=\"Ordem de Apresentação\" title=\"Ordem de Apresentação\" border=\"0\">\n";
      echo "        <img src=\"icones/seta_desce.png\" alt=\"Ordem de Apresentação\" title=\"Ordem de Apresentação\" border=\"0\">\n";
      echo "      </td>\n";
      echo "      <td class=\"celConteudo\">Ordem:</td>\n";
      echo "      <td class=\"celConteudo\">Conteúdo:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      $primeiro = true;
      $id = 0;    
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        $id += 1;
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">\n";
        if ($primeiro) {
          $primeiro = false;
          echo "        <img src=\"icones/vaziop.png\">\n";
        } else {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&acao=subir\"><img src=\"icones/ordem_subir.png\" alt=\"Subir na Ordem de Apresentação (Mais para o Início)\" title=\"Subir na Ordem de Apresentação (Mais para o Início)\" border=\"0\"></a>\n";
        }
        if ($id < count($itens)) {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&acao=descer\"><img src=\"icones/ordem_descer.png\" alt=\"Descer na Ordem de Apresentação (Mais para o Fim)\" title=\"Descer na Ordem de Apresentação (Mais para o Fim)\" border=\"0\"></a>\n";
        } else {
          echo "        <img src=\"icones/vaziop.png\">\n";
        }
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        if ($it['tt_conteudo'] != '') { 
          echo "      <td class=\"celConteudo\">".$it['tt_conteudo']."</td>\n";
        } else {
          echo "      <td class=\"celConteudo\">".$util->abreviar($it['ds_conteudo'], 100)."</td>\n";
        }
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharConteudoInterno($it['cd_conteudo_interno']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&acao=fotos\"><img src=\"icones/fotos.png\" alt=\"Fotos/Imagens\" title=\"Fotos/Imagens\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&acao=arquivos\"><img src=\"icones/arquivos.png\" alt=\"Arquivos (.pdf)\" title=\"Arquivos (.pdf)\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&tl=1&acao=links\"><img src=\"icones/videos.png\" alt=\"Vídeos (Links Externos)\" title=\"Vídeos (Links Externos)\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&tl=2&acao=links\"><img src=\"icones/audios.png\" alt=\"Áudios (Links Externos)\" title=\"Áudios (Links Externos)\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cs=".$curso."&tp=adicionais&ct=".$cd_conteudo."&at=".$ativas."&cd=".$it['cd_conteudo_interno']."&tl=4&acao=links\"><img src=\"icones/referencias.png\" alt=\"Refências (Links Externos)\" title=\"Refências (Links Externos)\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
    }
    
    public function detalharConteudoInterno($cd_conteudo_interno) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosConteudoInterno($cd_conteudo_interno);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
    
    private function utilizarNumeroOrdem($cd_conteudo) {
      $ultimo_numero_ordem = $this->selectMaiorNumeroOrdemConteudo($cd_conteudo);
      return $ultimo_numero_ordem + 1; 
    }    
     
    private function montarFormularioCadastro($link, $cd_conteudo) {
      $cd_conteudo_interno = "";
      $tt_conteudo = "";
      $ds_conteudo = "";
      $eh_publico = "0";
      $nr_ordem = $this->utilizarNumeroOrdem($cd_conteudo);
      $eh_ativo = "1";
      
      $_SESSION['c_o_edicao'] = 1;
      echo "  <h2>Cadastro de Conteúdos Internos dos Cursos</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_conteudo_interno, $cd_conteudo, $tt_conteudo, $ds_conteudo, $eh_publico, $nr_ordem, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_conteudo, $cd_conteudo_interno) {
      $dados = $this->selectDadosConteudoInterno($cd_conteudo_interno);
      
      if ($dados['cd_conteudo'] != $cd_conteudo) {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao recuperar Conteúdo Interno do Curso para edição!</p>\n";
        return false;
      }
      
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $eh_publico = $dados['eh_publico'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['c_o_edicao'] = 1;
      echo "  <h2>Edição de Conteúdo Interno de Curso</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_conteudo_interno, $cd_conteudo, $tt_conteudo, $ds_conteudo, $eh_publico, $nr_ordem, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_conteudo_interno, $cd_conteudo, $tt_conteudo, $ds_conteudo, $eh_publico, $nr_ordem, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_conteudo_interno.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";

      $util->campoHidden('cd_conteudo_interno', $cd_conteudo_interno);
      $util->campoHidden('cd_conteudo', $cd_conteudo);

      $util->linhaUmCampoText(0, 'Título: ', 'tt_conteudo', 150, 100, $tt_conteudo);
      $util->linhaTexto(1, 'Conteúdo: ', 'ds_conteudo', $ds_conteudo, '15', '965');

      $util->linhaUmCampoText(0, 'Ordem de Apresentação: ', 'nr_ordem', 11, 20, $nr_ordem);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Público: ', 'eh_publico', $eh_publico, $opcoes);
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      if ($cd_conteudo_interno > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'tt_conteudo'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_conteudo_interno = addslashes($_POST['cd_conteudo_interno']);
      $cd_conteudo = addslashes($_POST['cd_conteudo']);
      $tt_conteudo = $util->limparVariavel($_POST['tt_conteudo']);
      $ds_conteudo = $util->limparVariavel($_POST['ds_conteudo']);
      $nr_ordem = addslashes($_POST['nr_ordem']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_conteudo_interno > 0) {
        if ($this->alterarConteudoInterno($cd_conteudo_interno, $cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo)) {
          echo "<p class=\"fontConteudoSucesso\">Conteúdo Interno editado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Conteúdo Interno, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->inserirConteudoInterno($cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo)) {
          echo "<p class=\"fontConteudoSucesso\">Conteúdo Interno cadastrado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Conteúdo Interno!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoConteudoInterno($cd_conteudo_interno) {
      $dados = $this->selectDadosConteudoInterno($cd_conteudo_interno);

      $cd_conteudo = $dados['cd_conteudo'];
      $nr_ordem = $dados['nr_ordem'];
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alterarConteudoInterno($cd_conteudo_interno, $cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Conteúdo Interno alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do Conteúdo Interno!</p>\n";
      }
    }
    
    public function alterarOrdemItem($cd_conteudo_interno, $cd_curso, $cd_conteudo, $acao) {
      $dados = $this->selectDadosConteudoInterno($cd_conteudo_interno);
      $nr_ordem = $dados['nr_ordem'];
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $eh_ativo = $dados['eh_ativo'];
      
      if ($acao == 'i') {        $nr_ordem += 1;      } elseif ($acao == 'd') {        $nr_ordem -= 1;      }

      if ($this->alterarConteudoInterno($cd_conteudo_interno, $cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo)) {
        echo "<p class=\"fontConteudoSucesso\">Ordem do Conteúdo Interno ajustada com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar a ordem do Conteúdo Interno!</p>\n";
      }
    }      
                                                                              
//************************EXIBICAO**********************************************
    public function listarConteudoCompletoCurso($cd_conteudo, $acesso_liberado) {
      $dados = $this->selectConteudoInternos('1', $cd_conteudo);

      foreach ($dados as $d) {
        if (($acesso_liberado) || ($d['eh_publico'])) {                               
          require_once 'conteudos/fotos.php';                                   $foto = new Fotos();
          require_once 'conteudos/arquivos.php';                                $arq  = new Arquivo();
          require_once 'conteudos/conteudos_links.php';                         $con_lin = new ConteudoLink();
          require_once 'conteudos/conteudos_internos.php';                      $con_int = new ConteudoInterno();

          if ($d['tt_conteudo'] != '') {
            echo "  <h2>".$d['tt_conteudo']."</h2>\n";
          }
          echo "  <p>".nl2br($d['ds_conteudo'])."</p>\n";
          
          $foto->exibeFotosAreaCentral($d['cd_conteudo_interno'], 'CI');

          $tp_link = '1'; //Vídeos
          $con_lin->retornaRelacaoLinks('CI', $d['cd_conteudo_interno'], $tp_link, $acesso_liberado);
          $tp_link = '2'; //Áudios
          $con_lin->retornaRelacaoLinks('CI', $d['cd_conteudo_interno'], $tp_link, $acesso_liberado);
          $tp_link = '4'; //Referências
          $con_lin->retornaRelacaoLinks('CI', $d['cd_conteudo_interno'], $tp_link, $acesso_liberado);

          $arq->retornaRelacaoArquivosDownload('CI', $d['cd_conteudo_interno'], $acesso_liberado);
        }
      }
    }  

//**************BANCO DE DADOS**************************************************    
    public function selectConteudoInternos($eh_ativo, $cd_conteudo) {
      $sql  = "SELECT * ".
              "FROM c_o_conteudos_internos ".
              "WHERE cd_conteudo = '$cd_conteudo' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nr_ordem, tt_conteudo, ds_conteudo";
      $result_id = @mysql_query($sql) or die ("CONTEUDOS INTERNOS - Erro no banco de dados!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectMaiorNumeroOrdemConteudo($cd_conteudo) {
      $sql  = "SELECT MAX(nr_ordem) numero ".
              "FROM c_o_conteudos_internos ".
              "WHERE cd_conteudo = '$cd_conteudo' "; 
      $result_id = @mysql_query($sql) or die ("CONTEUDOS INTERNOS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['numero'];        
    }     
                   
    public function selectDadosConteudoInterno($cd_conteudo_interno) {
      $sql  = "SELECT * ".
              "FROM c_o_conteudos_internos ".
              "WHERE cd_conteudo_interno = '$cd_conteudo_interno' ";
      $result_id = @mysql_query($sql) or die ("CONTEUDOS INTERNOS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
                           
    public function inserirConteudoInterno($cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo) {
      $cd_usuario_cadastro = $_SESSION['c_o_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO c_o_conteudos_internos ".
             "(cd_conteudo, nr_ordem, eh_ativo, tt_conteudo, ds_conteudo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_conteudo\", \"$nr_ordem\", \"$eh_ativo\", \"$tt_conteudo\", \"$ds_conteudo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'conteudos_internos');            
      mysql_query($sql) or die ("CONTEUDOS INTERNOS - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alterarConteudoInterno($cd_conteudo_interno, $cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['c_o_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE c_o_conteudos_internos SET ".
             "eh_ativo = \"$eh_ativo\", ".
             "tt_conteudo = \"$tt_conteudo\", ".
             "ds_conteudo = \"$ds_conteudo\", ".
             "nr_ordem = \"$nr_ordem\", ".             
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_conteudo_interno= '$cd_conteudo_interno' ".
             "AND cd_conteudo = \"$cd_conteudo\"";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'conteudos_internos');            
      mysql_query($sql) or die ("CONTEUDOS INTERNOS - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    


  }
?>