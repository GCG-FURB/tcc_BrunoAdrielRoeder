<?php
  class ObjetoAprendizagemAvaliacao {
    
    public function __construct () {
    }

    public function registrarAvaliacaoObjetoAprendizagem() {
      $cd_usuario = addslashes($_SESSION['life_codigo']);
      $cd_objeto_aprendizagem = addslashes($_POST['cd_objeto_aprendizagem']);
      $nr_estrelas = addslashes($_POST['nr_estrelas']);
      if ($this->existeAvalicaoUsuarioObjetoAprendizagem($cd_usuario, $cd_objeto_aprendizagem)) {
        if ($this->atualizarAvalicaoUsuarioObjetoAprendizagem($cd_usuario, $cd_objeto_aprendizagem, $nr_estrelas)) {
          echo "<p class=\"fontConteudoSucesso\">Sua avaliação ao Objeto de Aprendizagem foi alterada!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Você havia aplicado a mesma avaliação Objeto de Aprendizagem anteriormente!</p>\n";
        }
      } else {
        if ($this->registrarAvalicaoUsuarioObjetoAprendizagem($cd_usuario, $cd_objeto_aprendizagem, $nr_estrelas)) {
          echo "<p class=\"fontConteudoSucesso\">Sua avaliação ao Objeto de Aprendizagem foi registrada!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao registrar sua avaliação Objeto de Aprendizagem!</p>\n";
        }
      }
    }

    public function existeAvalicaoUsuarioObjetoAprendizagem($cd_usuario, $cd_objeto_aprendizagem) {
      $dados = $this->selectAvalicaoUsuarioObjetoAprendizagem($cd_usuario, $cd_objeto_aprendizagem);
      if ($dados['cd_objeto_aprendizagem_avaliacao'] != '') {
        return true;
      } else {
        return false;
      }
    }

    public function retornaAvaliacaoObjetoAprendizagem($cd_objeto_aprendizagem) {
      $avaliacao = $this->retornaNotaMedia($cd_objeto_aprendizagem);
      //echo "    <p class=\"fontComandosFiltros\">\n";
      //echo "      Avaliação Geral:<br />\n";
      if ($avaliacao > 4.5) {
        $nota = '5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 4) {
        $nota = '4,5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3.5) {
        $nota = '4';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3) {
        $nota = '3.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2.5) {
        $nota = '3';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2) {
        $nota = '2.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1.5) {
        $nota = '2';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1) {
        $nota = '1.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0.5) {
        $nota = '1';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0) {
        $nota = '0.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } else {
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
      }
      //echo "    </p>\n";
    }

    public function retornaAvaliacaoObjetoAprendizagemCompleto($cd_objeto_aprendizagem) {
      $avaliacao = $this->retornaNotaMedia($cd_objeto_aprendizagem);
      if ($avaliacao > 4.5) {
        $nota = '5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 4) {
        $nota = '4,5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3.5) {
        $nota = '4';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3) {
        $nota = '3.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2.5) {
        $nota = '3';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2) {
        $nota = '2.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1.5) {
        $nota = '2';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1) {
        $nota = '1.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0.5) {
        $nota = '1';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0) {
        $nota = '0.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" title=\"Avalição do Objeto de Aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } else {
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Objeto de Aprendizagem ainda não avaliado\" title=\"Objeto de Aprendizagem ainda não avaliado\" border=\"0\">\n";
      }
    }

/*
    public function controleExibicao($secao, $subsecao, $item) {
      if (isset($_GET['acao']))  {      $acao = addslashes($_GET['acao']);          } else {      $acao = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);          } else {      $ativas = 1;          }
      if (isset($_GET['cd']))    {      $codigo = addslashes($_GET['cd']);          } else {      $codigo = '';         }
      if (isset($_GET['od']))    {      $ordem = addslashes($_GET['od']);           } else {      $ordem = 'oa';        }
      if (isset($_GET['fc']))    {      $funcao = addslashes($_GET['fc']);          } else {      $funcao = '';         }
      if (isset($_GET['tp']))    {      $tipo = addslashes($_GET['tp']);            } else {      $tipo = '';           }
      if (isset($_GET['at']))    {      $ativas = addslashes($_GET['at']);   $_SESSION['life_oa_ativas'] = $ativas;     } elseif (isset($_SESSION['life_oa_ativas']))  {      $ativas = $_SESSION['life_oa_ativas'];    } else {      $ativas = '1';     }
      if (isset($_GET['od']))    {      $ordem = addslashes($_GET['od']);   $_SESSION['life_oa_ordem'] = $ordem;        } elseif (isset($_SESSION['life_oa_ordem']))   {      $ordem = $_SESSION['life_oa_ordem'];      } else {      $ordem = 'oa';     }

      $_SESSION['life_c_eh_proprietario'] = '1';

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 

      if ($codigo > 0) {
        require_once 'conteudos/pessoas.php';                                   $pes = new Pessoa();
        
        $dados = $this->selectDadosObjetoAprendizagem($codigo);
        $pessoa = $pes->selectDadosPessoaUsuario($dados['cd_usuario_proprietario']);

        if ($permissoes_usuario[21] == '0') {
          $cd_usuario = $_SESSION['life_codigo'];
          if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
            echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este Objeto de Aprendizagem!</p>\n";
            return false;
          }
        }      
      }
      
      if ($funcao == 'agrupamento') {
        echo "<h2>Objeto de Aprendizagem: ".$dados['nm_objeto_aprendizagem']."</h2>\n";
        echo "<h3>Responsável: ".$pessoa['nm_pessoa']."</h3>\n";                

        require_once 'conteudos/objetos_aprendizagem_agrupamentos.php';         $oaa = new ObjetoAprendizagemAgrupamento();
        $oaa->controleExibicao($secao, $subsecao, $item, $codigo, $acao, $dados['nm_objeto_aprendizagem'], $pessoa['nm_pessoa']);
      } else {
        switch ($acao) {
          case "":
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;

          case "pesquisar":
            require_once 'conteudos/objetos_aprendizagem_pesquisa.php';         $oap = new ObjetoAprendizagemPesquisa();
            $oap->listarOpcoesPesquisaSimples($secao, $subsecao, $item, $ativas, $ordem, true);
          break;

          case "pesq":
            require_once 'conteudos/objetos_aprendizagem_pesquisa.php';         $oap = new ObjetoAprendizagemPesquisa();
            $_SESSION['life_c_pesquisando'] = '1';
            $oap->pesquisarSimples($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;

          case "zpesquisa":
            unset($_SESSION['life_c_pesquisando']);
            unset($_SESSION['life_c_termo']);
            unset($_SESSION['life_c_campos']);
            unset($_SESSION['life_c_eh_proprietario']);
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;

          case "cadastrar":
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas;
            $this->montarFormularioCadastro($link, $tipo);
          break;
          
          case "editar":
            $link = "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas;
            $this->montarFormularioEdicao($link, $codigo, $tipo);
          break;
        
          case "salvar":
            if (isset($_SESSION['life_edicao'])) {
              $this->salvarCadastroAlteracao();
              unset($_SESSION['life_edicao']);
            } 
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;  
      
          case "status":
            $this->alterarStatus($codigo);
            $this->listarAcoes($secao, $subsecao, $item, $ativas, $ordem);
            $this->listarItens($secao, $subsecao, $item, $ativas, $ordem);
          break;
        }             
      }
    }
                                                        
    private function listarAcoes($secao, $subsecao, $item, $ativas, $ordem) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $opcoes= array();
      $id = 1;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=1";                               $opcao['descricao']= "Ativos";                                                               $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=0";                               $opcao['descricao']= "Inativos";                                                             $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=2";                               $opcao['descricao']= "Ativos/Inativos";                                                      $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "";                                                                                                           $opcao['descricao']= "-----------------------------------------------------------";          $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=oa&at=".$ativas;                                $opcao['descricao']= "Ordenar por Objetos de Aprendizagem";                                  $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=pe&at=".$ativas;                                $opcao['descricao']= "Ordenar por Responsáveis";                                             $opcoes[]= $opcao;
      $opcao= array();      $opcao['indice']= $id;    $id+= 1;      $opcao['link']= "";                                                                                                           $opcao['descricao']= "-----------------------------------------------------------";          $opcoes[]= $opcao;

      foreach ($opcoes as $op) {        $nome = 'comandos_filtros_'.$op['indice'];        $util->campoHidden($nome, $op['link']);      }

      include 'js/js_navegacao.js';
      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=pesquisar\"><img src=\"icones/pesquisar.png\" alt=\"Pesquisar Objeto(s) de Aprendizagem\" title=\"Pesquisar Objeto(s) de Aprendizagem\" border=\"0\"></a> \n";
      if (isset($_SESSION['life_c_pesquisando'])) {
        echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&acao=zpesquisa\"><img src=\"icones/limpar_pesquisa.png\" alt=\"Limpar Pesquisa\" title=\"Limpar Pesquisa\" border=\"0\"></a> \n";
      }
      echo "  <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&tp=b&acao=cadastrar\"><img src=\"icones/novo_oa.png\" alt=\"Novo Objeto de Aprendizagem\" title=\"Novo Objeto de Aprendizagem\" border=\"0\"></a> \n";
      echo "  <select name=\"comandos_filtros\" id=\"comandos_filtros\" class=\"fontComandosFiltros\" onChange=\"navegar();\">\n";
      echo "    <option  value=\"0\" selected=\"selected\">-----------------------------------------------------------</option>\n";
      foreach ($opcoes as $op) {
        echo "    <option  value=\"".$op['indice']."\">".$op['descricao']."</option>\n";
      }
      echo "  </select>\n";
      echo "</p>\n";
    }


    private function listarItens($secao, $subsecao, $item, $ativas, $ordem) {
      require_once 'conteudos/objetos_aprendizagem_pesquisa.php';               $oap = new ObjetoAprendizagemPesquisa();
      
      $itens = $this->selectObjetosAprendizagem($ativas, $ordem, '', '', 'c');

      $mensagem = "Objetos de Aprendizagem ";
      if ($ativas == 1)   {        $mensagem.= "Ativos";      } elseif ($ativas == 0) {        $mensagem.= "Inativos";      }
      echo "<h2>".$mensagem."</h2>\n";      

      $oap->retornaDetalhesPesquisa();                  

      echo "  <table class=\"tabConteudo\">\n";
      $style = "linhaOn"; 
      echo "    <tr class=\"".$style."\">\n";
      echo "      <td class=\"celConteudo\" colspan=\"4\">Dados do Objeto de Aprendizagem:</td>\n";
      echo "      <td class=\"celConteudo\" style=\"width:18%;\">Ações:</td>\n";
      echo "    </tr>\n";

      foreach ($itens as $it) {
        $style = ($style!="linhaOf")?('linhaOf'):('linhaOn'); 
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nome:</td>\n";
        echo "      <td class=\"celConteudo\" colspan=\"3\">".$it['nm_objeto_aprendizagem']."</td>\n";
        echo "      <td class=\"celConteudo\" rowspan=\"3\">\n";
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"icones/informacoes.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo $this->detalharObjetoAprendizagem($it['cd_objeto_aprendizagem']);
        echo "        </span></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&tp=b&acao=editar\"><img src=\"icones/editar_basico.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Básico\" title=\"Editar Objeto de Aprendizagem - Cadastro Básico\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&tp=i&acao=editar\"><img src=\"icones/editar_intermediario.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" title=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&tp=c&acao=editar\"><img src=\"icones/editar_completo.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Completo\" title=\"Editar Objeto de Aprendizagem - Cadastro Completo\" border=\"0\"></a>\n";
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&od=".$ordem."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&acao=status\">";
        if ($it['eh_ativo'] == 1) {
          echo "          <img src=\"icones/inativar.png\" alt=\"Inativar\" title=\"Inativar\"border=\"0\"></a>\n";
        } else {
          echo "          <img src=\"icones/reativar.png\" alt=\"Reativar\" title=\"Reativar\"border=\"0\"></a>\n";
        }
        echo "        <a href=\"index.php?secao=".$secao."&sub=".$subsecao."&it=".$item."&at=".$ativas."&cd=".$it['cd_objeto_aprendizagem']."&fc=agrupamento\"><img src=\"icones/conteudos.png\" alt=\"Agrupamento de Objetos de Aprendizagem\" title=\"Agrupamento de Objetos de Aprendizagem\" border=\"0\"></a>\n";
        echo "      </td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nome Abrev.:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['ds_identifier']."</td>\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Responsável:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_pessoa']."</td>\n";
        echo "    </tr>\n";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Área Conh.:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_area_conhecimento']."</td>\n";
        echo "      <td class=\"celConteudoDestaque\" style=\"width:10%;\">Nível Educ.:</td>\n";
        echo "      <td class=\"celConteudo\">".$it['nm_nivel_educacional']."</td>\n";
        echo "    </tr>\n";      }
      echo "  </table>\n";       
    }

    private function detalharObjetoAprendizagem($cd_objeto_aprendizagem) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
     
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data da Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }

    private function montarFormularioCadastro($link, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
      $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
      $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
      $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
      $eh_informar_educational = $conf->retornaInformarEducational($tipo);
      $eh_informar_rights = $conf->retornaInformarRights($tipo);
      $eh_informar_relation = $conf->retornaInformarRelation($tipo);
      $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
      $eh_informar_classification = $conf->retornaInformarClassification($tipo);
      $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
      $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);

      $cd_objeto_aprendizagem = "";
      $nm_objeto_aprendizagem = "";
      $cd_general = "";
      $cd_lyfe_cycle = "";
      $cd_meta_metadata = "";
      $cd_technical = "";
      $cd_educational = "";
      $cd_rights = "";
      $cd_relation = "";
      $cd_annotation = "";
      $cd_classification = "";
      $cd_acessibility = "";
      $cd_segment_information_table = "";
      $eh_ativo = "1";
      
      $eh_liberado = $conf->retornaLiberacaoAutomaticaObjetosAprendizagem();
      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario_proprietario = $_SESSION['life_codigo'];
        $_SESSION['life_edicao']= 1;

        echo "  <h2>Cadastro de Objetos de Aprendizagem</h2>\n";
        $this->imprimeFormularioCadastro($link, $tipo, $cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario);
      }
    }

    private function montarFormularioEdicao($link, $cd_objeto_aprendizagem, $tipo) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);

      if ($permissoes_usuario[21] == '0') {
        $cd_usuario = $_SESSION['life_codigo'];
        if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
          echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este Objeto de Aprendizagem!</p>\n";
          return false;
        }
      }       

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();
      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_general = $dados['eh_informar_general'];
        $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
        $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
        $eh_informar_technical = '1'; //$dados['eh_informar_technical'];
        $eh_informar_educational = $dados['eh_informar_educational'];
        $eh_informar_rights = $dados['eh_informar_rights'];
        $eh_informar_relation = $dados['eh_informar_relation'];
        $eh_informar_annotation = $dados['eh_informar_annotation'];
        $eh_informar_classification = $dados['eh_informar_classification'];
        $eh_informar_acessibility = $dados['eh_informar_acessibility'];
        $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];      
      } else {
        $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
        $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
        $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
        $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
        $eh_informar_educational = $conf->retornaInformarEducational($tipo);
        $eh_informar_rights = $conf->retornaInformarRights($tipo);
        $eh_informar_relation = $conf->retornaInformarRelation($tipo);
        $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
        $eh_informar_classification = $conf->retornaInformarClassification($tipo);
        $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
        $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);
      }

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $cd_general = $dados['cd_general'];
      $cd_lyfe_cycle = $dados['cd_lyfe_cycle'];
      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $cd_technical = $dados['cd_technical'];
      $cd_educational = $dados['cd_educational'];
      $cd_rights = $dados['cd_rights'];
      $cd_relation = $dados['cd_relation'];
      $cd_annotation = $dados['cd_annotation'];
      $cd_classification = $dados['cd_classification'];
      $cd_acessibility = $dados['cd_acessibility'];
      $cd_segment_information_table = $dados['cd_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];

      $_SESSION['life_edicao']= 1;      
      echo "  <h2>Edição de Objeto de Aprendizagem</h2>\n";
      $this->imprimeFormularioCadastro($link, $tipo, $cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario);
    }                         

    private function imprimeFormularioCadastro($link, $tipo, $cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();

      echo "<p class=\"fontComandosFiltros\">\n";
      echo "  <a href=\"".$link."\"><img src=\"icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
      echo "</p>\n";

      include "js/js_cadastro_objeto_aprendizagem.js";                                                                           
      echo "  <form method=\"POST\" name=\"cadastro_o_a\" action=\"".$_SESSION['life_link_completo'].$link."&acao=salvar\" enctype=\"multipart/form-data\" onSubmit=\"return valida(this);\">\n";
      echo "    <table class=\"tabConteudo\">\n";

      $util->campoHidden('cd_objeto_aprendizagem', $cd_objeto_aprendizagem);
      $util->campoHidden('eh_informar_general', $eh_informar_general);
      $util->campoHidden('eh_informar_lyfe_cycle', $eh_informar_lyfe_cycle);
      $util->campoHidden('eh_informar_meta_metadata', $eh_informar_meta_metadata);
      $util->campoHidden('eh_informar_technical', $eh_informar_technical);
      $util->campoHidden('eh_informar_educational', $eh_informar_educational);
      $util->campoHidden('eh_informar_rights', $eh_informar_rights);
      $util->campoHidden('eh_informar_relation', $eh_informar_relation);
      $util->campoHidden('eh_informar_annotation', $eh_informar_annotation);
      $util->campoHidden('eh_informar_classification', $eh_informar_classification);
      $util->campoHidden('eh_informar_acessibility', $eh_informar_acessibility);
      $util->campoHidden('eh_informar_segment_information_table', $eh_informar_segment_information_table);
      $util->campoHidden('eh_ativo', $eh_ativo);
      $util->campoHidden('eh_liberado', $eh_liberado);
      $util->campoHidden('cd_usuario_proprietario', $cd_usuario_proprietario);

      $util->campoHidden('eh_imagem_ok', '1');

      $eh_obrigatorio_general = '1'; //$conf->retornaInformarGeneral('b');
      $eh_obrigatorio_lyfe_cycle = $conf->retornaInformarLyfeCycle('b');
      $eh_obrigatorio_meta_metadata = $conf->retornaInformarMetaMetadata('b');
      $eh_obrigatorio_technical = '1'; //$conf->retornaInformarTechnical('b');
      $eh_obrigatorio_educational = $conf->retornaInformarEducational('b');
      $eh_obrigatorio_rights = $conf->retornaInformarRights('b');
      $eh_obrigatorio_relation = $conf->retornaInformarRelation('b');
      $eh_obrigatorio_annotation = $conf->retornaInformarAnnotation('b');
      $eh_obrigatorio_classification = $conf->retornaInformarClassification('b');
      $eh_obrigatorio_acessibility = $conf->retornaInformarAcessibility('b');
      $eh_obrigatorio_segment_information_table = $conf->retornaInformarSegmentInformationTable('b');
      $util->campoHidden('eh_obrigatorio_general', $eh_obrigatorio_general);
      $util->campoHidden('eh_obrigatorio_lyfe_cycle', $eh_obrigatorio_lyfe_cycle);
      $util->campoHidden('eh_obrigatorio_meta_metadata', $eh_obrigatorio_meta_metadata);
      $util->campoHidden('eh_obrigatorio_technical', '1');
      $util->campoHidden('eh_obrigatorio_educational', $eh_obrigatorio_educational);
      $util->campoHidden('eh_obrigatorio_rights', $eh_obrigatorio_rights);
      $util->campoHidden('eh_obrigatorio_relation', $eh_obrigatorio_relation);
      $util->campoHidden('eh_obrigatorio_annotation', $eh_obrigatorio_annotation);
      $util->campoHidden('eh_obrigatorio_classification', $eh_obrigatorio_classification);
      $util->campoHidden('eh_obrigatorio_acessibility', $eh_obrigatorio_acessibility);
      $util->campoHidden('eh_obrigatorio_segment_information_table', $eh_obrigatorio_segment_information_table);

      require_once 'conteudos/objetos_aprendizagem_general.php';                $oa_gen = new ObjetoAprendizagemGeneral();
      $oa_gen->retornaFormularioCadastroEdicao($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';             $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
      $oa_lyf_cyc->retornaFormularioCadastroEdicao($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';          $oa_mtd = new ObjetoAprendizagemMetaMetadata();
      $oa_mtd->retornaFormularioCadastroEdicao($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_technical.php';              $oa_tec = new ObjetoAprendizagemTechnical();
      $oa_tec->retornaFormularioCadastroEdicao($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_educational.php';            $oa_edu = new ObjetoAprendizagemEducational();
      $oa_edu->retornaFormularioCadastroEdicao($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_rights.php';                 $oa_rig = new ObjetoAprendizagemRights();
      $oa_rig->retornaFormularioCadastroEdicao($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_relation.php';               $oa_rel = new ObjetoAprendizagemRelation();
      $oa_rel->retornaFormularioCadastroEdicao($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_annotation.php';             $oa_ann = new ObjetoAprendizagemAnnotation();
      $oa_ann->retornaFormularioCadastroEdicao($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_classification.php';         $oa_cla = new ObjetoAprendizagemClassification();
      $oa_cla->retornaFormularioCadastroEdicao($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_acessibility.php';           $oa_ace = new ObjetoAprendizagemAcessibility();
      $oa_ace->retornaFormularioCadastroEdicao($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);

      require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
      $oa_sit->retornaFormularioCadastroEdicao($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);

      $util->linhaBotao('Salvar');
      echo "    </table>\n";
      echo "    <p class=\"fontConteudoAlerta\">(*) Campos Obrigatórios</p>\n";
      echo "  </form>\n";       
      $util->posicionarCursor('cadastro', 'ds_general_title');
    }
                         

    private function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      if (isset($_SESSION['life_agrupador_termos_cadastro'])) {
        unset($_SESSION['life_agrupador_termos_cadastro']);
      }
      $_SESSION['life_agrupador_termos_cadastro'] = '';

      $cd_objeto_aprendizagem = addslashes($_POST['cd_objeto_aprendizagem']);
      $nm_objeto_aprendizagem = $util->limparVariavel($_POST['ds_general_title']);

      require_once 'conteudos/objetos_aprendizagem_general.php';                $oa_gen = new ObjetoAprendizagemGeneral();
      $cd_general = $oa_gen->salvarCadastroAlteracao();
      $eh_informar_general = addslashes($_POST['eh_informar_general']);

      require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';             $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
      $cd_lyfe_cycle = $oa_lyf_cyc->salvarCadastroAlteracao();
      $eh_informar_lyfe_cycle = addslashes($_POST['eh_informar_lyfe_cycle']);

      require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';          $oa_mtd = new ObjetoAprendizagemMetaMetadata();
      $cd_meta_metadata = $oa_mtd->salvarCadastroAlteracao();
      $eh_informar_meta_metadata = addslashes($_POST['eh_informar_meta_metadata']);

      require_once 'conteudos/objetos_aprendizagem_technical.php';              $oa_tec = new ObjetoAprendizagemTechnical();
      $cd_technical = $oa_tec->salvarCadastroAlteracao();
      $eh_informar_technical = addslashes($_POST['eh_informar_technical']);

      require_once 'conteudos/objetos_aprendizagem_educational.php';            $oa_edu = new ObjetoAprendizagemEducational();
      $cd_educational = $oa_edu->salvarCadastroAlteracao();
      $eh_informar_educational = addslashes($_POST['eh_informar_educational']);

      require_once 'conteudos/objetos_aprendizagem_rights.php';                 $oa_rig = new ObjetoAprendizagemRights();
      $cd_rights = $oa_rig->salvarCadastroAlteracao();
      $eh_informar_rights = addslashes($_POST['eh_informar_rights']);

      require_once 'conteudos/objetos_aprendizagem_relation.php';               $oa_rel = new ObjetoAprendizagemRelation();
      $cd_relation = $oa_rel->salvarCadastroAlteracao();
      $eh_informar_relation = addslashes($_POST['eh_informar_relation']);

      require_once 'conteudos/objetos_aprendizagem_annotation.php';             $oa_ann = new ObjetoAprendizagemAnnotation();
      $cd_annotation = $oa_ann->salvarCadastroAlteracao();
      $eh_informar_annotation = addslashes($_POST['eh_informar_annotation']);

      require_once 'conteudos/objetos_aprendizagem_classification.php';         $oa_cla = new ObjetoAprendizagemClassification();
      $cd_classification = $oa_cla->salvarCadastroAlteracao();
      $eh_informar_classification = addslashes($_POST['eh_informar_classification']);

      require_once 'conteudos/objetos_aprendizagem_acessibility.php';           $oa_ace = new ObjetoAprendizagemAcessibility();
      $cd_acessibility = $oa_ace->salvarCadastroAlteracao();
      $eh_informar_acessibility = addslashes($_POST['eh_informar_acessibility']);

      require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
      $cd_segment_information_table = $oa_sit->salvarCadastroAlteracao();
      $eh_informar_segment_information_table = addslashes($_POST['eh_informar_segment_information_table']);

      $eh_ativo = addslashes($_POST['eh_ativo']);
      $eh_liberado = addslashes($_POST['eh_liberado']);
      $cd_usuario_proprietario = addslashes($_POST['cd_usuario_proprietario']);
      $ds_informacoes_o_a = addslashes($_SESSION['life_agrupador_termos_cadastro']);
      unset($_SESSION['life_agrupador_termos_cadastro']);

      $lk_seo = $util->retornaLinkSEO($nm_objeto_aprendizagem, 'life_objetos_aprendizagem', 'lk_seo', '250', $cd_objeto_aprendizagem);

      if ($cd_objeto_aprendizagem > 0) {
        $cd_objeto_aprendizagem = $this->alterarObjetoAprendizagem($cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $eh_informar_general, $eh_informar_lyfe_cycle, $eh_informar_meta_metadata, $eh_informar_technical, $eh_informar_educational, $eh_informar_rights, $eh_informar_relation, $eh_informar_annotation, $eh_informar_classification, $eh_informar_acessibility, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a);
        if (($cd_objeto_aprendizagem > 0) || ($cd_general > 0) || ($cd_lyfe_cycle > 0) || ($cd_meta_metadata > 0) || ($cd_technical > 0) || ($cd_educational > 0) || ($cd_rights > 0) || ($cd_relation > 0) || ($cd_annotation > 0) || ($cd_classification > 0) || ($cd_acessibility > 0) || ($cd_segment_information_table > 0)) {
          echo "<p class=\"fontConteudoSucesso\">Objeto de Aprendizagem editado com sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas na edição do Objeto de Aprendizagem, ou nenhuma informação alterada!</p>\n";
        }  
      } else {
        if (($cd_general > 0) && ($cd_lyfe_cycle > 0) && ($cd_meta_metadata > 0) && ($cd_technical > 0) && ($cd_educational > 0) && ($cd_rights > 0) && ($cd_relation > 0) && ($cd_annotation > 0) && ($cd_classification > 0) && ($cd_acessibility > 0) && ($cd_segment_information_table > 0)) {
          if ($this->inserirObjetoAprendizagem($nm_objeto_aprendizagem, $cd_general, $eh_informar_general, $cd_lyfe_cycle, $eh_informar_lyfe_cycle, $cd_meta_metadata, $eh_informar_meta_metadata, $cd_technical, $eh_informar_technical, $cd_educational, $eh_informar_educational, $cd_rights, $eh_informar_rights, $cd_relation, $eh_informar_relation, $cd_annotation, $eh_informar_annotation, $cd_classification, $eh_informar_classification, $cd_acessibility, $eh_informar_acessibility, $cd_segment_information_table, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a)) {
            echo "<p class=\"fontConteudoSucesso\">Objeto de Aprendizagem cadastrado com sucesso!</p>\n";
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Objeto de Aprendizagem!</p>\n";
          }
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas no cadastro do Objeto de Aprendizagem!</p>\n";
        }
      }
    }

    private function alterarStatus($cd_objeto_aprendizagem) {
      if (isset($_SESSION['life_permissoes'])) {
        $permissoes_usuario= $_SESSION['life_permissoes'];
      } else {
        $permissoes_usuario= '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';
      } 

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);

      if ($permissoes_usuario[21] == '0') {
        $cd_usuario = $_SESSION['life_codigo'];
        if ($dados['cd_usuario_proprietario'] != $cd_usuario) {
          echo "<p class=\"fontConteudoAlerta\">Você não possui permissão para editar este Objeto de Aprendizagem!</p>\n";
          return false;
        }
      }       

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $eh_informar_general = $dados['eh_informar_general'];
      $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
      $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
      $eh_informar_technical = $dados['eh_informar_technical'];
      $eh_informar_educational = $dados['eh_informar_educational'];
      $eh_informar_rights = $dados['eh_informar_rights'];
      $eh_informar_relation = $dados['eh_informar_relation'];
      $eh_informar_annotation = $dados['eh_informar_annotation'];
      $eh_informar_classification = $dados['eh_informar_classification'];
      $eh_informar_acessibility = $dados['eh_informar_acessibility'];
      $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];
      $lk_seo = $dados['lk_seo'];
      $ds_informacoes_o_a = $dados['ds_informacoes_o_a'];

      if ($eh_ativo == '1') {        $eh_ativo = '0';      } else {        $eh_ativo = '1';      }

      if ($this->alterarObjetoAprendizagem($cd_objeto_aprendizagem, $nm_objeto_aprendizagem, $eh_informar_general, $eh_informar_lyfe_cycle, $eh_informar_meta_metadata, $eh_informar_technical, $eh_informar_educational, $eh_informar_rights, $eh_informar_relation, $eh_informar_annotation, $eh_informar_classification, $eh_informar_acessibility, $eh_informar_segment_information_table, $eh_ativo, $eh_liberado, $cd_usuario_proprietario, $lk_seo, $ds_informacoes_o_a)) {
        echo "<p class=\"fontConteudoSucesso\">Status do Objeto de Aprendizagem alterado com sucesso!</p>\n";
      } else {
        echo "<p class=\"fontConteudoSucesso\">Problemas ao alterar Status do Objeto de Aprendizagem!</p>\n";
      }                                                                                               
    }

    public function retornadetalhamentoObjetoAprendizagemBasico($cd_objeto_aprendizagem) {
      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $cd_general = $dados['cd_general'];

      $retorno = '<p class=\"fontDetalhar\">';
      $retorno.= 'Objeto de Aprendizagem: '.$nm_objeto_aprendizagem.'<br />';
      $retorno.= '</p>';

      require_once 'conteudos/objetos_aprendizagem_general.php';                $oa_gen = new ObjetoAprendizagemGeneral();
      $retorno.= '<p class=\"fontDetalhar\">';
      $retorno.= '<b>Informações Gerais</b><br />';
      $retorno.= $oa_gen->imprimeDados($cd_general);
      $retorno.= '</p>';

      return $retorno;
    }

    public function detalharObjetoAprendizagemBasico($cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
      
      $tipo = 'b';

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_general = $dados['eh_informar_general'];
        $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
        $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
        $eh_informar_technical = '1'; //$dados['eh_informar_technical'];
        $eh_informar_educational = $dados['eh_informar_educational'];
        $eh_informar_rights = $dados['eh_informar_rights'];
        $eh_informar_relation = $dados['eh_informar_relation'];
        $eh_informar_annotation = $dados['eh_informar_annotation'];
        $eh_informar_classification = $dados['eh_informar_classification'];
        $eh_informar_acessibility = $dados['eh_informar_acessibility'];
        $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];      
      } else {
        $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
        $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
        $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
        $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
        $eh_informar_educational = $conf->retornaInformarEducational($tipo);
        $eh_informar_rights = $conf->retornaInformarRights($tipo);
        $eh_informar_relation = $conf->retornaInformarRelation($tipo);
        $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
        $eh_informar_classification = $conf->retornaInformarClassification($tipo);
        $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
        $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);
      }

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $cd_general = $dados['cd_general'];
      $cd_lyfe_cycle = $dados['cd_lyfe_cycle'];
      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $cd_technical = $dados['cd_technical'];
      $cd_educational = $dados['cd_educational'];
      $cd_rights = $dados['cd_rights'];
      $cd_relation = $dados['cd_relation'];
      $cd_annotation = $dados['cd_annotation'];
      $cd_classification = $dados['cd_classification'];
      $cd_acessibility = $dados['cd_acessibility'];
      $cd_segment_information_table = $dados['cd_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];

      $retorno = '<p class=\"fontDetalhar\">';
      $retorno.= 'Objeto de Aprendizagem: '.$nm_objeto_aprendizagem.'<br />';
      $retorno.= '</p>';
      
      if ($eh_informar_general == '1') {
        require_once 'conteudos/objetos_aprendizagem_general.php';              $oa_gen = new ObjetoAprendizagemGeneral();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Gerais</b><br />';      
        $retorno.= $oa_gen->imprimeDados($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_lyfe_cycle == '1') {
        require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';           $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Ciclo de Vida</b><br />';
        $retorno.= $oa_lyf_cyc->imprimeDados($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_meta_metadata == '1') {
        require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';        $oa_mtd = new ObjetoAprendizagemMetaMetadata();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações de Metadados</b><br />';
        $retorno.= $oa_mtd->imprimeDados($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_technical == '1') {
        require_once 'conteudos/objetos_aprendizagem_technical.php';            $oa_tec = new ObjetoAprendizagemTechnical();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Técnicas</b><br />';  
        $retorno.= $oa_tec->imprimeDados($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo, false);
        $retorno.= '</p>';
      }

      if ($eh_informar_educational == '1') {
        require_once 'conteudos/objetos_aprendizagem_educational.php';          $oa_edu = new ObjetoAprendizagemEducational();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Educacionais</b><br />';
        $retorno.= $oa_edu->imprimeDados($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_rights == '1') {
        require_once 'conteudos/objetos_aprendizagem_rights.php';               $oa_rig = new ObjetoAprendizagemRights();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Direitos Autorais</b><br />';
        $retorno.= $oa_rig->imprimeDados($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_relation == '1') {
        require_once 'conteudos/objetos_aprendizagem_relation.php';             $oa_rel = new ObjetoAprendizagemRelation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Relação</b><br />';
        $retorno.= $oa_rel->imprimeDados($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_annotation == '1') {
        require_once 'conteudos/objetos_aprendizagem_annotation.php';           $oa_ann = new ObjetoAprendizagemAnnotation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Anotações</b><br />';
        $retorno.= $oa_ann->imprimeDados($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_classification == '1') {
        require_once 'conteudos/objetos_aprendizagem_classification.php';       $oa_cla = new ObjetoAprendizagemClassification();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Classificação</b><br />';
        $retorno.= $oa_cla->imprimeDados($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_acessibility == '1') {
        require_once 'conteudos/objetos_aprendizagem_acessibility.php';         $oa_ace = new ObjetoAprendizagemAcessibility();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Acessibilidade</b><br />';
        $retorno.= $oa_ace->imprimeDados($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_segment_information_table == '1') {
        require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Segmentos</b><br />';
        $retorno.= $oa_sit->imprimeDados($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }
      return $retorno;
    }
    
    public function retornaInformacoesCompletasObjetoAprendizagem($cd_objeto_aprendizagem) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagem($cd_objeto_aprendizagem);
      
      $tipo = 'c';

      $eh_manter_configuracoes_originais = $conf->retornaManterConfiguracoesOriginais();

      if ($eh_manter_configuracoes_originais == '1') {
        $eh_informar_general = $dados['eh_informar_general'];
        $eh_informar_lyfe_cycle = $dados['eh_informar_lyfe_cycle'];
        $eh_informar_meta_metadata = $dados['eh_informar_meta_metadata'];
        $eh_informar_technical = '1'; //$dados['eh_informar_technical'];
        $eh_informar_educational = $dados['eh_informar_educational'];
        $eh_informar_rights = $dados['eh_informar_rights'];
        $eh_informar_relation = $dados['eh_informar_relation'];
        $eh_informar_annotation = $dados['eh_informar_annotation'];
        $eh_informar_classification = $dados['eh_informar_classification'];
        $eh_informar_acessibility = $dados['eh_informar_acessibility'];
        $eh_informar_segment_information_table = $dados['eh_informar_segment_information_table'];      
      } else {
        $eh_informar_general = '1'; //$conf->retornaInformarGeneral($tipo);
        $eh_informar_lyfe_cycle = $conf->retornaInformarLyfeCycle($tipo);
        $eh_informar_meta_metadata = $conf->retornaInformarMetaMetadata($tipo);
        $eh_informar_technical = '1'; //$conf->retornaInformarTechnical($tipo);
        $eh_informar_educational = $conf->retornaInformarEducational($tipo);
        $eh_informar_rights = $conf->retornaInformarRights($tipo);
        $eh_informar_relation = $conf->retornaInformarRelation($tipo);
        $eh_informar_annotation = $conf->retornaInformarAnnotation($tipo);
        $eh_informar_classification = $conf->retornaInformarClassification($tipo);
        $eh_informar_acessibility = $conf->retornaInformarAcessibility($tipo);
        $eh_informar_segment_information_table = $conf->retornaInformarSegmentInformationTable($tipo);
      }

      $cd_objeto_aprendizagem = $dados['cd_objeto_aprendizagem'];
      $nm_objeto_aprendizagem = $dados['nm_objeto_aprendizagem'];
      $cd_general = $dados['cd_general'];
      $cd_lyfe_cycle = $dados['cd_lyfe_cycle'];
      $cd_meta_metadata = $dados['cd_meta_metadata'];
      $cd_technical = $dados['cd_technical'];
      $cd_educational = $dados['cd_educational'];
      $cd_rights = $dados['cd_rights'];
      $cd_relation = $dados['cd_relation'];
      $cd_annotation = $dados['cd_annotation'];
      $cd_classification = $dados['cd_classification'];
      $cd_acessibility = $dados['cd_acessibility'];
      $cd_segment_information_table = $dados['cd_segment_information_table'];
      $eh_ativo = $dados['eh_ativo'];
      $eh_liberado = $dados['eh_liberado'];
      $cd_usuario_proprietario = $dados['cd_usuario_proprietario'];

      $retorno = '<p class=\"fontDetalhar\">';
      $retorno.= 'Objeto de Aprendizagem: '.$nm_objeto_aprendizagem.'<br />';
      $retorno.= '</p>';
      
      if ($eh_informar_general == '1') {
        require_once 'conteudos/objetos_aprendizagem_general.php';              $oa_gen = new ObjetoAprendizagemGeneral();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Gerais</b><br />';      
        $retorno.= $oa_gen->imprimeDados($cd_general, $eh_informar_general, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_lyfe_cycle == '1') {
        require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';           $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Ciclo de Vida</b><br />';
        $retorno.= $oa_lyf_cyc->imprimeDados($cd_lyfe_cycle, $eh_informar_lyfe_cycle, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_meta_metadata == '1') {
        require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';        $oa_mtd = new ObjetoAprendizagemMetaMetadata();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações de Metadados</b><br />';
        $retorno.= $oa_mtd->imprimeDados($cd_meta_metadata, $eh_informar_meta_metadata, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_technical == '1') {
        require_once 'conteudos/objetos_aprendizagem_technical.php';            $oa_tec = new ObjetoAprendizagemTechnical();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Técnicas</b><br />';  
        $retorno.= $oa_tec->imprimeDados($cd_technical, $eh_informar_technical, $eh_manter_configuracoes_originais, $tipo, false);
        $retorno.= '</p>';
      }

      if ($eh_informar_educational == '1') {
        require_once 'conteudos/objetos_aprendizagem_educational.php';          $oa_edu = new ObjetoAprendizagemEducational();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações Educacionais</b><br />';
        $retorno.= $oa_edu->imprimeDados($cd_educational, $eh_informar_educational, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_rights == '1') {
        require_once 'conteudos/objetos_aprendizagem_rights.php';               $oa_rig = new ObjetoAprendizagemRights();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Direitos Autorais</b><br />';
        $retorno.= $oa_rig->imprimeDados($cd_rights, $eh_informar_rights, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_relation == '1') {
        require_once 'conteudos/objetos_aprendizagem_relation.php';             $oa_rel = new ObjetoAprendizagemRelation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Informações sobre Relação</b><br />';
        $retorno.= $oa_rel->imprimeDados($cd_relation, $eh_informar_relation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_annotation == '1') {
        require_once 'conteudos/objetos_aprendizagem_annotation.php';           $oa_ann = new ObjetoAprendizagemAnnotation();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Anotações</b><br />';
        $retorno.= $oa_ann->imprimeDados($cd_annotation, $eh_informar_annotation, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_classification == '1') {
        require_once 'conteudos/objetos_aprendizagem_classification.php';       $oa_cla = new ObjetoAprendizagemClassification();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Classificação</b><br />';
        $retorno.= $oa_cla->imprimeDados($cd_classification, $eh_informar_classification, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_acessibility == '1') {
        require_once 'conteudos/objetos_aprendizagem_acessibility.php';         $oa_ace = new ObjetoAprendizagemAcessibility();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Acessibilidade</b><br />';
        $retorno.= $oa_ace->imprimeDados($cd_acessibility, $eh_informar_acessibility, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }

      if ($eh_informar_segment_information_table == '1') {
        require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
        $retorno.= '<p class=\"fontDetalhar\">';
        $retorno.= '<b>Segmentos</b><br />';
        $retorno.= $oa_sit->imprimeDados($cd_segment_information_table, $eh_informar_segment_information_table, $eh_manter_configuracoes_originais, $tipo);
        $retorno.= '</p>';
      }
      return $retorno;    
    }

    private function retornaArrayTermos($termo) {
      $termo= str_replace(chr(13), " ", $termo);
      $termo= str_replace(chr(11), " ", $termo);
      $termo= str_replace("\"","'",$termo);
      $termo= str_replace("%","'",$termo);

      $termos = array();
      $palavra = '';
      for ($i=0; $i<strlen($termo); $i++) {
        if ($termo[$i] != ' ') {
          $palavra.= $termo[$i];
        } else {
          if ($palavra != '') {
            $termos[] = $palavra;
            $palavra = '';
          }
        }
      }
      if ($palavra != '') {
        $termos[] = $palavra;
      }
      return $termos;
    }
*/
//***************EXIBICAO PUBLICA***********************************************

/*
    public function retornaConteudoCapaTopo($pagina, $lista_paginas) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/niveis_educacionais.php';                         $niv_edu = new NivelEducacional();
      require_once 'conteudos/objetos_aprendizagem_pesquisa.php';               $oa_pes = new ObjetoAprendizagemPesquisa();

      $_SESSION['life_c_eh_proprietario'] = '0';
      $itens = $this->selectObjetosAprendizagem('1', 'no', '0', $conf->retornaNumeroItensCapaTopo(), 'i');

      $listados = array();
      foreach ($itens as $it) {
        $listados[] = $it['cd_objeto_aprendizagem'];
        echo "  <a href=\"".$_SESSION['life_link_completo']."objetos/".$it['lk_seo']."\">\n";
        echo "    <div class=\"divUmElementoCapaTopo\" style=\"background-color:".$it['ds_cor'].";\">\n";
        if ($it['ds_arquivo_imagem_especifica'] != '') {
          echo "      <img src=\"".$_SESSION['life_link_completo'].$it['ds_pasta_arquivo_imagem'].$it['ds_arquivo_imagem_especifica']."\" alt=\"".$it['nm_objeto_aprendizagem']."\" title=\"".$it['nm_objeto_aprendizagem']."\" border=\"0\" width=\"185\" height=\"185\" style=\"margin-top:1px;\">\n";
        } else {
          echo "      <img src=\"".$_SESSION['life_link_completo'].$it['ds_arquivo_imagem']."\" alt=\"".$it['nm_objeto_aprendizagem']."\" title=\"".$it['nm_objeto_aprendizagem']."\" border=\"0\" width=\"100%\">\n";
        }
        echo "      <p class=\"fontTituloItemTopoCapa\">".$it['ds_identifier']."</p>\n";
        echo "      <p class=\"fontChamadaItemTopoCapa\">".$it['nm_area_conhecimento']."</p>\n";
        echo "    </div>\n";
        echo "  </a>\n";
      }
      echo "  <div class=\"clear\"></div>\n";
      $_SESSION['life_objetos_apresentados_topo'] = $listados;
    }

    public function exibeRelacaoOutrosObjetosAprendizagemCapa() {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/niveis_educacionais.php';                         $niv_edu = new NivelEducacional();
      require_once 'conteudos/objetos_aprendizagem_pesquisa.php';               $oa_pes = new ObjetoAprendizagemPesquisa();

      $itens = $this->selectObjetosAprendizagem('1', 'ma', '0', ($conf->retornaNumeroItensCapaMeio() + $conf->retornaNumeroItensCapaTopo()), 'i');
      if (isset($_SESSION['life_objetos_apresentados_topo'])) {
        $acessados = $_SESSION['life_objetos_apresentados_topo'];
      } else {
        $acessados = array();
      }

      $nr_limite = $conf->retornaNumeroItensCapaMeio();
      $i = 1;
      $listados = array();
      foreach ($itens as $it) {
        $achou = false;
        foreach ($acessados as $l) {
          if ($it['cd_objeto_aprendizagem'] == $l) {
            $achou = true;
          }
        }
        if ((!$achou) && ($i <= $nr_limite)) {
          $listados[] = $it['cd_objeto_aprendizagem'];
          echo "  <a href=\"".$_SESSION['life_link_completo']."objetos/".$it['lk_seo']."\">\n";
          echo "    <div class=\"divUmElementoCapaMeio\" style=\"background-color:".$it['ds_cor'].";\">\n";
          if ($it['ds_arquivo_imagem_especifica'] != '') {
            echo "      <img src=\"".$_SESSION['life_link_completo'].$it['ds_pasta_arquivo_imagem'].$it['ds_arquivo_imagem_especifica']."\" alt=\"".$it['nm_objeto_aprendizagem']."\" title=\"".$it['nm_objeto_aprendizagem']."\" border=\"0\" width=\"100%\" height=\"146px\">\n";
          } else {
            echo "      <img src=\"".$_SESSION['life_link_completo'].$it['ds_arquivo_imagem']."\" alt=\"".$it['nm_objeto_aprendizagem']."\" title=\"".$it['nm_objeto_aprendizagem']."\" border=\"0\" width=\"100%\">\n";
          }
          echo "      <p class=\"fontTituloItemMeioCapa\">".$it['ds_identifier']."</p>\n";
          echo "      <p class=\"fontChamadaItemMeioCapa\">".$it['nm_area_conhecimento']."</p>\n";
          echo "    </div>\n";
          echo "  </a>\n";
          $i += 1;
        }
      }
      $_SESSION['life_objetos_apresentados_topo'] = $listados;
    }

    public function retornaConteudoPesquisa($pagina, $lista_paginas) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();
      require_once 'conteudos/niveis_educacionais.php';                         $niv_edu = new NivelEducacional();

      $maior_i = count($lista_paginas) - 1;

      $acao = addslashes($lista_paginas[$maior_i]);
      if (($acao != 'primeira') && ($acao != 'anterior') && ($acao != 'proxima') && ($acao != 'ultima')) {
        $acao = '';
      }

      $_SESSION['life_objetos_aprendizagem_pesquisa_qtd'] = $this->selectNumeroObjetosAprendizagemPesquisa();
      if (!isset($_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina'])) {
        $_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina'] = $conf->retornaNumeroLinhasPaginaExibicaoObjetosAprendizagem();
      }
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_qtd'] > $_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina']) {
        $_SESSION['life_objetos_aprendizagem_pesquisa_paginas'] = ceil($_SESSION['life_objetos_aprendizagem_pesquisa_qtd'] / $_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina']);
      } else {
        $_SESSION['life_objetos_aprendizagem_pesquisa_paginas'] = '1';
      }
      if (!isset($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'])) {
        $_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] = 0;
      } else {
        switch ($acao) {
          case "primeira":
            $_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] = 0;
          break;
          case "proxima":
            $_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] += 1;
            if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] > $_SESSION['life_objetos_aprendizagem_pesquisa_paginas']) {
               $_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] = $_SESSION['life_objetos_aprendizagem_pesquisa_paginas'];
            }
          break;
          case "anterior":
            if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] > 1) {
              $_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] -= 1;
            }
          break;
          case "ultima":
            $_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] = $_SESSION['life_objetos_aprendizagem_pesquisa_paginas'] - 1;
          break;
        }
      }

      $primeiro = $_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] * $_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina'];
      $limite = $_SESSION['life_objetos_aprendizagem_pesquisa_itens_pagina'];

      $itens = $this->selectObjetosAprendizagem('1', '', $primeiro, $limite, 'c');

      $nr_itens = 0;
      foreach ($itens as $it) {
        $nr_itens += 1;
        echo "    <div class=\"divUmElementoPesquisa\" style=\"background-color:".$it['ds_cor'].";\">\n";
        echo "      <div class=\"divImagemPesquisa\">\n";
        echo "        <a href=\"".$_SESSION['life_link_completo']."pesquisar/".$it['lk_seo']."\">\n";
        if ($it['ds_arquivo_imagem_especifica'] != '') {
          echo "        <img src=\"".$_SESSION['life_link_completo'].$it['ds_pasta_arquivo_imagem'].$it['ds_arquivo_imagem_especifica']."\" alt=\"".$it['nm_objeto_aprendizagem']."\" title=\"".$it['nm_objeto_aprendizagem']."\" border=\"0\" width=\"100%\">\n";
        } else {
          echo "        <img src=\"".$_SESSION['life_link_completo'].$it['ds_arquivo_imagem']."\" alt=\"".$it['nm_objeto_aprendizagem']."\" title=\"".$it['nm_objeto_aprendizagem']."\" border=\"0\" width=\"100%\">\n";
        }
        echo "        </a>\n";
        echo "      </div>\n";
        echo "      <div class=\"divTituloPesquisa\">\n";
        echo "        <div class=\"divChamadaTitulo\">\n";
        echo "          <h1>\n";
        echo "            <a href=\"".$_SESSION['life_link_completo']."pesquisar/".$it['lk_seo']."\" class=\"fontLink\">".$it['ds_identifier']."</a>\n";
        echo "          </h1>\n";
        echo "        </div>\n";
        echo "      <div class=\"divFuncoesTitulo\">\n";
        echo "        <p class=\"fontComandosFiltros\">\n";
        echo "          <a href=\"".$_SESSION['life_link_completo']."pesquisar/".$it['lk_seo']."\"><img src=\"".$_SESSION['life_link_completo']."icones/acessar_oa.png\" alt=\"Acessar Objeto de Aprendizagem\" title=\"Acessar Objeto de Aprendizagem\" border=\"0\"></a>\n";
        echo "    </p>\n";
        echo "  </div>\n";
        echo "</div>\n";


        require_once 'conteudos/objetos_aprendizagem_general.php';              $oa_gen = new ObjetoAprendizagemGeneral();
        echo "        ".$oa_gen->imprimeDadosResumidos($it['cd_general'])."\n";
        echo "    </div>\n";
      }

      echo "<div class=\"divLinhaRetornoPesquisa\">\n";
      echo "    <p class=\"fontConteudoCentralizado\">\n";
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] > 0)                                                             {           echo "      <a href=\"".$_SESSION['life_link_completo']."pesquisar/primeira\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_primeira.png\" alt=\"Primeira Página\" title=\"Primeira Página\" border=\"0\"></a>\n";            } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] > 1)                                                             {           echo "      <a href=\"".$_SESSION['life_link_completo']."pesquisar/anterior\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_anterior.png\" alt=\"Página Anterior\" title=\"Página Anterior\" border=\"0\"></a>\n";            } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";
      echo "      Página ".($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] +1)." de ".$_SESSION['life_objetos_aprendizagem_pesquisa_paginas']."\n";
      echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] < ($_SESSION['life_objetos_aprendizagem_pesquisa_paginas'] - 2)) {           echo "      <a href=\"".$_SESSION['life_link_completo']."pesquisar/proxima\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_proxima.png\" alt=\"Próxima Página\" title=\"Próxima Página\" border=\"0\"></a>\n";                } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] < ($_SESSION['life_objetos_aprendizagem_pesquisa_paginas'] - 1)) {           echo "      <a href=\"".$_SESSION['life_link_completo']."pesquisar/ultima\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_ultima.png\" alt=\"Última Página\" title=\"Última Página\" border=\"0\"></a>\n";                    } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      echo "    </p>\n";
      echo "</div>\n";
/*
      echo "<div class=\"divLinhaRetornoPesquisa\">\n";
      echo "    <p class=\"fontConteudoCentralizado\">\n";
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] > 0)                                                             {           echo "      <a href=\"".$_SESSION['life_link_completo']."materiais/primeira/".$categoria['lk_seo']."\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_primeira.png\" alt=\"Primeira Página\" title=\"Primeira Página\" border=\"0\"></a>\n";            } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] > 1)                                                             {           echo "      <a href=\"".$_SESSION['life_link_completo']."materiais/anterior/".$categoria['lk_seo']."\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_anterior.png\" alt=\"Página Anterior\" title=\"Página Anterior\" border=\"0\"></a>\n";            } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";
      echo "      Página ".($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] +1)." de ".$_SESSION['life_objetos_aprendizagem_pesquisa_paginas']."\n";
      echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] < ($_SESSION['life_objetos_aprendizagem_pesquisa_paginas'] - 2)) {           echo "      <a href=\"".$_SESSION['life_link_completo']."materiais/proxima/".$categoria['lk_seo']."\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_proxima.png\" alt=\"Próxima Página\" title=\"Próxima Página\" border=\"0\"></a>\n";                } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      if ($_SESSION['life_objetos_aprendizagem_pesquisa_pagina'] < ($_SESSION['life_objetos_aprendizagem_pesquisa_paginas'] - 1)) {           echo "      <a href=\"".$_SESSION['life_link_completo']."materiais/ultima/".$categoria['lk_seo']."\"><img src=\"".$_SESSION['life_link_completo']."icones/navegacao_ultima.png\" alt=\"Última Página\" title=\"Última Página\" border=\"0\"></a>\n";                    } else {       echo "      <img src=\"".$_SESSION['life_link_completo']."icones/navegacao_vazio.png\">\n";            }
      echo "    </p>\n";
      echo "</div>\n";
*
    }

    public function controleApresentacaoObjetoAprendizagem($pagina, $lista_paginas) {
      if ($lista_paginas[1] != '') {
        $lk_seo_oa = addslashes($lista_paginas[1]);
        $link = $_SESSION['life_link_completo'];
        $this->retornaExibicaoObjetoAprendizagem($lk_seo_oa, $link);
      } else {
        echo "  <img src=\"".$_SESSION['life_link_completo']."imagens/oa_nao_encontrado.png\" alt=\"Objeto de Aprendizagem não encontrado\" title=\"Objeto de Aprendizagem não encontrado\" border=\"0\">\n";
      }
    }

    public function retornaExibicaoObjetoAprendizagem($lk_seo_oa, $link) {
      $dados = $this->selectDadosObjetoAprendizagemSEO($lk_seo_oa);

      if ($dados['cd_objeto_aprendizagem'] > 0) {
        if ($dados['eh_liberado'] == '1') {
          if ($dados['eh_ativo'] == '1') {
            $this->contabilizarAcesso($dados['cd_objeto_aprendizagem'], ($dados['nr_acessos']+1));

            echo "<div class=\"divTituloObjetoAprendizagem\">\n";
            echo "  <div class=\"divChamadaTitulo\">\n";
            echo "    <h1>".$dados['nm_objeto_aprendizagem']."</h2>\n";
            echo "  </div>\n";
            echo "  <div class=\"divFuncoesTitulo\">\n";
            echo "    <p class=\"fontComandosFiltros\">\n";
            echo "      <a href=\"".$link."\"><img src=\"".$_SESSION['life_link_completo']."icones/voltar.png\" alt=\"Voltar\" title=\"Voltar\" border=\"0\"></a>\n";
            if ((isset($_SESSION['life_codigo'])) && ($dados['cd_usuario_proprietario'] == $_SESSION['life_codigo'])) {
              echo "      <a href=\"index.php?secao=5&sub=40&cd=".$dados['cd_objeto_aprendizagem']."&tp=b&acao=editar\"><img src=\"".$_SESSION['life_link_completo']."icones/editar_basico.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Básico\" title=\"Editar Objeto de Aprendizagem - Cadastro Básico\" border=\"0\"></a>\n";
              echo "      <a href=\"index.php?secao=5&sub=40&cd=".$dados['cd_objeto_aprendizagem']."&tp=i&acao=editar\"><img src=\"".$_SESSION['life_link_completo']."icones/editar_intermediario.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" title=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" border=\"0\"></a>\n";
              echo "      <a href=\"index.php?secao=5&sub=40&cd=".$dados['cd_objeto_aprendizagem']."&tp=c&acao=editar\"><img src=\"".$_SESSION['life_link_completo']."icones/editar_completo.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Completo\" title=\"Editar Objeto de Aprendizagem - Cadastro Completo\" border=\"0\"></a>\n";
            }
            echo "    </p>\n";
            echo "  </div>\n";
            echo "</div>\n";

            echo "<div class=\"divObjetoAprendizagem\">\n";
            if ($dados['eh_informar_general'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_general.php';                $oa_gen = new ObjetoAprendizagemGeneral();
              $retorno = $oa_gen->imprimeDados($dados['cd_general']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_technical'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_technical.php';              $oa_tec = new ObjetoAprendizagemTechnical();
              $retorno = $oa_tec->imprimeDados($dados['cd_technical'], true);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_lyfe_cycle'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_lyfe_cycle.php';             $oa_lyf_cyc = new ObjetoAprendizagemLyfeCycle();
              $retorno = $oa_lyf_cyc->imprimeDados($dados['cd_lyfe_cycle']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_meta_metadata'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_meta_metadata.php';          $oa_mtd = new ObjetoAprendizagemMetaMetadata();
              $retorno = $oa_mtd->imprimeDados($dados['cd_meta_metadata']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_educational'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_educational.php';            $oa_edu = new ObjetoAprendizagemEducational();
              $retorno = $oa_edu->imprimeDados($dados['cd_educational']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_rights'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_rights.php';                 $oa_rig = new ObjetoAprendizagemRights();
              $retorno = $oa_rig->imprimeDados($dados['cd_rights']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_relation'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_relation.php';               $oa_rel = new ObjetoAprendizagemRelation();
              $retorno = $oa_rel->imprimeDados($dados['cd_relation']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_annotation'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_annotation.php';             $oa_ann = new ObjetoAprendizagemAnnotation();
              $retorno = $oa_ann->imprimeDados($dados['cd_annotation']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_classification'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_classification.php';         $oa_cla = new ObjetoAprendizagemClassification();
              $retorno = $oa_cla->imprimeDados($dados['cd_classification']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_acessibility'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_acessibility.php';           $oa_ace = new ObjetoAprendizagemAcessibility();
              $retorno = $oa_ace->imprimeDados($dados['cd_acessibility']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }
            if ($dados['eh_informar_segment_information_table'] == '1') {
              require_once 'conteudos/objetos_aprendizagem_segment_information_table.php';       $oa_sit = new ObjetoAprendizagemSegmentInformationTable();
              $retorno = $oa_sit->imprimeDados($dados['cd_segment_information_table']);
              if ($retorno != "") {
                echo "      ".$retorno."\n";
              }
            }

            echo "    <p class=\"fontComandosFiltros\">\n";
            if (isset($_SESSION['life_codigo'])) {
              echo "      <input type=\"hidden\" name=\"caminho\" id=\"caminho\" value=\"".$_SESSION['life_link_completo']."icones/\" />\n";
              echo "      <img src=\"".$_SESSION['life_link_completo']."icones/estrela_025.png\" id=\"estrela_01\" alt=\"Avalie o Objeto de Aprendizagem\" title=\"Avalie o Objeto de Aprendizagem\" border=\"0\" onMouseOver=\"marcarEstrelas('1');\" onMouseOut=\"desmarcarEstrelas();\" onClick=\"registrarAvaliacao('1');\n>\n";
              echo "      <img src=\"".$_SESSION['life_link_completo']."icones/estrela_025.png\" id=\"estrela_02\" alt=\"Avalie o Objeto de Aprendizagem\" title=\"Avalie o Objeto de Aprendizagem\" border=\"0\" onMouseOver=\"marcarEstrelas('2');\" onMouseOut=\"desmarcarEstrelas();\" onClick=\"registrarAvaliacao('2');\n>\n";
              echo "      <img src=\"".$_SESSION['life_link_completo']."icones/estrela_025.png\" id=\"estrela_03\" alt=\"Avalie o Objeto de Aprendizagem\" title=\"Avalie o Objeto de Aprendizagem\" border=\"0\" onMouseOver=\"marcarEstrelas('3');\" onMouseOut=\"desmarcarEstrelas();\" onClick=\"registrarAvaliacao('3');\n>\n";
              echo "      <img src=\"".$_SESSION['life_link_completo']."icones/estrela_025.png\" id=\"estrela_04\" alt=\"Avalie o Objeto de Aprendizagem\" title=\"Avalie o Objeto de Aprendizagem\" border=\"0\" onMouseOver=\"marcarEstrelas('4');\" onMouseOut=\"desmarcarEstrelas();\" onClick=\"registrarAvaliacao('4');\n>\n";
              echo "      <img src=\"".$_SESSION['life_link_completo']."icones/estrela_025.png\" id=\"estrela_05\" alt=\"Avalie o Objeto de Aprendizagem\" title=\"Avalie o Objeto de Aprendizagem\" border=\"0\" onMouseOver=\"marcarEstrelas('5');\" onMouseOut=\"desmarcarEstrelas();\" onClick=\"registrarAvaliacao('5');\n>\n";


              echo "      <a href=\"index.php?secao=5&sub=40&cd=".$dados['cd_objeto_aprendizagem']."&tp=b&acao=editar\"><img src=\"".$_SESSION['life_link_completo']."icones/editar_basico.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Básico\" title=\"Editar Objeto de Aprendizagem - Cadastro Básico\" border=\"0\"></a>\n";
              echo "      <a href=\"index.php?secao=5&sub=40&cd=".$dados['cd_objeto_aprendizagem']."&tp=i&acao=editar\"><img src=\"".$_SESSION['life_link_completo']."icones/editar_intermediario.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" title=\"Editar Objeto de Aprendizagem - Cadastro Intermediário\" border=\"0\"></a>\n";
              echo "      <a href=\"index.php?secao=5&sub=40&cd=".$dados['cd_objeto_aprendizagem']."&tp=c&acao=editar\"><img src=\"".$_SESSION['life_link_completo']."icones/editar_completo.png\" alt=\"Editar Objeto de Aprendizagem - Cadastro Completo\" title=\"Editar Objeto de Aprendizagem - Cadastro Completo\" border=\"0\"></a>\n";

echo "avaliacao";
echo "denuncie";
echo "comente";

            }
            echo "    </p>\n";

            echo "</div>\n";
            echo "<div class=\"clear\"></div>\n";

            require_once 'conteudos/objetos_aprendizagem_agrupamentos.php';     $oaa = new ObjetoAprendizagemAgrupamento();
            $oaa->retornaListaAgrupamentosObjetoAprendizagem($dados['cd_objeto_aprendizagem']);
          } else {
            echo "  <img src=\"".$_SESSION['life_link_completo']."imagens/oa_excluido.png\" alt=\"Objeto de Aprendizagem excluído\" title=\"Objeto de Aprendizagem excluído\" border=\"0\">\n";
          }
        } else {
          echo "  <img src=\"".$_SESSION['life_link_completo']."imagens/oa_bloqueado.png\" alt=\"Objeto de Aprendizagem bloqueado\" title=\"Objeto de Aprendizagem bloqueado\" border=\"0\">\n";
        }
      } else {
        echo "  <img src=\"".$_SESSION['life_link_completo']."imagens/oa_nao_encontrado.png\" alt=\"Objeto de Aprendizagem não encontrado\" title=\"Objeto de Aprendizagem não encontrado\" border=\"0\">\n";
      }
    }
    */
//**************BANCO DE DADOS**************************************************
    public function retornaNotaMedia($cd_objeto_aprendizagem) {
      $sql  = "SELECT AVG(nr_estrelas) media ".
              "FROM life_objetos_aprendizagem_avaliacoes ".
              "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM AVALIAÇÕES");
      $dados= mysql_fetch_assoc($result_id);
      return $dados['media'];
    }

    public function selectAvalicaoUsuarioObjetoAprendizagem($cd_usuario_cadastro, $cd_objeto_aprendizagem) {
      $sql  = "SELECT * ".
              "FROM life_objetos_aprendizagem_avaliacoes ".
              "WHERE cd_usuario_cadastro = '$cd_usuario_cadastro' ".
              "AND cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM AVALIAÇÕES");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;
    }

    private function registrarAvalicaoUsuarioObjetoAprendizagem($cd_usuario_cadastro, $cd_objeto_aprendizagem, $nr_estrelas) {
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_objetos_aprendizagem_avaliacoes ".
             "(cd_objeto_aprendizagem, nr_estrelas, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_objeto_aprendizagem\", \"$nr_estrelas\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM AVALIAÇÕES");
      $saida = mysql_affected_rows();
      return $saida;
    }

    private function atualizarAvalicaoUsuarioObjetoAprendizagem($cd_usuario_cadastro, $cd_objeto_aprendizagem, $nr_estrelas) {
      $dt_cadastro = date('Y-m-d');
      $sql = "UPDATE life_objetos_aprendizagem_avaliacoes SET ".
             "nr_estrelas = \"$nr_estrelas\", ".
             "dt_cadastro = \"$dt_cadastro\" ".
             "WHERE cd_objeto_aprendizagem = '$cd_objeto_aprendizagem' ".
             "AND cd_usuario_cadastro = '$cd_usuario_cadastro' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'objetos_aprendizagem');            
      mysql_query($sql) or die ("Erro no banco de dados! - TABELA OBJETOS APRENDIZAGEM AVALIAÇÕES");
      $saida = mysql_affected_rows();
      return $saida;     
    }   

  }
?>                   