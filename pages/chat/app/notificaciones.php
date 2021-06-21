<?php
    //SESION INICIA?
    session_start();
    //CONFIGURACION DE LA BASE DE DATOS
    require "../../../config.php";

    switch($_SERVER['REQUEST_METHOD']){
        case "POST":

            //VARIABLES
            $user_on = $_SESSION['nombre_logueado'];
            $id_on = $_SESSION['id_logueado'];
            
            //VARIABLES QUE MANDO POR AJAX
            $tipo_ntf = $_POST['tipo'];

            /**
             * EN EL CASO DE ENVIAR SOLICITUD DE ACCESO A UN GRUPO REALIZAMOS UNA PREVIA QUERY
             * PARA CONOCER QUIEN SERA EL RECEPTOR DE LA NOTIFICACION QUE SERA EL ADMIN DEL 
             * GRUPO AL QUE QUEREMOS ACCEDER.
             */
            if($tipo_ntf == 2){
                //VARIABLES QUE MANDO POR AJAX
                $id_grupo = $_POST['id_grupo'];

                $select = "SELECT * FROM grupo_user WHERE id_grupo = :id_grupo AND admin = 1";
                $stmt = $conn->prepare($select);
                $stmt->bindParam(':id_grupo', $id_grupo);
                $stmt->execute();
                $dato = $stmt->fetch();

                //OBTENEMOS VARIABLE DE LA QUERY
                $id_user = $dato['id_user'];

                /**
                 * COMPRAMOS SI YA SE HA INVIADO UNA INVITACION AL USUARIO SELECCIONADO
                 */
                $select_ntf = "
                    SELECT * FROM notification 
                    WHERE 
                        tipo_notification = :tipo_notification
                    AND
                        id_emisor = :id_emisor
                    AND
                        id_receptor = :id_receptor
                    AND
                        id_grupo = :id_grupo
                ";
                $stmt_ntf = $conn->prepare($select_ntf);
                $stmt_ntf->bindParam(':id_grupo', $id_grupo);
            } else if ($tipo_ntf == 3) {
                //VARIABLES QUE MANDO POR AJAX
                $id_grupo = $_POST['id_grupo'];
                $id_user = $_POST['id_user'];

                /**
                 * COMPRAMOS SI YA SE HA INVIADO UNA INVITACION AL USUARIO SELECCIONADO
                 */
                $select_ntf = "
                    SELECT * FROM notification 
                    WHERE 
                        tipo_notification = :tipo_notification
                    AND
                        id_emisor = :id_emisor
                    AND
                        id_receptor = :id_receptor
                    AND
                        id_grupo = :id_grupo
                ";
                $stmt_ntf = $conn->prepare($select_ntf);
                $stmt_ntf->bindParam(':id_grupo', $id_grupo);
            } else if ($tipo_ntf == 4) {
                //VARIABLES QUE MANDO POR AJAX
                $id_topic = $_POST['id_topic'];

                $select = "SELECT * FROM topics WHERE topic_id = :id_topic";
                $stmt = $conn->prepare($select);
                $stmt->bindParam(':id_topic', $id_topic);
                $stmt->execute();
                $dato_post = $stmt->fetch();

                $name_user = $dato_post['topic_creator'];

                $select = "SELECT * FROM user WHERE usuario = :usuario";
                $stmt = $conn->prepare($select);
                $stmt->bindParam(':usuario', $name_user);
                $stmt->execute();
                $dato_user = $stmt->fetch();

                //OBTENEMOS VARIABLE DE LA QUERY
                $id_user = $dato_user['id'];

                /**
                 * COMPRAMOS SI YA SE HA INVIADO UNA INVITACION AL USUARIO SELECCIONADO
                 */
                $select_ntf = "
                    SELECT * FROM notification 
                    WHERE 
                        tipo_notification = :tipo_notification
                    AND
                        id_emisor = :id_emisor
                    AND
                        id_receptor = :id_receptor
                    AND
                        id_topic = :id_topic
                ";
                $stmt_ntf = $conn->prepare($select_ntf);
                $stmt_ntf->bindParam(':id_topic', $id_topic);
            } else {
                //VARIABLES QUE MANDO POR AJAX
                $id_user = $_POST['id_user'];

                /**
                 * COMPRAMOS SI YA SE HA INVIADO UNA INVITACION AL USUARIO SELECCIONADO
                 */
                $select_ntf = "
                    SELECT * FROM notification 
                    WHERE 
                        tipo_notification = :tipo_notification
                    AND
                        id_emisor = :id_emisor
                    AND
                        id_receptor = :id_receptor
                ";
                $stmt_ntf = $conn->prepare($select_ntf);
            }
            $stmt_ntf->bindParam(':tipo_notification', $tipo_ntf);
            $stmt_ntf->bindParam(':id_emisor', $id_on);
            $stmt_ntf->bindParam(':id_receptor', $id_user);
            $stmt_ntf->execute();

            $problema = "ninguno";
            
            /**
             * SI LA QUERY OBTIENE RESPUESTA SIGNIFICARA QUE LA NOTIFICACION ESTA REPETIDA
             */
            if(!$stmt_ntf->fetch()){
                switch($tipo_ntf){
                    /**
                     * PARA CUANDO LAS NOTIFICACIONES SON DE PETICION DE AMISTAD
                     */
                    case 1:
                        $notification = "<b>".$user_on."</b> te ha enviado una petición de amistad, ¿la aceptas?";

                        /**
                         * INSERTAMOS LA NOTIFICACION EN LA TABLA DE NOTIFICACIONES DEL USUARIO
                         * AL QUE HEMOS ENVIADO LA PETICION DE AMISTAD
                         */
                        $insert = "
                            INSERT INTO notification 
                                (notification, tipo_notification, id_emisor, id_receptor)
                                    VALUES
                                (:notification, :tipo_notification, :id_emisor, :id_receptor)
                        ";
                        $stmt = $conn->prepare($insert);
                        $stmt->bindParam(':notification', $notification);
                        $stmt->bindParam(':tipo_notification', $tipo_ntf);
                        $stmt->bindParam(':id_emisor', $id_on);
                        $stmt->bindParam(':id_receptor', $id_user);
                        // EXECUTE
                        $stmt->execute();
                    break;

                    /**
                     * PARA PEDIR INGRESOS A GRUPOS DEL INDEX.PHP
                     */
                    case 2:
                        /**
                         * REVISAMOS QUE EL USUARIO NO ESTE YA EN EL GRUPO, NO TIENE SENTIDO QUE SE PUEDA
                         * ENVIAR UNA PETICION PARA UN GRUPO EN EL QUE YA ESTAS
                         */
                        $select = "SELECT * FROM grupo_user WHERE id_grupo = :id_grupo";
                        $stmt = $conn->prepare($select);
                        $stmt->bindParam(':id_grupo', $id_grupo);
                        $stmt->execute();

                        while (($grupo_user = $stmt->fetch()) && $problema != "recluta"){
                            if($id_on == $grupo_user['id_user']){
                                $problema = "recluta";
                            }
                        }

                        if($problema != "recluta") {
                            /**
                             * OBTENEMOS TODOS LOS DATOS DEL GRUPO
                             */
                            $select_grupo = "
                                SELECT * FROM grupo
                                    WHERE id = :id
                            ";
                            $stmt_grupo = $conn->prepare($select_grupo);
                            $stmt_grupo->bindParam(':id', $id_grupo);
                            $stmt_grupo->execute();
                            $grupo = $stmt_grupo->fetch();

                            $notification = "<b>".$user_on."</b> quiere acceder a tu grupo <b>".$grupo['name']."</b>, ¿alistas a un nuevo recluta?";

                            /**
                             * INSERTAMOS LA NOTIFICACION EN LA TABLA DE NOTIFICACIONES DEL USUARIO
                             * AL QUE HEMOS ENVIADO LA PETICION DE AMISTAD
                             */
                            $insert = "
                                INSERT INTO notification 
                                    (notification, tipo_notification, id_grupo, id_emisor, id_receptor)
                                        VALUES
                                    (:notification, :tipo_notification, :id_grupo, :id_emisor, :id_receptor)
                            ";
                            $stmt = $conn->prepare($insert);
                            $stmt->bindParam(':notification', $notification);
                            $stmt->bindParam(':tipo_notification', $tipo_ntf);
                            $stmt->bindParam(':id_grupo', $id_grupo);
                            $stmt->bindParam(':id_emisor', $id_on);
                            $stmt->bindParam(':id_receptor', $id_user);
                            $stmt->execute();
                        }
                    break;
                    
                    /**
                     * CUANDO LA INVITACION ES ENVIADA DEL GRUPO AL USUARIO PARA UNIRSE AL GRUPO
                     */
                    case 3:
                        /**
                         * OBTENEMOS TODOS LOS DATOS DEL GRUPO
                         */
                        $select_grupo = "
                            SELECT * FROM grupo
                                WHERE id = :id
                        ";
                        $stmt_grupo = $conn->prepare($select_grupo);
                        $stmt_grupo->bindParam(':id', $id_grupo);
                        $stmt_grupo->execute();
                        $grupo = $stmt_grupo->fetch();

                        $notification = "<b>".$user_on."</b> te ha invitado al grupo <b>".$grupo['name']."</b>, ¿quieres unirte?";

                        /**
                         * INSERTAMOS LA NOTIFICACION EN LA TABLA DE NOTIFICACIONES DEL USUARIO
                         * AL QUE HEMOS ENVIADO LA PETICION DE AMISTAD
                         */
                        $insert = "
                            INSERT INTO notification 
                                (notification, tipo_notification, id_grupo, id_emisor, id_receptor)
                                    VALUES
                                (:notification, :tipo_notification, :id_grupo, :id_emisor, :id_receptor)
                        ";
                        $stmt = $conn->prepare($insert);
                        $stmt->bindParam(':notification', $notification);
                        $stmt->bindParam(':tipo_notification', $tipo_ntf);
                        $stmt->bindParam(':id_grupo', $id_grupo);
                        $stmt->bindParam(':id_emisor', $id_on);
                        $stmt->bindParam(':id_receptor', $id_user);
                        $stmt->execute();
                    break;

                    /**
                     * NOTIFICACION PARA CUANDO ALGUIEN TE EXCRIBE UN COMENTARIO EN UNO DE TUS POST
                     */
                    case 4:
                        $notification = "<b>".$user_on."</b> ha comentado en tu post \"<b>".$dato_post['topic_name']."</b>\"";

                        /**
                         * INSERTAMOS LA NOTIFICACION EN LA TABLA DE NOTIFICACIONES DEL USUARIO
                         * AL QUE HEMOS ENVIADO LA PETICION DE AMISTAD
                         */
                        $insert = "
                            INSERT INTO notification 
                                (notification, tipo_notification, id_emisor, id_receptor, id_topic)
                                    VALUES
                                (:notification, :tipo_notification, :id_emisor, :id_receptor, :id_topic)
                        ";
                        $stmt = $conn->prepare($insert);
                        $stmt->bindParam(':notification', $notification);
                        $stmt->bindParam(':tipo_notification', $tipo_ntf);
                        $stmt->bindParam(':id_emisor', $id_on);
                        $stmt->bindParam(':id_receptor', $id_user);
                        $stmt->bindParam(':id_topic', $id_topic);
                        // EXECUTE
                        $stmt->execute();
                    break;

                    /**
                     * UNA NOTIFICACION PARA CUANDO ERES EXPULSADO DEL GRUPO
                     * ADEMAS DE ECHARTE DEL GRUPO DE VERDAD JEJE
                     */
                    case 5:
                        $id_user = $_POST['id_user'];
                        $id_grupo = $_POST['id_grupo'];
                        /**
                         * OBTENEMOS TODOS LOS DATOS DEL GRUPO
                         */
                        $select_grupo = "
                            SELECT * FROM grupo
                                WHERE id = :id
                        ";
                        $stmt_grupo = $conn->prepare($select_grupo);
                        $stmt_grupo->bindParam(':id', $id_grupo);
                        $stmt_grupo->execute();
                        $grupo = $stmt_grupo->fetch();

                        /**
                         * BORRAMOS SU REGISTRO DE LA TABLA GRUPO_USER
                         */
                        $delete = "DELETE FROM grupo_user WHERE id_user = :id_user AND id_grupo = :id_grupo";
                        $stmt_delete = $conn->prepare($delete);
                        $stmt_delete->bindParam(':id_user', $id_user);
                        $stmt_delete->bindParam(':id_grupo', $id_grupo);
                        $stmt_delete->execute();

                        /**
                         * ENVIAMOS AL USUARIO LA NOTIFICACION DE EXPULSION
                         */
                        $notification = "Uups, te dieron la patada... Has sido expulsado del grupo <b>".$grupo['name']."</b>";

                        $insert = "
                            INSERT INTO notification 
                                (notification, tipo_notification, id_grupo, id_emisor, id_receptor)
                                    VALUES
                                (:notification, :tipo_notification, :id_grupo, :id_emisor, :id_receptor)
                        ";
                        $stmt = $conn->prepare($insert);
                        $stmt->bindParam(':notification', $notification);
                        $stmt->bindParam(':tipo_notification', $tipo_ntf);
                        $stmt->bindParam(':id_grupo', $id_grupo);
                        $stmt->bindParam(':id_emisor', $id_on);
                        $stmt->bindParam(':id_receptor', $id_user);
                        $stmt->execute();

                        /**
                         * OBTENEMOS EL NUMERO TOTAL DE INTEGRANTES EN EL GRUPO
                         * PARA RESTAR 1
                         */
                        $select_grupo = "SELECT * FROM grupo WHERE id = :id_grupo";
                        $stmt_grupo = $conn->prepare($select_grupo);
                        $stmt_grupo->bindParam(':id_grupo', $id_grupo);
                        $stmt_grupo->execute();
                        $datoGrupo = $stmt_grupo->fetch();

                        $num_user = $datoGrupo['num_user'];
                        $num_user--;

                        $update_num = "
                            UPDATE grupo SET
                                num_user = :num_user
                            WHERE
                                id = :id_grupo;
                        ";
                        $stmt_num = $conn->prepare($update_num);
                        $stmt_num->bindParam(':num_user', $num_user);
                        $stmt_num->bindParam(':id_grupo', $id_grupo);
                        $stmt_num->execute();
                    break;
                }
            } else {
                $problema = "repetido";
            }
            
            echo $problema;
            
        break;
    }
?>