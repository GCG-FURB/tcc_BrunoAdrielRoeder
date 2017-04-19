<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="windows-1252">
    <title>TCC Apresenta&ccedil;&atilde;o</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap-accessibility-plugin-master/plugins/css/bootstrap-accessibility.css">
    <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap-accessibility-plugin-master/plugins/js/bootstrap-accessibility.min.js"></script>
</head>
<body>
    <script type="text/javascript">
        $(document).ready(function(){
            var section = new Array('.text', 'p', 'cite');
            section = section.join(',');

                // Reset Font Size
                var originalFontSize = $(section).css('font-size');
                $(".resetFont").click(function(){
                    $(section).css('font-size', originalFontSize);
                });

                // Increase Font Size
                $(".increaseFont").click(function(){
                    var currentFontSize = $(section).css('font-size');
                    var currentFontSizeNum = parseFloat(currentFontSize, 10);
                    var newFontSize = currentFontSizeNum*1.2;
                    $(section).css('font-size', newFontSize);
                    return false;
                });

                // Decrease Font Size
                $(".decreaseFont").click(function(){
                    var currentFontSize = $(section).css('font-size');
                    var currentFontSizeNum = parseFloat(currentFontSize, 10);
                    var newFontSize = currentFontSizeNum*0.8;
                    $(section).css('font-size', newFontSize);
                    return false;
                });
            });
</script>
<div id="menu">
    <a href="#content">
        <button type="button" class="btn btn-default btn-xs" aria-label="Pular ao conte&uacute;do princial" title="Pular ao conte&uacute;do principal">
            <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
        </button>
    </a>
    <button type="button" class="btn btn-default btn-xs increaseFont" aria-label="Aumentar tamanho do fonte" title="Aumentar tamanho do fonte">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </button>
    <button type="button" class="btn btn-default btn-xs decreaseFont" aria-label="Diminuir tamanho do fonte" title="Diminuir tamanho do fonte">
        <span class="glyphicon glyphicon glyphicon-minus" aria-hidden="true"></span>
    </button>

    <button type="button" class="btn btn-default btn-xs resetFont" aria-label="Diminuir tamanho do fonte" title="Diminuir tamanho do fonte">
        <span class="glyphicon glyphicon glyphicon-font" aria-hidden="true"></span>
    </button>
