<?php
    session_start();
    require("../../config.php");
    //if(@$_SESSION['usuario_logueado']){
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">
    <link rel="stylesheet" type="text/css" href="./src/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <link rel="icon" href="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fupload.wikimedia.org%2Fwikipedia%2Fcommons%2Fthumb%2F5%2F53%2FPok%25C3%25A9_Ball_icon.svg%2F1026px-Pok%25C3%25A9_Ball_icon.svg.png&f=1&nofb=1">
    <title>Foro</title>
</head>

<body>
<?php

    //VARIABLES GLOBALES
     $titulo_tema;
     $contenido_tema;   
     $creador_tema;
     $fecha_tema; 

    //Recoger datos de la base de datos TOPICS
    $sql='SELECT * FROM topics'; //haces una busqueda de lo que hay en la tabla

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    while(($resultado = $stmt->fetch())){
        $id = $resultado['topic_id'];

        $titulo_tema = $resultado['topic_name'];
        ?><h1>Nombre del tema</h1><?php
        echo "<a href='topic.php?id=$id'>".$resultado['topic_name']."</a>";  

        $contenido_tema = $resultado['topic_content'];
        ?><h1>Contenido tema</h1><?php
        echo  $contenido_tema; 

        $creador_tema = $resultado['topic_creator'];
        ?><h1>Creador tema</h1><?php
        echo  $creador_tema; 

        $fecha_tema = $resultado['date'];
        ?><h1>Fecha tema</h1><?php
        echo  $fecha_tema; 

        
    }

?>

</body>

</html>
