<?php
    require "../../../config.php";

    switch($_SERVER['REQUEST_METHOD']){

        case "POST":
            // TODAS LAS VARIBALES A UTILIZAR
            $img_grupo = $_POST['img_grupo'];
            $nombre_grupo = $_POST['nombre_grupo'];
            $descripcion_grupo = $_POST['descripcion_grupo'];
            $num_grupo = $_POST['num_grupo'];
            $id_on = $_POST['id_on'];
            $repetido = false;

            /**
             * ANTES DE CREAR EL GRUPO E INSERTARLO EN LA TABLA SE COMPRUEBA
             * QUE EL NOMBRE DEL GRUPO NO VAYA A SER IGUAL A OTRO QUE YA HAYA
             * CREADO CON ANTERIORIDAD
             */
            $select = "SELECT * FROM grupo";
            $stmt = $conn->prepare($select);
            $stmt->execute();

            while($dato = $stmt->fetch()){
                if(strcasecmp($nombre_grupo, $dato['name']) == 0){
                    $repetido = true;
                }
            }

            if(!$repetido){
                /**
                 * PRIMERO SE AÑADE EL GRUPO A LA TABLA GRUPO
                 */
                $insert = "
                    INSERT INTO grupo
                        (name, image, description, num_user, num_max)
                    VALUES
                        (:nombre_grupo, :img_grupo, :descripcion_grupo, 1, :num_grupo)
                ";

                $stmt = $conn->prepare($insert);
                $stmt->bindParam(':nombre_grupo', $nombre_grupo);
                $stmt->bindParam(':img_grupo', $img_grupo);
                $stmt->bindParam(':descripcion_grupo', $descripcion_grupo);
                $stmt->bindParam(':num_grupo', $num_grupo);
                $stmt->execute();

                /**
                 * PEQUEÑA QUERY PARA OBTENER LA ID DEL GRUPO QUE SE ACABA DE CREAR
                 */
                $select = "SELECT * FROM grupo WHERE name = :nombre_grupo";
                $stmt = $conn->prepare($select);
                $stmt->bindParam(':nombre_grupo', $nombre_grupo);
                $stmt->execute();
                $dato = $stmt->fetch();
                $id_grupo = $dato['id'];

                /**
                 * SE AÑADE EL USUARIO AL GRUPO CREADO
                 */
                $insert_user =  "
                    INSERT INTO grupo_user
                        (id_grupo, id_user, admin)
                    VALUES
                        (:id_grupo, :id_on, true)
                ";

                $stmt = $conn->prepare($insert_user);
                $stmt->bindParam(':id_grupo', $id_grupo);
                $stmt->bindParam(':id_on', $id_on);
                $stmt->execute();
                $script = "
                    <script>
                        Swal.fire({
                            text: 'El grupo ha sido creado',
                            icon: 'success'
                        }).then((result) => {
                            window.location.replace('chat.php');
                        });
                    </script>
                ";
            } else {
                $script = "
                    <script>
                        Swal.fire({
                            text: 'El nombre de grupo ya esta en uso',
                            icon: 'error'
                        });
                    </script>
                ";
            }
            echo json_encode($script, JSON_NUMERIC_CHECK);
        break;

        case "PUT":
            //OBTENGO LOS PARAMETROS QUE HE PASADO MEDIANTE PUT EN LA VARIABLE QUE HE LLAMADO $_PUT
            parse_str(file_get_contents("php://input"), $_PUT);

            $nombre_grupo_anterior = $_PUT['nombre_grupo_anterior'];
            $nombre_grupo = $_PUT['nombre_grupo'];
            $descripcion_grupo = $_PUT['descripcion_grupo'];

            if(isset($_PUT['img_grupo'])){
                $img_grupo = $_PUT['img_grupo'];

                $update = "
                    UPDATE grupo SET
                        name = :name_new, image = :image_new, description = :description_new
                    WHERE
                        id = (SELECT id FROM grupo WHERE name = :name_old);
                ";
                $stmt = $conn->prepare($update);
                $stmt->bindParam(':image_new', $img_grupo);
            } else{
                $update = "
                    UPDATE grupo SET
                        name = :name_new, description = :description_new
                    WHERE
                        id = (SELECT id FROM grupo WHERE name = :name_old);
                ";
                $stmt = $conn->prepare($update);
            }
            $stmt->bindParam(':name_new', $nombre_grupo);
            $stmt->bindParam(':description_new', $descripcion_grupo);
            $stmt->bindParam(':name_old', $nombre_grupo_anterior);
            $stmt->execute();

        break;

        case "DELETE":
            $nombre_grupo = $_GET['nombre_grupo'];

            $select_grupo = "
                    SELECT * FROM grupo WHERE
                        name = :name
                ";
            $stmt_grupo = $conn->prepare($select_grupo);
            $stmt_grupo->bindParam(':name', $nombre_grupo);
            $stmt_grupo->execute();
            $grupo = $stmt_grupo->fetch();

            /**
             * UNA VEZ TENEMOS LA ID BORRAMOS TODO LO RELACIONADO CON LA ID DEL GRUPO
             * 
             * TENDREMOS QUE ELIMINAR REGISTROS DE 3 TABLAS - grupo - grupo_user - grupo_message
             */
            
            //DELETE grupo
            $delete_grupo = "DELETE FROM grupo WHERE id = :id";
            $stmt_delete = $conn->prepare($delete_grupo);
            $stmt_delete->bindParam(':id', $grupo['id']);
            $stmt_delete->execute();
            
            //DELETE grupo_user
            $delete_grupo_user = "DELETE FROM grupo_user WHERE id_grupo = :id";
            $stmt_delete = $conn->prepare($delete_grupo_user);
            $stmt_delete->bindParam(':id', $grupo['id']);
            $stmt_delete->execute();
            
            //DELETE grupo_message
            $delete_grupo_message = "DELETE FROM grupo_message WHERE id_grupo = :id";
            $stmt_delete = $conn->prepare($delete_grupo_message);
            $stmt_delete->bindParam(':id', $grupo['id']);
            $stmt_delete->execute();
        break;
    }
?>