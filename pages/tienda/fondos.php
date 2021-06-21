<?php
session_start();
require("../../config.php");
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="./src/style.css">
  <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">

  <!-- JAVASCRIPT -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="/pagina/proyecto/src/main.js"></script>
  <script src="src/main_tienda.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- ICONO -->
  <link rel="icon" type="image/png" href="../../img/ico.png">
  <title>Level UP - Tienda</title>
</head>

<body>
  <!--Navbar-->
  <?php include "../../nav.php"; ?>

  <?php
  //Dinero Usuario
  $sql_dinero = 'SELECT * FROM user WHERE usuario = :usuario';

  $stmt_dinero = $conn->prepare($sql_dinero);
  $stmt_dinero->bindParam(':usuario', $user_on);
  $stmt_dinero->execute();
  $resultado_dinero = $stmt_dinero->fetch();

  $cartera_usuario = $resultado_dinero['money']; //Dinero del usuario
  $id_usuario = $resultado_dinero['id']; //Id del usuario actual

  //Tienda
  /*$sql_tienda = 'SELECT * FROM shop WHERE id_usuario = :usuario';

  $stmt_tienda = $conn->prepare($sql_tienda);
  $stmt_tienda->bindParam(':usuario', $id_on);
  $stmt_tienda->execute();
  $resultado_tienda = $stmt_tienda->fetch();*/
  ?>

  <!--Barra tienda-->
  <section class="bandacategorias">
    <div class="banda flex">
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <ul class="lista ">
          <li class="lis00">
            <ul id="interlista" class="lista2">
              <li><a href="http://localhost/Pagina/proyecto/pages/tienda/inicio_tienda.php">Inicio</a>
              </li>
              <li> <a class="link_actual" href="">Foro</a>
              </li>
              <li><a href="#">Tienda</a>
              </li>
              <li><a href="#">Stream</a>
              </li>
              <li><a href="#">Comentarios</a>
              </li>
              <li><a href="#">General</a>
                <ul class="lista3">
                  <li><a href="#">Fondos</a>
                  </li>
                  <li><a href="#">Color de tus comentarios</a>
                  </li>
                  <li><a href="#">Color de tu username</a>
                  </li>
                </ul>
              </li>
              <li><a href="#" title="Marks">Monedas</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div id="main">
        <button class="openbtn" onclick="openNav()">☰ Categorías</button>
      </div>
      <span id="mensaje">¡ÚLTIMAS OFERTAS DE VERANO! Ofrecemos un 20% de descuento para comprar los últimos fondos</span>
      <?php
      if (@$_SESSION['id_logueado']) {
      ?>
        <span id="mensaje">Monedero: <span class="precio"><?php echo $resultado_dinero['money']; ?></span> <img class="doguecoin" src="../../img/Doguecoin.png"> </span>
      <?php
      }
      ?>
      <div></div>
    </div>
  </section>

  <!--Fondos-->
  <div class="main">
    <h1 class="title_fondos">FORO</h1>
    <ul class="cards">

      <!--PHP PARA MOSTRAR FONDOS-->
      <?php
      //Productos
      $sql_inventory = 'SELECT * FROM inventory WHERE page = "foro"';

      $stmt_inventory = $conn->prepare($sql_inventory);
      $stmt_inventory->execute();

      while (($resultado_inventory = $stmt_inventory->fetch())) {

        $id_producto = $resultado_inventory['id_inventory'];

        //Consultar si el producto está comprado
        $sql_producto = 'SELECT * FROM pocket WHERE id_usuario = :id_usuario AND id_producto = :id_producto';

        $stmt_producto = $conn->prepare($sql_producto);
        $stmt_producto->bindParam(':id_usuario', $id_usuario);
        $stmt_producto->bindParam(':id_producto', $id_producto);
        $stmt_producto->execute();
        $resultado_producto = $stmt_producto->fetch();

      ?>
        <li class="cards_item">
          <div class="card">
            <div class="card_image"><img src="<?php echo $resultado_inventory['file_product']; ?>"></div>
            <div class="card_content">
              <h2 class="card_title"><?php echo $resultado_inventory['name_product']; ?></h2>
              <p class="card_text"><?php echo $resultado_inventory['description']; ?></p>
              <?php
              if (isset($resultado_producto['id_producto'])) {
                if ($resultado_producto['status'] == 0) {
              ?>
                  <button onclick="seleccionar(<?php echo $resultado_inventory['id_inventory']; ?>, <?php echo $id_on ?>, '<?php echo $resultado_inventory['page']; ?>');" class="btn card_btn">Seleccionar Fondo <span class="precio"> </span><img class="doguecoin" src="../../img/check_tienda.png"></button>

                <?php
                } else {
                ?>
                  <button onclick="quitar(<?php echo $resultado_inventory['id_inventory']; ?>, <?php echo $id_on ?>, '<?php echo $resultado_inventory['page']; ?>');" class="btn card_btn">Quitar Fondo <span class="precio"> </span><img class="doguecoin" src="../../img/cancel.png"></button>
                <?php
                }
              } else {
                ?>
                <button onclick="comprar(<?php echo $resultado_inventory['id_inventory']; ?>, <?php echo $id_on ?>, <?php echo $resultado_inventory['price']; ?>, '<?php echo $resultado_inventory['page']; ?>' );" class="btn card_btn">Comprar <span class="precio"><?php echo $resultado_inventory['price']; ?></span> <img class="doguecoin" src="../../img/Doguecoin.png"></button>
              <?php
              }
              ?>
            </div>
          </div>
        </li>
      <?php
      }
      ?>
    </ul>
  </div>

</body>

<!--SCRIPT MENÚ CATEGORÍAS-->
<script>
  function openNav() {
    document.getElementById("mySidebar").style.overflow = "visible";
    document.getElementById("mySidebar").style.width = "250px";

  }

  function closeNav() {
    document.getElementById("mySidebar").style.overflow = "hidden";
    document.getElementById("mySidebar").style.width = "0";
  }
</script>

</html>