<?php
class AreaConhecimento
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
                $this->alterarSituacaoAtivoAreaConhecimento($codigo);
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
        }        $opcao['descricao']= "Ativas";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "2";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";
        if ($ativas == '0') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }        $opcao['descricao']= "Inativas";
        $opcoes_1[]= $opcao;
        $opcao= array();
        $opcao['indice']= "3";
        $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";
        if ($ativas == '2') {
            $opcao['selecionado'] = ' selected=\"selected\" ';
        } else {
            $opcao['selecionado'] = '';
        }        $opcao['descricao']= "Ativas/Inativas";
        $opcoes_1[]= $opcao;
        foreach ($opcoes_1 as $op) {
            $nome = 'comandos_filtros_1_'.$op['indice'];
            $util->campoHidden($nome, $op['link']);
        }
      
        include 'js/js_navegacao.js';
        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova área de conhecimento\" title=\"Nova área de conhecimento\" border=\"0\"></a> \n";
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

        $mensagem = "Área de conhecimento ";

        $itens = $this->selectAreasConhecimento($ativas);
      
        echo "<h2>".$mensagem."</h2>\n";
        echo "  <table class=\"tabConteudo\">\n";
        $style = "linhaOn";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">Área de conhecimento</td>\n";
        echo "      <td class=\"celConteudo\" colspan=\"2\">Cor</td>\n";
        echo "      <td class=\"celConteudo\">Ações</td>\n";
        echo "    </tr>\n";
        foreach ($itens as $it) {
            $style = ($style!="linhaOf")?('linhaOf'):('linhaOn');
            echo "    <tr class=\"".$style."\">\n";
            echo "      <td class=\"celConteudo\">".$it['nm_area_conhecimento']."</td>\n";
            echo "      <td class=\"celConteudo\" style=\"background-color:".$it['ds_cor'].";width:25px;\"> </td>\n";
            echo "      <td class=\"celConteudo\">".$it['nm_cor']."</td>\n";
            echo "      <td class=\"celConteudo\">\n";
            echo "        <a href=\"#\" class=\"dcontexto\">\n";
            echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
            echo "          <span class=\"fontdDetalhar\">\n";
            echo $this->detalharAreaConhecimento($it['cd_area_conhecimento']);
            echo "        </span></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_area_conhecimento']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
            echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_area_conhecimento']."&acao=status\">";
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
    
    public function detalharAreaConhecimento($cd_area_conhecimento)
    {
        require_once 'usuarios/usuarios.php';
        $usu = new Usuario();
        require_once 'includes/data_hora.php';
        $dh = new DataHora();
      
        $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);
      
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
        $cd_area_conhecimento = "";
        $nm_area_conhecimento = "";
        $ds_area_conhecimento = "";
        $cd_cor = "";
        $eh_ativo = "1";

        $_SESSION['life_edicao']= 1;
        echo "  <h2>Cadastro de área de conhecimento</h2>\n";
        $this->imprimeFormularioCadastro($link, $cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_area_conhecimento)
    {
        $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);

        $nm_area_conhecimento = $dados['nm_area_conhecimento'];
        $ds_area_conhecimento = $dados['ds_area_conhecimento'];
        $cd_cor = $dados['cd_cor'];
        $eh_ativo = $dados['eh_ativo'];
      
        $_SESSION['life_edicao']= 1;
        echo "  <h2>Edição de área de conhecimento</h2>\n";
        $this->imprimeFormularioCadastro($link, $cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();
        require_once 'conteudos/cores.php';
        $cor = new Cor();

        echo "<p class=\"fontComandosFiltros\">\n";
        echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
        echo "</p>\n";

        include "js/js_cadastro_area_conhecimento.js";
        echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');
        $util->campoHidden('cd_area_conhecimento', $cd_area_conhecimento);
        $util->linhaUmCampoText(1, 'Área de conhecimento ', 'nm_area_conhecimento', '150', '100', $nm_area_conhecimento);
        $util->linhaTexto(0, 'Descrição ', 'ds_area_conhecimento', $ds_area_conhecimento, '5', '100');

        $cor->retornaSeletorCores($cd_cor);

        $opcoes= array();
        $opcao= array();
        $opcao[]= '1';
        $opcao[]= 'Sim';
        $opcoes[]= $opcao;
        $opcao= array();
        $opcao[]= '0';
        $opcao[]= 'Não';
        $opcoes[]= $opcao;
        $util->linhaSeletor('É ativa ', 'eh_ativo', $eh_ativo, $opcoes, '100');
        $util->linhaBotao('Salvar', "valida(cadastro);");
        echo "    </table>\n";
        echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
        echo "  </form>\n";
        $util->posicionarCursor('cadastro', 'nm_area_conhecimento');
    }
    
    public function salvarCadastroAlteracao()
    {
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();

        $cd_area_conhecimento = addslashes($_POST['cd_area_conhecimento']);
        $nm_area_conhecimento = $util->limparVariavel($_POST['nm_area_conhecimento']);
        $ds_area_conhecimento = $util->limparVariavel($_POST['ds_area_conhecimento']);
        $cd_cor = addslashes($_POST['cd_cor']);
        $eh_ativo = addslashes($_POST['eh_ativo']);

        if ($cd_area_conhecimento > 0) {
            if ($this->alteraAreaConhecimento($cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)) {
                echo "<p class=\"fontConteudoSucesso\">Área de conhecimento editada com sucesso!</p>\n";
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas na edição da área de conhecimento, ou nenhuma informação alterada!</p>\n";
            }
        } else {
            if ($this->insereAreaConhecimento($nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)) {
                echo "<p class=\"fontConteudoSucesso\">Área de conhecimento cadastrada com sucesso!</p>\n";
            } else {
                echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da área de conhecimento!</p>\n";
            }
        }
    }


    private function alterarSituacaoAtivoAreaConhecimento($cd_area_conhecimento)
    {
        $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);

        $nm_area_conhecimento = $dados['nm_area_conhecimento'];
        $ds_area_conhecimento = $dados['ds_area_conhecimento'];
        $cd_cor = $dados['cd_cor'];
        $eh_ativo = $dados['eh_ativo'];

        if ($eh_ativo == 1) {
            $eh_ativo = 0;
        } else {
            $eh_ativo = 1;
        }

        if ($this->alteraAreaConhecimento($cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)) {
            echo "<p class=\"fontConteudoSucesso\">Status da área de conhecimento alterado com sucesso!</p>\n";
        } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da área de conhecimento!</p>\n";
        }
    }
            

    public function retornaSeletorAreasConhecimento($cd_area_conhecimento)
    {
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
      
        $itens = $this->selectAreasConhecimento('1');
      
        $opcoes= array();
        $opcao= array();
        $opcao[] = '0';
        $opcao[]= 'Selecione uma área de conhecimento';
        $opcoes[]= $opcao;
        foreach ($itens as $it) {
            $opcao= array();
            $opcao[] = $it['cd_area_conhecimento'];
            $opcao[]= $it['nm_area_conhecimento'];
            $opcoes[]= $opcao;
        }
        $util->linhaSeletor('Área de conhecimento ', 'cd_area_conhecimento', $cd_area_conhecimento, $opcoes, '100');
    }
    
    public function retornaDados($cd_area_conhecimento, $cd_general, $descricao)
    {
        require_once 'conteudos/sub_areas_conhecimento_general.php';
        $sacg = new SubAreaConhecimentoGeneral();
      
        $dados = $this->selectDadosAreaConhecimento($cd_area_conhecimento);

        $retorno = "<b>".$descricao."</b> ".$dados['nm_area_conhecimento'];
        $retorno.= $sacg->retornaDados($cd_area_conhecimento, $cd_general);
  
        return $retorno;
    }
    
    public function retornaSeletorOutrasAreasConhecimento($cd_area_conhecimento)
    {
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
      
        $itens = $this->selectAreasConhecimento('1');
      
        $opcoes= array();
        $opcao= array();
        $opcao[] = '0';
        $opcao[]= 'Selecione uma área de conhecimento';
        $opcoes[]= $opcao;
        foreach ($itens as $it) {
            if ($cd_area_conhecimento != $it['cd_area_conhecimento']) {
                $opcao= array();
                $opcao[] = $it['cd_area_conhecimento'];
                $opcao[]= $it['nm_area_conhecimento'];
                $opcoes[]= $opcao;
            }
        }
        $util->linhaSeletor('Área de conhecimento ', 'cd_area_conhecimento', $cd_area_conhecimento, $opcoes, '100');
    }
    
    private function tratarConteudoAreaConhecimento($area_conhecimento)
    {
        $conteudo = array();
      
        $string = $area_conhecimento['nm_area_conhecimento'];
        $tamanho = strlen($string);
        $maior_string = '0';
        $posicao = 0;
        $nome = '';
        $quebras = 0;
        for ($i=0; $i<$tamanho; $i++) {
            $posicao += 1;
            if ($string[$i] == '') {
                if ($maior_string < $posicao) {
                    $maior_string = $posicao;
                    $posicao = 0;
                }
                $nome.= "<br \>";
                $quebras += 1;
            } else {
                $nome.= $string[$i];
            }
        }
        if ($maior_string == 0) {
            $maior_string = $tamanho;
        }
        if ($maior_string <= 5) {
            $tamanho_letra = '22';
            $tamanho_quebra = '9';
        } elseif ($maior_string <= 10) {
            $tamanho_letra = '18';
            $tamanho_quebra = '11';
        } elseif ($maior_string <= 15) {
            $tamanho_letra = '14';
            $tamanho_quebra = '11';
        } elseif ($maior_string <= 20) {
            $tamanho_letra = '12';
            $tamanho_quebra = '12';
        }
      
        if ($quebras == '0') {
            $nome = "<label style=\"font-size: ".$tamanho_quebra."px;\"><br /><br /><br /></label>".$nome;
        }

        $conteudo['identificador'] = $area_conhecimento['cd_area_conhecimento'];
      
        $conteudo['descricao'] = "<label style=\"font-size: ".$tamanho_letra."px;\" onMouseOver=\"JavaScript:this.style.cursor='pointer'\">".$nome."</label>";
        $conteudo['nome'] = $area_conhecimento['nm_area_conhecimento'];
        return $conteudo;
    }
    
    
    public function retornaSeletorAreasConhecimentoObjetoAprendizagem($cd_coverage, $campo, $tamanho, $exibir_ajuda, $cd_general, $descricao, $denominacao)
    {
        $cd_area_conhecimento = $cd_coverage;
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
      
        $itens = $this->selectAreasConhecimento('1');
      
        $opcoes= array();
        $opcao= array();
        $opcao[] = '0';
        $opcao[]= $descricao;
        $opcoes[]= $opcao;
        foreach ($itens as $it) {
            $opcao= array();
            $opcao[] = $it['cd_area_conhecimento'];
            $opcao[]= $it['nm_area_conhecimento'];
            $opcoes[]= $opcao;
        }
        $util->linhaSeletorAcaoHint($descricao, $denominacao, $campo, $cd_area_conhecimento, $opcoes, $tamanho, false, $exibir_ajuda, " onChange=\"atualizarCampoSubAreasConhecimento();\" ");
      
        echo "      <tr>\n";
        echo "		    <td class=\"celConteudoCentralizado\" colspan=\"2\" id=\"celula_sub_areas_conhecimento\">\n";
        if ($cd_area_conhecimento != 0) {
            require_once 'conteudos/sub_areas_conhecimento.php';
            $sac = new SubAreaConhecimento();
            echo $sac->retornaCadastroSubAreasConhecimentoObjetoAprendizagem($cd_area_conhecimento, $tamanho, $cd_general);
        } else {
            echo "          <input type=\"text\" maxlength=\"0\" name=\"cd_sub_area_conhecimento\" id=\"cd_sub_area_conhecimento\" value=\"\" alt=\"Sub área de conhecimento\n\n- Antes selecione ".$descricao."\" title=\"Sub área de conhecimento\n\n- Antes selecione ".$descricao."\" class=\"fontConteudoCampoTextHint\" placeholder=\"Sub Área do conhecimento\" tabindex=\"1\" style=\"height: 120px; width:100%;\" />\n";
            $ajuda = "\n\n- Campo do Tipo Texto com capacidade para até 150 caracteres\n".
                 "- Apenas utilizar este campo se a Sub área de conhecimento desejada não estiver cadastrada.\n";
            echo "          <input type=\"text\" maxlength=\"150\" name=\"ds_sub_area_conhecimento\" id=\"ds_sub_area_conhecimento\" value=\"\" style=\"width:100%;\" alt=\"Sub Área de conhecimento".$ajuda."\" title=\"Sub área de conhecimento".$ajuda."\" class=\"fontConteudoCampoTextHint\" placeholder=\"Sub área de conhecimento - descritivo\" tabindex=\"1\"/>\n";
        }
        echo "        </td>\n";
        echo "      </tr>\n";
    }


    public function retornaSeletorAreasConhecimentoFiltro($cd_coverage, $nome, $tamanho, $exibir_ajuda, $descricao, $ajuda)
    {
        $cd_area_conhecimento = $cd_coverage;
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();

        $itens = $this->selectAreasConhecimento('1');

        echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" class=\"fontConteudoCampoSeletorHintFiltro\" ";
        if ($exibir_ajuda == '1') {
            echo "title=\"".$descricao."\" ";
        } else {
            echo "title=\"".$ajuda."\" ";
        }
        echo "tabindex=\"1\">\n";
        echo "  			    <option ";
        if ($cd_area_conhecimento == '') {
            echo " selected ";
        }
        echo " value=\"0\">$descricao</option>\n";
        foreach ($itens as $it) {
            echo "  			    <option ";
            if ($it['cd_area_conhecimento'] == $cd_area_conhecimento) {
                echo " selected ";
            }
            echo " value=\"".$it['cd_area_conhecimento']."\">".$it['nm_area_conhecimento']."</option>\n";
        }
        echo "          </select>\n";
        if ($exibir_ajuda == '1') {
            echo "        <a href=\"#\" class=\"dcontexto\">\n";
            echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\" border=\"0\" title=\"Ajuda\" alt=\"Ajuda\">\n";
            echo "          <span class=\"fontdDetalhar\">\n";
            echo "            Selecione a área de conhecimento desejada para pesquisar por Objetos de Aprendizagem.\n";
            echo "          </span>\n";
            echo "        </a>\n";
        } else {
            echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\" border=\"0\" title=\"Sem Ajuda Disponível\" alt=\"Sem Ajuda Disponível\">\n";
        }
    }
    
    
