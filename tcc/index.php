<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>TCC Apresentação</title>
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
                var section = new Array('.text', 'p'); 
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
                <button type="button" class="btn btn-default btn-xs" aria-label="Pular ao conteúdo princial" title="Pular ao conteúdo principal">
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
            <h1>Introdução</h1>
            <h3>Apresentação</h3>
            <div class="text">
                <p>Esta página apresenta o Trabalho de Conclusão de Curso (TCC) de Bruno Roeder e seu orientador, o professor Dalton Reis. Primeiramente quero agradecer a você que está lendo esta página e contribuindo para a realização e conclusão de menu tcc. Quero agradecer ao professor Rodrigo M. França pela oportunidade e, em vários projetos, por sempre estar buscando algo a mais para a educação especial e inclusão.</p>
            </div>
            <h3>Sobre o TCC</h3>
            <div class="text">
                <p>O TCC será apresentado no primeiro semestre de 2017 e tem como título <b>"ADAPTAÇÃO DO PORTAL INVERSOS ÀS DIRETRIZES DE ACESSIBILIDADE
                    WCAG 2.0"</b>. Este TCC tem como objetivo adaptar um portal, o InVersos; que é um portal desenvolvido pelo grupo TecEdu-FURB, que visa tecnologias digitais na educação, cujo objetivo será agregar materiais digitais para os cursos de licenciatura, às diretrizes de acessibilidade para websites chamdas WCAG 2.0. </p>
                <p>Estas diretrizes WCAG, especificam quais elementos fazem parte de um website acessivel e define normas e regras para a acessibilidade digital. Durante a produção deste TCC, o portal InVersos recebeu as adaptações indicadas nestas regras e passou em testes de acessibilidade automáticos, queremos agora validar com usuários reais.</p>
            </div>

            <h3>Motivação</h3>
            <div class="text">
<p>A motivação principal foi tornar o portal InVersos acessivel a qualquer público que o deseja acessar, além de, um TCC como espécie de um manual para consulta de futuras adaptações ou criações de portais acessiveis.</p>
            </div>

            <h1>Sites Acessiveis</h1>
            <h3>O que são?</h3>
            <div class="text">
                <p>É um website que permite a qualquer pessoa navegar entender, perceber e interagir com o conteúdo de forma eficaz ao utilizá-lo. Um website acessível beneficia pessoas com qualquer tipo de deficiência, bem como pessoas idosas ou com conexões lentas, por exemplo.</p>
                <p>Desde 2004, um Decreto Federal (nº 5.296) torna obrigatório que todos os portais e sites dos órgãos da administração pública atendam aos padrões de acessibilidade digital. Depois disso, vários decretos, portarias e até uma lei – a Lei de Acesso à Informação Nº 12.527, de novembro de 2011 - trataram do tema, abrangendo todos os sites e não apenas os governamentais. No dia 06 de julho de 2015 foi sancionada a Lei Brasileira de Inclusão da Pessoa com Deficiência (Lei 13.146) que torna obrigatória a acessibilidade nos sítios da internet mantidos por empresas com sede ou representação comercial no País ou por órgãos de governo. Mas, mesmo existindo a legislação, isso não foi suficiente para que de fato a situação tenha sido resolvida até hoje. </p>
                <p>Fonte: <a href="http://www.acessoparatodos.com.br/acessibilidadeweb.php" target="_blank">Acesso para todos</a></p>
            </div>


            <h3>Desenvolvimento</h3>
            <div class="text">
            <p>Por desconhecer ou por desacreditar a importância da acessibilidade, muito do desenvolvimento de websites e aplicações Web são feitos de maneira errônea, sem utilizar uma recomendação padrão (HENRY, 2005). Desenvolver conteúdo Web com acessibilidade é um dever de todos os desenvolvedores e um direito de todo cidadão, portador ou não de necessidades especiais (CUSIN, 2009). A acessibilidade é passível de obrigação legislativa (KRUG, 2008) e possui custo mínimo (DIAS, 2007).</p>
            </div>



<h4>Bibliografia</h4>
<ol>
<li><cite>HENRY, Shawn Lawton; <b>Education and Outreach Working Group (EOWG)</b>. Introduction to Web Accessibility. World Wide Web Consortium/Web Accessibility Initiative (W3C/WAI). 2005. Disponível em: &lt;http://www.w3.org/WAI/intro/accessibility.php&gt;. Acesso em: 24 ago. 2016.</cite></li>
<li><cite>CUSIN, César Augusto; VIDOTTI, Silvana Aparecida Borsetti Gregório. <b>Inclusão digital via acessibilidade web</b>, Rio de Janeiro, v. 5, n. 1, p. 45-65, mar. 2009. Disponível em: &lt;http://liinc.revista.ibict.br/index.php/liinc/article/download/297/195&gt;. Acesso em: 22 ago. 2016.</cite></li>
<li><cite>KRUG, Steve. <b>Não me faça pensar</b> – Uma abordagem de bom senso à usabilidade na web. Tradução Acauan Pereira Fernandes. 2. ed. Rio de Janeiro: Alta Books, 2008. 201 p.</cite></li>
<li><cite>DIAS, C. <b>Usabilidade na Web</b>: criando portais mais acessíveis, 2a edição, Rio de Janeiro, Alta Books, 2007.</cite></li>
</ol>
        </div>
    </body>
</html> 


