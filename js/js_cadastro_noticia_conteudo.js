<script>
<!--
function valida(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');

  if (f.ds_conteudo.value == '') {
    alert('Informe o Conte�do!');
    return false; 
  }

  if (f.nr_ordem.value == '') {
    alert('Informe o N�mero de Ordem do Conte�do da Mat�ria!');
    return false; 
  } else {
    var nr = f.nr_ordem.value;
    if (!ehTipoInteiro(nr)) {
      alert('Informe um valor inteiro para o N�mero de Ordem do Conte�do da Mat�ria!');
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
