<?php
    require "../../../config.php";

    // PROBAR RESPUESTA CON POSTMAN
    //$user_on = "pato";
    //$id_on = 8;
    //$name_friend = "prueba";

    $mostrar_chat = "";

    //SWITCH PARA DETERMINAR LAS VARIABLES EN FUNCION SI VIENE MEDIANTE GET O POST
    switch($_SERVER['REQUEST_METHOD']){
        case "GET":
            $id_on = $_GET['id_on'];
            $name = $_GET['name'];
            $chat = $_GET['chat']; // PARA DIFERENCIAR ENTRE AMIGOS Y GRUPOS

            mostrarChatAmigos($id_on, $name, $chat);
        break;

        case "POST":
            $id_on = $_POST['id_on'];
            $name = $_POST['name']; // PUEDE SER name DE UN AMIGO O UN GRUPO
            $mensaje = $_POST['mensaje'];
            $chat = $_POST['chat']; // PARA DIFERENCIAR ENTRE AMIGOS Y GRUPOS

            enviarMensaje($mensaje, $id_on, $name, $chat);
        break;

        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            $id_mensaje = $_DELETE['id_mensaje'];
            $chat = $_DELETE['chat'];

            eliminarMensaje($id_mensaje, $chat);
        break;
    }

/** -----------------------------------------------------------------------------------------------------------------
 * 
 * FUNCIONES PARA LOS METODOS MOSTRAR CHAT / ENVIAR MENSAJE 
 * 
 *  -----------------------------------------------------------------------------------------------------------------
 */ 
    /** -------------------------------------------------------------------------------------------------------------
     * METODO PARA MOSTRAR EL CHAT DE AMIGOS
     */
    function mostrarChatAmigos($id_on, $name, $chat){
        require "../../../config.php"; // HAY QUE PONERLO EN TODAS LAS FUNCIONES

        switch($chat){
            case "amigo":
                $select_message = "
                    SELECT * FROM friend_message WHERE
                        (id_emisor = :id_on 
                            OR 
                        id_receptor = :id_on)
                            AND
                        (id_emisor = (SELECT id FROM user WHERE usuario = :name)
                            OR 
                        id_receptor = (SELECT id FROM user WHERE usuario = :name))
                ";
                $stmt_message = $conn->prepare($select_message);
                $stmt_message->bindParam(':id_on', $id_on);
                
            break;

            case "grupo":
                /**
                 * PRIMERO QUERY PARA SABER SI ERES EL ADMIN
                 */
                $select_admin = "
                    SELECT * FROM grupo_user WHERE
                        id_user = :id_on AND
                        id_grupo = (SELECT id FROM grupo WHERE name = :name)
                ";
                $stmt_admin = $conn->prepare($select_admin);
                $stmt_admin->bindParam(':id_on', $id_on);
                $stmt_admin->bindParam(':name', $name);
                $stmt_admin->execute();
                $user_admin = $stmt_admin->fetch();

                $select_message = "
                    SELECT * FROM grupo_message WHERE
                        id_grupo = (SELECT id FROM grupo WHERE name = :name)
                ";
                $stmt_message = $conn->prepare($select_message);
            break;
        }
        $stmt_message->bindParam(':name', $name);
        $stmt_message->execute();
        
        $msg = $stmt_message->fetch();
        if($stmt_message->rowCount()>0) {
            for($i = 0; $i<$stmt_message->rowCount(); $i++) {

                $date = date('H:i', strtotime($msg['time']));

                if($msg['id_emisor'] == $id_on){
                    $GLOBALS['mostrar_chat'] .= "<div class='me'>
                                                    <button class='drch caja_msj' onclick='dropBorrar(".$msg['id'].")'>
                                                        <p class='texto'>".$msg['message']."</p>
                                                        <label class='hora'>".$date."</label>
                                                    </button>
                                                    <!-- MODAL PARA LA ELIMINACION DE MENSAJES -->
                                                    <ul id='menuMensaje".$msg['id']."' class='menuBorrar' class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1'> 
                                                        <li role='presentation'>
                                                            <button onclick=eliminarMensaje(".$msg['id'].",'".$chat."') class='borrar_msg'>Eliminar mensaje</button>
                                                        </li>
                                                    </ul>
                                                    ";
                                                    

                    while(($msg = $stmt_message->fetch()) && ($msg['id_emisor'] == $id_on)){
                        $date = date('H:i', strtotime($msg['time']));

                        $GLOBALS['mostrar_chat'] .= "<button class='drch caja_msj' onclick='dropBorrar(".$msg['id'].")'>
                                                        <p class='texto'>".$msg['message']."</p>
                                                        <label class='hora'>".$date."</label>
                                                    </button>
                                                    <!-- MODAL PARA LA ELIMINACION DE MENSAJES -->
                                                    <ul id='menuMensaje".$msg['id']."' class='menuBorrar' class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1'> 
                                                        <li role='presentation'>
                                                            <button onclick=eliminarMensaje(".$msg['id'].",'".$chat."') class='borrar_msg'>Eliminar mensaje</button>
                                                        </li>
                                                    </ul>
                                                    ";
                        $i++;
                    }

                    $GLOBALS['mostrar_chat'] .= "</div>";
                }else{
                    $GLOBALS['mostrar_chat'] .= "<div class='other'>";
                    //SOLAMENTE CUANDO SE MUESTRAN GRUPOS
                    if($chat == "grupo"){

                        $select = "SELECT usuario FROM user WHERE id = :id_emisor";
                        $stmt = $conn->prepare($select);
                        $stmt->bindParam(':id_emisor', $msg['id_emisor']);
                        $stmt->execute();
                        $user = $stmt->fetch();

                        $GLOBALS['mostrar_chat'] .="<div class='caja_nombre'>
                                                        <p class='texto'>".$user['usuario']."</p>
                                                    </div>";
                    }

                    if(@$user_admin['admin']){
                        $GLOBALS['mostrar_chat'] .=     "<button class='caja_msj' onclick='dropBorrar(".$msg['id'].")'>
                                                            <p class='texto'>".$msg['message']."</p>
                                                            <label class='hora'>".$date."</label>
                                                        </button>
                                                        <!-- MODAL PARA LA ELIMINACION DE MENSAJES -->
                                                        <ul id='menuMensaje".$msg['id']."' class='menuBorrar' class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1'> 
                                                            <li role='presentation'>
                                                                <button onclick=eliminarMensaje(".$msg['id'].",'".$chat."') class='borrar_msg'>Eliminar mensaje</button>
                                                            </li>
                                                        </ul>
                                                        ";
                    } else {
                        $GLOBALS['mostrar_chat'] .=     "<button class='caja_msj'>
                                                            <p class='texto'>".$msg['message']."</p>
                                                            <label class='hora'>".$date."</label>
                                                        </button>";
                    }

                    /**
                     * CONDICIONES DEL BUCLE
                     * SE REPITE SIEMPRE QUE $msg['id_emisor'] NO SEA IGUAL AL $id_on
                     * Y SIEMPRE QUE $msg['id_emisor'] SEA EL MISMO QUE $id_anterior
                     */
                    $id_anterior = $msg['id_emisor'];
                    while(($msg = $stmt_message->fetch()) && ($msg['id_emisor'] != $id_on && $msg['id_emisor'] == $id_anterior)){
                        $date = date('H:i', strtotime($msg['time']));

                        if(@$user_admin['admin']){
                            $GLOBALS['mostrar_chat'] .=     "<button class='caja_msj' onclick='dropBorrar(".$msg['id'].")'>
                                                                <p class='texto'>".$msg['message']."</p>
                                                                <label class='hora'>".$date."</label>
                                                            </button>
                                                            <!-- MODAL PARA LA ELIMINACION DE MENSAJES -->
                                                            <ul id='menuMensaje".$msg['id']."' class='menuBorrar' class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1'> 
                                                                <li role='presentation'>
                                                                    <button onclick=eliminarMensaje(".$msg['id'].",'".$chat."') class='borrar_msg'>Eliminar mensaje</button>
                                                                </li>
                                                            </ul>
                                                            ";
                        } else {
                            $GLOBALS['mostrar_chat'] .=     "<button class='caja_msj'>
                                                                <p class='texto'>".$msg['message']."</p>
                                                                <label class='hora'>".$date."</label>
                                                            </button>";
                        }
                        $i++;
                        $id_anterior = $msg['id_emisor'];
                    }

                    $GLOBALS['mostrar_chat'] .= "</div><div></div>";
                }
            }
        } else {
            $GLOBALS['mostrar_chat'] .= "   <div class='vacio'>
                                                <label> No hay mensajes </label>
                                            </div>";
        }
        echo $GLOBALS['mostrar_chat'];
    }

    /** -------------------------------------------------------------------------------------------------------------
     * METODO PARA ENVIAR MENSAJES
     */
    function enviarMensaje($mensaje, $id_on, $name, $chat){
        require "../../../config.php"; // HAY QUE PONERLO EN TODAS LAS FUNCIONES

        switch($chat){
            case "amigo":
                $insert_message = "
                    INSERT INTO friend_message
                        (message, id_emisor, id_receptor) 
                            VALUES
                        (:mensaje, :id_on, (SELECT id FROM user WHERE usuario = :name))
                ";
                $stmt_message = $conn->prepare($insert_message);
            break;

            case "grupo":
                $insert_message = "
                    INSERT INTO grupo_message
                        (id_grupo, id_emisor, message) 
                            VALUES
                        ((SELECT id FROM grupo WHERE name = :name), :id_on, :mensaje)
                ";
                $stmt_message = $conn->prepare($insert_message);
            break;
        }
        
        $stmt_message->bindParam(':mensaje', $mensaje);
        $stmt_message->bindParam(':id_on', $id_on);
        $stmt_message->bindParam(':name', $name);
        $stmt_message->execute();
    }

    /** -------------------------------------------------------------------------------------------------------------
     * METODO PARA ELIMINAR MENSAJES
     */
    function eliminarMensaje($id_mensaje, $chat){
        require "../../../config.php"; // HAY QUE PONERLO EN TODAS LAS FUNCIONES

        if($chat == "grupo"){
            $delete_ntf = "DELETE FROM grupo_message WHERE id = :id_mensaje";
            
        } else if ($chat == "amigo") {
            $delete_ntf = "DELETE FROM friend_message WHERE id = :id_mensaje";
        }
        $stmt_ntf = $conn->prepare($delete_ntf);
        $stmt_ntf->bindParam(':id_mensaje', $id_mensaje);
        $stmt_ntf->execute();
    }
?>