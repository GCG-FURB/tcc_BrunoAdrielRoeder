<script>
<!--
function valida(f) {
  if (f.ds_foto.value == '') {
    alert('Informe uma Descrição para a Foto!');
    return false;
  }

  if (f.ds_arquivo.value == '') {
    alert('Selecione o Arquivo contendo a Foto!');
    return false;
  } else {
    var tamanho_foto = parseInt(document.getElementById('ds_arquivo').files[0].size);
    var tamanho_limite = parseInt('2000000');
    if (tamanho_foto > tamanho_limite) {
      alert('O tamanho da Foto não pode exceder 1,9MB!');
      return false;
    }
  }

  return true;
}

-->    
</script>