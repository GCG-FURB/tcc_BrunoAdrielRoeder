<script>
<!--
  	
  function AbreCalendario(largura,altura,formulario,campo,tmpx) {
    vertical   = (screen.height/2) - (altura/2);
    horizontal = (screen.width/2) - (largura/2);
    var jan = window.open('calendario.php?formulario='+formulario+'&campo='+campo+'&tmpx='+tmpx,'','toolbar=false,location=false,directories=0,status=1,menubar=false,scrollbars=yes,resizable=0,copyhistory=0,screenX='+screen.width+',screenY='+screen.height+',top='+vertical+',left='+horizontal+',width='+largura+',height='+altura);
    jan.focus();                                                                                   
  }
  
  function valor(valor,campo){
    if (campo == 'ds_annotation_date') {
      opener.document.cadastro_o_a.ds_annotation_date.value = valor;
    } else {      
      if (campo == 'dt_arquivo') {
        opener.document.cadastro.dt_arquivo.value = valor;
      }
    }      
    window.self.close();
  }
         

-->    
</script>