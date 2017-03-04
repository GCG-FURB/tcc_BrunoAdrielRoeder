<?php
  class Cabecalho {
    private $titulo;                                private $link;
    private $palavras_chave;                        private $descricao;
    private $javaScript= array();                   private $css= array();
    private $secao;                                 private $subsecao;
    private $item;                                  private $pagina;

    public function __construct($tit, $secao, $subsecao, $item, $atual) {
      $this->titulo = $tit;
      $this->secao = $secao; 
      $this->subsecao = $subsecao; 
      $this->item = $item;
      if (count($atual) > 0) {
        $this->pagina = $atual[count($atual)-1];
      } else {
        $this->pagina = '';
      }
    }

    public function insereArquivoJavaScript($nomeArquivo) {
      $this->javaScript[]= $nomeArquivo;
    }     
    
    public function insereArquivoCss($nomeArquivo) {
      $this->css[]= $nomeArquivo;
    }

    public function setLink($lk) {
      $this->link= $lk;
    }  
    
    public function setPalavrasChave($pc) {
      $this->palavras_chave= $pc;
    } 
     
    public function setDescricao($des) {
      $this->descricao= $des;
    }
          
    public function imprimeCabecalhoXHTML() {
      $cab  = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?". ">\n";
      $cab .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd\">\n";
      $cab .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"cs\" lang=\"cs\">\n";
      $cab .= "<head>\n";
      $cab .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />\n";
      foreach ($this->javaScript as $js) {
          $cab .= "<script src=\"". $js ."\" type=\"text/javascript\" />\n";
      }
      foreach ($this->css as $c) {
          $cab .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"". $c . "\" />\n";
      }
      $cab .= "<title>". $this->titulo ."</title>\n";
      $cab .= "</head>\n";
      echo $cab;
    }
    
    public function imprimeCabecalhoHTML($indexar, $nm_site) {
      //echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?". ">\n";
      echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
      echo "<html lang=\"pt-br\">\n";
      echo "<head>\n";
//criar codigo para este site      
//      echo "<meta name=\"google-site-verification\" content=\"H_iXGE5AcCbOXpzRBc947-qVBbEQ_r4GrWIGEzJ9iF8\" />\n"; 
      echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\">\n";

      foreach ($this->javaScript as $js) {
          include $js;
      }

      foreach ($this->css as $c) {
          echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"". $c . "\" />\n";
      }
      if ($indexar) {
        echo "<meta name=\"robots\" content=\"noindex\">\n";
      }
      echo "<meta NAME=\"description\" CONTENT=\"".$this->descricao."\">\n";
      echo "<meta NAME=\"keywords\" CONTENT=\"".$this->palavras_chave."\">\n";
      echo "<meta NAME=\"url\" CONTENT=\"".$this->link."\">\n";
      echo "<meta NAME=\"language\" CONTENT=\"Portuguese\">\n";
      echo "<title>".$this->compoeTitulo($indexar, $nm_site)."</title>\n";

      echo "<script type=\"text/javascript\">\n";
      echo "window.onbeforeunload = function(e) {";
      echo "  var teste = document.getElementById('digitou').value;";
      echo "  if (teste == '1') {";
      echo "    var dialogText = 'Dialog text here';";
      echo "    e.returnValue = dialogText;";
      echo "    return dialogText;";
      echo "  }";
      echo "}";
      echo "</script>";




      echo "</head>\n";
    }
    
    public function compoeTitulo($indexar, $nm_site) {
      if ($indexar) {
        require_once 'menu/menu.php';                                           $menu = new Menu();
        $dados = $menu->retornaDadosMenu($this->secao, $this->subsecao, '0');
        return $dados['ds_menu_completo']." ".$nm_site." | ".$this->titulo;
      } else {
        if ($nm_site != $this->titulo) {
          return $nm_site." | ".$this->titulo;
        } else {
          return $nm_site;
        }
      }
    }
    
  }
?>