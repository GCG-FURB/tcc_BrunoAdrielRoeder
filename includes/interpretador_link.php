<?php
  class InterpretadorLink {
    public function __construct() {
    }
    
    public function interpretar() {
      require_once 'menu/menu.php';                                             $menu = new Menu();
      $atual = array();
      if (isset($_GET['pg'])) {
        $atual = addslashes($_GET['pg']);
        $secao = '0';
        $subsecao = '0';
        $item = '0';
        $lista_branca_secao = $menu->retornaListaBranca($secao, $subsecao, $item);
        $tamanho = substr_count($atual, '/');
        if ($tamanho > 0) {
          $atual = explode('/', $atual);
          if (($atual[0] == 'reduzir-tamanho-fonte') || ($atual[0] == 'aumentar-tamanho-fonte') || ($atual[0] == 'tamanho-fonte-regular')) {
            $pagina = '';
            $secao = '0';

            require_once 'includes/utilitarios.php';                            $util = new Utilitario();
            $util->mudarTamanhoFonte($atual[0]);
          } else {
            if (in_array($atual[0], $lista_branca_secao)) {
              $pagina = $atual[0];
              $secao = $menu->retornaSecao($pagina, '0', '0');
              if (isset($atual[1])) {
                $lista_branca_subsecao = $menu->retornaListaBranca($secao, $subsecao, $item);
                if (($atual[1] == 'reduzir-tamanho-fonte') || ($atual[1] == 'aumentar-tamanho-fonte') || ($atual[1] == 'tamanho-fonte-regular')) {
                  $pagina = $atual[0];
                  $subsecao = '0';

                  require_once 'includes/utilitarios.php';                      $util = new Utilitario();
                  $util->mudarTamanhoFonte($atual[1]);
                } else {
                  if (in_array($atual[1], $lista_branca_subsecao)) {
                    $pagina = $atual[1];
                    $subsecao = $menu->retornaSecao($pagina, $secao, '0');
                  }
                  if (isset($atual[2])) {
                    $lista_branca_item = $menu->retornaListaBranca($secao, $subsecao, $item);
                    if (in_array($atual[2], $lista_branca_item)) {
                      $pagina = $atual[2];
                      $item = $menu->retornaSecao($pagina, $secao, $subsecao);
                    } else {
                      $pagina = 'erro';
                    }
                  }
                }
              }
            } else {
              $pagina = 'erro';
            }
          }
        } else {
          if (($atual == 'reduzir-tamanho-fonte') || ($atual == 'aumentar-tamanho-fonte') || ($atual == 'tamanho-fonte-regular')) {
            $pagina = '';
            $secao = '0';

            require_once 'includes/utilitarios.php';                            $util = new Utilitario();
            $util->mudarTamanhoFonte($atual);

            $atual = array($pagina);
          } else {
            if (in_array($atual, $lista_branca_secao)) {
              $pagina = $atual;
              $secao = $menu->retornaSecao($pagina, '0', '0');
              $atual = array($pagina);
            } else {
              $pagina = 'erro';
            }
          }
        }
      } else {
        $pagina = '';
    
        if (isset($_GET['secao'])) {    $secao= addslashes($_GET['secao']);     } else {    $secao= 0;        }
        if (isset($_GET['sub']))   {    $subsecao= addslashes($_GET['sub']);    } else {    $subsecao= 0;     }
        if (isset($_GET['it']))    {    $item= addslashes($_GET['it']);         } else {    $item= 0;         }
      }

      if (is_array($atual)) {
        $atualizada = array();
        foreach ($atual as $a) {
          if (($a != 'reduzir-tamanho-fonte') && ($a != 'aumentar-tamanho-fonte') && ($a != 'tamanho-fonte-regular')) {
            $atualizada[] = $a;
          }
        }
        $atual = $atualizada;
      }
      if (($pagina == 'erro') && ($secao != '0')) {
        $pagina = '';
      }
      $retorno = array();
      $retorno['atual'] = $atual;
      $retorno['pagina'] = $pagina;
      $retorno['secao'] = $secao;
      $retorno['subsecao'] = $subsecao;
      $retorno['item'] = $item;
/*
echo "<pre>";
print_r($retorno);
echo "</pre>";
*/
      return $retorno;
    }
  
  }
?>