<?php
    require "../config.php";

    $funcion = $_POST['funcion'];
    $id_notification = $_POST['id_notification'];

    if($funcion == "aceptar") {

        $id_on = $_POST['id_on'];
        $otra_id  = $_POST['otra_id'];
        $tipo_notificacion = $_POST['tipo_notification'];

        switch($tipo_notificacion){

            /**--------------------------------------------------------------------------------------
             * 
             * ACEPTAR PETICIONES DE AMISTAD
             */
            case 1:
                $insert_amigo = "INSERT INTO friend (id_user, id_other_user) VALUES (:otra_id, :id_on)";
                $stmt_amigo = $conn->prepare($insert_amigo);
                $stmt_amigo->bindParam(':otra_id', $otra_id);
                $stmt_amigo->bindParam(':id_on', $id_on);
                $stmt_amigo->execute();
            break;

            /**--------------------------------------------------------------------------------------
             * 
             *  ACEPTAR COMO ADMIN DE [GRUPO] EL ACCESO DE UN USUARIO QUE HA PEDIDO ENTRAR
             */
            case 2:
                $id_grupo = $_POST['id_grupo'];
                
                /**
                 * OBTENEMOS EL NUMERO TOTAL DE INTEGRANTES EN EL GRUPO
                 */
                $select_grupo = "SELECT * FROM grupo WHERE id = :id_grupo";
                $stmt_grupo = $conn->prepare($select_grupo);
                $stmt_grupo->bindParam(':id_grupo', $id_grupo);
                $stmt_grupo->execute();
                $datoGrupo = $stmt_grupo->fetch();

                if($datoGrupo['num_user'] != $datoGrupo['num_max']) {
                    $insert_user = "INSERT INTO grupo_user (id_grupo, id_user, admin) VALUES (:id_grupo, :otra_id, 0)";
                    $stmt_user = $conn->prepare($insert_user);
                    $stmt_user->bindParam(':id_grupo', $id_grupo);
                    $stmt_user->bindParam(':otra_id', $otra_id);
                    $stmt_user->execute();

                    $num_user = $datoGrupo['num_user'];
                    $num_user++;

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
                } else {
                    //GRUPO LLENO
                    $borrar = false;
                    echo "grupo_lleno";
                }
            break;

            /**--------------------------------------------------------------------------------------
             * 
             * ACEPTAR COMO USUARIO UNA PETICION PARA UNIRTE A UN CHAT
             */
            case 3:
                /**
                 * OBTENEMOS EL NUMERO TOTAL DE INTEGRANTES EN EL GRUPO
                 */
                $select_grupo = "SELECT * FROM grupo WHERE id = :otra_id";
                $stmt_grupo = $conn->prepare($select_grupo);
                $stmt_grupo->bindParam(':otra_id', $otra_id);
                $stmt_grupo->execute();
                $datoGrupo = $stmt_grupo->fetch();

                if($datoGrupo['num_user'] != $datoGrupo['num_max']) {
                    $insert_user = "INSERT INTO grupo_user (id_grupo, id_user, admin) VALUES (:otra_id, :id_on, 0)";
                    $stmt_user = $conn->prepare($insert_user);
                    $stmt_user->bindParam(':otra_id', $otra_id);
                    $stmt_user->bindParam(':id_on', $id_on);
                    $stmt_user->execute();

                    $num_user = $datoGrupo['num_user'];
                    $num_user++;

                    $update_num = "
                        UPDATE grupo SET
                            num_user = :num_user
                        WHERE
                            id = :otra_id;
                    ";
                    $stmt_num = $conn->prepare($update_num);
                    $stmt_num->bindParam(':num_user', $num_user);
                    $stmt_num->bindParam(':otra_id', $otra_id);
                    $stmt_num->execute();
                } else {
                    //GRUPO LLENO
                    $borrar = false;
                    echo "grupo_lleno";
                }
            break;
        }
    }

    //TRAS RECHAZAR O ACEPTAR LA INVITACION SE BORRA LA NOTIFICACION DE LA TABLA
    $delete_ntf = "DELETE FROM notification WHERE id = :id_notification";
    $stmt_ntf = $conn->prepare($delete_ntf);
    $stmt_ntf->bindParam(':id_notification', $id_notification);
    $stmt_ntf->execute();
?>