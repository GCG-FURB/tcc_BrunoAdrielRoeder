<?php
  session_start();
  include_once "includes/conectar.php";
  require_once 'includes/configuracoes.php';                                    $conf= new Configuracao();
  $_SESSION['life_tipo_equipamento_acesso'] = 'pc';
  $_SESSION['life_link_completo'] = $conf->retornaLinkCompletoAplicacao();
  $nm_site = $conf->retornaNomeSite();
  require_once 'menu/menu.php';                                                 $menu = new Menu();
  require_once 'login/login.php';                                               $login= new Login();

  require_once 'includes/interpretador_link.php';                               $int_lin = new InterpretadorLink();
  $retorno = $int_lin->interpretar();
  $atual = $retorno['atual'];
  $pagina = $retorno['pagina'];
  $secao = $retorno['secao'];
  $subsecao = $retorno['subsecao'];
  $item = $retorno['item'];
                  
  if ((isset($atual[1])) && ($atual[1] == 'entrar')) {
    if ($login->executarProcedimentosLogin()) {
      $atual = array();       $atual[0] = 'home';          $pagina = 'home';
      $secao = '0';           $subsecao = '0';             $item = '0';
    }
  }
  $dados_menu = $menu->retornaDadosMenu($secao, $subsecao, $item);
  $no_index = $dados_menu['eh_indexar'];
/*

  
  //se é para logar
  if ((isset($_GET['acao'])) && ($_GET['acao']) == 'logar') {
    $no_index = true;
    if (!$login->executarProcedimentosLogin()) {
      $_SESSION['life_erro_login'] = "Erro ao efetuar Login!"; 
    }
  }

  if (!isset($_SESSION['life_primeiro'])) {
    require_once 'includes/contador.php';                                       $contador = new Contador();
    //somente na primeira vez que a pagina é carregada para evitar que fique tentando contar a cada reload 
    $contador->contabilizaSecao();
    $_SESSION['life_primeiro']= 1;
  }

*/
  $permissao = array();
  $permissao[0]= $dados_menu['tp_permissao'];
  $permissao[1]= $dados_menu['id_permissao']; 
  
  $descricao_secao = $dados_menu['ds_menu_completo'];
  if ($secao > 0) {
    $descricao_site = ".:. ".$nm_site." .:. ".$dados_menu['ds_menu_completo']; 
  } else {
    $descricao_site = $conf->retornaDescricaoSite();
  }  
  $palavras_chave = $conf->retornaPalavrasChave();

  //para utilizar ajax  
  require("sajax/sajax.php");
  include "sajax/ajax_funcoes.php";
  
  if ($secao != '0')        {    $cd_menu = $secao;      } else {    $cd_menu = '0';    }
  if ($subsecao != '0')     {    $cd_menu = $subsecao;   };
  if ($item != '0')         {    $cd_menu = $item;       };

  $javascript = '';
  switch ($cd_menu) {
    case  "0":       $javascript = "js/funcoes_capa.js";                        break;    
    case "41":       $javascript = "js/funcoes_cadastro_objeto_aprendizagem.js";break;    
/*    default:        $javascript = "js/funcoes.js";                              break;  */
  }  

  $link_atual = "http://".$_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'];

  //inclusao de arquivo para montagem do cabecalho
  include "includes/cabecalho.php"; 
  //criacao do cabecalho
  $cabecalho= new Cabecalho($nm_site, $secao, $subsecao, $item, $atual);

  $cabecalho->setLink($link_atual);
  $cabecalho->setDescricao($descricao_site);
  $cabecalho->setPalavrasChave($palavras_chave);
  //inclusao do arquivo de css
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/divisoes.css");
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/fontes.css");
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/tabelas.css");
//  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/listas.css");

  if ($javascript != '') {
    //inclusao do arquivo javascript
    $cabecalho->insereArquivoJavaScript($javascript);
  }
  //ordem de impressao do cabecalho 
  $cabecalho->imprimeCabecalhoHTML($no_index, $nm_site);
  
?>
  <body class="geral">
    <p class="fontConteudoAlerta">Versão de Testes</p>
    <div class="divLogo">
<?php
  $conf->retornaLogoSite();
?>  
    </div>
    
    <div  class="divTopo">
      <div  class="divConteudoTopo">
        <div  class="divConteudoEsquerdaTopo">
<?php
  $menu->retornaMenuSuperior($secao, $subsecao, $item, 'SE');
?>    
        </div>     
        <div  class="divConteudoDireitaTopo">
<?php
  if ($login->estaLogado()) {
    $login->retornaUsuarioLogado();
  } else {
    $menu->retornaMenuSuperior($secao, $subsecao, $item, 'SD');
  }
?>    
        </div>     
      </div>
    </div>
    
    <div class="divAreaBanner">
      <div class="divBanner">
<?php      
     echo "   <img src=\"".$_SESSION['life_link_completo']."fotos/banners/banner.png\" alt=\"banner.png, 98kB\" title=\"banner\" height=\"130\" width=\"980\">\n";
?>           
      </div>
    </div>
    
<?php
  if ($login->estaLogado()) {
    echo "    <div class=\"clear\"></div>\n";
    echo "    <div class=\"divMenuAdministrativo\">\n";
    $menu->retornaMenuAdministrativo($secao, $subsecao, $item);
    echo "    </div>\n";                                 
  }
?>
    <div class="clear"></div>
    
    <div class="divConteudo">
<?php
  if ($pagina == 'erro') {
    require $pagina.'.php';
  } else {
    require_once 'conteudos/conteudo.php';                                      $con= new Conteudo();
    $con->controlaExibicaoConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual);
  }
?>    
    </div>
    
    <div class="clear">&nbsp;</div>

    <div class="divAreaRodape">
      <div class="divRodape">
Patrocinadores:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Apoiadores:      
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
      </div>
    </div>
  

  </body>
</html>  