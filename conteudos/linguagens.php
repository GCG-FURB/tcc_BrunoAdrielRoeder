<?php
class Linguagem
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
            $ativas = '1';
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
                $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas;
                $this->montarFormularioCadastro($link);
                break;

            case "editar":
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
               
            case "status":
                $this->alterarSituacaoAtivoLinguagem($codigo);
                $this->listarAcoes($secao, $subsecao, $item, $ativas);
                $this->listarItens($secao, $subsecao, $item, $ativas);
                break;
        }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas)
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
        }        $opcao['descricao']= "Ativos";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "2";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";
        if ($ativas == '0') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }        $opcao['descricao']= "Inativos";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "3";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";
        if ($ativas == '2') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }        $opcao['descricao']= "Ativos/Inativos";
        $opcoes_1[]= $opcao;
        foreach ($opcoes_1 as $op) {
            $nome = 'comandos_filtros_1_'.$op['indice'];
            $util->campoHidden($nome, $op['link']);
        }
      
        include 'js/js_navegacao.js';
        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo idioma\" title=\"Novo idioma\" border=\"0\"></a> \n";
        echo "  <select name=\"comandos_filtros_1\" id=\"comandos_filtros_1\" class=\"fontComandosFiltros\" onChange=\"navegar(1);\" alt=\"Filtro de status\" title=\"Filtro de status\">\n";
        foreach ($opcoes_1 as $op) {
            echo "    <option value=\"".$op['indice']."\" ".$op['selecionado'].">".$op['descricao']."&nbsp;</option>\n";
        }
        echo "  </select>\n";
        echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas)
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();

        $mensagem = "Idiomas ";
        $itens = $this->selectLinguagem($ativas);
      
        echo "<h2>".$mensagem."</h2>\n";
        echo "  <table class=\"tabConteudo\">\n";
        $style = "linhaOn";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">Idioma</td>\n";
        echo "      <td class=\"celConteudo\">Ações</td>\n";
        echo "    </tr>\n";
        foreach ($itens as $it) {
            $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
            echo "    <tr class=\"".$style."\">\n";
            echo "      <td class=\"celConteudo\">".$it['nm_linguagem']."</td>\n";
            echo "      <td class=\"celConteudo\">\n";
            echo "        <a href=\"#\" class=\"dcontexto\">\n";
            echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
            echo "          <span class=\"fontdDetalhar\">\n";
            echo $this->detalharLinguagem($it['cd_linguagem']);
            echo "        </span></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_linguagem']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_linguagem']."&acao=status\">";
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
    
    public function detalharLinguagem($cd_linguagem)
    {
        require_once 'usuarios/usuarios.php';
        $usu = new Usuario();
        require_once 'includes/data_hora.php';
        $dh = new DataHora();
      
        $dados = $this->selectDadosLinguagem($cd_linguagem);
      
        $retorno = "";
        if ($dados['cd_usuario_cadastro'] != '') {
            $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
            $retorno.= "Cadastrado por ".$dados_usuario['nm_usuario']."<br />\n";
            $retorno.= "Data do cadastro ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
        } else {
            $retorno.= "Cadastro automático<br />";
        }
        if ($dados['cd_usuario_ultima_atualizacao'] != '') {
            $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
            $retorno.= "Última atualização por ".$dados_usuario['nm_usuario']."<br />\n";
            $retorno.= "Data do última atualização ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
        }
        return $retorno;
    }
     
    private function montarFormularioCadastro($link)
    {
        $cd_linguagem = "";
        $nm_linguagem = "";
        $eh_ativo = "1";

        $_SESSION['life_edicao']= 1;
        echo "  <h2>Cadastro de idiomas</h2>\n";
        $this->imprimeFormularioCadastro($link, $cd_linguagem, $nm_linguagem, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_linguagem)
    {
        $dados = $this->selectDadosLinguagem($cd_linguagem);

        $nm_linguagem = $dados['nm_linguagem'];
        $eh_ativo = $dados['eh_ativo'];
      
        $_SESSION['life_edicao']= 1;
        echo "  <h2>Edição de idioma</h2>\n";
        $this->imprimeFormularioCadastro($link, $cd_linguagem, $nm_linguagem, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_linguagem, $nm_linguagem, $eh_ativo)
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();
        require_once 'conteudos/cores.php';
        $cor = new Cor();

        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "</p>\n";

        include "js/js_cadastro_linguagem.js";
        echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');
        $util->campoHidden('cd_linguagem', $cd_linguagem);
        $util->linhaUmCampoText(1, 'Idioma ', 'nm_linguagem', '150', '100', $nm_linguagem);

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
        $util->posicionarCursor('cadastro', 'nm_linguagem');
    }
    
    public function salvarCadastroAlteracao()
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();

        $cd_linguagem = addslashes($_POST['cd_linguagem']);
        $nm_linguagem = $util->limparVariavel($_POST['nm_linguagem']);
        $eh_ativo = addslashes($_POST['eh_ativo']);

        if ($cd_linguagem > 0) {
            if ($this->alteraLinguagem($cd_linguagem, $nm_linguagem, $eh_ativo)) {
                echo "<p class=\"fontConteudoSucesso\">Idioma editado com sucesso!</p>\n";
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas na edição do idioma, ou nenhuma informação alterada!</p>\n";
            }
        } else {
            if ($this->insereLinguagem($nm_linguagem, $eh_ativo)) {
                echo "<p class=\"fontConteudoSucesso\">Idioma cadastrado com sucesso!</p>\n";
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do idioma!</p>\n";
            }
        }
    }


    private function alterarSituacaoAtivoLinguagem($cd_linguagem)
    {
        $dados = $this->selectDadosLinguagem($cd_linguagem);

        $nm_linguagem = $dados['nm_linguagem'];
        $eh_ativo = $dados['eh_ativo'];

        if ($eh_ativo == 1) {
            $eh_ativo = 0;
        } else {
            $eh_ativo = 1;
        }

        if ($this->alteraLinguagem($cd_linguagem, $nm_linguagem, $eh_ativo)) {
            echo "<p class=\"fontConteudoSucesso\">Status do idioma alterado com sucesso!</p>\n";
        } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do idioma!</p>\n";
        }
    }

    public function retornaSeletorLinguagem($cd_linguagem, $campo, $tamanho, $exibir_ajuda, $descricao, $denominacao)
    {
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
      
        $itens = $this->selectLinguagem('1');
      
        $opcoes= array();
        $opcao= array();
        $opcao[] = '0';
        $opcao[]= $descricao;
        $opcoes[]= $opcao;
        foreach ($itens as $it) {
            $opcao= array();
            $opcao[] = $it['cd_linguagem'];
            $opcao[]= $it['nm_linguagem'];
            $opcoes[]= $opcao;
        }
        $util->linhaSeletorHint($descricao, $denominacao, $campo, $cd_linguagem, $opcoes, $tamanho, false, $exibir_ajuda);
    }
    
    public function retornaDados($cd_linguagem, $descricao)
    {
        $dados = $this->selectDadosLinguagem($cd_linguagem);
      
        return "<b>".$descricao."</b> ".$dados['nm_linguagem'];
    }

    public function retornaSeletorLinguagemFiltro($cd_linguagem, $nome, $tamanho, $exibir_ajuda, $descricao, $ajuda)
    {
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();

        $itens = $this->selectLinguagem('1');

        echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" class=\"fontConteudoCampoSeletorHintFiltro\" ";
        if ($exibir_ajuda == '1') {
            echo "title=\"".$descricao."\" ";
        } else {
            echo "title=\"".$ajuda."\" ";
        }
        echo "tabindex=\"1\">\n";
        echo "  			    <option ";
        if ($cd_linguagem == '') {
            echo " selected ";
        }
        echo " value=\"0\">$descricao</option>\n";
        foreach ($itens as $it) {
            echo "  			    <option ";
            if ($it['cd_linguagem'] == $cd_linguagem) {
                echo " selected ";
            }
            echo " value=\"".$it['cd_linguagem']."\">".$it['nm_linguagem']."</option>\n";
        }
        echo "          </select>\n";
        if ($exibir_ajuda == '1') {
            echo "        <a href=\"#\" class=\"dcontexto\">\n";
            echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
            echo "          <span class=\"fontdDetalhar\">\n";
            echo "            Selecione o idioma desejado para pesquisar por objetos de aprendizagem.\n";
            echo "          </span>\n";
            echo "        </a>\n";
        } else {
            echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
        }
    }

