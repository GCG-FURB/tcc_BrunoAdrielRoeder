<?php
  class Home {
    
    public function __construct () {
    }
    
    public function controleExibicaoPublica($secao, $subsecao, $item, $pagina, $lista_paginas) {

      $_SESSION['life_pesquisa_secao'] = $secao;
      $_SESSION['life_pesquisa_subsecao'] = $subsecao;
      $_SESSION['life_pesquisa_item'] = $item;

      $this->exibeCapaSite($pagina, $lista_paginas);
    }
    
    public function exibeCapaSite($pagina, $lista_paginas) {
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();

      echo "<div class=\"divTopoCorpoSite \">\n";
      $oa->retornaConteudoCapaTopo($pagina, $lista_paginas);
      echo "</div>\n";

    }
  

    public function retornaConteudoObjetosAprendizagemNoticias() {
      require_once 'conteudos/objetos_aprendizagem.php';                        $oa = new ObjetoAprendizagem();
      require_once 'conteudos/noticias.php';                                    $not = new Noticia();

      echo "<div class=\"divMeioCorpoSite\">\n";
      echo "  <div class=\"divCentroMeioCorpoSite\">\n";
      echo "    <div class=\"divLado01MeioCorpoSite\">\n";
      $oa->exibeRelacaoOutrosObjetosAprendizagemCapa();
      echo "    </div>\n";
      echo "    <div class=\"divLado02MeioCorpoSite\">\n";
      $not->exibeNoticiasCapa();
      echo "    </div>\n";
      echo "  </div>\n";
      echo "  <div class=\"clear\"></div>\n";
      echo "</div>\n";

    }

  }
?>