<?php
class Blog
{
    
    public function __construct()
    {
    }
    
    public function controleExibicao($secao, $subsecao, $item)
    {
        if (isset($_GET['acao'])) {
            $acao = addslashes($_GET['acao']);
        } else {
            $acao = '';
        }
        if (isset($_GET['at'])) {
            $ativas = addslashes($_GET['at']);
        } else {
            $ativas = 1;
        }
        if (isset($_GET['cd'])) {
            $codigo = addslashes($_GET['cd']);
        } else {
            $codigo = '';
        }

        switch ($acao) {
            case "":
                $this->listarAcoes($secao, $subsecao, $item, $ativas);
                $this->listarItens($secao, $subsecao, $item, $ativas);
                break;

            case "cadastrar":
                echo "  <h2>Cadastro de blogs de notícias</h2>\n";
                $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
                $this->montarFormularioCadastro($link);
                break;

            case "editar":
                echo "  <h2>Edição de blog de notítica</h2>\n";
                $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
                $this->montarFormularioEdicao($link, $codigo);
                break;
          
            case "salvar":
                if (isset($_SESSION['life_edicao'])) {
                    $this->salvarCadastroAlteracao();
                    unset($_SESSION['life_edicao']);
                }
                $this->listarAcoes($secao, $subsecao, $item, $ativas);
                $this->listarItens($secao, $subsecao, $item, $ativas);
                break;
        
            case "alt_status":
                $this->alterarStatusItem($codigo);
                $this->listarAcoes($secao, $subsecao, $item, $ativas);
                $this->listarItens($secao, $subsecao, $item, $ativas);
                break;
        }
    }

    private function listarAcoes($secao, $subsecao, $item, $ativas)
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();

