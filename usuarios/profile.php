<?php
session_start();
//Detectar usuarios por ID para ver si existen
require("../config.php");
$id = $_GET['id'];

if (@$_GET['id']) {
  $selectid = "SELECT * FROM user WHERE id LIKE :id";
  $stmt = $conn->prepare($selectid);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $resultado = $stmt->fetch();
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./src/style_profile.css">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>

    <!-- ICONO -->
    <link rel="icon" type="image/png" href="../img/ico.png">
    <title>Level UP - <?php echo $resultado['usuario'] ?></title>
  </head>

  <body>
    <!--Navbar-->
    <?php include "../nav.php"; ?>

    <?php
    //Si encuentra el usuario muestra sus datos
    if (!empty($resultado)) {
    ?>

      <!-- DATOS DEL USUARIO -->

      <!-- Banner -->
      <div class="seccion_banner">
        <div class="banner">
          <?php echo "<img src='../" . $resultado['banner'] . "'>"; ?>
          <div class="degradado">
          </div>
        </div>

        <!-- Nombre y Título -->
        <div class="cabecera">
          <div class="datos_usuario">
            <h3 class="nombre_usuario"><?php echo $resultado['usuario'] ?></h3>
          </div>

          <!-- Avatar usuario -->
          <div class="perfil_usuario">
            <div class="imagen_usuario">
              <?php echo "<img src='../" . $resultado['profile_pic'] . "'>"; ?>
            </div>
          </div>

          <!-- Pestañas información usuario -->
          <!-- UTILIZACIÓN DE CLASES PREDEFINIDAD IMPORTADAS -->
          <div class="menu_pestañas">
            <ul id="Tabla_principal" class="lista_opciones">
              <li class="lista_opciones_items">
                <button type="button" class="boton_item boton_activado" onclick="abrir_ventana(this, 'Informacion')">
                  <i class="fa fa-user"></i> <span>Información</span>
                </button>
              </li>
              <li class="lista_opciones_items">
                <button type="button" class="boton_item" onclick="abrir_ventana(this, 'Publicaciones')">
                  <i class="fa fa-file-text-o"></i> <span>Publicaciones</span>
                </button>
              </li>
              <li class="lista_opciones_items">
                <button type="button" class="boton_item" onclick="abrir_ventana(this, 'Comentarios')">
                  <i class="fa fa-comment-o"></i> <span>Comentarios</span>
                </button>
              </li>
              <li class="lista_opciones_items">
                <button type="button" class="boton_item" onclick="abrir_ventana(this, 'Opciones')">
                  <i class="fa fa-cog"></i> <span>Opciones</span>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Contenido primera pestaña (Mostrar enlaces y descripción) -->
      <div class="paginas_desplegables">
        <section id="Informacion" class="seccion_ventana boton_activado" data-type="animacion_ventana">
          <h1 class="titulo_ventana">Perfil</h1>
          <div class="contenido_ventana">
            <div class="fila">
              <div class="fila_100">
                <div class="grupo_contenido icono_contenido">
                  <label for="" class="titulo_etiqueta_contenido"><i class="fa fa-user-circle" id="user_icon"></i> Descripción</label>
                  <p><?php echo $resultado['description'] ?> </p>
                </div>
              </div>
            </div>
            <div class="fila">
              <div class="fila_50">
                <div class="grupo_contenido icono_contenido">
                  <label for="" class="titulo_etiqueta_contenido"><i class="fa fa-steam-square" id="steam_icon"></i> Steam</label>
                  <p><a href="<?php echo $resultado['steam'] ?>">Cuenta Steam <?php echo $resultado['usuario'] ?></a></p>
                </div>
              </div>
              <div class="fila_50">
                <div class="grupo_contenido icono_contenido">
                  <label for="" class="titulo_etiqueta_contenido"><i class="fa fa-youtube-play" id="youtube_icon"></i> Youtube</label>
                  <p><a href="<?php echo $resultado['youtube'] ?>">Cuenta Youtube <?php echo $resultado['usuario'] ?></a></p>
                </div>
              </div>
            </div>
            <div class="fila">
              <div class="fila_50">
                <div class="grupo_contenido icono_contenido">
                  <label for="" class="titulo_etiqueta_contenido"><i class="fa fa-twitter" id="twitter_icon"></i> Twitter</label>
                  <p><a href="<?php echo $resultado['twitter'] ?>">Cuenta Twitter <?php echo $resultado['usuario'] ?></a></p>
                </div>
              </div>
              <div class="fila_50">
                <div class="grupo_contenido icono_contenido">
                  <label for="" class="titulo_etiqueta_contenido"><i class="fa fa-twitch" id="twich_icon"></i> Twich</label>
                  <p><a href="<?php echo $resultado['twich'] ?>">Cuenta Twich <?php echo $resultado['usuario'] ?></a></p>
                </div>
              </div>
            </div>
            <?php
            //Si el usuario no es el mismo que el de perfil, no puede cambiar nada
            if (@$_SESSION['id_logueado'] == $id) {
            ?>
              <h1 class="titulo_imagenes">Cambiar Imágenes</h1>
              <div class="contenido_ventana">
                <div class="fila">
                  <div class="fila_50">
                    <form method="POST" enctype="multipart/form-data">
                      <div class="grupo_contenido icono_contenido">
                        <label class="titulo_etiqueta_contenido">Avatar</label>
                        <label for="file-avatar" class="boton-file-avatar"> Subir Avatar</label>
                        <input id="file-avatar" type="file" name="file" />
                        <input type="submit" name="upload" class="boton_guardar_imagen" value="Guardar">
                      </div>
                    </form>
                  </div>
                  <div class="fila_50">
                    <form method="POST" enctype="multipart/form-data">
                      <div class="grupo_contenido icono_contenido">
                        <label class="titulo_etiqueta_contenido">Banner</label>
                        <label for="file-banner" class="boton-file-avatar"> Subir Banner</label>
                        <input id="file-banner" type="file" name="file2" />
                        <input type="submit" name="upload_banner" class="boton_guardar_imagen" value="Guardar">
                      </div>
                    </form>
                  </div>
                </div>
              <?php
              //Si el usuario no es el mismo que el de perfil, no puede cambiar nada
            } else {
            }
              ?>
              </div>
        </section>

        <!-- Contenido segunda pestaña -->
        <section id="Publicaciones" class="seccion_ventana" data-type="animacion_ventana">
          <h1 class="titulo_ventana">Publicaciones realizadas</h1>
          <div class="contenido_ventana">
            <div class = "con">
            <?php
            //Mostrar temas del usuario
            $sql_topic_user = 'SELECT * FROM topics WHERE topic_creator = :usuario_perfil';

            $stmt_topic_user = $conn->prepare($sql_topic_user);
            $stmt_topic_user->bindParam(':usuario_perfil', $resultado['usuario']);
            $stmt_topic_user->execute();

            while (($resultado_topic_user = $stmt_topic_user->fetch())) {
              $id_topic = $resultado_topic_user['topic_id']; //Saco los id de los topics

              //Variables globales
              $titulo_tema = $resultado_topic_user['topic_name'];

              $contenido_tema = $resultado_topic_user['topic_content'];

              $imagen_foro = $resultado_topic_user['image_foro'];

              $url_youtube = $resultado_topic_user['url'];

              echo "<div class = 'publication espac'><a href='../pages/foro/topic.php?id=$id_topic'>" . $resultado_topic_user['topic_name'] . "</a></div>";
              if ($contenido_tema == null && $url_youtube == null) {
                echo "<a class='ay' href='../pages/foro/topic.php?id=$id_topic'><img class='imagen_foro' src='../pages/foro/img_foro/" . $imagen_foro . "'></a>";
                //Si mandas un vídeo
              } else if ($contenido_tema == null && $imagen_foro == null) {
                //Te consigue la ID del vídeo de Youtube
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url_youtube, $match);
                $youtube_id = $match[1];

                //Ponemos el embed, pero usando la id del vídeo que el usuario ha mandado
                echo "<div class='container'><iframe class='responsive-iframe' style= 'height: 480px; width: 750px';  src='https://www.youtube.com/embed/" . $youtube_id . "' frameborder='0' allowfullscreen></iframe></div>";
                //Si mandas un texto
              } else {
                echo "<a class='ay' href='../pages/foro/topic.php?id=$id_topic'><div class='texto tex'> <span id='texto' class='textito'>" . $contenido_tema . "</span> </div> </a>";
              }
              echo "<div class = 'separator'></div>";
            }
            ?>
            </div>
          </div>
        </section>

        <!-- Contenido tercera pestaña -->
        <section id="Comentarios" class="seccion_ventana" data-type="animacion_ventana">
          <h1 class="titulo_ventana">Comentarios Realizados</h1>
          <div class="contenido_ventana comentarioss">
            <?php
            //Recoger datos de la base de datos COMMENTS
            $sql_comment = 'SELECT * FROM comments WHERE user_name = :usuarioperfil';

            $stmt_comment = $conn->prepare($sql_comment);
            $stmt_comment->bindParam(':usuarioperfil', $resultado['usuario']);
            $stmt_comment->execute();

            while (($resultado_comment = $stmt_comment->fetch())) {
              $post_id = $resultado_comment['post_id'];
              echo "<a href='../pages/foro/topic.php?id=$post_id'>" . $resultado_comment['comment_content'] . "</a>";
              echo "<div class = 'separator'></div>";
            }
            ?>

          </div>
        </section>


        <!-- Contenido cuarta pestaña -->
        <section id="Opciones" class="seccion_ventana" data-type="animacion_ventana">
          <h1 class="titulo_ventana">Cambiar ajustes</h1>
          <div class="contenido_ventana ">
            <?php
            //El usuario es el mismo que el del perfil
            if (@$_SESSION['id_logueado'] == $id) {

              //Muestra los datos del usuario
              $sql_datos_opciones = 'SELECT * FROM user WHERE id = :id';

              $stmt_opciones = $conn->prepare($sql_datos_opciones);
              $stmt_opciones->bindParam(':id', $_SESSION['id_logueado']);
              $stmt_opciones->execute();
              $resultado_opciones = $stmt_opciones->fetch();



            ?>
              <!--Opciones para cambiar cosas del perfil-->
              <form method="POST">
                <div class="fila_100">
                  <div class="grupo_contenido icono_contenido">
                    <label for="" class="titulo_etiqueta_contenido">Cambiar Descripción</label>
                    <input type="text" class="caja_contenido" name="descripcion" placeholder="Cambia tu descripción" minlength="40" maxlength="75" value="<?php echo $resultado_opciones['description'] ?>">
                  </div>
                </div>
                <div class="fila">
                  <div class="fila_50">
                    <div class="grupo_contenido icono_contenido">
                      <label for="" class="titulo_etiqueta_contenido">Cambiar cuenta Steam</label>
                      <input type="text" class="caja_contenido" name="steam_url" placeholder="Enlaza tu cuenta de Steam" value="<?php echo $resultado_opciones['steam'] ?>">
                    </div>
                  </div>
                  <div class="fila_50">
                    <div class="grupo_contenido icono_contenido">
                      <label for="" class="titulo_etiqueta_contenido">Cambiar cuenta Youtube</label>
                      <input type="text" class="caja_contenido" name="youtube_url" placeholder="Enlaza tu cuenta de Youtube" value="<?php echo $resultado_opciones['youtube'] ?>">
                    </div>
                  </div>
                </div>
                <div class="fila">
                  <div class="fila_50">
                    <div class="grupo_contenido icono_contenido">
                      <label for="" class="titulo_etiqueta_contenido">Cambiar cuenta Twitter</label>
                      <input type="text" class="caja_contenido" name="twitter_url" placeholder="Enlaza tu cuenta de Twitter" value="<?php echo $resultado_opciones['twitter'] ?>">
                    </div>
                  </div>
                  <div class="fila_50">
                    <div class="grupo_contenido icono_contenido">
                      <label for="" class="titulo_etiqueta_contenido">Cambiar cuenta Twich</label>
                      <input type="text" class="caja_contenido" name="twich_url" placeholder="Enlaza tu cuenta de Twich" value="<?php echo $resultado_opciones['twich'] ?>">
                    </div>
                  </div>
                </div>
                <input type="submit" class="boton_guardar" name="bt_guardar" value="Guardar Cambios">
              </form>
          </div>
          <!-- Contenido de botón Reportar -->
          <?php
            } else {
              //EL USUARIO ES DISTINTO AL DEL PERFIL
              if (@$_SESSION['id_logueado']) {
          ?>
            <p class="problemas">¿Has tenido algún problema con este usuario?</p>
            <div class="centrado">
              <button class="boton_reportar">Reportar</button>
            </div>
            <div class="ventana_formulario">
              <div class="contenedor formulario">
                <button class="cerrar_formulario">X</button>
                <form id="my-form" novalidate="novalidate">
                  <div>
                    <div>
                      <h1 class="titulo_formulario">Reportar Usuario</h1>
                    </div>
                  </div>
                  <div class="fila_50">
                    <div class="grupo_contenido">
                      <label for="" class="titulo_etiqueta_contenido">Nombre de Usuario</label>
                      <input type="text" class="caja_contenido" value="<?php echo $resultado['usuario'] ?>" disabled>
                    </div>
                  </div>
                  <div class="fila_50">
                    <div class="grupo_contenido">
                      <label for="" class="titulo_etiqueta_contenido">Asunto</label>
                      <select name="example" class="caja_contenido">
                        <option>Contenido inapropiado</option>
                        <option>Contenido explícito</option>
                        <option>Contenido spam</option>
                        <option>Contenido sensible</option>
                      </select>
                    </div>
                  </div>
                  <div class="fila_50">
                    <div class="grupo_contenido">
                      <label for="caja_contenido">Mensaje</label>
                      <textarea cols="80" rows="10" name="mensaje" maxlength="500" minlength="50"></textarea>
                    </div>
                  </div>
                  <input type="submit" class="boton_guardar" name="bt_guardar" value="Enviar">
              </div>
              </form>
            </div>
      </div>
<?php
              } else {
                echo "Debes estar registrado para poder acceder a este apartado";
              }
            }
          } else {
            echo "El usuario no existe";
          }
        } else {
          echo "ERROR";
        }
