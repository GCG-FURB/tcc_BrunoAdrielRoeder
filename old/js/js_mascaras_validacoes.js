<script>
<!--

function mascaraTelefone(campo) {    
  var tel = document.getElementById(campo).value;
  if (tel.length == 1) {
    tel = "(" + tel; 
  }
  if (tel.length == 3) { 
    tel = tel + ") "; 
  }
  if (tel.length == 9) {
    tel = tel + "-";
  }
  document.getElementById(campo).value = tel;
}


function validaTelefone(campo) {    
  var tel = document.getElementById(campo).value;
  if (tel != '') {
    exp = /\(\d{2}\)\ \d{4}\-\d{4}/;        
    if  (!exp.test(tel)) {                                           
      alert('Número de Telefone Inválido!');
      setTimeout("document.getElementById('"+campo+"').focus()",250);
    }
  }
}

function mascaraCep(campo) {
  var cep = document.getElementById(campo).value;
  if (cep.length == 2) {
    cep = cep+"."; 
  }
  if (cep.length == 6) { 
    cep = cep+"-";  
  }
  document.getElementById(campo).value = cep;
}

function validaCep(campo) {    
  var cep = document.getElementById(campo).value;
  if (cep != '') {
    exp = /^[0-9]{2}\.[0-9]{3}-[0-9]{3}$/;
    if  (!exp.test(cep)) {
      alert('Cep Inválido!');
      setTimeout("document.getElementById('"+campo+"').focus()",250);
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
        alert('Número do Cpf Inválido!');
        setTimeout("document.getElementById('"+campo+"').focus()",250);  
      } 
	    numeros = cpf.substring(0,10); 
	    soma = 0; 
    	for (i = 11; i > 1; i--) 
	    soma += numeros.charAt(11 - i) * i; 
  	  resultado = soma % 11 < 2 ? 0 : 11 - soma % 11; 
  	  if (resultado != digitos.charAt(1)) {
        alert('Número do Cpf Inválido!');
        setTimeout("document.getElementById('"+campo+"').focus()",250);  
      } else {
    	  return true;
      } 
  	} else {
      alert('Número do Cpf Inválido!');
      setTimeout("document.getElementById('"+campo+"').focus()",250);  
    }
  }   
} 

function mascaraCnpj(campo) {
  var cnpj = document.getElementById(campo).value;
  if (cnpj.length == 2) {
    cnpj = cnpj + "."; 
  }
  if (cnpj.length == 6) { 
    cnpj = cnpj + "."; 
  }
  if (cnpj.length == 10) {
    cnpj = cnpj + "/";
  }
  if (cnpj.length == 15) {
    cnpj = cnpj + "-";
  }
  document.getElementById(campo).value = cnpj;   
}
         

function validaCnpj(campo) {
  var cnpj = document.getElementById(campo).value;
  if (cnpj != "") {
    var novo_cnpj = ''; 
	  for (i = 0; i < cnpj.length; i++) { 
  	  if ((cnpj.charAt(i) != '.') && (cnpj.charAt(i) != '/') && (cnpj.charAt(i) != '-'))	{ 
    	  novo_cnpj += cnpj.charAt(i); 
  	  }
    }
    cnpj = novo_cnpj;   
	  var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais; 
  	digitos_iguais = 1; 
	  if (cnpj.length < 14 && cnpj.length < 15) { 
      alert('Número do CNPJ Inválido!');
      setTimeout("document.getElementById('"+campo+"').focus()",250);
    } 
  	for (i = 0; i < cnpj.length - 1; i++) { 
    	if (cnpj.charAt(i) != cnpj.charAt(i + 1))	{ 
      	digitos_iguais = 0; 
    	  break; 
  	  }
    } 
  	if (!digitos_iguais) { 
    	tamanho = cnpj.length - 2 
	    numeros = cnpj.substring(0,tamanho); 
    	digitos = cnpj.substring(tamanho); 
    	soma = 0; 
  	  pos = tamanho - 7; 
  	  for (i = tamanho; i >= 1; i--) { 
      	soma += numeros.charAt(tamanho - i) * pos--; 
	      if (pos < 2) { 
  	      pos = 9;
        } 
	    } 
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11; 
    	if (resultado != digitos.charAt(0)) { 
        alert('Número do CNPJ Inválido!');
        setTimeout("document.getElementById('"+campo+"').focus()",250);
      } 
    	tamanho = tamanho + 1; 
	    numeros = cnpj.substring(0,tamanho); 
    	soma = 0; 
	    pos = tamanho - 7; 
  	  for (i = tamanho; i >= 1; i--) { 
    	  soma += numeros.charAt(tamanho - i) * pos--; 
      	if (pos < 2) { 
        	pos = 9;
        } 
    	} 
    	resultado = soma % 11 < 2 ? 0 : 11 - soma % 11; 
	    if (resultado != digitos.charAt(1)) { 
        alert('Número do CNPJ Inválido!');
        setTimeout("document.getElementById('"+campo+"').focus()",250);
      } 
  	  return true; 
  	} else { 
      alert('Número do CNPJ Inválido!');
      setTimeout("document.getElementById('"+campo+"').focus()",250);
    }
  } 
} 

function mascaraPlaca(campo) {
  var placa = document.getElementById(campo).value;
  if (placa.length == 3) {
    placa = placa+"-"; 
  }
  document.getElementById(campo).value = placa;
}

function validaPlaca(campo) {    
  var placa = document.getElementById(campo).value;
  placa = placa.toUpperCase();
  if (placa != '') {
    exp = /^[A-Z]{3}\-[0-9]{4}$/;
    if  (!exp.test(placa)) {
      alert('Placa Inválida!');
      setTimeout("document.getElementById('"+campo+"').focus()",250);
    }
  }
  document.getElementById(campo).value = placa;
}



function IsEmail(email){
  var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
  var check=/@[\w\-]+\./;
  var checkend=/\.[a-zA-Z]{2,3}$/;
  if(((email.search(exclude) != -1)||(email.search(check)) == -1)||(email.search(checkend) == -1)){
    return false;
  } else {
    return true;
  }
}
            
-->    
</script>