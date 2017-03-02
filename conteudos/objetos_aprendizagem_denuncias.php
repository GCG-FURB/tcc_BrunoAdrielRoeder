<?php
  class ObjetoAprendizagemDenuncia {
    
    public function __construct () {
    }

    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }

      if (isset($_SESSION['life_gerenciar_denuncias'])) {         unset($_SESSION['life_gerenciar_denuncias']);         }

      switch ($acao) {
        case "":
          $this->listarItens($secao, $subsecao, $item);
        break;

        case "denunciado":
          $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item;
          $this->montarFormularioAnaliseDenunciaAdministrador($link, $codigo);
        break;
        
        case "salvar":
          if (isset($_SESSION['life_edicao'])) {
            unset($_SESSION['life_edicao']);
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item;
            if ($this->salvarAnaliseDenuncia($link)) {
              $this->listarItens($secao, $subsecao, $item);
            }
          } else {
            $this->listarItens($secao, $subsecao, $item);
          }
        break;
      }
    }

    private function listarItens($secao, $subsecao, $item) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();

      $nr_limite_denuncias = $conf->retornaNumeroLimiteDenunciasReversaoProprietario();

      $itens = $oa->selectObjetosAprendizagemBloqueadosExcessoDenuncias($nr_limite_denuncias);

      $mensagem = "Objetos de aprendizagem com denúncias não analisadas";
      echo "<h2>".$mensagem."</h2>\n";

      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" colspan=\"4\">Dados do objeto de aprendizagem</td>\n";
      echo "      <td class=\"celConteudo\" style=\"width:7%;\">Ações</td>\n";
      echo "    </tr>\n";

      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nome</td>\n";
        echo "      <td class=\"celConteudo\" colspan=\"3\">".$it['nm_objeto_aprendizagem']."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"3\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $oa->detalharObjetoAprendizagem($it['cd_objeto_aprendizagem']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&cd=".$it['cd_objeto_aprendizagem']."&acao=denunciado\"><img src=\"icones/denunciado.png\" alt=\"Analisar Denúncia de Objeto Aprendizagem\" title=\"Analisar Denúncia de Objeto Aprendizagem\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nome abrev.</td>\n";
        echo "      <td class=\"celConteudo\">".$it['ds_identifier']."</td>\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Responsável</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_pessoa']."</td>\n";
        echo "    </tr>\n";
      }
      echo "  </table>\n";       
    }

    private function montarFormularioAnaliseDenunciaAdministrador($link, $cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      }

      $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);

      if ($permissoes_usuario[35] == '0') {
        echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para gerenciar denúncias contra objetos de aprendizagem!</p>\n";
        return false;
      }

      echo "<h2>Análise de denúncia de objeto de aprendizagem</h2>\n";

      echo "    <p class=\"fontComandosFiltros\">\n";
      echo "      <a href=\"".$link."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "    </p>\n";

      $denuncias = $this->selectDenunciasObjetosAprendizagem($cd_objeto_aprendizagem, '1', '0');

      if (count($denuncias) > 1) {
        echo "<p class=\"fontConteudoAlerta\">Existem ".count($denuncias)." denúncias à serem analisadas!</p>\n";
      }

      if (count($denuncias) > 0) {
        $denuncia = $denuncias[0];

        include "js/js_avaliar_denuncia_objeto_aprendizagem.js";
        echo "  <form method=\"POST\" name=\"cadastro_o_a\" id=\"cadastro\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');
        $util->campoHidden('cd_objeto_aprendizagem', $cd_objeto_aprendizagem);
        $util->campoHidden('cd_objeto_aprendizagem_denuncia', $denuncia['cd_objeto_aprendizagem_denuncia']);
        $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $denuncia['cd_pessoa_responsavel_objeto_aprendizagem']);
        $util->campoHidden('cd_pessoa_denunciante', $denuncia['cd_pessoa_denunciante']);
        $util->campoHidden('ds_denuncia', $denuncia['ds_denuncia']);
        $util->campoHidden('dt_denuncia', $denuncia['dt_denuncia']);
        $util->campoHidden('eh_avaliada', '0');
        $util->campoHidden('dt_justificativa', date('Y-m-d'));

        $cd_objeto_aprendizagem_denuncia = $denuncia['cd_objeto_aprendizagem_denuncia'];

        $util->linhaDuasColunasComentario('Objeto de aprendizagem ', $dados['nm_objeto_aprendizagem']);
        $util->linhaDuasColunasComentario('Responsável ', $denuncia['nm_denunciado']);
        $util->linhaComentario('<hr>');
        $util->linhaDuasColunasComentario('Denunciado por ', $denuncia['nm_denunciante']);
        $util->linhaDuasColunasComentario('Data da denúncia ', $dh->imprimirData($denuncia['dt_denuncia']));
        $util->linhaDuasColunasComentario('Denúncia ', nl2br($denuncia['ds_denuncia']));
        $util->linhaComentario('<hr>');
        $util->linhaTexto('1', 'Justificativa ', 'ds_justificativa', '', '10', '100');
        $util->linhaBotao('Salvar', "valida(cadastro);");
        echo "    </table>\n";
        echo "    <p class=\"fontConteudoAlerta\">(*) Campos obrigatórios</p>\n";
        echo "  </form>\n";

        $denuncias = $this->selectDenunciasObjetosAprendizagem($cd_objeto_aprendizagem, '1', '2');

        if (count($denuncias) > 1) {
          echo "<h2>Acompanhe outras denúncias do objeto de aprendizagem</h2>\n";
          echo "    <table class=\"tabConteudo\">\n";
          foreach ($denuncias as $denuncia) {
            if ($cd_objeto_aprendizagem_denuncia != $denuncia['cd_objeto_aprendizagem_denuncia']) {
              $util->linhaComentario('<hr>');
              $util->linhaDuasColunasComentario('Denunciado por ', $denuncia['nm_denunciante']);
              $util->linhaDuasColunasComentario('Data da denúncia ', $dh->imprimirData($denuncia['dt_denuncia']));
              $util->linhaDuasColunasComentario('Denúncia ', nl2br($denuncia['ds_denuncia']));
              if ($denuncia['eh_avaliada'] == '1') {
                $util->linhaDuasColunasComentario('Denúncia avaliada? ', 'Sim');
                $util->linhaDuasColunasComentario('Justificativa do responsável ', nl2br($denuncia['ds_justificativa']));
              } else {
                $util->linhaDuasColunasComentario('Denúncia avaliada? ', 'Não');
              }
            }
          }
          $util->linhaComentario('<hr>');
          echo "    </table>\n";
        }

        $util->posicionarCursor('cadastro', 'ds_justificativa');
        $_SESSION['life_edicao'] = '1';
      }
    }

    public function montarFormularioAnaliseDenuncia($link, $cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 

      $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);

      if ($permissoes_usuario[21] == '0') {
        $cd_usuario = $_SESSION['life_codigo'];
        if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
          echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este objeto de aprendizagem!</p>\n";
          return false;
        }
      }       

      echo "<h2>Análise de denúncia de objeto de aprendizagem</h2>\n";

      echo "    <p class=\"fontComandosFiltros\">\n";
      echo "      <a href=\"".$link."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "    </p>\n";

      $denuncias = $this->selectDenunciasObjetosAprendizagem($cd_objeto_aprendizagem, '1', '0');

      if (count($denuncias) > 1) {
        echo "<p class=\"fontConteudoAlerta\">Você precisa avaliar ".count($denuncias)." denúncias!</p>\n";
      }

      if (count($denuncias) > 0) {
        $denuncia = $denuncias[0];

        include "js/js_avaliar_denuncia_objeto_aprendizagem.js";
        echo "  <form method=\"POST\" name=\"cadastro_o_a\" id=\"cadastro\" action=\"".$link."&acao=avaliar_denuncia\" onSubmit=\"return valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('eh_form', '1');
        $util->campoHidden('cd_objeto_aprendizagem', $cd_objeto_aprendizagem);
        $util->campoHidden('cd_objeto_aprendizagem_denuncia', $denuncia['cd_objeto_aprendizagem_denuncia']);
        $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $denuncia['cd_pessoa_responsavel_objeto_aprendizagem']);
        $util->campoHidden('cd_pessoa_denunciante', $denuncia['cd_pessoa_denunciante']);
        $util->campoHidden('ds_denuncia', $denuncia['ds_denuncia']);
        $util->campoHidden('dt_denuncia', $denuncia['dt_denuncia']);
        $util->campoHidden('eh_avaliada', '0');
        $util->campoHidden('dt_justificativa', date('Y-m-d'));

        $util->linhaDuasColunasComentario('objeto de aprendizagem ', $dados['nm_objeto_aprendizagem']);
        $util->linhaComentario('<hr>');
        $util->linhaDuasColunasComentario('Data da denúncia ', $dh->imprimirData($denuncia['dt_denuncia']));
        $util->linhaDuasColunasComentario('Denúncia ', nl2br($denuncia['ds_denuncia']));
        $util->linhaComentario('<hr>');
        $util->linhaTexto('1', 'Justificativa ', 'ds_justificativa', '', '10', '100');
        $util->linhaBotao('Salvar', "valida(cadastro);");
        echo "    </table>\n";
        echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
        echo "  </form>\n";
        $util->posicionarCursor('cadastro', 'ds_justificativa');
        $_SESSION['life_edicao'] = '1';
      }
    }

    public function salvarAnaliseDenuncia($link) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $cd_objeto_aprendizagem_denuncia = addslashes($_POST['cd_objeto_aprendizagem_denuncia']);
      $cd_objeto_aprendizagem = addslashes($_POST['cd_objeto_aprendizagem']);
      $cd_pessoa_responsavel_objeto_aprendizagem = addslashes($_POST['cd_pessoa_responsavel_objeto_aprendizagem']);
      $cd_pessoa_denunciante = addslashes($_POST['cd_pessoa_denunciante']);
      $ds_denuncia = $util->limparVariavel($_POST['ds_denuncia']);
      $dt_denuncia = addslashes($_POST['dt_denuncia']);
      $eh_avaliada = '1';
      $ds_justificativa = $util->limparVariavel($_POST['ds_justificativa']);
      $dt_justificativa = addslashes($_POST['dt_justificativa']);
      $eh_ativo = '1';

      if ($this->alterarDenunciaObjetoAprendizagem($cd_objeto_aprendizagem_denuncia, $cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_denunciante, $ds_denuncia, $dt_denuncia, $eh_avaliada, $ds_justificativa, $dt_justificativa, $eh_ativo)) {
        echo "<p class=\"fontConteudoSucesso\">Sua análise sobre a denúncia foi registrada em nossa base!</p>\n";
        require_once 'conteudos/objetos_aprendizagem.php';                      $oa = new ObjetoAprendizagem();
        require_once 'conteudos/email.php';                                     $email = new Email();
        require_once 'conteudos/pessoas.php';                                   $pes = new Pessoa();

        $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
        $texto = "Objeto de aprendizagem ".$dados['nm_objeto_aprendizagem']."<br />".
                 "O objeto denunciado pode ser acessado <a href=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."\">por este link</a>.<br /><br />".
                 "<hr>".
                 "Denúncia<br />".$ds_denuncia.
                 "<hr>".
                 "Justificativa/Análise<br />".$ds_justificativa;

        $denunciante = $pes->selectDadosPessoa($cd_pessoa_denunciante);
        $email->notificarAnaliseDenunciaDenunciante($denunciante['cd_contato'], $texto);
        $denuncias = $this->selectDenunciasObjetosAprendizagem($cd_objeto_aprendizagem, '1', '0');
        if (count($denuncias) > 0) {
          $this->montarFormularioAnaliseDenuncia($link, $cd_objeto_aprendizagem);
          return false;
        } else {
          $oa->desbloquearObjetoAprendizagem($cd_objeto_aprendizagem);
          return true;
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Desculpe! Não conseguimos registrar sua análise!</p>\n";
        return true;
      }
    }

    public function existeNecessidadeGerenciamentoDenuncias() {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();

      $nr_limite_denuncias = $conf->retornaNumeroLimiteDenunciasReversaoProprietario();

      $itens = $oa->selectObjetosAprendizagemBloqueadosExcessoDenuncias($nr_limite_denuncias);

      if (count($itens) > 0) {
        return true;
      } else {
        return false;
      }
    }

