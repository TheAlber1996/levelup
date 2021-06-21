/**
 * FUNCION QUE ENVIA UNA PETICION DE AMISTAD AL USUARIO
 * @param {*} id_user 
 */
function agregar(id_user) {

    $.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/notificaciones.php',
		type : 'POST',
		dataType : 'html',
		data : {
			tipo: 1,
			id_user: id_user
		}

	})
	.done(function(problema){

		if(problema == "ninguno"){
			Swal.fire({
				title: "¡Hecho!",
				text: '¡¡Su petición de amistad ha sido enviada!!',
				backdrop: `
					rgba(0,0,123,0.4)
					url("../../img/nyan_cat.gif")
					left top
					no-repeat
				`
			}).then((result) => {
				window.location.reload();
			});
		} else {
			Swal.fire({
				text: '¡Ya has mandado la peticion a este usuario!'
			}).then((result) => {
			});
		}
		
	});
}

/**
 * FUNCION QUE ENVIA UNA PETICION DE ENTRADA A UN GRUPO
 * @param {*} id_grupo 
 */
function solicitud(id_grupo) {

    $.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/notificaciones.php',
		type : 'POST',
		dataType : 'html',
		data : {
			tipo: 2,
			id_grupo: id_grupo
		}

	})
	.done(function(problema){

		if(problema == "ninguno"){
			Swal.fire({
				title: "¡Hecho!",
				text: '¡¡Su invitación al grupo ha sido enviada!!',
				background: '#2a2f32',
				backdrop: `
					rgba(0,0,123,0.4)
					url("../../img/nyan_cat.gif")
					left top
					no-repeat
				`
			}).then((result) => {
			});
		} else if(problema == "repetido") {
			Swal.fire({
				text: '¡Ya has mandado la peticion a este grupo!',
				background: '#2a2f32'
			}).then((result) => {
			});
		} else if(problema == "recluta"){
			Swal.fire({
				text: '¡Ya estas en este grupo!',
				background: '#2a2f32'
			}).then((result) => {
			});
		}
		
	});
}

/**
 * FUNCION QUE ENVIA LA PETICION AL USUARIO PARA ACCEDER AL GRUPO
 * @param {*} id_user 
 * @param {*} id_grupo 
 */
function agregarGrupo(id_user, id_grupo) {

    $.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/notificaciones.php',
		type : 'POST',
		dataType : 'html',
		data : {
			tipo: 3,
			id_grupo: id_grupo,
			id_user: id_user
		}

	})
	.done(function(problema){

		if(problema == "ninguno"){
			Swal.fire({
				title: "¡Hecho!",
				text: '¡¡La peticion de acceso al grupo ha sido enviada al usuario!!',
				backdrop: `
					rgba(0,0,123,0.4)
					url("../../img/nyan_cat.gif")
					left top
					no-repeat
				`
			}).then((result) => {
			});
		} else {
			Swal.fire({
				text: '¡Ya has mandado la peticion a este usuario!',
			}).then((result) => {
			});
		}
		
	});
}

/**
 * FUNCION PARA NOTIFICAR CUANDO TE HAN CONTESTADO EN UN POST
 * @param {} id_topic 
 */
function notificar(id_topic) {

    $.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/notificaciones.php',
		type : 'POST',
		dataType : 'html',
		data : {
			tipo: 4,
			id_topic: id_topic
		}

	})
	.done(function(){
	});
}

/**
 * FUNCION QUE ELIMINA A UN USUARIO DE UN GRUPO, TAMBIEN ENVIA UNA NOTIFICACION
 * @param {*} id_user 
 * @param {*} id_grupo 
 */
function eliminar(id_user, id_grupo) {

    $.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/notificaciones.php',
		type : 'POST',
		dataType : 'html',
		data : {
			tipo: 5,
			id_grupo: id_grupo,
			id_user: id_user
		}

	})
	.done(function(){
		window.location.reload();
	});
}

/** ARRIBA NOTIFICACIONES
 * --------------------------------------------------------------------------------------------------------------
 *  ABAJO DEMAS COMANDOS
 */ 

/**
 * FUNCION PARA ELIMINAR LOS MENSAJES
 * @param {*} id_user 
 * @param {*} id_grupo 
 */
function eliminarMensaje(id_mensaje, chat) {

    $.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/funciones_chat.php',
		type : 'DELETE',
		dataType : 'html',
		data : {
			id_mensaje: id_mensaje,
			chat: chat
		}
	})
	.done(function(){
		obtener_chat();
	});
}

/**
 * DROP PARA MOSTRAR EL ELIMINAR MENSAJE DE CADA MENSAJE
 * @param {*} id 
 */
function dropBorrar(id) {

	var id_elemento = "menuMensaje"+id;

    document.getElementById(id_elemento).classList.toggle("show");
}

/**
 * FUNCION PARA MOSTRAR MENU DE EMOTICONOS
 */
function emoji(){
    document.getElementById("menuEmoji").classList.toggle("show");
}

/**
 * FUNCION PARA ESCRIBIR LOS EMOTICONOS EN LA CAJA DE TEXTO
 */
function escribeEmoji(emoji){

	var caja = document.getElementById("caja_mensaje");
	caja.value += emoji;
}

/**
 * FUNCION PARA CUANDO SE PULSA LA TECLA ENTER
 */
function pulsar(e) {

	if (e.keyCode === 13 && !e.shiftKey) {
        e.preventDefault();
        var boton = document.getElementById("bt_enviar");
        angular.element(boton).triggerHandler('click');
    }
}

/**
 * FUNCION PARA CUANDO SE PULSA LA TECLA ENTER
 */
 function enviarMensaje(id_on, name, chat) {

	var mensaje = document.getElementById("caja_mensaje").value;

	//alert(id_on+' '+mensaje+' '+nombre+' '+chat);
	$.ajax({
		url : 'http://localhost//Pagina/proyecto/pages/chat/app/funciones_chat.php',
		type : 'POST',
		dataType : 'html',
		data : {
			id_on: id_on,
			mensaje: mensaje,
			name: name,
			chat: chat
		}
	})
	.done(function(repetido){
		document.getElementById("caja_mensaje").value = "";
		obtener_chat();
	});
}