function enviarPeticion(objeto, metodo, datos, callback){
	$.ajax({
		url:'api',
		type:'POST',
		dataType: 'json',					
		data:{
            objeto: objeto,
            metodo: metodo,
            datos: datos
        },	
		success: function(respuesta){
			if(respuesta.ejecuto == true){
				callback(respuesta)
			}else{				
                toastr.error(respuesta.mensajeError)
			}
		},
		error: function(xhr, status){
			console.log('Ocurrio un error')
		}
	})
}

function enviarPeticionPura(objeto, metodo, datos, callback){
    $.ajax({
        url:'api',
        type:'POST',
        dataType: 'json',                   
        data:{
            objeto: objeto,
            metodo: metodo,
            datos: datos
        },  
        success: function(respuesta){            
                callback(respuesta)            
        },
        error: function(xhr, status){
            console.log('Ocurrio un error')
        }
    })
}

function parsearFormulario(form){
	let formulario = $(form).serializeArray()
	let respuesta = {}
	for(let i = 0; i < formulario.length; i++){
		respuesta[formulario[i].name] = formulario[i].value
	}
	return respuesta
}

//El campo defecto indica lo siguiente:
//En caso de 0: no muestra campo por defecto
//En caso de 1: Muestra un valor por defecto vacio
//En caso de 2: Muestra un valor por defecto en 1 que es el default de las llaves foraneas
function llenarSelect(objeto, metodo, datos, elemento, campo, defecto = 0, textDefault = 'Seleccione...', idc = 'id'){
    enviarPeticion(objeto,metodo,datos,function(r){
        $("#"+elemento).empty();
        switch(defecto){
            case 0:
                break
            case 1:
                $("#"+elemento).append('<option value = "">'+textDefault+'</option>');
                break
            case 2:
                $("#"+elemento).append('<option value = 1>'+textDefault+'</option>');
                break
        }        
        for (i = 0; i < r.data.length; i++){        
            $("#"+elemento).append("<option value='"+r.data[i][idc]+"'>"+r.data[i][campo]+"</option>")
        }
    })
}

function llenarSelectCallback(objeto, metodo, datos, elemento, campo, defecto = 0, textDefault = 'Seleccione...', idc = 'id', callback){
    enviarPeticion(objeto,metodo,datos,function(r){
        $("#"+elemento).empty();
        switch(defecto){
            case 0:
                break
            case 1:
                $("#"+elemento).append('<option value = "">'+textDefault+'</option>');
                break
            case 2:
                $("#"+elemento).append('<option value = 1>'+textDefault+'</option>');
                break
        }        
        for (i = 0; i < r.data.length; i++){        
            $("#"+elemento).append("<option value="+r.data[i][idc]+">"+r.data[i][campo]+"</option>")
        }
        callback()
    })
}

function llenarList(objeto, metodo, datos, elemento){
    enviarPeticion(objeto, metodo, datos, function(r){
        $("#"+elemento).empty();
        r.data.map(registro => {
            $("#"+elemento).append(`<option data-value="${registro.id}" value="${registro.nombre}">`)
        })
    })   
}

function llenarFormulario(formulario, objeto, metodo, datos, callback){
	enviarPeticion(objeto, metodo, datos, function(r){
		$.each(r.data[0], function(campo, valor){
            if($('#'+formulario+' #'+campo).is(":checkbox")){
                if(valor == 1){
                    $('#'+formulario+' #'+campo).prop("checked", true)
                }
            }else{
                $('#'+formulario+' #'+campo).val(valor)    
            }			
		})
		callback(r)
	})
}

function currency(value, decimals, separators) {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (3 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}