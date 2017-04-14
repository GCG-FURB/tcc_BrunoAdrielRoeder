<?php
class Noticia
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
        if (isset($_GET['tp'])) {
            $tipo = addslashes($_GET['tp']);
        } else {
            $tipo = '';
        }
      
        require_once 'includes/data_hora.php';
        $dh= new DataHora();
        if (!isset($_SESSION['life_dt_inicio'])) {
            $dt_fim = date("Y-m-d");
            $dt_inicio = $dh->subtrairData($dt_fim, '15');
            $_SESSION['life_dt_inicio'] = $dt_inicio;
            $_SESSION['life_dt_fim'] = $dt_fim;
        }

        if ($tipo == 'adicionais') {
            if (isset($_GET['cm'])) {
                $cd_noticia = addslashes($_GET['cm']);
            } else {
                echo "<p class=\"fontConteudoAlerta\">Selecione a not�cia ao qual o conte�do se relaciona!</p>\n";
                $this->controleExibicao($secao, $subsecao, $item);
                return false;
            }

            require_once 'conteudos/noticias_conteudos.php';
            $mat_con = new NoticiaConteudo();
            $mat_con->controleExibicao($secao, $subsecao, $item, $acao, $ativas, $codigo, $cd_noticia);
        } else {
            switch ($acao) {
                case "":
                    $this->listarAcoes($secao, $subsecao, $item, $ativas);
                    $this->listarItens($secao, $subsecao, $item, $ativas);
                    break;

                case "pesquisar":
                    $this->pesquisar();
                    $this->listarAcoes($secao, $subsecao, $item, $ativas);
                    $this->listarItens($secao, $subsecao, $item, $ativas);
                    break;

                case "cadastrar":
                    echo "  <h2>Cadastro de not�cias</h2>\n";
                    $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
                    $this->montarFormularioCadastro($link);
                    break;

                case "editar":
                    echo "  <h2>Edi��o de not�cia</h2>\n";
                    $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
                    $this->montarFormularioEdicao($link, $codigo);
                    break;
          
                case "salvar":
                    if (isset($_SESSION['life_edicao'])) {
                        $termo = "Not�cia";
                        $genero = "a";
                        $this->salvarCadastroAlteracao($termo, $genero);
                        unset($_SESSION['life_edicao']);
                    }
                    $this->listarAcoes($secao, $subsecao, $item, $ativas);
                    $this->listarItens($secao, $subsecao, $item, $ativas);
                    break;
        
                case "alt_status":
                    $termo = "Not�cia";
                    $genero = "a";
                    $this->alterarStatusItem($codigo, $termo, $genero);
                    $this->listarAcoes($secao, $subsecao, $item, $ativas);
                    $this->listarItens($secao, $subsecao, $item, $ativas);
                    break;
          
                case "fotos":
                    require_once 'conteudos/fotos.php';
                    $foto= new Fotos();
                    $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $codigo);
                    $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$codigo;
                    $foto->listarFotos($link, $codigo, 'NO');
                    break;
  
                case "cad_foto":
                    require_once 'conteudos/fotos.php';
                    $foto= new Fotos();
                    $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $codigo);
                    $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$codigo;
                    $foto->montarFormularioCadastroFoto($link, $codigo, 'NO', 'normal');
                    break;
      
                case "edi_foto":
                    require_once 'conteudos/fotos.php';
                    $foto= new Fotos();
                    $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $codigo);
                    $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$codigo;
                    if (isset($_GET['ft'])) {
                        $foto->montarFormularioEdicaoFoto($link, $codigo, 'NO', 'normal');
                    } else {
                        $foto->listarFotos($link, $codigo, 'NO');
                    }
                    break;
                     
                case "salv_foto":
                    require_once 'conteudos/fotos.php';
                    $foto= new Fotos();
                    $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $codigo);
                    if (isset($_SESSION['life_edicao'])) {
                        $foto->salvarFoto();
                        unset($_SESSION['life_edicao']);
                    }
                    $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$codigo;
                    $foto->listarFotos($link, $codigo, 'NO');
                    break;
  
                case "exc_foto":
                    require_once 'conteudos/fotos.php';
                    $foto= new Fotos();
                    $this->listarAcoesFotos($secao, $subsecao, $item, $ativas, $codigo);
                    $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$codigo;
                    if (isset($_GET['ft'])) {
                        $foto->exclusaoFoto($link, $codigo, 'NO');
                    }
                    $foto->listarFotos($link, $codigo, 'NO');
                    break;
            }
        }
    }

    private function listarAcoesFotos($secao, $subsecao, $item, $ativas, $cd_noticia)
    {
        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$cd_noticia."&acao=cad_foto\"><img src=\"icones/novo.png\" alt=\"Nova foto\" title=\"Nova foto\" border=\"0\"></a> \n";
        echo "</p>\n";

        $noticia = $this->selectDadosNoticia($cd_noticia);
        echo "<h2>Not�cia ".$noticia['tt_noticia']."</h2>\n";
    }
       
    private function listarAcoes($secao, $subsecao, $item, $ativas)
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();
        require_once 'includes/data_hora.php';
        $dh= new DataHora();

        $opcoes_1= array();
        $opcao= array();
        $opcao['indice']= "1";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";
        if ($ativas == '1') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }                 $opcao['descricao']= "Ativas";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "2";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";
        if ($ativas == '0') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }                 $opcao['descricao']= "Inativas";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "3";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";
        if ($ativas == '2') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }                 $opcao['descricao']= "Ativas/Inativas";
        $opcoes_1[]= $opcao;
        foreach ($opcoes_1 as $op) {
            $nome = 'comandos_filtros_1_'.$op['indice'];
            $util->campoHidden($nome, $op['link']);
        }
      
        include 'js/js_navegacao.js';
        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova not�cia\" title=\"Nova not�cia\" border=\"0\"></a> \n";
        echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
        foreach ($opcoes_1 as $op) {
            echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
        }
        echo "  </select>\n";
        echo "</p>\n";

        include "js/js_compara_datas.js";
        echo "<form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=pesquisar\" onSubmit=\"return validaDataMenorIgual(this);\">\n";
        $util->campoHidden('eh_form', '1');
        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  In�cio ".$dh->imprimeFormularioDataComandos($_SESSION['life_dt_inicio'], 'inicio')."\n";
        echo "  Fim ".$dh->imprimeFormularioDataComandos($_SESSION['life_dt_fim'], 'fim')."\n";
        echo "  <br />\n";
        echo "  <input type=\"button\" class=\"botao\" value=\"Pesquisar\" onClick=\"validaDataMenorIgual(cadastro);\">\n";
        echo "</p>\n";
        echo "</form>\n";
    }
    
    public function pesquisar()
    {
        $dia_inicio = addslashes($_POST['dia_inicio']);
        $mes_inicio = addslashes($_POST['mes_inicio']);
        $ano_inicio = addslashes($_POST['ano_inicio']);
        $_SESSION['life_dt_inicio'] = $ano_inicio."-".$mes_inicio."-".$dia_inicio;
        $dia_fim = addslashes($_POST['dia_fim']);
        $mes_fim = addslashes($_POST['mes_fim']);
        $ano_fim = addslashes($_POST['ano_fim']);
        $_SESSION['life_dt_fim'] = $ano_fim."-".$mes_fim."-".$dia_fim;
    }

    private function listarItens($secao, $subsecao, $item, $ativas)
    {
        require_once 'includes/data_hora.php';
        $dh = new DataHora();

        $ordem = 'd';
        $noticias = $this->selectNoticias($ativas, $_SESSION['life_dt_inicio'], $_SESSION['life_dt_fim'], $ordem);

        $mensagem = "Not�cias ";

        echo "<h2>".$mensagem."</h2>\n";
        echo "  <table class=\"tabConteudo\">\n";
        $style = "linhaOn";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">Not�cia</td>\n";
        echo "      <td class=\"celConteudo\">A��es</td>\n";
        echo "    </tr>\n";
        $hoje = date('Y-m-d');
        foreach ($noticias as $it) {
            $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
            echo "    <tr class=\"".$style."\">\n";
            echo "      <td class=\"celConteudo\">".$it['tt_noticia']."</td>\n";
            echo "      <td class=\"celConteudo\">\n";
            echo "        <a href=\"#\" class=\"dcontexto\">\n";
            echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
            echo "          <span class=\"fontdDetalhar\">\n";
            echo $this->detalharNoticia($it['cd_noticia']);
            echo "        </span></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_noticia']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_noticia']."&acao=alt_status\">";
            if ($it['eh_ativo'] == 1) {
                echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
            } else {
                echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
            }
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_noticia']."&acao=fotos\"><img src=\"icones/fotos.png\" alt=\"Fotos\" title=\"Fotos\" border=\"0\"></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cm=".$it['cd_noticia']."&tp=adicionais\"><img src=\"icones/conteudos.png\" alt=\"Conte�dos adicionais da not�cia\" title=\"Conte�dos adicionais da not�cia\" border=\"0\"></a>\n";
            echo "      </td>\n";
            echo "    </tr>\n";
        }
        echo "  </table>\n";
        echo "  <br /><br />\n";
    }

    
    public function detalharNoticia($cd_noticia)
    {
        require_once 'usuarios/usuarios.php';
        $usu = new Usuario();
        require_once 'includes/data_hora.php';
        $dh = new DataHora();
      
        $dados = $this->selectDadosNoticia($cd_noticia);
      
        $retorno = "";
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
        if ($dados['cd_usuario_ultima_atualizacao'] != '') {
            $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
            $retorno.= "�ltima atualiza��o por ".$dados_usuario['nm_usuario']."<br />\n";
            $retorno.= "Data do �ltima atualiza��o ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
        }
        return $retorno;
    }

     
    public function montarFormularioCadastro($link)
    {
        $cd_noticia = "";
        $tt_noticia = "";
        $ds_noticia = "";
        $nm_autor = "";
        $ds_email_autor = "";
        $ds_fonte = "";
        $ds_link = "";
        $dt_noticia = date('Y-m-d');
        $eh_ativo = "1";
      
        $_SESSION['life_edicao']= 1;
        $this->imprimeFormularioCadastro($link, $cd_noticia, $tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo);
    }

    public function montarFormularioEdicao($link, $cd_noticia)
    {
        $dados= $this->selectDadosNoticia($cd_noticia);
        $tt_noticia = $dados['tt_noticia'];
        $ds_noticia = $dados['ds_noticia'];
        $nm_autor = $dados['nm_autor'];
        $ds_email_autor = $dados['ds_email_autor'];
        $ds_fonte = $dados['ds_fonte'];
        $ds_link = $dados['ds_link'];
        $dt_noticia = $dados['dt_noticia'];
        $eh_ativo = $dados['eh_ativo'];

        $_SESSION['life_edicao']= 1;
        $this->imprimeFormularioCadastro($link, $cd_noticia, $tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo);
    }
    
    private function imprimeFormularioCadastro($link, $cd_noticia, $tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo)
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();
        require_once 'includes/data_hora.php';
        $dh = new DataHora();
        require_once 'conteudos/fotos.php';
        $foto = new Fotos();
      
        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
        echo "</p>\n";
      
        include "js/js_cadastro_noticia.js";
        echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" enctype=\"multipart/form-data\" onSubmit=\"return  valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');
        $util->campoHidden('cd_noticia', $cd_noticia);
      
        $util->linhaUmCampoText(1, 'T�tulo ', 'tt_noticia', 100, 100, $tt_noticia);
        $util->linhaTexto(1, 'Conte�do principal ', 'ds_noticia', $ds_noticia, '15', '100');
        if ($cd_noticia > 0) {
            $imagem = $foto->selectPrimeiraFoto($cd_noticia, 'NO');
            if ($imagem['cd_foto'] != '') {
                $util->campoHidden('ds_arquivo_antigo', $imagem['ds_arquivo']);
                $util->campoHidden('cd_foto', $imagem['cd_foto']);
                $util->linhaUmCampoArquivo(1, 'Foto ', 'ds_arquivo', 150, 70, '');
                echo "      <tr>\n";
                echo "        <td class=\"celFoto\" colspan=\"2\"style=\"text-align:center;\">\n";
                echo "          <img src=\"".$imagem['ds_pasta']."/".$imagem['ds_arquivo']."\" alt=\"".$imagem['ds_arquivo']."\" title=\"".$imagem['ds_arquivo']."\" border=\"0\" width=\"500\">\n";
                echo "        </td>\n";
                echo "      </tr>\n";
                $util->linhaComentario('Para alterar o arquivo atual, selecione um novo arquivo. O arquivo com a imagem deve estar no formato .png ou .jpg.');
            } else {
                $util->campoHidden('cd_foto', '');
                $util->linhaUmCampoArquivo(1, 'Foto ', 'ds_arquivo', 150, 70, '');
                $util->linhaComentario('O arquivo com a imagem deve estar no formato .png ou .jpg.');
            }
        } else {
            $util->campoHidden('cd_foto', '');
            $util->linhaUmCampoArquivo(0, 'Foto ', 'ds_arquivo', 150, 70, '');
            $util->linhaComentario('O arquivo com a imagem deve estar no formato .png ou .jpg.');
        }
        $util->linhaUmCampoText(0, 'Autor ', 'nm_autor', 100, 100, $nm_autor);
        $util->linhaUmCampoText(0, 'E-mail do autor ', 'ds_email_autor', 100, 100, $ds_email_autor);
        $util->linhaUmCampoText(0, 'Fonte ', 'ds_fonte', 100, 100, $ds_fonte);
        $util->linhaUmCampoText(0, 'Link da fonte ', 'ds_link', 250, 100, $ds_link);
        $dh->imprimeFormularioData("Data ", $dt_noticia, 'noticia');
      
        $opcao= array();
        $opcao[]= '1';
        $opcao[]= 'Sim';
        $opcoes[]= $opcao;
        $opcao= array();
        $opcao[]= '0';
        $opcao[]= 'N�o';
        $opcoes[]= $opcao;
        $util->linhaSeletor('� ativa ', 'eh_ativo', $eh_ativo, $opcoes, '100');

        $util->linhaBotao('Salvar', "valida(cadastro);");
        echo "    </table>\n";
        echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigat�rios</p>\n";
        echo "  </form>\n";
        $util->posicionarCursor('cadastro', 'tt_noticia');
    }
    
    public function salvarCadastroAlteracao($termo, $genero)
    {
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        require_once 'conteudos/fotos.php';
        $foto = new Fotos();

        $cd_noticia = addslashes($_POST['cd_noticia']);

        $tt_noticia = $util->limparVariavel($_POST['tt_noticia']);
        $ds_noticia = $util->limparVariavel($_POST['ds_noticia']);
        $nm_autor = $util->limparVariavel($_POST['nm_autor']);
        $ds_email_autor = $util->limparVariavel($_POST['ds_email_autor']);
        $ds_fonte = $util->limparVariavel($_POST['ds_fonte']);
        $ds_link = $util->limparVariavel($_POST['ds_link']);

        $dia_noticia = addslashes($_POST['dia_noticia']);
        $mes_noticia = addslashes($_POST['mes_noticia']);
        $ano_noticia = addslashes($_POST['ano_noticia']);
        $dt_noticia = $ano_noticia.'-'.$mes_noticia.'-'.$dia_noticia;

        $eh_ativo = addslashes($_POST['eh_ativo']);

        $lk_seo = $util->retornaLinkSEO($tt_noticia, 'life_noticias', 'lk_seo', '100', $cd_noticia);

        if ($cd_noticia > 0) {
            $alterou_noticia = $this->alteraNoticia($cd_noticia, $tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo, $lk_seo);
            $alterou_foto = $foto->salvarFotoNoticia($cd_noticia, 'NO');
            if ($alterou_noticia || $alterou_foto) {
                echo "<p class=\"fontConteudoSucesso\">".$termo." editad".$genero." com sucesso!</p>\n";
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas na edi��o d".$genero." ".$termo.", ou nenhuma informa��o alterada!</p>\n";
            }
        } else {
            $cd_noticia = $this->insereNoticia($tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo, $lk_seo);
            if ($cd_noticia > 0) {
                if ($arquivo['name'] != '') {
                    if ($foto->salvarFotoNoticia($cd_noticia, 'NO')) {
                        echo "<p class=\"fontConteudoSucesso\">".$termo." cadastrad".$genero." com sucesso!</p>\n";
                    } else {
                        echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro d".$genero." ".$termo."!</p>\n";
                    }
                } else {
                    echo "<p class=\"fontConteudoSucesso\">".$termo." cadastrad".$genero." com sucesso!</p>\n";
                }
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro d".$genero." ".$termo."!</p>\n";
            }
        }
    }
    
    public function alterarStatusItem($cd_noticia, $termo, $genero)
    {
        $dados= $this->selectDadosNoticia($cd_noticia);
        $tt_noticia = $dados['tt_noticia'];
        $ds_noticia = $dados['ds_noticia'];
        $nm_autor = $dados['nm_autor'];
        $ds_email_autor = $dados['ds_email_autor'];
        $ds_fonte = $dados['ds_fonte'];
        $ds_link = $dados['ds_link'];
        $dt_noticia = $dados['dt_noticia'];
        $eh_ativo = $dados['eh_ativo'];
        $lk_seo = $dados['lk_seo'];
      
        if ($eh_ativo == 1) {
            $eh_ativo= 0;
        } else {
            $eh_ativo= 1;
        }
      
        if ($this->alteraNoticia($cd_noticia, $tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo, $lk_seo)) {
            echo "<p class=\"fontConteudoSucesso\">Status d".$genero." ".$termo." alterado com sucesso!</p>\n";
        } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status d".$genero." ".$termo."!</p>\n";
        }
    }

