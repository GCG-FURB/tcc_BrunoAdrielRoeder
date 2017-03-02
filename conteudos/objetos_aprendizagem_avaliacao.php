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
          echo "<p class=\"fontConteudoSucesso\">Obrigado por avaliar o objeto de aprendizagem!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Ops. Você já havia feito a mesma avaliação objeto de aprendizagem anteriormente!</p>\n";
        }
      } else {
        if ($this->registrarAvalicaoUsuarioObjetoAprendizagem($cd_usuario, $cd_objeto_aprendizagem, $nr_estrelas)) {
          echo "<p class=\"fontConteudoSucesso\">Obrigado por avaliar o objeto de aprendizagem!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Desculpe! Não conseguimos registrar sua avaliação do objeto de aprendizagem!</p>\n";
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
      //echo "      Avaliação Geral<br />\n";
      if ($avaliacao > 4.5) {
        $nota = '5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 4) {
        $nota = '4,5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3.5) {
        $nota = '4';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3) {
        $nota = '3.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2.5) {
        $nota = '3';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2) {
        $nota = '2.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1.5) {
        $nota = '2';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1) {
        $nota = '1.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0.5) {
        $nota = '1';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0) {
        $nota = '0.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } else {
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
      }
      //echo "    </p>\n";
    }

    public function retornaAvaliacaoObjetoAprendizagemCompleto($cd_objeto_aprendizagem) {
      $avaliacao = $this->retornaNotaMedia($cd_objeto_aprendizagem);
      if ($avaliacao > 4.5) {
        $nota = '5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 4) {
        $nota = '4,5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3.5) {
        $nota = '4';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 3) {
        $nota = '3.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2.5) {
        $nota = '3';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 2) {
        $nota = '2.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1.5) {
        $nota = '2';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 1) {
        $nota = '1.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0.5) {
        $nota = '1';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_inteira.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } elseif ($avaliacao > 0) {
        $nota = '0.5';
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_meia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" title=\"Avalição do objeto de aprendizagem com ".$nota." estrelas\" border=\"0\">\n";
      } else {
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
        echo "        <img src=\"".$_SESSION['life_link_completo']."icones/estrela_vazia.png\" alt=\"objeto de aprendizagem ainda não avaliado\" title=\"objeto de aprendizagem ainda não avaliado\" border=\"0\">\n";
      }
    }

//***************EXIBICAO PUBLICA***********************************************

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