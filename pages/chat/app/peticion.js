/**
 * JS PARA QUE SEGUN SE INICIE EL CHAT RECOJA LOS VALORES ESCERITOS EN 
 * LAS CAJAS DE BUSQUEDAS, QUE ESTAN VACIAS, PARA ENVIARSELAS A AJAX
 * PARA QUE REALICE LA QUERY DE TODOS NUESTROS GRUPOS Y AMIGOS
 */
$(document).ready(main);

function main(){
	var valor_busquedaAmigo = $("#busqueda_amigo").val();
	var valor_busquedaGrupo = $("#busqueda_grupos").val();
	var valor_busquedaUsuario = $("#busqueda_usuario").val();

	if (valor_busquedaAmigo!=""){
		obtener_amigos(valor_busquedaAmigo);
	} else{
		obtener_amigos();
	}

	if (valor_busquedaGrupo!=""){
		obtener_grupos(valor_busquedaGrupo);
	}
	else{
		obtener_grupos();
	}

	if (valor_busquedaUsuario!=""){
		obtener_grupos(valor_busquedaUsuario);
	}
	else{
		obtener_grupos();
	}
}

/**
 * TEMPORIZADOR PARA CARGAR DE NUEVO LOS AMIGOS O GRUPOS POR SI EL USUARIO NO RECARGARA LA PAGINA
 * ASI SI ALGUIEN LE AGREGARA O LE ACEPTARAN SU PETICION A UN GRUPO ESTE APARECERIA
 */
setInterval(function() {
	main();
}, 10000); // TEMPORIZACION CADA 10 SEG

/**
 * AJAX PARA LA VISUALIZACION DE LOS AMIGOS DEL USUARIO
 */
function obtener_amigos(valor_busqueda) {


	$.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/mostrar_amigoGrupo.php',
		type : 'POST',
		dataType : 'html',
		data : {
			valor_busqueda: valor_busqueda,
			tipo: "amigo"
		}
	})
	.done(function(respuesta){
		$("#scroll_amigos").html(respuesta);
	});
}
//DETECTA PULSACIONES EN LAS TECLAS DONDE COINCIDA CON EL ID
$(document).on('keyup', '#busqueda_amigo', function() {

	var valor_busqueda=$(this).val();

	if (valor_busqueda!=""){
		obtener_amigos(valor_busqueda);
	} else{
		obtener_amigos();
	}
});

/**
 * AJAX PARA LA VISUALIZACION DE LOS GRUPOS DEL USUARIO
 */
function obtener_grupos(valor_busqueda) {
 
	$.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/mostrar_amigoGrupo.php',
		type : 'POST',
		dataType : 'html',
		data : {
			valor_busqueda: valor_busqueda,
			tipo: "grupo"
		}
	})
	.done(function(respuesta){
		$("#scroll_grupos").html(respuesta);
	});
}
 
//DETECTA PULSACIONES EN LAS TECLAS DONDE COINCIDA CON EL ID
$(document).on('keyup', '#busqueda_grupos', function() {
 
	var valor_busqueda=$(this).val();
 
	if (valor_busqueda!=""){
		obtener_grupos(valor_busqueda);
	}
	else{
		obtener_grupos();
	}
});