        $opcoes_1= array();
        $opcao= array();
        $opcao['indice']= "1";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";
        if ($ativas == '1') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }                 $opcao['descricao']= "Ativos";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "2";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";
        if ($ativas == '0') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }                 $opcao['descricao']= "Inativos";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "3";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";
        if ($ativas == '2') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }                 $opcao['descricao']= "Ativos/Inativos";
        $opcoes_1[]= $opcao;
        foreach ($opcoes_1 as $op) {
            $nome = 'comandos_filtros_1_'.$op['indice'];
            $util->campoHidden($nome, $op['link']);
        }
      
        include 'js/js_navegacao.js';
        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo blog de notícia\" title=\"Novo blog de notícia\" border=\"0\"></a> \n";
        echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
        foreach ($opcoes_1 as $op) {
            echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
        }
        echo "  </select>\n";
        echo "</p>\n";
    }
    
    private function listarItens($secao, $subsecao, $item, $ativas)
    {
        $itens = $this->selectBlogs($ativas);

        $mensagem = "Blogs de notícias ";

        echo "<h2>".$mensagem."</h2>\n";
        echo "  <table class=\"tabConteudo\">\n";
        $style = "linhaOn";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">Blog de notícia</td>\n";
        echo "      <td class=\"celConteudo\">Ações</td>\n";
        echo "    </tr>\n";
        $hoje = date('Y-m-d');
        foreach ($itens as $it) {
            $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
            echo "    <tr class=\"".$style."\">\n";
            echo "      <td class=\"celConteudo\">".$it['nm_blog']."</td>\n";
            echo "      <td class=\"celConteudo\">\n";
            echo "        <a href=\"#\" class=\"dcontexto\">\n";
            echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
            echo "          <span class=\"fontdDetalhar\">\n";
            echo $this->detalharBlog($it['cd_blog']);
            echo "        </span></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_blog']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_blog']."&acao=alt_status\">";
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

    
    public function detalharBlog($cd_blog)
    {
        require_once 'usuarios/usuarios.php';
        $usu = new Usuario();
        require_once 'includes/data_hora.php';
        $dh = new DataHora();
      
        $dados = $this->selectDadosBlog($cd_blog);
      
        $retorno = "";
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
        if ($dados['cd_usuario_ultima_atualizacao'] != '') {
            $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
            $retorno.= "Última atualização por ".$dados_usuario['nm_usuario']."<br />\n";
            $retorno.= "Data do última atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
        }
        return $retorno;
    }

     
    public function montarFormularioCadastro($link)
    {
        $cd_blog = "";
        $nm_blog = "";
        $ds_link = "";
        $nr_noticias = "1";
        $eh_ativo = "1";
      
        $_SESSION['life_edicao']= 1;
        $this->imprimeFormularioCadastro($link, $cd_blog, $nm_blog, $ds_link, $nr_noticias, $eh_ativo);
    }

    public function montarFormularioEdicao($link, $cd_blog)
    {
        $dados= $this->selectDadosBlog($cd_blog);
        $nm_blog = $dados['nm_blog'];
        $ds_link = $dados['ds_link'];
        $nr_noticias = $dados['nr_noticias'];
        $eh_ativo = $dados['eh_ativo'];

        $_SESSION['life_edicao']= 1;
        $this->imprimeFormularioCadastro($link, $cd_blog, $nm_blog, $ds_link, $nr_noticias, $eh_ativo);
    }
    
    private function imprimeFormularioCadastro($link, $cd_blog, $nm_blog, $ds_link, $nr_noticias, $eh_ativo)
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();

        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
        echo "</p>\n";
      
        include "js/js_cadastro_blog.js";
        echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');
        $util->campoHidden('cd_blog', $cd_blog);
      
        $util->linhaUmCampoText(1, 'Nome ', 'nm_blog', 100, 100, $nm_blog);
        $util->linhaUmCampoText(1, 'Link RSS ', 'ds_link', 250, 100, $ds_link);

        $opcoes= array();
        for ($i=1; $i<=15; $i++) {
            $opcao= array();
            $opcao[]= $i;
            $opcao[]= $i;
            $opcoes[]= $opcao;
        }
        $util->linhaSeletor('Número de itens a serem exibidos ', 'nr_noticias', $nr_noticias, $opcoes, '100');

        $opcoes= array();
        $opcao= array();
        $opcao[]= '1';
        $opcao[]= 'Sim';
        $opcoes[]= $opcao;
        $opcao= array();
        $opcao[]= '0';
        $opcao[]= 'Não';
        $opcoes[]= $opcao;
        $util->linhaSeletor('É ativo ', 'eh_ativo', $eh_ativo, $opcoes, '100');

        $util->linhaBotao('Salvar', "valida(cadastro);");
        echo "    </table>\n";
        echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
        echo "  </form>\n";
        $util->posicionarCursor('cadastro', 'nm_blog');
    }
    
    public function salvarCadastroAlteracao()
    {
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        require_once 'conteudos/fotos.php';
        $foto = new Fotos();

        $cd_blog = addslashes($_POST['cd_blog']);
        $nm_blog = $util->limparVariavel($_POST['nm_blog']);
        $ds_link = addslashes($_POST['ds_link']);
        $nr_noticias = addslashes($_POST['nr_noticias']);
        $eh_ativo = addslashes($_POST['eh_ativo']);

        if ($cd_blog > 0) {
            if ($this->alteraBlog($cd_blog, $nm_blog, $ds_link, $nr_noticias, $eh_ativo)) {
                echo "<p class=\"fontConteudoSucesso\">Blog de notícias editado com sucesso!</p>\n";
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas na edição do blog de notícias, ou nenhuma informação alterada!</p>\n";
            }
        } else {
            if ($this->insereBlog($nm_blog, $ds_link, $nr_noticias, $eh_ativo)) {
                echo "<p class=\"fontConteudoSucesso\">Blog de notícias cadastrado com sucesso!</p>\n";
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do blog de notícias!</p>\n";
            }
        }
    }
    
    public function alterarStatusItem($cd_blog)
    {
        $dados= $this->selectDadosBlog($cd_blog);
        $nm_blog = $dados['nm_blog'];
        $ds_link = $dados['ds_link'];
        $nr_noticias = $dados['nr_noticias'];
        $eh_ativo = $dados['eh_ativo'];
        if ($eh_ativo == 1) {
            $eh_ativo= 0;
        } else {
            $eh_ativo= 1;
        }
      
        if ($this->alteraBlog($cd_blog, $nm_blog, $ds_link, $nr_noticias, $eh_ativo)) {
            echo "<p class=\"fontConteudoSucesso\">Status do blog de notícias alterado com sucesso!</p>\n";
        } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do blog de notícias!</p>\n";
        }
    }

