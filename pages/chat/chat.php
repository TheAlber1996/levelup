<?php
    session_start();

    require_once '..\..\lib\Requests\library\Requests.php';
    Requests::register_autoloader();
    require "../../config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <!-- CSS -->
    <!-- <link rel="stylesheet" type="text/css" href="../../lib/bootstrap-5.0.0/css/bootstrap.css"> -->
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">
    <link rel="stylesheet" type="text/css" href="../../lib/bootstrap-5.0.0/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="./src/style.css">
    
    <!-- JavaScript -->
    <script src="../../lib/jquery.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <script src="app/peticion.js"></script>
    <script src="../../lib/bootstrap-5.0.0/js/bootstrap.bundle.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="/pagina/proyecto/pages/chat/app/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

    <!-- ICONO -->
    <link rel="icon" type="image/png" href="../../img/ico.png">
    <title>Level UP - Chat</title>
</head>

<body>
    <!--Navbar-->
    <?php include "../../nav.php"; ?>

    <!-- MAIN -->
    <main class="main">
        <section class="general">
            <!-- BARRA DE NAVEGACION ENTRE GRUPOS Y AMIGOS -->
            <nav class="conver">
                <section class="amigos">
                    <div class="titulo_amigos">
                        <h2> Amigos </h2>
                    </div>
                    <div class="div_busqueda">
                        <div class="caja">
                            <img src="../../img/search.png">
                            <input type="text" name="amigo_busqueda" id="busqueda_amigo" placeholder="Buscar amigos..."></input>
                        </div>
                    </div>
                    <div id="scroll_amigos">

                        <!-- AJAX -->
                        <!-- VISUALIZACION DE AMIGOS MEDIANTE AJAX -->
                        <!-- AJAX -->

                    </div>
                </section>

                <section class='grupos'>
                    <div class="titulo_grupos">
                        <h2> Grupos </h2>
                        <a href="./chat.php?grupo=create" class="div_grupo">
                            <img class="menu" src="../../img/menu.png">
                        </a>
                    </div>
                    <div class="div_busqueda">
                        <div class="caja">
                            <img src="../../img/search.png">
                            <input type="text" name="grupo_busqueda" id="busqueda_grupos" placeholder="Buscar grupos..."></input>
                        </div>
                    </div>
                    <div id="scroll_grupos">

                        <!-- AJAX -->
                        <!-- VISUALIZACION DE GRUPOS MEDIANTE AJAX -->
                        <!-- AJAX -->

                    </div>
                </section>
            </nav>

            <div class="chat">
                <!-- DIV DONDE SE VISUALIZARAN NOMBRE, FOTO DE GRUPO/USUARIO -->
                <div name='info_user' class="datos">
                    <?php
                        $datos = "";
                        //BOTON LIGADO CON EL NUMERO DE ID DE CADA USUARIO
                        
                        if(isset($_GET['chat'])){

                            /**
                             * PEQUEÑA QUERY PARA OBTENER RAPIDAMENTE LA FOTO DEL PERFIL DE NUESTRO
                             * AMIGO PARA MOSTRARLA ARRIBA EN LA BARRA DE NAVEGACION DE CHAT
                             */
                            $select_datosAmigos = "SELECT * FROM user WHERE usuario = :friend";
                            $stmt_friend = $conn->prepare($select_datosAmigos);
                            $stmt_friend->bindParam(':friend', $_GET['chat']);
                            // EXECUTE
                            $stmt_friend->execute();
                            $friend = $stmt_friend->fetch();

                            $datos .=   "<img class='imgperfil' src='/pagina/proyecto".$friend['profile_pic']."'>
                                        <h2 class='nombre'>".$_GET['chat']."</h2>";
                        }

                        if((isset($_GET['grupo']) && $_GET['grupo'] != "create")){

                            /**
                             * PEQUEÑA QUERY PARA OBTENER RAPIDAMENTE LA FOTO DEL PERFIL DEL
                             * GRUPO PARA MOSTRARLA ARRIBA EN LA BARRA DE NAVEGACION DE CHAT
                             */
                            require "../../config.php";

                            $select_datosGrupo = "SELECT * FROM grupo WHERE name = :name";
                            $stmt_grupo = $conn->prepare($select_datosGrupo);
                            if(isset($_GET['grupo'])){
                                $stmt_grupo->bindParam(':name', $_GET['grupo']);
                            } elseif(isset($_GET['info'])) {
                                $stmt_grupo->bindParam(':name', $_GET['info']);
                            }
                            
                            // EXECUTE
                            $stmt_grupo->execute();
                            $grupo = $stmt_grupo->fetch();

                            $datos .=   "<img class='imgperfil' src='/pagina/proyecto".$grupo['image']."'>
                                        <h2 class='nombre'>".$grupo['name']."</h2>
                                        <a href='http://localhost/Pagina/proyecto/pages/chat/chat.php?info=".$grupo['name']."'>
                                            <img class='img_info' src='../../img/info.png'>
                                        </a>";
                        }

                        if(isset($_GET['info'])){
                            $datos .=   "<img class='imgperfil' src='../../img/info.png'>
                                        <label class='nombre'> Info. del grupo </label>
                                        <a href='http://localhost/Pagina/proyecto/pages/chat/chat.php?grupo=".$_GET['info']."'>
                                            <img class='img_info' src='../../img/cross.png'>
                                        </a>";
                        }

                        echo $datos;
                    ?>
                </div>

                <!-- DIV DONDE SE VISUALIZARAN LOS MENSAJES ENVIADOS Y RECIBIDOS -->
                <div class="scroll" id="scroll">
                    <?php 
                        if(isset($_GET['grupo']) && ($_GET['grupo'] == "create")){
                    ?>
                        <div class="create">
                            <form method="post" enctype="multipart/form-data">
                                <div id="previewChat"></div>
                                <div class="create_img_grupo">
                                    <label for="imgGrupo"> Subir imagen </label>
                                    <input name="imgGrupo" id="imgGrupo" type="file">
                                </div>
                                <form method="post">
                                    <div class="create_nombre"> 
                                        <label class="label"> Nombre </label>
                                        <?php
                                            if(isset($_POST['bt_create'])){
                                        ?>
                                                <input type="text" name='nombre_grupo' value="<?php echo trim($_POST['nombre_grupo']); ?>">
                                        <?php
                                            } else {
                                        ?>
                                                <input type="text" name='nombre_grupo' maxlength="15">
                                        <?php
                                            }
                                        ?>
                                        
                                    </div>
                                    <div class="create_desc">  
                                        <label> Descripción </label>
                                        <?php
                                            if(isset($_POST['bt_create'])){
                                        ?>
                                                <textarea name='descripcion_grupo' maxlength="140" rows="3" cols="50"><?php echo trim($_POST['descripcion_grupo']); ?></textarea>
                                        <?php
                                            } else {
                                        ?>
                                                <textarea name='descripcion_grupo' maxlength="140" rows="3" cols="50"></textarea>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="create_num">  
                                        <label> Número </label>
                                        <input type="range" id='num_grupo' name='num_grupo' min="5" max="50" step="1" value="10">
                                        <span id="num">10</span>
                                    </div>
                                    <div class="boton"> 
                                        <input class="bt_create" type="submit" name='bt_create' value="Crear">
                                    </div>
                                </form>
                            </form>
                        </div>
                    <?php
                            if(isset($_POST['bt_create'])){
                                $file_name = $_FILES['imgGrupo']['name'];
                                $file_type = $_FILES['imgGrupo']['type'];
                                $file_size = $_FILES['imgGrupo']['size'];
                                $file_tem_loc = $_FILES['imgGrupo']['tmp_name'];
                                $file_store = "../../img_grupos/".$file_name;

                                //SE SUBE LA IMAGEN A LA CARPETA DONDE GUARDAREMOS TODAS LAS IMAGENES DE LOS GRUPOS
                                if(move_uploaded_file($file_tem_loc, $file_store)){
                                    $img_grupo = "/img_grupos/".$file_name;
                                } else {
                                    $img_grupo = "/img_grupos/default.jpg"; // FOTO PREDETERMINADA POR SI NO SE ELIGE NINGUNA IMAGEN
                                }
                                /**
                                 * TODO LO RELACIONADO CON LA IMAGEN
                                 */
                                $nombre_grupo = trim($_POST['nombre_grupo']);

                                if(strlen($nombre_grupo) <= 15 && strlen($nombre_grupo) != 0){
                                    $descripcion_grupo = trim($_POST['descripcion_grupo']);
                                    $num_grupo = trim($_POST['num_grupo']);

                                    $url = "http://localhost/Pagina/proyecto/pages/chat/app/funciones_grupo.php";
                                    $type = Requests::POST;
                                    $data = array(
                                        'img_grupo'=>$img_grupo,
                                        'nombre_grupo'=>$nombre_grupo,
                                        'descripcion_grupo'=>$descripcion_grupo,
                                        'num_grupo'=>$num_grupo,
                                        'id_on'=>$_SESSION['id_logueado']
                                    );
                                    $res = Requests::request($url, array(), $data, $type);
                                    $script = json_decode($res->body, true);
                                } else if(strlen($nombre_grupo) == 0) {
                                    $script = "
                                        <script>
                                            Swal.fire({
                                                text: 'Introduce un nombre para el grupo',
                                                icon: 'error'
                                            });
                                        </script>
                                    ";
                                }
                                echo $script;
                            }
                        } elseif(isset($_GET['info'])) {
                            $select = "SELECT * FROM grupo WHERE name = :name";
                            $stmt = $conn->prepare($select);
                            $stmt->bindParam(':name', $_GET['info']);
                            // EXECUTE
                            $stmt->execute();
                            $info = $stmt->fetch();

                                /**
                                 * MINI QUERY PARA OBTENER LOS ADMINS DEL GRUPO
                                 */
                                $select_admin = "
                                    SELECT admin FROM grupo_user 
                                        WHERE 
                                            id_user = :id_on
                                        AND
                                            id_grupo = (SELECT id FROM grupo WHERE name = :name)
                                 ";
                                $stmt_admin = $conn->prepare($select_admin);
                                $stmt_admin->bindParam(':id_on', $id_on);
                                $stmt_admin->bindParam(':name', $_GET['info']);
                                $stmt_admin->execute();
                                $admin = $stmt_admin->fetch();
                            ?>
                            <div class="info">
                                <form method="post" class='info_grupo' enctype="multipart/form-data">
                                    <div id="previewChat">
                                        <img class="imgInfo" src="/pagina/proyecto/<?php echo $info['image']; ?>">
                                    </div>
                                    <div class="cambiar_img_grupo">
                                        <label for="imgGrupo"> Subir imagen </label>
                                        <input name="imgGrupo" id="imgGrupo" type="file">
                                    </div>
                                    <form method="post" class='info_grupo'>
                                        <label for="nombre">Nombre</label>
                                        <input type="text" maxlength="15" name="nombre" value="<?php echo $info['name']; ?>">
                                        <label for="descripcion">Descripción</label>
                                        <textarea name='descripcion' maxlength="140" rows="5" cols="50"><?php echo $info['description']; ?></textarea>
                                        <?php
                                            if($admin['admin'] == 1) {
                                        ?>
                                        <input class="bt_editar" type="submit" name='bt_editar' value="Editar">
                                        <input class="bt_eliminar" type="submit" name='bt_eliminar' value="Eliminar">
                                        <?php
                                            }
                                        ?>
                                    </form>
                                </form>

                                <?php
                                    if(isset($_POST['bt_editar'])){

                                        $file_name = $_FILES['imgGrupo']['name'];
                                        $file_type = $_FILES['imgGrupo']['type'];
                                        $file_size = $_FILES['imgGrupo']['size'];
                                        $file_tem_loc = $_FILES['imgGrupo']['tmp_name'];
                                        $file_store = "../../img_grupos/".$file_name;
                                        //SE SUBE LA IMAGEN A LA CARPETA DONDE GUARDAREMOS TODAS LAS IMAGENES DE LOS GRUPOS

                                        $nombre_grupo = trim($_POST['nombre']);
                                        $descripcion_grupo = trim($_POST['descripcion']);

                                        $url = "http://localhost/Pagina/proyecto/pages/chat/app/funciones_grupo.php";
                                        $type = Requests::PUT;

                                        if(move_uploaded_file($file_tem_loc, $file_store)){
                                            $img_grupo = "/img_grupos/".$file_name;

                                            $data = array(
                                                'nombre_grupo_anterior'=>$_GET['info'],
                                                'img_grupo'=>$img_grupo,
                                                'nombre_grupo'=>$nombre_grupo,
                                                'descripcion_grupo'=>$descripcion_grupo
                                            );
                                        } else {
                                            $data = array(
                                                'nombre_grupo_anterior'=>$_GET['info'],
                                                'nombre_grupo'=>$nombre_grupo,
                                                'descripcion_grupo'=>$descripcion_grupo
                                            );
                                        }

                                        $res = Requests::request($url, array(), $data, $type);
                                        $script = json_decode($res->body, true);

                                        echo "<script>window.location.replace('chat.php?grupo=".$nombre_grupo."');</script>";
                                    }

                                    if(isset($_POST['bt_eliminar'])){
                                        $nombre_grupo = $_GET['info'];

                                        $url = "http://localhost/Pagina/proyecto/pages/chat/app/funciones_grupo.php";
                                        $type = Requests::DELETE;
                                        $data = array(
                                            'nombre_grupo'=>$nombre_grupo
                                        );
                                        $res = Requests::request($url, array(), $data, $type);

                                        echo "<script>window.location.replace('chat.php');</script>";
                                    }
                                ?>

                                <div>
                                    <?php
                                    if($admin['admin'] == 1) {
                                    ?>
                                    <div class="div_busqueda">
                                        <div class="caja">
                                            <img src="../../img/search.png">
                                            <input type="text" name="busqueda_usuario" id="busqueda_usuario" placeholder="Busca nuevos miembros..."></input>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    <div id="scroll_usuario">

                                        <script>
                                            $(document).ready(main);

                                            function main(){
                                                var valor_busquedaUsuario = $("#busqueda_usuario").val();

                                                if (valor_busquedaUsuario!=""){
                                                    obtener_usuario(valor_busquedaUsuario);
                                                }
                                                else{
                                                    obtener_usuario();
                                                }
                                            }
                                            /**
                                             * AJAX PARA LA BUSQUEDA DE NUEVOS USUARIOS A LOS GRUPOS
                                             */
                                            function obtener_usuario(valor_busqueda) {

                                                var grupo = "<?php echo $_GET['info'];?>";

                                                $.ajax({
                                                    url : 'http://localhost//Pagina/proyecto/pages/chat/app/mostrar_amigoGrupo.php',
                                                    type : 'POST',
                                                    dataType : 'html',
                                                    data : {
                                                        valor_busqueda: valor_busqueda,
                                                        grupo: grupo,
                                                        tipo: "usuario"
                                                    }
                                                })
                                                .done(function(respuesta){
                                                    $("#scroll_usuario").html(respuesta);
                                                });
                                            }
                                            //DETECTA PULSACIONES EN LAS TECLAS DONDE COINCIDA CON EL ID
                                            $(document).on('keyup', '#busqueda_usuario', function() {

                                                var valor_busqueda=$(this).val();

                                                if (valor_busqueda!=""){
                                                    obtener_usuario(valor_busqueda);
                                                } else{
                                                    obtener_usuario();
                                                }
                                            });
                                        </script>

                                    </div>
                                <div>
                            </div>
                    <?php
                        } else {
                    ?>
                    <div id="mensajes">
                        <?php
                            if(isset($_GET['chat']) || (isset($_GET['grupo']) && ($_GET['grupo'] != "create"))){
                                $id_on = $_SESSION['id_logueado'];
                                if(isset($_GET['chat'])){
                                    $name = $_GET['chat'];
                                    $chat = "amigo";
                                } else if(isset($_GET['grupo'])){
                                    $name = $_GET['grupo'];
                                    $chat = "grupo";
                                }
                        ?>
                        <!--
                        AJAX PARA LA VISUALIZACION DE LOS CHATS
                        EL SCRIPT SE ENCUENTRA EN EL MISMO FICHERO QUE EL RESTO DEL CODIGO YA
                        QUE SINO NO SE PUEDEN RECOGER LAS VARIABLES SI ESTUVIERA EN OTRO FICHERO COMO
                        PETICION.JS
                        -->
                        <script type="text/javascript">
                            $(obtener_chat());

                            //REFRESCA AUTOMATICAMENTE CADA 5 SEGUNDOS LA FUNCION OBTENER_CHAT
                            setInterval(function() {
                                obtener_chat();
                            }, 5000); //TIEMPO EN MILISEGUNDOS QUE TARDA EN REFRESCAR

                            function obtener_chat() {
                                //VARIABLES RECOGIDAS DESDE PHP
                                var id_on = '<?php echo $id_on;?>';
                                var name = '<?php echo $name;?>';
                                var chat = '<?php echo $chat;?>';

                                $.ajax({
                                    url : './app/funciones_chat.php',
                                    type : 'GET',
                                    dataType : 'html',
                                    data : {
                                        id_on: id_on,
                                        name: name,
                                        chat: chat
                                    }
                                })
                                .done(function(respuesta){

                                    var scroll = document.getElementById("scroll");

                                    if((scroll.scrollTop + scroll.clientHeight) === scroll.scrollHeight){
                                        var scrollMax = scroll.scrollHeight-500;
                                        $("#mensajes").html(respuesta);

                                        if((scroll.scrollTop + scroll.clientHeight) > scrollMax){
                                            scroll.scrollTop = scroll.scrollHeight;
                                        }
                                    } else {
                                        $("#mensajes").html(respuesta);
                                    }
                                })
                            }
                        </script>

                        <?php 
                            // ESPACIO PARA EDITAR LAS OPCIONES PARA CREAR EL GRUPO
                            } else { // SI NO HAY UNA CONVERSACION SELECCIONADA APARECE UNA IMAGENE
                                //CHAT INVENTADO PARA INICIO DEL CHAT CUANDO NO HAYA UNA CONVERSACION SELECCIONADA
                                echo "  <div class='me'>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>No te molaria tener charlas interesantes sobre videojuegos ó hacer nuevos amigos</p>
                                                <label class='hora'>11:11</label>
                                            </button>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>Me pregunto si esto seria posible 🤔</p>
                                                <label class='hora'>11:11</label>
                                            </button>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>(Claramente estamos siendo irónicos)</p>
                                                <label class='hora'>11:11</label>
                                            </button>
                                        </div>
                                        <div class='other'>
                                            <button class='caja_msj'>
                                                <p class='texto'>Como?</p>
                                                <label class='hora'>11:12</label>
                                            </button>
                                            <button class='caja_msj'>
                                                <p class='texto'>Qué?</p>
                                                <label class='hora'>11:13</label>
                                            </button>
                                            <button class='caja_msj'>
                                                <p class='texto'>Lo estabas diciendo enserio!!!???</p>
                                                <label class='hora'>11:13</label>
                                            </button>
                                        </div>
                                        <div class='me'>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>Claro jajajaja</p>
                                                <label class='hora'>11:14</label>
                                            </button>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>Quieres saber como?</p>
                                                <label class='hora'>11:14</label>
                                            </button>
                                        </div>
                                        <div class='other'>
                                            <button class='caja_msj'>
                                                <p class='texto'>Por favor 🥺🥺🥺</p>
                                                <label class='hora'>11:15</label>
                                            </button>
                                        </div>
                                        <div class='me'>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>En LevelUP todo esto es muy fácil</p>
                                                <label class='hora'>11:15</label>
                                            </button>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>Solamente tienes que unirte a grupos sobre videojuegos que te interesen y los amigos vendran solos</p>
                                                <label class='hora'>11:16</label>
                                            </button>
                                            <button class='drch caja_msj'>
                                                <p class='texto'>Porque en LevelUp reina la gente con buen rollito 😎😎</p>
                                                <label class='hora'>11:16</label>
                                            </button>
                                        </div>";
                            }
                        ?>
                    </div>
                    <?php 
                        } //CIERRE DEL ELSE PARA CREAR GRUPOS
                    ?>
                </div>
                
                <?php
                    if(isset($_GET['chat']) || (isset($_GET['grupo']) && $_GET['grupo'] != "create")) {

                        if(isset($_GET['chat'])){
                            $nombre = $_GET['chat'];
                            $tipo_chat = "amigo";
                        } else if(isset($_GET['grupo'])){
                            $nombre = $_GET['grupo'];
                            $tipo_chat = "grupo";
                        }
                ?>
                    <!-- DIV DONDE SE VISUALIZARAN LA CAJA PARA ESCRIBIR Y EL BOTON ENVIAR -->
                    <form method="post" class="escribir">
                        <div class="div">
                            <button id="abajo" class="abajo">
                                    <img src="/pagina/proyecto/img/flecha.png" />
                            </button>
                            <button class="emoji" onclick="emoji(); return false;">
                                    <img src="/pagina/proyecto/img/emoji.png" />
                            </button>
                            <!-- MENU DESPLEGABLE DE LOS EMOJIS -->
                            <div id="menuEmoji" class="menuEmoji">
                                <button onclick="escribeEmoji('😂'); return false;">😂</button>
                                <button onclick="escribeEmoji('🤔'); return false;">🤔</button>
                                <button onclick="escribeEmoji('😁'); return false;">😁</button>
                                <button onclick="escribeEmoji('😘'); return false;">😘</button>
                                <button onclick="escribeEmoji('🥺'); return false;">🥺</button>
                                <button onclick="escribeEmoji('🥳'); return false;">🥳</button>
                                <button onclick="escribeEmoji('😱'); return false;">😱</button>
                                <button onclick="escribeEmoji('🤯'); return false;">🤯</button>
                                <button onclick="escribeEmoji('🥱'); return false;">🥱</button>
                                <button onclick="escribeEmoji('🥴'); return false;">🥴</button>
                                <button onclick="escribeEmoji('🤐'); return false;">🤐</button>
                                <button onclick="escribeEmoji('😭'); return false;">😭</button>
                                <button onclick="escribeEmoji('😤'); return false;">😤</button>
                                <button onclick="escribeEmoji('🤤'); return false;">🤤</button>
                                <button onclick="escribeEmoji('😪'); return false;">😪</button>
                                <button onclick="escribeEmoji('🤮'); return false;">🤮</button>
                                <button onclick="escribeEmoji('👍'); return false;">👍</button>
                                <button onclick="escribeEmoji('👎'); return false;">👎</button>
                                <button onclick="escribeEmoji('👌'); return false;">👌</button>
                                <button onclick="escribeEmoji('🏆'); return false;">🏆</button>
                                <button onclick="escribeEmoji('👻'); return false;">👻</button>
                                <button onclick="escribeEmoji('🕹️'); return false;">🕹️</button>
                                <button onclick="escribeEmoji('🎮'); return false;">🎮</button>
                                <button onclick="escribeEmoji('🖥️'); return false;">🖥️</button>
                                <button onclick="escribeEmoji('💻'); return false;">💻</button>
                                <button onclick="escribeEmoji('📱'); return false;">📱</button>
                                <button onclick="escribeEmoji('👾'); return false;">👾</button>
                                <button onclick="escribeEmoji('👽'); return false;">👽</button>
                                <button onclick="escribeEmoji('🤖'); return false;">🤖</button>
                                <button onclick="escribeEmoji('💎'); return false;">💎</button>
                            </div>
                        </div>
                        <textarea onkeypress="pulsar(event)" type="text" name="mensaje" class="caja_mensaje" id="caja_mensaje" placeholder="Escribe tu mensaje aquí"></textarea>
                        <button onclick="enviarMensaje(<?php echo $_SESSION['id_logueado'] ?>, '<?php echo $nombre ?>', '<?php echo $tipo_chat ?>'); return false;" id="bt_enviar" class="bt_enviar" name="bt_enviar">
                                <img src="/pagina/proyecto/img/send.png" />
                        </button>

                        <!-- SCRIPT DEL BOTON QUE APARECE CUANDO EL SCROLL NO ESTA ANCLADO ABAJO -->
                        <script type="text/javascript">
                            $(document).ready(scroll);

                            setInterval(function() {
                                scroll();
                            }, 100);

                            function scroll() {
                                var scroll = document.getElementById("scroll");
                                    
                                if((scroll.scrollTop + scroll.clientHeight) === scroll.scrollHeight){
                                    document.getElementById("abajo").classList.remove("show");
                                } else {
                                    document.getElementById("abajo").classList.add("show");
                                }
                            }
                        </script>

                        <!-- SCRIPT DEL BOTON QUE APARECE CUANDO EL SCROLL NO ESTA ANCLADO ABAJO -->
                        <script type="text/javascript">
                            $(document).ready(scroll);

                            setInterval(function() {
                                scroll();
                            }, 100);

                            function scroll() {
                                var scroll = document.getElementById("scroll");
                                    
                                if((scroll.scrollTop + scroll.clientHeight) === scroll.scrollHeight){
                                    document.getElementById("abajo").classList.remove("show");
                                } else {
                                    document.getElementById("abajo").classList.add("show");
                                }
                            }
                        </script>

                        <?php
                            /**
                             * ACCION DEL BOTON PARA ENVIAR MENSAJES
                             */
                            if(isset($_POST['bt_enviar'])){
                                //LIBRERIA DE REQUESTS
                                require_once '..\..\lib\Requests\library\Requests.php';
                                Requests::register_autoloader();

                                //VARIABLES
                                $mensaje = trim($_POST['mensaje']);

                                // PARA ENVIAR UN MENSAJE AL MENOS SE DEBE ESCRIBIR UN CARACTER
                                if (strlen($mensaje) >= 1) {
                                    if(isset($_GET['chat'])){
                                        $data = array(
                                            'id_on'=>$_SESSION['id_logueado'],
                                            'name'=>$_GET['chat'],
                                            'mensaje'=>$mensaje,
                                            'chat'=>"amigo"
                                        );
                                    } else if(isset($_GET['grupo'])){
                                        $data = array(
                                            'id_on'=>$_SESSION['id_logueado'],
                                            'name'=>$_GET['grupo'],
                                            'mensaje'=>$mensaje,
                                            'chat'=>"grupo"
                                        );
                                    }
                                }
                                
                                $url_chat = "http://localhost/pagina/proyecto/pages/chat/app/funciones_chat.php";
                                $type = Requests::POST;
                                
                                $respuesta = Requests::request($url_chat, array(), $data, $type);
                            }
                        ?>
                    </form>
                <?php
                    }
                ?>
            </div>
        </section>
    </main>
</body>
</html>

<script>
    // PREVISUALIZACION DE LA IMAGEN PARA EL GRUPO
    document.getElementById("imgGrupo").onchange = function(e) {
        // CREAMOS EL OBJETO DE LA CLASE FILEREADER
        let reader = new FileReader();

        // LEEMOS EL ARCHIVO SUBIDO Y SE LO PASAMOS A NUESTRO FILEREADER
        reader.readAsDataURL(e.target.files[0]);

        // LE DECIMOS QUE CUANDO ESTE LISTO EJECUTE EL CODIGO INTERNO
        reader.onload = function(){
            let preview = document.getElementById('previewChat'),
                    image = document.createElement('img');

            image.src = reader.result;

            preview.innerHTML = '';
            preview.append(image);
        };
    }

    addEventListener('load',inicio,false);

    function inicio() {
        document.getElementById('num_grupo').addEventListener('change',cambioNum,false);
    }

    function cambioNum() {    
        document.getElementById('num').innerHTML=document.getElementById('num_grupo').value;
    }
</script>