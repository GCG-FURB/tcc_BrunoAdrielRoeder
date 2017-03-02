<?php
  class ObjetoAprendizagemConteudo {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item, $cd_objeto_aprendizagem, $acao) {

      if (isset($_GET['atv']))    {      $ativas = addslashes($_GET['atv']);          } else {      $ativas = 1;          }
      if (isset($_GET['cdc']))    {      $codigo = addslashes($_GET['cdc']);          } else {      $codigo = '';         }
      if (isset($_GET['tp']))     {      $tipo = addslashes($_GET['tp']);             } else {      $tipo = '';           }
      if (isset($_GET['tl']))     {      $tipo_link = addslashes($_GET['tl']);        } else {      $tipo_link = '';      }
      
/*      
      if ($tipo == 'adicionais') {
        require_once 'conteudos/conteudos.php';                                 $con = new Conteudo();
        require_once 'includes/configuracoes.php';                              $conf = new Configuracao();
        require_once 'conteudos/conteudos_internos.php';                        $con_int = new ConteudoInterno();

        if (isset($_GET['ct']))    {      
          $conteudo = addslashes($_GET['ct']);     
        } else {
          echo "<p class=\"fontConteudoAlerta\">Selecione o Conteúdo Principal do(s) Sub-Conteúdo(s)!</p>\n";
          $this->controleExibicao($secao, $subsecao, $item);
          return false;        
        }      

        $dados_conteudo = $con->selectDadosConteudo($conteudo);
        echo "<h3>Conteúdo: ".$dados_conteudo['tt_conteudo']."</h3>\n";

        $permite_edicao_conteudo_qualquer_usuario = $conf->permitirEdicaoConteudoQualquerUsuario();
      
        $edicao = true;
        if (!$permite_edicao_conteudo_qualquer_usuario) {
          $cd_usuario = $_SESSION['life_codigo'];
          if ($dados_conteudo['cd_usuario_cadastro'] != $cd_usuario) {
            if (isset($_SESSION['life_permissoes'])) {
              $permissoes_usuario= $_SESSION['life_permissoes'];
            } else {
              $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
            } 
            if ($permissoes_usuario[7] == 0) {
              echo "<p class=\"fontConteudoAlerta\">Usuário Logado não tem permissão para Editar Fotos/Imagens do Conteúdo de Curso cadastrado por outro usuário!</p>\n"; 
              $edicao = false;            
            }
          }
        }

        $con_int->controleExibicao($secao, $subsecao, $item, $acao, $ativas, $cd_objeto_aprendizagem, $conteudo, 'CI', $edicao);
        
      } else {
*/      
        switch ($acao) {
          case "":
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;

          case "cadastrar":
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
            $this->montarFormularioCadastro($link, $cd_objeto_aprendizagem);
          break;

          case "editar":
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas;
            $this->montarFormularioEdicao($link, $cd_objeto_aprendizagem, $codigo);
          break;

          case "salvar":
            if (isset($_SESSION['life_edicao'])) {
              $this->salvarCadastroAlteracao($cd_objeto_aprendizagem);
              unset($_SESSION['life_edicao']);
            } 
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;        

          case "alt_status":
            $this->alterarStatusItem($codigo, $cd_objeto_aprendizagem);
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;

          case "subir":
            $this->alterarOrdemItem($codigo, $cd_objeto_aprendizagem, 'd');
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;

          case "descer":
            $this->alterarOrdemItem($codigo, $cd_objeto_aprendizagem, 'i');
            $this->listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
            $this->listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas);
          break;

          case "fotos":
            require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
            $this->listarAcoesFotos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, true);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $foto->listarFotos($link, $codigo, 'CC');
          break;  

          case "cad_foto":
            require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $this->listarAcoesFotos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, false);
            $foto->montarFormularioCadastroFoto($link, $codigo, 'CC');
          break;
      
          case "edi_foto":
            require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $this->listarAcoesFotos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, false);
            $foto->montarFormularioEdicaoFoto($link, $codigo, 'CC');
          break;
                     
          case "salv_foto":
            require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $this->listarAcoesFotos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, true);
            if (isset($_SESSION['life_edicao'])) {
              $foto->salvarFoto();
              unset($_SESSION['life_edicao']);
            }
            $foto->listarFotos($link, $codigo, 'CC');
          break;        

          case "exc_foto":
            require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $this->listarAcoesFotos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, true);
            if (isset($_GET['ft'])) {
              $foto->exclusaoFoto($link, $codigo, 'CC');
            }      
            $foto->listarFotos($link, $codigo, 'CC');
          break;    

          case "arquivos":
            require_once 'conteudos/arquivos.php';                              $arq= new Arquivo();
            $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, true);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $arq->listarArquivos($link, $codigo, 'CC');
          break;  

          case "cad_arquivo":
            require_once 'conteudos/arquivos.php';                              $arq= new Arquivo();
            $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, false);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $arq->montarFormularioCadastro($link, $codigo, 'CC');
          break;
      
          case "edi_arquivo":
            require_once 'conteudos/arquivos.php';                              $arq= new Arquivo();
            $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, false);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            $cd_arquivo = addslashes($_GET['ar']);
            $arq->montarFormularioEdicao($link, $codigo, 'CC', $cd_arquivo);
          break;

          case "salv_arquivo":
            require_once 'conteudos/arquivos.php';                              $arq= new Arquivo();
            $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, true);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            if (isset($_SESSION['life_edicao'])) {
              $arq->salvarArquivo();
              unset($_SESSION['life_edicao']);
            }
            $arq->listarArquivos($link, $codigo, 'CC');
          break;        

          case "exc_arquivo":
            require_once 'conteudos/arquivos.php';                              $arq= new Arquivo();
            $edicao = $this->listarAcoesArquivos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, true);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo;
            if (isset($_GET['ar'])) {
              $cd_arquivo = addslashes($_GET['ar']);
              $arq->excluirArquivo($codigo, 'CC', $cd_arquivo);
            }      
            $arq->listarArquivos($link, $codigo, 'CC');
          break;   

          case "links":
            require_once 'conteudos/conteudos_links.php';                       $lk = new ConteudoLink();
            if ($tipo_link != '1') {
              if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
            } else {
              $lk_ativos = '1';
            }  
            $this->listarAcoesLinks($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, $tipo_link, $lk_ativos, true);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
            $lk->listarLinks($link, $codigo, 'CC', $tipo_link, $lk_ativos);
          break;   
                    
          case "cad_link":
            require_once 'conteudos/conteudos_links.php';                       $lk = new ConteudoLink();
            if ($tipo_link != '1') {
              if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
            } else {
              $lk_ativos = '1';
            }  
            $this->listarAcoesLinks($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, $tipo_link, $lk_ativos, false);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
            if ($tipo_link == '1') {
              if (!$lk->possuiLink('1', $codigo, 'CC')) { 
                $lk->montarFormularioCadastro($link, $codigo, 'CC', $tipo_link);
              } else {
                $lk->listarLinks($link, $codigo, 'CC', $tipo_link, $lk_ativos);
              }
            } else { 
              $lk->montarFormularioCadastro($link, $codigo, 'CC', $tipo_link);
            }
          break;
          
          case "edi_link":
            require_once 'conteudos/conteudos_links.php';                       $lk = new ConteudoLink();
            if ($tipo_link != '1') {
              if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
            } else {
              $lk_ativos = '1';
            }  
            $this->listarAcoesLinks($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, $tipo_link, $lk_ativos, false);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
            if (isset($_GET['cl'])) { 
              $cd_link = addslashes($_GET['cl']);
              $lk->montarFormularioEdicao($link, $codigo, 'CC', $tipo_link, $cd_link);
            } else {
              $lk->listarLinks($link, $codigo, 'CC', $tipo_link, $lk_ativos, false);
            }
          break;

          case "salv_link":
            require_once 'conteudos/conteudos_links.php';                       $lk = new ConteudoLink();
            if ($tipo_link != '1') {
              if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
            } else {
              $lk_ativos = '1';
            }  
            $this->listarAcoesLinks($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, $tipo_link, $lk_ativos, true);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
            if (isset($_SESSION['life_edicao'])) {
              $lk->salvarLink();
              unset($_SESSION['life_edicao']);
            }
            $lk->listarLinks($link, $codigo, 'CC', $tipo_link, $lk_ativos);
          break;        
          
          case "sta_link":
            require_once 'conteudos/conteudos_links.php';                       $lk = new ConteudoLink();
            if ($tipo_link != '1') {
              if (isset($_GET['lat'])) {   $lk_ativos = addslashes($_GET['lat']);  } else {   $lk_ativos = 1;        }
            } else {
              $lk_ativos = '1';
            }  
            $this->listarAcoesLinks($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $codigo, $tipo_link, $lk_ativos, true);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$codigo."&lat=".$lk_ativos."&tl=".$tipo_link;
            if (isset($_GET['cl'])) {
              $cd_link = addslashes($_GET['cl']);
              $lk->alteraStatusLink($codigo, 'CC', $tipo_link, $cd_link); 
            }      
            $lk->listarLinks($link, $codigo, 'CC', $tipo_link, $lk_ativos);
          break;       
        }              
