<script>
<!--
  function mudaTamanho(acao){
    var tamanho_atual = "<?php echo $_SESSION['life_tamanho_fonte']; ?>";
alert(tamanho_atual);

    //var tamanho_atual = document.getElementById("tamanho_fonte").value;
    if (acao == 'diminuir') {
      tamanho_atual = parseInt(tamanho_atual) - 1;
    } else {
      if (acao == 'aumentar') {
        tamanho_atual = parseInt(tamanho_atual) + 1;
      } else {
        if (acao == 'normal') {
          tamanho_atual = 4;
        }
      }
    }
alert(tamanho_atual);


$_SESSION['life_tamanho_fonte'] = tamanho_atual;
    var tamanho_atual = "<?php echo $_SESSION['life_tamanho_fonte']; ?>";
alert(tamanho_atual);
/*    if (tamanho_atual > 8) {      tamanho_atual = 8;    }
    if (tamanho_atual < 0) {      tamanho_atual = 0;    }

*/
//    document.getElementById("tamanho_fonte").value = tamanho_atual;


    location.reload();
  }
-->
</script>