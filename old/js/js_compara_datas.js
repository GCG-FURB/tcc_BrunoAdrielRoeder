<script>
<!--
function validaDataMenorIgual(f) {

  var data1 = f.dia_inicio.value+"/"+f.mes_inicio.value+"/"+f.ano_inicio.value;
  var data2 = f.dia_fim.value+"/"+f.mes_fim.value+"/"+f.ano_fim.value;

  if (!ehData1MenorData2(data1, data2)) {
    alert('A data do In�cio deve ser menor ou igual a data do fim do per�odo!');
    return false;
  }
  
  return true; 
}

function ehData1MenorData2(data1, data2) {
  if (parseInt(data2.split("/")[2].toString() + data2.split("/")[1].toString() + data2.split("/")[0].toString()) > parseInt( data1.split("/")[2].toString() + data1.split("/")[1].toString() + data1.split("/")[0].toString())) {
    //data 2 � maior que 1
    return true;
  } else {
    //data 1 � maior ou igual a 2
    return false;
  }
}
-->
</script>