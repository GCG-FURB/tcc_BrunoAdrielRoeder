<?php
  class Patrocinador {
  
    public function __construct() {
    }

    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['cdm']))   {      $codigo_menu = addslashes($_GET['cdm']);    } else {      $codigo_menu = '0';       }

      switch ($acao) {
        case "":
          $this->listarAcoes($secao, $subsecao, $item, $codigo_menu, $ativas);
          $this->listarItens($secao, $subsecao, $item, $codigo_menu, $ativas);
        break;      

        case "cadastrar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=".$ativas;
          $this->montarPatrocinadorCadastro($link);
        break;

        case "editar":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=".$ativas;
          $this->montarPatrocinadorEdicao($link, $codigo);
        break;

        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            $this->salvarCadastroAlteracao();
            unset($_SESSION['life_edicao']);
          } 
          $this->listarAcoes($secao, $subsecao, $item, $codigo_menu, $ativas);
          $this->listarItens($secao, $subsecao, $item, $codigo_menu, $ativas);
        break;        
        
        case "alt_status":
          $this->alterarStatusItem($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $codigo_menu, $ativas);
          $this->listarItens($secao, $subsecao, $item, $codigo_menu, $ativas);
        break;

        case "secao":
          $this->listarAcoes($secao, $subsecao, $item, '0', $ativas);
          require_once 'menu/menu.php';                                         $obj = new Menu();
          $_SESSION['life_edicao'] = '1';
          $obj->controleEdicao($secao, $subsecao, $item, $codigo_menu, $codigo_menu);
        break;

        case "salvar_secao":
          $this->listarAcoes($secao, $subsecao, $item, $codigo_menu, $ativas);
          require_once 'menu/menu.php';                                         $obj = new Menu();
          if (isset($_SESSION['life_edicao'])) {
            $obj->controleEdicao($secao, $subsecao, $item, $codigo_menu, $codigo_menu);
            unset($_SESSION['life_edicao']);
          }               
          $this->listarItens($secao, $subsecao, $item, $codigo_menu, $ativas);
        break;

      }
    }
  
    private function listarAcoes($secao, $subsecao, $item, $codigo_menu, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/data_hora.php';                                    $dh= new DataHora();

      $opcoes_1= array();
      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=1";           if($ativas == '1') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Ativos";                                        $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=0";           if($ativas == '0') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Inativos";                                      $opcoes_1[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=2";           if($ativas == '2') { $opcao['selecionado'] = ' selected=\"selected\" '; } else { $opcao['selecionado'] = ''; }      $opcao['descricao']= "Ativos/Inativos";                               $opcoes_1[]= $opcao;
      foreach ($opcoes_1 as $op) {        $nome = 'comandos_filtros_1_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Patrocinador\" title=\"Novo Patrocinador\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
      foreach ($opcoes_1 as $op) {
        echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }
  
    
    public function listarItens($secao, $subsecao, $item, $codigo_menu, $ativas) {
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $itens = $this->selectPatrocinadores($ativas);
      
      $mensagem = "Patrocinadores";

      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Patrocinador</td>\n";
      echo "      <td class=\"celConteudo\">Ordem</td>\n";
      echo "      <td class=\"celConteudo\">Ações</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');      
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_patrocinador']."</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nr_ordem']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharPatrocinador($it['cd_patrocinador']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=".$ativas."&cd=".$it['cd_patrocinador']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cdm=".$codigo_menu."&at=".$ativas."&cd=".$it['cd_patrocinador']."&acao=alt_status\">";
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
       
    private function detalharPatrocinador($cd_patrocinador) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosPatrocinador($cd_patrocinador);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }    

    private function montarPatrocinadorCadastro($link) {
     
      $cd_patrocinador = "";
      $nm_patrocinador = "";
      $ds_link_patrocinador = "";
      $nr_ordem = "";
      $eh_ativo = "1";
      $ds_logo = "";
      
      echo "  <h2>Cadastro de patrocinadores</h2>\n";
      $_SESSION['life_edicao']= 1;
      $this->imprimePatrocinadorCadastroPatrocinadores($link, $cd_patrocinador, $nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo);
    }

    private function montarPatrocinadorEdicao($link, $cd_patrocinador) {
      $dados= $this->selectDadosPatrocinador($cd_patrocinador);
      $nm_patrocinador = $dados['nm_patrocinador'];
      $ds_link_patrocinador = $dados['ds_link_patrocinador'];
      $nr_ordem = $dados['nr_ordem'];

      $eh_ativo = $dados['eh_ativo'];
      $ds_logo = $dados['ds_logo'];

      echo "  <h2>Edição de patrocinador</h2>\n";
      $_SESSION['life_edicao']= 1;
      $this->imprimePatrocinadorCadastroPatrocinadores($link, $cd_patrocinador, $nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo);
    }
    
    private function imprimePatrocinadorCadastroPatrocinadores($link, $cd_patrocinador, $nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";
    
      include 'js/js_cadastro_patrocinador.js';
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" enctype=\"multipart/form-data\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_patrocinador', $cd_patrocinador);
      $util->campoHidden('ds_pasta', 'fotos/patrocinadores');
      
      $util->linhaUmCampoText(1, 'Nome ', 'nm_patrocinador', '100', '100', $nm_patrocinador);
      $util->linhaUmCampoText(0, 'Link ', 'ds_link_patrocinador', '100', '100', $ds_link_patrocinador);
      if ($ds_logo != '') {
        $util->campoHidden('ds_logo_antigo', $ds_logo);
        $util->linhaUmCampoArquivo(0, 'Logo ', 'ds_logo', '150', '100', '');
        $util->linhaComentario('Para alterar o arquivo atual, selecione um novo arquivo. O arquivo com a imagem deve estar no formato .png ou .jpg.');
      } else {
        $util->campoHidden('ds_logo_antigo', '');
        $util->linhaUmCampoArquivo(1, 'Logo ', 'ds_logo', '150', '100', '');
        $util->linhaComentario('O arquivo com a imagem deve estar no formato .png ou .jpg.');
      }

      $itens = $this->selectPatrocinadores('2');
      $limite = count($itens) + 50;
      $opcoes= array();
      for ($i=1; $i<=$limite; $i++) {
        $opcao= array();      $opcao[]= $i;      $opcao[]= $i;      $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Ordem ', 'nr_ordem', $nr_ordem, $opcoes, '100');

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É ativo ', 'eh_ativo', $eh_ativo, $opcoes, '100');

      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";
      $util->posicionarCursor('cadastro', 'nm_patrocinador');
    }

    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'conteudos/fotos.php';                                       $foto = new Fotos();

      $cd_patrocinador = addslashes($_POST['cd_patrocinador']);
      $nm_patrocinador = $util->limparVariavel($_POST['nm_patrocinador']);
      $ds_link_patrocinador = $util->limparVariavel($_POST['ds_link_patrocinador']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      $nr_ordem = addslashes($_POST['nr_ordem']);

      $pasta = addslashes($_POST['ds_pasta']);
      $logo = $_FILES['ds_logo'];
      if ($logo['name'] != '') {
        $campo = "ds_logo";
        $envio_foto = $foto->enviarFoto($campo, $pasta, '0', 'PA', '400');
        if ($envio_foto[0] != '') {
          echo "<p class=\"fontConteudoAlerta\">".$envio_foto[0]."</p>\n";
          $ds_logo= '';
        } else {
          $ds_logo= $envio_foto[1];
        }
      } else {
        $ds_logo_antigo = $util->limparVariavel($_POST['ds_logo_antigo']);
        if ($ds_logo_antigo != '') {
          $ds_logo = $ds_logo_antigo;
        } else {
          $ds_logo = '';        
        }
      }   
      
      if ($ds_logo != '') {
        if ($cd_patrocinador != '') {
          if ($this->editarPatrocinador($cd_patrocinador, $nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo)) {
            echo "  <p class=\"fontConteudoSucesso\">Patrocinador editado com sucesso!</p>\n";
          } else {
            echo "  <p class=\"fontConteudoAlerta\">Problemas ao editar patrocinador, ou nenhuma informação alterada!</p>\n";
          }
        } else {
          if ($this->cadastrarPatrocinador($nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo)) {
            echo "  <p class=\"fontConteudoSucesso\">Patrocinador cadastrado com sucesso!</p>\n";
          } else {
            echo "  <p class=\"fontConteudoAlerta\">Problemas no cadastro do patrocinador!</p>\n";
          }
        }
      } else { 
        echo "  <p class=\"fontConteudoAlerta\">Logo do patrocinador não informado!</p>\n";
      }      
    }
    
    private function alterarStatusItem($cd_patrocinador) {
      $dados= $this->selectDadosPatrocinador($cd_patrocinador);
      $nm_patrocinador = $dados['nm_patrocinador'];
      $ds_link_patrocinador = $dados['ds_link_patrocinador'];
      $nr_ordem = $dados['nr_ordem'];
      $eh_ativo = $dados['eh_ativo'];
      $ds_logo = $dados['ds_logo'];

      if ($eh_ativo) {        $eh_ativo= '0';      } else {        $eh_ativo= '1';      }    
      if ($this->editarPatrocinador($cd_patrocinador, $nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo)) {
        echo "  <p class=\"fontConteudoSucesso\">Status do patrocinador alterado com sucesso!</p>\n";
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Problemas ao alterar Status do patrocinador!</p>\n";
      }
    }


//**********************************EXIBIÇÃO************************************
    public function controleExibicaoPublica($pagina, $lista_paginas) {

      $itens = $this->selectPatrocinadoresCorrentes();

      foreach ($itens as $it) {
        if ($it['ds_link_patrocinador'] != '') {
          echo "      <a href=\"".$it['ds_link_patrocinador']."\" target=\"_blank\" class=\"fontLink patrocinador\"><img src=\"".$_SESSION['life_link_completo']."fotos/patrocinadores/".$it['ds_logo']."\" alt=\"".$it['nm_patrocinador']."\" title=\"".$it['nm_patrocinador']."\" border=\"0\" height=\"50\"></a>\n";
        } else {
          echo "        <img src=\"".$_SESSION['life_link_completo']."fotos/patrocinadores/".$it['ds_logo']."\" alt=\"".$it['nm_patrocinador']."\" title=\"".$it['nm_patrocinador']."\" border=\"0\" height=\"50\">\n";
        }
      }
    }

//****************************************BANCO DE DADOS************************
    public function selectPatrocinadores($eh_ativo) {
      $sql = "SELECT * ".
             "FROM life_patrocinadores ".
             "WHERE cd_patrocinador > 0 ";
      if ($eh_ativo != '2') {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_patrocinador ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PATROCINADORES!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;      
    }    
    
    public function selectPatrocinadoresCorrentes() {
      $hoje = date('Y-m-d');
      $sql = "SELECT * ".
             "FROM life_patrocinadores ".
             "WHERE eh_ativo = '1' ".
             "ORDER BY nr_ordem, nm_patrocinador";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PATROCINADORES!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;      
    }
    
    public function selectDadosPatrocinador($codigo) {
      $sql = "SELECT * ".
             "FROM life_patrocinadores ".
             "WHERE cd_patrocinador = '$codigo'";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PATROCINADORES!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;    
    }

    private function cadastrarPatrocinador($nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql= "INSERT INTO life_patrocinadores ".
            "(nm_patrocinador, ds_link_patrocinador, nr_ordem, eh_ativo, ds_logo, cd_usuario_cadastro, dt_cadastro) ".
            "VALUES ".
            "(\"$nm_patrocinador\", \"$ds_link_patrocinador\", \"$nr_ordem\", \"$eh_ativo\", \"$ds_logo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'patrocinadores');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PATROCINADORES!");
      $saida = mysql_affected_rows();
      return $saida;
    }
     
    private function editarPatrocinador($cd_patrocinador, $nm_patrocinador, $ds_link_patrocinador, $nr_ordem, $eh_ativo, $ds_logo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_patrocinadores SET ".
             "nm_patrocinador= \"$nm_patrocinador\", ".
             "ds_link_patrocinador = \"$ds_link_patrocinador\", ".
             "nr_ordem = \"$nr_ordem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "ds_logo = \"$ds_logo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_patrocinador= '$cd_patrocinador' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'patrocinadores');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PATROCINADORES!");
      $saida = mysql_affected_rows();
      return $saida;             
    }     
    
  }
?>