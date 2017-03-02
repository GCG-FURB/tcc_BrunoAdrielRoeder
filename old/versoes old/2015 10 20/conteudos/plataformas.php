<?php
  class Plataforma {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';               }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = '1';            }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';             }

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
          $this->alterarSituacaoAtivoPlataforma($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                 $opcao['descricao']= "Ativas";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                 $opcao['descricao']= "Inativas";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                 $opcao['descricao']= "Ativas/Inativas";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                               $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Plataforma\" title=\"Nova Plataforma\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">----------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }

    private function listarItens($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $mensagem = "Plataforma ";
      if ($ativas == 1)    {      $mensagem.= "Ativas ";         } elseif ($ativas == 0)    {       $mensagem.= "Inativas ";       }

      $itens = $this->selectPlataforma($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Plataforma:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_plataforma']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharPlataforma($it['cd_plataforma']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_plataforma']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_plataforma']."&acao=status\">";
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
    
    public function detalharPlataforma($cd_plataforma) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosPlataforma($cd_plataforma);
      
      $retorno = "";
      if ($dados['cd_usuario_cadastro'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
        $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      } else {
        $retorno.= "Cadastro automático<br />";
      }
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
     
    private function montarFormularioCadastro($link) {
      $cd_plataforma = "";
      $nm_plataforma = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Plataformas</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_plataforma, $nm_plataforma, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_plataforma) {
      $dados = $this->selectDadosPlataforma($cd_plataforma);

      $nm_plataforma = $dados['nm_plataforma'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Plataforma</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_plataforma, $nm_plataforma, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_plataforma, $nm_plataforma, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/cores.php';                                       $cor = new Cor();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_plataforma.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_plataforma', $cd_plataforma);
      $util->linhaUmCampoText(1, 'Plataforma: ', 'nm_plataforma', '150', '70', $nm_plataforma);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      if ($cd_plataforma > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_plataforma'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_plataforma = addslashes($_POST['cd_plataforma']);
      $nm_plataforma = $util->limparVariavel($_POST['nm_plataforma']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_plataforma > 0) {
        if ($this->alteraPlataforma($cd_plataforma, $nm_plataforma, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Plataforma editada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Plataforma, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->inserePlataforma($nm_plataforma, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Plataforma cadastrada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Plataforma!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoPlataforma($cd_plataforma) {
      $dados = $this->selectDadosPlataforma($cd_plataforma);

      $nm_plataforma = $dados['nm_plataforma'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraPlataforma($cd_plataforma, $nm_plataforma, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Plataforma alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da Plataforma!</p>\n";
      }
    }
            
                                         /*
    private function existeNumeroOrdem($nr_ordem) {
      $dados = $this->selectNumeroOrdemPlataforma($nr_ordem);
      if ($dados['cd_plataforma'] != '') {
        return true;
      } else {
        return false;
      }
    }
    

                                    */
    public function retornaSeletorPlataforma($cd_plataforma) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectPlataforma('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione uma Plataforma';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_plataforma'];          $opcao[]= $it['nm_plataforma'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Plataformas: ', 'cd_plataforma', $cd_plataforma, $opcoes);      
    } 
                                                               /*
    public function retornaSeletorOutrasPlataforma($cd_plataforma) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectPlataforma('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione umo Plataforma';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        if ($cd_plataforma != $it['cd_plataforma']) { 
          $opcao= array();      $opcao[] = $it['cd_plataforma'];          $opcao[]= $it['nm_plataforma'];                   $opcoes[]= $opcao;
        }
      }
      $util->linhaSeletor('Plataformas: ', 'cd_plataforma', $cd_plataforma, $opcoes);      
    }  
    
    private function tratarConteudoPlataforma($plataforma) {
      $conteudo = array();
      
      $string = $plataforma['nm_plataforma']; 
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
        $tamanho_letra = '22';              $tamanho_quebra = '9';
      } elseif ($maior_string <= 10) {
        $tamanho_letra = '18';              $tamanho_quebra = '11';
      } elseif ($maior_string <= 15) {
        $tamanho_letra = '14';              $tamanho_quebra = '11';
      } elseif ($maior_string <= 20) {
        $tamanho_letra = '12';              $tamanho_quebra = '12';
      }
      
      if ($quebras == '0') {
        $nome = "<label style=\"font-size: ".$tamanho_quebra."px;\"><br /><br /><br /></label>".$nome;      
      }

      $conteudo['identificador'] = $plataforma['cd_plataforma'];
      
      $conteudo['descricao'] = "<label style=\"font-size: ".$tamanho_letra."px;\" onMouseOver=\"JavaScript:this.style.cursor='pointer'\">".$nome."</label>";
      $conteudo['nome'] = $plataforma['nm_plataforma'];
      return $conteudo;
    } 
    
//*********************EXIBICAO PUBLICA*****************************************
    public function controleExibicaoPublica($pagina, $lista_paginas) {

      if (isset($lista_paginas[1])) {
      
      } else {
        $this->relacionarPlataforma();
      }    
    }
    
    public function relacionarPlataforma() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $areas = $this->selectPlataforma('1');
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
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$_SESSION['life_link_completo']."areas-conhecimento/pesquisar\">\n";
    
      echo "  <table class=\"tabConteudo\">\n";
      echo "    <thead>\n";
      echo "    <tr>\n";
      echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Plataforma</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "        <td class=\"celConteudoCabecalho\"	style=\"background-color: transparent;\"></td>\n";
      echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Plataforma</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "      </tr>\n";
      echo "    </thead>\n"; 
      for ($i=0; $i<$qtd; $i++) {
        $a = $lista_1[$i];
        echo "    <tr>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
        $campo = 'cd_plataforma_'.$a['cd_plataforma'];
        echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
        foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
          if ($it == $a['cd_plataforma']) {           echo " checked=\"checked\" ";      }
        } 
        echo " value=\"".$a['cd_plataforma']."\" class=\"fontConteudo\" />\n";        
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
        echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorPlataforma\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Plataforma ".$a['nm_plataforma']."\" title=\"Plataforma ".$a['nm_plataforma']."\" border=\"0\"></div>\n";
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_plataforma']."</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">nr</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:16%;\"></td>\n";
        if (isset($lista_2[$i])) {
          $a = $lista_2[$i];
          echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
          $campo = 'cd_plataforma_'.$a['cd_plataforma'];
          echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
          foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
            if ($it == $a['cd_plataforma']) {           echo " checked=\"checked\" ";      }
          } 
          echo " value=\"".$a['cd_plataforma']."\" class=\"fontConteudo\" />\n";        
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
          echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorPlataforma\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Plataforma ".$a['nm_plataforma']."\" title=\"Plataforma ".$a['nm_plataforma']."\" border=\"0\"></div>\n";
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_plataforma']."</td>\n";
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
      echo "  		    <input type=\"submit\" class=\"botaoGrande\" value=\"Pesquisar\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      
      echo "  </table>\n";

      echo "  </form>\n";  
    }                   
    
    public function retornaChamadaPlataformaCapa() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
    
      $cores = $cor->retornaCoresNaoUtilizadas();
      $cores_sorteadas = array();
      
      $areas = $this->selectPlataforma('1');
      $qtd_areas = count($areas);
      $areas_sorteadas = array();
      
      echo "    <div class=\"divConteudoPlataforma\">\n";
      echo "      <div class=\"divConteudoPlataformaAcesso\">\n";
      echo "        <div id=\"barra_areas_conhecimento\" class=\"divConteudoPlataformaAcessoChamada\">\n";
      if (!isset($_SESSION['life_codigo_areas_conhecimento_capa'])) {
        $_SESSION['life_codigo_areas_conhecimento_capa'] = $itens = array();
      }                                                                
      echo "          <p class=\"fontChamadaRelacaoPlataforma\">";
      $tamanho = 0;
      $limite = $conf->retornaNumeroLimiteCaracteresRelacaoChamadaPlataformaCapaSite();
      $concatenar = true;
      foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
        $dados = $this->selectDadosPlataforma($it);
        $tamanho_nome = strlen($dados['nm_plataforma']);
        $tamanho += $tamanho_nome + 4;
        if ($tamanho < $limite) {
          echo $dados['nm_plataforma']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoPlataformaLink\" alt=\"Retirar o Plataforma ".$dados['nm_plataforma']." da Relação de Itens à serem Pesquisados\" title=\"Retirar o Plataforma ".$dados['nm_plataforma']." da Relação de Itens à serem Pesquisados\">[x]</a>&nbsp;&nbsp;";
        } else {     
          if ($concatenar) {
            echo "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoPlataformaLink\" alt=\"Exibir Relação Completa de Itens à serem Pesquisados\" title=\"Exibir Relação Completa de Itens à serem Pesquisados\">[+]</a> ";
            $concatenar = false;
          }
        }
      }
      echo "          </p>\n";    
      echo "        </div>\n";
      echo "        <div class=\"divConteudoPlataformaAcessoAcao\">\n";
      echo "        <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento/pesquisar\"><img src=\"".$_SESSION['life_link_completo']."icones/selecionar_materiais.png\" alt=\"Pesquisar Conteúdos das Plataformas Selecionadas\" title=\"Pesquisar Conteúdos das Plataformas Selecionadas\" border=\"0\"></a>\n";
      echo "        </div>\n";
      echo "      </div>\n";
      echo "      <div class=\"divConteudoPlataformaContorno\">\n";
      echo "        <div class=\"divConteudoPlataformaCentro\">\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemAbreTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemFechaTopo\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemAbreLinha1\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=1; $i<=6; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoPlataformaItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemFechaLinha1\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemAbreLinha2\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=7; $i<=12; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoPlataformaItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemFechaLinha2\" style=\"background-color:".$cor.";\"></div>\n";      
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemAbreLinha3\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=13; $i<=17; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoPlataformaItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      if ($qtd_areas > 18) {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "          <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\"  alt=\"Listar Todas as Plataformas\" title=\"Listar Todas as Plataformas\" class=\"fontChamadaPlataforma\">\n";
        echo "            <div class=\"divConteudoPlataformaItem\" style=\"background-color:".$cor.";\"><label style=\"font-size: 16px;\"><br />Todas as<br />Áreas do<br />Conhecimento</label></div>\n";
        echo "          </a>\n";                                                                                                                 
      } elseif ($qtd_areas == 18) {
        $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
        $this->retornaElementoArea($area);
      } else {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "            <div class=\"divConteudoPlataformaItem\" style=\"background-color:".$cor.";\"></div>\n";
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemFechaLinha3\" style=\"background-color:".$cor.";\"></div>\n";         
                     
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemAbreBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoPlataformaItemFechaBase\" style=\"background-color:".$cor.";\"></div>\n";                         
      
      echo "        </div>\n";
      echo "      </div>\n";
      echo "    </div>\n"; 
      
      echo "    <div class=\"divTodosConteudoPlataforma\">\n";
      echo "      <a href=\"\"><img src=\"".$_SESSION['life_link_completo']."icones/todas_areas.png\" alt=\"Relacionar todas as Plataformas\" title=\"Relacionar todas as Plataformas\" border=\"0\"></a>\n";
      echo "    </div>\n";    
    }

    public function retornaElementoArea($area) {
      $conteudo = $this->tratarConteudoPlataforma($area);
      echo "          <a href=\"#\"  alt=\"Incluir o Plataforma ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" title=\"Incluir o Plataforma ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" class=\"fontChamadaPlataforma\" onClick=\"incluirPlataformaRelacao('".$conteudo['identificador']."');\">\n";
      echo "            <div class=\"divConteudoPlataformaItem\" style=\"background-color:".$area['ds_cor'].";\">\n";
      echo "              ".$conteudo['descricao']."\n";
      echo "            </div>\n";
      echo "          </a>\n";
    }
       
                                                                 */
//**************BANCO DE DADOS**************************************************    
    public function selectPlataforma($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_plataformas ".
              "WHERE cd_plataforma > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_plataforma ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORMA!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
       
    public function selectDadosPlataforma($cd_plataforma) {
      $sql  = "SELECT * ".
              "FROM life_plataformas ".
              "WHERE cd_plataforma = '$cd_plataforma' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORMA!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function inserePlataforma($nm_plataforma, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_plataformas ".
             "(nm_plataforma, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_plataforma\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'plataformas');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORMA!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraPlataforma($cd_plataforma, $nm_plataforma, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_plataformas SET ".
             "nm_plataforma = \"$nm_plataforma\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_plataforma= '$cd_plataforma' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'plataformas');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA PLATAFORMA!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>