<?php
  class Menu {

    public function __construct() {
    }

    public function controleExibicao($secao, $subsecao, $item, $cd_menu) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }

      switch ($acao) {
        case "":

          $this->montarFormularioEdicao($secao, $subsecao, $item, $cd_menu, 'salvar');
        break;

        case "salvar":
          $this->salvarEdicaoItemMenu();
          $this->montarFormularioEdicao($secao, $subsecao, $item, $cd_menu, 'salvar');
        break;
      }
    }

    public function montarFormularioEdicao($secao, $subsecao, $item, $item_edicao, $acao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $dados = $this->selectConteudoMenu($item_edicao);
      $cd_menu = $item_edicao;
      $ds_menu = $dados['ds_menu'];
      $ds_menu_completo = $dados['ds_menu_completo'];
      $ds_informacoes = $dados['ds_informacoes'];

      echo "  <h2>Edição de ".$dados['ds_menu']."</h2>\n";

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_cadastro_menu.js";
      echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cd=".$item_edicao."&acao=".$acao."\" onSubmit=\"return valida(this);\">\n";
      $util->campoHidden('eh_form', '1');
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_menu', $cd_menu);
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        require_once 'usuarios/usuarios.php';                                   $usu = new Usuario();
        require_once 'includes/data_hora.php';                                  $dh = new DataHora();
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);

        $util->linhaComentario('Última edição ');
        $util->linhaDuasColunasComentario('Por ', $dados_usuario['nm_usuario']);
        $util->linhaDuasColunasComentario('Em ', $dh->imprimirData($dados['dt_ultima_atualizacao']));
        $util->linhaComentario('<hr>');
      }      
      $util->linhaDuasColunasComentario('Menu ', $ds_menu);
      $util->linhaUmCampoText(1, 'Descrição ', 'ds_menu_completo', '70', '100', $ds_menu_completo);
     
      $util->linhaTexto(1, 'Conteúdo ', 'ds_informacoes', $ds_informacoes, '10', '100');

      $util->linhaBotao('Salvar', "valida(cadastro);");
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'ds_informacoes'); 
    }    

    public function salvarEdicaoItemMenu() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
            
      $cd_menu = addslashes($_POST['cd_menu']);
      $ds_menu_completo = $util->limparVariavel($_POST['ds_menu_completo']);
      $ds_informacoes = $util->limparVariavel($_POST['ds_informacoes']);
      
      if ($this->alterarMenu($cd_menu, $ds_menu_completo, $ds_informacoes)) {
        echo "<p class=\"fontConteudoSucesso\">Seção editada com sucesso!</p>\n";   
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas na edição da seção, ou nenhuma informação alterada!</p>\n";
      }        
    }     


    public function retornaMenuSuperior($secao, $subsecao, $item, $ds_posicao) {
      if (isset($_SESSION['life_categoria'])) {         $cd_categoria_usuario= $_SESSION['life_categoria'];      } else {        $cd_categoria_usuario= 0;      }
      $ativos = 1;
      $pai = 0;
      $itensMenu= $this->selectItensMenu($pai, $cd_categoria_usuario, $ativos, 'G', $ds_posicao);
      
      if ($ds_posicao == 'SE') {
        $position = 'Esquerda';
      } elseif ($ds_posicao == 'SD') {
        $position = 'Direita';
      } else {
        $position = '';
      }      
      $saida  = "";
      //percorrer itens de menu
      $saida .= "     <div class=\"divDivisorBotaoMenuSuperior".$position."\"></div>\n";
      foreach ($itensMenu as $im) {
        if ($im['cd_menu'] == $secao) {
          $eh_selecionado= "Selecionado";
        } else {
          $eh_selecionado= "";
        }      
        $saida .= "     <div class=\"divBotaoMenuSuperior".$position."\">\n";
        $saida .= "       <a href=\"".$_SESSION['life_link_completo'].$im['ds_link']."\" class=\"fontLinkMenuSuperior".$eh_selecionado."\">".$im['ds_menu']."</a>\n";
        $saida .= "     </div>\n";
        $saida .= "     <div class=\"divDivisorBotaoMenuSuperior".$position."\"></div>\n";
      }
      echo $saida;
    }

    public function retornaMenuAcessoCadastro($secao) {
      $saida  = "";
      $saida .= "     <div class=\"divDivisorBotaoMenuSuperiorDireita\"></div>\n";
      $saida .= "     <div class=\"divBotaoMenuSuperiorDireita\">\n";
      if ($secao == '4') {
        $saida .= "       <span class=\"fontLinkMenuSuperiorInativo\">Cadastre-se</span>\n";
      } else {
        $saida .= "       <a href=\"#\" onClick=\"chamarCadastro(event);\" onKeyPress=\"chamarCadastro(event);\" class=\"fontLinkMenuSuperior\">Cadastre-se</a>\n";
      }
      $saida .= "     </div>\n";
      $saida .= "     <div class=\"divDivisorBotaoMenuSuperiorDireita\"></div>\n";
      $saida .= "     <div class=\"divBotaoMenuSuperiorDireita\">\n";
      if ($secao == '4') {
        $saida .= "       <span class=\"fontLinkMenuSuperiorInativo\">Entre</span>\n";
      } else {
        $saida .= "       <a href=\"#\" onClick=\"chamarLogin(event);\" onKeyPress=\"chamarLogin(event);\" class=\"fontLinkMenuSuperior\">Entre</a>\n";
      }
      $saida .= "     </div>\n";
      $saida .= "     <div class=\"divDivisorBotaoMenuSuperiorDireita\"></div>\n";
      echo $saida;
    }

    public function retornaMenuAdministrativo($secao, $subsecao, $item) {
      if (isset($_SESSION['life_categoria'])) {         $cd_categoria_usuario= $_SESSION['life_categoria'];      } else {        $cd_categoria_usuario= 0;      }
      //selecionar itens de menu
      $ativos = 1;
      $pai = 0;
      $ds_posicao = 'SA';
      $itensMenu = $this->selectItensMenu($pai, $cd_categoria_usuario, $ativos, '', $ds_posicao);
      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 
      if (count($itensMenu) > 0) {
        echo "<p class=\"fontMenuAdministrativo\">\n";      
        echo "  &nbsp;&nbsp;&nbsp;&nbsp;\n";
        //percorrer itens de menu
        foreach ($itensMenu as $im) {
          if ($im['cd_menu'] == $secao) {
            $eh_selecionado= "Selecionado";
          } else {
            $eh_selecionado= "";
          }      
          $posicao= $im['id_permissao'];
          if (($im['tp_permissao'] == 'G') || ($permissoes_usuario[0] == 1) || ($permissoes_usuario[$posicao] == 1)) {
            echo "<a href=\"".$_SESSION['life_link_completo'].$im['ds_link']."\" class=\"fontLinkMenuAdministrativo".$eh_selecionado."\">".$im['ds_menu']."</a>\n";
            echo "  &nbsp;&nbsp;&nbsp;&nbsp;\n";
          }
        }
        echo "</p>\n";
      }
    }
    
    public function retornaMenuAdministrativoInterno($secao, $subsecao, $item) {
      require_once 'login/login.php';                                           $login = new Login();
      if ($login->estaLogado()) {
        if (isset($_SESSION['life_categoria'])) {         $cd_categoria_usuario= $_SESSION['life_categoria'];      } else {        $cd_categoria_usuario= 0;      }
        $ativos = 1;
        $pai = $secao;

        if (isset($_SESSION['life_permissoes'])) {
          $permissoes_usuario= $_SESSION['life_permissoes'];
        } else {
          $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        }

        echo "  <div class=\"divCadastrosConfiguracoes\">\n";
        $ds_posicao = 'IA';
        $dados_secao = $this->selectConteudoMenu($pai);
        $posicao = $dados_secao['id_permissao'];
        if (($dados_secao['tp_permissao'] == 'G') || ($permissoes_usuario[0] == 1) || ($permissoes_usuario[$posicao] == 1)) {
          if ($pai == '10') {
            echo "    <h1>".$dados_secao['ds_menu']."</h1>\n";
          }
          $itensMenu = $this->selectItensMenu($pai, $cd_categoria_usuario, $ativos, '', $ds_posicao);
          if (count($itensMenu) > 0) {
            //percorrer itens de menu
            foreach ($itensMenu as $im) {
              $posicao= $im['id_permissao'];
              if (($im['tp_permissao'] == 'G') || ($permissoes_usuario[0] == 1) || ($permissoes_usuario[$posicao] == 1)) {
                $ativos = 1;
                $pai = $im['cd_menu'];
                $ds_posicao = 'MI';
                $itensSubMenu = $this->selectItensMenu($pai, $cd_categoria_usuario, $ativos, '', $ds_posicao);
                if (count($itensSubMenu) > 0) {
                  echo "  <div class=\"divAreaMenuInterno\">\n";
                  echo "    <h2>".$im['ds_menu_completo']."</h2>\n";
                  foreach ($itensSubMenu as $ism) {
                    $posicao= $ism['id_permissao'];
                    if (($ism['tp_permissao'] == 'G') || ($permissoes_usuario[0] == 1) || ($permissoes_usuario[$posicao] == 1)) {
                      echo "    <p class=\"fontConteudoMenuInterno\">\n";
                      if ($ism['ds_link'] == '') {
                        echo "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$im['cd_menu']."&it=".$ism['cd_menu']."\"><img src=\"".$_SESSION['life_link_completo']."icones/".$ism['ds_icone']."\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" height=\"35\" border=\"0\"></a>\n";
                        echo "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$im['cd_menu']."&it=".$ism['cd_menu']."\" class=\"fontLink\">".$ism['ds_menu_completo']."</a>\n";
                      } elseif($ism['eh_link_seo'] == '1') {
                        echo "      <a href=\"".$_SESSION['life_link_completo'].$dados_secao['ds_link']."/".$im['ds_link']."/".$ism['ds_link']."\"><img src=\"".$_SESSION['life_link_completo']."icones/".$ism['ds_icone']."\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" height=\"35\" border=\"0\"></a>\n";
                        echo "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$im['cd_menu']."&it=".$ism['cd_menu']."\" class=\"fontLink\">".$ism['ds_menu_completo']."</a>\n";
                      } else {
                        echo "      <a href=\"".$_SESSION['life_link_completo'].$ism['ds_link']."\"><img src=\"".$_SESSION['life_link_completo']."icones/".$ism['ds_icone']."\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" height=\"35\" border=\"0\"></a>\n";
                        echo "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$im['cd_menu']."&it=".$ism['cd_menu']."\" class=\"fontLink\">".$ism['ds_menu_completo']."</a>\n";
                      }
                      echo "    </p>\n";
                    }
                  }
                  echo "  </div>\n";
                }
              }
            }
          }
        }
        echo "  </div>\n";
      }      
    }

    public function retornaMenuAdministrativoDadosPessoais($secao) {
      require_once 'login/login.php';                                           $login = new Login();
      if ($login->estaLogado()) {
        if (isset($_SESSION['life_categoria'])) {         $cd_categoria_usuario= $_SESSION['life_categoria'];      } else {        $cd_categoria_usuario= 0;      }
        $ativos = 1;
        $pai = $secao;
        $ds_posicao = 'IA';
        $itensMenu = $this->selectItensMenu($pai, $cd_categoria_usuario, $ativos, '', $ds_posicao);

        if (isset($_SESSION['life_permissoes'])) {
          $permissoes_usuario= $_SESSION['life_permissoes'];
        } else {
          $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        }

        $dados_secao = $this->selectConteudoMenu($secao);
        if (count($itensMenu) > 0) {
          $im = $itensMenu[0];
          $posicao = $im['id_permissao'];
          if (($im['tp_permissao'] == 'G') || ($permissoes_usuario[0] == 1) || ($permissoes_usuario[$posicao] == 1)) {
            $ativos = 1;
            $pai = $im['cd_menu'];
            $ds_posicao = 'MI';
            $itensSubMenu = $this->selectItensMenu($pai, $cd_categoria_usuario, $ativos, '', $ds_posicao);

            echo "<div class=\"divDadosUsuarioLogadoEvidencia\">\n";
            echo "  <div class=\"divNomeUsuarioLogadoEvidencia\">\n";
            echo "    <p class=\"fontNomeUsuarioLogadoEvidencia\">\n";
            if ($_SESSION['life_nome'] == '') {
              echo "      ".$_SESSION['life_usuario']."\n";
            } else {
              echo "      ".$_SESSION['life_nome']."\n";
            }
            echo "    </p>\n";
            echo "  </div>\n";
            echo "</div>\n";
            echo "<div class=\"divAcoesUsuarioLogadoEvidencia\">\n";

            foreach ($itensSubMenu as $ism) {
              $posicao = $ism['id_permissao'];
              if (($ism['tp_permissao'] == 'G') || ($permissoes_usuario[0] == 1) || ($permissoes_usuario[$posicao] == 1)) {
                if  (($ism['cd_menu'] != 23) || ($_SESSION['life_origem'] == '0'))  {
                if ($ism['ds_link'] == '') {
                  echo "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$im['cd_menu']."&it=".$ism['cd_menu']."\"><img src=\"".$_SESSION['life_link_completo']."icones/".$ism['ds_icone']."\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;\n";
                  echo "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=".$secao."&sub=".$im['cd_menu']."&it=".$ism['cd_menu']."\" class=\"fontLinkMenuDadosLogin\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\">".$ism['ds_menu_completo']."</a><br />\n";
                } elseif($ism['eh_link_seo'] == '1') {
                  echo "      <a href=\"".$_SESSION['life_link_completo'].$dados_secao['ds_link']."/".$im['ds_link']."/".$ism['ds_link']."\"><img src=\"".$_SESSION['life_link_completo']."icones/".$ism['ds_icone']."\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;\n";
                  echo "      <a href=\"".$_SESSION['life_link_completo'].$dados_secao['ds_link']."/".$im['ds_link']."/".$ism['ds_link']."\" class=\"fontLinkMenuDadosLogin\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\">".$ism['ds_menu_completo']."</a><br />\n";
                } else {
                  echo "      <a href=\"".$_SESSION['life_link_completo'].$ism['ds_link']."\"><img src=\"".$_SESSION['life_link_completo']."icones/".$ism['ds_icone']."\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;\n";
                  echo "      <a href=\"".$_SESSION['life_link_completo'].$ism['ds_link']."\" class=\"fontLinkMenuDadosLogin\" alt=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\" title=\"".$im['ds_menu_completo']." - ".$ism['ds_menu_completo']."\">".$ism['ds_menu_completo']."</a><br />\n";
                }
                }
              }
            }
            if (($permissoes_usuario[35] == '1') && (!isset($_SESSION['life_gerenciar_denuncias']))) {
              require_once 'conteudos/objetos_aprendizagem_denuncias.php';        $oad = new ObjetoAprendizagemDenuncia();
              if ($oad->existeNecessidadeGerenciamentoDenuncias()) {
                $_SESSION['life_gerenciar_denuncias'] = "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=68\"><img src=\"".$_SESSION['life_link_completo']."icones/gerenciar_denuncias.png\" alt=\"Gerenciar denúncias contra Objetos de Aprendizagem\" title=\"Gerenciar denúncias contra Objetos de Aprendizagem\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;\n".
                                                        "      <a href=\"".$_SESSION['life_link_completo']."index.php?secao=68\" class=\"fontLinkMenuDadosLogin\" alt=\"Gerenciar denúncias contra Objetos de Aprendizagem\" title=\"Gerenciar denúncias contra Objetos de Aprendizagem\">Gerenciar denúncias</a><br />\n";
              }
            }
            if (isset($_SESSION['life_gerenciar_denuncias'])) {
              echo $_SESSION['life_gerenciar_denuncias'];
            }
            echo "</div>\n";
          }
        }
      }
    }

    public function retornaMenuAcessibilidade($pagina, $atual) {
      require_once 'login/login.php';                                           $login = new Login();

      if ($login->estaLogado()) {
        echo "      <a href=\"".$_SESSION['life_link_completo']."meus-objetos-aprendizagem\"><img src=\"".$_SESSION['life_link_completo']."icones/meus_objetos_aprendizagem.png\" alt=\"Meus Objetos de Aprendizagem\" title=\"Meus Objetos de Aprendizagem\" border=\"0\"></a>\n";
      }
      echo "      <a href=\"".$_SESSION['life_link_completo']."como-usar\"><img src=\"".$_SESSION['life_link_completo']."icones/como_usar.png\" alt=\"Como Usar\" title=\"Como Usar\" border=\"0\"></a>\n";
      echo "      <a href=\"#\" onClick=\"chamarPesquisa(event);\" onKeyPress=\"chamarPesquisa(event);\"><img src=\"".$_SESSION['life_link_completo']."icones/pesquisar_capa.png\" alt=\"Pesquisar\" title=\"Pesquisar\" border=\"0\"></a>\n";
      echo "      <input type=\"hidden\" name=\"tamanho_fonte\" id=\"tamanho_fonte\"/>\n";
      echo "      <a href=\"javascript:void()\" onClick=\"mudaTamanho('d');\" onKeyPress=\"if (event.keyCode == 13 || event.which == 13 || event.type == \"click\"){mudaTamanho('d');}\"><img src=\"".$_SESSION['life_link_completo']."icones/fonte_diminuir.png\" alt=\"Diminuir tamanho da letra\" title=\"Diminuir tamanho da letra\" border=\"0\"></a>\n";
      echo "      <a href=\"javascript:void()\" onClick=\"mudaTamanho('p');\" onKeyPress=\"if (event.keyCode == 13 || event.which == 13 || event.type == \"click\"){mudaTamanho('p');}\"><img src=\"".$_SESSION['life_link_completo']."icones/fonte_normal.png\" alt=\"Deixar a letra no tamanho padrão\" title=\"Deixar a letra no tamanho padrão\" border=\"0\"></a>\n";
      echo "      <a href=\"javascript:void()\" onClick=\"mudaTamanho('a');\" onKeyPress=\"if (event.keyCode == 13 || event.which == 13 || event.type == \"click\"){mudaTamanho('a');}\"><img src=\"".$_SESSION['life_link_completo']."icones/fonte_aumentar.png\" alt=\"Aumentar tamanho da letra\" title=\"Aumentar tamanho da letra\" border=\"0\"></a>\n";
    }

    public function retornaDadosMenu($secao, $subsecao, $item) {
      if ($item != '0') {
        return $this->selectConteudoMenu($item);
      } elseif ($subsecao != '0') {
        return $this->selectConteudoMenu($subsecao);
      } else {
        return $this->selectConteudoMenu($secao);
      }
    }
                                
    public function retornaSecao($link, $secao, $subsecao) {
      if ($subsecao > 0) {        $pai = $subsecao;      } else {        $pai = $secao;      }
      $dados = $this->selectDadosMenuLink($link, $pai);
      return $dados['cd_menu'];
    }
                                
    public function retornaListaBranca($secao, $subsecao, $item) {
      if (isset($_SESSION['life_categoria'])) {         $cd_categoria_usuario = $_SESSION['life_categoria'];      } else {        $cd_categoria_usuario = 0;      }
      if ($item > 0) {
        $itens = $this->selectItensMenu($item, $cd_categoria_usuario, '2', '', '');
      } elseif ($subsecao > 0) {
        $itens = $this->selectItensMenu($subsecao, $cd_categoria_usuario, '2', '', '');
      } else {
        $itens = $this->selectItensMenu($secao, $cd_categoria_usuario, '2', '', '');
      }
      $lista = array();
      foreach ($itens as $it) {        $lista[] =  $it['ds_link'];      }      
      $lista[] = 'erro';
      $lista[] = 'restrito';
      return $lista;
    }

    public function retornaConteudoMenu($cd_menu) {
      $dados = $this->selectConteudoMenu($cd_menu);
      echo "<p class=\"fontConteudo\">".nl2br($dados['ds_informacoes'])."</p>\n";
    }

