<script>
<!--
function valida(f) {
  if (document.getElementById('e_mail_senha').value == '') {
    alert('Informe seu E-mail!');
    return false;
  }
  return true; 
}

function ehEmailValido() {
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
}
-->    
</script>