<script>
<!--
function validaPesquisa(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro');

  var informou = false;
  if (f.termo.value != '') {
    informou = true;
  }

  if (document.getElementById('cd_nivel_educacional')) {
    if (f.cd_nivel_educacional.value != '0') {
      informou = true;
    }
  }
  if (document.getElementById('cd_coverage')) {
    if (f.cd_coverage.value != '0') {
      informou = true;
    }
  }
  if (document.getElementById('cd_formato_objeto')) {
    if (f.cd_formato_objeto.value != '0') {
      informou = true;
    }
  }
  if (document.getElementById('cd_linguagem')) {
    if (f.cd_linguagem.value != '0') {
      informou = true;
    }
  }
  if (document.getElementById('cd_status_ciclo_vida')) {
    if (f.cd_status_ciclo_vida.value != '0') {
      informou = true;
    }
  }

  if (!informou) {
    alert('Você não marcou nenhuma informação para pesquisa!');
    document.getElementById("termo").focus();
    return false;
  }

  document.getElementById('cadastro').submit();
}
-->    
</script>