<?php
  class Arquivo {
    
    public function __construct () {
    }
    
                        /*
    public function listarArquivos($link, $cd_origem_arquivo, $tp_origem_arquivo) {
      $arquivos = $this->selectArquivos($tp_origem_arquivo, $cd_origem_arquivo, '1');    
      
      echo "  <h2>Arquivos</h2>\n";
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Ordem:</td>\n";
      echo "      <td class=\"celConteudo\">Arquivo:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($arquivos as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['ds_arquivo']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharArquivo($it['cd_arquivo']);
        echo "        </span></a>\n";
        echo "        <a href=\"".$_SESSION['life_link_completo']."/".$it['ds_pasta']."/".$it['nm_arquivo']."\" target=\"_blank\"><img src=\"icones/visualizar_arquivo.png\" alt=\"Visualizar\" title=\"Visualizar\" border=\"0\"></a>\n";
        echo "        <a href=\"".$link."&ar=".$it['cd_arquivo']."&acao=edi_arquivo\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"".$link."&ar=".$it['cd_arquivo']."&acao=exc_arquivo\" class=\"fontLink\"><img src=\"icones/excluir.png\" alt=\"Excluir\" title=\"Excluir\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";              
      }
      echo "  </table>\n";
      echo "  <br /><br />\n";             
    }
    
    public function detalharArquivo($cd_arquivo) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $arquivo = $this->selectDadosArquivo($cd_arquivo);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($arquivo['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($arquivo['dt_cadastro'])."<br />\n";
      if ($arquivo['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($arquivo['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($arquivo['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
                  
    public function montarFormularioCadastro($link, $cd_origem_arquivo, $tp_origem_arquivo) {
      require_once 'conteudos/tipos_arquivos.php';                              $tip_arq = new TipoArquivo();
      $dados_pasta = $tip_arq->selectDadosTipoArquivoNome($tp_origem_arquivo);
      $ds_pasta = $dados_pasta['nm_pasta'];

      $cd_arquivo = "";
      $dt_arquivo = date('Y-m-d');
      $nm_arquivo = "";
      $nm_autor = "";
      $ds_email_autor = "";
      $ds_fonte = "";
      $ds_link_fonte = "";
      $ds_comentarios = "";
      $ds_arquivo = "";
      $nr_ordem = $this->utilizarNumeroOrdem($tp_origem_arquivo, $cd_origem_arquivo);
      $eh_ativo = "1";
      $lk_seo = "";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Arquivo</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_arquivo, $tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo);
    }
                  
    public function montarFormularioEdicao($link, $cd_origem_arquivo, $tp_origem_arquivo, $cd_arquivo) {
      $dados= $this->selectDadosArquivo($cd_arquivo);
      if (($dados['tp_origem_arquivo'] != $tp_origem_arquivo) || ($dados['cd_origem_arquivo'] != $cd_origem_arquivo)) {
        echo "<p class=\"fontConteudoAlerta\">Erro ao recuperar dados do arquivo para edição!</p>\n";
        return false;
      }
      $dt_arquivo = $dados['dt_arquivo'];
      $nm_arquivo = $dados['nm_arquivo'];
      $nm_autor = $dados['nm_autor'];
      $ds_email_autor = $dados['ds_email_autor'];
      $ds_fonte = $dados['ds_fonte'];
      $ds_link_fonte = $dados['ds_link_fonte'];
      $ds_comentarios = $dados['ds_comentarios'];
      $ds_arquivo = $dados['ds_arquivo'];
      $ds_pasta = $dados['ds_pasta'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];

      $_SESSION['life_edicao']= 1;      
      echo "  <h2>Edição de Arquivo</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_arquivo, $tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo);
    }    
    
    private function utilizarNumeroOrdem($tp_origem_arquivo, $cd_origem_arquivo) {
      $ultimo_numero_ordem = $this->selectMaiorNumeroArquivo($tp_origem_arquivo, $cd_origem_arquivo);
      return $ultimo_numero_ordem + 1; 
    }
    

    private function imprimeFormularioCadastro($link, $cd_arquivo, $tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      include_once 'js/funcoes_cadastro_data.js';
      include "js/js_cadastro_arquivo.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salv_arquivo\" enctype=\"multipart/form-data\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_arquivo', $cd_arquivo);
      $util->campoHidden('tp_origem_arquivo', $tp_origem_arquivo);
      $util->campoHidden('cd_origem_arquivo', $cd_origem_arquivo);
      $util->campoHidden('ds_pasta', $ds_pasta);
      $util->campoHidden('nm_arquivo_original', $nm_arquivo);

      $util->linhaUmCampoTextHint(1, 'Nome para o Arquivo', 'ds_arquivo', 150, 840, $ds_arquivo, 1);
      $opcoes= array();
      for ($i=1;$i<($nr_ordem+100);$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;      $opcoes[]= $opcao;
      }
      $util->linhaSeletorHint('Ordem de Apresentação', 'nr_ordem', $nr_ordem, $opcoes, '840', '1', 1);
      $util->linhaUmCampoDataHint('0', 'Data do Arquivo', 'dt_arquivo', '10', '813', $dt_arquivo, 1);
      $util->linhaTextoHint(0, 'Descrição do Arquivo', 'ds_comentarios', $ds_comentarios, '5', '840', 1);
      $util->linhaUmCampoTextHint(0, 'Autor', 'nm_autor', 150, 840, $nm_autor, 1);
      $util->linhaUmCampoTextHint(0, 'E-mail do Autor', 'ds_email_autor', 150, 840, $ds_email_autor, 1);
      $util->linhaUmCampoTextHint(0, 'Fonte', 'ds_fonte', 150, 840, $ds_fonte, 1);
      $util->linhaUmCampoTextHint(0, 'Link para a Fonte', 'ds_link_fonte', 150, 840, $ds_link_fonte, 1);
      $util->linhaUmCampoArquivoHint(1, 'Arquivo', 'nm_arquivo', 100, 840, '', 1);
      if ($cd_arquivo > 0) {
        $util->linhaComentario("Caso seja informado novo arquivo, o anterior será substituído!");  
      }      
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'É Ativo';          $opcoes[]= $opcao; 
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não é Ativo';      $opcoes[]= $opcao;
      $util->linhaSeletorHint('É Ativo', 'eh_ativo', $eh_ativo, $opcoes, '840', '1', 1);

      if ($cd_arquivo > 0) {        $util->linhaBotao('Editar');           } else {         $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "  </form>\n";       
      require_once 'conteudos/arquivos_extensao.php';                           $arq_ext = new ArquivoExtensao();
      require_once 'conteudos/tipos_arquivos.php';                              $tip_arq = new TipoArquivo();
      $dados = $tip_arq->selectDadosTipoArquivoNome($tp_origem_arquivo);
      $extensoes = $arq_ext->selectExtensoes('1', $dados['cd_tipo_arquivo']);
      echo "<p class=\"fontConteudoAlerta\">\n";
      echo "  Formato(s) permitido(s) para o Upload de Arquivo(s): ";
      $limite = count($extensoes);
      $i = 1;
      foreach ($extensoes as $e) {
        echo $e['nm_extensao']." (".$e['ds_extensao'].")";
        $i += 1;
        if ($i < count($extensoes)) {
          echo ", ";
        } elseif ($i == count($extensoes)) {
          echo " e ";
        } else {
          echo ".";
        }
      }
      echo "\n";
      echo "</p>\n";      
      $util->posicionarCursor('cadastro', 'ds_arquivo'); 
    }

    public function salvarArquivo() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $cd_arquivo = addslashes($_POST['cd_arquivo']);
      $tp_origem_arquivo = addslashes($_POST['tp_origem_arquivo']);
      $cd_origem_arquivo = addslashes($_POST['cd_origem_arquivo']);
      $dt_arquivo = $util->limparVariavel($_POST['dt_arquivo']);
      $dt_arquivo = $dh->inverterData($dt_arquivo);
      $nm_autor = $util->limparVariavel($_POST['nm_autor']);
      $ds_email_autor = $util->limparVariavel($_POST['ds_email_autor']);
      $ds_fonte = $util->limparVariavel($_POST['ds_fonte']);
      $ds_link_fonte = $util->limparVariavel($_POST['ds_link_fonte']);
      if ($ds_link_fonte != '') {
        if (($ds_link_fonte[0] != 'h') || ($ds_link_fonte[1] != 't') || ($ds_link_fonte[2] != 't') || ($ds_link_fonte[3] != 'p')) {
          $ds_link_fonte = "http://".$ds_link_fonte;
        }      
      }
      
      $ds_comentarios = $util->limparVariavel($_POST['ds_comentarios']);
      $ds_arquivo = $util->limparVariavel($_POST['ds_arquivo']);
      $ds_pasta = addslashes($_POST['ds_pasta']);

      if ($_FILES['nm_arquivo']['name'] != '') {
        require_once 'includes/upload_arquivos.php';                            $upl_arq= new UploadArquivo();
        $campo = 'nm_arquivo';
        $retorno = $upl_arq->uploadArquivoGenerico($campo, $ds_pasta, $tp_origem_arquivo);
        if ($retorno[0] != '') {
          echo "<p class=\"fontConteudoAlerta\">".$retorno[0]."</p>\n";
          return false;
        } else {
          $nm_arquivo = $retorno[1];
        }
      } else {
        if ($cd_arquivo > 0) {
          $nm_arquivo = $util->limparVariavel($_POST['nm_arquivo_original']);
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no Cadastro! Nenhum arquivo anexado!</p>\n";
          return false;
        }
      }                           
      $nr_ordem = $util->limparVariavel($_POST['nr_ordem']);
      $eh_ativo = addslashes($_POST['eh_ativo']);


      $lk_seo = $util->retornaLinkSEO($ds_arquivo, 'life_arquivos', 'lk_seo', '150', $cd_arquivo);

      if ($cd_arquivo > 0) {
        if ($this->alterarArquivo($cd_arquivo, $tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Arquivo editado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Arquivo, ou nenhuma informação alterada!</p>\n";
        }        
      } else {
        if ($this->inserirArquivo($tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Arquivo cadastrado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Arquivo!</p>\n";
        }
      }
    } 

    public function excluirArquivo($cd_origem_arquivo, $tp_origem_arquivo, $cd_arquivo) {
      $dados= $this->selectDadosArquivo($cd_arquivo);
      if (($dados['tp_origem_arquivo'] != $tp_origem_arquivo) || ($dados['cd_origem_arquivo'] != $cd_origem_arquivo)) {
        echo "<p class=\"fontConteudoAlerta\">Erro ao recuperar dados do arquivo para exclusão!</p>\n";
        return false;
      }
      if ($dados['eh_ativo'] == 0) {
        echo "<p class=\"fontConteudoAlerta\">Arquivo já foi excluído!</p>\n";
      } else {
        $dt_arquivo = $dados['dt_arquivo'];
        $nm_arquivo = $dados['nm_arquivo'];
        $nm_autor = $dados['nm_autor'];
        $ds_email_autor = $dados['ds_email_autor'];
        $ds_fonte = $dados['ds_fonte'];
        $ds_link_fonte = $dados['ds_link_fonte'];
        $ds_comentarios = $dados['ds_comentarios'];
        $ds_arquivo = $dados['ds_arquivo'];
        $ds_pasta = $dados['ds_pasta'];
        $nr_ordem = $dados['nr_ordem'];
        $eh_ativo = '0';
        $lk_seo = $dados['lk_seo'];
        if ($this->alterarArquivo($cd_arquivo, $tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Arquivo excluído com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao excluir o Arquivo!</p>\n";
        }
      }
    }     
 
    public function retornaQuantidadeArquivosDownload($tp_origem_arquivo, $cd_origem_arquivo, $acesso_liberado) {
      $arquivos = $this->selectArquivos($tp_origem_arquivo, $cd_origem_arquivo, '1');    

      return count($arquivos);
    }   
                                                                      
//******************EXIBICAO****************************************************
/*
    public function retornaRelacaoArquivosDownload($tp_origem_arquivo, $cd_origem_arquivo, $acesso_liberado) {
      $arquivos = $this->selectArquivos($tp_origem_arquivo, $cd_origem_arquivo, '1');    

      if (count($arquivos) > 0) {
        require_once 'includes/configuracoes.php';                              $conf = new Configuracao();
        $chave_hoje = $conf->retornaChaveAcessoHoje();
        
        echo " <h3>Arquivos Complementares</h3>\n";
        
        $ds_texto_arquivos = $conf->retornaTextoArquivosDownload();
        if ($ds_texto_arquivos != '') {
          echo "  <p>".nl2br($ds_texto_arquivos)."</p>\n";
        }
      
        foreach ($arquivos as $a) {
          echo "  <p>\n";
            echo "    - <a href=\"".$_SESSION['life_link_completo']."download.php?a=".$a['lk_seo']."&c=".$chave_hoje."\" target=\"_blank\" class=\"fontLink\">".$a['ds_arquivo']."</a><br />\n";
          echo "  </p>\n";                            
        }                                     
      }  
    }    
    

    public function retornaArquivo($lk_seo) {
      $dados = $this->selectDadosArquivoSEO($lk_seo);
      if ($dados['cd_arquivo'] != '') {
        $arquivo = $_SESSION['life_link_arquivos'].$dados['ds_pasta']."/".$dados['nm_arquivo'];
     
        if (file_exists($arquivo)) {
          switch (strtolower(substr(strrchr(basename($arquivo),"."),1))) {
            case "pdf": $tipo="application/pdf";                                  break;
            case "exe": $tipo="application/octet-stream";             break;
            case "zip": $tipo="application/zip";                      break;
            case "doc": $tipo="application/msword";                   break;
            case "xls": $tipo="application/vnd.ms-excel";             break;
            case "ppt": $tipo="application/vnd.ms-powerpoint";        break;
            case "gif": $tipo="image/gif";                            break;
            case "png": $tipo="image/png";                            break;
            case "jpg": $tipo="image/jpg";                            break;
            case "mp3": $tipo="audio/mpeg";                           break;
            case "php": // deixar vazio por seurança
            case "htm": // deixar vazio por seurança
            case "html": // deixar vazio por seurança
          }
          header("Content-Type: ".$tipo);
          header("Content-Length: ".filesize($arquivo));
          header("Content-Disposition: attachment; filename=".basename($arquivo));
          readfile($arquivo);
        } else {
          $_SESSION['life_erro_download'] = "O Arquivo não foi encontrado!";
        }
      } else {
        $_SESSION['life_erro_download'] = "Problemas ao recuperar dados do Arquivo!";
      }
    }
*       
//**************BANCO DE DADOS**************************************************    
    public function selectArquivos($tp_origem_arquivo, $cd_origem_arquivo, $ativos) {
      $sql  = "SELECT * ".
              "FROM life_arquivos ".               
              "WHERE tp_origem_arquivo = '$tp_origem_arquivo' ".
              "AND cd_origem_arquivo = '$cd_origem_arquivo' ".
              "AND nm_arquivo != '' ";
      if ($ativos != '2') {              
        $sql.= "AND eh_ativo = '$ativos' ";
      }
      $sql .= "ORDER BY nr_ordem, ds_arquivo ";   
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosArquivo($cd_arquivo) {
      $sql  = "SELECT *  ".
              "FROM life_arquivos ".
              "WHERE cd_arquivo = '$cd_arquivo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function selectDadosArquivoSEO($lk_seo) {
      $sql  = "SELECT *  ".
              "FROM life_arquivos ".
              "WHERE lk_seo = '$lk_seo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function selectMaiorNumeroArquivo($tp_origem_arquivo, $cd_origem_arquivo) {
      $sql  = "SELECT MAX(nr_ordem) numero ".
              "FROM life_arquivos ".               
              "WHERE tp_origem_arquivo = '$tp_origem_arquivo' ".
              "AND cd_origem_arquivo = '$cd_origem_arquivo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS");
      $dados = mysql_fetch_assoc($result_id);
      return $dados['numero'];       
    }
                                     
    public function inserirArquivo($tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');    
      $sql = "INSERT INTO life_arquivos ".
             "(tp_origem_arquivo, cd_origem_arquivo, dt_arquivo, nm_arquivo, nm_autor, ds_email_autor, ds_fonte, ds_link_fonte, ds_comentarios, ds_arquivo, ds_pasta, nr_ordem, eh_ativo, lk_seo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$tp_origem_arquivo\", \"$cd_origem_arquivo\", \"$dt_arquivo\", \"$nm_arquivo\", \"$nm_autor\", \"$ds_email_autor\", \"$ds_fonte\", \"$ds_link_fonte\", \"$ds_comentarios\", \"$ds_arquivo\", \"$ds_pasta\", \"$nr_ordem\", \"$eh_ativo\", \"$lk_seo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'arquivos');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alterarArquivo($cd_arquivo, $tp_origem_arquivo, $cd_origem_arquivo, $dt_arquivo, $nm_arquivo, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_arquivo, $ds_pasta, $nr_ordem, $eh_ativo, $lk_seo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_arquivos SET ".
             "dt_arquivo = \"$dt_arquivo\", ".
             "nm_arquivo = \"$nm_arquivo\", ".
             "nm_autor = \"$nm_autor\", ".
             "ds_email_autor = \"$ds_email_autor\", ".
             "ds_fonte = \"$ds_fonte\", ".
             "ds_link_fonte = \"$ds_link_fonte\", ".
             "ds_comentarios = \"$ds_comentarios\", ".
             "ds_arquivo = \"$ds_arquivo\", ".
             "ds_pasta = \"$ds_pasta\", ".
             "nr_ordem = \"$nr_ordem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_arquivo = '$cd_arquivo' ".
             "AND tp_origem_arquivo = '$tp_origem_arquivo' ".
             "AND cd_origem_arquivo = '$cd_origem_arquivo' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'arquivos');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA ARQUIVOS");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
              */   
  }
?>