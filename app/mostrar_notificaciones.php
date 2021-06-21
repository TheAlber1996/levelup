<?php
    require "../config.php";

    switch($_POST['funcion']){

        /**--------------------------------------------------------------------------------------
         * 
         * MUESTRA LAS NOTIFICACIONES QUE TIENE CADA USUARIO
         */
        case "mostrar":
            $id_on = $_POST['id_on'];

            $select_notification = "SELECT * FROM notification WHERE id_receptor = :id_on";
            $stmt_notification = $conn->prepare($select_notification);
            $stmt_notification->bindParam(':id_on', $id_on);
            $stmt_notification->execute();
            
            $mostrar_notificaiones = "";

            if($stmt_notification->rowCount()>0){
                while($notification = $stmt_notification->fetch()){

                    /**
                     * tipo_notification SIRVE PARA DIFERENCIAR ENTRE LOS TIPOS DE NOTIFICACIONES QUE HAYA
                     * YA SEA:
                     * 1 - PARA AGREGAR AMIGOS
                     * 2 - PEDIR SOLICITUD DE ACCESO AL GRUPO COMO USUARIO
                     * 3 - ENVIAR INVITACION DEL GRUPO AL USUARIO 
                     * 4 - CUANDO HAY RESPUESTAS EN TU POST
                     * 5 - CUANDO TE EXPULSAN DE UN GRUPO
                     */
                    switch($notification['tipo_notification']){
                        case 1: 
                            $mostrar_notificaiones .= "
                                <li class='notify'>
                                    <img src='/pagina/proyecto/img/friends.png'>
                                    <label>".$notification['notification']."</label>
                                    <div>
                                        <button class='bt_notify' onclick='aceptar(".$notification['id'].",".$notification['tipo_notification'].",".$notification['id_emisor'].")'><img src='/pagina/proyecto/img/check.png'></button>
                                        <button class='bt_notify' onclick='rechazar(".$notification['id'].")'><img src='/pagina/proyecto/img/cross.png'></button>
                                    </div>
                                </li>
                            ";
                        break;

                        case 2:
                            $mostrar_notificaiones .= "
                                <li class='notify'>
                                    <img src='/pagina/proyecto/img/bot.png'>
                                    <label>".$notification['notification']."</label>
                                    <div>
                                        <button class='bt_notify' onclick='aceptarGrupo(".$notification['id'].",".$notification['tipo_notification'].",".$notification['id_emisor'].",".$notification['id_grupo'].")'><img src='/pagina/proyecto/img/check.png'></button>
                                        <button class='bt_notify' onclick='rechazar(".$notification['id'].")'><img src='/pagina/proyecto/img/cross.png'></button>
                                    </div>
                                </li>
                            ";
                        break;

                        case 3:
                            $mostrar_notificaiones .= "
                                <li class='notify'>
                                    <img src='/pagina/proyecto/img/grupo4.png'>
                                    <label>".$notification['notification']."</label>
                                    <div>
                                        <button class='bt_notify' onclick='aceptar(".$notification['id'].",".$notification['tipo_notification'].",".$notification['id_grupo'].")'><img src='/pagina/proyecto/img/check.png'></button>
                                        <button class='bt_notify' onclick='rechazar(".$notification['id'].")'><img src='/pagina/proyecto/img/cross.png'></button>
                                    </div>
                                </li>
                            ";
                        break;

                        case 4:
                            $mostrar_notificaiones .= "
                                <li class='notify'>
                                    <img src='/pagina/proyecto/img/comentarios.png'>
                                    <label>".$notification['notification']."</label>
                                    <button class='bt_notify' onclick='ir(".$notification['id'].",".$notification['id_topic']."); '>Ir...</button>
                                </li>
                            ";
                        break;

                        case 5:
                            $mostrar_notificaiones .= "
                                <li class='notify'>
                                    <img src='/pagina/proyecto/img/expulsado.png'>
                                    <label>".$notification['notification']."</label>
                                    <div>
                                        <button class='bt_notify' onclick='rechazar(".$notification['id'].")'><img src='/pagina/proyecto/img/cross.png'></button>
                                    </div>
                                </li>
                            ";
                        break;
                    }
                    

                }
            } else {
                $mostrar_notificaiones = "
                    <li class='sinntf'>
                        <label>No tienes notificaciones</label>
                    </li>
                ";
            }

            echo $mostrar_notificaiones;
        break;
    }
?>
