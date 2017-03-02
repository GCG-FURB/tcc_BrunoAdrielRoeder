<?php
  class Contador {
  
    public function __construct() {
    }
    
    public function contabiliza() {
      //verificar ip do usuario
      $ip= $_SERVER['REMOTE_ADDR'];
      
      //testar se ip já se conectou hoje
      $acessou= $this->verificarAcessoHoje($ip);
      if ($acessou == 0) {
        //se nao se acessou hoje - contar acesso
        $this->incrementarAcessos(); 
        //registrar acesso ip
        $this->registrarAcessoIp($ip);
      }
      
    }

    public function contabilizaSecao() {
      $ip= $_SERVER['REMOTE_ADDR'];
      $this->incrementarAcessos(); 
      //registrar acesso ip
      $this->registrarAcessoIp($ip);
    }
        
    public function imprimeContador() {
      //selecionar numero atual de acessos
      $acessos= $this->selectNumeroAcessos();
      while (strlen($acessos) < 8) {
        $acessos= "0".$acessos;
      }
      return "<p class=\"fontConteudoCentralizado\">Acessos:<br />".$acessos."</p>\n";
    }
    
    private function incrementarAcessos() {
      //selecionar numero atual de acessos
      $acessos= $this->selectNumeroAcessos();
      //incremetar 1
      $acessos = $acessos + 1;
      //fazer update
      $this->atualizarContadorAcessos($acessos);
    }
    
    public function selectNumeroAcessos() {
      $sql  = "SELECT * ".
              "FROM life_contador ".
              "WHERE cd_contador = '1'";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONTADOR");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['vl_contador'];
    }
    
    private function verificarAcessoHoje($ip) {
      $hoje = date('Y-m-d');
      $sql  = "SELECT COUNT(cd_contador) qtd_acessos ".
              "FROM life_contador_acessos ".
              "WHERE ip_acesso = '$ip' ".
              "AND dt_acesso = '$hoje' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco dados! - TABELA CONTADOR ACESSOS");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['qtd_acessos'];
    }
    
    private function registrarAcessoIp($ip) {
      $hoje = date('Y-m-d');
      $sql  = "INSERT INTO life_contador_acessos ".
              "(ip_acesso, dt_acesso) ".
              "VALUES ".
              "('$ip', '$hoje')";
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONTADOR ACESSOS");
      $saida = mysql_affected_rows();
      return $saida;               
    }
    
    private function atualizarContadorAcessos($acessos) {
      $sql  = "UPDATE life_contador ".
              "SET vl_contador = '$acessos' ".
              "WHERE cd_contador = '1'";
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA CONTADOR");
      $saida = mysql_affected_rows();
      return $saida;           
    }
  }
?>
