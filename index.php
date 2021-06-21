<?php
    //SESION INICIA?
    session_start();
    require "./config.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./src/inicio.css">
    <link rel="stylesheet" type="text/css" href="./src/nav.css">
    <link rel="stylesheet" type="text/css" href="./src/carrusel.css">
    
    <!-- JavaScript -->
    <script src="./lib/jquery.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <script src="/pagina/proyecto/pages/chat/app/main.js"></script>

    <!-- ICONO -->
    <link rel="icon" type="image/png" href="./img/ico.png">
    <title>Level UP - Inicio</title>
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
    $sql_producto = "SELECT * FROM pocket WHERE id_usuario = :id_usuario AND id_producto = :id_producto AND page = 'inicio' AND status = 1";

    $stmt_producto = $conn->prepare($sql_producto);
    $stmt_producto->bindParam(':id_usuario', $_SESSION['id_logueado']);
    $stmt_producto->bindParam(':id_producto', $id_producto);
    $stmt_producto->execute();
    $resultado_producto = $stmt_producto->fetch();

    if (isset($resultado_producto['id_producto'])) {
        echo "<body background='pages/tienda/" . $resultado_inventory['file_product'] . "'>";
        $fondo = true;
    }
}
if (!$fondo) {
    echo "<body background='img/4k-dark-wallpaper-12.jpg'>";
}
?>

