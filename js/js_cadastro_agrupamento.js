<script>
<!--
function valida_agrupamento(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');

  var nr_objetos_aprendizagem = f.nr_objetos_aprendizagem.value;
  var selecionados = 0;
  
  for (i=1; i<nr_objetos_aprendizagem; i++) {
    var nome = 'cd_objeto_aprendizagem_agrupar_'+i;
    if (!document.getElementById(nome).disabled) {
      if (document.getElementById(nome).checked) {
        selecionados += 1;
      }    
    }  
  }
  
  if (selecionados == 0) {
    alert('Selecione pelo menos um Objeto de Aprendizagem para agrupamento!'); 
    return false;
  }
  
  document.getElementById('cadastro').submit();
}
-->    
</script>