<?php
session_start();
require("../../config.php");
//if(@$_SESSION['usuario_logueado']){
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./src/style.css">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/popups.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- JAVASCRIPT -->
    <script src="../../lib/jquery.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/@lyket/widget@latest/dist/lyket.js?apiKey=f65b5071dc6713b579994261bea53a"></script>

    <!-- ICONO -->
    <link rel="icon" type="image/png" href="../../img/ico.png">
    <title>Level UP - Foro</title>
</head>

<?php
//LOCALIZAR FONDO
$sql_inventory = 'SELECT * FROM inventory';

$stmt_inventory = $conn->prepare($sql_inventory);
$stmt_inventory->execute();
$fondo = false;

while (($resultado_inventory = $stmt_inventory->fetch())) {

    $id_producto = $resultado_inventory['id_inventory']; //Id del producto

    //CAMBIAR FONDO DE FORO
    $sql_producto = "SELECT * FROM pocket WHERE id_usuario = :id_usuario AND id_producto = :id_producto AND page = 'foro' AND status = 1";

    $stmt_producto = $conn->prepare($sql_producto);
    $stmt_producto->bindParam(':id_usuario', $_SESSION['id_logueado']);
    $stmt_producto->bindParam(':id_producto', $id_producto);
    $stmt_producto->execute();
    $resultado_producto = $stmt_producto->fetch();

    if (isset($resultado_producto['id_producto'])) {
        echo "<body background='../tienda/" . $resultado_inventory['file_product'] . "'>";
        $fondo = true;
    }
}
if (!$fondo) {
    echo "<body background='../../img/Wallpaper_inicio.jpg'>";
}
?>

<!--Navbar-->
<?php include "../../nav.php"; ?>

<!--Cartera del Usuario-->
<?php
    $sql_dinero = 'SELECT * FROM user WHERE usuario = :usuario';

    $stmt_dinero = $conn->prepare($sql_dinero);
    $stmt_dinero->bindParam(':usuario', $user_on);
    $stmt_dinero->execute();
    $resultado_dinero = $stmt_dinero->fetch();

    $cartera_usuario = @$resultado_dinero['money']; //Dinero del usuario
?>

<!--Botón ir arriba-->
<span class="ir-arriba"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="white" class="bi bi-chevron-up" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
    </svg></span>