//******************************BANCO DE DADOS*********************************
    public function selectItensMenu($cd_pai, $cd_categoria_usuario, $eh_ativo, $tp_permissao, $ds_posicao) {
      $sql = "SELECT * ".
             "FROM life_menu ".
             "WHERE cd_menu_pai = '$cd_pai' ";
      if ($tp_permissao != '') {
        $sql.= "AND tp_permissao = '$tp_permissao' ";
      }
      if ($ds_posicao != '') {
        $sql.= "AND ds_posicao = '$ds_posicao' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ds_ordem, ds_menu";
     
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA MENU!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;       
    }

    public function selectDadosMenuLink($lk_seo, $pai) {
      $sql  = "SELECT * ".
              "FROM life_menu ".
              "WHERE ds_link = '$lk_seo' ".
              "AND cd_menu_pai = '$pai'";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA MENU!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;       
    }
                                   
    public function selectConteudoMenuSEO($lk_seo) {
      $sql  = "SELECT * ".
              "FROM life_menu ".
              "WHERE ds_link = '$lk_seo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA MENU!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;       
    }

    public function selectConteudoMenu($item) {
      $sql  = "SELECT * ".
              "FROM life_menu ".
              "WHERE cd_menu = '$item'";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA MENU!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;
    }

    private function alterarMenu($cd_menu, $ds_menu_completo, $ds_informacoes) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_menu SET ".
             "ds_informacoes = \"$ds_informacoes\", ".
             "ds_menu_completo = \"$ds_menu_completo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_menu = '$cd_menu' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'menu');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA MENU!");
      $saida = mysql_affected_rows();
      return $saida;      
    }
    

  }
?>