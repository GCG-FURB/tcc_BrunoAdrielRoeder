<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="windows-1252">
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
    <h2>Apresentação</h2>
    <div class="text">
        <p>Esta página apresenta o Trabalho de Conclusão de Curso (TCC) de Bruno Roeder e seu orientador, o professor Dalton Reis. Primeiramente quero agradecer a você que está lendo esta página e contribuindo para a realização e conclusão de menu tcc. Quero agradecer ao professor Rodrigo M. França pela oportunidade e, em vários projetos, por sempre estar buscando algo a mais para a educação especial e inclusão.</p>
    </div>
    <h2>Sobre o TCC</h2>
    <div class="text">
        <p>O TCC será apresentado no primeiro semestre de 2017 e tem como título <strong>"ADAPTAÇÃO DO PORTAL INVERSOS ÀS DIRETRIZES DE ACESSIBILIDADE
            WCAG 2.0"</strong>. Este TCC tem como objetivo adaptar um portal, o InVersos; que é um portal desenvolvido pelo grupo TecEdu-FURB, que visa tecnologias digitais na educação, cujo objetivo será agregar materiais digitais para os cursos de licenciatura, às diretrizes de acessibilidade para websites chamdas WCAG 2.0. </p>
            <p>Estas diretrizes WCAG, especificam quais elementos fazem parte de um website acessivel e define normas e regras para a acessibilidade digital. Durante a produção deste TCC, o portal InVersos recebeu as adaptações indicadas nestas regras e passou em testes de acessibilidade automáticos, queremos agora validar com usuários reais.</p>
        </div>

        <h2>Motivação</h2>
        <div class="text">
            <p>A motivação principal foi tornar o portal InVersos acessivel a qualquer público que o deseja acessar, além de, um TCC como espécie de um manual para consulta de futuras adaptações ou criações de portais acessiveis.</p>
        </div>

        <h1>Sites Acessiveis</h1>
        <h2>O que são?</h2>
        <div class="text">
            <p>É um website que permite a qualquer pessoa navegar entender, perceber e interagir com o conteúdo de forma eficaz ao utilizá-lo. Um website acessível beneficia pessoas com qualquer tipo de deficiência, bem como pessoas idosas ou com conexões lentas, por exemplo (Acesso para Todos, 2017).</p>
            <p>Desde 2004, um Decreto Federal (nº 5.296) torna obrigatório que todos os portais e sites dos órgãos da administração pública atendam aos padrões de acessibilidade digital. Depois disso, vários decretos, portarias e até uma lei – a Lei de Acesso à Informação Nº 12.527, de novembro de 2011 - trataram do tema, abrangendo todos os sites e não apenas os governamentais. No dia 06 de julho de 2015 foi sancionada a Lei Brasileira de Inclusão da Pessoa com Deficiência (Lei 13.146) que torna obrigatória a acessibilidade nos sítios da internet mantidos por empresas com sede ou representação comercial no País ou por órgãos de governo. Mas, mesmo existindo a legislação, isso não foi suficiente para que de fato a situação tenha sido resolvida até hoje (Acesso para Todos, 2017). </p>
        </div>


        <h2>Desenvolvimento</h2>
        <div class="text">
        <p>Por desconhecer ou por desacreditar a importância da acessibilidade, muito do desenvolvimento de websites e aplicações Web são feitos de maneira errônea, sem utilizar uma recomendação padrão (HENRY, 2005). Desenvolver conteúdo Web com acessibilidade é um dever de todos os desenvolvedores e um direito de todo cidadão, portador ou não de necessidades especiais (CUSIN, 2009). A acessibilidade é passível de obrigação legislativa (KRUG, 2008) e possui custo mínimo (DIAS, 2007). Além de um requerir um conhecimento mínimo para ser implementada a nível de desenvolvimento.</p>
        </div>




        <h2>Como testar a acessibilidade?</h2>
        <div class="text">
        <p>Existem diferentes maneiras de testar a acessibilidade de um Website: testes automáticos, semi-automáticos, manuais com especialistas, usuários (SOARES, 2006).</p>
        <p>O primeiro passo é testar as páginas que compõem o site nos avaliadores de acessibilidade que são programas que detectam o código de programação de uma página e fazem uma análise do seu conteúdo, baseada nas regras WCAG. Dentro de um conjunto de regras, avaliam o nível de acessibilidade das páginas, produzindo automaticamente relatórios detalhados (SOARES, 2006).</p>
        <p>Teste com especialistas simulando usuários deficientes, também podem ser efetuados. Não é tarefa muito fácil simular uma pessoa com deficiência, mas podemos obter um bom resultado se desligarmos alguns periféricos como, por exemplo, o mouse e realizarmos algumas tarefas, colocando-se no lugar de usuários com deficiência e testar se a navegação do site e as suas principais funções/tarefas estão verdadeiramente acessíveis (SOARES, 2006).</p>
        <p>De todas as maneiras, o teste com usuário reais traz mais confiabilidade à pesquisa, visto a união entre usabilidade e acessbilidade que este tipo de teste pode trazer.</p>
        </div>

        <h2>Pesquisa</h2>
        <div class="text">
        <p>Para testarmos a acessibilidade do portal InVersos, solicitamos que seja acessado o formulário no link <a href="https://goo.gl/forms/nbmgb1lRLIVaVypp1" target="_blank">https://goo.gl/forms/nbmgb1lRLIVaVypp1</a>, para responder as perguntas e efetuar os passos do teste sugerido, e acesse o portal InVersos adaptado às diretrizes WCAG em <a href="http://inversos.brunoroeder.com.br/" target="_blank">http://inversos.brunoroeder.com.br/</a>.</p>
        </div>



        <h3>Bibliografia</h3>
        <ol>
            <li><cite>Acesso para Todos. <strong>Acesso para Todos</strong>: Acessibilidade na Web. Disponível em: &lt;http://www.acessoparatodos.com.br/acessibilidadeweb.php&gt;. Acesso em: 8 abr. 2017. </cite></li>
            <li><cite>CUSIN, César Augusto; VIDOTTI, Silvana Aparecida Borsetti Gregório. <strong>Inclusão digital via acessibilidade web</strong>, Rio de Janeiro, v. 5, n. 1, p. 45-65, mar. 2009. Disponível em: &lt;http://liinc.revista.ibict.br/index.php/liinc/article/download/297/195&gt;. Acesso em: 22 ago. 2016.</cite></li>
            <li><cite>DIAS, C. <strong>Usabilidade na Web</strong>: criando portais mais acessíveis, 2a edição, Rio de Janeiro, Alta Books, 2007.</cite></li>
            <li><cite>HENRY, Shawn Lawton; <strong>Education and Outreach Working Group (EOWG)</strong>. Introduction to Web Accessibility. World Wide Web Consortium/Web Accessibility Initiative (W3C/WAI). 2005. Disponível em: &lt;http://www.w3.org/WAI/intro/accessibility.php&gt;. Acesso em: 24 ago. 2016.</cite></li>
            <li><cite>KRUG, Steve. <strong>Não me faça pensar</strong> – Uma abordagem de bom senso à usabilidade na web. Tradução Acauan Pereira Fernandes. 2. ed. Rio de Janeiro: Alta Books, 2008. 201 p.</cite></li>
            <li><cite>SOARES, Horacio. <strong>Como testar a acessibilidade em Websites?</strong> (parte 1). Interativa. Artigos e Negocios. 2006. Disponivel em: &lt;http://internativa.com.br/artigo_acessibilidade_03_06.html&gt;. Acesso em 31 ago. 2016.</cite></li>
        </ol>
    </div>
</body>
</html> 


