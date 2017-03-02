<?php
  //inclusao de conexao
  include_once "conexao/conexao.php"; 
  //criacao da conexao
  $conexao= new Conexao('localhost', 'life', 'root', '');
  //$conexao= new Conexao("localhost", "", "", "");
  //conectar
  $conexao->conectar();
?>
