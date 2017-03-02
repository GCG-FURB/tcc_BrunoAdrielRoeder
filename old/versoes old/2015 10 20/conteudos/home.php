<?php
  class Home {
    
    public function __construct () {
    }
    
    public function controleExibicaoPublica($pagina, $lista_paginas) {
      $this->exibeCapaSite($pagina, $lista_paginas);
    }
    
    public function exibeCapaSite($pagina, $lista_paginas) {
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      
      $are_con->retornaChamadaAreasConhecimentoCapa();
    }
  
  }
?>