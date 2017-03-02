<script>
<!--
<?php
  sajax_show_javascript();
?>
  	
function incluindoAreaConhecimentoRelacao(retorno) {
  document.getElementById('barra_areas_conhecimento').innerHTML = retorno;
}
function incluirAreaConhecimentoRelacao(identificador) {
  if (identificador != '') {
    x_incluirAreaConhecimentoRelacao(identificador, incluindoAreaConhecimentoRelacao);
  }
}  

function retirandoRelacao(retorno) {
  document.getElementById('barra_areas_conhecimento').innerHTML = retorno;
}
function retirarRelacao(identificador) {
  if (identificador != '') {
    x_retirarRelacao(identificador, retirandoRelacao);
  }
}                   
           

-->    
</script>