<body>
    <!--Navbar-->
    <?php include "nav.php"; ?>
    <!--Contenido de la página-->
    <section class="diseccion">
        <div class="contenedor">
            <div class="left">
                <!--Carruseles de noticias-->
                <!--Noticias grandes de arriba-->
                <div class="noticia flex">
                    <button class="bots">
                        <div class="sliderNoticias fade" style="display: block;">
                            <img src="./img/E3img.jpg">
                            <div class="divtext"> 
                                <span class="colorgrey">
                                    Este 2021 no solo esta el E3... tambien nace Level<span class="up">UP</span>
                                </span>
                            </div>
                        </div>
                    </button>
                    <button class="bots">
                        <div class="sliderNoticias fade" style="display: none;">
                            <img src="./img/oso.gif">
                            <div class="divchatea"> 
                                <span class="colorgrey">
                                    Comienza a chatear con tus amigos en Level<span class="up">UP</span>, create un usuario y comienza a quemar tu teclado
                                </span>
                            </div>
                        </div>
                    </button>
                    <button class="bots">
                        <div class="sliderNoticias fade" style="display: none;">
                            <img src="./img/stalker.jpg">
                            <div class="divtext"> 
                                <span class="colorgrey">
                                    El juego del <span class="up">E3</span> - 22/abril/22
                                </span>
                            </div>
                        </div>
                    </button>
                    <!-- <div class="press">
                        <span class="titulo">Cosa guapa</span>
                        <span class="subtit">Cosa mass guapppa </span>
                    </div>-->
                    <!-- Signos < y > para cambiar de noticias -->
                    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1)">&#10095;</a>

                </div>
                <!--Noticias pequeñas de abajo-->
                <div class="secundario ">
                    <!--Carrusel izquierdo-->
                    <div class="tendencias flex">
                        <button class="bots">
                            <div class="sliderTendencias fade" style="display: block;">
                                <img src="./img/dark.jpg">
                                <div class="divtextTendencias"> 
                                    <span>
                                        alber
                                    </span>
                                </div>
                            </div>
                        </button>
                        <button class="bots">
                            <div class="sliderTendencias fade" style="display: none;">
                                <img src="./img/naruto.jpg">
                                <div class="divtextTendencias"> 
                                    <span>
                                        LolGamer
                                    </span>
                                </div>
                            </div>
                        </button>
                        <button class="bots">
                            <div class="sliderTendencias fade" style="display: none;">
                                <img src="./img/pixel.gif">
                                <div class="divtextTendencias"> 
                                    <span>
                                        Doraemon
                                    </span>
                                </div>
                            </div>
                        </button>
                        <button class="bots">
                            <div class="sliderTendencias fade" style="display: none;">
                                <img src="./img/bosque.png">
                                <div class="divtextTendencias"> 
                                    <span>
                                        El Cubano
                                    </span>
                                </div>
                            </div>
                        </button>
                        <button class="bots">
                            <div class="sliderTendencias fade" style="display: none;">
                                <img src="./img/poke.gif">
                                <div class="divtextTendencias"> 
                                    <span>
                                        Pikachu
                                    </span>
                                </div>
                            </div>
                        </button>
                        <a class="prev" onclick="plusSlide(-1)">&#10094;</a>
                        <a class="next" onclick="plusSlide(1)">&#10095;</a>
                    </div>
                    <!--Carrusel derecho-->
                    <div class="noticiasPeq flex">
                        <button class="bots">
                            <div class="sliderPeq fade" style="display: block;">
                                <img src="./img/ubi.jpg">
                                <div class="divtext"> 
                                    <span class="colorblack">
                                        12/06 - 21:00
                                    </span>
                                </div>
                            </div>
                        </button>
                        <button class="bots">
                            <div class="sliderPeq fade" style="display: none;">
                                <img src="./img/xbox.jpg">
                                <div class="divtext"> 
                                    <span class="colorblack">
                                        13/06 - 19:00
                                    </span>
                                </div>
                            </div>
                        </button>
                        <button class="bots">
                            <div class="sliderPeq fade" style="display: none;">
                                <img src="./img/nintendo.png">
                                <div class="divtext"> 
                                    <span class="colorblack">
                                        15/06 - 18:00
                                    </span>
                                </div>
                            </div>
                        </button>
                        <a class="prev" onclick="Slides(-1)">&#10094;</a>
                        <a class="next" onclick="Slides(1)">&#10095;</a>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="grupos ">
                    <?php
                        $grupoMostrado = array();
                        $i = 1;
                        /**
                         * PRIMERO VEMOS CUANTOS REGISTROS HAY EN LA TABLA GRUPO
                         * PARA PODER ELEGIR UN NUMERO RANDOM ENTRE EL NUMERO TOTAL
                         * DE REGISTROS DE LA TABLA GRUPO
                         */
                        $select_num = "SELECT COUNT(*) FROM grupo";
                        $stmt_num = $conn->prepare($select_num);
                        $stmt_num->execute();
                        $numero = $stmt_num->fetch();

                        /**
                         * AHORA VEMOS CUAL ES EL ID CON EL NUMERO MAS ALTO PARA REALIZAR EL RANDOM DESDE
                         * 1 HASTA LA ID MAS GRANDE
                         */
                        $select_num_id = "SELECT * FROM grupo ORDER BY id DESC LIMIT 1";
                        $stmt_num_id = $conn->prepare($select_num_id);
                        $stmt_num_id->execute();
                        $max_id = $stmt_num_id->fetch();

                        if($numero[0]<9){
                            $numGrupo = $numero[0];
                        } else{
                            $numGrupo = 9;
                        }

                        /**
                         * SE REPITE EL BUCLE DE BUSQUEDA DE GRUPOS HASTA UN NUMERO DE VECES DE 9
                         */
                        $ceroGrupo = true;
                        while($i<=$numGrupo){
                            $mostrar = true;
                            $random = rand (1, $max_id[0]);

                            $select_grupo = "SELECT * FROM grupo where id = :random";
                            $stmt_grupo = $conn->prepare($select_grupo);
                            $stmt_grupo->bindParam(':random', $random);
                            $stmt_grupo->execute();
                            $grupo = $stmt_grupo->fetch();

                            for($j=0; $j<sizeof($grupoMostrado); $j++) {
                                    
                                if($stmt_grupo->rowCount() == 0) {
                                    $mostrar = false;
                                } else if(($grupoMostrado[$j] == $grupo['id'])){
                                    $mostrar = false;
                                }
                            }
                            if($stmt_grupo->rowCount() == 0) {
                                $mostrar = false;
                            }

                            if($mostrar) {
                                $grupoMostrado[] = $grupo['id'];
                    ?>
                                <button class="grupo" <?php if (@$_SESSION['id_logueado']) { ?> onclick="solicitud(<?php echo $grupo['id']; ?>)" <?php } ?>>
                                    <div class="perfnom flex ">
                                        <img class="imgGrupo" src=".<?php echo $grupo['image']; ?>">
                                        <span class="gruponom"><?php echo $grupo['name']; ?></span>
                                        <span class="usuarios"><?php echo $grupo['num_user']; ?> / <?php echo $grupo['num_max']; ?></span>
                                    </div>
                                    <p class="descGrupo"><?php echo $grupo['description']; ?></p>
                                </button>
                    <?php
                                $i++;
                            }
                            $ceroGrupo = false;
                        }

                        if($ceroGrupo){
                            ?>
                                <div class="grupo cero">
                                    <span>No hay grupos para mostrar</span>
                                </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>