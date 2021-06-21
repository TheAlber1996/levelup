<?php
    require "../config.php";

    switch($_SERVER['REQUEST_METHOD']){
        case "GET":
            //OBTENEMOS TODOS LOS DATOS DEL USUARIO PARA LA FOTO Y DEMAS
            $select_datos = "SELECT * FROM user WHERE id = :id";
            $stmt_datos = $conn->prepare($select_datos);
            $stmt_datos->bindParam(':id', $_GET['id_on']);
            $stmt_datos->execute();
            $dato = $stmt_datos->fetch();

            $array_datos = array('id'=>$dato['id'],
                                'usuario'=>$dato['usuario'],
                                'email'=>$dato['email'],
                                'profile_pic'=>$dato['profile_pic']
            );

            echo json_encode($array_datos, JSON_NUMERIC_CHECK);
        break;

        case "PUT":
            //OBTENGO LOS PARAMETROS QUE HE PASADO MEDIANTE PUT EN LA VARIABLE QUE HE LLAMADO $_PUT
            parse_str(file_get_contents("php://input"), $_PUT);

            $off = "UPDATE user SET online = 0 WHERE id = :id";
            $stmt = $conn->prepare($off);
            $stmt->bindParam(':id', $_PUT['id_on']);
            $stmt->execute();
        break;
    }

?>