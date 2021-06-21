<?php
//VARIABLES DE INICIO SOLAMENTE SI LA SESION ES INICIADA
if (isset($_SESSION['id_logueado'])) {
    $user_on = $_SESSION['nombre_logueado'];
    $id_on = $_SESSION['id_logueado'];
}

//RUTA ACTUAL EN LA QUE NOS ENCONTRAMOS
$url_actual = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

//RUTAS A PAGINAS
$proyecto = "http://" . $_SERVER["HTTP_HOST"] . "/Pagina/proyecto/";

$home = $proyecto . "index.php";
$foro = $proyecto . "pages/foro/foro.php";
$tienda = $proyecto . "pages/tienda/tienda.php";
$stream = $proyecto . "pages/stream/stream.php";
$chat = $proyecto . "pages/chat/chat.php";
$usuarios = $proyecto . "usuarios/miembros.php";
$guardados = $proyecto . "usuarios/favoritos.php";
$iniciar = $proyecto . "pages/sesion/iniciar_sesion.php";
$registro = $proyecto . "pages/sesion/registrar.php";
$normas = $proyecto . "pages/normativa/normas.php";
$privacidad = $proyecto . "pages/normativa/privacidad.php";
$terminos = $proyecto . "pages/normativa/terminos.php";

//SI NO SE HA LOGEADO NO SE CREA ESTA VARIABLE - EVITAR ERRORES
if (isset($id_on)) {
    $perfil = $proyecto . "usuarios/profile.php?id=" . $id_on;
}
?>