?>
</div>
</section>
</div>

<?php
//Cambiar Perfil
if (isset($_POST['bt_guardar'])) {

  $descripcion = trim($_POST['descripcion']);
  $steam_link = trim($_POST['steam_url']);
  $youtube_link = trim($_POST['youtube_url']);
  $twitter_link = trim($_POST['twitter_url']);
  $twich_link = trim($_POST['twich_url']);

  $update = "
      UPDATE user SET
          description = :des,
          steam = :steam,
          youtube = :youtube,
          twitter = :twitter,
          twich = :twich
      WHERE 
          id = :id";
  $stmt = $conn->prepare($update);
  $stmt->bindParam(':des', $descripcion);
  $stmt->bindParam(':steam',  $steam_link);
  $stmt->bindParam(':youtube',  $youtube_link);
  $stmt->bindParam(':twitter',  $twitter_link);
  $stmt->bindParam(':twich',  $twich_link);
  $stmt->bindParam(':id', $_SESSION['id_logueado']);
  $stmt->execute();
  echo "<script>window.location.replace('profile.php?id=$id');</script>";
}


//Cambiar imagen de perfil PHP
if (isset($_POST['upload'])) {
  $file_name = $_FILES['file']['name'];
  $file_type = $_FILES['file']['type'];
  $file_size = $_FILES['file']['size'];
  $file_tem_loc = $_FILES['file']['tmp_name'];
  $file_store = "../avatares/" . $file_name;

  if (move_uploaded_file($file_tem_loc, $file_store)) {
    $file_store = "/avatares/" . $file_name;
    $updateavatar = "UPDATE user SET profile_pic='" . $file_store . "' WHERE id LIKE :id";
    $stmt = $conn->prepare($updateavatar);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo "<script>window.location.replace('profile.php?id=$id');</script>";
    exit;
  } else {
    echo '<script>alert("Sube primero una imagen")</script>';
  }
}