//**********************************EXIBI��O************************************
    public function exibeNoticiasCapa()
    {
        return;
        require_once 'conteudos/blogs.php';
        $blo = new Blog();

        echo "      <div class=\"divNoticiasMeioCapa\">\n";
        echo "        <div>\n";

        $blo->exibeBlogCapa();

        echo "        </div>\n";
        echo "      </div>\n";
/*
      require_once 'conteudos/fotos.php';                                       $foto= new Fotos();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();


      $cd_ultima_noticia = $this->selectCodigoUltimaNoticia();
      $dados = $this->selectDadosNoticia($cd_ultima_noticia);


      echo "      <div class=\"divNoticiasMeioCapa\">\n";
      echo "        <div>\n";
      echo "          <a href=\"".$_SESSION['life_link_completo']."noticias/".$dados['lk_seo']."\" class=\"fontLinkNoticias\">\n";

      echo "            <div class=\"divPrimeiraNoticiaMeioCapa\">\n";
      echo "              <p class=\"fontTituloNoticia\">".$dados['tt_noticia']."</p>\n";
      $listadas = array();
      $listadas[] = $dados['cd_noticia'];
      $uma_foto = $foto->retornaUmaFoto($dados['cd_noticia'], 'NO');
      if ($uma_foto != '') {
        echo "              <div class=\"divFotoPrimeiraNoticiaMeioCapa\">\n";
        echo "                <img src=\"".$_SESSION['life_link_completo'].$uma_foto['ds_pasta'].$uma_foto['ds_arquivo']."\" alt=\"".$uma_foto['ds_foto']."\" title=\"".$uma_foto['ds_foto']."\" width=\"100%\">\n";
        echo "              </div>\n";
      }
      echo "              <p class=\"fontResumoNoticia\">".nl2br($util->abreviar($dados['ds_noticia'], $conf->retornaNumeroCaracteresResumoNoticiaCentroCapaPrincipal()))."</p>\n";
      echo "            </div>\n";
      echo "          </a>\n";

      $nr_noticias = $conf->retornaNumeroChamadasNoticiaisSecundariasCapa();
      $itens = $this->selectNoticiasLimite('0', ($nr_noticias + 3));
      if (count($itens) > '2') {
        echo "        <div class=\"divSegundaNoticiaMeioCapa\">\n";
        $limite = 0;
        foreach ($itens as $it) {
          if ($it['cd_noticia'] != $dados['cd_noticia']) {
            if ($limite < 2) {
              echo "          <a href=\"".$_SESSION['life_link_completo']."noticias/".$dados['lk_seo']."\" class=\"fontLinkNoticias\">\n";
              $listadas[] = $it['cd_noticia'];
              $limite += 1;
              if ($limite == 1) {
                echo "            <div class=\"divSegundaNoticiaMeioCapaMeiaEsquerda\">\n";
              } else {
                echo "            <div class=\"divSegundaNoticiaMeioCapaMeiaDireita\">\n";
              }
              echo "              <p class=\"fontTituloNoticia\">".$it['tt_noticia']."</p>\n";
              $uma_foto = $foto->retornaUmaFoto($it['cd_noticia'], 'NO');
              if ($uma_foto != '') {
                echo "              <div class=\"divFotoSegundaNoticiaMeioCapa\">\n";
                echo "                <img src=\"".$_SESSION['life_link_completo'].$uma_foto['ds_pasta'].$uma_foto['ds_arquivo']."\" alt=\"".$uma_foto['ds_foto']."\" title=\"".$uma_foto['ds_foto']."\" width=\"100%\">\n";
                echo "              </div>\n";
                echo "              <p class=\"fontResumoNoticia\">".nl2br($util->abreviar($it['ds_noticia'], $conf->retornaNumeroCaracteresResumoNoticiaCentroCapaSecundaria()))."</p>\n";
              } else {
                echo "              <p class=\"fontResumoNoticia\">".nl2br($util->abreviar($it['ds_noticia'], ($conf->retornaNumeroCaracteresResumoNoticiaCentroCapaSecundaria() *3)))."</p>\n";
              }
              echo "            </div>\n";
              echo "          </a>\n";
            }
          }
        }
        echo "        </div>\n";
      }
      echo "      </div>\n";

      echo "        <div class=\"divSegundaNoticiaMeioCapa\">\n";
      echo "          <p class=\"fontTituloNoticia\">Veja tamb�m</p>\n";
      foreach ($itens as $it) {
        if (!in_array($it['cd_noticia'], $listadas)) {
          echo "          <div class=\"divPrimeiraNoticiaMeioCapa\">\n";
          echo "            <a href=\"".$_SESSION['life_link_completo']."noticias/".$it['lk_seo']."\" class=\"fontLink\">\n";
          echo "            <p class=\"fontTituloNoticiaMenor\">".$it['tt_noticia']."</p>\n";
          echo "            </a>\n";
          echo "          </div>\n";
        }
      }
      echo "<p class=\"fontComandosFiltros\" style=\"font-weight: lighter;\">\n";
      echo "  <a href=\"".$_SESSION['life_link_completo']."noticias\" class=\"fontLink\">Todas as not�cias do portal Inversos</a> \n";
      echo "</p>\n";

      echo "        </div>\n";
      echo "      </div>\n";    */
    }

    public function controleExibicaoPublica($pagina, $lista_paginas)
    {
        if (!isset($lista_paginas[1])) {
            $this->listarNoticias('');
        } elseif (isset($lista_paginas[1])) {
            if (($lista_paginas[1] == 'primeira') || ($lista_paginas[1] == 'anterior') || ($lista_paginas[1] == 'proxima') || ($lista_paginas[1] == 'ultima')) {
                $acao = addslashes($lista_paginas[1]);
                $this->listarNoticias($acao);
            } else {
                $lk_seo = addslashes($lista_paginas[1]);
                $this->listarNoticiaCompleta($lk_seo);
            }
        }
    }


    public function listarNoticias($acao)
    {
        require_once 'includes/configuracoes.php';
        $conf = new Configuracao();
      
     
        $_SESSION['life_noticia_qtd'] = $this->selectNumeroNoticiasLimite();
        $_SESSION['life_noticia_itens_pagina'] = '10';
        $_SESSION['life_noticia_paginas'] = round($_SESSION['life_noticia_qtd'] / $_SESSION['life_noticia_itens_pagina']);
        if (($_SESSION['life_noticia_paginas'] * $_SESSION['life_noticia_itens_pagina']) >= $_SESSION['life_noticia_qtd']) {
            $_SESSION['life_noticia_paginas'] -= 1;
        }
        if (!isset($_SESSION['life_noticia_pagina'])) {
            $_SESSION['life_noticia_pagina'] = 0;
        } else {
            switch ($acao) {
                case "primeira":
                    $_SESSION['life_noticia_pagina'] = 0;
                    break;
                case "proxima":
                    $_SESSION['life_noticia_pagina'] += 1;
                    if ($_SESSION['life_noticia_pagina'] > $_SESSION['life_noticia_paginas']) {
                        $_SESSION['life_noticia_pagina'] = $_SESSION['life_noticia_paginas'];
                    }
                    break;
                case "anterior":
                    if ($_SESSION['life_noticia_pagina'] > 0) {
                        $_SESSION['life_noticia_pagina'] -= 1;
                    }
                    break;
                case "ultima":
                    $_SESSION['life_noticia_pagina'] = $_SESSION['life_noticia_paginas'];
                    break;
            }
        }
        $primeiro = $_SESSION['life_noticia_pagina'] * $_SESSION['life_noticia_itens_pagina'];
        $limite = $_SESSION['life_noticia_itens_pagina'];
        $itens = $this->selectNoticiasLimite($primeiro, $limite);


        foreach ($itens as $noticia) {
            $this->listarNoticia($noticia, '1', '', 'noticias', 'NO');
            echo "<hr>\n";
        }
      
        echo "    <p class=\"fontConteudoCentralizado\">\n";
        if ($_SESSION['life_noticia_pagina'] > 0) {
            echo "      <a href=\"".$_SESSION['life_link_completo']."noticias/primeira\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_primeira.png\" alt=\"Primeira P�gina\" title=\"Primeira P�gina\" border=\"0\"></a>\n";
        }
        if ($_SESSION['life_noticia_pagina'] > 1) {
            echo "      <a href=\"".$_SESSION['life_link_completo']."noticias/anterior\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_anterior.png\" alt=\"P�gina Anterior\" title=\"P�gina Anterior\" border=\"0\"></a>\n";
        }
        if ($_SESSION['life_noticia_pagina'] < ($_SESSION['life_noticia_paginas'] - 1)) {
            echo "      <a href=\"".$_SESSION['life_link_completo']."noticias/proxima\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_proxima.png\" alt=\"Pr�xima P�gina\" title=\"Pr�xima P�gina\" border=\"0\"></a>\n";
        }
        if ($_SESSION['life_noticia_pagina'] < $_SESSION['life_noticia_paginas']) {
            echo "      <a href=\"".$_SESSION['life_link_completo']."noticias/ultima\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_ultima.png\" alt=\"�ltima P�gina\" title=\"�ltima P�gina\" border=\"0\"></a>\n";
        }
        echo "    </p>\n";
    }

    public function listarNoticiaCompleta($lk_seo)
    {
        $noticia = $this->selectDadosNoticiaSEO($lk_seo);
        if ($noticia['cd_noticia'] > 0) {
            $this->listarNoticia($noticia, '1', '0', 'noticias', 'NO');
            echo "<p class=\"fontComandosFiltros\">\n";
            echo "  <a href=\"".$_SESSION['life_link_completo']."noticias\" class=\"fontLink\">Mais not�cias</a> \n";
            echo "</p>\n";
        } else {
            echo "<p class=\"fontConteudoAlerta\">Not�cia n�o encontrada!</p>\n";
        }
    }
    
    public function listarNoticia($noticia, $eh_completa, $tm_resumo, $secao, $tp_associacao)
    {
        require_once 'conteudos/fotos.php';
        $foto = new Fotos();
        require_once 'conteudos/noticias_conteudos.php';
        $mat_con = new NoticiaConteudo();
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();
        require_once 'includes/data_hora.php';
        $dh = new DataHora();
            
        echo "      <h2>".$noticia['tt_noticia']."</h2>\n";

        echo "      <p>\n";
        echo "        ".$dh->imprimirData($noticia['dt_noticia'])."<br />\n";
        if (($noticia['nm_autor'] != '') || ($noticia['ds_email_autor'] != '')) {
            echo "        Autor ";
            if ($noticia['ds_email_autor'] != '') {
                echo "<a href=\"mailto:".$noticia['ds_email_autor']."\" class=\"fontLink\">";
                if ($noticia['nm_autor'] != '') {
                    echo $noticia['nm_autor'];
                } else {
                    echo $noticia['ds_email_autor'];
                }
                echo "</a>\n";
            } else {
                echo $noticia['nm_autor']."\n";
            }
            echo "        <br />\n";
        }
        if (($noticia['ds_fonte'] != '') || ($noticia['ds_link'] != '')) {
            echo "        Fonte ";
            if ($noticia['ds_link'] != '') {
                echo "<a href=\"".$noticia['ds_link']."\" target=\"_blank\" class=\"fontLinkExibicao\">";
                if ($noticia['ds_fonte'] != '') {
                    echo $noticia['ds_fonte'];
                } else {
                    echo $noticia['ds_link'];
                }
                echo "</a>\n";
            } else {
                echo $noticia['ds_fonte']."\n";
            }
            echo "        <br />\n";
        }
        echo "        <br />\n";
        echo "      </p>\n";
        if ($eh_completa == '1') {
            echo "      <p>\n";
            echo "        ".nl2br($noticia['ds_noticia'])."\n";
            echo "      </p>\n";
            $foto->exibeFotos($noticia['cd_noticia'], $tp_associacao);
            $mat_con->listarConteudosNoticia($noticia['cd_noticia']);
        } else {
            echo "      <p>\n";
            echo "        ".nl2br($util->abreviar($noticia['ds_noticia'], $tm_resumo))."\n";
            echo "      </p>\n";
            echo "      <p class=\"fontLinkLeiaMais\">\n";
            echo "      <a href=\"".$_SESSION['life_link_completo'].$secao."/".$noticia['lk_seo']."\" class=\"fontLinkLeiaMais\">.:. Leia Mais .:.</a>\n";
            echo "      </p>\n";
        }
           
    }

