//Función para comprar en la tienda
function comprar(id_producto, id_user, precio, pagina) {
    $.ajax({
        url: 'http://localhost//Pagina/proyecto/pages/tienda/funciones.php',
        type: 'POST',
        dataType: 'html',
        data: {
            id_producto: id_producto,
            id_user: id_user,
            precio: precio,
            pagina: pagina,
            function: "comprar"
        }

    })
        .done(function () {
                Swal.fire({
                    title: "¡Comprado!",
                    text: '¡Su compra ha sido realizada!'
                }).then((result) => {
                    window.location.reload();
                });

        });
}

//Función para seleccionar el fondo
function seleccionar(id_producto, id_user, pagina) {
    $.ajax({
        url: 'http://localhost//Pagina/proyecto/pages/tienda/funciones.php',
        type: 'POST',
        dataType: 'html',
        data: {
            id_producto: id_producto,
            id_user: id_user,
            pagina: pagina,
            function: "seleccionar"
        }

    })
        .done(function () {
                Swal.fire({
                    title: "¡Seleccionado!",
                    text: '¡Ahora el fondo estará disponible en la sección ' + pagina + '!'
                }).then((result) => {
                    window.location.reload();
                });

        });
}

//Función para quitar el fondo
function quitar(id_producto, id_user, pagina) {
    $.ajax({
        url: 'http://localhost//Pagina/proyecto/pages/tienda/funciones.php',
        type: 'POST',
        dataType: 'html',
        data: {
            id_producto: id_producto,
            id_user: id_user,
            pagina: pagina,
            function: "quitar"
        }

    })
        .done(function () {
                Swal.fire({
                    title: "¡Quitado!",
                    text: '¡El fondo vuelve a ser el mismo en la sección ' + pagina + '!'
                }).then((result) => {
                    window.location.reload();
                });

        });
}