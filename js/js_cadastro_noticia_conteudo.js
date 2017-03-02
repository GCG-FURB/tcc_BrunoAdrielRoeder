<script>
<!--
function valida(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');

  if (f.ds_conteudo.value == '') {
    alert('Informe o Conteúdo!');
    return false; 
  }

  if (f.nr_ordem.value == '') {
    alert('Informe o Número de Ordem do Conteúdo da Matéria!');
    return false; 
  } else {
    var nr = f.nr_ordem.value;
    if (!ehTipoInteiro(nr)) {
      alert('Informe um valor inteiro para o Número de Ordem do Conteúdo da Matéria!');
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
