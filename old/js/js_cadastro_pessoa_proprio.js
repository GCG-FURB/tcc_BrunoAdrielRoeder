<script>
<!--
function valida(f) {
  if (document.getElementById('eh_nome_valido').value == '0') {
    alert('Informe seu Nome Completo!');
    return false;
  }
  
  if (document.getElementById('eh_username_valido').value == '0') {
    alert('Informe seu E-mail!');
    return false;
  }

  if (document.getElementById('eh_senha_valido').value == '0') {
    alert("Informe uma Senha!");
    return false;
  }

  if (f.nr_cpf.value == '') {
    alert("Informe seu CPF!");
    return false;
  } else {
    document.getElementById('validar').value = '1';
    if (!validaCpf(nr_cpf)) {
      alert("CPF inválido!");
      return false;
    }
  }

  return true; 
}

function ehNomeCompleto() {
  if (document.getElementById('validar').value == '1') {
    ehValidar(0);
    var nome = document.getElementById('nm_pessoa').value;
    if (nome == '') {
      alert('Informe seu Nome Completo!');
      setTimeout("document.getElementById('nm_pessoa').focus()",300);    
    } else {
      var achou = false;
      for (i=0; i<nome.length; i++) {
        if (nome[i] == ' ') {
          achou = true;
        }
      }
      if (!achou) {
        alert('Informe seu Nome Completo!');
        setTimeout("document.getElementById('nm_pessoa').focus()",300);
      } else {
        ehValido('eh_nome_valido', 1);
      }
    }
  }
}

function ehEmailValido() {
  if (document.getElementById('validar').value == '1') {
    ehValidar(0);
    var email = document.getElementById('ds_username').value;
    if (email == '') {
      alert('Informe seu E-mail!');
      setTimeout("document.getElementById('ds_username').focus()",300);    
    } else {
      var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
      var check=/@[\w\-]+\./;
      var checkend=/\.[a-zA-Z]{2,3}$/;
      if(((email.search(exclude) != -1)||(email.search(check)) == -1)||(email.search(checkend) == -1)){
        alert('Informe um e-mail válido!');
        setTimeout("document.getElementById('ds_username').focus()",300);    
      } else {
        ehValido('eh_username_valido', '1');
      }
    }    
  }
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

function mascaraCpf(campo) {
  var cpf = document.getElementById(campo).value;
  if (cpf.length == 3) {
    cpf = cpf + "."; 
  }
  if (cpf.length == 7) { 
    cpf = cpf + "."; 
  }
  if (cpf.length == 11) {
    cpf = cpf + "-";
  }
  document.getElementById(campo).value = cpf;   
}     

function validaCpf(campo) {
  if (document.getElementById('validar').value == '1') {
    ehValidar(0);
    var cpf = document.getElementById(campo).value;
    if (cpf != '') {
      var novo_cpf = ''; 
	    for (i = 0; i < cpf.length; i++) { 
      	if ((cpf.charAt(i) != '.') && (cpf.charAt(i) != '-'))	{ 
        	novo_cpf += cpf.charAt(i); 
  	    }
      }
      cpf = novo_cpf;  
	    var numeros, digitos, soma, i, resultado, digitos_iguais; 
  	  digitos_iguais = 1; 
	    if (cpf.length < 11) {
        document.getElementById("eh_cpf_valido").value = '0';
        alert('Número do Cpf Inválido!');
        setTimeout("document.getElementById('"+campo+"').focus()",250);  
      } 
    	for (i = 0; i < cpf.length - 1; i++) { 
      	if (cpf.charAt(i) != cpf.charAt(i + 1))	{ 
        	digitos_iguais = 0; 
      	  break;
        } 
	    } 
  	  if (!digitos_iguais) 	{ 
    	  numeros = cpf.substring(0,9); 
   	    digitos = cpf.substring(9); 
      	soma = 0; 
	      for (i = 10; i > 1; i--) 
 	      soma += numeros.charAt(10 - i) * i; 
   	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11; 
    	  if (resultado != digitos.charAt(0)) {
          document.getElementById("eh_cpf_valido").value = '0';
          alert('Número do Cpf Inválido!');
          setTimeout("document.getElementById('"+campo+"').focus()",250);  
        } 
	      numeros = cpf.substring(0,10); 
   	    soma = 0; 
      	for (i = 11; i > 1; i--) 
	      soma += numeros.charAt(11 - i) * i; 
  	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11; 
    	  if (resultado != digitos.charAt(1)) {
          document.getElementById("eh_cpf_valido").value = '0';
          alert('Número do Cpf Inválido!');
          setTimeout("document.getElementById('"+campo+"').focus()",250);  
        } else {
          document.getElementById("eh_cpf_valido").value = '1';
    	    return true;
        } 
    	} else {
        document.getElementById("eh_cpf_valido").value = '0';
        alert('Número do Cpf Inválido!');
        setTimeout("document.getElementById('"+campo+"').focus()",250);  
      }
      document.getElementById("eh_cpf_valido").value = '1';
    } else {
      document.getElementById("eh_cpf_valido").value = '1';
    }
  }  
} 

function temNumeroCaracter(texto, tipo, quantidade) {
  var achou = 0;
  for(i=0; i<texto.length; i++){
    if (tipo.indexOf(texto.charAt(i),0)!=-1){
      achou += 1;
    }
  }
  if (achou >= quantidade) {
    return 1;
  }
  return 0;
} 


function ehValidar(valor) {
  document.getElementById('validar').value = valor;  
}

function ehValido(campo, valor) {
  document.getElementById(campo).value = valor;
}
-->    
</script>