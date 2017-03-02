<script>
<!--
function valida(f) {

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
  
  return true; 
}

function ehTipoInteiro(valor) {
	var reTipo = /^\d+$/; 
	return reTipo.test(valor);
}
-->    
</script>
