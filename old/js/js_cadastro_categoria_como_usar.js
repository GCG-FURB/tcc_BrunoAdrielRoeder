<script>
<!--
function valida(f) {

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
  
  return true; 
}

function ehTipoInteiro(valor) {
	var reTipo = /^\d+$/; 
	return reTipo.test(valor);
}
-->    
</script>