//**********************************EXIBIÇÃO************************************
    public function exibeBlogCapa()
    {
        $itens = $this->selectBlogs('1');

        $blogs_listados = array();
        $i = 0;
        $aberto = 0;
        $posicao_noticia = '1';
        while ($i < count($itens)) {
            $nro_sorteado= rand(0, count($itens)-1);
            if (!in_array($nro_sorteado, $blogs_listados)) {
                $i+= 1;
                $blogs_listados[] = $nro_sorteado;
                $it = $itens[$nro_sorteado];

                if ($this->endereco_existe($it['ds_link'])) {
                    $feed = file_get_contents($it['ds_link']);
                    $rss = new SimpleXmlElement($feed);

                    if ($rss) {
                        $nr_noticias = $it['nr_noticias'];

                        $j = 0;
                        foreach ($rss->channel->item as $entrada) {
                            if ($j < $nr_noticias) {
                                $texto = $entrada->description;
                                $posicao = strpos($texto, '<div class="separator"');
                                if ($posicao > 0) {
                                    if ($posicao_noticia == '1') {
                                        $aberto += 1;
                                        echo "            <div class=\"divPrimeiraNoticiaMeioCapa\">\n";
                                        $posicao_noticia = '2';
                                        $largura = '180';
                                        $altura = '100';
                                    } elseif ($posicao_noticia == '2') {
                                        $aberto += 1;
                                        $aberto += 1;
                                        echo "            <div class=\"divSegundaNoticiaMeioCapa\">\n";
                                        echo "              <div class=\"divSegundaNoticiaMeioCapaMeiaEsquerda\">\n";
                                        $posicao_noticia = '3';
                                        $largura = '170';
                                        $altura = '60';
                                    } elseif ($posicao_noticia == '3') {
                                        $aberto += 1;
                                        echo "              <div class=\"divSegundaNoticiaMeioCapaMeiaDireita\">\n";
                                        $posicao_noticia = '4';
                                        $largura = '170';
                                        $altura = '60';
                                    } elseif ($posicao_noticia == '4') {
                                        $aberto += 1;
                                        echo "            <div class=\"divPrimeiraNoticiaMeioCapa\">\n";
                                        $posicao_noticia = '5';
                                        $largura = '';
                                        $altura = '';
                                    }
                                    $texto_novo = $texto;
                                    $posicao_inicio_imagem = strpos($texto_novo, '<img');
                                    $imagens = array();
                                    while ($posicao_inicio_imagem > 0) {
                                        $este_texto_novo = substr($texto_novo, 0, $posicao_inicio_imagem);

                                        $texto_ajustado = substr($texto_novo, ($posicao_inicio_imagem - (strlen($texto_novo))));
                                        $posicao_fim_imagem = strpos($texto_ajustado, '/>');
                                        $imagens[] = substr($texto_ajustado, 0, $posicao_fim_imagem+2);
                                        $este_texto_novo.= substr($texto_ajustado, (($posicao_fim_imagem+2) - (strlen($texto_ajustado))));
                                        $texto_novo = $este_texto_novo;
                                        $posicao_inicio_imagem = strpos($texto_novo, '<img');
                                    }

                                    $texto = $texto_novo;
                                    $posicao = strpos($texto, '<div class="separator"');
                                    $abreviado = substr($texto, 0, $posicao);

                                    echo "            <a href=\"".$entrada->link."\" target=\"_blank\" class=\"fontLink\">\n";
                                    echo "              <p class=\"fontTituloNoticiaMenor\" style=\"font-weight: bolder;\">".utf8_decode($entrada->title)."</p>\n";
                                    echo "            </a>\n";
                                    if (count($imagens) > 0) {
                                        $imagem = $imagens[rand(0, count($imagens)-1)];
                                        $nova_imagem = '';
                                        $posicao_i = strpos($imagem, 'width="');
                                        if ($posicao_i > 0) {
                                            $arquivo = substr($imagem, (($posicao_i+7) - (strlen($imagem))));
                                            $nova_imagem = substr($imagem, 0, ($posicao_i+7)).$largura;
                                            $posicao_f = strpos($arquivo, '"');
                                            $arquivo = substr($arquivo, (($posicao_f) - (strlen($arquivo))));
                                            $nova_imagem.= $arquivo;

                                            $posicao_i = strpos($nova_imagem, 'height="');
                                            if ($posicao_i > 0) {
                                                $arquivo = substr($nova_imagem, (($posicao_i+8) - (strlen($nova_imagem))));
                                                $outra_imagem = substr($nova_imagem, 0, $posicao_i);
                                                $posicao_f = strpos($arquivo, '"');
                                                $arquivo = substr($arquivo, (($posicao_f+1) - (strlen($arquivo))));
                                                $outra_imagem.= $arquivo;
                                                $nova_imagem = $outra_imagem;
                                            }
                                        } else {
                                            $posicao_i = strpos($imagem, 'height="');
                                            if ($posicao_i > 0) {
                                                $arquivo = substr($imagem, (($posicao_i+8) - (strlen($imagem))));
                                                $nova_imagem = substr($imagem, 0, ($posicao_i+8)).$altura;
                                                $posicao_f = strpos($arquivo, '"');
                                                $arquivo = substr($arquivo, (($posicao_f) - (strlen($arquivo))));
                                                $nova_imagem.= $arquivo;

                                                $posicao_i = strpos($nova_imagem, 'width="');
                                                if ($posicao_i > 0) {
                                                    $arquivo = substr($nova_imagem, (($posicao_i+7) - (strlen($nova_imagem))));
                                                    $outra_imagem = substr($nova_imagem, 0, $posicao_i);
                                                    $posicao_f = strpos($arquivo, '"');
                                                    $arquivo = substr($arquivo, (($posicao_f+1) - (strlen($arquivo))));
                                                    $outra_imagem.= $arquivo;
                                                    $nova_imagem = $outra_imagem;
                                                }
                                            }
                                        }

                                        // Bruno Roeder acessibilidade: adicionado title e retirado atributo
                                        $nova_imagem = str_replace('<img', "<img alt=\"Imagem notícia: $entrada->title\" title=\"Imagem notícia: $entrada->title\"", $nova_imagem);
                                        if ($nova_imagem != '') {
                                            if ($posicao_noticia == '2') {
                                                echo "              <div class=\"divFotoPrimeiraNoticiaMeioCapa\">".$nova_imagem."</div>\n";
                                            } elseif ($posicao_noticia == '3') {
                                                echo "              <div class=\"divFotoSegundaNoticiaMeioCapa\">".$nova_imagem."</div>\n";
                                            } elseif ($posicao_noticia == '4') {
                                                echo "              <div class=\"divFotoSegundaNoticiaMeioCapa\">".$nova_imagem."</div>\n";
                                            }
                                        }
                                    }
                                    // Bruno Roeder acessibilidade: retirado o atributo imageanchor invalido ao w3c
                                    $abreviado = str_replace('imageanchor="1"', '', $abreviado);
                                    echo "            <p class=\"fontResumoNoticia\">".utf8_decode(nl2br($abreviado))."</p>\n";
                                    if ($posicao_noticia == '2') {
                                        $aberto -= 1;
                                        echo "            </div>\n";
                                    } elseif ($posicao_noticia == '3') {
                                        $aberto -= 1;
                                        echo "            </div>\n";
                                    } elseif ($posicao_noticia == '4') {
                                        $aberto -= 1;
                                        $aberto -= 1;
                                        echo "            </div>\n";
                                        echo "            </div>\n";
                                    }
                                }
                            }
                            $j+= 1;
                        }
                    }
                }
            }
        }
        while ($aberto > 0) {
            $aberto -= 1;
            echo "            </div>\n";
        }

        echo "            <div class=\"divPrimeiraNoticiaMeioCapa\">\n";
        echo "              <p class=\"fontComandosFiltros\" style=\"font-weight: lighter;\">\n";
        echo "                <a href=\"".$_SESSION['life_link_completo']."noticias\" class=\"fontLink\">Mais notícias</a> \n";
        echo "              </p>\n";
        echo "            </div>\n";
    }

    function endereco_existe($url)
    {
        $h = get_headers($url);
        $status = array();
        preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0], $status);
        return ($status[1] == 200);
    }