<body>
    <!-- CSS DE W3schools -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <!-- CSS DE LA NAV -->
    <link rel="stylesheet" type="text/css" href="./src/nav.css">
    <!-- CSS DE LOS POPUPS -->
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/popups.css">

    <!-- SCRIPT OBLIGATORIOS EN LA NAVBAR -->
    <link rel="stylesheet" href="@sweetalert2/themes/dark/dark.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <header>
        <nav class="navbar_final">
            <div class="contenido_nav">
                <a class="nomlogo flexeando" href="<?php echo $home; ?>">
                    <img src='/pagina/proyecto/img/ico_navbar.png'>
                    <span class="txt lef">Level<span class="up">UP</span></span>
                </a>
                <div class="botones flexeando espacio">
                    <div class="btn_nav">
                        <a href="<?php echo $home; ?>">Inicio</a>
                    </div>
                    <div class="btn_nav">
                        <a href="<?php echo $foro; ?>">Foro</a>
                    </div>
                    <div class="btn_nav">
                        <a href="<?php echo $tienda; ?>">Tienda</a>
                    </div>
                    <div class="btn_nav">
                        <a href="<?php echo $stream; ?>">Stream</a>
                    </div>

                    <div class="separador espacio"></div>

                    <?php
                    if (isset($id_on)) {
                    ?>
                        <div class='brillo'>
                            <a href="<?php echo $chat;?>"><img class='iconos' src='/pagina/proyecto/img/chat.png' /></a>
                        </div>
                        <div class='txt '>
                            <div class='dropdown1'>
                                <button class='btndrop1 brillo' onclick='dropa()'><img class='iconos' src='/pagina/proyecto/img/campana.png' /></button>
                                <div id='midrop1' class='imgdropdown1'>
                                    <div class="tit_notificaciones">
                                        <label> Notificaciones </label>
                                    </div>
                                    <ul id='lista' class='list_notification'>

                                        <script>
                                            setInterval(function() {
                                                obtener_notificaciones();
                                            }, 5000);
                                            
                                            $(obtener_notificaciones());

                                            function obtener_notificaciones() {
                                                
                                                var id_on = '<?php echo $id_on;?>';
                                                var user_on = '<?php echo $user_on;?>';

                                                $.ajax({
                                                    url : 'http://localhost/Pagina/proyecto/app/mostrar_notificaciones.php',
                                                    type : 'POST',
                                                    dataType : 'html',
                                                    data : {
                                                        id_on: id_on,
                                                        funcion: "mostrar"
                                                    }
                                                })
                                                .done(function(respuesta){
                                                    $("#lista").html(respuesta);
                                                });
                                            }

                                            function aceptar(id_notification, tipo_notification, otra_id) {
                                                
                                                var id_on = '<?php echo $id_on;?>';
                                                var id_notification = id_notification;
                                                var tipo_notification = tipo_notification;
                                                var otra_id = otra_id;
                                                var funcion = "aceptar";

                                                $.ajax({
                                                    url : 'http://localhost/Pagina/proyecto/app/funciones_notificaciones.php',
                                                    type : 'POST',
                                                    dataType : 'html',
                                                    data : {
                                                        id_on: id_on,
                                                        id_notification: id_notification,
                                                        tipo_notification: tipo_notification,
                                                        otra_id: otra_id,
                                                        funcion: funcion
                                                    }
                                                })
                                                .done(function(respuesta){

                                                    //alert(respuesta);

                                                    if(respuesta=="grupo_lleno"){
                                                        Swal.fire({
                                                            text: '¡¡Oh nooo!! ¡¡El grupo esta lleno!!',
                                                            background: '#2a2f32'
                                                        }).then((result) => {
                                                        });
                                                    } else{
                                                        window.location.reload();
                                                    }
                                                });
                                            }

                                            function aceptarGrupo(id_notification, tipo_notification, otra_id, id_grupo) {
                                                
                                                var id_on = '<?php echo $id_on;?>';
                                                var id_notification = id_notification;
                                                var tipo_notification = tipo_notification;
                                                var otra_id = otra_id;
                                                var id_grupo = id_grupo;
                                                var funcion = "aceptar";

                                                $.ajax({
                                                    url : 'http://localhost/Pagina/proyecto/app/funciones_notificaciones.php',
                                                    type : 'POST',
                                                    dataType : 'html',
                                                    data : {
                                                        id_on: id_on,
                                                        id_notification: id_notification,
                                                        tipo_notification: tipo_notification,
                                                        otra_id: otra_id,
                                                        id_grupo: id_grupo,
                                                        funcion: funcion
                                                    }
                                                })
                                                .done(function(respuesta){

                                                    //alert(respuesta);

                                                    if(respuesta=="grupo_lleno"){
                                                        Swal.fire({
                                                            text: '¡¡Oh nooo!! ¡¡El grupo esta lleno!!',
                                                            background: '#2a2f32'
                                                        }).then((result) => {
                                                        });
                                                    } else{
                                                        window.location.reload();
                                                    }
                                                });
                                            }

                                            function rechazar(id_notification) {
                                                
                                                var id_notification = id_notification;
                                                var funcion = "rechazar";

                                                $.ajax({
                                                    url : 'http://localhost/Pagina/proyecto/app/funciones_notificaciones.php',
                                                    type : 'POST',
                                                    dataType : 'html',
                                                    data : {
                                                        id_notification: id_notification,
                                                        funcion: funcion
                                                    }
                                                })
                                                .done(function(){
                                                    window.location.reload();
                                                });
                                            }

                                            function ir(id_notification, id_topic) {
                                                
                                                var id_topic = id_topic;

                                                rechazar(id_notification);
                                                window.location.replace('http://localhost/Pagina/proyecto/pages/foro/topic.php?id='+id_topic);
                                            }
                                        </script>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <!-- INICIAR SESIÓN -->

                        <!-- Contenedor -->
                        <div class="w3-container">
                            <!-- Botón de Iniciar Sesión -->
                            <button onclick="document.getElementById('id01').style.display='block'" class="w3-button w3-grey w3-responsive conex">Iniciar Sesión</button>

                            <!-- Ventana de Iniciar Sesión -->
                            <div id="id01" class="w3-modal">
                                <div class="popup w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                                    <!-- Parte superior de la ventana -->
                                    <div class="w3-center formu "><br>
                                        <!--Botón cerrar ventana -->
                                        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-large w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                                        <!--Título de la ventana -->
                                        <h1 class="Titulo_PopUp">Iniciar Sesión</h1>
                                    </div>

                                    <!-- Contenido de la ventana -->
                                    <div class="w3-container formu ">
                                        <div class="w3-section">
                                            <form method="POST" class="postenedor">
                                                <!--Método POST porque la información no debe ser visible para los demás-->
                                                <span class="txtt">Nombre de usuario</span>
                                                <input class="postbox postin" type="text" name="user" placeholder="Usuario / e-mail" required>
                                                <span class="txtt">Contraseña</span>
                                                <input class="postbox postin" type="password" name="pass" placeholder="Contraseña" required>
                                                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                                                <input class="btnenviar botones" type="submit" name="bt_iniciar" value="Iniciar Sesión">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin botón de Iniciar Sesión -->

                        <!-- REGISTRAR -->

                        <!-- Contenedor -->
                        <div class="w3-container">
                            <!-- Botón de Iniciar Sesión -->
                            <button onclick="document.getElementById('id02').style.display='block'" class="w3-button w3-grey w3-responsive conex">Registrarse</button>

                            <!-- Ventana de Iniciar Sesión -->
                            <div id="id02" class="w3-modal">
                                <div class="popup w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                                    <!-- Parte superior de la ventana -->
                                    <div class="w3-center formu"><br>
                                        <!--Botón cerrar ventana -->
                                        <span onclick="document.getElementById('id02').style.display='none'" class="w3-button w3-large w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                                        <!--Título de la ventana -->
                                        <h1 class="Titulo_PopUp">Registrarse</h1>
                                    </div>

                                    <!-- Contenido de la ventana -->
                                    <div class="w3-container formu">
                                        <div class="w3-section">
                                            <form method="POST" class="postenedor">
                                                <!--Método POST porque la información no debe ser visible para los demás-->
                                                <span class="txtt">Nombre de usuario</span>
                                                <input class="postbox postin" type="text" name="user" placeholder="Tu nombre de usuario" maxlength="15" required>
                                                <div class="contras">
                                                    <div class="mitad">
                                                        <span class="txtt">Contraseña</span>
                                                        <input class="postbox postin" type="password" name="pass" placeholder="Tu contraseña" required>
                                                    </div>
                                                    <div class="mitad">
                                                        <span class="txtt">Repita la contraseña</span>
                                                        <input class="postbox postin" type="password" name="confirm_pass" placeholder="Repite la contraseña" required>
                                                    </div>
                                                </div>
                                                <span class="txtt">E-mail</span>
                                                <input class="postbox postin" type="text" name="email" placeholder="E-mail" required>
                                                <div class="condiciones">
                                                    <input type="checkbox" name="checkbox" class="checkbox" required>
                                                    <label for="checkbox">Acepto los <span class="enlaces"><a href='http://localhost/Pagina/proyecto/pages/normativa/terminos.php'> Términos de Uso</a></span> y la <span class="enlaces"><a href='http://localhost/Pagina/proyecto/pages/normativa/privacidad.php'> Política de Privacidad </a></span></label>
                                                </div>
                                                <script src='https://www.google.com/recaptcha/api.js'></script>
                                                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                                                <input class="btnenviar botones" type="submit" name="bt_crear" value="Crear Cuenta">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin botón de Registrar -->

                    <?php
                        include $_SERVER['DOCUMENT_ROOT'] . '/Pagina/proyecto/pages/sesion/validar.php';
                    }
                    ?>

                </div>
                <div class="nomperf flexeando espacio">
                    <?php
                    if (isset($id_on)) {
                        // REQUIRE_ONCE QUE SUSTITUYE A LOS IF DE ARRIBA - LINEA 105
                        require_once $_SERVER['DOCUMENT_ROOT'] . '/Pagina/proyecto/lib/Requests/library/Requests.php';
                        Requests::register_autoloader();

                        $data = array(
                            'id_on' => $id_on
                        );
                        $url = "http://localhost/pagina/proyecto/app/datos_user.php";
                        $type = Requests::GET;

                        $respuesta = Requests::request($url, array(), $data, $type);
                        $dato_user = json_decode($respuesta->body, true);

                        //var_dump($dato_user['profile_pic']);

                        echo "  <div class='dropdown'>
                                        <button class='btndrop' onclick='drop()'><img class='img_perfil' src='/pagina/proyecto/" . $dato_user['profile_pic'] . "'></button>
                                        <div id='midrop' class='imgdropdown'>
                                            <ul id='lista' class='list'>
                                                <li>
                                                    <a href='" . $perfil . "'>Perfil</a>
                                                </li>
                                                <li>
                                                    <a href='" . $usuarios . "'>Usuarios</a>
                                                </li>
                                                <li>
                                                    <a href='" . $guardados . "'>Favoritos</a>
                                                </li>
                                                <li>
                                                    <a href='" . $normas . "'>Normas</a>
                                                </li>
                                                <li>
                                                    <form method='post'><input class='btn_limpio' type='submit' name='bt_cerrar' value='Cerrar sesión'></form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <span class='nombre_user' class='txt'>".$user_on."</span>";

                            if(isset($_POST['bt_cerrar'])) {
                                $data = array(
                                    'id_on'=>$id_on
                                );
                                $url = "http://localhost/pagina/proyecto/app/datos_user.php";
                                $type = Requests::PUT;
                                
                                $respuesta = Requests::request($url, array(), $data, $type);
                                $dato_user = json_decode($respuesta->body,true);

                                session_destroy();
                                echo "<script>window.location.replace('".$home."');</script>";
                            }
                    } else {
                        echo "<img class='img_off' src='/pagina/proyecto/img/user.png'>";
                    }
                    ?>

                </div>
            </div>
        </nav>
    </header>