<?php
  class Formato {
    
    public function __construct () {
    }
    
    public function retornaSeletorFormato($cd_formato, $campo, $tamanho, $exibir_ajuda, $descricao) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectFormato('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                              $opcao[]= $descricao;                        $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_formato'];                $opcao[]= $it['nm_formato'];                 $opcoes[]= $opcao;
      }
      $util->linhaSeletorAcaoHint($descricao, $campo, $cd_formato, $opcoes, $tamanho, false, $exibir_ajuda, " onChange=\"atualizarCampoTipoArquivo();\" ");
    } 
    
    public function imprimeDados($cd_formato, $descricao) {
      $dados = $this->selectDadosFormato($cd_formato);
      
      return $descricao.': '.$dados['nm_formato'].'<br />';
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