//**************BANCO DE DADOS**************************************************
    public function selectBlogs($eh_ativo)
    {
        $sql  = "SELECT * ".
              "FROM life_blogs ".
              "WHERE cd_blog > 0 ";
        if ($eh_ativo != 2) {
            $sql.= "AND eh_ativo = '$eh_ativo' ";
        }
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA BLOGS");
        $dados = array();
        while ($linha = mysql_fetch_assoc($result_id)) {
            $dados[] = $linha;
        }
        return $dados;
    }

    public function selectDadosBlog($cd_blog)
    {
        $sql  = "SELECT * ".
              "FROM life_blogs ".
              "WHERE cd_blog = '$cd_blog' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA BLOGS");
        $dados= mysql_fetch_assoc($result_id);
        return $dados;
    }
    
    public function selectNumeroBlogsLimite()
    {
        $sql  = "SELECT count(cd_blog) qtd ".
              "FROM life_blogs ".
              "WHERE eh_ativo = '1' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA BLOGS");
        $dados = mysql_fetch_assoc($result_id);
        return $dados['qtd'];
    }
    
    public function selectBlogsLimite($primeiro, $limite)
    {
        $sql  = "SELECT * ".
              "FROM life_blogs ".
              "WHERE eh_ativo = '1' ".
              "LIMIT ".$primeiro.", ".$limite;
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA BLOGS");
        $dados = array();
        while ($linha = mysql_fetch_assoc($result_id)) {
            $dados[] = $linha;
        }
        return $dados;
    }
    
    
    public function insereBlog($nm_blog, $ds_link, $nr_noticias, $eh_ativo)
    {
        $cd_usuario_cadastro = $_SESSION['life_codigo'];
        $dt_cadastro = date('Y-m-d');
        $sql = "INSERT INTO life_blogs ".
             "(nm_blog, ds_link, nr_noticias, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_blog\", \"$ds_link\", \"$nr_noticias\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'blogs');
        mysql_query($sql) or die("Erro no banco de dados! - TABELA BLOGS");
        $saida = mysql_affected_rows();
        return $saida;
    }
    
    public function alteraBlog($cd_blog, $nm_blog, $ds_link, $nr_noticias, $eh_ativo)
    {
        $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
        $dt_ultima_atualizacao = date('Y-m-d');
        $sql = "UPDATE life_blogs SET ".
             "nm_blog = \"$nm_blog\", ".
             "ds_link = \"$ds_link\", ".
             "nr_noticias = \"$nr_noticias\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_blog= '$cd_blog' ";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'blogs');
        mysql_query($sql) or die("Erro no banco de dados! - TABELA BLOGS");
        $saida = mysql_affected_rows();
        return $saida;
    }
}
