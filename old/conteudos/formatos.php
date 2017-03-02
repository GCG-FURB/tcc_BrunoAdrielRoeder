<?php
  class Formato {
    
    public function __construct () {
    }
    
    public function retornaSeletorFormato($cd_formato, $campo, $tamanho, $exibir_ajuda, $descricao, $denominacao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectFormato('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= $descricao;                        $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_formato'];                $opcao[]= $it['nm_formato'];                 $opcoes[]= $opcao;
      }
      $util->linhaSeletorAcaoHint($descricao, $denominacao, $campo, $cd_formato, $opcoes, $tamanho, false, $exibir_ajuda, " onChange=\"atualizarCampoTipoArquivo();\" ");
    } 
    
    public function imprimeDados($cd_formato, $descricao) {
      $dados = $this->selectDadosFormato($cd_formato);
      
      return "<b>".$descricao."</b> ".$dados['nm_formato'];
    }  

    public function retornaSeletorFormatoFiltro($cd_formato, $nome, $tamanho, $exibir_ajuda, $descricao, $ajuda) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $this->selectFormato('1');

      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho.";\" class=\"fontConteudoCampoSeletorHintFiltro\" placeholder=\"".$descricao."\" ";
      if ($exibir_ajuda == '1') {
        echo "alt=\"".$descricao."\" title=\"".$descricao."\" ";
      } else {
        echo "alt=\"".$ajuda."\" title=\"".$ajuda."\" ";
      }
      echo "tabindex=\"1\">\n";
      echo "  			    <option ";
      if ($cd_formato == '') {          echo " selected ";        }
      echo " value=\"0\">$descricao</option>\n";
      foreach ($itens as $it) {
        echo "  			    <option ";
        if ($it['cd_formato'] == $cd_formato) {          echo " selected ";        }
        echo " value=\"".$it['cd_formato']."\">".$it['nm_formato']."</option>\n";
      }
      echo "          </select>\n";
      if ($exibir_ajuda == '1') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            Selecione o formato desejado para pesquisar por Objetos de Aprendizagem.\n";
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\">\n";
      }
    }
//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************    
    public function selectFormato($eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_formatos ".
              "WHERE cd_formato > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY nm_formato ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA FORMATOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
       
    public function selectDadosFormato($cd_formato) {
      $sql  = "SELECT * ".
              "FROM life_formatos ".
              "WHERE cd_formato = '$cd_formato' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA FORMATOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

  }
?>