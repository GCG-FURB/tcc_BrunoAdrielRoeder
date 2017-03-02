<?php
  class TipoArquivo {
    
    public function __construct () {
    }
    
//**************BANCO DE DADOS**************************************************    
    public function selectTiposArquivos($eh_ativo) {
      $sql  = "SELECT *  ".
              "FROM life_tipos_associacoes_arquivos ".
              "WHERE cd_tipo_arquivo > 0 ";
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }            
      $sql.= "ORDER BY ds_tipo_arquivo";        
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA TIPOS ASSOCIAÇÕES ARQUIVOS");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }    
    
    public function selectDadosTipoArquivo($cd_tipo_arquivo) {
      $sql  = "SELECT *  ".
              "FROM life_tipos_associacoes_arquivos ".
              "WHERE cd_tipo_arquivo = '$cd_tipo_arquivo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA TIPOS ASSOCIAÇÕES ARQUIVOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function selectDadosTipoArquivoNome($nm_tipo_arquivo) {
      $sql  = "SELECT *  ".
              "FROM life_tipos_associacoes_arquivos ".
              "WHERE nm_tipo_arquivo = '$nm_tipo_arquivo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA TIPOS ASSOCIAÇÕES ARQUIVOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }    
    
  }
?>
