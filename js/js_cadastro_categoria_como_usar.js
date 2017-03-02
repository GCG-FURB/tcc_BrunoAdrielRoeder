<script>
<!--
function valida(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');

  if (f.nm_categoria.value == '') {
    alert('Informe o Nome da Categoria!');
    return false; 
  }
  
  if (f.nr_ordem.value != '') {
    var numero = f.nr_ordem.value;
    if (!ehTipoInteiro(numero)) {
      alert('O Número de Ordem da Categoria deve ser um valor Inteiro!');
      return false; 
    }  
  }
  
  document.getElementById('cadastro').submit();
}

function ehTipoInteiro(valor) {
	var reTipo = /^\d+$/; 
	return reTipo.test(valor);
}
-->    
</script>