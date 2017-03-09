<?php
  session_start();
  include_once "includes/conectar.php";
  require_once 'includes/configuracoes.php';
$conf= new Configuracao();
  $_SESSION['life_tipo_equipamento_acesso'] = 'pc';
  $_SESSION['life_link_completo'] = $conf->retornaLinkCompletoAplicacao();
  $nm_site = $conf->retornaNomeSite();
  require_once 'menu/menu.php';
$menu = new Menu();
  require_once 'login/login.php';
$login= new Login();

  require_once 'includes/interpretador_link.php';
$int_lin = new InterpretadorLink();
  $retorno = $int_lin->interpretar();
  $atual = $retorno['atual'];
  $pagina = $retorno['pagina'];
  $secao = $retorno['secao'];
  $subsecao = $retorno['subsecao'];
  $item = $retorno['item'];

if ((isset($atual[0])) && ($atual[0] == 'entre')) {
    if ($login->executarProcedimentosLogin()) {
        $atual = array();
        $atual[0] = 'home';
        $pagina = 'home';
        $secao = '0';
        $subsecao = '0';
        $item = '0';
    }
} elseif ((isset($atual[0])) && (isset($atual[1])) && ($atual[0] == 'cadastre-se') && ($atual[1] == 'salvar')) {
    //cadastro
    echo "<br />\n";
    require_once 'conteudos/pessoas.php';
    $obj = new Pessoa();
    $obj->salvarCadastroProprioDireto();
}
  $dados_menu = $menu->retornaDadosMenu($secao, $subsecao, $item);
  $no_index = $dados_menu['eh_indexar'];

if (!isset($_SESSION['life_primeiro'])) {
    require_once 'includes/contador.php';
    $contador = new Contador();
    //somente na primeira vez que a pagina é carregada para evitar que fique tentando contar a cada reload
    $contador->contabilizaSecao();
    $_SESSION['life_primeiro']= 1;
}

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
  
if ($secao != '0') {
    $cd_menu = $secao;
} else {
    $cd_menu = '0';
}
if ($subsecao != '0') {
    $cd_menu = $subsecao;
};
if ($item != '0') {
    $cd_menu = $item;
};

  $javascript = 'js/funcoes.js';

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
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/style-preto-branco.css");

if ($javascript != '') {
    //inclusao do arquivo javascript
    $cabecalho->insereArquivoJavaScript($javascript);
}

if (!$login->estaLogado()) {
    $cabecalho->insereArquivoJavaScript("js/login_face.js");
}

// Bruno Roeder acessibilidade: inclusao de js de acessibilidade para troca de contraste
$cabecalho->insereArquivoJavaScript('js/styleswitcher.js');

  //ordem de impressao do cabecalho
  $cabecalho->imprimeCabecalhoHTML($no_index, $nm_site);



  require_once "html_index.php";
$html = new HTML();
$html->retornaControleConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual, $cd_menu);

mysql_close();
