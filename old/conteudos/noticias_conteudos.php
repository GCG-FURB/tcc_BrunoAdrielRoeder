<?php
  class NoticiaConteudo {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item, $acao, $ativas, $codigo, $cd_noticia) {
      require_once 'conteudos/noticias.php';                                    $mat = new Noticia();

      $noticia = $mat->selectDadosNoticia($cd_noticia);

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $cd_noticia, $ativas, $noticia['tt_noticia']);
          $this->listarItens($secao, $subsecao, $item, $cd_noticia, $ativas);
        break;

        case "cadastrar":
          echo "<h2>Matéria: ".$noticia['tt_noticia']."</h2>\n";
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas;
          $this->montarFormularioCadastro($link, $cd_noticia);
        break;

        case "editar":
          echo "<h2>Matéria: ".$noticia['tt_noticia']."</h2>\n";
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas;
          $this->montarFormularioEdicao($link, $cd_noticia, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $cd_noticia, $ativas, $noticia['tt_noticia']);
          $this->listarItens($secao, $subsecao, $item, $cd_noticia, $ativas);
        break;        
               
        case "status":
          $this->alterarSituacaoAtivoNoticiaConteudo($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $cd_noticia, $ativas, $noticia['tt_noticia']);
          $this->listarItens($secao, $subsecao, $item, $cd_noticia, $ativas);
        break;


        case "fotos":
          require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
          $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_noticia, $codigo, $noticia['tt_noticia']);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$codigo;
          $foto->listarFotos($link, $codigo, 'CI');
        break;  

        case "cad_foto":
          require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
          $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_noticia, $codigo, $noticia['tt_noticia']);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$codigo;
          $foto->montarFormularioCadastroFoto($link, $codigo, 'CI', 'normal');
        break;
      
        case "edi_foto":
          require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
          $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_noticia, $codigo, $noticia['tt_noticia']);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$codigo;
          if (isset($_GET['ft'])) { 
            $foto->montarFormularioEdicaoFoto($link, $codigo, 'CI', 'normal');
          } else {
            $foto->listarFotos($link, $codigo, 'CI');
          }
        break;
                   
        case "salv_foto":
          require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
          $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_noticia, $codigo, $noticia['tt_noticia']);
          if (isset($_SESSION['life_edicao'])) {
            $foto->salvarFoto();
            unset($_SESSION['life_edicao']);
          }
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$codigo;
          $foto->listarFotos($link, $codigo, 'CI');
        break;        
       
        case "exc_foto":
          require_once 'conteudos/fotos.php';                                 $foto= new Fotos();
          $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_noticia, $codigo, $noticia['tt_noticia']);
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$codigo;
          if (isset($_GET['ft'])) {
            $foto->exclusaoFoto($link, $codigo, 'CI');
          }      
          $foto->listarFotos($link, $codigo, 'CI');
        break;            
      }
    }
   
    private function listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_noticia, $cd_noticia_conteudo, $tt_noticia) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&tp=adicionais&cm=".$cd_noticia."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$cd_noticia_conteudo."&acao=cad_foto\"><img src=\"icones/novo.png\" alt=\"Nova Foto\" title=\"Nova Foto\" border=\"0\"></a> \n";
      echo "</p>\n";

      $conteudo = $this->selectDadosNoticiaConteudo($cd_noticia_conteudo);
      echo "<h2>Matéria: ".$tt_noticia."</h2>\n";
      if ($conteudo['tt_conteudo'] != '') {
        echo "<h2>Conteúdo: ".$conteudo['tt_conteudo']."</h2>\n";      
      } else {
        echo "<h2>Conteúdo: ".$util->abreviar($conteudo['ds_conteudo'], 100)."</h2>\n";      
      }
    }

    public function listarAcoes($secao, $subsecao, $item, $cd_noticia, $ativas, $tt_noticia) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=1";                 $opcao['descricao']= "Ativos";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=0";                 $opcao['descricao']= "Inativos";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=2";                 $opcao['descricao']= "Ativos/Inativos";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                                  $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."\"><img src=\"icones/voltar.png\" alt=\"Voltar ao Cadastro de Matérias\" title=\"Voltar ao Cadastro de Matérias\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Conteúdo para a Matéria\" title=\"Novo Conteúdo para a Matéria\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
      echo "<h2>Matéria: ".$tt_noticia."</h2>\n";
    }

    private function listarItens($secao, $subsecao, $item, $cd_noticia, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();      
      $itens = $this->selectNoticiaConteudos($ativas, $cd_noticia);

      $mensagem = "Conteúdos das Matérias ";
      if ($ativas == 1) {             $mensagem.= "Ativos ";      } elseif ($ativas == 0) {       $mensagem.= "Inativos ";      }

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Conteúdo da Matéria:</td>\n";
      echo "      <td class=\"celConteudo\">Ordem:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        if ($it['tt_conteudo'] != '') { 
          echo "      <td class=\"celConteudo\">".$it['tt_conteudo']."</td>\n";
        } else {
          echo "      <td class=\"celConteudo\">".$util->abreviar($it['ds_conteudo'], 100)."</td>\n";
        }
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharNoticiaConteudo($it['cd_noticia_conteudo']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$it['cd_noticia_conteudo']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$it['cd_noticia_conteudo']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&tp=adicionais&cm=".$cd_noticia."&at=".$ativas."&cd=".$it['cd_noticia_conteudo']."&acao=fotos\"><img src=\"icones/fotos.png\" alt=\"Fotos\" title=\"Fotos\"  border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
      echo "  <br /><br />\n"; 
    }
    
    public function detalharNoticiaConteudo($cd_noticia_conteudo) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosNoticiaConteudo($cd_noticia_conteudo);
      
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
     
    private function montarFormularioCadastro($link, $cd_noticia) {
      $cd_noticia_conteudo = "";
      $maior_nr_ordem = $this->selectMaiorNumeroOrdemNoticia($cd_noticia);
      $nr_ordem = $maior_nr_ordem + 1;
      $tt_conteudo = "";
      $ds_conteudo = "";
      $eh_ativo = "1";
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Conteúdos das Matérias</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_noticia_conteudo, $cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo);
    }
    
    private function montarFormularioEdicao($link, $cd_noticia, $cd_noticia_conteudo) {
      $dados = $this->selectDadosNoticiaConteudo($cd_noticia_conteudo);
      
      if ($dados['cd_noticia'] != $cd_noticia) {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao recuperar Conteúdo da Matéria para edição!</p>\n";
        return false;
      }
      $nr_ordem = $dados['nr_ordem'];
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Conteúdo da Matéria</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_noticia_conteudo, $cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_noticia_conteudo, $cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_noticia_conteudo.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_noticia_conteudo', $cd_noticia_conteudo);
      $util->campoHidden('cd_noticia', $cd_noticia);
      $util->linhaUmCampoText(0, 'Título: ', 'tt_conteudo', 150, 70, $tt_conteudo);
      $util->linhaTexto(1, 'Conteúdo: ', 'ds_conteudo', $ds_conteudo, '15', '910');
      $util->linhaUmCampoText(1, 'Número de Ordem: ', 'nr_ordem', 4, 10, $nr_ordem);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      if ($cd_noticia_conteudo > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'cd_parceiro'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_noticia_conteudo = addslashes($_POST['cd_noticia_conteudo']);
      $cd_noticia = addslashes($_POST['cd_noticia']);
      $tt_conteudo = $util->limparVariavel($_POST['tt_conteudo']);
      $ds_conteudo = $util->limparVariavel($_POST['ds_conteudo']);
      $nr_ordem = addslashes($_POST['nr_ordem']);

      $dados = $this->selectNumeroOrdemNoticia($nr_ordem, $cd_noticia);
      if (($dados['cd_noticia_conteudo'] != '') && ($dados['cd_noticia_conteudo'] != $cd_noticia_conteudo)){
        $maior_nr_ordem = $this->selectMaiorNumeroOrdemNoticia($cd_noticia);
        $nr_ordem = $maior_nr_ordem + 1;
        echo "<p class=\"fontConteudoAlerta\">Número de Ordem informado já estava cadastrado para a Matéria, sendo assim foi alterado automaticamente!</p>\n";
      }
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_noticia_conteudo > 0) {
        if ($this->alteraNoticiaConteudo($cd_noticia_conteudo, $cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo)) {
          echo "<p class=\"fontConteudoSucesso\">Conteúdo da Matéria editado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Conteúdo da Matéria, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereNoticiaConteudo($cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo)) {
          echo "<p class=\"fontConteudoSucesso\">Conteúdo da Matéria cadastrado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Conteúdo da Matéria!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoNoticiaConteudo($cd_noticia_conteudo) {
      $dados = $this->selectDadosNoticiaConteudo($cd_noticia_conteudo);

      $cd_noticia = $dados['cd_noticia'];
      $nr_ordem = $dados['nr_ordem'];
      $tt_conteudo = $dados['tt_conteudo'];
      $ds_conteudo = $dados['ds_conteudo'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraNoticiaConteudo($cd_noticia_conteudo, $cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Conteúdo da Matéria alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do Conteúdo da Matéria!</p>\n";
      }
    }
    
    private function existeNumeroOrdem($nr_ordem, $cd_noticia) {
      $dados = $this->selectNumeroOrdemNoticia($nr_ordem, $cd_noticia);
      if ($dados['cd_noticia_conteudo'] != '') {
        return true;
      } else {
        return false;
      }
    }

//************************EXIBICAO**********************************************
    public function listarConteudosNoticia($cd_noticia) {
      require_once 'conteudos/fotos.php';                                       $foto = new Fotos();
      
      $conteudos = $this->selectNoticiaConteudos('1', $cd_noticia);
      foreach ($conteudos as $conteudo) {
        if ($conteudo['tt_conteudo'] != '') {
          echo "        <h3>".$conteudo['tt_conteudo']."</h3>\n";
        }
        echo "      <p>\n";
        echo "        ".nl2br($conteudo['ds_conteudo'])."\n";
        echo "      </p>\n";
        $foto->exibeFotosAreaCentral($conteudo['cd_noticia_conteudo'], 'CI');
      }   
    }    

//**************BANCO DE DADOS**************************************************    
    public function selectNoticiaConteudos($eh_ativo, $cd_noticia) {
      $sql  = "SELECT * ".
              "FROM life_noticias_conteudos ".
              "WHERE cd_noticia = '$cd_noticia' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nr_ordem";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA NOTICIAS CONTEUDOS");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectMaiorNumeroOrdemNoticia($cd_noticia) {
      $sql  = "SELECT MAX(nr_ordem) nr ".
              "FROM life_noticias_conteudos ".
              "WHERE cd_noticia = '$cd_noticia' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA NOTICIAS CONTEUDOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['nr'];        
    }
    
    public function selectNumeroOrdemNoticia($nr_ordem, $cd_noticia) {
      $sql  = "SELECT * ".
              "FROM life_noticias_conteudos ".
              "WHERE cd_noticia = '$cd_noticia' ".
              "AND nr_ordem = '$nr_ordem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA NOTICIAS CONTEUDOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }    
    
    public function selectDadosNoticiaConteudo($cd_noticia_conteudo) {
      $sql  = "SELECT * ".
              "FROM life_noticias_conteudos ".
              "WHERE cd_noticia_conteudo = '$cd_noticia_conteudo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA NOTICIAS CONTEUDOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereNoticiaConteudo($cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_noticias_conteudos ".
             "(cd_noticia, nr_ordem, eh_ativo, tt_conteudo, ds_conteudo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_noticia\", \"$nr_ordem\", \"$eh_ativo\", \"$tt_conteudo\", \"$ds_conteudo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'noticia_conteudos');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA NOTICIAS CONTEUDOS");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraNoticiaConteudo($cd_noticia_conteudo, $cd_noticia, $nr_ordem, $eh_ativo, $tt_conteudo, $ds_conteudo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_noticias_conteudos SET ".
             "eh_ativo = \"$eh_ativo\", ".
             "tt_conteudo = \"$tt_conteudo\", ".
             "ds_conteudo = \"$ds_conteudo\", ".
             "nr_ordem = \"$nr_ordem\", ".             
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_noticia_conteudo= '$cd_noticia_conteudo' ".
             "AND cd_noticia = \"$cd_noticia\"";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'noticia_conteudos');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA NOTICIAS CONTEUDOS");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>
