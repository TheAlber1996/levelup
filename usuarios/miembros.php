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
    <link rel="stylesheet" type="text/css" href="./src/style_miembros.css">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>

    <!-- ICONO -->
    <link rel="icon" type="image/png" href="../img/ico.png">
    <title>Level UP - Usuarios</title>
</head>

<body>
    <!--Navbar-->
    <?php include "../nav.php"; ?>

    <h1 class="title_usuarios">Lista de usuarios registrados</h1>

    <!--FORMULARIO DE BÚSQUEDA -->
    <form class="formulario_busqueda" method="post">
        <input type="text" id="buscar" name="caja_busqueda" placeholder="Escribe aquí..." required>
        <input type="submit" value="BUSCAR" id="submit" name="boton_buscar">
    </form>

    <?php
    //Mostrar el total de usuarios del foro
    require("../config.php");
    if (strlen(trim(@$_POST['caja_busqueda'])) >= 1) {
        $sql_busqueda = 'SELECT * FROM user WHERE usuario LIKE :clave';
        $stmt = $conn->prepare($sql_busqueda);
        $stmt->bindParam(':clave', $clave);
        $clave = "%" . $_POST['caja_busqueda'] . "%";
    } else {
        $sql = 'SELECT * FROM user ORDER BY id DESC'; //haces una busqueda de lo que hay en la tabla
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    ?>

    <div class=general>
        <?php
        if ($stmt->rowCount() > 0) {
            while (($user = $stmt->fetch())) {
                $id = $user['id'];
        ?>
                <div id="contenedor">
                    <div id="usuarios">
                        <div id="banner">
                            <?php echo "<img src='../" . $user['banner'] . "'>"; ?></p>
                        </div>
                        <div id="foto_perfil">
                            <?php echo "<img src='../" . $user['profile_pic'] . "'>"; ?></p>
                        </div>
                        <div id="descripcion">
                            <h2 class="enlace_usuario"><?php echo "<a href='profile.php?id=$id'>" . $user['usuario'] . "</a>" ?></h2>
                            <p><?php echo $user['description'] ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            ?><h1 class='fallo'>No se ha encontrado usuarios que correspondan con tu búsqueda</h1><?php
                                                                                                }
                                                                                                    ?>

    </div>

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