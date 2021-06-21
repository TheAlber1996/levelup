<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/pagina/proyecto/src/nav.css">
    <link rel="stylesheet" type="text/css" href="./src/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/pagina/proyecto/src/main.js"></script>
    <link rel="icon" href="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fupload.wikimedia.org%2Fwikipedia%2Fcommons%2Fthumb%2F5%2F53%2FPok%25C3%25A9_Ball_icon.svg%2F1026px-Pok%25C3%25A9_Ball_icon.svg.png&f=1&nofb=1">
    <title>Página web Videojuegos</title>
</head>

<body>
    <!-- BARRA DE NAVEGACIÓN -->
    <header>
        <nav class="navbar">
            <div class="topnav">
                <a class="inactivelink logo btnform" href="#home">Pagina</a>
                <a class="btnform" href="/pagina/proyecto/index.php">Inicio</a>
                <a class="btnform" href="../foro/Foro.php">Foro</a>
                <a class="btnform" href="../tienda/Tienda.php">Tienda</a>
                <a class="active btnform" href="../red social/Red_social.php">Red Social</a>
                <a class="btnform" href="../stream/Stream.php">Streaming</a>
                <div class="dropdown">
                    <button onclick="drop()" class="btndrop"></button>
                    <div id="midrop" class="imgdropdown">
                        <ul id="lista" class="list"> 
                            <li>
                                <a href="#news">Inciciar Sesión</a>
                            </li>
                            <li>
                                <a href="#contact">Registro</a>
                            </li>
                            <li>
                                <a href="#about">Perfil</a>
                            </li>
                            <li>
                                <a href="#about">Mis huevos</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- MAIN -->
    <main class="main">
        <section class="general">
            <!-- BARRA DE NAVEGACION ENTRE GRUPOS Y AMIGOS -->
            <nav class="amigos">
            </nav>

            <div class="chat">
                <!-- DIV DONDE SE VISUALIZARAN NOMBRE, FOTO DE GRUPO/USUARIO -->
                <div class="datos">
                    <img class="imgperfil" src="../../img/perfil.png">
                    <h2 class="nombre"> Nombre Grupo/Usuario <h2>
                </div>

                <!-- DIV DONDE SE VISUALIZARAN LOS MENSAJES ENVIADOS Y RECIBIDOS -->
                <div class="scroll">
                    <div class="mensajes">
                        <!-- CUANDO SE MUESTRE POR PANTALLA LOS MENSAJES DE OTROS USUARIOS EL DIV
                        PERTENECERA A LA CLASE OTHER -->
                        <div class="other">
                            <p> NOMBRE </p>
                            <p> El otro dia me comi unas gominolas <label class="hora">HORA</label></p>
                            <p> estaban to ricas jajajaja <label class="hora">HORA</label></p>
                            <p> xd <label class="hora">HORA</label></p>
                            <p> bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla <label class="hora">HORA</label></p>
                        </div>
                        
                        <!-- CUANDO SE MUESTRE POR PANTALLA TUS MENSAJES EL DIV
                        PERTENECERA A LA CLASE ME -->
                        <div class="me">
                            <p> Que gocho que eres <label class="hora">HORA</label></p>
                            <p> deja de comer <label class="hora">HORA</label></p>
                            <p> bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla <label class="hora">HORA</label></p>
                        </div>

                        <div class="other">
                            <p> NOMBRE </p>
                            <p> El otro dia me comi unas gominolas <label class="hora">HORA</label></p>
                            <p> estaban to ricas jajajaja <label class="hora">HORA</label></p>
                            <p> xd <label class="hora">HORA</label></p>
                            <p> bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla <label class="hora">HORA</label></p>
                        </div>
                        <div class="other">
                            <p> NOMBRE </p>
                            <p> Que gocho que eres <label class="hora">HORA</label></p>
                            <p> deja de comer <label class="hora">HORA</label></p>
                        </div>

                        <div class="other">
                            <p> NOMBRE </p>
                            <p> El otro dia me comi unas gominolas <label class="hora">HORA</label></p>
                            <p> estaban to ricas jajajaja <label class="hora">HORA</label></p>
                            <p> xd <label class="hora">HORA</label></p>
                            <p> bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla <label class="hora">HORA</label></p>
                        </div>
                        <div class="me">
                            <p> Que gocho que eres <label class="hora">HORA</label></p>
                            <p> deja de comer <label class="hora">HORA</label></p>
                            <p> bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla <label class="hora">HORA</label></p>
                        </div>
                    </div>
                </div>

                <!-- DIV DONDE SE VISUALIZARAN LA CAJA PARA ESCRIBIR Y EL BOTON ENVIAR -->
                <div class="escribir">
                    <input type="text" class="mensaje" placeholder="Escribe tu mensaje aquí">
                    <button class="bt_enviar">
                            <img src="/pagina/proyecto/img/send.png" />
                    </button>
                </div>
            </div>
        </section>
    </main>

    </body>
</html>