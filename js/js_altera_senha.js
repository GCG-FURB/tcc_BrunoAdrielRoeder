<script>
<!--
function valida_senha(f) {
  document.getElementById('digitou').value = '0';
  var f = document.getElementById('cadastro_senha');
  if (document.getElementById('eh_senha_valido').value == '0') {
    if (!validaSenha()) {
      alert("Preencha o formulário!");
      return false;
    } else {
      alert("Preencha o formulário!");
      return false;
    }
  }

  document.getElementById('cadastro_senha').submit();
}

function validaSenha() {
  if (document.getElementById('validar').value == '1') {
    ehValidar(0);
    var senha = document.getElementById('ds_senha').value;
    if (senha == "") {
      alert("Informe uma Senha!");
      setTimeout("document.getElementById('ds_senha').focus()",300);
    } else {
      var nr_caracteres_senha = parseInt(document.getElementById('nr_caracteres_senha').value);
      if (senha.length >= nr_caracteres_senha) {
        var nr_letras_maiusculas_senha = parseInt(document.getElementById('nr_letras_maiusculas_senha').value);
        var letras_maiusculas = "ABCDEFGHYJKLMNOPQRSTUVWXYZ";
        if (temNumeroCaracter(senha, letras_maiusculas, nr_letras_maiusculas_senha)) {
          var nr_letras_minusculas_senha =parseInt( document.getElementById('nr_letras_minusculas_senha').value);
          var letras_minusculas="abcdefghyjklmnopqrstuvwxyz";      
          if (temNumeroCaracter(senha, letras_minusculas, nr_letras_minusculas_senha)) {
            var nr_numeros_senha = parseInt(document.getElementById('nr_numeros_senha').value);
            var numeros="0123456789";
            if (!temNumeroCaracter(senha, numeros, nr_numeros_senha)) {
              alert("A Senha deve possuir "+nr_numeros_senha+" ou mais Números!");
              setTimeout("document.getElementById('ds_senha').focus()",300);
            } else {
              ehValido('eh_senha_valido', 1);
            }
          } else {
            alert("A Senha deve possuir "+nr_letras_minusculas_senha+" ou mais Letras Minúsculas!");
            setTimeout("document.getElementById('ds_senha').focus()",300);
          }      
        } else {
          alert("A Senha deve possuir "+nr_letras_maiusculas_senha+" ou mais Letras Maiúsculas!");
          setTimeout("document.getElementById('ds_senha').focus()",300);
        }
      } else {
        alert("A Senha deve possuir "+nr_caracteres_senha+" ou mais caracteres!");
        setTimeout("document.getElementById('ds_senha').focus()",300);
      }           
    }
  }
}

function temNumeroCaracter(senha, string, numero) {
  var ocorrencias = 0;
  for (i=0; i<senha.length; i++) {
    for (j=0; j<string.length; j++) {
      if (senha[i] == string[j]) {
        ocorrencias += 1;
      }
    }
  }
  if (ocorrencias >= numero) {
    return true;
  } else {
    return false;
  }
}

function validaConfirmacaoSenha() {
  if (document.getElementById('validar').value == '1') {
    ehValidar(0);
    var confirma_senha = document.getElementById('ds_confirma_senha').value;
    if (confirma_senha == "") {
      alert("Informe a Confirmação da Senha!");
      setTimeout("document.getElementById('ds_confirma_senha').focus()",300);
    } else {  
      var senha = document.getElementById('ds_senha').value;
      if (senha != confirma_senha)  {
        alert("Senha e Confirmação da Senha não possuem o mesmo valor!");
        setTimeout("document.getElementById('ds_confirma_senha').focus()",300);
      } else {
        ehValido('eh_senha_valido', 1);
      }
    }      
  }  
}

function ehValidar(valor) {
  document.getElementById('validar').value = valor;  
}

function ehValido(campo, valor) {
  document.getElementById(campo).value = valor;
}
-->    
</script>