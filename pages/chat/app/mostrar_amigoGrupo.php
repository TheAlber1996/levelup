<?php
    //SESION INICIA?
    session_start();
    //CONFIGURACION DE LA BASE DE DATOS
    require "../../../config.php";

    //VARIABLES
    $busqueda_amigos = true;
    $busqueda = true;
    $tipo = $_POST['tipo'];
    
    $user_on = $_SESSION['nombre_logueado'];
    $id_on = $_SESSION['id_logueado'];

    // PROBAR RESPUESTA CON POSTMAN
    //$user_on = "pato";
    //$id_on = 8;

    switch($tipo){
        //SELECT
        case "grupo":
            mostrarGrupos();
        break;

        case "amigo":
            mostrarAmigos();
        break;

        case "usuario":
            mostrarUsuario($id_on);
        break;
    }

/** -----------------------------------------------------------------------------------------------------------------
 * 
 * FUNCIONES PARA LOS METODOS DE BUSCAR AMIGOS O GRUPOS
 * 
 *  -----------------------------------------------------------------------------------------------------------------
 */ 
    /** -------------------------------------------------------------------------------------------------------------
     * METODO PARA MOSTRAR A TUS AMIGOS O BUSCAR A CUALQUIER USUARIO
     */
    function mostrarAmigos(){
        require "../../../config.php"; // HAY QUE PONERLO EN TODAS LAS FUNCIONES

        $mostrar_amigos = "";

        // SI SE HA INTRODUCIDO ALGUN VALOR EN LA CAJA DE BUSQUEDA DE AMIGOS
        if(isset($_POST['valor_busqueda'])){
            /**
             * COMO $_POST['valor_busqueda'] EXISTE GUARDAMOS SU VALOR EN UNA VARIABLE 
             * PARA MEJOR MANEJO DE SU VALOR
             * 
             * ADEMAS YA DE PASO CONCATENAMOS LOS % PARA REALIZAR LA BUSQUEDA PERSONALIZA
             */ 
            $valor_busqueda = "%".$_POST['valor_busqueda']."%";
            
            /**
             * PRIMERO REALIZAMOS EL FILTRO CON NUESTROS AMIGOS
             */
            $select_amigo = "
                SELECT * FROM user WHERE 
                    usuario LIKE :valor_busqueda
                        AND
                    (id IN (SELECT id_other_user FROM friend WHERE id_user = :id_on)
                        OR
                    id IN (SELECT id_user FROM friend WHERE id_other_user = :id_on))
            ";
            $stmt_amigo = $conn->prepare($select_amigo);
            $stmt_amigo->bindParam(':valor_busqueda', $valor_busqueda);
            $stmt_amigo->bindParam(':id_on', $GLOBALS['id_on']);
            $stmt_amigo->execute();

            // COMPRUEBA SI LA QUERY HA RECIBIDO ALGUNA RESPUESTA
            if($stmt_amigo->rowCount()>0) {
                // BUCLE QUE SE REPITE MIENTRAS HAYA REGISTROS
                while($friend = $stmt_amigo->fetch()){
                    $mostrar_amigos .= " <a class='user' href='./chat.php?chat=".$friend['usuario']."'>
                                            <img class='img_amigo' src='/pagina/proyecto/".$friend['profile_pic']."'>
                                            <label>".$friend['usuario']."</label>";

                    if($friend['online']){
                        $mostrar_amigos .= "<img class='img_on' src='..\..\img\on.png'>";
                    } else{
                        $mostrar_amigos .= "<img class='img_on' src='..\..\img\off.png'>";
                    }
                    $mostrar_amigos .= "\n</a>";
                }
            }
            $select_no_amigo = "
                SELECT * FROM user 
                    WHERE usuario LIKE :valor_busqueda
                        AND NOT
                    (id IN (SELECT id_other_user FROM friend WHERE id_user = :id_on)
                        OR
                    id IN (SELECT id_user FROM friend WHERE id_other_user = :id_on))
            ";
            $stmt_no_amigo = $conn->prepare($select_no_amigo);
            $stmt_no_amigo->bindParam(':valor_busqueda', $valor_busqueda);
            $stmt_no_amigo->bindParam(':id_on', $GLOBALS['id_on']);
            $stmt_no_amigo->execute();

            // COMPRUEBA SI LA QUERY HA RECIBIDO ALGUNA RESPUESTA
            if($stmt_no_amigo->rowCount()>0) {
                // BUCLE QUE SE REPITE MIENTRAS HAYA REGISTROS
                while($no_friend = $stmt_no_amigo->fetch()){
                    if($GLOBALS['id_on']!=$no_friend['id']){
                        $mostrar_amigos .= "<button class='user bt_mas' onclick='agregar(".$no_friend['id'].");'>
                                            <img class='img_amigo' src='/pagina/proyecto".$no_friend['profile_pic']."'>
                                            <label>".$no_friend['usuario']."</label>
                                            <img class='img_mas' src='..\..\img\agregar.png'>
                                        </button>";
                    }       
                }
            } 
            echo $mostrar_amigos;

        /**
         * ELSE - MUESTRA A TUS AMIGOS CUANDO NO SE INTROUDUCE NADA POR LA CAJA DE BUSQUEDA
         */
        } else { 
            $select_amigo = "
                SELECT * FROM user
                    WHERE id IN (SELECT id_other_user FROM friend WHERE id_user = :id_on)
                        OR
                    id IN (SELECT id_user FROM friend WHERE id_other_user = :id_on)
            ";
            $stmt_amigo = $conn->prepare($select_amigo);
            $stmt_amigo->bindParam(':id_on', $GLOBALS['id_on']);
            $stmt_amigo->execute();

            while($friend = $stmt_amigo->fetch()){
                $mostrar_amigos = " <a class='user' href='./chat.php?chat=".$friend['usuario']."'>
                                        <img class='img_amigo' src='/pagina/proyecto/".$friend['profile_pic']."'>
                                        <label>".$friend['usuario']."</label>";

                if($friend['online']){
                    $mostrar_amigos .= "<img class='img_on' src='..\..\img\on.png'>";
                } else{
                    $mostrar_amigos .= "<img class='img_on' src='..\..\img\off.png'>";
                }
                echo $mostrar_amigos."</a>";
            }
        }
    }

    /** -------------------------------------------------------------------------------------------------------------
     * METODO PARA MOSTRAR O BUSCAR LOS GRUPOS A LOS QUE PERTENEZCAS
     */
    function mostrarGrupos(){
        require "../../../config.php"; // HAY QUE PONERLO EN TODAS LAS FUNCIONES

        $mostrar_grupos = "";

        // SI SE HA INTRODUCIDO ALGUN VALOR EN LA CAJA DE BUSQUEDA DE GRUPOS
        if(isset($_POST['valor_busqueda'])){
            /**
             * COMO $_POST['valor_busqueda'] EXISTE GUARDAMOS SU VALOR EN UNA VARIABLE 
             * PARA MEJOR MANEJO DE SU VALOR
             * 
             * ADEMAS YA DE PASO CONCATENAMOS LOS % PARA REALIZAR LA BUSQUEDA PERSONALIZA
             */ 
            $valor_busqueda = "%".$_POST['valor_busqueda']."%";

            $select_grupo = "
                SELECT * FROM grupo 
                    WHERE name LIKE :valor_busqueda 
                        AND 
                    id IN (SELECT id_grupo FROM grupo_user WHERE id_user = :id_on)
            ";
            $stmt_grupo = $conn->prepare($select_grupo);
            $stmt_grupo->bindParam(':valor_busqueda', $valor_busqueda);
            $stmt_grupo->bindParam(':id_on', $GLOBALS['id_on']);
            $stmt_grupo->execute();

        /**
         * ELSE - MUESTRA TUS GRUPOS CUANDO NO SE INTROUDUCE NADA POR LA CAJA DE BUSQUEDA
         */
        } else {
            $select_grupo = "
                SELECT * FROM grupo 
                    WHERE id IN (SELECT id_grupo FROM grupo_user WHERE id_user = :id_on)
            ";
            $stmt_grupo = $conn->prepare($select_grupo);
            $stmt_grupo->bindParam(':id_on', $GLOBALS['id_on']);
            $stmt_grupo->execute();
        }

        while($grupo = $stmt_grupo->fetch()){
            $mostrar_grupos = " <a class='user' href='./chat.php?grupo=".$grupo['name']."'>
                                    <img class='img_amigo' src='/pagina/proyecto/".$grupo['image']."'>
                                    <label>".$grupo['name']."</label>
                                    <span>".$grupo['num_user']."/".$grupo['num_max']."</span>";
            echo $mostrar_grupos."</a>";
        }
    }

    /** -------------------------------------------------------------------------------------------------------------
     * METODO PARA MOSTRAR USUARIOS PARA ENVIAR PETICIOCIONES PARA QUE SE UNAN AL GRUPO
     * 
     * DE POR SI MUESTRA A TODOS LOS USUARIOS PERTENECIENTES AL GRUPO
     */
    function mostrarUsuario($id_on){
        require "../../../config.php"; // HAY QUE PONERLO EN TODAS LAS FUNCIONES

        $name_grupo = $_POST['grupo'];

        //QUERY PARA OBTENER TODOS LOS DATOS DEL GRUPO
        $select_grupo = "
                SELECT * FROM grupo
                    WHERE name = :name
            ";
        $stmt_grupo = $conn->prepare($select_grupo);
        $stmt_grupo->bindParam(':name', $name_grupo);
        $stmt_grupo->execute();
        $grupo = $stmt_grupo->fetch();

        $mostrar_usuario = "";

        // SI SE HA INTRODUCIDO ALGUN VALOR EN LA CAJA DE BUSQUEDA DE GRUPOS
        if(isset($_POST['valor_busqueda'])){

            $valor_busqueda = "%".$_POST['valor_busqueda']."%";

            $select_user = "
                SELECT * FROM user 
                    WHERE 
                        usuario LIKE :valor_busqueda 
                    AND NOT 
                        usuario = :user_on
                    AND NOT 
                        id IN (SELECT id_user FROM grupo_user WHERE id_grupo = :id_grupo)
            ";

            $stmt_user = $conn->prepare($select_user);
            $stmt_user->bindParam(':valor_busqueda', $valor_busqueda);
            $stmt_user->bindParam(':user_on', $GLOBALS['user_on']);
            $stmt_user->bindParam(':id_grupo', $grupo['id']);
            $stmt_user->execute();

            while($user = $stmt_user->fetch()){
                //QUERY PARA OBTENER TODOS LOS DATOS DE LOS USUARIOS A MOSTRAR

                $mostrar_usuario .= "<button class='user bt_mas' onclick='agregarGrupo(".$user['id'].", ".$grupo['id'].");'>
                                            <img class='img_amigo' src='/pagina/proyecto".$user['profile_pic']."'>
                                            <label>".$user['usuario']."</label>
                                            <img class='img_mas' src='..\..\img\agregar.png'>
                                        </button>";
            }
        } else {
            $select_user = "
                SELECT * FROM grupo_user
                    WHERE id_grupo = :id_grupo
            ";
            $stmt_user = $conn->prepare($select_user);
            $stmt_user->bindParam(':id_grupo', $grupo['id']);
            $stmt_user->execute();

            while($user = $stmt_user->fetch()){
                //QUERY PARA OBTENER TODOS LOS DATOS DE LOS USUARIOS A MOSTRAR

                $select_userDatos = "SELECT * FROM user WHERE id = :id_user";
                $stmt_userDatos = $conn->prepare($select_userDatos);
                $stmt_userDatos->bindParam(':id_user', $user['id_user']);
                $stmt_userDatos->execute();
                $userDato = $stmt_userDatos->fetch();

                /**
                 * COMPROBAMOS SI EL USUARIO DEL GRUPO ES AMIGO DEL USUARIO CONECTADO
                 */
                $select_amigo = "
                    SELECT * FROM friend 
                    WHERE 
                        (id_user = :id_user OR id_other_user = :id_user)
                    AND
                        (id_user = :id_on OR id_other_user = :id_on)
                ";
                $stmt_amigo = $conn->prepare($select_amigo);
                $stmt_amigo->bindParam(':id_on', $id_on);
                $stmt_amigo->bindParam(':id_user', $userDato['id']);
                $stmt_amigo->execute();

                if(!$stmt_amigo->fetch()){
                    $mostrar_usuario .= "<button class='user bt_mas' onclick='agregar(".$userDato['id'].");'>
                                            <img class='img_amigo' src='/pagina/proyecto".$userDato['profile_pic']."'>
                                            <label>".$userDato['usuario']."</label>
                                            <img class='img_mas' src='..\..\img\agregar.png'>";

                                            if(@$user_on['admin'] && ($userDato['id']!=$GLOBALS['id_on'])){
                                                $mostrar_usuario .= "<button2 class='delete' onclick='eliminar(".$userDato['id'].",".$grupo['id'].");'>
                                                                        <img src='..\..\img\cross.png'>
                                                                    </button2>";
                                            }

                    $mostrar_usuario .= "</button>";
                }else{
                    
                    $mostrar_usuario .= " <div class='user'>
                                            <img class='img_amigo' src='/pagina/proyecto/".$userDato['profile_pic']."'>
                                            <label>".$userDato['usuario']."</label>";

                    if($userDato['online']){
                        $mostrar_usuario .= "<img class='img_on' src='..\..\img\on.png'>";
                    } else{
                        $mostrar_usuario .= "<img class='img_on' src='..\..\img\off.png'>";
                    }

                    /**
                     * MINI QUERY PARA SABER SI EL USUARIO CONECTADO ES EL ADMIN DEL GRUPO PARA PODER
                     * EXPULSAR A CUALQUIER USUARIO SI QUISIERA
                     */
                    $select_admin = "
                        SELECT * FROM grupo_user WHERE id_grupo = :id_grupo AND id_user = :id_on
                    ";
                    $stmt_admin = $conn->prepare($select_admin);
                    $stmt_admin->bindParam(':id_grupo', $grupo['id']);
                    $stmt_admin->bindParam(':id_on', $GLOBALS['id_on']);
                    $stmt_admin->execute();
                    $user_on = $stmt_admin->fetch();

                    if($user_on['admin'] && ($userDato['id']!=$GLOBALS['id_on'])){
                        $mostrar_usuario .= "<button class='delete' onclick='eliminar(".$userDato['id'].",".$grupo['id'].");'>
                                                <img src='..\..\img\cross.png'>
                                            </button>";
                    }

                    $mostrar_usuario .= "</div>";
                } 
            }
        }


        echo $mostrar_usuario;
    }
?>
