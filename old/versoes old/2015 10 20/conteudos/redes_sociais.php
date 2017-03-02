<?php
  class RedeSocial {
    
    public function __construct () {
    }
    
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';               }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;              }
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
          $this->alterarSituacaoAtivoRedeSocial($codigo);
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
      $opcao= array();      $opcao['indice']= "4";      $opcao['link']= "";                                                                                    $opcao['descricao']= "----------------------------------------";          $opcoes[]= $opcao;
        
      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }
      
      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "  <img src=\"icones/vazio.png\" border=\"0\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&acao=cadastrar\"><img src=\"icones/novo.png\" alt=\"Nova Rede Social\" title=\"Nova Rede Social\" border=\"0\"></a> \n";
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

      $mensagem = "Redes Sociais ";
      if ($ativas == 1) {             $mensagem.= "Ativas ";      } elseif ($ativas == 0) {       $mensagem.= "Inativas ";      }

      $itens = $this->selectRedesSociais('1', '2', '2');
      
      echo "<h2>".$mensagem."</h2>\n";      
      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\">Conteúdo:</td>\n";
      echo "      <td class=\"celConteudo\">Ações:</td>\n";
      echo "    </tr>\n";      
      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\">".$it['nm_rede_social']."</td>\n";
        echo "      <td class=\"celConteudo\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\" border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharRedeSocial($it['cd_rede_social']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_rede_social']."&acao=editar\"><img src=\"icones/editar.png\" alt=\"Editar\" title=\"Editar\" border=\"0\"></a>\n";
        if ($it['eh_editavel'] == '1') {
          echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_rede_social']."&acao=status\">";
          if ($it['eh_ativo'] == 1) {
            echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\" border=\"0\"></a>\n";
          } else {
            echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\" border=\"0\"></a>\n";
          }
        } else {
          echo "          <img src=\"icones/vazio.png\" border=\"0\">\n";
        }
        echo "      </td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
    }
    
    public function detalharRedeSocial($cd_rede_social) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosRedeSocial($cd_rede_social);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }
     
    private function montarFormularioCadastro($link) {
      $cd_rede_social = "";
      $nm_rede_social = "";
      $ds_arquivo_logo_compartilhamento = "";
      $ds_arquivo_logo_login = "";
      $lk_rede_social = "";
      $eh_exibir = "1";
      $eh_login = "0";
      $eh_ativo = "1";
      $eh_editavel = '1';
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Cadastro de Redes Sociais</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_rede_social, $nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $eh_editavel);
    }
    
    private function montarFormularioEdicao($link, $cd_rede_social) {
      $dados = $this->selectDadosRedeSocial($cd_rede_social);

      $nm_rede_social = $dados['nm_rede_social'];
      $ds_arquivo_logo_compartilhamento = $dados['ds_arquivo_logo_compartilhamento'];
      $ds_arquivo_logo_login = $dados['ds_arquivo_logo_login'];
      $lk_rede_social = $dados['lk_rede_social'];
      $eh_exibir = $dados['eh_exibir'];
      $eh_login = $dados['eh_login'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_editavel = $dados['eh_editavel'];
      
      $_SESSION['life_edicao']= 1;
      echo "  <h2>Edição de Rede Social</h2>\n";
      $this->imprimeFormularioCadastro($link, $cd_rede_social, $nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $eh_editavel);
    }
    
    public function imprimeFormularioCadastro($link, $cd_rede_social, $nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $eh_editavel) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a> \n";
      echo "</p>\n";

      include "js/js_cadastro_rede_social.js";
      echo "  <form method=\"POST\" name=\"cadastro\" enctype=\"multipart/form-data\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";

      $util->campoHidden('cd_rede_social', $cd_rede_social);
      $util->campoHidden('eh_login', $eh_login);
      $util->campoHidden('ds_arquivo_logo_compartilhamento_antigo', $ds_arquivo_logo_compartilhamento);
      $util->campoHidden('ds_arquivo_logo_login_antigo', $ds_arquivo_logo_login);

      if ($eh_editavel == '1') {
        $util->linhaUmCampoText(1, 'Rede Social: ', 'nm_rede_social', '100', '70', $nm_rede_social);        
      } else {
        $util->campoHidden('nm_rede_social', $nm_rede_social);
        $util->linhaDuasColunasComentario('Rede Social: ', $nm_rede_social);        
      }
      $util->linhaComentario('');
      $util->linhaUmCampoText(1, 'Link para Compartilhamento: ', 'lk_rede_social', '100', '70', $lk_rede_social);
      $explicacao = "Diferentes Redes Sociais necessitam de diferentes elementos no link de chamada, sendo assim, utilize:<br />".
                    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#URL# - será substituído pela URL da página;<br />".
                    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#TITULO# - será substituído pelo título da página, matéria ou material;<br />".
                    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#NOME# - será sustituído pelo nome do Site;<br />".
                    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#RESUMO# - será substituído por um resumo automático da página, matéria ou material (de tamanho configurável).<br />".
                    "Frisa-se que nem todos os itens precisam ou devem ser utilizados para todas as Redes Sociais. Algumas requerem uns, outras requerem outros itens.<br />";
      $util->linhaComentario($explicacao);
      $util->linhaComentario('');
      if ($cd_rede_social > 0) {
        $util->linhaUmCampoArquivo('0', 'Logo para Compartilhamento: ', 'ds_arquivo_logo_compartilhamento', 100, 70, '');
        if ($eh_login == '1') {
          $util->linhaUmCampoArquivo('0', 'Logo para Login: ', 'ds_arquivo_logo_login', 100, 70, '');
          $util->linhaComentario('Para substituir logos anteriores, selecione novo arquivo com logo!<br />Campos vazios indicarão a intenção de manter as logos anteriores!<br />O arquivo com a imagem deve estar no formato .png ou .jpg!');
        } else {
          $util->linhaComentario('Para substituir o logo de compartilhamento, selecione um novo arquivo com logo!<br />Se não houver a seleção de novo arquivo será mantida a logo anterior!<br />O arquivo com a imagem deve estar no formato .png ou .jpg!');
        }
      } else {
        $util->linhaUmCampoArquivo('1', 'Logo para Compartilhamento: ', 'ds_arquivo_logo_compartilhamento', 100, 70, '');
        $util->linhaComentario('O arquivo com a imagem deve estar no formato .png ou .jpg!');
      }
      $util->linhaComentario('');
      
      $opcoes= array();
      $opcao= array();      $opcao[]= '1';      $opcao[]= 'Sim';      $opcoes[]= $opcao;
      $opcao= array();      $opcao[]= '0';      $opcao[]= 'Não';      $opcoes[]= $opcao;

      $util->linhaSeletor('Exibir a Rede para compartilhamentos: ', 'eh_exibir', $eh_exibir, $opcoes);
      $util->linhaSeletor('É Ativa: ', 'eh_ativo', $eh_ativo, $opcoes);

      if ($cd_rede_social > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      echo "    <p class=\"fontConteudoAlerta\">".$conf->retornaDescricaoTamanhoLogoCompartilhamentoRedesSociais()."</p>\n";
      if ($eh_login == '1') {
        echo "    <p class=\"fontConteudoAlerta\">".$conf->retornaDescricaoTamanhoLogoLoginRedesSociais()."</p>\n";
      }
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'nm_rede_social'); 
    }
    
    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/fotos.php';                                       $fot = new Fotos();

      $cd_rede_social = addslashes($_POST['cd_rede_social']);
      $nm_rede_social = $util->limparVariavel($_POST['nm_rede_social']);

      $ds_arquivo_logo_compartilhamento_antigo = $util->limparVariavel($_POST['ds_arquivo_logo_compartilhamento_antigo']);
      $ds_arquivo_logo_login_antigo = $util->limparVariavel($_POST['ds_arquivo_logo_login_antigo']);

      $lk_rede_social = $util->limparVariavel($_POST['lk_rede_social']);

      $eh_exibir = addslashes($_POST['eh_exibir']);
      $eh_login = addslashes($_POST['eh_login']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      $tp_associacao = 'RS';
      $dados_pasta = $fot->selectDadosTiposAssociacoesFotos($tp_associacao);
      $ds_pasta = $dados_pasta['ds_pasta_tipo_associacao_foto'];

      $lk_seo = $util->retornaLinkSEO($nm_rede_social, 'life_redes_sociais', 'lk_seo', '100', $cd_rede_social);
      
      $arquivo = $_FILES['ds_arquivo_logo_compartilhamento'];
      if ($arquivo['name'] != '') {
        $foto = $fot->enviarFoto('ds_arquivo_logo_compartilhamento', $ds_pasta, $tp_associacao, '');
        if ($foto[0] != '') {
          echo "<p class=\"fontConteudoAlerta\">ERRO - ".$foto[0]."</p>\n";
          $ds_arquivo_logo_compartilhamento = '';
        } else {
          $ds_arquivo_logo_compartilhamento = $ds_pasta."/".$foto[1];
        }
      } else {
        if ($cd_rede_social > 0) {
          $ds_arquivo_logo_compartilhamento = $ds_arquivo_logo_compartilhamento_antigo;
        } else {
          echo "<p class=\"fontConteudoAlerta\">ERRO - Não foi selecionado Arquivo com Logo de Compartilhamento!</p>\n";
          $ds_arquivo_logo_compartilhamento = '';
        }
      }

      if (isset($_FILES['ds_arquivo_logo_login'])) {
        $arquivo = $_FILES['ds_arquivo_logo_login'];
        if ($arquivo['name'] != '') {
          $foto = $fot->enviarFoto('ds_arquivo_logo_login', $ds_pasta, $tp_associacao, '');
          if ($foto[0] != '') {
            echo "<p class=\"fontConteudoAlerta\">ERRO - ".$foto[0]."</p>\n";
            $ds_arquivo_logo_login = '';
          } else {
            $ds_arquivo_logo_login = $ds_pasta."/".$foto[1];
          }
        } else {
          if ($cd_rede_social > 0) {
            $ds_arquivo_logo_login = $ds_arquivo_logo_login_antigo;
          } else {
            echo "<p class=\"fontConteudoAlerta\">ERRO - Não foi selecionado Arquivo com Logo de Compartilhamento!</p>\n";
            $ds_arquivo_logo_login = '';
          }
        }
      } else {
        if ($cd_rede_social > 0) {
          $ds_arquivo_logo_login = $ds_arquivo_logo_login_antigo;
        } else {
          $ds_arquivo_logo_login = '';
        }
      }   


      if ($cd_rede_social > 0) {
        if ($this->alteraRedeSocial($cd_rede_social, $nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Rede Social editada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição da Rede Social, ou nenhuma informação alterada!</p>\n";
        }
      } else {
        if ($this->insereRedeSocial($nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $lk_seo)) {
          echo "<p class=\"fontConteudoSucesso\">Rede Social cadastrada com sucesso!</p>\n";   
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro da Rede Social!</p>\n";
        }
      }
    } 


    private function alterarSituacaoAtivoRedeSocial($cd_rede_social) {
      $dados = $this->selectDadosRedeSocial($cd_rede_social);

      $nm_rede_social = $dados['nm_rede_social'];
      $ds_arquivo_logo_compartilhamento = $dados['ds_arquivo_logo_compartilhamento'];
      $ds_arquivo_logo_login = $dados['ds_arquivo_logo_login'];
      $lk_rede_social = $dados['lk_rede_social'];
      $eh_exibir = $dados['eh_exibir'];
      $eh_login = $dados['eh_login'];
      $eh_ativo = $dados['eh_ativo'];
      $lk_seo = $dados['lk_seo'];

      if ($eh_ativo == 1) {        $eh_ativo = 0;      } else {        $eh_ativo = 1;      }      

      if ($this->alteraRedeSocial($cd_rede_social, $nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $lk_seo)) {
        echo "<p class=\"fontConteudoSucesso\">Status da Rede Social alterado com sucesso!</p>\n";            
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas ao alterar status da Rede Social!</p>\n";
      }
    }
/*    


    public function retornaSeletorRedesSociais($cd_rede_social, $eh_ativo, $eh_exibir, $eh_login) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectRedesSociais($eh_ativo, $eh_exibir, $eh_login);
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                       $opcao[]= 'Selecione uma Rede Social';         $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_rede_social'];          $opcao[]= $it['nm_funcao'];      $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Redes Sociais: ', 'cd_rede_social', $cd_rede_social, $opcoes);      
    }      
*/
//**************BANCO DE DADOS**************************************************    
    public function selectRedesSociais($eh_ativo, $eh_exibir, $eh_login) {
      $sql  = "SELECT * ".
              "FROM life_redes_sociais ".
              "WHERE cd_rede_social > '0' ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      if ($eh_exibir != 2) {
        $sql.= "AND eh_exibir = '$eh_exibir' ";
      }
      if ($eh_login != 2) {
        $sql.= "AND eh_login = '$eh_login' ";
      }
      $sql.= "ORDER BY nm_rede_social";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA REDES SOCIAIS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectDadosRedeSocial($cd_rede_social) {
      $sql  = "SELECT * ".
              "FROM life_redes_sociais ".
              "WHERE cd_rede_social = '$cd_rede_social' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA REDES SOCIAIS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function insereRedeSocial($nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $lk_seo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_redes_sociais ".
             "(nm_rede_social, ds_arquivo_logo_compartilhamento, ds_arquivo_logo_login, lk_rede_social, eh_exibir, eh_login, eh_ativo, cd_usuario_cadastro, dt_cadastro, lk_seo) ".
             "VALUES ".
             "(\"$nm_rede_social\", \"$ds_arquivo_logo_compartilhamento\", \"$ds_arquivo_logo_login\", \"$lk_rede_social\", \"$eh_exibir\", \"$eh_login\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\", \"$lk_seo\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'redes_sociais');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA REDES SOCIAIS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function alteraRedeSocial($cd_rede_social, $nm_rede_social, $ds_arquivo_logo_compartilhamento, $ds_arquivo_logo_login, $lk_rede_social, $eh_exibir, $eh_login, $eh_ativo, $lk_seo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_redes_sociais SET ".
             "nm_rede_social = \"$nm_rede_social\", ".
             "ds_arquivo_logo_compartilhamento = \"$ds_arquivo_logo_compartilhamento\", ".
             "ds_arquivo_logo_login = \"$ds_arquivo_logo_login\", ".
             "lk_rede_social = \"$lk_rede_social\", ".
             "eh_exibir = \"$eh_exibir\", ".
             "eh_login = \"$eh_login\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_rede_social= '$cd_rede_social' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'redes_sociais');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA REDES SOCIAIS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
    
  }
?>