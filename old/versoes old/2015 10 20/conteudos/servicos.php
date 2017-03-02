<?php
  class Servico {
    
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
          $this->alterarSituacaoAtivoServico($codigo);
          $this->listarAcoes($secao, $subsecao, $item, $ativas);
          $this->listarItens($secao, $subsecao, $item, $ativas);
        break;
        
      }
    }
   
    public function listarAcoes($secao, $subsecao, $item, $ativas) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $opcoes= array();

      $opcao= array();      $opcao['indice']= "1";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=1";                 $opcao['descricao']= "Ativos";                                            $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "2";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=0";                 $opcao['descricao']= "Inativos";                                          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "3";      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=2";                 $opcao['descricao']= "Ativos/Inativos";                                   $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                               $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Novo Serviço\" title=\"Novo Serviço\" border=\"0\"></a> \n";
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

      $mensagem = "Áreas de Conhecimento ";
      if ($ativas == 1)    {      $mensagem.= "Ativos ";         } elseif ($ativas == 0)    {       $mensagem.= "Inativos ";       }

      $itens = $this->selectServico($ativas);
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Serviço:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_servico']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharServico($it['cd_servico']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_servico']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_servico']."&acao=status\">";
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
    
    public function detalharServico($cd_servico) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosServico($cd_servico);
      
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
      $cd_servico = "";
      $nm_servico = "";
      $eh_ativo = "1";

      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Serviços</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_servico, $nm_servico, $eh_ativo);
    }
    
    private function montarFormularioEdicao($link, $cd_servico) {
      $dados = $this->selectDadosServico($cd_servico);

      $nm_servico = $dados['nm_servico'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Serviço</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_servico, $nm_servico, $eh_ativo);
    }
    
    public function imprimeFormularioCadastro($link, $cd_servico, $nm_servico, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/cores.php';                                       $cor = new Cor();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_servico.js";
      echo "  <form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return  valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_servico', $cd_servico);
      $util->linhaUmCampoText(1, 'Serviço: ', 'nm_servico', '150', '70', $nm_servico);

      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;
      $util->linhaSeletor('É Ativo: ', 'eh_ativo', $eh_ativo, $opcoes);
      if ($cd_servico > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_servico'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_servico = addslashes($_POST['cd_servico']);
      $nm_servico = $util->limparVariavel($_POST['nm_servico']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_servico > 0) {
        if ($this->alteraServico($cd_servico, $nm_servico, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Serviço editado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Serviço, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereServico($nm_servico, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Serviço cadastrado com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Serviço!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoServico($cd_servico) {
      $dados = $this->selectDadosServico($cd_servico);

      $nm_servico = $dados['nm_servico'];
      $eh_ativo = $dados['eh_ativo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraServico($cd_servico, $nm_servico, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Serviço alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status do Serviço!</p>\n";
      }
    }
            
                                         /*
    private function existeNumeroOrdem($nr_ordem) {
      $dados = $this->selectNumeroOrdemServico($nr_ordem);
      if ($dados['cd_servico'] != '') {
        return true;
      } else {
        return false;
      }
    }
    

                                    */
    public function retornaSeletorServico($cd_servico) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectServico('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione um Serviço';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_servico'];          $opcao[]= $it['nm_servico'];                   $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Serviços: ', 'cd_servico', $cd_servico, $opcoes);      
    } 
                                                               /*
    public function retornaSeletorOutrasServico($cd_servico) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectServico('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= 'Selecione umo Serviço';          $opcoes[]= $opcao;
      foreach ($itens as $it) {
        if ($cd_servico != $it['cd_servico']) { 
          $opcao= array();      $opcao[] = $it['cd_servico'];          $opcao[]= $it['nm_servico'];                   $opcoes[]= $opcao;
        }
      }
      $util->linhaSeletor('Serviços: ', 'cd_servico', $cd_servico, $opcoes);      
    }  
    
    private function tratarConteudoServico($servico) {
      $conteudo = array();
      
      $string = $servico['nm_servico']; 
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

      $conteudo['identificador'] = $servico['cd_servico'];
      
      $conteudo['descricao'] = "<label style=\"font-size: ".$tamanho_letra."px;\" onMouseOver=\"JavaScript:this.style.cursor='pointer'\">".$nome."</label>";
      $conteudo['nome'] = $servico['nm_servico'];
      return $conteudo;
    } 
    
//*********************EXIBICAO PUBLICA*****************************************
    public function controleExibicaoPublica($pagina, $lista_paginas) {

      if (isset($lista_paginas[1])) {
      
      } else {
        $this->relacionarServico();
      }    
    }
    
    public function relacionarServico() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $areas = $this->selectServico('1');
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
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Serviço</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "        <td class=\"celConteudoCabecalho\"	style=\"background-color: transparent;\"></td>\n";
      echo "        <td class=\"celConteudoCabecalho\">?</td>\n";
      echo "        <td class=\"celConteudoCabecalho\" colspan=\"2\">Serviço</td>\n";
      echo "        <td class=\"celConteudoCabecalho\">Itens</td>\n";
      echo "      </tr>\n";
      echo "    </thead>\n"; 
      for ($i=0; $i<$qtd; $i++) {
        $a = $lista_1[$i];
        echo "    <tr>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
        $campo = 'cd_servico_'.$a['cd_servico'];
        echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
        foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
          if ($it == $a['cd_servico']) {           echo " checked=\"checked\" ";      }
        } 
        echo " value=\"".$a['cd_servico']."\" class=\"fontConteudo\" />\n";        
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
        echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorServico\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Serviço ".$a['nm_servico']."\" title=\"Serviço ".$a['nm_servico']."\" border=\"0\"></div>\n";
        echo "      </td>\n";
        echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_servico']."</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:5%;\">nr</td>\n";
        echo "      <td class=\"celConteudo\" style=\"width:16%;\"></td>\n";
        if (isset($lista_2[$i])) {
          $a = $lista_2[$i];
          echo "      <td class=\"celConteudo\" style=\"width:5%;\">\n";
          $campo = 'cd_servico_'.$a['cd_servico'];
          echo "          <input type=\"checkbox\" name=\"".$campo."\" id=\"".$campo."\" ";
          foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
            if ($it == $a['cd_servico']) {           echo " checked=\"checked\" ";      }
          } 
          echo " value=\"".$a['cd_servico']."\" class=\"fontConteudo\" />\n";        
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"width:7%;\">\n";
          echo "        <div style=\"background-color:".$a['ds_cor'].";\" class=\"divLivroCorServico\"><img src=\"".$_SESSION['life_link_completo']."icones/livro.png\" alt=\"Serviço ".$a['nm_servico']."\" title=\"Serviço ".$a['nm_servico']."\" border=\"0\"></div>\n";
          echo "      </td>\n";
          echo "      <td class=\"celConteudo\" style=\"font-size:18px; font-weight:bolder; color:".$a['ds_cor']."; width:25%;\">".$a['nm_servico']."</td>\n";
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
    
    public function retornaChamadaServicoCapa() {
      require_once 'conteudos/cores.php';                                       $cor = new Cor();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
    
      $cores = $cor->retornaCoresNaoUtilizadas();
      $cores_sorteadas = array();
      
      $areas = $this->selectServico('1');
      $qtd_areas = count($areas);
      $areas_sorteadas = array();
      
      echo "    <div class=\"divConteudoServico\">\n";
      echo "      <div class=\"divConteudoServicoAcesso\">\n";
      echo "        <div id=\"barra_areas_conhecimento\" class=\"divConteudoServicoAcessoChamada\">\n";
      if (!isset($_SESSION['life_codigo_areas_conhecimento_capa'])) {
        $_SESSION['life_codigo_areas_conhecimento_capa'] = $itens = array();
      }                                                                
      echo "          <p class=\"fontChamadaRelacaoServico\">";
      $tamanho = 0;
      $limite = $conf->retornaNumeroLimiteCaracteresRelacaoChamadaServicoCapaSite();
      $concatenar = true;
      foreach ($_SESSION['life_codigo_areas_conhecimento_capa'] as $it) {
        $dados = $this->selectDadosServico($it);
        $tamanho_nome = strlen($dados['nm_servico']);
        $tamanho += $tamanho_nome + 4;
        if ($tamanho < $limite) {
          echo $dados['nm_servico']." <a href=\"#\" onClick=\"retirarRelacao('".$it."');\" class=\"fontChamadaRelacaoServicoLink\" alt=\"Retirar o Servico ".$dados['nm_servico']." da Relação de Itens à serem Pesquisados\" title=\"Retirar o Servico ".$dados['nm_servico']." da Relação de Itens à serem Pesquisados\">[x]</a>&nbsp;&nbsp;";
        } else {     
          if ($concatenar) {
            echo "&nbsp;&nbsp;<a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\" class=\"fontChamadaRelacaoServicoLink\" alt=\"Exibir Relação Completa de Itens à serem Pesquisados\" title=\"Exibir Relação Completa de Itens à serem Pesquisados\">[+]</a> ";
            $concatenar = false;
          }
        }
      }
      echo "          </p>\n";    
      echo "        </div>\n";
      echo "        <div class=\"divConteudoServicoAcessoAcao\">\n";
      echo "        <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento/pesquisar\"><img src=\"".$_SESSION['life_link_completo']."icones/selecionar_materiais.png\" alt=\"Pesquisar Conteúdos das Servicos Selecionadas\" title=\"Pesquisar Conteúdos das Servicos Selecionadas\" border=\"0\"></a>\n";
      echo "        </div>\n";
      echo "      </div>\n";
      echo "      <div class=\"divConteudoServicoContorno\">\n";
      echo "        <div class=\"divConteudoServicoCentro\">\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemAbreTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemTopo\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemFechaTopo\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemAbreLinha1\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=1; $i<=6; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoServicoItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemFechaLinha1\" style=\"background-color:".$cor.";\"></div>\n";
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemAbreLinha2\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=7; $i<=12; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoServicoItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemFechaLinha2\" style=\"background-color:".$cor.";\"></div>\n";      
      
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemAbreLinha3\" style=\"background-color:".$cor.";\"></div>\n";

      for ($i=13; $i<=17; $i++) {
        if ($qtd_areas >= $i) {
          $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
          $this->retornaElementoArea($area);
        } else {
          $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
          echo "            <div class=\"divConteudoServicoItem\" style=\"background-color:".$cor.";\"></div>\n";
        }
      }

      if ($qtd_areas > 18) {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "          <a href=\"".$_SESSION['life_link_completo']."areas-conhecimento\"  alt=\"Listar Todas as Servicos\" title=\"Listar Todas as Servicos\" class=\"fontChamadaServico\">\n";
        echo "            <div class=\"divConteudoServicoItem\" style=\"background-color:".$cor.";\"><label style=\"font-size: 16px;\"><br />Todas as<br />Áreas do<br />Conhecimento</label></div>\n";
        echo "          </a>\n";                                                                                                                 
      } elseif ($qtd_areas == 18) {
        $numero_area = $util->sortearValorNaoRepetido($areas_sorteadas, 0, ($qtd_areas - 1));     $areas_sorteadas[] = $numero_area;     $area = $areas[$numero_area];
        $this->retornaElementoArea($area);
      } else {
        $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
        echo "            <div class=\"divConteudoServicoItem\" style=\"background-color:".$cor.";\"></div>\n";
      }

      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemFechaLinha3\" style=\"background-color:".$cor.";\"></div>\n";         
                     
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemAbreBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemBase\" style=\"background-color:".$cor.";\"></div>\n";
      $numero_cor = $util->sortearValorNaoRepetido($cores_sorteadas, 0, (count($cores) - 1));      $cores_sorteadas[] = $numero_cor;      $cor = $cores[$numero_cor];
      echo "          <div class=\"divConteudoServicoItemFechaBase\" style=\"background-color:".$cor.";\"></div>\n";                         
      
      echo "        </div>\n";
      echo "      </div>\n";
      echo "    </div>\n"; 
      
      echo "    <div class=\"divTodosConteudoServico\">\n";
      echo "      <a href=\"\"><img src=\"".$_SESSION['life_link_completo']."icones/todas_areas.png\" alt=\"Relacionar todas as Servicos\" title=\"Relacionar todas as Servicos\" border=\"0\"></a>\n";
      echo "    </div>\n";    
    }

    public function retornaElementoArea($area) {
      $conteudo = $this->tratarConteudoServico($area);
      echo "          <a href=\"#\"  alt=\"Incluir o Servico ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" title=\"Incluir o Servico ".$conteudo['nome']." na Relação de Itens à serem Pesquisados\" class=\"fontChamadaServico\" onClick=\"incluirServicoRelacao('".$conteudo['identificador']."');\">\n";
      echo "            <div class=\"divConteudoServicoItem\" style=\"background-color:".$area['ds_cor'].";\">\n";
      echo "              ".$conteudo['descricao']."\n";
      echo "            </div>\n";
      echo "          </a>\n";
    }
       
                                                                 */
//**************BANCO DE DADOS**************************************************    
    public function selectServico($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_servicos ".
              "WHERE cd_servico > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_servico ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
       
    public function selectDadosServico($cd_servico) {
      $sql  = "SELECT * ".
              "FROM life_servicos ".
              "WHERE cd_servico = '$cd_servico' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereServico($nm_servico, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_servicos ".
             "(nm_servico, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$nm_servico\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'servicos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraServico($cd_servico, $nm_servico, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_servicos SET ".
             "nm_servico = \"$nm_servico\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_servico= '$cd_servico' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'servicos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SERVICO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

  
  }
?>