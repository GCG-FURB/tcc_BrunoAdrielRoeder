<?php
  //inclusao de conexao
  include_once "conexao/conexao.php"; 
  //criacao da conexao
  $conexao= new Conexao("localhost", "inversos", "root", "123456");
  //conectar
  $conexao->conectar();
?>