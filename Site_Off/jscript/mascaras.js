function mMascarasJS (formato, objeto) {
	campo = document.getElementById(objeto);
	
	//CPF
	if (formato=='CPF')	{
		caracteres = '01234567890';
		separacoes = 3;
		separacao1 = '.';
		separacao2 = '-';
		conjuntos = 4;
		conjunto1 = 3;
		conjunto2 = 7;
		conjunto3 = 11;
		conjunto4 = 14;
		if (campo.value.length < conjunto4) {
			if (campo.value.length == conjunto1) campo.value = campo.value + separacao1;
			else if (campo.value.length == conjunto2) campo.value = campo.value + separacao1;
			else if (campo.value.length == conjunto3) campo.value = campo.value + separacao2;
		}
		else event.returnValue = false;
	}
	 
	//CNPJ
	if (formato=='CNPJ')	{
		caracteres = '01234567890';
		separacoes = 4;
		separacao1 = '.';
		separacao2 = '/';
		separacao3 = '-';
		conjuntos = 5;
		conjunto1 = 2;
		conjunto2 = 6;
		conjunto3 = 10;
		conjunto4 = 15;
		conjunto5 = 18;
		if (campo.value.length < conjunto5) {
			if (campo.value.length == conjunto1) campo.value = campo.value + separacao1;
			else if (campo.value.length == conjunto2) campo.value = campo.value + separacao1;
			else if (campo.value.length == conjunto3) campo.value = campo.value + separacao2;
			else if (campo.value.length == conjunto4) campo.value = campo.value + separacao3;
		}
		else event.returnValue = false;
	}
	
	//CEP
	if (formato=='CEP') {
		if (campo.value.length <= 8) {
			separador = '-';
			conjunto1 = 5;
			if (campo.value.length == conjunto1) {
				campo.value = campo.value + separador;
			}
		} else {
			event.returnValue = false;
		}
	}


	//NASCIMENTO
	if (formato=='DATA') {
		separador = '/'; 
		conjunto1 = 2;
		conjunto2 = 5;
		if (campo.value.length == conjunto1) {
			campo.value = campo.value + separador;
		}
		if (campo.value.length == conjunto2) {
			campo.value = campo.value + separador;
		}
	}
	
	//TELEFONE
	if (formato=='TELEFONE') {
		if (campo.value.length <= 13) {
			separador1 = '(';
			separador2 = ') ';
			separador3 = '-';
			conjunto1 = 0;
			conjunto2 = 3;
			conjunto3 = 9;
			if (campo.value.length == conjunto1) {
				campo.value = campo.value + separador1;
			}
			if (campo.value.length == conjunto2) {
				campo.value = campo.value + separador2;
			}
			if (campo.value.length == conjunto3) {
				campo.value = campo.value + separador3;
			}
		} else {
			event.returnValue = false;	
		}
	}
}

function mMascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){ 
    var sep = 0; 
    var key = ""; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = "0123456789"; 
    var aux = aux2 = ""; 
     e=e||window.event;
	var whichCode=e.charCode||e.keyCode||e.which;
     
    if (whichCode == 13) return true;
    if (whichCode == 8) return true;
    if (whichCode == 0) return true;
    
    key = String.fromCharCode(whichCode);
    if (strCheck.indexOf(key) == -1) return false;
    len = objTextBox.value.length; 
    
    for(i = 0; i < len; i++) 
        if ((objTextBox.value.charAt(i) != "0") && (objTextBox.value.charAt(i) != SeparadorDecimal)) break; 
	aux = ""; 
    for(; i < len; i++) 
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) objTextBox.value = ""; 
    if (len == 1) objTextBox.value = "0"+ SeparadorDecimal + "0" + aux; 
    if (len == 2) objTextBox.value = "0"+ SeparadorDecimal + aux; 
   
    if (len > 2) { 
        aux2 = ""; 
        for (j = 0, i = len - 3; i >= 0; i--) { 
            if (j == 3) { 
                aux2 += SeparadorMilesimo; 
                j = 0; 
            } 
            aux2 += aux.charAt(i); 
            j++; 
        } 
        objTextBox.value = ""; 
        len2 = aux2.length; 
       
        for (i = len2 - 1; i >= 0; i--) 
        objTextBox.value += aux2.charAt(i); 
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len); 
    } 
    return false;
}