<main class="main">
    <section class="general">
        <div class="contenedor">
            <!--Barra de busqueda en el foro-->
            <nav class="nav1">
                <div id="filterdrop" class="filter">
                    <div class="contenido">
                        <div class="recientes">
                            <img src="/pagina/proyecto/img/recientes.png" />
                            <button onclick="recientes()" class="btn_foro botones">Mas recientes</button>
                        </div>
                        <div class="likeados">
                            <img src="/pagina/proyecto/img/likeados.png" />
                            <button onclick="toplike()" class="btn_foro botones">Mas Gustados</button>
                        </div>
                        <div class="top">
                            <img src="/pagina/proyecto/img/top.png" />
                            <button onclick="semanal()" class="btn_foro botones">Top de la semana</button>
                        </div>
                        <div class="controversiales">
                            <img src="/pagina/proyecto/img/polemic.png" />
                            <button onclick="controver()" class="btn_foro botones">Polémicos</button>
                        </div>
                    </div>
                </div>
                <div class="navegador1">
                    <button onclick="verfiltros()" class="btnfiltro botones"><img src="/pagina/proyecto/img/list.png" /></button>
                    <form class="formulario_busqueda" method="post">
                        <input type="text" name="caja_busqueda" class="input inputbusca" placeholder="Buscar en el foro" required>
                        <button onclick="buscar()" class="btnbusca">Buscar</button>
                    </form>
                </div>
            </nav>
            <!--Barra de creación de posts en el foro-->
            <?php
            if (@$_SESSION['id_logueado']) {
            ?>
                <nav class="nav2">
                    <div class="navegador2">
                        <button id="perfnav" class="btnperf botones"><?php echo "<a href='" . $perfil . "'><img class='img_perfil' src='/pagina/proyecto/" . $dato_user['profile_pic'] . "'></a>" ?></button>


                        <!-- Botón POP UP de enviar post -->
                        <input type="text" onclick="document.getElementById('id03').style.display='block'" name="post" class="input " placeholder="Crea un post">
                        <!-- Ventana de crear Post -->
                        <div id="id03" class="w3-modal">
                            <div class="popup w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                                <!-- Parte superior de la ventana -->
                                <div class="w3-center "><br>
                                    <!--Botón cerrar ventana -->
                                    <span onclick="document.getElementById('id03').style.display='none'" class="w3-button w3-large w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                                    <!--Título de la ventana -->
                                    <h1 class="Titulo_PopUp">Crear Post</h1>
                                </div>

                                <!-- Contenido de la ventana -->
                                <div class="w3-container ">
                                    <div class="w3-section">
                                        <form method="POST" class="postenedor">
                                            <span class="txtt">Título</span>
                                            <input class="postbox postin" type="text" name="topic_name" required>
                                            <span class="txtt">Cuéntanos que ha pasado</span>
                                            <textarea class="postbox" name="contenido" cols="30" rows="10" required></textarea>
                                            <input class="btnenviar botones" type="submit" name="submit" value="Post">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHP para recoger los datos del tema creado -->
                        <?php
                        $nombre_tema = @$_POST['topic_name'];
                        $contenido = @$_POST['contenido'];

                        if (isset($_POST['submit'])) {
                            //Actualizar cartera +5
                            $sql_actualizar_dinero = 'UPDATE user SET money=money+5 WHERE usuario = :usuario';

                            $stmt_actualizar_dinero = $conn->prepare($sql_actualizar_dinero);
                            $stmt_actualizar_dinero->bindParam(':usuario', $user_on);
                            $stmt_actualizar_dinero->execute();

                            $insert = "
                                                        INSERT INTO topics 
                                                            (topic_name, topic_content, topic_creator)
                                                        VALUES
                                                            (:nombre, :contenido, :usuario_actual)";

                            $stmt = $conn->prepare($insert);
                            $stmt->bindParam(':nombre', $nombre_tema);
                            $stmt->bindParam(':contenido', $contenido);
                            $stmt->bindParam(':usuario_actual', $user_on);
                            $stmt->execute();
                            echo "<script>window.location.replace('Foro.php');</script>";
                        }
                        ?>


                        <!-- Botón POP UP de enviar imágenes -->
                        <button class="imgpost botones" onclick="document.getElementById('id04').style.display='block'">
                            <img src="/pagina/proyecto/img/imgForo.png" />
                        </button>

                        <!-- Ventana de crear Post -->
                        <div id="id04" class="w3-modal">
                            <div class="popup w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                                <!-- Parte superior de la ventana -->
                                <div class="w3-center"><br>
                                    <!--Botón cerrar ventana -->
                                    <span onclick="document.getElementById('id04').style.display='none'" class="w3-button w3-large w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                                    <!--Título de la ventana -->
                                    <h1 class="Titulo_PopUp">Postear imagen</h1>
                                </div>

                                <!-- Contenido de la ventana -->
                                <div class="w3-container">
                                    <div class="w3-section">
                                        <form method="POST" enctype="multipart/form-data" class="postenedor">
                                            <span class="txtt">Título</span>
                                            <input class="postbox postin" type="text" name="topic_name" required>
                                            <span class=" txtt txtt_img">Imagen</span>
                                            <div class="img_pre">
                                                <label class="file_label" for="file_foro">Upload</label>
                                                <input class="btnimagen botones" id="file_foro" type="file" name="file_foro" />
                                                <div id="preview"></div>
                                            </div>
                                            <input class="btnenviar botones" type="submit" name="upload_foro" class="boton_imagen_foro" value="Enviar">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php

                        $nombre_tema_imagen = @$_POST['topic_name'];
                        $contenido_imagen = null;

                        //Enviar imagen PHP
                        if (isset($_POST['upload_foro'])) {
                            //Actualizar cartera +10
                            $sql_actualizar_dinero = 'UPDATE user SET money=money+10 WHERE usuario = :usuario';

                            $stmt_actualizar_dinero = $conn->prepare($sql_actualizar_dinero);
                            $stmt_actualizar_dinero->bindParam(':usuario', $user_on);
                            $stmt_actualizar_dinero->execute();

                            $file_name = $_FILES['file_foro']['name'];
                            $file_type = $_FILES['file_foro']['type'];
                            $file_size = $_FILES['file_foro']['size'];
                            $file_tem_loc = $_FILES['file_foro']['tmp_name'];
                            $file_store = "img_foro/" . $file_name;

                            if (move_uploaded_file($file_tem_loc, $file_store)) {
                                $file_store = "/img_foro/" . $file_name;
                                $insert_image = "
                                                        INSERT INTO topics 
                                                            (topic_name, topic_content, topic_creator, image_foro)
                                                        VALUES
                                                            (:topic_nombre, :content, :creator, :imgforo)";
                                $stmt_image = $conn->prepare($insert_image);
                                $stmt_image->bindParam(':topic_nombre', $nombre_tema_imagen);
                                $stmt_image->bindParam(':content', $contenido_imagen);
                                $stmt_image->bindParam(':creator', $user_on);
                                $stmt_image->bindParam(':imgforo', $file_name);
                                $stmt_image->execute();
                                echo "<script>window.location.replace('Foro.php');</script>";
                                exit;
                            } else {
                                echo "
                                    <script>
                                        Swal.fire({
                                            text: '¡Tienes que subir una imagen primero!'
                                        }).then((result) => {
                                        });
                                    </script>
                                ";
                            }
                        }
                        ?>

                        <!-- Botón POP UP de enviar enlaces -->
                        <button class="linkpost botones" onclick="document.getElementById('id05').style.display='block'">
                            <img src="/pagina/proyecto/img/link.png" />
                        </button>


                        <!-- Ventana de crear Post -->
                        <div id="id05" class="w3-modal">
                            <div class="popup w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                                <!-- Parte superior de la ventana -->
                                <div class="w3-center"><br>
                                    <!--Botón cerrar ventana -->
                                    <span onclick="document.getElementById('id05').style.display='none'" class="w3-button w3-large w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                                    <!--Título de la ventana -->
                                    <h1 class="Titulo_PopUp">Postear un vídeo de Youtube</h1>
                                </div>

                                <!-- Contenido de la ventana -->
                                <div class="w3-container">
                                    <div class="w3-section">
                                        <form method="POST" enctype="multipart/form-data" class="postenedor">
                                            <span class="txtt">Nombre del tema</span>
                                            <input class="postbox postin" type="text" name="topic_name" required>
                                            <span class="txtt">URL del vídeo de youtube</span>
                                            <input class="postbox postin" id="video_youtube" type="url" name="video_youtube" required />
                                            <input class="btnenviar botones" type="submit" name="upload_video" value="Enviar">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHP para recoger los vídeos del tema creado -->
                        <?php
                        $nombre_tema = @$_POST['topic_name'];
                        $url = @$_POST['video_youtube'];

                        if (isset($_POST['upload_video'])) {
                            //Actualizar cartera +15
                            $sql_actualizar_dinero = 'UPDATE user SET money=money+15 WHERE usuario = :usuario';

                            $stmt_actualizar_dinero = $conn->prepare($sql_actualizar_dinero);
                            $stmt_actualizar_dinero->bindParam(':usuario', $user_on);
                            $stmt_actualizar_dinero->execute();

                            $insert = "
                                                        INSERT INTO topics 
                                                            (topic_name, topic_creator, url)
                                                        VALUES
                                                            (:nombre_topic, :creador, :enlace)";

                            $stmt = $conn->prepare($insert);
                            $stmt->bindParam(':nombre_topic', $nombre_tema);
                            $stmt->bindParam(':creador', $user_on);
                            $stmt->bindParam(':enlace', $url);
                            $stmt->execute();
                            echo "<script>window.location.replace('Foro.php');</script>";
                        }
                        ?>


                    </div>
                </nav>

            <?php
            } else {
            }
            ?>
            <!--Aqui es donde van los posts-->
            <div class="content">
                <?php
                //VARIABLES GLOBALES
                $titulo_tema;
                $contenido_tema;
                $creador_tema;
                $fecha_tema;
                $imagen_perfil;
                $url_youtube;

                //Recoger datos de la base de datos TOPICS

                if (strlen(trim(@$_POST['caja_busqueda'])) >= 1) {
                    $sql_busqueda = 'SELECT * FROM topics WHERE topic_creator LIKE :clave OR topic_name LIKE :clave ORDER BY topic_id DESC';
                    $stmt = $conn->prepare($sql_busqueda);
                    $stmt->bindParam(':clave', $clave);
                    $clave = "%" . $_POST['caja_busqueda'] . "%";
                } else {
                    $sql = 'SELECT * FROM topics ORDER BY topic_id DESC'; //haces una busqueda de lo que hay en la tabla
                    $stmt = $conn->prepare($sql);
                }

                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    while (($resultado = $stmt->fetch())) {

                        //ATRIBUTOS PARA LOS TOPICS
                        $id = $resultado['topic_id'];

                        $creador_tema = $resultado['topic_creator']; //Usuario creador del tema -> 1

                        $titulo_tema = $resultado['topic_name']; //Nombre del tema -> 2

                        $contenido_tema = $resultado['topic_content']; //Contenido del tema -> 3

                        $imagen_foro = $resultado['image_foro']; //Imagen foro -> 5

                        //Calcular tiempo de las publicaciones

                        $tiempo = $resultado['date'];

                        if (!function_exists('tiempo')) {
                            function tiempo($tiempo)
                            {
                                $tiempo = strtotime($tiempo);
                                $curTime = time();
                                $timeElapsed = $curTime - $tiempo;
                                $seconds = $timeElapsed;
                                $minutes = round($timeElapsed / 60);
                                $hours = round($timeElapsed / 3600);
                                $days = round($timeElapsed / 86400);
                                $weeks = round($timeElapsed / 604800);
                                $months = round($timeElapsed / 2600640);
                                $years = round($timeElapsed / 31207680);
                                $resultado_final = 0;

                                /* Seconds  Calculation*/
                                if ($seconds <= 60) {
                                    $resultado_final = 'Ahora mismo';
                                } /* Minutes */ elseif ($minutes <= 60) {
                                    if ($minutes == 1) {
                                        $resultado_final = "Hace un minuto";
                                    } else {
                                        $resultado_final = "Hace " . $minutes . " minutos";
                                    }
                                } /* Hours */ elseif ($hours <= 24) {
                                    if ($hours == 1) {
                                        $resultado_final = "Hace una hora";
                                    } else {
                                        $resultado_final = "Hace " . $hours . " horas";
                                    }
                                } /* Days */ elseif ($days <= 7) {
                                    if ($days == 1) {
                                        $resultado_final = "Hace un día";
                                    } else {
                                        $resultado_final = "Hace " . $days . " días";
                                    }
                                } /* Weeks */ elseif ($weeks <= 4.3) {
                                    if ($weeks == 1) {
                                        $resultado_final = "Hace una semana";
                                    } else {
                                        $resultado_final = "Hace " . $weeks . " semanas";
                                    }
                                } /* Months */ elseif ($months <= 12) {
                                    if ($months == 1) {
                                        $resultado_final = "Hace un mes";
                                    } else {
                                        $resultado_final = "Hace " . $months . " meses";
                                    }
                                } /* Years */ else {
                                    if ($years == 1) {
                                        $resultado_final = "Hace un año";
                                    } else {
                                        $resultado_final = "Hace " . $years . " años";
                                    }
                                }

                                return $resultado_final;
                            }
                        }

                        $url_youtube = $resultado['url']; //Vídeos Youtube -> 6

                        //ATRIBUTOS PARA LOS AVATARES DE PERFIL
                        $sql2 = 'SELECT * FROM user WHERE usuario = :usuario'; //haces una busqueda para la foto de perfil de usuarios

                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->bindParam(':usuario', $resultado['topic_creator']);
                        $stmt2->execute();
                        $resultado2 = $stmt2->fetch();

                        $id_user = $resultado2['id'];
                        $imagen_perfil = $resultado2['profile_pic']; //Imagen avatar usuarios -> 4


                ?>      <div class="solotexto">
                            <div class="hud">
                                <div class="titulo">
                                    <div class="posteador">
                                        <div class="imgperf">
                                            <?php echo "<a class='ay' href='../../usuarios/profile.php?id=$id_user'><img class='imgperf' src='../../" . $resultado2['profile_pic'] . "'></a>"; // 4 
                                            ?>
                                        </div>
                                        <?php echo "<div class='nombrepos'><a class='ay' href='../../usuarios/profile.php?id=$id_user'>" . $creador_tema . "</a>   <span class = 'tiempo'>" . tiempo($tiempo) . "</span> </div>"; //1 
                                        ?>
                                        <div class="nombreforo">
                                            <button class="foro botones tex">Cosas graciosas</button>
                                        </div>
                                    </div>

                                    <!-- Compartir Temas -->
                                    <div class="head">

                                        <?php echo "<a class='ay titulos' href='topic.php?id=$id'>" . $titulo_tema . "</a>"; //2 
                                        ?>
                                        <button onclick="compartir(<?php echo $id ?>)" class="icoforo btnshare botones">
                                            <div class="share">
                                                <img src="/pagina/proyecto/img/share.png" />
                                                <span class="space tex1">Compartir</span>
                                            </div>
                                        </button>
                                    </div>

                                </div>
                                <div class="post">
                                    <button class="bot botones">
                                        <?php
                                        //Si mandas una imagen
                                        if ($contenido_tema == null && $url_youtube == null) {
                                            echo "<a class='ay' href='topic.php?id=$id'><img class='imagen_foro' src='img_foro/" . $imagen_foro . "'></a>";
                                            //Si mandas un vídeo
                                        } else if ($contenido_tema == null && $imagen_foro == null) {
                                            //Te consigue la ID del vídeo de Youtube
                                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url_youtube, $match);
                                            $youtube_id = $match[1];

                                            //Ponemos el embed, pero usando la id del vídeo que el usuario ha mandado
                                            echo "<div class='container'><iframe class='responsive-iframe' style= 'height: 480px; width: 750px';  src='https://www.youtube.com/embed/" . $youtube_id . "' frameborder='0' allowfullscreen></iframe></div>";
                                            //Si mandas un texto
                                        } else {
                                            echo "<a class='ay' href='topic.php?id=$id'><div class='texto tex'> <span id='texto' class='textito'>" . $contenido_tema . "</span> </div> </a>";
                                        }
                                        ?>
                                    </button>
                                </div>

                                <div class="botoncitos">
                                    <div class="votaciones">
                                        <!-- minimal settings -->
                                        <div class="box__button__container">
                                            <div data-lyket-type="updown" data-lyket-id="my-<?php echo $id."up"; ?>-post" data-lyket-template="Chevron" data-lyket-color-text="white" data-lyket-color-primary="#73CBCB" data-lyket-color-secondary="#f93e3e" data-lyket-color-icon="white"></div>
                                        </div>
                                    </div>

                                    <!-- Contar comentarios -->
                                    <?php
                                    $sql_contar_comentarios = 'SELECT * FROM comments WHERE post_id = :id_post'; //haces una busqueda para la foto de perfil de usuarios

                                    $stmt_contar_comentarios = $conn->prepare($sql_contar_comentarios);
                                    $stmt_contar_comentarios->bindParam(':id_post', $id);
                                    $stmt_contar_comentarios->execute();
                                    $contador_comentarios = $stmt_contar_comentarios->rowCount();
                                    $comentario;
                                    if ($contador_comentarios == 0) {
                                        $comentario = "Sin comentarios...";
                                    } else if ($contador_comentarios == 1) {
                                        $comentario = $contador_comentarios . " comentario";
                                    } else {
                                        $comentario = $contador_comentarios . " comentarios";
                                    }

                                    ?>

                                    <button class="icoforo botones ">
                                        <div class="comentarios">
                                            <img src="/pagina/proyecto/img/comment.png" />
                                            <span class="space tex1"><?php echo "<a class='ay tex1' href='topic.php?id=$id'>" . $comentario . "</a>" ?></span>
                                        </div>
                                    </button>

                                    <!-- Guardar publicaciones a favoritos -->
                                    <button class="icoforo btnsave botones" onclick="guardar(<?php echo @$resultado['topic_id'];  ?>)">
                                        <div class="guardar">
                                            <img src="/pagina/proyecto/img/favoritos.png" />
                                            <form method="post">
                                                <span><input class="space tex1" type="submit" name="submit_favourite" value="Guardar" /></span>
                                            </form>
                                        </div>
                                    </button>

                                    <!-- PHP de favoritos -->
                                    <script>
                                        function guardar(id_post_guardar) {
                                            $.ajax({
                                                    url: 'http://localhost//Pagina/proyecto/pages/foro/post_guardado.php',
                                                    type: 'POST',
                                                    data: {
                                                        id_post_guardar: id_post_guardar,
                                                        id_logueado: <?php echo @$_SESSION['id_logueado'];  ?>
                                                    }
                                                })
                                                .done(function(respuesta) {
                                                    alert(respuesta);
                                                    window.location.replace('Foro.php');
                                                });
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?><h1 class="fallo">No se han encontrado nada que corresponda con tu búsqueda</h1><?php
                                                                                                            }
                                                                                                                ?>
            </div>
        </div>
    </section>