//*********************EXIBICAO PUBLICA*****************************************
    public function controleExibicaoPublica($pagina, $lista_paginas)
    {

        if (isset($lista_paginas[1])) {
        } else {
            $this->relacionarAreasConhecimento();
        }
    }
    
    public function relacionarAreasConhecimento()
    {
        require_once 'conteudos/cores.php';
        $cor = new Cor();
        require_once 'includes/utilitarios.php';
        $util = new Utilitario();
        require_once 'includes/configuracoes.php';
        $conf = new Configuracao();

        $areas = $this->selectAreasConhecimento('1');
        $lista_1 = array();
        $lista_2 = array();
        $par = true;
        foreach ($areas as $a) {
            if ($par) {
                $lista_1[] = $a;
                $par = false;
            } else {
                $lista_2[] = $a;
                $par = true;
            }
        }
        $qtd = count($lista_1);
        echo "  <form method=\"POST\" name=\"cadastro\" id=\"cadastro\" action=\"".$_SESSION['life_link_completo']."areas-conhecimento/pesquisar\">\n";
        $util->campoHidden('eh_form', '1');

        echo "  <table class=\"tabConteudo\">\n";
        echo "    <thead>\n";
        echo "    <tr>\n";
        echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
        echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Área de conhecimento</td>\n";
        echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
        echo "        <td class=\"celConteudoCabecalho\"	style=\"background-color: transparent;\"></td>\n";
        echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
        echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Área de conhecimento</td>\n";
        echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
        echo "      </tr>\n";
        echo "    </thead>\n";
        for ($i=0; $i<$qtd; $i++) {
            $a = $lista_1[$i];
            echo "    <tr>\n";
            echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
            $campo = 'cd_area_conhecimento_'.$a['cd_area_conhecimento'];
            echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
            foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
                if ($it == $a['cd_area_conhecimento']) {
                    echo " checked=\"checked\" ";
                }
            }
            echo " value=\"".$a['cd_area_conhecimento']."\" class=\"fontConteudo\" />\n";
            echo "      </td>\n";
            echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
            echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorAreaConhecimento\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Área de conhecimento ".$a['nm_area_conhecimento']."\" title=\"Área de conhecimento ".$a['nm_area_conhecimento']."\" border=\"0\"></div>\n";
            echo "      </td>\n";
            echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_area_conhecimento']."</td>\n";
            echo "      <td class=\"celConteudo\" style=\"width:5%;\">nr</td>\n";
            echo "      <td class=\"celConteudo\" style=\"width:16%;\"></td>\n";
            if (isset($lista_2[$i])) {
                $a = $lista_2[$i];
                echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
                $campo = 'cd_area_conhecimento_'.$a['cd_area_conhecimento'];
                echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
                foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
                    if ($it == $a['cd_area_conhecimento']) {
                        echo " checked=\"checked\" ";
                    }
                }
                echo " value=\"".$a['cd_area_conhecimento']."\" class=\"fontConteudo\" />\n";
                echo "      </td>\n";
                echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
                echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorAreaConhecimento\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Área de conhecimento ".$a['nm_area_conhecimento']."\" title=\"Área de conhecimento ".$a['nm_area_conhecimento']."\" border=\"0\"></div>\n";
                echo "      </td>\n";
                echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_area_conhecimento']."</td>\n";
                echo "      <td class=\"celConteudo\" style=\"width:5%;\">nr</td>\n";
            } else {
                echo "      <td class=\"celConteudo\"></td>\n";
                echo "      <td class=\"celConteudo\"></td>\n";
                echo "      <td class=\"celConteudo\"></td>\n";
                echo "      <td class=\"celConteudo\"></td>\n";
            }
            echo "    </tr>\n";
        }
        echo "      <tr>\n";
        echo "        <td class=\"celConteudo\" colspan=\"9\" style=\"text-align:center;\">\n";
        echo "  		    <input type=\"button\" class=\"botaoGrande\" value=\"Pesquisar\" onClick=\"document.getElementById('cadastro').submit();\">\n";
        echo "        </td>\n";
        echo "      </tr>\n";
      
        echo "  </table>\n";

        echo "  </form>\n";
    }