//Cambiar banner de perfil PHP
if (isset($_POST['upload_banner'])) {
  $file_name = $_FILES['file2']['name'];
  $file_type = $_FILES['file2']['type'];
  $file_size = $_FILES['file2']['size'];
  $file_tem_loc = $_FILES['file2']['tmp_name'];
  $file_store = "../banners/" . $file_name;

  if (move_uploaded_file($file_tem_loc, $file_store)) {
    $file_store = "/banners/" . $file_name;
    $updatebanner = "UPDATE user SET banner='" . $file_store . "' WHERE id LIKE :id";
    $stmt = $conn->prepare($updatebanner);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo "<script>window.location.replace('profile.php?id=$id');</script>";
    exit;
  } else {
    echo '<script>alert("Sube primero una imagen")</script>';
  }
}

?>

  </body>

  <script>
    function abrir_ventana(obj, target) {
      //Mostrar seccion de tabs
      $("[data-type='animacion_ventana']").removeClass("boton_activado");
      $("#" + target).addClass("boton_activado");

      //Marcar boton de menu activo
      $("#Tabla_principal .boton_item").removeClass("boton_activado");
      $(obj).addClass("boton_activado");
    }

    //Abrir y cerrar el botón de Reportar
    $(document).ready(function() {

      $('.boton_reportar').click(function() {
        $('.ventana_formulario').show();
      });
      $('.cerrar_formulario').click(function() {
        $('.ventana_formulario').hide();
      });

      //Abrir y cierra las ventanas de pestañas
      $(document).mouseup(function(e) {
        var contenedor = $(".formulario");
        var form = $(".ventana_formulario");

        //JQUERY -> .is = si está seleccionado / .has me permite comprobar si una ventana está cerrada al no mostrar divs
        if (!contenedor.is(e.target) && contenedor.has(e.target).length === 0) { //Permite cambiar de ventana y esconder la otra
          form.hide();
        }
      });


    });
  </script>

  </html>