//      }
    }
                             
    private function listarAcoesFotos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $cd_conteudo, $liberar_cadastro) {
      $dados = $this->selectDadosConteudo($cd_conteudo);
      
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      if ($liberar_cadastro) {
        echo "  <img src=\"icones/vazio.png\">\n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&cdc=".$cd_conteudo."&atv=".$ativas."&acao=cad_foto\"><img src=\"icones/novo_foto.png\" alt=\"Nova Foto/Imagem\" title=\"Nova Foto/Imagem\" border=\"0\"></a> \n";
      }
      echo "</p>\n";
      
      echo "<h3>Conteúdo: ".$dados['tt_conteudo']."</h3>\n";
    }
    
    private function listarAcoesArquivos($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $cd_conteudo, $liberar_cadastro) {
      $dados = $this->selectDadosConteudo($cd_conteudo);

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      if ($liberar_cadastro) {
        echo "  <img src=\"icones/vazio.png\">\n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$cd_conteudo."&acao=cad_arquivo\"><img src=\"icones/novo_arquivo.png\" alt=\"Novo Arquivo\" title=\"Novo Arquivo\" border=\"0\"></a> \n";
      }
      echo "</p>\n";
      echo "<h3>Conteúdo: ".$dados['tt_conteudo']."</h3>\n";
    }    
    
    private function listarAcoesLinks($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas, $cd_conteudo, $tipo, $lk_ativos, $liberar_cadastro) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();

      $dados = $this->selectDadosConteudo($cd_conteudo);
 
      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$cd_conteudo."&tl=".$tipo."&lat=1&acao=links";           $opcao['descricao']= "Ativos";                                        $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$cd_conteudo."&tl=".$tipo."&lat=0&acao=links";           $opcao['descricao']= "Inativos";                                      $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$cd_conteudo."&tl=".$tipo."&lat=2&acao=links";           $opcao['descricao']= "Ativos/Inativos";                               $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                                                                                                  $opcao['descricao']= "----------------------------------------";      $opcoes[]= $opcao;

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      if ($tipo == '1') {
        if (!$con_lin->possuiLink('1', $cd_conteudo, 'CC')) {
          echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$cd_conteudo."&tl=1&lat=".$lk_ativos."&acao=cad_link\"><img src=\"icones/novo_video.png\" alt=\"Novo Vídeo\" title=\"Novo Vídeo\" border=\"0\"></a> \n";
        }
      } elseif ($tipo == '2') {
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$cd_conteudo."&tl=2&lat=".$lk_ativos."&acao=cad_link\"><img src=\"icones/novo_audio.png\" alt=\"Novo Áudio\" title=\"Novo Áudio\" border=\"0\"></a> \n";
      } elseif ($tipo == '4') {
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$cd_conteudo."&tl=4&lat=".$lk_ativos."&acao=cad_link\"><img src=\"icones/novo_referencia.png\" alt=\"Nova Referência\" title=\"Novo Referência\" border=\"0\"></a> \n";
      }
      if ($tipo != '1') {
        echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
        echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
        foreach ($opcoes as $op) {
          echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
        }
        echo "  </select>\n";
      }
      echo "</p>\n";

      echo "<h2>Conteúdo: ".$dados['tt_conteudo']."</h2>\n";
    }    
    
    private function listarAcoes($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $opcoes= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=1";                               $opcao['descricao']= "Ativos";                                        $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=0";                               $opcao['descricao']= "Inativos";                                      $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=2";                               $opcao['descricao']= "Ativos/Inativos";                               $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                                                         $opcao['descricao']= "----------------------------------------";      $opcoes[]= $opcao;

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Conteúdo do Objeto de Aprendizagem\" title=\"Novo Conteúdo do Objeto de Aprendizagem\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";    
    }

    private function listarItens($secao, $subsecao, $item, $cd_objeto_aprendizagem, $ativas) {
      $itens = $this->selectObjetosAprendizagemConteudos($ativas, $cd_objeto_aprendizagem);    

      $mensagem = "Conteúdos ";
      if ($ativas == 1) {        $mensagem.= "Ativos";      } elseif ($ativas == 0) {        $mensagem.= "Inativos";      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" style=\"width: 50px;\">\n";
      echo "        <img src=\"icones/seta_sobe.png\" alt=\"Ordem de Apresentação\" title=\"Ordem de Apresentação\" border=\"0\">\n";
      echo "        <img src=\"icones/seta_desce.png\" alt=\"Ordem de Apresentação\" title=\"Ordem de Apresentação\" border=\"0\">\n";
      echo "      </td>\n";
      echo "      <td class=\"celConteudo\" style=\"width: 60px;\">Ordem:</td>\n";
      echo "      <td class=\"celConteudo\">Conteúdo:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";  
      $primeiro = true;
      $id = 0;    
      foreach ($itens as $it) {
        $id += 1;
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">\n";
        if ($primeiro) {
          $primeiro = false;
          echo "        <img src=\"icones/vaziop.png\">\n";
        } else {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&acao=subir\"><img src=\"icones/ordem_subir.png\" alt=\"Subir na Ordem de Apresentação (Mais para o Início)\" title=\"Subir na Ordem de Apresentação (Mais para o Início)\" border=\"0\"></a>\n";
        }
        if ($id < count($itens)) {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&acao=descer\"><img src=\"icones/ordem_descer.png\" alt=\"Descer na Ordem de Apresentação (Mais para o Fim)\" title=\"Descer na Ordem de Apresentação (Mais para o Fim)\" border=\"0\"></a>\n";
        } else {
          echo "        <img src=\"icones/vaziop.png\">\n";
        }
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['tt_conteudo']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharObjetoAprendizagemConteudo($cd_objeto_aprendizagem, $it['cd_conteudo']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&acao=alt_status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&acao=fotos\"><img src=\"icones/fotos.png\" alt=\"Fotos/Imagens\" title=\"Fotos/Imagens\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&acao=arquivos\"><img src=\"icones/arquivos.png\" alt=\"Arquivos (.pdf)\" title=\"Arquivos (.pdf)\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&tl=1&acao=links\"><img src=\"icones/videos.png\" alt=\"Vídeos (Links Externos)\" title=\"Vídeos (Links Externos)\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&tl=2&acao=links\"><img src=\"icones/audios.png\" alt=\"Áudios (Links Externos)\" title=\"Áudios (Links Externos)\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&cdc=".$it['cd_conteudo']."&tl=4&acao=links\"><img src=\"icones/referencias.png\" alt=\"Refências (Links Externos)\" title=\"Refências (Links Externos)\" border=\"0\"></a>\n";

/*
        //echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&fc=conteudo&cd=".$cd_objeto_aprendizagem."&atv=".$ativas."&ct=".$it['cd_conteudo']."&tp=adicionais\"><img src=\"icones/conteudos.png\" alt=\"Conteúdos Adicionais do Conteúdo de Curso\" title=\"Conteúdos Adicionais do Conteúdo de Curso\" border=\"0\"></a>\n";
*/        
        echo "      </td>\n";
        echo "    </tr>\n";              
      }
      echo "  </table>\n";
      echo "  <br /><br />\n";       
    }
    
    private function detalharObjetoAprendizagemConteudo($cd_objeto_aprendizagem, $cd_conteudo) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $dados = $this->selectDadosObjetoAprendizagemConteudo($cd_objeto_aprendizagem, $cd_conteudo);
     
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
    
    
    private function utilizarNumeroOrdem($cd_objeto_aprendizagem) {
      $ultimo_numero_ordem = $this->selectMaiorNumeroOrdemObjetoAprendizagem($cd_objeto_aprendizagem);
      return $ultimo_numero_ordem + 1; 
    }

    public function montarFormularioCadastro($link, $cd_objeto_aprendizagem) {
      $cd_conteudo = "";
      $nr_ordem = $this->utilizarNumeroOrdem($cd_objeto_aprendizagem);
      $eh_ativo = "1";
      $tt_conteudo = "";
      $ds_conteudo = "";

      echo "<h2>Cadastro de Conteúdos</h2>\n";
      $_SESSION['life_edicao']= 1;
      $this->imprimeFormularioCadastro($link, $cd_objeto_aprendizagem, $cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo);
    }

    public function montarFormularioEdicao($link, $cd_objeto_aprendizagem, $cd_conteudo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      
      $dados = $this->selectDadosConteudo($cd_conteudo);
      if ($dados['cd_objeto_aprendizagem'] != $cd_objeto_aprendizagem) {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao relacionar Objeto de Aprendizagem com Conteúdo!</p>\n";
        return false;            
      }
      
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];

      echo "<h2>Edição de Conteúdo</h2>\n";
      $_SESSION['life_edicao']= 1;      
      $this->imprimeFormularioCadastro($link, $cd_objeto_aprendizagem, $cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo);
    }    

    private function imprimeFormularioCadastro($link, $cd_objeto_aprendizagem, $cd_conteudo, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";
      
      include "js/js_cadastro_conteudos.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_conteudo', $cd_conteudo);
      $util->campoHidden('cd_objeto_aprendizagem', $cd_objeto_aprendizagem);

      $util->linhaUmCampoTextHint(0, 'Título', 'tt_conteudo', '150', '840', $tt_conteudo, 1);
      $util->linhaTextoHint(1, 'Conteúdo', 'ds_conteudo', $ds_conteudo, '10', '840', 1);

      $opcoes= array();
      for ($i=1;$i<($nr_ordem+100);$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;      $opcoes[]= $opcao;
      }
      $util->linhaSeletorHint('Ordem de Apresentação', 'nr_ordem', $nr_ordem, $opcoes, '840', '1', 1);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletorHint('É Ativo', 'eh_ativo', $eh_ativo, $opcoes, '840', '1', 1);

      $util->linhaBotao('Salvar');  
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'tt_conteudo'); 
    }

    public function salvarCadastroAlteracao($cd_objeto_aprendizagem) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $cd_objeto_aprendizagem_post = addslashes($_POST['cd_objeto_aprendizagem']);
      if ($cd_objeto_aprendizagem_post != $cd_objeto_aprendizagem) {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao relacionar Objeto de Aprendizagem ao Conteúdo!</p>\n"; 
        return false;
      }
      
      $cd_conteudo =  addslashes($_POST['cd_conteudo']);
      $nr_ordem = addslashes($_POST['nr_ordem']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      $tt_conteudo = $util->limparVariavel($_POST['tt_conteudo']);
      $ds_conteudo = $util->limparVariavel($_POST['ds_conteudo']);

      $lk_seo = $util->retornaLinkSEO($tt_conteudo, 'life_objetos_aprendizagem_conteudos', 'lk_seo', '150', 'cd_conteudo');
      
      if ($cd_conteudo > 0) {
        if ($this->editarObjetoAprendizagemConteudo($cd_conteudo, $cd_objeto_aprendizagem, $tt_conteudo, $ds_conteudo, $nr_ordem, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Conteúdo do Objeto de Aprendizagem editado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Conteúdo do Objeto de Aprendizagem, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->inserirObjetoAprendizagemConteudo($cd_objeto_aprendizagem, $tt_conteudo, $ds_conteudo, $nr_ordem, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Conteúdo do Objeto de Aprendizagem cadastrado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Conteúdo do Objeto de Aprendizagem!</p>\n";
        }
      }
    } 
                       
    public function alterarStatusItem($cd_conteudo, $cd_objeto_aprendizagem) {
      $dados = $this->selectDadosConteudo($cd_conteudo);
      if ($dados['cd_objeto_aprendizagem'] != $cd_objeto_aprendizagem) {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao relacionar Objeto de Aprendizagem com Conteúdo!</p>\n";
        return false;            
      }
      
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];
      
      if ($eh_ativo == 1) {        $eh_ativo= 0;      } else {        $eh_ativo= 1;      }
      if ($this->editarObjetoAprendizagemConteudo($cd_conteudo, $cd_objeto_aprendizagem, $tt_conteudo, $ds_conteudo, $nr_ordem, $eh_ativo, $lk_seo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Conteúdo do Curso alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do Conteúdo do Curso!</p>\n";
      }
    } 

    public function alterarOrdemItem($cd_conteudo, $cd_objeto_aprendizagem, $acao) {
      $dados = $this->selectDadosConteudo($cd_conteudo);
      if ($dados['cd_objeto_aprendizagem'] != $cd_objeto_aprendizagem) {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao relacionar Objeto de Aprendizagem com Conteúdo!</p>\n";
        return false;            
      }
      
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];
      
      if ($acao == 'i') {        $nr_ordem += 1;      } elseif ($acao == 'd') {        $nr_ordem -= 1;      }

      if ($this->editarObjetoAprendizagemConteudo($cd_conteudo, $cd_objeto_aprendizagem, $tt_conteudo, $ds_conteudo, $nr_ordem, $eh_ativo, $lk_seo)) {
        echo "<p class=\"fontConteudoSucesso\">Ordem do Conteúdo do Curso ajustada com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar a ordem do Conteúdo do Curso!</p>\n";
      }
    }    
                                      /*
    public function retornaTempoTotalObjetoAprendizagem($cd_objeto_aprendizagem) {    
      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      //require_once 'conteudos/conteudos_internos.php';                          $con_int = new ConteudoInterno();

      $conteudos = $this->selectObjetosAprendizagemConteudosExibicao($cd_objeto_aprendizagem);
      $tm_total_video = '00:00:00';
      $videos = $con_lin->selectLinks('1', $cd_objeto_aprendizagem, 'VI', '1');
      foreach ($videos as $v) {
        $tm_total_video = $dh->somarHoras($tm_total_video, $v['tm_video']); 
      }
      foreach ($conteudos as $c) {
        $videos = $con_lin->selectLinks('1', $c['cd_conteudo'], 'CC', '1');
        foreach ($videos as $v) {
          $tm_total_video = $dh->somarHoras($tm_total_video, $v['tm_video']);
        }        
        /*
        $conteudos_internos = $con_int->selectConteudoInternos('1', $c['cd_conteudo']);
        foreach ($conteudos_internos as $ci) {
          $videos = $con_lin->selectLinks('1', $ci['cd_conteudo_interno'], 'CI', '1');
          foreach ($videos as $v) {
            $tm_total_video = $dh->somarHoras($tm_total_video, $v['tm_video']);
          }        
        }
        * 
      }      
      return $tm_total_video; 
    }  
    
    public function retornaQuantidadesConteudos($cd_objeto_aprendizagem) {
      $conteudos = $this->selectObjetosAprendizagemConteudosExibicao($cd_objeto_aprendizagem);
      return count($conteudos);
    }      
    
    public function retornaQuantidadeArquivosConteudos($cd_objeto_aprendizagem) {
      require_once 'conteudos/arquivos.php';                                    $arq  = new Arquivo();
      $conteudos = $this->selectObjetosAprendizagemConteudosExibicao($cd_objeto_aprendizagem);
      $total_arquivos = 0;
      foreach ($conteudos as $c) {
        $total_arquivos += $arq->retornaQuantidadeArquivosDownload('CC', $c['cd_conteudo'], true);
      }
      return $total_arquivos;
    }
    
         */
//**********************************EXIBIÇÃO************************************
/*
    public function retornaListaConteudosIniciaisObjetoAprendizagem($lista_paginas, $cd_objeto_aprendizagem) {
      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new ObjetoAprendizagemAlunoInscricao();
      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();
      //require_once 'conteudos/conteudos_internos.php';                          $con_int = new ConteudoInterno();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $acesso_liberado = $cur_alu_ins->validarInscricaoAlunoObjetoAprendizagem($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      $conteudos = $this->selectObjetosAprendizagemConteudosExibicao($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      
      if (count($conteudos) > 0) {
        require_once 'conteudos/cursos_alunos_acessos_conteudos.php';           $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoConteudo();
        echo "          		    <p>&nbsp;</p>\n";
        echo "                  <div class=\"twelvecol column\">\n";
        echo "                    <h1>Aulas grátis</h1>\n";
        $qtd_aulas_gratis = $this->retornaListaGratisPago($lista_paginas, $cd_objeto_aprendizagem, $acesso_liberado, $conteudos, '1', '0');
        echo "          	        <br><br>\n";
        echo "                    <h1>Curso completo</h1>\n";
        $this->retornaListaGratisPago($lista_paginas, $cd_objeto_aprendizagem, $acesso_liberado, $conteudos, '0', $qtd_aulas_gratis);
        echo "                  </div>\n";
        if (isset($_SESSION['life_acesso_curso_situacao'])) {
          if ($_SESSION['life_acesso_curso_situacao'] == '1') {
            require_once 'login/login.php';                                         $log = new Login();
            if ($log->estaLogado()) {
              if (isset($_SESSION['life_acesso_curso_motivo'])) {
                if ($_SESSION['life_acesso_curso_motivo'] == '1') {
                  $mensagem = 'Prazo para finalização do curso expirado!';
                } elseif ($_SESSION['life_acesso_curso_motivo'] == '2') {
                  $mensagem = 'Nossos Sistema não identificou o Pagamento!';
                } elseif ($_SESSION['life_acesso_curso_motivo'] == '3') {
                  $mensagem = 'Não identificamos sua inscrição!';
                }
              } else {
                $mensagem = '';
              }
            } else {
              $mensagem = 'Você não está logado no sistema!';
            }
            echo "                  <div class=\"popup hidden\">\n";
            echo "                    <h2 class=\"popup-text\" style=\"background-color: rgba(0,0,0,0.4); padding: 10px 10px 10px 10px\">Acesso Bloqueado:<br />".$mensagem."</h2>\n";
            echo "                  </div>\n";
          }
        } else {
          echo "                  <div class=\"popup hidden\">\n";
          echo "                    <h2 class=\"popup-text\" style=\"background-color: rgba(0,0,0,0.4); padding: 10px 10px 10px 10px\">Acesso Bloqueado:<br />Você não está logado no sistema!</h2>\n";
          echo "                  </div>\n";
        }
        echo "                  <div>&nbsp;</div>\n";
        echo "                  <div>&nbsp;</div>\n";
        echo "                  <div>&nbsp;</div>\n";
        echo "          	    </div>\n";
      }
    }
    
    
    public function retornaListaGratisPago($lista_paginas, $cd_objeto_aprendizagem, $acesso_liberado, $conteudos, $eh_gratis, $qt_aulas_gratis) {
      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();
      //require_once 'conteudos/conteudos_internos.php';                          $con_int = new ConteudoInterno();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      require_once 'conteudos/cursos_alunos_acessos_conteudos.php';             $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoConteudo();
      
      
      $qtd = 0;
      foreach ($conteudos as $c) {
        $videos = $con_lin->selectLinks('1', $c['cd_conteudo'], 'CC', '1');
        foreach ($videos as $v) {
          if ($v['eh_publico'] == $eh_gratis) {
            echo "                    <div class=\"lessons-listing\">\n";
            echo "                      <div class=\"lesson-item \">\n";
            echo "                        <div class=\"lesson-title\">\n";
            echo "                          <h4 class=\"nomargin\">&nbsp;</h4>\n";
            if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
              echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/conteudo/".$c['lk_seo']."\" title=\"Assistir vídeo\">".$c['tt_conteudo']."</a>\n";
            } else {
              echo "                          ".$c['tt_conteudo']."\n";
            }
            if ((isset($_SESSION['life_acesso_curso_situacao'])) && ($_SESSION['life_acesso_curso_situacao'] == '1')) {
              echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">\n";
              echo "                          &nbsp;&nbsp;&nbsp;&nbsp;\n";
              $cur_alu_ace_con->retornaStatusAcessoAluno($cd_objeto_aprendizagem['cd_objeto_aprendizagem'], $c['cd_conteudo']);
              echo "                          </div>\n";            
            }            
            echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">(".$v['tm_video'].")</div>\n";
            echo "                        </div>\n";
            echo "                        <div class=\"lesson-attachments\" style=\"width:25px;\">\n";
            if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
              echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/conteudo/".$c['lk_seo']."\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
            } else {
              echo "                          <a href=\"\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
            }
            echo "                        </div>\n";
            echo "                      </div>\n";
            echo "                    </div>\n";        
            echo "          	        <br>\n";
            $qtd += 1;
          } 
        }        
        /*
        $conteudos_internos = $con_int->selectConteudoInternos('1', $c['cd_conteudo']);
        foreach ($conteudos_internos as $ci) {
          $videos = $con_lin->selectLinks('1', $ci['cd_conteudo_interno'], 'CI', '1');
          foreach ($videos as $v) {
            if ($v['eh_publico'] == $eh_gratis) {
              echo "                    <div class=\"lessons-listing\">\n";
              echo "                      <div class=\"lesson-item \">\n";
              echo "                        <div class=\"lesson-title\">\n";
              echo "                          <h4 class=\"nomargin\">&nbsp;</h4>\n";
              if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
                echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/conteudo/".$c['lk_seo']."\" title=\"Assistir vídeo\">".$v['nm_link']."</a>\n";
              } else {
                echo "                          ".$v['nm_link']."\n";
              }
              echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">(".$v['tm_video'].")</div>\n";
              echo "                        </div>\n";
              echo "                        <div class=\"lesson-attachments\" style=\"width:25px;\">\n";
              if (($v['eh_publico'] == '1') || ($acesso_liberado)) {
                echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/conteudo/".$c['lk_seo']."\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
              } else {
                echo "                          <a href=\"\" title=\"Assistir vídeo\" class=\"video\"></a>\n";
              }
              echo "                        </div>\n";
              echo "                      </div>\n";
              echo "                    </div>\n";        
              echo "          	        <br>\n";
              $qtd += 1;
            } 
          }        
        }
        * 
      }    
      
      if (($eh_gratis == '1') && ($qtd == 0)) {
        echo "                    <div class=\"lessons-listing\">\n";
        echo "                      <div class=\"lesson-item \">\n";
        echo "                        <div class=\"lesson-title\">\n";
        echo "                          Não há aulas grátis para este curso\n";
        echo "                        </div>\n";
        echo "                      </div>\n";
        echo "                    </div>\n";        
        echo "          	        <br>\n";        
      } elseif (($eh_gratis == '0') && ($qtd == 0)) {
        echo "                    <div class=\"lessons-listing\">\n";
        echo "                      <div class=\"lesson-item \">\n";
        echo "                        <div class=\"lesson-title\">\n";
        if ($qt_aulas_gratis > 0) {
          echo "                          Todas as aulas deste curso são grátis\n";
        } else {
          echo "                          Não há aulas cadastradas para este curso\n";
        }
        echo "                        </div>\n";
        echo "                      </div>\n";
        echo "                    </div>\n";        
        echo "          	        <br>\n";        
      }
    }
    
    
    public function retornaListaConteudosObjetoAprendizagem($lista_paginas, $cd_objeto_aprendizagem) {
      require_once 'conteudos/cursos_alunos_inscricoes.php';                    $cur_alu_ins = new ObjetoAprendizagemAlunoInscricao();
      require_once 'conteudos/conteudos_links.php';                             $con_lin = new ConteudoLink();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $acesso_liberado = $cur_alu_ins->validarInscricaoAlunoObjetoAprendizagem($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      $conteudos = $this->selectObjetosAprendizagemConteudosExibicao($cd_objeto_aprendizagem['cd_objeto_aprendizagem']);
      
      if (count($conteudos) > 0) {
        require_once 'conteudos/cursos_alunos_acessos_conteudos.php';           $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoConteudo();
        echo "<h2>Conteúdos</h2>\n";
        foreach ($conteudos as $c) {
          $videos = $con_lin->selectLinks('1', $c['cd_conteudo'], 'CC', '1');
          $tm_video = "00:00:00";
          foreach ($videos as $v) {
            $tm_video = $dh->somarHoras($tm_video, $v['tm_video']);
          }        
          echo "                    <div class=\"lessons-listing\">\n";
          echo "                      <div class=\"lesson-item \">\n";
          echo "                        <div class=\"lesson-title\">\n";
          echo "                          <h4 class=\"nomargin\">&nbsp;</h4>\n";
          if (($c['eh_publico'] == '1') || ($acesso_liberado)) {
            echo "<a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/conteudo/".$c['lk_seo']."\">".$c['tt_conteudo']."</a>\n";
          } else {
            echo $c['tt_conteudo']."\n";
          }
          echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">\n";
          if ((isset($_SESSION['life_acesso_curso_situacao'])) && ($_SESSION['life_acesso_curso_situacao'] == '1')) {
            $cur_alu_ace_con->retornaStatusAcessoAluno($cd_objeto_aprendizagem['cd_objeto_aprendizagem'], $c['cd_conteudo']);
          }            
          echo "                          </div>\n";
          echo "                          <div style=\"display:inline-block; float:right; color:#999; margin-top:2px;\">(".$tm_video.")&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
          echo "                        </div>\n";
          
          echo "                        <div class=\"lesson-attachments\" style=\"width:25px;\">\n";
          if (($c['eh_publico'] == '1') || ($acesso_liberado)) {
            echo "                          <a href=\"".$_SESSION['life_link_completo']."cursos/".$cd_objeto_aprendizagem['lk_seo']."/conteudo/".$c['lk_seo']."\" class=\"video\"></a>\n";
          } else {
            echo "                          <a href=\"\" class=\"video\"></a>\n";
          }
          echo "                        </div>\n";
          echo "                      </div>\n";
          echo "                    </div>\n";        
          echo "          	        <br>\n";
        }                                 
      } else {
        echo "<br /><br />\n";
        echo "<p class=\"fontConteudoAlerta\">Não há conteúdos cadastrados para este curso!</p>\n";
        echo "<br /><br />\n";
      }
    }

    public function retornaConteudoObjetoAprendizagem($lista_paginas, $cd_objeto_aprendizagem) {
      if (isset($lista_paginas[3])) {
        require_once 'login/login.php';                                         $login = new Login();
        
        $lk_seo = addslashes($lista_paginas[3]);
        if ($login->estaLogado()) {
          $this->listarConteudoCompletoObjetoAprendizagem($lk_seo, $cd_objeto_aprendizagem);
        } else {
          $this->retornaJustificativaAcesso($lk_seo, $cd_objeto_aprendizagem);
        } 
      
      }
    }
    
    public function listarConteudoCompletoObjetoAprendizagem($lk_seo, $cd_objeto_aprendizagem) {
      $dados = $this->selectDadosObjetoAprendizagemConteudoSEO($cd_objeto_aprendizagem, $lk_seo);

      if ($dados['cd_objeto_aprendizagem_conteudo'] > 0) {
        require_once 'conteudos/cursos_alunos_inscricoes.php';                  $cur_alu_ins = new ObjetoAprendizagemAlunoInscricao();
        $acesso_liberado = $cur_alu_ins->validarInscricaoAlunoObjetoAprendizagem($cd_objeto_aprendizagem);
        if ($acesso_liberado) {
          require_once 'conteudos/cursos_alunos_acessos_conteudos.php';         $cur_alu_ace_con = new ObjetoAprendizagemAlunoAcessoConteudo();
          $cur_alu_ace_con->alunoJaAcessouConteudo($cd_objeto_aprendizagem, $dados['cd_conteudo']);
        }
        if (($acesso_liberado) || ($dados['eh_publico'])) {                               
          require_once 'conteudos/fotos.php';                                   $foto = new Fotos();
          require_once 'conteudos/arquivos.php';                                $arq  = new Arquivo();
          require_once 'conteudos/conteudos_links.php';                         $con_lin = new ConteudoLink();
          //require_once 'conteudos/conteudos_internos.php';                      $con_int = new ConteudoInterno();

          echo "  <h2>".$dados['tt_conteudo']."</h2>\n";
          
          echo "  <p>".nl2br($dados['ds_conteudo'])."</p>\n";

          $tp_link = '1'; //Vídeos
          $con_lin->retornaRelacaoLinks('CC', $dados['cd_conteudo'], $tp_link, $acesso_liberado);
          
          $foto->exibeFotosAreaCentral($dados['cd_conteudo'], 'CC');

          $tp_link = '2'; //Áudios
          $con_lin->retornaRelacaoLinks('CC', $dados['cd_conteudo'], $tp_link, $acesso_liberado);
          $tp_link = '4'; //Referências
          $con_lin->retornaRelacaoLinks('CC', $dados['cd_conteudo'], $tp_link, $acesso_liberado);

          $arq->retornaRelacaoArquivosDownload('CC', $dados['cd_conteudo'], $acesso_liberado);

          //$con_int->listarConteudoCompletoObjetoAprendizagem($dados['cd_conteudo'], $acesso_liberado);
        } else {
          echo "<p class=\"fontConteudoAlerta\">".
               "  Desculpe mas você não tem direito de acesso à este Conteúdo!<br />".
               "  Verifique se está inscrito no curso, se sua inscrição está quitada e,<br />".
               "  no caso de irregularidades ou dúvidas, ".
               "  utilize a seção <a href=\"".$_SESSION['life_link_completo']."fale-conosco\" class=\"fontLinkPublico\">Fale Conosco</a>!".               
               "</p>\n";
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Conteúdo não encontrado!</p>\n";
      }
    }    
    
    public function retornaJustificativaAcesso($lk_seo, $cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      
      $dados = $this->selectDadosObjetoAprendizagemConteudoSEO($cd_objeto_aprendizagem, $lk_seo);
      
      if ($dados['eh_publico'] == '1') {
        $ds_justificativa_acesso_nao_logado_publico = $conf->retornaJustificativaAcessoNaoLogadoPublico();
        echo "<p class=\"fontConteudoAlerta\">".nl2br($ds_justificativa_acesso_nao_logado_publico)."</p>\n";
      } else {
        $ds_justificativa_acesso_nao_logado_pago = $conf->retornaJustificativaAcessoNaoLogadoPago();
        echo "<p class=\"fontConteudoAlerta\">".nl2br($ds_justificativa_acesso_nao_logado_pago)."</p>\n";
      }
      echo "<br />\n";
      echo "<p class=\"fontConteudoAlerta\"><a href=\"".$_SESSION['life_link_completo']."efetuar-login\" class=\"fontLinkObjetoAprendizagem\">Efetue Login</a> para continuar!</p>\n"; 
    }
    
    public function contabilizarAcessoAlunoConteudo() {
    
    }
         */
//**************BANCO DE DADOS**************************************************    
    public function selectObjetosAprendizagemConteudos($eh_ativo, $cd_objeto_aprendizagem) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_conteudos ".
              "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nr_ordem, tt_conteudo ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
                                    /*
    public function selectObjetosAprendizagemConteudosExibicao($cd_objeto_aprendizagem) {
      $sql  = "SELECT c.tt_conteudo, c.cd_conteudo, cc.lk_seo ".
              "FROM life_objetos_aprendizagem_conteudos cc, life_conteudos c ".
              "WHERE cc.cd_conteudo = c.cd_conteudo ".
              "AND cc.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
              "AND cc.eh_ativo = '1' ".
              "ORDER BY cc.nr_ordem, c.tt_conteudo ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
                                      */
    public function selectDadosObjetoAprendizagemConteudo($cd_objeto_aprendizagem, $cd_conteudo) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_conteudos ".
              "WHERE cd_conteudo= '$cd_conteudo' ".
              "AND cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function selectDadosConteudo($cd_conteudo) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_conteudos ".
              "WHERE cd_conteudo= '$cd_conteudo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }    
                                        /*
    public function selectDadosCompletosObjetoAprendizagemConteudo($cd_objeto_aprendizagem, $cd_conteudo) {
      $sql  = "SELECT cur.*, con.*, cur_con.* ".
              "FROM life_objetos_aprendizagem_conteudos cur_con, life_conteudos con, life_cursos cur ".
              "WHERE cur_con.cd_conteudo = con.cd_conteudo ".
              "AND cur_con.cd_objeto_aprendizagem = cur.cd_objeto_aprendizagem ".
              "AND cur_con.cd_conteudo= '$cd_conteudo' ".
              "AND cur_con.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function selectDadosObjetoAprendizagemConteudoSEO($cd_objeto_aprendizagem, $lk_seo) {
      $sql  = "SELECT con.*, cur_con.* ".
              "FROM life_objetos_aprendizagem_conteudos cur_con, life_conteudos con ".
              "WHERE cur_con.cd_conteudo = con.cd_conteudo ".
              "AND cur_con.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
              "AND cur_con.lk_seo = '$lk_seo' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    } 
                                                            */
    public function selectMaiorNumeroOrdemObjetoAprendizagem($cd_objeto_aprendizagem) {
      $sql  = "SELECT MAX(nr_ordem) numero ".
              "FROM life_objetos_aprendizagem_conteudos ".
              "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['numero'];        
    } 
    
    public function inserirObjetoAprendizagemConteudo($cd_objeto_aprendizagem, $tt_conteudo, $ds_conteudo, $nr_ordem, $eh_ativo, $lk_seo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_objetos_aprendizagem_conteudos ".
             "(cd_objeto_aprendizagem, tt_conteudo, ds_conteudo, nr_ordem, eh_ativo, lk_seo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_objeto_aprendizagem\", \"$tt_conteudo\", \"$ds_conteudo\", \"$nr_ordem\", \"$eh_ativo\", \"$lk_seo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_conteudos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $saida = mysql_affected_rows();
      return $saida;      
    }
    
    public function editarObjetoAprendizagemConteudo($cd_conteudo, $cd_objeto_aprendizagem, $tt_conteudo, $ds_conteudo, $nr_ordem, $eh_ativo, $lk_seo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_objetos_aprendizagem_conteudos SET ".
             "tt_conteudo = \"$tt_conteudo\", ".
             "ds_conteudo = \"$ds_conteudo\", ".
             "nr_ordem = \"$nr_ordem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_conteudo= '$cd_conteudo' ".
             "AND cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_conteudos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA OBJETOS APRENDIZAGEM CONTEÚDOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }
                                          
  }
?>