//**************BANCO DE DADOS**************************************************    
    public function selectAreasConhecimento($eh_ativo)
    {
        $sql  = "SELECT ac.*, c.* ".
              "FROM life_areas_conhecimento ac, life_cores c ".
              "WHERE ac.cd_cor = c.cd_cor ";
        if ($eh_ativo != 2) {
            $sql.= "AND ac.eh_ativo = '$eh_ativo' ";
        }
        $sql.= "ORDER BY ac.nm_area_conhecimento ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO!");
        $dados = array();
        while ($linha = mysql_fetch_assoc($result_id)) {
            $dados[] = $linha;
        }
        return $dados;
    }

    public function selectCoresAreasConhecimento()
    {
        $sql  = "SELECT c.cd_cor ".
              "FROM life_areas_conhecimento ac, life_cores c ".
              "WHERE ac.cd_cor = c.cd_cor ".
              "AND ac.eh_ativo = '1' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO!");
        $dados = array();
        while ($linha = mysql_fetch_assoc($result_id)) {
            $dados[] = $linha;
        }
        return $dados;
    }
        
    public function selectDadosAreaConhecimento($cd_area_conhecimento)
    {
        $sql  = "SELECT * ".
              "FROM life_areas_conhecimento ".
              "WHERE cd_area_conhecimento = '$cd_area_conhecimento' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados;
    }

    public function selectDadosCorAreaConhecimento($cd_area_conhecimento)
    {
        $sql  = "SELECT c.ds_cor ".
              "FROM life_areas_conhecimento ac, life_cores c ".
              "WHERE ac.cd_cor = c.cd_cor ".
              "AND ac.cd_area_conhecimento = '$cd_area_conhecimento' ";
        $result_id = @mysql_query($sql) or die("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados;
    }

    public function insereAreaConhecimento($nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)
    {
        $cd_usuario_cadastro = $_SESSION['life_codigo'];
        $dt_cadastro = date('Y-m-d');
        $sql = "INSERT INTO life_areas_conhecimento ".
             "(nm_area_conhecimento, ds_area_conhecimento, cd_cor, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_area_conhecimento\", \"$ds_area_conhecimento\", \"$cd_cor\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'areas_conhecimento');
        mysql_query($sql) or die("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO!");
        $saida = mysql_affected_rows();
        return $saida;
    }

    public function alteraAreaConhecimento($cd_area_conhecimento, $nm_area_conhecimento, $ds_area_conhecimento, $cd_cor, $eh_ativo)
    {
        $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
        $dt_ultima_atualizacao = date('Y-m-d');
        $sql = "UPDATE life_areas_conhecimento SET ".
             "nm_area_conhecimento = \"$nm_area_conhecimento\", ".
             "ds_area_conhecimento = \"$ds_area_conhecimento\", ".
             "cd_cor = \"$cd_cor\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_area_conhecimento= '$cd_area_conhecimento' ";
        require_once 'includes/utilitarios.php';
        $util= new Utilitario();
        $util->gerarLog($sql, 'areas_conhecimento');
        mysql_query($sql) or die("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO!");
        $saida = mysql_affected_rows();
        return $saida;
    }
}
