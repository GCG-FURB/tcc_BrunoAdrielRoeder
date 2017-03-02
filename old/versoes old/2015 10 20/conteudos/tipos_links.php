<?php
  class TipoLink {
    
    public function __construct () {
    }
    

    public function retornaSeletorTiposLinks($cd_tipo_link) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $itens = $this->selectTiposLinks('1');
      
      $opcoes= array();
      $opcao= array();        $opcao[] = '0';                     $opcao[]= 'Selecione um Tipo de Link';         $opcoes[]= $opcao;
      foreach ($itens as $it) {
        $opcao= array();      $opcao[] = $it['cd_tipo_link'];     $opcao[]= $it['nm_tipo_link'];                 $opcoes[]= $opcao;
      }
      $util->linhaSeletor('Tipo de Link: ', 'cd_tipo_link', $cd_tipo_link, $opcoes);      
    }       
//**************BANCO DE DADOS**************************************************    
    public function selectTiposLinks($eh_ativo) {
      $sql  = "SELECT *  ".
              "FROM c_o_tipos_links ".
              "WHERE cd_tipo_link > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }            
      $sql.= "ORDER BY nm_tipo_link";  
      $result_id = @mysql_query($sql) or die ("TIPOS LINKS - Erro no banco de dados!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectDadosTipoLink($cd_tipo_link) {
      $sql  = "SELECT *  ".
              "FROM c_o_tipos_links ".
              "WHERE cd_tipo_link = '$cd_tipo_link' ";
      $result_id = @mysql_query($sql) or die ("TIPOS LINKS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }    
    
  }
?>