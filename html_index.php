<?php
class HTML
{

    public function __construct()
    {
    }

    public function retornaControleConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual, $cd_menu)
    {
        require_once 'includes/configuracoes.php';
        $conf= new Configuracao();
        require_once 'menu/menu.php';
        $menu = new Menu();
        require_once 'login/login.php';
        $login= new Login();
        require_once 'conteudos/conteudo.php';
        $con= new Conteudo();

        echo "  <body class=\"geral\">\n";
        echo "<script type=\"text/javascript\">window.addEventListener('keydown', marcarDigitou());</script>\n";

      /**
       *Bruno Roeder acessibilidade: adicionado meu ssuperior de acessibilidae, seguindo diretriz 2.4.1
       *que diz que o primeiro link deve ser de acessibiliadde levando ao conteudo do site e logo em seguida ao menu
       **/
        echo "      <div  class=\"divBaseTopo\">\n";
        echo "        <div  class=\"divMenuAcessibilidade\">\n";
        $menu->retornaMenuAcessibilidade($pagina, $atual);
        echo "        </div>\n";
        echo "      </div>\n";

        echo "    <div id=\"menu\">\n";
        echo "      <input type=\"hidden\" name=\"digitou\" id=\"digitou\" value=\"0\" />\n";
        echo "        <div  class=\"divTopo\" id=\"topoSiteControleFonte\">\n";
        echo "          <div  class=\"divConteudoTopo\">\n";
        echo "            <div  class=\"divConteudoEsquerdaTopo\">\n";
        $menu->retornaMenuSuperior($secao, $subsecao, $item, 'SE');
        echo "            </div>\n";
        echo "            <div class=\"divLogo\">\n";
        $conf->retornaLogoSite();
        echo "            </div>\n";
        echo "            <div  class=\"divConteudoDireitaTopo\">\n";
        if ($login->estaLogado()) {
            $login->retornaUsuarioLogado();
        } else {
            $menu->retornaMenuAcessoCadastro($secao);
        }
        echo "            </div>\n";
        echo "          </div>\n";
        echo "        </div>\n";
        echo "      </div>\n";

      // Bruno Roeder: barra de pesquisa fora da barra de acessibilidade
        echo "      <div  class=\"divBaseInferior\">\n";
        echo "        <div  class=\"divMenuInferior\">\n";
        $menu->retornaMenuBarraInferior($pagina, $atual);
        echo "        </div>\n";
        echo "      </div>\n";

        if (!$login->estaLogado()) {
            $con->controlaExibicaoTopoConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual);
        }
        if ($pagina == 'erro') {
            require $pagina.'.php';
        } else {
            $con->controlaExibicaoConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual);
        }
        if ($cd_menu == '0') {
            require_once 'conteudos/home.php';
            $home = new Home();
            $home->retornaConteudoObjetosAprendizagemNoticias();
        }
        echo "      <div class=\"divAreaRodape\">\n";
        echo "        <div class=\"divRodape\">\n";
        require_once 'conteudos/patrocinadores.php';
        $pat = new Patrocinador();
        $pat->controleExibicaoPublica($pagina, $atual);
        echo "        </div>\n";
        echo "      </div>\n";
        if ($login->estaLogado()) {
            echo "    <div id=\"fundo_tela\" class=\"divFundoCapa\">\n";
            echo "    </div>\n";
            echo "    <div id=\"fundo_tela_dados_login\" class=\"divConteudoTopoTelaDadosLogin\">\n";
            echo "      <div id=\"tela_dados_login\" class=\"divTelaDadosLogin\">\n";
            $menu->retornaMenuAdministrativoDadosPessoais('17');
            echo "      </div>\n";
            echo "    </div>\n";
            if ($secao == '4') {
                echo "    <div id=\"fundo_tela\" class=\"divFundoCapaAtivo\">\n";
                echo "    </div>\n";
                echo "    <div id=\"tela_area_cadastro\" class=\"divTelaCadastroAtivo\">\n";
                require_once 'conteudos/pessoas.php';
                $obj = new Pessoa();
                $obj->retornoCadastro();
                echo "    </div>\n";
            }
        } else {
            if (isset($_SESSION['life_exibe_login'])) {
                unset($_SESSION['life_exibe_login']);
                echo "    <div id=\"fundo_tela\" class=\"divFundoCapaAtivo\">\n";
                echo "    </div>\n";
                echo "    <div id=\"tela_login\" class=\"divTelaLoginAtivo\">\n";
                $login->controleExibicaoPublica($pagina, $atual);
                echo "    </div>\n";
            } else {
                echo "    <div id=\"fundo_tela\" class=\"divFundoCapa\">\n";
                echo "    </div>\n";
                echo "    <div id=\"tela_login\" class=\"divTelaLogin\">\n";
                $login->controleExibicaoPublica($pagina, $atual);
                echo "    </div>\n";
            }
            echo "    <div id=\"tela_cadastro\" class=\"divTelaCadastro\">\n";
            require_once 'conteudos/pessoas.php';
            $obj = new Pessoa();
            $obj->controleExibicaoPublica('', array());
            echo "    </div>\n";
        }
        echo "    <div id=\"tela_pesquisa\" class=\"divPesquisaCapa\">\n";
        require_once 'conteudos/objetos_aprendizagem_pesquisa.php';
        $oap = new ObjetoAprendizagemPesquisa();
        $oap->listarOpcoesPesquisaCapa($secao, $subsecao, $item);
        echo "    </div>\n";
        if (!isset($_SESSION['life_tamanho_fonte'])) {
            $_SESSION['life_tamanho_fonte'] = 4;
        }
        $tamanho = $_SESSION['life_tamanho_fonte'];
        echo "<script type=\"text/javascript\">\n";
        echo "mudaTamanho('$tamanho');\n";
        echo "mudarEstilo(window.event);\n";
        // Bruno Roeder acessibilidade: ao pressionar a tecla esc, fecha as caixas de dialogo do portal
        echo "document.onkeydown = function(evt) { \n";
        echo "  evt = evt || window.event; \n";
        echo "   if (evt.keyCode == 27) { \n";
        echo "      ocultarPesquisa(evt); \n";
        echo "      ocultarLogin(evt); \n";
        echo "      ocultarCadastro(evt); \n";
        echo "      ocultarCadastro(evt); \n";
        echo "  }\n";
        echo "}; \n";
        echo "</script>\n";
        echo "    </body>\n";
        echo "  </html>\n";
    }
}
