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

      $mensagem = "Objetos de Aprendizagem com denúncias não analisadas";
      echo "<h2>".$mensagem."</h2>\n";

      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" colspan=\"4\">Dados do Objeto de Aprendizagem:</td>\n";
      echo "      <td class=\"celConteudo\" style=\"width:7%;\">Ações:</td>\n";
      echo "    </tr>\n";

      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nome:</td>\n";
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
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nome Abrev.:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['ds_identifier']."</td>\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Responsável:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_pessoa']."</td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Área Conh.:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_area_conhecimento']."</td>\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nível Educ.:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_nivel_educacional']."</td>\n";
        echo "    </tr>\n";      }
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
        echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para gerenciar denúncias contra Objetos de Aprendizagem!</p>\n";
        return false;
      }

      echo "<h2>Análise de denúncia de Objeto de Aprendizagem</h2>\n";

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
        echo "  <form method=\"POST\" name=\"cadastro_o_a\" action=\"".$link."&acao=salvar\" onSubmit=\"return valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('cd_objeto_aprendizagem', $cd_objeto_aprendizagem);
        $util->campoHidden('cd_objeto_aprendizagem_denuncia', $denuncia['cd_objeto_aprendizagem_denuncia']);
        $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $denuncia['cd_pessoa_responsavel_objeto_aprendizagem']);
        $util->campoHidden('cd_pessoa_denunciante', $denuncia['cd_pessoa_denunciante']);
        $util->campoHidden('ds_denuncia', $denuncia['ds_denuncia']);
        $util->campoHidden('dt_denuncia', $denuncia['dt_denuncia']);
        $util->campoHidden('eh_avaliada', '0');
        $util->campoHidden('dt_justificativa', date('Y-m-d'));

        $cd_objeto_aprendizagem_denuncia = $denuncia['cd_objeto_aprendizagem_denuncia'];

        $util->linhaDuasColunasComentario('Objeto de Aprendizagem: ', $dados['nm_objeto_aprendizagem']);
        $util->linhaDuasColunasComentario('Responsável: ', $denuncia['nm_denunciado']);
        $util->linhaComentario('<hr>');
        $util->linhaDuasColunasComentario('Denunciado por: ', $denuncia['nm_denunciante']);
        $util->linhaDuasColunasComentario('Data da Denúncia: ', $dh->imprimirData($denuncia['dt_denuncia']));
        $util->linhaDuasColunasComentario('Denúncia: ', nl2br($denuncia['ds_denuncia']));
        $util->linhaComentario('<hr>');
        $util->linhaTexto('1', 'Justificativa: ', 'ds_justificativa', '', '10', '100');
        $util->linhaBotao('Salvar');
        echo "    </table>\n";
        echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
        echo "  </form>\n";

        $denuncias = $this->selectDenunciasObjetosAprendizagem($cd_objeto_aprendizagem, '1', '2');

        if (count($denuncias) > 1) {
          echo "<h2>Acompanhe outras denúncias do Objeto de Aprendizagem</h2>\n";
          echo "    <table class=\"tabConteudo\">\n";
          foreach ($denuncias as $denuncia) {
            if ($cd_objeto_aprendizagem_denuncia != $denuncia['cd_objeto_aprendizagem_denuncia']) {
              $util->linhaComentario('<hr>');
              $util->linhaDuasColunasComentario('Denunciado por: ', $denuncia['nm_denunciante']);
              $util->linhaDuasColunasComentario('Data da Denúncia: ', $dh->imprimirData($denuncia['dt_denuncia']));
              $util->linhaDuasColunasComentario('Denúncia: ', nl2br($denuncia['ds_denuncia']));
              if ($denuncia['eh_avaliada'] == '1') {
                $util->linhaDuasColunasComentario('Denúncia avaliada? ', 'Sim');
                $util->linhaDuasColunasComentario('Justificativa do responsável: ', nl2br($denuncia['ds_justificativa']));
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
          echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este Objeto de Aprendizagem!</p>\n";
          return false;
        }
      }       

      echo "<h2>Análise de denúncia de Objeto de Aprendizagem</h2>\n";

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
        echo "  <form method=\"POST\" name=\"cadastro_o_a\" action=\"".$link."&acao=avaliar_denuncia\" onSubmit=\"return valida(this);\">\n";
        echo "    <table class=\"tabConteudo\">\n";
        $util->campoHidden('cd_objeto_aprendizagem', $cd_objeto_aprendizagem);
        $util->campoHidden('cd_objeto_aprendizagem_denuncia', $denuncia['cd_objeto_aprendizagem_denuncia']);
        $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $denuncia['cd_pessoa_responsavel_objeto_aprendizagem']);
        $util->campoHidden('cd_pessoa_denunciante', $denuncia['cd_pessoa_denunciante']);
        $util->campoHidden('ds_denuncia', $denuncia['ds_denuncia']);
        $util->campoHidden('dt_denuncia', $denuncia['dt_denuncia']);
        $util->campoHidden('eh_avaliada', '0');
        $util->campoHidden('dt_justificativa', date('Y-m-d'));

        $util->linhaDuasColunasComentario('Objeto de Aprendizagem: ', $dados['nm_objeto_aprendizagem']);
        $util->linhaComentario('<hr>');
        $util->linhaDuasColunasComentario('Data da Denúncia: ', $dh->imprimirData($denuncia['dt_denuncia']));
        $util->linhaDuasColunasComentario('Denúncia: ', nl2br($denuncia['ds_denuncia']));
        $util->linhaComentario('<hr>');
        $util->linhaTexto('1', 'Justificativa: ', 'ds_justificativa', '', '10', '100');
        $util->linhaBotao('Salvar');
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
        echo "<p class=\"fontConteudoSucesso\">Denúncia contra o Objeto de Aprendizagem analisada com sucesso!</p>\n";
        require_once 'conteudos/objetos_aprendizagem.php';                      $oa = new ObjetoAprendizagem();
        require_once 'conteudos/email.php';                                     $email = new Email();
        require_once 'conteudos/pessoas.php';                                   $pes = new Pessoa();

        $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
        $texto = "Objeto de Aprendizagem: ".$dados['nm_objeto_aprendizagem']."<br />".
                 "O objeto denunciado pode ser acessado <a href=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."\">por este link</a>.<br /><br />".
                 "<hr>".
                 "Denúncia:<br />".$ds_denuncia.
                 "<hr>".
                 "Justificativa/Análise:<br />".$ds_justificativa;

        $denunciante = $pes->selectDadosPessoa($cd_pessoa_denunciante);
        if ($email->notificarAnaliseDenunciaDenunciante($denunciante['cd_contato'], $texto)) {
          echo "<p class=\"fontConteudoSucesso\">Responsável pela denúncia notificado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao notificar responsável pela denúncia!</p>\n";
        }

        $denuncias = $this->selectDenunciasObjetosAprendizagem($cd_objeto_aprendizagem, '1', '0');
        if (count($denuncias) > 0) {
          $this->montarFormularioAnaliseDenuncia($link, $cd_objeto_aprendizagem);
          return false;
        } else {
          $oa->desbloquearObjetoAprendizagem($cd_objeto_aprendizagem);
          return true;
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problemas na analise da denúncia contra o Objeto de Aprendizagem!</p>\n";
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
      if ($comentario) {
        echo "<p class=\"fontConteudo\">".nl2br($comentario)."</p>\n";
      }

      include "js/js_denuncia_objeto_aprendizagem.js";
      echo "  <form method=\"POST\" name=\"cadastro_o_a\" action=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."/denuncia\" onSubmit=\"return valida(this);\">\n";
      $util->campoHidden('cd_objeto_aprendizagem', $dados['cd_objeto_aprendizagem']);
      $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $responsavel['cd_pessoa']);
      $util->campoHidden('cd_pessoa_denunciante', $denunciante['cd_pessoa']);
      $util->campoHidden('dt_denuncia', date('Y-m-d'));
      $util->campoHidden('eh_ativo', '1');

      echo "    <p class=\"fontConteudoObjetosAprendizagemComentarioDenuncia\">\n";
      echo "      <textarea name=\"ds_denuncia\" id=\"ds_denuncia\" rows=\"2\" class=\"fontConteudoCampoTextoHint\" style=\"width:86%;\" alt=\"Denuncie esse conteúdo\" title=\"Denuncie esse conteúdo\" placeholder=\"Denuncie esse conteúdo\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\"></textarea>\n";
      echo "  	  <input type=\"submit\" class=\"botao\" value=\"Denunciar\" style=\"margin-top:-42px; height:38px;\">\n";
      echo "    </p>\n";

/*      echo "    <p class=\"fontConteudoObjetosAprendizagemComentarioDenuncia\" style=\"width:100%; height:35px; vertical-align: baseline; display: inline;\">\n";
      echo "      <textarea name=\"ds_denuncia\" id=\"ds_denuncia\" rows=\"2\" class=\"fontConteudoCampoTextoHint\" style=\"width:80%;\" alt=\"Denuncie esse conteúdo\" title=\"Denuncie esse conteúdo\" placeholder=\"Denuncie esse conteúdo\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\"></textarea>\n";
      echo "  	  <input type=\"submit\" class=\"botao\" value=\"Denunciar\" style=\"margin-top:-42px; height:38px;\">\n";
      echo "    </p>\n";*/
      echo "  </form>\n";
      $_SESSION['life_denuncia'] = '1';

    /*
      require_once 'login/login.php';                                           $login = new Login();
      require_once 'conteudos/pessoas.php';                                     $pes = new Pessoa();
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();

      $dados = $oa->selectDadosObjetoAprendizagemSEO($lk_seo_oa);

      if ($dados['cd_objeto_aprendizagem'] > 0) {
        if ($dados['eh_liberado'] == '1') {
          if ($dados['eh_ativo'] == '1') {
            echo "<div class=\"divTituloObjetoAprendizagem\">\n";
            echo "  <div class=\"divChamadaTitulo\">\n";
            echo "    <h1>".$dados['nm_objeto_aprendizagem']."</h2>\n";
            echo "  </div>\n";
            echo "  <div class=\"divFuncoesTitulo\">\n";
            echo "    <p class=\"fontComandosFiltros\">\n";
            echo "      <a href=\"".$_SESSION['life_link_completo']."objetos/".$lk_seo_oa."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
            echo "    </p>\n";
            echo "  </div>\n";
            echo "</div>\n";

            echo "<div class=\"divObjetoAprendizagem\">\n";
            if ($dados['eh_informar_general'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_general.php';                $oa_gen = new ObjetoAprendizagemGeneral();
              $retorno = $oa_gen->imprimeDados($dados['cd_general']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            echo "</div>\n";

            if ($login->estaLogado()) {
              $responsavel = $pes->selectDadosPessoaUsuario($dados['cd_usuario_proprietario']);
              $denunciante = $pes->selectDadosPessoaUsuario($_SESSION['life_codigo']);

              echo "<h2>Denúncia de Objeto de Aprendizagem</h2>\n";
              echo "<p class=\"fontConteudo\">".nl2br($conf->retornaExplicacaoProcessoDenuncia())."</p>\n";

              include "js/js_denuncia_objeto_aprendizagem.js";
              echo "  <form method=\"POST\" name=\"cadastro_o_a\" action=\"".$_SESSION['life_link_completo']."objetos/".$lk_seo_oa."/denuncia\" onSubmit=\"return valida(this);\">\n";
              echo "    <table class=\"tabConteudo\">\n";
              $util->campoHidden('cd_objeto_aprendizagem', $dados['cd_objeto_aprendizagem']);
              $util->campoHidden('cd_pessoa_responsavel_objeto_aprendizagem', $responsavel['cd_pessoa']);
              $util->campoHidden('cd_pessoa_denunciante', $denunciante['cd_pessoa']);
              $util->campoHidden('dt_denuncia', date('Y-m-d'));
              $util->campoHidden('eh_ativo', '1');
              $util->linhaTexto('1', 'Denúncia: ', 'ds_denuncia', '', '10', '100');
              $util->linhaBotao('Denunciar');
              echo "    </table>\n";
              echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
              echo "  </form>\n";
              $util->posicionarCursor('cadastro', 'ds_denuncia');
              $_SESSION['life_denuncia'] = '1';
            } else {
              echo "  <p class=\"fontConteudoAlerta\">Usuário deve estar logado para efetuar Denúncia!</p>\n";
            }
          } else {
            echo "  <img src=\"".$_SESSION['life_link_completo']."imagens/oa_excluido.png\" alt=\"Objeto de Aprendizagem excluído\" title=\"Objeto de Aprendizagem excluído\" border=\"0\">\n";
          }
        } else {
          echo "  <img src=\"".$_SESSION['life_link_completo']."imagens/oa_bloqueado.png\" alt=\"Objeto de Aprendizagem bloqueado\" title=\"Objeto de Aprendizagem bloqueado\" border=\"0\">\n";
        }
      } else {
        echo "  <img src=\"".$_SESSION['life_link_completo']."imagens/oa_nao_encontrado.png\" alt=\"Objeto de Aprendizagem não encontrado\" title=\"Objeto de Aprendizagem não encontrado\" border=\"0\">\n";
      }
      */
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
//          echo "<p class=\"fontConteudoSucesso\">Denúncia contra o Objeto de Aprendizagem registrada com sucesso!</p>\n";
          echo "<p class=\"fontConteudoSucesso\">Sua denúncia foi encaminhada para avaliação!</p>\n";
          require_once 'conteudos/email.php';                                   $email = new Email();
          require_once 'conteudos/pessoas.php';                                 $pes = new Pessoa();
          require_once 'conteudos/objetos_aprendizagem.php';                    $oa = new ObjetoAprendizagem();

          $dados = $oa->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
          $texto = "Objeto de Aprendizagem: ".$dados['nm_objeto_aprendizagem']."<br />".
                   "O objeto denunciado pode ser acessado <a href=\"".$_SESSION['life_link_completo']."objetos/".$dados['lk_seo']."\">por este link</a>.<br /><br />".
                   "<hr>".
                   "Denúncia:<br />".
                   $ds_denuncia;

          $oa->registrarDenunciaBloqueio($cd_objeto_aprendizagem);

          $denunciante = $pes->selectDadosPessoa($cd_pessoa_denunciante);
          if ($email->notificarDenunciaDenunciante($denunciante['cd_contato'], $texto)) {
//            echo "<p class=\"fontConteudoSucesso\">Responsável pela denúncia notificado com sucesso!</p>\n";
          } else {
//            echo "<p class=\"fontConteudoAlerta\">Problemas ao notificar responsável pela denúncia!</p>\n";
          }
          $denunciado = $pes->selectDadosPessoa($cd_pessoa_responsavel_objeto_aprendizagem);
          if ($email->notificarDenunciaDenunciado($denunciado['cd_contato'], $texto)) {
//            echo "<p class=\"fontConteudoSucesso\">Responsável pelo Objeto de Aprendizagem notificado com sucesso!</p>\n";
          } else {
//            echo "<p class=\"fontConteudoAlerta\">Problemas ao notificar responsável pelo Objeto de Aprendizagem!</p>\n";
          }
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no registro da denúncia contra o Objeto de Aprendizagem!</p>\n";
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