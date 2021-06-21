<?php
session_start();
require("../../config.php");

switch ($_POST['function']) {
        //COMPRAR EN TIENDA
    case 'comprar':
        //Variables
        $id_producto = $_POST['id_producto'];
        $id_user = $_POST['id_user'];
        $precio = $_POST['precio'];
        $pagina = $_POST['pagina'];

        //Actualizar cartera comprar
        $sql_actualizar_dinero = 'UPDATE user SET money=money-:price WHERE id = :usuario';

        $stmt_actualizar_dinero = $conn->prepare($sql_actualizar_dinero);
        $stmt_actualizar_dinero->bindParam(':price', $precio);
        $stmt_actualizar_dinero->bindParam(':usuario', $id_user);
        $stmt_actualizar_dinero->execute();

        //Añadir producto a tienda
        $insert_pocket = "
            INSERT INTO pocket 
                (id_usuario, id_producto, page, status)
            VALUES
                (:id_usuario, :id_producto, :page, 0)";

        $stmt_pocket = $conn->prepare($insert_pocket);
        $stmt_pocket->bindParam(':id_usuario', $id_user);
        $stmt_pocket->bindParam(':id_producto', $id_producto);
        $stmt_pocket->bindParam(':page', $pagina);
        $stmt_pocket->execute();


        break;

        //SELECCIONAR FONDO
    case 'seleccionar':
        //Variables
        $id_producto = $_POST['id_producto'];
        $id_user = $_POST['id_user'];
        $pagina = $_POST['pagina'];

        //Quitamos todos los fondos de la página seleccionada
        $sql_pagina = 'UPDATE pocket SET status=0 WHERE id_usuario = :id_usuario AND page = :page';

        $stmt_pagina = $conn->prepare($sql_pagina);
        $stmt_pagina->bindParam(':id_usuario', $_SESSION['id_logueado']);
        $stmt_pagina->bindParam(':page', $pagina);
        $stmt_pagina->execute();

        //Actualizar fondo de pantilla
        $sql_seleccionar = 'UPDATE pocket SET status=1 WHERE id_usuario = :usuario AND id_producto = :producto';

        $stmt_seleccionar = $conn->prepare($sql_seleccionar);
        $stmt_seleccionar->bindParam(':usuario', $id_user);
        $stmt_seleccionar->bindParam(':producto', $id_producto);
        $stmt_seleccionar->execute();
        break;

        //QUITAR FONDO
    case 'quitar':
        //Variables
        $id_producto = $_POST['id_producto'];
        $id_user = $_POST['id_user'];
        $pagina = $_POST['pagina'];

        //Actualizar fondo de pantilla
        $sql_quitar = 'UPDATE pocket SET status=0 WHERE id_usuario = :usuario AND id_producto = :producto';

        $stmt_quitar = $conn->prepare($sql_quitar);
        $stmt_quitar->bindParam(':usuario', $id_user);
        $stmt_quitar->bindParam(':producto', $id_producto);
        $stmt_quitar->execute();
        break;
}