function mCampoNumerico(Campo, e){ 
 	var key = ''; 
    var len = 0;  
    var strCheck = '0123456789'; 
    var aux = Campo; 
    e=e||window.event;
	var whichCode=e.charCode||e.keyCode||e.which;
    
    if (whichCode == 13 || whichCode == 8 || whichCode == 0) return true; 
    key = String.fromCharCode(whichCode); 
    if (strCheck.indexOf(key) == -1) return false; 
    return aux; 
}
// Marca ou Descamrca todos os checkbox: onclick="checkAllCheckbox(this.check, 'selecionados[]');"
function mCheckAllCheckbox(check, nameInput){
	var els = document.getElementsByTagName("input");
	
	for(var i = 0; i < els.length; i++){
		element = els[i];
	
		if((element.type == "checkbox") && (element.name == nameInput)){
			 if(check == true) element.checked = true;
			 else element.checked = false;
		}
	}
}
// verficica se checck foi marcado, e pergunta se deseja mesmo excluir, nameInput: nome do input, msg1: pergunta, msg2: nada foi selecionado
function mConfirmaExcluir(nameInput, msg1, msg2){
	
	if(msg1 == undefined) msg1 = "Tem certeza que deseja excluir o(s) cadastro(s) selecionado(s)?";
	if(msg2 == undefined) msg2 = "Nenhum item foi selecionado!";
	
	var els = document.getElementsByTagName("input");
	var checar = 0;
	
	for(var i = 0; i < els.length; i++){
		element = els[i];
		
		if((element.type == "checkbox") && (element.name == nameInput) && (element.checked == true)){
			++checar;
		}
	}
	
	if(checar > 0) return confirm(msg1);
	else { alert(msg2); return false; }
}

function ValidarCPF(Objcpf){
        var cpf = Objcpf.value;
        exp = /\.|\-/g
        cpf = cpf.toString().replace( exp, "" ); 
        var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
        var soma1=0, soma2=0;
        var vlr =11;
        
        for(i=0;i<9;i++){
                soma1+=eval(cpf.charAt(i)*(vlr-1));
                soma2+=eval(cpf.charAt(i)*vlr);
                vlr--;
        }       
        soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
        soma2=(((soma2+(2*soma1))*10)%11);
        
        var digitoGerado=(soma1*10)+soma2;
        if(digitoGerado!=digitoDigitado) {
           alert('CPF Invalido!');
           Objcpf.value = "";
	    }
	    
	    if( (cpf == "00000000000") ||  (cpf == "11111111111") || (cpf == "22222222222") || (cpf == "33333333333") || (cpf == "44444444444") || 
	    (cpf == "55555555555") || (cpf == "66666666666") || (cpf == "77777777777") || (cpf == "88888888888") || (cpf == "99999999999") )
	    {
	    	alert('CPF Invalido!');
           Objcpf.value = "";
	    }
	    
                       
}


function ValidarCNPJ( c ) {

    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais, cnpj = c.value.replace(/\D+/g, '');
    digitos_iguais = 1;
    
    if (cnpj.length != 14) {
	    alert('CNPJ invalido');
	    c.value="";
	    return false;
    }
    for (i = 0; i < cnpj.length - 1; i++)
    	if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
        	digitos_iguais = 0;
            break;
        }
     	if (!digitos_iguais) {
        	tamanho = cnpj.length - 2
        	numeros = cnpj.substring(0,tamanho);
           	digitos = cnpj.substring(tamanho);
           	soma = 0;
           	pos = tamanho - 7;
           	for (i = tamanho; i >= 1; i--) {
            	soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) 
                   pos = 9;
            }
           	resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
           	if (resultado != digitos.charAt(0)) {
            	alert('CNPJ invalido');
            	c.value="";
            	return false;
            }

           	tamanho = tamanho + 1;
           	numeros = cnpj.substring(0,tamanho);
           	soma = 0;
           	pos = tamanho - 7;
           	for (i = tamanho; i >= 1; i--)
            {
            	soma += numeros.charAt(tamanho - i) * pos--;
            	if (pos < 2) 
                   pos = 9;
            }
           	resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
          	if (resultado != digitos.charAt(1)){
            	alert('CNPJ invalido');
            	c.value="";
            	return false;
            }
           	else {
            	return true;
            }
    	}
     	else{
           alert('CNPJ inválido');
           c.value="";
           return false;
        }
} 