</main>
</body>

</html>



<!-- Botón para subir arriba-->
<script type="text/javascript">
    $(document).ready(function() {

        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.ir-arriba').fadeIn();
            } else {
                $('.ir-arriba').fadeOut();
            }
        });

        $('.ir-arriba').click(function() {
            $("html, body").animate({
                scrollTop: 0
            }, 600);
            return false;
        });

    });
</script>

<?php
//Si pulsamos el botón cerrar sesión, nos recoge la acción aquí descrita.
//Cierra la sesión y nos manda a la página principal
if ((@$_GET['action'] == "logout")) {
    session_destroy();
}


//}else{
//echo "Debes estar registrado para poder ver el foro";
//}


?>

<script>
    // PREVISUALIZACION DE LA IMAGEN PARA EL GRUPO
    document.getElementById("file_foro").onchange = function(e) {
        // CREAMOS EL OBJETO DE LA CLASE FILEREADER
        let reader = new FileReader();

        // LEEMOS EL ARCHIVO SUBIDO Y SE LO PASAMOS A NUESTRO FILEREADER
        reader.readAsDataURL(e.target.files[0]);

        // LE DECIMOS QUE CUANDO ESTE LISTO EJECUTE EL CODIGO INTERNO
        reader.onload = function() {
            let preview = document.getElementById('preview'),
                image = document.createElement('img');

            image.src = reader.result;

            preview.innerHTML = '';
            preview.append(image);
        };
    }

    addEventListener('load', inicio, false);

    function inicio() {
        document.getElementById('num_grupo').addEventListener('change', cambioNum, false);
    }

    function cambioNum() {
        document.getElementById('num').innerHTML = document.getElementById('num_grupo').value;
    }
</script>