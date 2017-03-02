<?php
  class Conexao {
    private $hostnameBanco; 
    private $databaseBanco;
    private $usernameBanco;
    private $passwordBanco;
    
    //construtor
    public function __construct ($host, $database, $username, $password) {
      $this->hostnameBanco = $host;
      $this->databaseBanco = $database;
      $this->usernameBanco = $username;
      $this->passwordBanco = $password;      
    }
    
    //conectar
    public function conectar() {
      $banco = mysql_connect($this->hostnameBanco, $this->usernameBanco, $this->passwordBanco) or trigger_error(mysql_error(),E_USER_ERROR);
      mysql_select_db($this->databaseBanco, $banco);
    }
  }
?>
