<script>
<!--
function valida(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');

   var opcoes = f.eh_liberado;
   var valor = '';
   for(i=0; i<opcoes.length; i++){
      if(opcoes[i].checked){
         valor = opcoes[i].value;
      }
   }
   
   if (valor == '0') {
     if (f.cd_sub_area_conhecimento_destino.value == '0') {
       alert('Selecione uma Sub Área de Conhecimento!');
       return false;
     } 
   }

  document.getElementById('cadastro').submit();
}
-->
</script>