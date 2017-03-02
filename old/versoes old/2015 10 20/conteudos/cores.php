<?php
  class Cor {
    
    public function __construct () {
    }
    
    public function retornaSeletorCores($cd_cor) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();

      $itens = $this->selectCores();    
      $cores_areas = $are_con->selectCoresAreasConhecimento();
      echo "      <tr>\n";
      echo " 	      <td class=\"celConteudo\">Cor: </td>\n";
      echo "  		  <td class=\"celConteudo\">\n";
      echo "          <span id=\"area_cd_periodo\">\n";
      echo "            <select name=\"cd_cor\" id=\"cd_cor\" class=\"fontConteudo\">\n";
      echo "  		  	    <option ";
      if ($cd_cor == '0') {        echo " selected ";      }
      echo " value=\"0\" class=\"fontConteudo\">Selecione uma Cor</option>\n";
      foreach ($itens as $it) {
        $achou = false;
        foreach ($cores_areas as $ca) {
          if (($it['cd_cor'] == $ca['cd_cor']) && ($it['cd_cor'] != $cd_cor)) {            $achou = true;          }
        }
        if (!$achou) {
          echo "  			    <option ";
          if ($it['cd_cor'] == $cd_cor) {          echo " selected ";        }
          echo " value=\"".$it['cd_cor']."\" class=\"fontConteudo\" style=\"color:".$it['ds_cor'].";\">".$it['nm_cor']."</option>\n";
        }
      }
      echo "          </select>\n";      
      echo "          </span>\n";
      echo "        </td>\n";
      echo "      </tr>\n";
    }
    
    public function retornaCoresNaoUtilizadas() {
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();

      $itens = $this->selectCores();    
      $cores_areas = $are_con->selectCoresAreasConhecimento();

      $cores = array();      
      foreach ($itens as $it) {
        $achou = false;
        foreach ($cores_areas as $ca) {
          if ($it['cd_cor'] == $ca['cd_cor']) {            $achou = true;          }
        }
        if (!$achou) {
          $cores[] = $it['ds_cor'];
        }
      }
      return $cores;
    }    
    
//***************************BANCO DE DADOS*************************************
    public function selectCores() {
      $sql  = "SELECT * ".
              "FROM life_cores ".
              "ORDER BY nm_cor ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CORES!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }                               

  }
?>