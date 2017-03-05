<?php
  class Conteudo {
  
    //construtor sem parametros
    public function __construct() {
    }
    
//***********************************CONTEUDOS**********************************
    //controlador de conteudo -  de acordo como secao e subsecao
    public function controlaExibicaoConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas) {
      require_once "menu/menu.php";                                             $menu= new Menu();

      $titulo = '';
      $item_selecionado= 0;
      if ($secao != 0) {
        $conteudo_secao= $menu->selectConteudoMenu($secao);
        $titulo.= $conteudo_secao['ds_menu_completo'];
        $item_selecionado= $secao;
      }
      if ($subsecao != 0) {
        $conteudo_subsecao= $menu->selectConteudoMenu($subsecao);
        if ($titulo != '') {                                                            $titulo .= ' - ';                  }
        $titulo.= $conteudo_subsecao['ds_menu_completo'];
        $item_selecionado= $subsecao;
      }
      if ($item != 0) {
        $conteudo_item= $menu->selectConteudoMenu($item);
        if ($titulo != '') {                                                            $titulo .= ' - ';                  }
        $titulo.= $conteudo_item['ds_menu_completo'];
        $item_selecionado= $item;
      }



      $item_liberado= false;
      //G = geral / L = limitado
      if ($permissoes[0] == 'G') {
        $item_liberado= true;
      } else {
        if (isset($_SESSION['life_permissoes'])) {
          $permissoes_usuario= $_SESSION['life_permissoes'];
        } else {
          $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
        } 
        //se o usuario possuir permissao 0 como sendo 1
        //pode acessar todas as secoes do grupo
        if ($permissoes_usuario[0] == 1) {
          $item_liberado= true;
        } else {
          $posicao_permissao= $permissoes[1];
          if ($posicao_permissao != '') {
            if ($permissoes_usuario[$posicao_permissao] == 1) {
              $item_liberado= true;
            } else {
              if ($secao == 0) {
                $item_liberado= true;
              } else {
                $item_liberado= false;
              }
            }
          } else {
            $item_liberado = true;                          
          }
        }            
      }

      if ($titulo != '') {
        if (($secao != "17") && ($secao != "18")) {
          if (!(($secao == '5') && ($permissoes_usuario[21] == '0'))) {
            echo "<div class=\"divTitulo\"><h1>".$titulo."</h1></div>\n";
          }
        }
      }

      if ($item_liberado) {
        switch ($item_selecionado) {
          case "2":
            $this->apresentaConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas, $item_selecionado);
          break;

          case "3":
            $this->apresentaConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas, $item_selecionado);
          break;

          case "4":
            $this->apresentaConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas, $item_selecionado);
          break;

          case "32":
            $this->apresentaConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas, $item_selecionado);
          break;

          case "33":
            $this->apresentaConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas, $item_selecionado);
          break;

          default:
            echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
            echo "      <div class=\"divConteudosSite\">\n";
            $this->apresentaConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas, $item_selecionado);
            echo "      </div>\n";
            echo "    </div>\n";
        	break;
        }
      } else {
        //mensagem de que item nao é liberado para aquele usuario
        echo "  <p class=\"fontBloqueioAcesso\">\n";
        require_once 'includes/configuracoes.php';                              $conf= new Configuracao();
        $mensagem= $conf->verificarMensagemBloqueio();
        echo "    ".nl2br($mensagem)."\n";
        echo "  </p>\n";
      }

    }

    private function apresentaConteudo($secao, $subsecao, $item, $permissoes, $pagina, $lista_paginas, $item_selecionado) {
        switch ($item_selecionado) {
          case '0': 
            //home
            require_once 'conteudos/home.php';                                  $obj = new Home();
            $obj->controleExibicaoPublica($secao, $subsecao, $item, $pagina, $lista_paginas);
          break; 

          case '1':
            //sobre
            require_once 'menu/menu.php';                                       $obj = new Menu();
            $obj->retornaConteudoMenu('1');
          break;

          case '2':
            //como usar
            require_once 'conteudos/faqs.php';                                  $obj = new FAQ();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break;

          case '3':
            $_SESSION['life_exibe_login'] = '1';
            //home
            echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
            echo "      <div class=\"divConteudosSite\">\n";
            require_once 'conteudos/home.php';                                  $obj = new Home();
            $obj->controleExibicaoPublica($secao, $subsecao, $item, $pagina, $lista_paginas);
            echo "      </div>\n";
            echo "    </div>\n";
            $obj->retornaConteudoObjetosAprendizagemNoticias();
          break;


          case '4':
            if (isset($_SESSION['life_cadastro_proprio_usuario_acao'])) {
              if ($_SESSION['life_cadastro_proprio_usuario_acao'] == 'a') {
                echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
                echo "      <div class=\"divConteudosSite\">\n";
                //cadastro
                require_once 'conteudos/pessoas.php';                           $obj = new Pessoa();
                $obj->controleExibicaoPublica($pagina, $lista_paginas);
                echo "      </div>\n";
                echo "    </div>\n";
              } else {
                //home
                $atual = array();       $atual[0] = 'home';          $pagina = 'home';
                $secao = '0';           $subsecao = '0';             $item = '0';
            echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
            echo "      <div class=\"divConteudosSite\">\n";
            require_once 'conteudos/home.php';                                  $obj = new Home();
            $obj->controleExibicaoPublica($secao, $subsecao, $item, $pagina, $lista_paginas);
            echo "      </div>\n";
            echo "    </div>\n";
            $obj->retornaConteudoObjetosAprendizagemNoticias();
              }
              unset($_SESSION['life_cadastro_proprio_usuario_acao']);
            } else {
                echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
                echo "      <div class=\"divConteudosSite\">\n";
                //cadastro
                require_once 'conteudos/pessoas.php';                           $obj = new Pessoa();
                $obj->controleExibicaoPublica($pagina, $lista_paginas);
                echo "      </div>\n";
                echo "    </div>\n";
            }
          break;  

          case '5':
            //menu - administrativo - cadastros
            require_once 'menu/menu.php';                                       $obj = new Menu();
            $obj->retornaMenuAdministrativoInterno($secao, $subsecao, $item);
          break;

          case '7': 
            //administrativo - cadastros - rede social
            require_once 'conteudos/redes_sociais.php';                         $obj = new RedeSocial();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;           

          case '8': 
            //administrativo - cadastros - areas formacao
            require_once 'conteudos/areas_formacao.php';                        $obj = new AreaFormacao();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;           

          case '9': 
            //administrativo - cadastros - sub areas do conhecimento
            require_once 'conteudos/sub_areas_conhecimento.php';                $obj = new SubAreaConhecimento();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;           

          case '10': 
            //menu - administrativo - configuracoes
            require_once 'menu/menu.php';                                       $obj = new Menu();
            $obj->retornaMenuAdministrativoInterno($secao, $subsecao, $item);
          break; 

          case '12': 
            //administrativo - cadastros - cidades
            require_once 'conteudos/cidades.php';                               $obj = new Cidade();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;           

          case '13': 
            //administrativo - cadastros - estados
            require_once 'conteudos/estados.php';                               $obj = new Estado();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;           

          case '15': 
            //administrativo - configuracoes - categorias usuarios
            require_once 'conteudos/categorias_usuarios.php';                   $obj = new CategoriaUsuario();
            $obj->controleExibicao($secao, $subsecao, $item);
          break; 

          case '16':
            //cadastro - esqueceu senha
            require_once 'conteudos/pessoas.php';                               $obj = new Pessoa();
            $obj->controleExibicaoPublicaEsqueceuSenha($pagina, $lista_paginas);
          break;  
          
          
          case '17': 
            //menu - administrativo - dados_pessoais - administrador
            require_once 'menu/menu.php';                                       $obj = new Menu();
            $obj->retornaMenuAdministrativoDadosPessoais($secao, $subsecao, $item);
          break; 
          
          case '19':
            require_once 'conteudos/noticias.php';                              $obj = new Noticia();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break;

          case '21':
            require_once 'login/login.php';                                     $obj = new Login();
            if ($obj->estaLogado()) {
              //administrativo - cadastros - objetos aprendizagem
              require_once 'conteudos/objetos_aprendizagem.php';                $obj = new ObjetoAprendizagem();
              $obj->controleExibicao($secao, $subsecao, $item);
            } else {
              require_once 'includes/configuracoes.php';                        $conf= new Configuracao();
              echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
              echo "      <div class=\"divConteudosSite\">\n";
              echo "        <h2>".$conf->retornaTituloLogadoMeusObjetosAprendizagem()."</h2>\n";
              echo "        <p>".$conf->retornaTextoLogadoMeusObjetosAprendizagem()."</p>\n";
              echo "        <hr>\n";
              $atual = array();       $atual[0] = 'home';          $pagina = 'home';
              $secao = '0';           $subsecao = '0';             $item = '0';

              require_once 'conteudos/home.php';                                $obj = new Home();
              $obj->controleExibicaoPublica($secao, $subsecao, $item, $pagina, $atual);
              echo "      </div>\n";
              echo "    </div>\n";
              $obj->retornaConteudoObjetosAprendizagemNoticias();
            }
          break;

          case '23':
            //menu - administrativo - dados_pessoais - alterar senha 
            require_once 'usuarios/usuarios.php';                               $obj = new Usuario();
            $obj->controleExibicaoAlteracaoSenha($pagina, $lista_paginas);
          break; 





          case '32':
            require_once 'conteudos/pessoas.php';                               $obj = new Pessoa();
            echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
            echo "      <div class=\"divConteudosSite\">\n";
            if ($obj->controleExibicaoFichaCadastral($secao, $subsecao, $item, $pagina, $lista_paginas)) {
              //home
              $atual = array();       $atual[0] = 'home';          $pagina = 'home';
              $secao = '0';           $subsecao = '0';             $item = '0';
              require_once 'conteudos/home.php';                              $obj = new Home();
              $obj->controleExibicaoPublica($secao, $subsecao, $item, $pagina, $atual);
              echo "      </div>\n";
              echo "    </div>\n";
              $obj->retornaConteudoObjetosAprendizagemNoticias();
            } else {
              echo "      </div>\n";
              echo "    </div>\n";
            }
          break;

          case '33':
            require_once 'conteudos/pedidos_alteracao_categorias.php';          $obj = new PedidoAlteracaoCategoria();
            echo "    <div class=\"divCorpoSite\" id=\"content\">\n";
            echo "      <div class=\"divConteudosSite\">\n";
            echo "        <div class=\"divDadosCadastrais\">\n";
            if ($obj->controleExibicaoAlterarCategoria($secao, $subsecao, $item, $pagina, $lista_paginas)) {
              //home
              $atual = array();       $atual[0] = 'home';          $pagina = 'home';
              $secao = '0';           $subsecao = '0';             $item = '0';
              require_once 'conteudos/home.php';                              $obj = new Home();
              $obj->controleExibicaoPublica($secao, $subsecao, $item, $pagina, $atual);
              echo "        </div>\n";
              echo "      </div>\n";
              echo "    </div>\n";
              $obj->retornaConteudoObjetosAprendizagemNoticias();
            } else {
              echo "        </div>\n";
              echo "      </div>\n";
              echo "    </div>\n";
            }
          break;

          case '34':
            require_once 'conteudos/pessoas.php';                               $obj = new Pessoa();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;



          case '35': 
            //administrativo - cadastros - areas conhecimento
            require_once 'conteudos/areas_conhecimento.php';                    $obj = new AreaConhecimento();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;           

          case '36': 
            //areas conhecimento
            require_once 'conteudos/areas_conhecimento.php';                    $obj = new AreaConhecimento();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break;           

          case '37': 
            //areas conhecimento - pesquisar
            echo "AQUI VAI RETORNAR PESQUISA";
          break;  

          case '39': 
            //administrativo - configuracoes - cadastro objetos aprendizagem
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'obaa');
          break; 

          case '41': 
            //administrativo - cadastros - objetos aprendizagem
            require_once 'conteudos/objetos_aprendizagem.php';                  $obj = new ObjetoAprendizagem();
            $obj->controleExibicao($secao, $subsecao, $item);
          break; 
          
          case '42': 
            //administrativo - cadastros - idiomas
            require_once 'conteudos/linguagens.php';                            $obj = new Linguagem();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;          
          
/*          case '43':
            //administrativo - cadastros - status ciclo vida
            require_once 'conteudos/status_ciclo_vida.php';                     $obj = new StatusCicloVida();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;*/
          
          case '44': 
            //administrativo - cadastros - plataforma
            require_once 'conteudos/plataformas.php';                           $obj = new Plataforma();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;    
          
/*          case '45':
            //administrativo - cadastros - servicos
            require_once 'conteudos/servicos.php';                              $obj = new Servico();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;*/
          
          case '46': 
            //administrativo - cadastros - tipos
            require_once 'conteudos/tipos.php';                                 $obj = new Tipo();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;    
          
          case '47': 
            //administrativo - cadastros - extensoes
            require_once 'conteudos/arquivos_extensao.php';                     $obj = new ArquivoExtensao();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;    
          
          case '48': 
            //administrativo - configuracoes - campos padrao obaa
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'padrao_obaa');
          break; 
          
          case '53':
            //administrativo - configuracoes - pesquisa - campos
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'pesq_campos');
          break;

          case '55':
            //administrativo - configuracoes - acesso - senha
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'senha');
          break;

          case '56':
            //administrativo - niveis educacionais
            require_once 'conteudos/niveis_educacionais.php';                   $obj = new NivelEducacional();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;


          case '58':
            //administrativo - como usar - categorias
            require_once 'conteudos/faqs_categorias.php';                       $obj = new FAQCategoria();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          case '59':
            //administrativo - como usar - perguntas
            require_once 'conteudos/faqs.php';                                  $obj = new FAQ();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          case '60':
            //pesquisa
            require_once 'conteudos/objetos_aprendizagem_pesquisa.php';         $obj = new ObjetoAprendizagemPesquisa();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break;
          case '61':
            //pesquisa
            require_once 'conteudos/objetos_aprendizagem_pesquisa.php';         $obj = new ObjetoAprendizagemPesquisa();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break;
          case '62':
            //pesquisa
            require_once 'conteudos/objetos_aprendizagem_pesquisa.php';         $obj = new ObjetoAprendizagemPesquisa();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break;

          case '64':
            //cadastro - noticias
            require_once 'conteudos/noticias.php';                              $obj = new Noticia();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          case '65':
            //cadastro - patrocinadores
            require_once 'conteudos/patrocinadores.php';                        $obj = new Patrocinador();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          case '66':
            require_once 'conteudos/objetos_aprendizagem.php';                  $obj = new ObjetoAprendizagem();
            $obj->controleApresentacaoObjetoAprendizagem($pagina, $lista_paginas);
          break;

          case '68':
            //administrativo - cdenuncias - gerenciamento
            require_once 'conteudos/objetos_aprendizagem_denuncias.php';        $obj = new ObjetoAprendizagemDenuncia();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          case '69':
            //administrativo - comentarios - gerenciamento - responsavel
            require_once 'conteudos/objetos_aprendizagem_comentarios.php';      $obj = new ObjetoAprendizagemComentario();
            $obj->controleExibicaoResponsavel($secao, $subsecao, $item);
          break;

          case '70':
            //administrativo - comentarios - gerenciamento - administradores
            require_once 'conteudos/objetos_aprendizagem_comentarios.php';      $obj = new ObjetoAprendizagemComentario();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          case '71':
            //administrativo - formatos objetos
            require_once 'conteudos/formatos_objetos.php';                      $obj = new FormatoObjeto();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          case '73':
            //sobre
            require_once 'menu/menu.php';                                       $obj = new Menu();
            $obj->controleExibicao($secao, $subsecao, $item, '1');
          break;

          case '74':
            //administrativo - configuracoes - cadastro objetos aprendizagem
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'detalhes');
          break;

          case '75':
            //administrativo - configuracoes - acesso
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'acesso');
          break;

          case '76':
            //administrativo - configuracoes - acesso
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'exibicao');
          break;

          case '77':
            //cadastro - blogs
            require_once 'conteudos/blogs.php';                                 $obj = new Blog();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;

          default:
            require 'erro.php';
          break;
        }
        echo "<br />\n";
    }

    public function controlaExibicaoTopoConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual) {
      $item_selecionado= 0;
      if ($secao != 0)    {        $item_selecionado= $secao;           }
      if ($subsecao != 0) {        $item_selecionado= $subsecao;        }
      if ($item != 0)     {        $item_selecionado= $item;            }


      switch ($item_selecionado) {
        case '0':
          /*echo "  <div class=\"divTopoCorpoSite\">\n";
          $this->retornaTopoConteudoHome();
          echo "  </div>\n";*/
        break;
      }
    }

    public function controlaExibicaoMeioConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual) {
      $item_selecionado= 0;
      if ($secao != 0)    {        $item_selecionado= $secao;           }
      if ($subsecao != 0) {        $item_selecionado= $subsecao;        }
      if ($item != 0)     {        $item_selecionado= $item;            }


      switch ($item_selecionado) {
        case '0':
          echo "  <div class=\"divMeioCorpoSite\">\n";
          echo "  <p>Aqui vai um texto sobre o InVersos</p>".
               "  <p>Lorem ipsum dolor sit amet consectetuer interdum sem porta at faucibus. Convallis Curabitur Sed ullamcorper facilisi et turpis eu non Pellentesque ullamcorper. Vestibulum sociis Lorem urna et elit tristique tempus ligula tortor tellus. Interdum egestas sem porttitor ut pede parturient aliquam nibh Phasellus a. Curabitur mauris vel vitae gravida Cras Vestibulum convallis magna tellus semper. Eu eu orci facilisis Phasellus.</p>".
               "  <p>Tellus id pretium Praesent facilisis eget et Curabitur urna Nullam dui. Ante nibh Ut egestas est et vitae sem Vivamus porttitor sed. Commodo leo montes massa vel dui enim Sed facilisis eros aliquet. Nec neque ipsum dui id In Nulla Donec leo mus penatibus. Et leo purus nec libero In vitae sed risus.</p>".
               "  <p>Et nibh nec Pellentesque justo porttitor tincidunt accumsan nec eget ante. Curabitur et accumsan habitasse nunc malesuada lacinia laoreet ornare nibh natoque. Dis Sed ornare eget nisl urna Curabitur accumsan laoreet ut commodo. Id vel id risus turpis justo Sed nisl a cursus sed. Vestibulum egestas vitae tincidunt Nunc lorem laoreet elit lacinia neque elit. </p>".
               "  <p>Mauris faucibus cursus egestas sapien lorem Curabitur Nulla adipiscing quis libero. Laoreet libero id hendrerit nonummy cursus quam ante felis elit sed. Mollis sed massa accumsan eget et Suspendisse tristique Proin adipiscing Nulla. Ipsum fames lorem at Curabitur Nunc vel justo lorem justo platea. A congue ut justo Nullam convallis nibh laoreet tincidunt vel pulvinar. Elit ac dictum ante Vestibulum pretium Suspendisse ac wisi Curabitur adipiscing. Metus magna.</p><br /><br /><br /><br /><br /><br /><br /><br />";
          echo "  </div>\n";
        break;
      }
    }

    public function controlaExibicaoBaseConteudo($secao, $subsecao, $item, $permissao, $pagina, $atual) {
      $item_selecionado= 0;
      if ($secao != 0)    {        $item_selecionado= $secao;           }
      if ($subsecao != 0) {        $item_selecionado= $subsecao;        }
      if ($item != 0)     {        $item_selecionado= $item;            }


      switch ($item_selecionado) {
        case '0':
          echo "  <div class=\"divBaseCorpoSite\">\n";
          echo "  Aqui vão chamadas que podem se alternar<br />".
               "  Chamada para Login<br />".
               "  Chamada para Cadastro<br />".
               "  Orientações<br />".
               "  Pesquisa<br /><br /><br /><br /><br /><br /><br /><br /><br />";
          echo "  </div>\n";
        break;
      }
    }


    private function retornaTopoConteudoHome() {
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/contador.php';                                     $con = new Contador();


      $qtd_oas = $oa->selectQuantidadeObjetosAprendizagem('1');
      $qtd_usuarios = $usu->selectQuantidadeUsuarios('1');
      $qtd_acessos = $con->selectNumeroAcessos();

      echo "    <div class=\"divContadoresEspacador\">&nbsp;</div>\n";
      echo "    <div class=\"divContadores\">\n";
      echo "      <div class=\"divImagemContadores\"><img src=\"".$_SESSION['life_link_completo']."icones/numero_objetos_aprendizagem.png\" alt=\"Objetos de Aprendizagem\" title=\"Objetos de Aprendizagem\" border=\"0\"></div>\n";
      echo "      <div class=\"divConteudoContadores\">".$qtd_oas." Objetos de Aprendizagem</div>\n";
      echo "    </div>\n";

      echo "    <div class=\"divContadores\">\n";
      echo "      <div class=\"divImagemContadores\"><img src=\"".$_SESSION['life_link_completo']."icones/numero_usuarios.png\" alt=\"Usuários\" title=\"Usuários\" border=\"0\"></div>\n";
      echo "      <div class=\"divConteudoContadores\">".$qtd_usuarios." usuários cadastrados</div>\n";
      echo "    </div>\n";

      echo "    <div class=\"divContadores\">\n";
      echo "      <div class=\"divImagemContadores\"><img src=\"".$_SESSION['life_link_completo']."icones/numero_acessos.png\" alt=\"Acessos\" title=\"Acessos\" border=\"0\"></div>\n";
      echo "      <div class=\"divConteudoContadores\">".$qtd_acessos." acessos ao Portal</div>\n";
      echo "    </div>\n";
      echo "    <div class=\"divContadoresEspacador\">&nbsp;</div>\n";
    }
  }
?>