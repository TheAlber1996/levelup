<?php
//SESION INICIA?
session_start();
require "../config.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../pages/foro/src/style.css">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/@lyket/widget@latest/dist/lyket.js?apiKey=f65b5071dc6713b579994261bea53a"></script>

    <!-- ICONO -->
    <link rel="icon" type="image/png" href="../img/ico.png">
    <title>LevelUP - Favoritos</title>
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
            echo "<body background='../pages/tienda/" . $resultado_inventory['file_product'] . "'>";
            $fondo = true;
        }
    }
    if (!$fondo) {
        echo "<body background='../img/Wallpaper_inicio.jpg'>";
    }
?>
    <!--Navbar-->
    <?php include "../nav.php"; ?>

    <h1 class="title_favoritos">Lista de favoritos</h1>

    <!-- Mostrar post favoritos del usuario-->

    <?php
    //Rescatar favoritos
    $sql_favorito = 'SELECT * FROM favourite WHERE id_user = :id_usuario ORDER BY id_favourite DESC';
    $stmt_favorito = $conn->prepare($sql_favorito);
    $stmt_favorito->bindParam(':id_usuario', $_SESSION['id_logueado']);
    $stmt_favorito->execute();

    if ($stmt_favorito->rowCount() > 0) {
        while (($resultado_favorito = $stmt_favorito->fetch())) {

            //VARIABLES FAVORITOS

            $id_favorito = $resultado_favorito['id_favourite']; //Id favorito
            $id_post = $resultado_favorito['id_post']; //Id del post
            $id_user = $resultado_favorito['id_user']; //Id del usuario que le ha dado a favorito

            //Rescatar los posts
            $sql_post = 'SELECT * FROM topics WHERE topic_id = :id_post';
            $stmt_post = $conn->prepare($sql_post);
            $stmt_post->bindParam(':id_post', $id_post);
            $stmt_post->execute();
            $resultado_posts = $stmt_post->fetch();

            //VARIABLES POSTS
            $id_topic = $resultado_posts['topic_id']; //Id del post

            //Calcular tiempo de las publicaciones

            $tiempo = $resultado_posts['date'];

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

            $creador_tema = $resultado_posts['topic_creator']; //Nombre del creador del post
            $name_topic = $resultado_posts['topic_name']; //Id del post
            $contenido_tema = $resultado_posts['topic_content'];
            $imagen_foro = $resultado_posts['image_foro'];
            $url_youtube = $resultado_posts['url'];

            //Rescatar los usuarios
            $sql_usuario = 'SELECT * FROM user WHERE id = :id_usuario'; //haces una busqueda para la foto de perfil de usuarios
            $stmt_usuario = $conn->prepare($sql_usuario);
            $stmt_usuario->bindParam(':id_usuario', $_SESSION['id_logueado']);
            $stmt_usuario->execute();
            $resultado_usuarios = $stmt_usuario->fetch();

            //VARIABLES USUARIOS
            $id_usuarios = $resultado_usuarios['id']; //id del usuario
            $avatar_usuarios = $resultado_usuarios['profile_pic']; //id del usuario

            //ATRIBUTOS PARA LOS AVATARES DE PERFIL
            $sql2 = 'SELECT * FROM user WHERE usuario = :usuario'; //haces una busqueda para la foto de perfil de usuarios

            $stmt2 = $conn->prepare($sql2);
            $stmt2->bindParam(':usuario', $creador_tema);
            $stmt2->execute();
            $resultado2 = $stmt2->fetch();
            $id_user = $resultado2['id'];
            $imagen_perfil = $resultado2['profile_pic'];

    ?>
            <main class="main">
                <section class="general">
                    <div class="contenedor">
                        <div class="content">
                            <div class="solotexto">
                                <div class="hud">
                                    <div class="titulo">
                                        <div class="posteador">
                                            <div class="imgperf">
                                                <?php echo "<a class='ay' href='profile.php?id=$id_user'><img class='imgperf' src='../" . $imagen_perfil . "'></a>";
                                                ?>
                                            </div>
                                            <?php echo "<div class='nombrepos'><a class='ay' href='profile.php?id=$id_user'>" . $creador_tema . "</a>   <span class = 'tiempo'>" . tiempo($tiempo) . "</span> </div>";
                                            ?>
                                            <div class="nombreforo">
                                                <button class="foro botones tex">Cosas graciosas</button>
                                            </div>
                                        </div>

                                        <!-- Compartir Temas -->
                                        <div class="head">

                                            <?php echo "<a class='ay titulos' href='../pages/foro/topic.php?id=$id_topic'>" . $name_topic . "</a>";
                                            ?>
                                            <button onclick="compartir(<?php echo $id_topic ?>)" class="icoforo btnshare botones tex1">
                                                <div class="share">
                                                    <img src="/pagina/proyecto/img/share.png" />
                                                    <span class="space">Compartir</span>
                                                </div>
                                            </button>
                                        </div>

                                    </div>
                                    <div class="post">
                                        <button class="bot botones">
                                            <?php
                                            //Si mandas una imagen
                                            if ($contenido_tema == null && $url_youtube == null) {
                                                echo "<a class='ay' href='../pages/foro/topic.php?id=$id_topic'><img class='imagen_foro' src='../pages/foro/img_foro/" . $imagen_foro . "'></a>";
                                                //Si mandas un vídeo
                                            } else if ($contenido_tema == null && $imagen_foro == null) {
                                                //Te consigue la ID del vídeo de Youtube
                                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url_youtube, $match);
                                                $youtube_id = $match[1];

                                                //Ponemos el embed, pero usando la id del vídeo que el usuario ha mandado
                                                echo "<div class='container'><iframe class='responsive-iframe' style= 'height: 480px; width: 750px'; src='https://www.youtube.com/embed/" . $youtube_id . "' frameborder='0' allowfullscreen></iframe></div>";
                                                //Si mandas un texto
                                            } else {
                                                echo "<a class='ay' href='../pages/foro/topic.php?id=$id_topic'><div class='texto'> <span id='texto' class='textito'>" . $contenido_tema . "</span> </div> </a>";
                                            }


                                            ?>
                                        </button>
                                    </div>
                                    <div class="botoncitos">
                                        <div class="votaciones">
                                            <!-- minimal settings -->
                                            <div class="box__button__container">
                                                <div data-lyket-type="updown" data-lyket-id="my-<?php echo $id_post; ?>-post" data-lyket-template="Chevron" data-lyket-color-text="white" data-lyket-color-primary="#73CBCB" data-lyket-color-secondary="#f93e3e" data-lyket-color-icon="white"></div>
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

                                        <button class="icoforo botones">
                                            <div class="comentarios">
                                                <img src="/pagina/proyecto/img/comment.png" />
                                                <span class="space"><?php echo "<a class='ay tex1' href='topic.php?id=$id_topic'>" . $comentario . "</a>" ?></span>
                                            </div>
                                        </button>

                                        <!-- Guardar publicaciones a favoritos -->
                                        <button class="icoforo btnsave botones" onclick="quitar(<?php echo @$id_topic; ?>)">
                                            <div class="guardar">
                                                <img src="/pagina/proyecto/img/favoritos.png" />
                                                <form method="post">
                                                    <span><input class="space" type="submit" name="submit_favourite" value="Quitar" /></span>
                                                </form>
                                            </div>
                                        </button>

                                        <!-- PHP de favoritos -->
                                        <script>
                                            function quitar(id_post_quitar) {
                                                $.ajax({
                                                        url: 'http://localhost//Pagina/proyecto/pages/foro/post_quitar.php',
                                                        type: 'POST',
                                                        data: {
                                                            id_post_quitar: id_post_quitar,
                                                            id_logueado: <?php echo @$_SESSION['id_logueado'];  ?>
                                                        }
                                                    })
                                                    .done(function() {
                                                        window.location.replace('favoritos.php');
                                                    });
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </main>
        <?php

        }
    } else {
        ?> <h1 class="error">No has guardado todavía ninguna publicación</h1> <?php
                                                                            }






                                                                                ?>



</body>

</html>

<?php
//Si pulsamos el botón cerrar sesión, nos recoge la acción aquí descrita.
//Cierra la sesión y nos manda a la página principal
if ((@$_GET['action'] == "logout")) {
    session_destroy();
    header("Location: ../proyecto/index.php");
}
?>