</div>
<div class="container" id="content">
    <h1>Introdu&ccedil;&atilde;o</h1>
    <h2>Apresenta&ccedil;&atilde;o</h2>
    <div class="text">
        <p>Esta p&aacute;gina apresenta o Trabalho de Conclus&atilde;o de Curso (TCC) de Bruno Roeder e seu orientador, o professor Dalton Reis. Primeiramente quero agradecer a voc&ecirc; que est&aacute; lendo esta p&aacute;gina e contribuindo para a realiza&ccedil;&atilde;o e conclus&atilde;o de menu TCC e agradecer ao professor Maur&iacute;cio Capobianco Lopes pela oportunidade.</p>
    </div>
    <h2>Sobre o TCC</h2>
    <div class="text">
        <p>O TCC ser&aacute; apresentado no primeiro semestre de 2017 e tem como t&iacute;tulo <strong>"ADAPTA&Ccedil;&Atilde;O DO PORTAL INVERSOS &Agrave;S DIRETRIZES DE ACESSIBILIDADE
            WCAG 2.0"</strong>. Este TCC tem como objetivo adaptar um portal, o InVersos; que &eacute; um portal desenvolvido pelo grupo TecEdu-FURB, que visa tecnologias digitais na educa&ccedil;&atilde;o, cujo objetivo ser&aacute; agregar materiais digitais para os cursos de licenciatura, &agrave;s diretrizes de acessibilidade para websites chamadas Diretrizes de Acessibilidade para o Conte&uacute;do da Web (WCAG 2.0). </p>
            <p>Estas diretrizes WCAG, especificam quais elementos fazem parte de um website acessivel e definem normas e regras para a acessibilidade digital. Durante a produ&ccedil;&atilde;o deste TCC, o portal InVersos recebeu as adapta&ccedil;&otilde;es indicadas nestas regras e passou em testes de acessibilidade autom&aacute;ticos. Agora queremos validar com usu&aacute;rios reais.</p>
        </div>

        <h2>Motiva&ccedil;&atilde;o</h2>
        <div class="text">
            <p>A motiva&ccedil;&atilde;o principal foi tornar o portal InVersos acessivel a qualquer p&uacute;blico que o deseja acessar, al&eacute;m de, um TCC como esp&eacute;cie de um manual para consulta de futuras adapta&ccedil;&otilde;es ou cria&ccedil;&otilde;es de portais acessiveis.</p>
        </div>

        <h1>Sites Acessiveis</h1>
        <h2>O que s&atilde;o?</h2>
        <div class="text">
            <p>&Eacute; um website que permite a qualquer pessoa navegar entender, perceber e interagir com o conte&uacute;do de forma eficaz ao utiliz&aacute;-lo. Um website acess&iacute;vel beneficia pessoas com qualquer tipo de defici&ecirc;ncia, bem como pessoas idosas ou com conex&otilde;es lentas, por exemplo (Acesso para Todos, 2017).</p>
            <p>Desde 2004, um Decreto Federal (n&ordm; 5.296) torna obrigat&oacute;rio que todos os portais e sites dos &oacute;rg&atilde;os da administra&ccedil;&atilde;o p&uacute;blica atendam aos padr&otilde;es de acessibilidade digital. Depois disso, v&aacute;rios decretos, portarias e at&eacute; uma lei, a Lei de Acesso &agrave; Informa&ccedil;&atilde;o N&ordm; 12.527, de novembro de 2011, trataram do tema, abrangendo todos os sites e n&atilde;o apenas os governamentais. No dia 06 de julho de 2015 foi sancionada a Lei Brasileira de Inclus&atilde;o da Pessoa com Defici&ecirc;ncia (Lei 13.146) que torna obrigat&oacute;ria a acessibilidade nos s&iacute;tios da internet mantidos por empresas com sede ou representa&ccedil;&atilde;o comercial no Pa&iacute;s ou por &oacute;rg&atilde;os de governo. Mas, mesmo existindo a legisla&ccedil;&atilde;o, isso n&atilde;o foi suficiente para que de fato a situa&ccedil;&atilde;o tenha sido resolvida at&eacute; hoje (Acesso para Todos, 2017). </p>
        </div>


        <h2>Desenvolvimento</h2>
        <div class="text">
        <p>Por desconhecer ou por desacreditar a import&acirc;ncia da acessibilidade, muito do desenvolvimento de websites e aplica&ccedil;&otilde;es Web s&atilde;o feitos de maneira err&ocirc;nea, sem utilizar uma recomenda&ccedil;&atilde;o padr&atilde;o (HENRY, 2005). Desenvolver conte&uacute;do Web com acessibilidade &eacute; um dever de todos os desenvolvedores e um direito de todo cidad&atilde;o, portador ou n&atilde;o de necessidades especiais (CUSIN, 2009). A acessibilidade &eacute; pass&iacute;vel de obriga&ccedil;&atilde;o legislativa (KRUG, 2008) e possui custo m&iacute;nimo (DIAS, 2007). Al&eacute;m de um requerir um conhecimento m&iacute;nimo para ser implementada a n&iacute;vel de desenvolvimento.</p>
        </div>




        <h2>Como testar a acessibilidade?</h2>
        <div class="text">
        <p>Existem diferentes maneiras de testar a acessibilidade de um Website: testes autom&aacute;ticos, semi-autom&aacute;ticos, manuais com especialistas, usu&aacute;rios (SOARES, 2006).</p>
        <p>O primeiro passo &eacute; testar as p&aacute;ginas que comp&otilde;em o site nos avaliadores de acessibilidade que s&atilde;o programas que detectam o c&oacute;digo de programa&ccedil;&atilde;o de uma p&aacute;gina e fazem uma an&aacute;lise do seu conte&uacute;do, baseada nas regras WCAG. Dentro de um conjunto de regras, avaliam o n&iacute;vel de acessibilidade das p&aacute;ginas, produzindo automaticamente relat&oacute;rios detalhados (SOARES, 2006).</p>
        <p>Teste com especialistas simulando usu&aacute;rios deficientes, tamb&eacute;m podem ser efetuados. N&atilde;o &eacute; tarefa muito f&aacute;cil simular uma pessoa com defici&ecirc;ncia, mas podemos obter um bom resultado se desligarmos alguns perif&eacute;ricos como, por exemplo, o mouse e realizarmos algumas tarefas, colocando-se no lugar de usu&aacute;rios com defici&ecirc;ncia e testar se a navega&ccedil;&atilde;o do site e as suas principais fun&ccedil;&otilde;es/tarefas est&atilde;o verdadeiramente acess&iacute;veis (SOARES, 2006).</p>
        <p>De todas as maneiras, o teste com usu&aacute;rio reais traz mais confiabilidade &agrave; pesquisa, visto a uni&atilde;o entre usabilidade e acessbilidade que este tipo de teste pode trazer.</p>
        </div>

        <h2>Pesquisa</h2>
        <div class="text">
        <p>Para testarmos a acessibilidade do portal InVersos, solicitamos que seja acessado o formul&aacute;rio no link <a href="https://goo.gl/forms/nbmgb1lRLIVaVypp1" target="_blank">https://goo.gl/forms/nbmgb1lRLIVaVypp1</a>, para responder as perguntas e efetuar os passos do teste sugerido, e acesse o portal InVersos adaptado &agrave;s diretrizes WCAG em <a href="http://inversos.brunoroeder.com.br/" target="_blank">http://inversos.brunoroeder.com.br/</a>.</p>
        </div>



        <h3>Bibliografia</h3>
        <ol>
            <li><cite>Acesso para Todos. <strong>Acesso para Todos</strong>: Acessibilidade na Web. Dispon&iacute;vel em: &lt;http://www.acessoparatodos.com.br/acessibilidadeweb.php&gt;. Acesso em: 8 abr. 2017. </cite></li>
            <li><cite>CUSIN, C&eacute;sar Augusto; VIDOTTI, Silvana Aparecida Borsetti Greg&oacute;rio. <strong>Inclus&atilde;o digital via acessibilidade web</strong>, Rio de Janeiro, v. 5, n. 1, p. 45-65, mar. 2009. Dispon&iacute;vel em: &lt;http://liinc.revista.ibict.br/index.php/liinc/article/download/297/195&gt;. Acesso em: 22 ago. 2016.</cite></li>
            <li><cite>DIAS, C. <strong>Usabilidade na Web</strong>: criando portais mais acess&iacute;veis, 2a edi&ccedil;&atilde;o, Rio de Janeiro, Alta Books, 2007.</cite></li>
            <li><cite>HENRY, Shawn Lawton; <strong>Education and Outreach Working Group (EOWG)</strong>. Introduction to Web Accessibility. World Wide Web Consortium/Web Accessibility Initiative (W3C/WAI). 2005. Dispon&iacute;vel em: &lt;http://www.w3.org/WAI/intro/accessibility.php&gt;. Acesso em: 24 ago. 2016.</cite></li>
            <li><cite>KRUG, Steve. <strong>N&atilde;o me fa&ccedil;a pensar</strong> – Uma abordagem de bom senso &agrave; usabilidade na web. Tradu&ccedil;&atilde;o Acauan Pereira Fernandes. 2. ed. Rio de Janeiro: Alta Books, 2008. 201 p.</cite></li>
            <li><cite>SOARES, Horacio. <strong>Como testar a acessibilidade em Websites?</strong> (parte 1). Interativa. Artigos e Negocios. 2006. Disponivel em: &lt;http://internativa.com.br/artigo_acessibilidade_03_06.html&gt;. Acesso em 31 ago. 2016.</cite></li>
        </ol>
    </div>
</body>
</html>


