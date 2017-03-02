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


      if ($titulo != '') {
        echo "<h1>".$titulo."</h1>\n";
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
      if ($item_liberado) {
        switch ($item_selecionado) {
          case '0': 
            //formulario de login
            require_once 'conteudos/home.php';                                  $obj = new Home();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break; 

          case '3': 
            //formulario de login
            require_once 'login/login.php';                                     $obj = new Login();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
          break; 
          
          case '4':
            //cadastro
            require_once 'conteudos/pessoas.php';                               $obj = new Pessoa();
            $obj->controleExibicaoPublica($pagina, $lista_paginas);
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
            $obj->retornaMenuAdministrativoInterno($secao, $subsecao, $item);
          break; 
          
          case '18': 
            //menu - administrativo - dados_pessoais - colaborador 
            require_once 'menu/menu.php';                                       $obj = new Menu();
            $obj->retornaMenuAdministrativoInterno($secao, $subsecao, $item);
          break; 

          case '19': 
            //menu - administrativo - dados_pessoais - usuario 
            require_once 'menu/menu.php';                                       $obj = new Menu();
            $obj->retornaMenuAdministrativoInterno($secao, $subsecao, $item);
          break; 
          
          case '23':
            //menu - administrativo - dados_pessoais - alterar senha 
            require_once 'usuarios/usuarios.php';                               $obj = new Usuario();
            $obj->controleExibicaoAlteracaoSenha($pagina, $lista_paginas);
          break; 
          
          case '24':
            //menu - administrativo - dados_pessoais - alterar senha 
            require_once 'usuarios/usuarios.php';                               $obj = new Usuario();
            $obj->controleExibicaoAlteracaoSenha($pagina, $lista_paginas);
          break; 
          
          case '25':
            //menu - administrativo - dados_pessoais - alterar senha 
            require_once 'usuarios/usuarios.php';                               $obj = new Usuario();
            $obj->controleExibicaoAlteracaoSenha($pagina, $lista_paginas);
          break; 
          
          case '32':
            echo "AQUI VAI CHAMAR O FORM DE AJUSTE DE INFORMACOES PESSOAIS";
          break;

          case '33':
            echo "AQUI VAI CHAMAR O FORM DE AJUSTE DE INFORMACOES PESSOAIS";
          break;

          case '34':
            echo "AQUI VAI CHAMAR O FORM DE AJUSTE DE INFORMACOES PESSOAIS";
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
            $obj->controleExibicao($secao, $subsecao, $item, 'obba');
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
          
          case '43': 
            //administrativo - cadastros - status ciclo vida
            require_once 'conteudos/status_ciclo_vida.php';                     $obj = new StatusCicloVida();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;          
          
          case '44': 
            //administrativo - cadastros - plataforma
            require_once 'conteudos/plataformas.php';                           $obj = new Plataforma();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;    
          
          case '45': 
            //administrativo - cadastros - servicos
            require_once 'conteudos/servicos.php';                              $obj = new Servico();
            $obj->controleExibicao($secao, $subsecao, $item);
          break;    
          
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
            //administrativo - configuracoes - campos obba - basico
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'obba_basico');
          break; 
          
          case '49': 
            //administrativo - configuracoes - campos obba - intermediario
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'obba_requisitar');
          break; 


          case '53':
            //administrativo - configuracoes - pesquisa - campos
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'pesq_campos');
          break;

          case '54':
            //administrativo - configuracoes - descricao - campos
            require_once 'includes/configuracoes.php';                          $obj = new Configuracao();
            $obj->controleExibicao($secao, $subsecao, $item, 'campos_obba');
          break;

        }
        echo "<br />\n";
      } else {
        //mensagem de que item nao é liberado para aquele usuario
        echo "  <p class=\"fontBloqueioAcesso\">\n";
        require_once 'includes/configuracoes.php';                              $conf= new Configuracao();
        $mensagem= $conf->verificarMensagemBloqueio();
        echo "    ".nl2br($mensagem)."\n";
        echo "  </p>\n";
      }
    }    
  }
?>