//***************EXIBICAO PUBLICA***********************************************

    public function montarFormularioDenuncia($dados) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();

      $responsavel = $pes->selectDadosPessoaUsuario($dados['cd_usuario_proprietario']);
      $denunciante = $pes->selectDadosPessoaUsuario($_SESSION['life_codigo']);

      $comentario = $conf->retornaExplicacaoProcessoDenuncia();

      echo "  <p class=\"fontComandosFiltros\" style=\"height:15px;	vertical-align: baseline; text-align:left;\">\n";
      echo "    Denuncie: ";
      echo "  </p>\n";

      if ($comentario) {
        echo "<p class=\"fontConteudo\">".nl2br($comentario)."</p>\n";
      }

      include "js/js_denuncia_objeto_aprendizagem.js";
      echo "  <form method=\"POST\" name=\"cadastro_o_a\" id=\"cadastro_denuncia\" action=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."/denuncia\" onSubmit=\"return valida(this);\">\n";
      $util->campoHidden('eh_form', '1');
      $util->campoHidden('cd_objeto_aprendizagem', $dados['cd_objeto_aprendizagem']);
      $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $responsavel['cd_pessoa']);
      $util->campoHidden('cd_pessoa_denunciante', $denunciante['cd_pessoa']);
      $util->campoHidden('dt_denuncia', date('Y-m-d'));
      $util->campoHidden('eh_ativo', '1');

      echo "    <p class=\"fontConteudoObjetosAprendizagemComentarioDenuncia\">\n";
      echo "      <textarea name=\"ds_denuncia\" id=\"ds_denuncia\" rows=\"2\" class=\"fontConteudoCampoTextoHint\" style=\"width:80%;\" alt=\"Denuncie esse conteúdo\" title=\"Denuncie esse conteúdo\" placeholder=\"Denuncie esse conteúdo\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\"></textarea>\n";
      echo "  	  <input type=\"button\" class=\"botao\" value=\"Denunciar\" style=\"margin-top:-42px; height:38px;\" onClick=\"valida_denuncia(cadastro_denuncia);\">\n";
      echo "    </p>\n";
      echo "  </form>\n";
      $_SESSION['life_denuncia'] = '1';
    }

    public function salvarDenuncia() {
      if (isset($_SESSION['life_denuncia'])) {
        unset($_SESSION['life_denuncia']);

        require_once 'includes/utilitarios.php';                                $util = new Utilitario();

        $cd_objeto_aprendizagem = addslashes($_POST['cd_objeto_aprendizagem']);
        $cd_pessoa_responsavel_objeto_aprendizagem = addslashes($_POST['cd_pessoa_responsavel_objeto_aprendizagem']);
        $cd_pessoa_denunciante = addslashes($_POST['cd_pessoa_denunciante']);
        $ds_denuncia = $util->limparVariavel($_POST['ds_denuncia']);
        $dt_denuncia = addslashes($_POST['dt_denuncia']);
        $eh_avaliada = '0';
        $eh_ativo = addslashes($_POST['eh_ativo']);

        if ($this->insereDenuncia($cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_denunciante, $ds_denuncia, $dt_denuncia, $eh_avaliada, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Sua denúncia foi registrada em nossa base e será avaliada. Obrigado!</p>\n";
          require_once 'conteudos/email.php';                                   $email = new Email();
          require_once 'conteudos/pessoas.php';                                 $pes = new Pessoa();
          require_once 'conteudos/objetos_aprendizagem.php';                    $oa = new ObjetoAprendizagem();

          $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
          $texto = "Objeto de aprendizagem: ".$dados['nm_objeto_aprendizagem']."<br />".
                   "O objeto denunciado pode ser acessado <a href=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."\">por este link</a>.<br /><br />".
                   "<hr>".
                   "Denúncia:<br />".
                   $ds_denuncia;

          $oa->registrarDenunciaBloqueio($cd_objeto_aprendizagem);

          $denunciante = $pes->selectDadosPessoa($cd_pessoa_denunciante);
          $email->notificarDenunciaDenunciante($denunciante['cd_contato'], $texto);
          $denunciado = $pes->selectDadosPessoa($cd_pessoa_responsavel_objeto_aprendizagem);
          $email->notificarDenunciaDenunciado($denunciado['cd_contato'], $texto);
        } else {
          echo "<p class=\"fontConteudoAlerta\">Ops! Não conseguimos registrar sua denúncia. Desculpe!</p>\n";
        }
      }
    }

//**************BANCO DE DADOS**************************************************    
   public function selectDenunciasObjetosAprendizagem($cd_objeto_aprendizagem, $eh_ativo, $eh_avaliada) {
      $sql  = "SELECT ".
              "     oad.*, ".
              "     pe1.nm_pessoa nm_denunciante, pe1.cd_contato cd_contato_denunciante, ".
              "     pe2.nm_pessoa nm_denunciado, pe2.cd_contato cd_contato_denunciado ".
              "FROM ".
              "     life_objetos_aprendizagem_denuncias oad, ".
              "     life_pessoas pe1, ".
              "     life_pessoas pe2 ".
              "WHERE oad.cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
              "AND oad.cd_pessoa_denunciante = pe1.cd_pessoa ".
              "AND oad.cd_pessoa_responsavel_objeto_aprendizagem = pe2.cd_pessoa ";
      if ($eh_ativo != '2') {
        $sql.= "AND oad.eh_ativo = '$eh_ativo' ";
      }
      if ($eh_avaliada != '2') {
        $sql.= "AND oad.eh_avaliada = '$eh_avaliada' ";
      }
      $sql.= " ORDER BY oad.dt_denuncia ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM DENUNCIAS - 1");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;
    }


    private function insereDenuncia($cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_denunciante, $ds_denuncia, $dt_denuncia, $eh_avaliada, $eh_ativo) {
      $sql = "INSERT INTO life_objetos_aprendizagem_denuncias ".
             "(cd_objeto_aprendizagem, cd_pessoa_responsavel_objeto_aprendizagem, cd_pessoa_denunciante, ds_denuncia, dt_denuncia, eh_avaliada, eh_ativo) ".
             "VALUES ".
             "(\"$cd_objeto_aprendizagem\", \"$cd_pessoa_responsavel_objeto_aprendizagem\", \"$cd_pessoa_denunciante\", \"$ds_denuncia\", \"$dt_denuncia\", \"$eh_avaliada\", \"$eh_ativo\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_denuncias');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM DENUNCIAS - 5");
      $saida = mysql_affected_rows();
      return $saida;
    }

    private function alterarDenunciaObjetoAprendizagem($cd_objeto_aprendizagem_denuncia, $cd_objeto_aprendizagem, $cd_pessoa_responsavel_objeto_aprendizagem, $cd_pessoa_denunciante, $ds_denuncia, $dt_denuncia, $eh_avaliada, $ds_justificativa, $dt_justificativa, $eh_ativo) {
      $sql = "UPDATE life_objetos_aprendizagem_denuncias SET ".
             "ds_denuncia = \"$ds_denuncia\", ".
             "dt_denuncia = \"$dt_denuncia\", ".
             "eh_avaliada = \"$eh_avaliada\", ".
             "ds_justificativa = \"$ds_justificativa\", ".
             "dt_justificativa = \"$dt_justificativa\", ".
             "eh_ativo = \"$eh_ativo\" ".
             "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
             "AND cd_objeto_aprendizagem_denuncia = '$cd_objeto_aprendizagem_denuncia' ".
             "AND cd_pessoa_responsavel_objeto_aprendizagem = '$cd_pessoa_responsavel_objeto_aprendizagem' ".
             "AND cd_pessoa_denunciante = '$cd_pessoa_denunciante' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem_denuncias');
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM DENUNCIAS - 7");
      $saida = mysql_affected_rows();
      return $saida;     
    }   

  }
?>                   