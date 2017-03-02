<script>
<!--
function validaEnvioSenha(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('logar');

  if (f.e_mail_senha.value == '') {
    alert('Informe seu E-mail!');
    return false;
  } else {
    if (!validarEmailEnvioSenha()) {
      return false;
    }
  }
  document.getElementById('logar').submit();
}

function validarEmailEnvioSenha() {
  document.getElementById('digitou').value = '0';
  var email = document.getElementById('e_mail_senha').value;
  if (email == '') {
    alert('Informe seu E-mail!');
    setTimeout("document.getElementById('e_mail_senha').focus()",300);    
  } else {
    var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
    var check=/@[\w\-]+\./;
    var checkend=/\.[a-zA-Z]{2,3}$/;
    if(((email.search(exclude) != -1)||(email.search(check)) == -1)||(email.search(checkend) == -1)){
      alert('Informe um e-mail válido!');
      setTimeout("document.getElementById('e_mail_senha').focus()",300);    
    }
  }
  return true;
}
-->    
</script>