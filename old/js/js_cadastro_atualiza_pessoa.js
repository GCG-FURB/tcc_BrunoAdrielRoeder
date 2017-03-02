<script>
<!--
function valida(f) {

  if (f.nm_pessoa.value == '') {
    alert('Informe o seu nome!');
    return false; 
  }

  if (f.nr_cpf.value == '') {
    alert('Informe o seu CPF!');
    return false;
  } else {
    if (!validaCpf('nr_cpf')) {
      alert('CPF inválido!');
      return false;
    }
  }

  if (f.cd_nivel_educacional.value == '0') {
    alert('Selecione seu nível de instrução!');
    return false;
  }

  if (f.ds_email_01.value == '') {
    alert('Informe o e-mail 01!');
    return false; 
  }
  
  return true; 
}

function validaCpf(campo) {
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
      return false;
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
        return false;
      }
	    numeros = cpf.substring(0,10);
	    soma = 0;
    	for (i = 11; i > 1; i--)
	    soma += numeros.charAt(11 - i) * i;
  	  resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
  	  if (resultado != digitos.charAt(1)) {
        return false;
      } else {
    	  return true;
      }
  	} else {
      return false;
    }
    return true;
  } else {
    return false;
  }
}

-->    
</script>