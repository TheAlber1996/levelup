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
    <title>Level UP - Normas</title>
</head>

<body>
    <!--Navbar-->
    <?php include "../../nav.php"; ?>

    <!--Título-->
    <h1 class="title_normas">NORMAS - LEVEL UP</h1>

    <!--Normas-->
    <div class="text_center">
        <span class="content_entrada">Level UP es una página de humor. Cuando subas memes, envíes comentarios, noticias, juegos o lo uses de cualquier otra manera debes saber que el contenido y el comportamiento ofensivo, irrespetuoso, explícito o sexual están estrictamente prohibidos.
            Si no sigues las reglas descritas a continuación, tu cuenta será baneada:</span>
    </div>

    <h2 class="subtitles">Reglas de Contenido</h2>

    <div class="rules">
        Cuando subas memes, juegos o noticias ten en cuenta que lo siguiente NO está permitido:
        <ol>
            <li>1. <strong>Desnudos, pornografía o contenido sexual explícito.</strong></li>
            <li>2. <strong>Violencia, acoso o bullying.</strong></li>
            <li>3. <strong>Incitación al odio o insultos.</strong></li>
            <li>4. <strong>Suplantación de identidad o contenido engañoso.</strong></li>
            <li>5. <strong>Contenido protegido por copyright.</strong></li>
            <li>6. <strong>Información personal o confidencial.</strong></li>
            <li>7. <strong>Contenido repetido o subidas de otros usuarios.</strong></li>
        </ol>
    </div>

    <h2 class="subtitles">Reglas de Comentarios</h2>

    <div class="rules">
        Respeta al resto de los usuarios cuando envíes comentarios. NO está permitido:
        <ol>
            <li><strong>Comentarios sexuales explícitos.</strong></li>
            <li><strong>Violencia, acoso o bullying.</strong></li>
            <li><strong>Incitación al odio o insultos.</strong></li>
            <li><strong>Suplantación de identidad o contenido engañoso.</strong></li>
            <li><strong>Información personal o confidencial.</strong></li>
            <li><strong>Spam o comentarios repetidos</strong></li>
            <li><strong>Links a sitios con contenido pornográfico, ofensivo, irrespetuoso, explícito o que infrinja copyrights.</strong></li>
        </ol>
    </div>

    <h2 class="subtitles">Reglas de Avatar</h2>

    <div class="rules">
        Tu avatar y tus datos te identifican en Level UP. Cuando los edites, ten en cuenta que lo siguiente NO está permitido:
        <ol>
            <li><strong>Desnudos, pornografía o contenido sexual explícito.</strong></li>
            <li><strong>Violencia, acoso o bullying.</strong></li>
            <li><strong>Incitación al odio o insultos.</strong></li>
            <li><strong>Suplantación de identidad o comportamiento engañoso. No uses el nombre, avatar o el estato de otros usuarios para hacerte pasar por ellos.</strong></li>
            <li><strong>Contenido protegido por copyright.</strong></li>
            <li><strong>Información personal o confidencial.</strong></li>
        </ol>
        Si tienes dudas sobre las reglas, por favor <a href="#"><strong>contacta con nosotros</strong></a>.
    </div>

</body>

</html>