<script>
<!--
function validaPesquisaOASimples(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');

  if (f.termo_1.value == '') {
    alert('Informe o Termo de Pesquisa!');
    return false; 
  }

  var setado = false; 
  for(i=0; i<=f.tabela_1.length-1; i++){
    if(f.tabela_1[i].checked){
      setado = true;
    }
  }
  if (!setado) {
    alert('Marque a Seção na qual deseja realizar a pesquisa!');
    return false;
  }

  document.getElementById('cadastro').submit();
}
-->    
</script>