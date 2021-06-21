<?php
session_start();
require("../../config.php");
$id = $_GET['id']; //Id del post 
//if(@$_SESSION['usuario_logueado']){
?>

<!DOCTYPE html>
<html>

<head>
    <!--CSS-->
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">
    <link rel="stylesheet" type="text/css" href="./src/topic.css">
    <link rel="stylesheet" type="text/css" href="./src/style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/@lyket/widget@latest/dist/lyket.js?apiKey=f65b5071dc6713b579994261bea53a"></script>
    <script src="/pagina/proyecto/pages/chat/app/main.js"></script>

    <!-- ICONO -->
    <link rel="icon" type="image/png" href="../../img/ico.png">
    <title>Level UP - Post</title>
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

    <!--Llamada a la base de datos-->
    <?php
    //VARIABLES GLOBALES
    $titulo_tema;
    $contenido_tema;
    $creador_tema;
    $fecha_tema;
    $imagen_perfil;
    $url_youtube;

    //Recoger datos de la base de datos TOPICS
    $sql = 'SELECT * FROM topics WHERE topic_id = :id';

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $resultado = $stmt->fetch();

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

    //Recoger datos de la base de datos USERS para el creador del POST
    $sql2 = 'SELECT * FROM user WHERE usuario = :usuario';

    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindParam(':usuario', $resultado['topic_creator']);
    $stmt2->execute();
    $resultado2 = $stmt2->fetch();

    $id_user = $resultado2['id'];
    $imagen_perfil = $resultado2['profile_pic'];

    //Recoger datos de la base de datos COMMENTS
    $sql_comment = 'SELECT * FROM comments WHERE post_id = :id_topic';

    $stmt_comment = $conn->prepare($sql_comment);
    $stmt_comment->bindParam(':id_topic', $_GET['id']);
    $stmt_comment->execute();

    ?>

    <section class="general">
        <!--En caso de que el post sea imagen se usa este-->
        <div class="content">
            <div class="conimagen">
                <div class="hud">
                    <!--La parte del titulo del post -->
                    <div class="titulo">
                        <div class="posteador">
                            <div class="imgperf">
                                <?php echo "<a class='ay' href='../../usuarios/profile.php?id=$id_user'><img class='imgperf' src='../../" . $resultado2['profile_pic'] . "'></a>"; ?>
                            </div>
                            <div class="nombrepos">
                                <?php echo $resultado['topic_creator'] ?>
                                <span class="tiempo"> <?php echo tiempo($tiempo) ?> </span>
                            </div>
                            <div class="nombreforo">
                                <button class="foro botones tex">Memes</button>
                            </div>
                        </div>
                        <div class="head">
                            <?php echo "<a class='ay titulos' href='topic.php?id=$id'>" . $titulo_tema . "</a>"; //2 
                            $url_php = "$id";
                            ?>
                            <script>
                                var url_compartir = "<?php echo $url_php ?>";
                            </script>
                            <button onclick="compartir(url_compartir)" class="icoforo btnshare botones">
                                <div class="share">
                                    <img src="/pagina/proyecto/img/share.png" />
                                    <span class="space tex1">Compartir</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    <!--El contenido en sí -->
                    <div class="post">
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
                            echo "<a class='ay escrito' href='topic.php?id=$id'><div class='texto tex'> <span id='texto' class='textito'>" . $contenido_tema . "</span> </div> </a>";
                        }
                        ?>
                    </div>
                    <!--Botones para interacuar-->
                    <div class="botoncitos">
                        <div class="votaciones">
                            <!-- minimal settings -->
                            <div class="box__button__container">
                                <div data-lyket-type="updown" data-lyket-id="my-<?php echo $id."up"; ?>-post" data-lyket-template="Chevron" data-lyket-color-text="white" data-lyket-color-primary="#73CBCB" data-lyket-color-secondary="#f93e3e" data-lyket-color-icon="white"></div>
                            </div>
                        </div>

                        <!-- Guardar publicaciones a favoritos -->
                        <button class="icoforo btnsave botones" onclick="guardar(<?php echo @$resultado['topic_id'];  ?>)">
                            <div class="guardar">
                                <img src="/pagina/proyecto/img/favoritos.png" />
                                <form method="post">
                                    <span class="space tex1"><input class="sav botones tex1" type="submit" name="submit_favourite" value="Guardar" /></span>
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
                                        window.location.reload();
                                    });
                            }
                        </script>
                    </div>
                    <?php
                    if (@$_SESSION['id_logueado']) {
                    ?>
                        <!--Comentar-->
                        <div class="comentar">
                            <div class="nombre">
                                <span class="tex"> <b class="nombrepos"><?php echo @$_SESSION['nombre_logueado'] ?></b> cuéntanos tu opinión</span>
                            </div>
                            <div>
                                <form method="POST" class="areatext">
                                    <textarea name="contenido" class="crearcoment"></textarea>
                                    <button onclick="notificar(<?php echo $_GET['id']; ?>);" class="botoncom" name="submit">Comentar</button>
                                </form>
                            </div>
                        </div>
                    <?php
                    } else {
                    }
                    ?>
                    <!--ENVIAR COMENTARIOS-->
                    <?php

                    $usuario = @$_SESSION['nombre_logueado'];
                    $topic = @$_GET['id'];
                    $comentario = @$_POST['contenido'];

                    if (isset($_POST['submit'])) {

                        $insert = "
                                                INSERT INTO comments 
                                                    (user_name, post_id, comment_content)
                                                VALUES
                                                    (:usuario, :topic, :comentario)";

                        $stmt3 = $conn->prepare($insert);
                        $stmt3->bindParam(':usuario', $usuario);
                        $stmt3->bindParam(':topic', $topic);
                        $stmt3->bindParam(':comentario', $comentario);
                        $stmt3->execute();
                        echo "<script>window.location.replace('topic.php?id=$topic');</script>";
                    }
                    ?>

                    <?php
                    while (($resultado_comment = $stmt_comment->fetch())) {
                        //Recoger datos de la base de datos USERS para los usuarios que comenten
                        $sql_users_comment = 'SELECT * FROM user WHERE usuario = :nombre';
                        $stmt_users_comment = $conn->prepare($sql_users_comment);
                        $stmt_users_comment->bindParam(':nombre', $resultado_comment['user_name']);
                        $stmt_users_comment->execute();
                        while (($resultado_users_comment = $stmt_users_comment->fetch())) {
                            $id_nombre = $resultado_users_comment['id'];
                            
                            //Calcular tiempo de las publicaciones

                            $tiempo_comment = $resultado_comment['hora'];

                            if (!function_exists('tiempo')) {
                                function tiempo($tiempo_comment)
                                {
                                    $tiempo_comment = strtotime($tiempo_comment);
                                    $curTime = time();
                                    $timeElapsed = $curTime - $tiempo_comment;
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
                    ?>
                            <div class="separator">
                                <!--Linea gris que separa los comentarios del sitio donde comentas-->
                            </div>
                            <div class="comment">
                                <div class="fila">
                                    <div class="imgperf" id="come">
                                        <?php echo "<a class='ay' href='../../usuarios/profile.php?id=$id_nombre'><img class='imgperf' src='../../" . $resultado_users_comment['profile_pic'] . "'></a>";  ?>
                                    </div>
                                    <div class="comentario">
                                        <div>
                                            <span class="usr nombrepos"><?php echo $resultado_users_comment['usuario'] ?></span>
                                            <span class="tiempo"><?php echo tiempo($tiempo_comment) ?></span>
                                        </div>
                                        <div class="escrito">
                                            <?php echo $resultado_comment['comment_content'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="botoncitos">
                                    <div class="votaciones">
                                        <!-- minimal settings -->
                                        <div class="box__button__container">
                                            <div data-lyket-type="updown" data-lyket-id="my-<?php echo $resultado_comment['comment_id']."up"; ?>-post" data-lyket-color-text="white" data-lyket-color-primary="#73CBCB" data-lyket-color-secondary="#f93e3e" data-lyket-color-icon="white"></div>
                                        </div>
                                    </div>


                                    <!-- RESPONDER -->

                                    <button class="icoforo botones" onclick="document.getElementById('respuesta').style.display='block'">
                                        <div class="comentarios">
                                            <img src="/pagina/proyecto/img/share.png" />
                                            <span class="space">Responder</span>
                                        </div>
                                    </button>

                                    <!-- Ventana POP UP de RESPONDER -->
                                    <div id="respuesta" class="w3-modal">
                                        <div class="popup w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                                            <!-- Parte superior de la ventana -->
                                            <div class="w3-center "><br>
                                                <!--Botón cerrar ventana -->
                                                <span onclick="document.getElementById('respuesta').style.display='none'" class="w3-button w3-large w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                                                <!--Título de la ventana -->
                                                <h1 class="Titulo_PopUp">Escribir comentario</h1>
                                            </div>

                                            <!-- Contenido de la ventana -->
                                            <div class="w3-container ">
                                                <div class="w3-section">
                                                    <form method="POST" class="postenedor">
                                                        <span class="txtt">Enviar respuesta</span>
                                                        <textarea class="postbox" name="respuesta" cols="30" rows="10"></textarea>
                                                        <input class="btnenviar botones" type="submit" name="submit_respuesta" value="Enviar">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PHP para recoger las respuestas de los usuarios -->
                                    <?php
                                    $id_comentario = $resultado_comment['comment_id'];
                                    $name_usuario = @$_SESSION['nombre_logueado'];
                                    $respuesta = @$_POST['respuesta'];
                                    if (isset($_POST['submit_respuesta'])) {

                                        $insert_response = "
                                                        INSERT INTO response 
                                                            (id_comment, user_name, response_comment)
                                                        VALUES
                                                            (:id_c, :user_n, :respon_comment)";

                                        $stmt_response = $conn->prepare($insert_response);
                                        $stmt_response->bindParam(':id_c', $id_comentario);
                                        $stmt_response->bindParam(':user_n', $name_usuario);
                                        $stmt_response->bindParam(':respon_comment', $respuesta);
                                        $stmt_response->execute();
                                        echo "<script>window.location.replace('topic.php?id=$topic');</script>";
                                    }
                                    ?>

                                    <!-- GUARDAR -->

                                    <button class="icoforo btnsave botones">
                                        <div class="guardar">
                                            <img src="/pagina/proyecto/img/favoritos.png" />
                                            <span class="space">Guardar</span>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!--PHP MOSTRAR RESPUESTAS-->
                            <?php
                            $sql_response = 'SELECT * FROM response WHERE id_comment = :id_comment';

                            $stmt_response = $conn->prepare($sql_response);
                            $stmt_response->bindParam(':id_comment', $resultado_comment['comment_id']);
                            $stmt_response->execute();


                            while (($resultado_response = $stmt_response->fetch())) {
                            ?>

                                <!--PHP MOSTRAR RESPUESTAS-->



                                <div class="separator">
                                    <!--Linea gris que separa los comentarios del sitio donde comentas-->
                                </div>
                                <div class="comment">
                                    <div class="fila">
                                        <div class="imgperf" id="come">
                                            <?php echo "<a class='ay' href='../../usuarios/profile.php?id=$id_nombre'><img class='imgperf' src='../../" . $resultado_users_comment['profile_pic'] . "'></a>";  ?>
                                        </div>
                                        <div class="comentario">
                                            <div>
                                                <span class="usr"><?php echo $resultado_users_comment['usuario'] ?></span>
                                                <span class="tiempo"><?php echo $resultado_comment['hora'] ?></span>
                                            </div>
                                            <div class="escrito">
                                                <?php echo $resultado_response['response_comment']; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="botoncitos">
                                        <div class="votaciones">
                                            <div class="like">
                                                <button class="icoforo botones"><img src="/pagina/proyecto/img/like.png" /></button>
                                            </div>
                                            <div class="votos">
                                                <span class="nvoto">0</span>
                                            </div>
                                            <div class="nolike">
                                                <button class="icoforo botones"><img src="/pagina/proyecto/img/dislike.png" /></button>
                                            </div>
                                        </div>
                                    </div>

                        <?php
                            }
                        }
                    }
                        ?>
                                </div>
                </div>
            </div>
            <?php
            /*
    //Si el id no existe
    $id = $_GET['id'];
    if($_GET['id']){
        $selectid = "SELECT * FROM topics WHERE topic_id LIKE :id";
        $stmt = $conn->prepare($selectid);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $resultado = $stmt->fetch();

        //Si encuentra el usuario muestra sus datos
        if(!empty($resultado)){

        //Enlazar el perfil del usuario que ha publicado el topic
        $id_usuario = $resultado['topic_creator']; // -> De momento es la manera fácil
        



?>

    <!--DATOS DEL POST-->
    <h1><?php echo $resultado['topic_name'] //Nombre del post?></h1>
    <p><?php echo $resultado['topic_content'] //Contenido?></p>
    <p><?php echo $resultado['topic_creator'] //Creador?></p>
    <p><?php echo "<a href='profile.php?id=$id_usuario'>".$resultado['topic_creator']."</a>";?></p>
    <p><?php echo $resultado['date'] //Fecha de publicación?></p>

<?php
    //Si no encuentra ni el usuario ni la página
        }else{
            echo "No encontrado :(";
        }

    }else{
        header("Location: post.php");
    }

*/ ?>
    </section>
</body>

</html>