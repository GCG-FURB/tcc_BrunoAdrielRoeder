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
          if (in_array($atual[0], $lista_branca_secao)) {
            $pagina = $atual[0];
            $secao = $menu->retornaSecao($pagina, '0', '0');
            if (isset($atual[1])) {
              $lista_branca_subsecao = $menu->retornaListaBranca($secao, $subsecao, $item);
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
          } else {
            $pagina = 'erro';
          }
        } else {
          if (in_array($atual, $lista_branca_secao)) {
            $pagina = $atual;
            $secao = $menu->retornaSecao($pagina, '0', '0');
            $atual = array($pagina);
          } else {
            $pagina = 'erro';
          }
        }
      } else {
        $pagina = '';
    
        if (isset($_GET['secao'])) {    $secao= addslashes($_GET['secao']);     } else {    $secao= 0;        }
        if (isset($_GET['sub']))   {    $subsecao= addslashes($_GET['sub']);    } else {    $subsecao= 0;     }
        if (isset($_GET['it']))    {    $item= addslashes($_GET['it']);         } else {    $item= 0;         }
      }    

      $retorno = array();
      $retorno['atual'] = $atual;
      $retorno['pagina'] = $pagina;
      $retorno['secao'] = $secao;
      $retorno['subsecao'] = $subsecao;
      $retorno['item'] = $item;
      
      return $retorno;
    }
  
  }
?>