<?php
//SESION INICIA?
session_start();
require "../../config.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./src/style.css">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>


    <!-- ICONO -->
    <link rel="icon" type="image/png" href="../../img/ico.png">
    <title>Level UP - Stream</title>
</head>

<body>
    <!--Navbar-->
    <?php include "../../nav.php"; ?>

    <main>
        <section class="pagstream flex">
            <!--Título-->
            <div class="top flex">
                <div class="orden flex">
                    <span class="title_stream">Stream de usuarios</span>
                    <?php
                    if (@$_SESSION['id_logueado']) {
                    ?>
                        <!--Botón para enviar streamings -->
                        <button class="boton_stream botones" onclick="document.getElementById('streaming').style.display='block'">
                            ¡Comparte tu canal!
                        </button>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <!-- PHP para recoger los datos del tema creado -->
            <div class="videos flex">
                <?php
                $url_stream = @$_POST['streaming_url'];
                if (isset($_POST['submit'])) {

                    $insert = "
                                                        INSERT INTO streaming 
                                                            (video_stream, id_usuario)
                                                        VALUES
                                                            (:url, :usuario)";

                    $stmt_stream = $conn->prepare($insert);
                    $stmt_stream->bindParam(':url', $url_stream);
                    $stmt_stream->bindParam(':usuario', $_SESSION['id_logueado']);
                    $stmt_stream->execute();
                    echo "<script>window.location.replace('Stream.php');</script>";
                }
                ?>

                <!--Vídeos Twich-->
                <?php
                $sql_str = 'SELECT * FROM streaming order by id_stream desc';
                $stmt_str = $conn->prepare($sql_str);
                $stmt_str->execute();
                while (($resultado_stream = $stmt_str->fetch())) {
                ?>
                    <div class="twitch">
                        <div class="twitch-video">
                            <iframe src="https://player.twitch.tv/?channel=<?php echo $resultado_stream['video_stream']; ?>&parent=localhost&autoplay=false" frameborder="0" scrolling="no" allowfullscreen="true" height="100%" width="100%">
                            </iframe>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
        <!-- Ventana POP UP de enviar Streaming -->
        <div id="streaming" class="w3-modal">
            <div class="popup w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                <!-- Parte superior de la ventana -->
                <div class="w3-center "><br>
                    <!--Botón cerrar ventana -->
                    <span onclick="document.getElementById('streaming').style.display='none'" class="w3-button w3-large w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                    <!--Título de la ventana -->
                    <h1 class="Titulo_PopUp">Envíanos tu Canal</h1>
                </div>

                <!-- Contenido de la ventana -->
                <div class="w3-container ">
                    <div class="w3-section">
                        <form method="POST" class="postenedor">
                            <span class="txtt">Nombre de tu canal Twich</span>
                            <input class="postbox postin" type="text" name="streaming_url">
                            <input class="btnenviar botones" type="submit" name="submit" value="Compartir">
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
</body>

</html>