//**************BANCO DE DADOS**************************************************    
    public function selectLinguagem($eh_ativo)
    {
        $sql  = "SELECT * ".
              "FROM life_linguagens ".
              "WHERE cd_linguagem > 0 ";
        if ($eh_ativo != 2) {
            $sql.= "AND eh_ativo = '$eh_ativo' ";
        }
        $sql.= "ORDER BY nm_linguagem ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados - TABELA LINGUAGEM!");
        $dados = array();
        while ($linha = mysql_fetch_assoc($result_id)) {
            $dados[] = $linha;
        }
        return $dados;
    }
       
    public function selectDadosLinguagem($cd_linguagem)
    {
        $sql  = "SELECT * ".
              "FROM life_linguagens ".
              "WHERE cd_linguagem = '$cd_linguagem' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados - TABELA LINGUAGEM!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados;
    }
    
    public function insereLinguagem($nm_linguagem, $eh_ativo)
    {
        $cd_usuario_cadastro = $_SESSION['life_codigo'];
        $dt_cadastro = date('Y-m-d');
        $sql = "INSERT INTO life_linguagens ".
             "(nm_linguagem, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_linguagem\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'linguagens');
        mysql_query($sql) or die("Erro no banco de dados - TABELA LINGUAGEM!");
        $saida = mysql_affected_rows();
        return $saida;
    }

    public function alteraLinguagem($cd_linguagem, $nm_linguagem, $eh_ativo)
    {
        $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
        $dt_ultima_atualizacao = date('Y-m-d');
        $sql = "UPDATE life_linguagens SET ".
             "nm_linguagem = \"$nm_linguagem\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_linguagem= '$cd_linguagem' ";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'linguagens');
        mysql_query($sql) or die("Erro no banco de dados - TABELA LINGUAGEM!");
        $saida = mysql_affected_rows();
        return $saida;
    }
}
