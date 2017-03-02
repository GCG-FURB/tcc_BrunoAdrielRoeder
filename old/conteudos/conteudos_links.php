<?php
  class ConteudoLink {
    
    public function __construct () {
    }

    public function listarLinks($link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $ativos) {
      $itens = $this->selectLinks($cd_tipo_link, $cd_origem_link, $tp_origem_link, $ativos);
      
      if ($ativos == '1') {        $complemento = " Ativos(as)";      } elseif ($ativos == '0') {        $complemento = " Inativos(as)";      } else {        $complemento = "";      }   
      
      if ($cd_tipo_link == '1')        {        $nome = "Vídeo:";             echo "<h2>Vídeos".$complemento."</h2>\n";
      } elseif ($cd_tipo_link == '2')  {        $nome = "Áudio:";             echo "<h2>Áudios".$complemento."</h2>\n";
      } elseif ($cd_tipo_link == '4')  {        $nome = "Referência:";        echo "<h2>Referências".$complemento."</h2>\n";
      }                               
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Ordem:</td>\n";
      echo "      <td class=\"celConteudo\">".$nome."</td>\n";
      if (($cd_tipo_link == '1') || ($cd_tipo_link == '2')) {
        echo "      <td class=\"celConteudo\">Duração:</td>\n";
      }      
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_link']."</td>\n";
        if (($cd_tipo_link == '1') || ($cd_tipo_link == '2')) {
          echo "      <td class=\"celConteudo\">".$it['tm_video']."</td>\n";
        } 
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharLink($it['cd_conteudo_link']);
        echo "        </span></a>\n";
        if ($cd_tipo_link == '1')        {
          echo "        <a href=\"http://".$it['ds_link']."\" target=\"_blank\"><img src=\"icones/visualizar_link.png\" alt=\"Visualizar\" title=\"Visualizar\" border=\"0\"></a>\n";
        } else {
          echo "        <a href=\"".$it['ds_link']."\" target=\"_blank\"><img src=\"icones/visualizar_link.png\" alt=\"Visualizar\" title=\"Visualizar\" border=\"0\"></a>\n";        
        }
        echo "        <a href=\"".$link."&cl=".$it['cd_conteudo_link']."&tl=".$cd_tipo_link."&acao=edi_link\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"".$link."&cl=".$it['cd_conteudo_link']."&tl=".$cd_tipo_link."&acao=sta_link\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";              
      }
      echo "  </table>\n";  
      echo "  <br /><br />\n";     
    }
    
    public function detalharLink($cd_conteudo_link) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $link = $this->selectDadosLink($cd_conteudo_link);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($link['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($link['dt_cadastro'])."<br />\n";
      if ($link['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($link['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($link['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
    
    public function possuiLink($cd_tipo_link, $cd_origem_link, $tp_origem_link) {
      $itens = $this->selectLinks($cd_tipo_link, $cd_origem_link, $tp_origem_link, '1');
      if (count($itens) > 0) {
        return true;
      } else {
        return false;
      } 
    }
                  
    private function utilizarNumeroOrdem($cd_origem_link, $tp_origem_link, $cd_tipo_link) {
      $ultimo_numero_ordem = $this->selectMaiorNumeroOrdemConteudoLink($cd_origem_link, $tp_origem_link, $cd_tipo_link);
      return $ultimo_numero_ordem + 1; 
    }
                  
    public function montarFormularioCadastro($link, $cd_origem_link, $tp_origem_link, $cd_tipo_link) {
      $cd_conteudo_link = "";
      $nm_link = "";
      $ds_link = "";
      $nm_autor = "";
      $ds_email_autor = "";
      $ds_fonte = "";
      $ds_link_fonte = "";
      $ds_comentarios = "";
      $ds_chave_link = "";
      $nr_ordem = $this->utilizarNumeroOrdem($cd_origem_link, $tp_origem_link, $cd_tipo_link);
      $eh_ativo = "1";
      $lk_seo = "";
      $tm_video = "00:00:00";

      $_SESSION['life_edicao']= 1;
      if ($cd_tipo_link == '1')        {        echo "<h2>Cadastro de Vídeo</h2>\n";
      } elseif ($cd_tipo_link == '2')  {        echo "<h2>Cadastro de Áudio</h2>\n";
      } elseif ($cd_tipo_link == '4')  {        echo "<h2>Cadastro de Referência</h2>\n";
      }        
      $this->imprimeFormularioCadastro($link, $cd_conteudo_link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video);
    }
                  
    public function montarFormularioEdicao($link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $cd_conteudo_link) {
      require_once 'conteudos/tipos_links.php';
      $dados = $this->selectDadosLink($cd_conteudo_link);

      if (($dados['cd_tipo_link'] != $cd_tipo_link) || ($dados['cd_origem_link'] != $cd_origem_link) || ($dados['tp_origem_link'] != $tp_origem_link)) {
        echo "<p class=\"fontConteudoAlerta\">Erro ao recuperar dados para edição!</p>\n";
        return false;
      }
      $nm_link = $dados['nm_link'];
      $ds_link = $dados['ds_link'];
      $nm_autor = $dados['nm_autor'];
      $ds_email_autor = $dados['ds_email_autor'];
      $ds_fonte = $dados['ds_fonte'];
      $ds_link_fonte = $dados['ds_link_fonte'];
      $ds_comentarios = $dados['ds_comentarios'];
      $ds_chave_link = $dados['ds_chave_link'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];
      $tm_video = $dados['tm_video'];

      $_SESSION['life_edicao']= 1;      
      if ($cd_tipo_link == '1')        {        echo "<h2>Edição de Vídeo</h2>\n";
      } elseif ($cd_tipo_link == '2')  {        echo "<h2>Edição de Áudio</h2>\n";
      } elseif ($cd_tipo_link == '4')  {        echo "<h2>Edição de Referência</h2>\n";
      }        
      $this->imprimeFormularioCadastro($link, $cd_conteudo_link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video);
    }    

    private function imprimeFormularioCadastro($link, $cd_conteudo_link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/data_hora.php';                                    $dh   = new DataHora();

      include "js/js_cadastro_link.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salv_link\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_conteudo_link', $cd_conteudo_link);
      $util->campoHidden('cd_tipo_link', $cd_tipo_link);
      $util->campoHidden('cd_origem_link', $cd_origem_link);
      $util->campoHidden('tp_origem_link', $tp_origem_link);
      $util->campoHidden('ds_chave_link', $ds_chave_link);
      
      if ($cd_tipo_link == '1')        {        $util->campoHidden('tipo', 'Vídeo');
      } elseif ($cd_tipo_link == '2')  {        $util->campoHidden('tipo', 'Áudio');
      } elseif ($cd_tipo_link == '4')  {        $util->campoHidden('tipo', 'Referência');
      }       
      if ($cd_tipo_link == '1') {
        $util->campoHidden("nm_link", $cd_conteudo_link."-".$cd_origem_link);      
      } else {
        $util->linhaUmCampoTextHint(1, 'Nome para o Link', 'nm_link', 150, 840, $nm_link, 1);
      }
      $util->linhaUmCampoTextHint(1, 'Link Externo', 'ds_link', 250, 840, $ds_link, 1);
      if (($cd_tipo_link == '1') || ($cd_tipo_link == '2')) {
        $dh->imprimeFormularioHorarioHint('0', 'Tempo de Duração', 'video', '834', $tm_video, 1, 1, 1, 1);
      } else {
        $dh->camposHiddenHora($tm_video, 'video');
      }                                             
      $opcoes= array();
      for ($i=1;$i<($nr_ordem+100);$i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;      $opcoes[]= $opcao;
      }
      $util->linhaSeletorHint('Ordem de Apresentação', 'nr_ordem', $nr_ordem, $opcoes, '840', '1', 1);
      $util->linhaTextoHint(0, 'Descrição: ', 'ds_comentarios', $ds_comentarios, '5', '840', 1);
      $util->linhaUmCampoTextHint(0, 'Autor: ', 'nm_autor', 150, 840, $nm_autor, 1);
      $util->linhaUmCampoTextHint(0, 'E-mail do Autor: ', 'ds_email_autor', 150, 840, $ds_email_autor, 1);
      $util->linhaUmCampoTextHint(0, 'Fonte: ', 'ds_fonte', 150, 840, $ds_fonte, 1);
      $util->linhaUmCampoTextHint(0, 'Link para a Fonte: ', 'ds_link_fonte', 150, 840, $ds_link_fonte, 1);
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'É Ativo';          $opcoes[]= $opcao; 
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não é Ativo';      $opcoes[]= $opcao;
      $util->linhaSeletorHint('É Ativo', 'eh_ativo', $eh_ativo, $opcoes, '840', '1', 1);

      if ($cd_conteudo_link > 0) {        $util->linhaBotao('Editar');           } else {         $util->linhaBotao('Cadastrar');      }

      echo "    </table>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_link'); 
    }

    public function salvarLink() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $cd_conteudo_link = addslashes($_POST['cd_conteudo_link']);
      $cd_origem_link = addslashes($_POST['cd_origem_link']);
      $tp_origem_link = addslashes($_POST['tp_origem_link']);
      $cd_tipo_link = addslashes($_POST['cd_tipo_link']);
      $nm_link = $util->limparVariavel($_POST['nm_link']);
      if (($cd_tipo_link == '1') || ($cd_tipo_link == '2')) {
        $ds_link = addslashes($_POST['ds_link']);
      } else {
        $ds_link = $util->limparVariavel($_POST['ds_link']);
        if (($ds_link[0] != 'h') || ($ds_link[1] != 't') || ($ds_link[2] != 't') || ($ds_link[3] != 'p')) {
          $ds_link= "http://".$ds_link;
        }      
      }
      $nm_autor = $util->limparVariavel($_POST['nm_autor']);
      $ds_email_autor = $util->limparVariavel($_POST['ds_email_autor']);
      $ds_fonte = $util->limparVariavel($_POST['ds_fonte']);
      $ds_link_fonte = $util->limparVariavel($_POST['ds_link_fonte']);
      $ds_comentarios = $util->limparVariavel($_POST['ds_comentarios']);
      $nr_ordem = $util->limparVariavel($_POST['nr_ordem']);
      $eh_ativo = addslashes($_POST['eh_ativo']);
      $tipo = addslashes($_POST['tipo']);
      
      $hora_video = addslashes($_POST['hora_video']);
      $minuto_video = addslashes($_POST['minuto_video']);
      $segundo_video = addslashes($_POST['segundo_video']);
      $tm_video = $hora_video.":".$minuto_video.":".$segundo_video;      

      $lk_seo = $util->retornaLinkSEO($nm_link, 'life_conteudos_links', 'lk_seo', '150', $cd_conteudo_link);

      if ($cd_conteudo_link > 0) {
        $ds_chave_link = $util->limparVariavel($_POST['ds_chave_link']);
        if ($this->alterarLink($cd_conteudo_link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video)) {
          echo "<p class=\"fontConteudoSucesso\">".$tipo." editado(a) com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do(a) ".$tipo.", ou nenhuma informação alterada!</p>\n";
        }        
      } else {
        $ds_chave_link = "life_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,15)."_".substr(md5(uniqid(time())),0,14);
        if ($this->inserirLink($cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video)) {
          echo "<p class=\"fontConteudoSucesso\">".$tipo." cadastrado(a) com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do(a) ".$tipo."!</p>\n";
        }
      }
    } 

    public function alteraStatusLink($cd_origem_link, $tp_origem_link, $cd_tipo_link, $cd_conteudo_link) {
      $dados= $this->selectDadosLink($cd_conteudo_link);
      if (($dados['cd_tipo_link'] != $cd_tipo_link) || ($dados['cd_origem_link'] != $cd_origem_link) ||($dados['tp_origem_link'] != $tp_origem_link)) {
        echo "<p class=\"fontConteudoAlerta\">Erro ao recuperar dados para exclusão!</p>\n";
        return false;
      }
      if ($cd_tipo_link == '1')        {        $tipo = 'Vídeo';
      } elseif ($cd_tipo_link == '2')  {        $tipo = 'Áudio';
      } elseif ($cd_tipo_link == '4')  {        $tipo = 'Referência';
      }       
      $nm_link = $dados['nm_link'];
      $ds_link = $dados['ds_link'];
      $nm_autor = $dados['nm_autor'];
      $ds_email_autor = $dados['ds_email_autor'];
      $ds_fonte = $dados['ds_fonte'];
      $ds_link_fonte = $dados['ds_link_fonte'];
      $ds_comentarios = $dados['ds_comentarios'];
      $ds_chave_link = $dados['ds_chave_link'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];
      $tm_video = $dados['tm_video'];

      if ($eh_ativo == 1) {        $eh_ativo= 0;      } else {        $eh_ativo= 1;      }
      if ($this->alterarLink($cd_conteudo_link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video)) {
        echo "<p class=\"fontConteudoSucesso\">Status do(a) ".$tipo." alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar Status do(a) ".$tipo."!</p>\n";
      }
    }     
                                                                 
//*******************EXIBIÇÃO***************************************************
/*
    public function retornaRelacaoLinks($tp_origem_link, $cd_origem_link, $cd_tipo_link, $acesso_liberado) {
      $links = $this->selectLinks($cd_tipo_link, $cd_origem_link, $tp_origem_link, '1');

      if (count($links) > 0) {
        if ($cd_tipo_link == '1') {                                             //Vídeos
          require_once 'includes/configuracoes.php';                            $conf = new Configuracao();
          if ($tp_origem_link == 'VI') {
            $largura = $conf->retornaLarguraVideoCapaCurso();
            $altura = $conf->retornaAlturaVideoCapaCurso();
          } else {
            $largura = $conf->retornaLarguraVideoInternoCurso();
            $altura = $conf->retornaAlturaVideoInternoCurso();
          } 
          foreach ($links as $l) {
            if (($acesso_liberado) || ($l['eh_publico'])) { 
              echo "      <iframe src=\"//".$l['ds_link']."\" width=\"98.8%\" height=\"450\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen style=\"padding-top:0.6%; padding-left:5px; padding-right:5px;\" name=\"video_preview\"></iframe>\n";
              if ($tp_origem_link != 'VI') {
                echo "    <br />\n";
              }
            }
          }
        
        } elseif ($cd_tipo_link == '2') {                                       //Áudios
          foreach ($links as $l) {
            if (($acesso_liberado) || ($l['eh_publico'])) { 
              echo "    <div class=\"divVideo\">\n";
              echo $l['ds_link'];
              echo "    </div>\n";
              echo "    <br />\n";
            }
          }
        } elseif ($cd_tipo_link == '4') {                                       //Referências
          echo " <h3>Referências Complementares</h3>\n";
          foreach ($links as $l) {
            echo "  <p>\n";
            if (($acesso_liberado) || ($l['eh_publico'])) { 
              echo "    - <a href=\"".$l['ds_link']."\" target=\"_blank\" class=\"fontLink\">".$l['nm_link']."</a><br />\n";
            } else {
              echo "    - ".$l['nm_link']."<br />\n";          
            }        
            echo "  </p>\n";
          }
        }    
      } 
    }
*/
//**************BANCO DE DADOS**************************************************    
    public function selectLinks($cd_tipo_link, $cd_origem_link, $tp_origem_link, $ativos) {
      $sql  = "SELECT * ".
              "FROM life_conteudos_links ".               
              "WHERE cd_tipo_link = '$cd_tipo_link' ".
              "AND cd_origem_link = '$cd_origem_link' ".
              "AND tp_origem_link = '$tp_origem_link' ";
      if ($ativos != '2') {              
        $sql.= "AND eh_ativo = '$ativos' ";
      }
      $sql .= "ORDER BY nr_ordem, nm_link ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA LINKS");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    

    public function selectDadosLink($cd_conteudo_link) {
      $sql  = "SELECT *  ".
              "FROM life_conteudos_links ".
              "WHERE cd_conteudo_link = '$cd_conteudo_link' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA LINKS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function selectMaiorNumeroOrdemConteudoLink($cd_origem_link, $tp_origem_link, $cd_tipo_link) {
      $sql  = "SELECT MAX(nr_ordem) numero  ".
              "FROM life_conteudos_links ".
              "WHERE cd_tipo_link = '$cd_tipo_link' ".
              "AND cd_origem_link = '$cd_origem_link' ".
              "AND tp_origem_link = '$tp_origem_link' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA LINKS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['numero'];
    }
                                      
    public function inserirLink($cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');    
      $sql = "INSERT INTO life_conteudos_links ".
             "(cd_origem_link, tp_origem_link, cd_tipo_link, nm_link, ds_link, nm_autor, ds_email_autor, ds_fonte, ds_link_fonte, ds_comentarios, ds_chave_link, nr_ordem, tm_video, eh_ativo, lk_seo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_origem_link\", \"$tp_origem_link\", \"$cd_tipo_link\", \"$nm_link\", \"$ds_link\", \"$nm_autor\", \"$ds_email_autor\", \"$ds_fonte\", \"$ds_link_fonte\", \"$ds_comentarios\", \"$ds_chave_link\", \"$nr_ordem\", \"$tm_video\", \"$eh_ativo\", \"$lk_seo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'conteudos_links');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA LINKS");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alterarLink($cd_conteudo_link, $cd_origem_link, $tp_origem_link, $cd_tipo_link, $nm_link, $ds_link, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $ds_comentarios, $ds_chave_link, $nr_ordem, $eh_ativo, $lk_seo, $tm_video) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_conteudos_links SET ".
             "nm_link = \"$nm_link\", ".
             "ds_link = \"$ds_link\", ".
             "nm_autor = \"$nm_autor\", ".
             "ds_email_autor = \"$ds_email_autor\", ".
             "ds_fonte = \"$ds_fonte\", ".
             "ds_link_fonte = \"$ds_link_fonte\", ".
             "ds_comentarios = \"$ds_comentarios\", ".
             "ds_chave_link = \"$ds_chave_link\", ".
             "nr_ordem = \"$nr_ordem\", ".
             "tm_video = \"$tm_video\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_conteudo_link = '$cd_conteudo_link' ".
             "AND cd_tipo_link = '$cd_tipo_link' ".
             "AND cd_origem_link = '$cd_origem_link' ".
             "AND tp_origem_link = '$tp_origem_link' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'conteudos_links');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA LINKS");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                  
  }
?>