//**************BANCO DE DADOS**************************************************

    public function selectNoticias($eh_ativo, $dt_inicio, $dt_fim, $ordem)
    {
        $sql  = "SELECT * ".
              "FROM life_noticias ".
              "WHERE dt_noticia >= '$dt_inicio' ".
              "AND dt_noticia <= '$dt_fim' ";
        if ($eh_ativo != 2) {
            $sql.= "AND eh_ativo = '$eh_ativo' ";
        }
        if ($ordem == 'd') {
            $sql.= "ORDER BY dt_noticia DESC, tt_noticia ";
        } elseif ($ordem == 't') {
            $sql.= "ORDER BY tt_noticia, dt_noticia DESC ";
        }
  
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $dados = array();
        while ($linha = mysql_fetch_assoc($result_id)) {
            $dados[] = $linha;
        }
        return $dados;
    }

    public function selectCodigoUltimaNoticia()
    {
        $sql  = "SELECT MAX(cd_noticia) codigo ".
              "FROM life_noticias ".
              "WHERE eh_ativo = '1' ".
              "AND dt_noticia = ( ".
              "                   SELECT MAX(dt_noticia) dt_noticia ".
              "                   FROM life_noticias ".
              "                 )";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];

    }
                
    public function selectDadosNoticia($cd_noticia)
    {
        $sql  = "SELECT * ".
              "FROM life_noticias ".
              "WHERE cd_noticia = '$cd_noticia' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $dados= mysql_fetch_assoc($result_id);
        return $dados;
    }
    
    
    public function selectDadosNoticiaSEO($lk_seo)
    {
        $sql  = "SELECT * ".
              "FROM life_noticias ".
              "WHERE lk_seo = '$lk_seo' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $dados= mysql_fetch_assoc($result_id);
        return $dados;
    }
    
    public function selectNumeroNoticiasLimite()
    {
        $sql  = "SELECT count(cd_noticia) qtd ".
              "FROM life_noticias ".
              "WHERE eh_ativo = '1' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $dados = mysql_fetch_assoc($result_id);
        return $dados['qtd'];
    }
    
    public function selectNoticiasLimite($primeiro, $limite)
    {
        $sql  = "SELECT * ".
              "FROM life_noticias ".
              "WHERE eh_ativo = '1' ".
              "ORDER BY dt_noticia DESC, cd_noticia DESC ".
              "LIMIT ".$primeiro.", ".$limite;
        $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $dados = array();
        while ($linha = mysql_fetch_assoc($result_id)) {
            $dados[] = $linha;
        }
        return $dados;
    }
    
    
    public function insereNoticia($tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo, $lk_seo)
    {
        $cd_usuario_cadastro = $_SESSION['life_codigo'];
        $dt_cadastro = date('Y-m-d');
        $sql = "INSERT INTO life_noticias ".
             "(tt_noticia, ds_noticia, nm_autor, ds_email_autor, ds_fonte, ds_link, dt_noticia, eh_ativo, lk_seo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$tt_noticia\", \"$ds_noticia\", \"$nm_autor\", \"$ds_email_autor\", \"$ds_fonte\", \"$ds_link\", \"$dt_noticia\", \"$eh_ativo\", \"$lk_seo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'noticias');
        mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $saida = mysql_affected_rows();
        if ($saida > 0) {
            $sql  = "SELECT MAX(cd_noticia) cd ".
                "FROM life_noticias ".
                "WHERE tt_noticia = \"$tt_noticia\" ".
                "AND ds_noticia = \"$ds_noticia\" ";
            $result_id = @mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
            $dados= mysql_fetch_assoc($result_id);
            return $dados['cd'];
        } else {
            return $saida;
        }
    }
    
    public function alteraNoticia($cd_noticia, $tt_noticia, $ds_noticia, $nm_autor, $ds_email_autor, $ds_fonte, $ds_link, $dt_noticia, $eh_ativo, $lk_seo)
    {
        $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
        $dt_ultima_atualizacao = date('Y-m-d');
        $sql = "UPDATE life_noticias SET ".
             "tt_noticia = \"$tt_noticia\", ".
             "ds_noticia = \"$ds_noticia\", ".
             "nm_autor = \"$nm_autor\", ".
             "ds_email_autor = \"$ds_email_autor\", ".
             "ds_fonte = \"$ds_fonte\", ".
             "ds_link = \"$ds_link\", ".
             "dt_noticia = \"$dt_noticia\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_noticia= '$cd_noticia' ";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'noticias');
        mysql_query($sql) or die("Erro no banco de dados! - TABELA NOTICIAS");
        $saida = mysql_affected_rows();
        return $saida;
    }
}
