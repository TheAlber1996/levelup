<!-- AVISO - SI LA TABLA ESTA VACIA NO FUNCIONA, NECESITA ESTAR MINIMO RELLENA UNA FILA -->
<?php
include $_SERVER['DOCUMENT_ROOT']."/Pagina/proyecto/config.php";
$_SERVER['DOCUMENT_ROOT'].'/Pagina/proyecto/pages/sesion/validar.php';

/**
 * INCLUIMOS LA LIBRERIA PHPMAILER
 */
//require_once 'mailer/class.phpmailer.php';
//$mail = new PHPMailer(true);

//REGISTRARSE
//CONDICION QUE DETECTA CUANDO SE PULSA EL BOTON DE CREAR CUENTA
if(isset($_POST['bt_crear'])){

    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);
    $confirm_pass = trim($_POST['confirm_pass']);
    $email = trim($_POST['email']);

    //Cifrar contraseña
    $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT); //Me crea la "sal" de forma autómatica el último string

    if(strlen($user) >= 1){//LAS CONDICIONES SIGUIENTES SON PARA REVISAR QUE SE HAYA ESCRITO TODO
        if(strlen($pass) >= 1){
            if(strlen($confirm_pass) >= 1){ 
                /**
                 * METODO PARA COMPROBAR QUE ES UN EMAIL VALIDO
                 */
                $emailValido = false;

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailValido = true;
                } else {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            text: 'e-mail no valido',
                            background: '#2a2f32'
                        }).then((result) => {
                            document.getElementById('id02').style.display='block';
                        });
                    </script>
                    <?php
                }

                if($emailValido){

                    $registrado = false;

                    $selectEmail = "SELECT * FROM user";
                    $stmt = $conn->prepare($selectEmail);
                    $stmt->execute();

                    //COMPROBACION DE NO REPETIR EMAIL
                    //HAY QUE HACER OTRO PARA EL USUARIO
                    while(($fila = $stmt->fetch()) && (!$registrado)){
                        
                        /**
                         * COMPROBAR QUE EL USUARIO NO ESTA REPETIDO
                         */
                        if($user == $fila['usuario']){
                            $registrado = true;
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    text: 'El usuario introducido ya esta en uso',
                                    background: '#2a2f32'
                                }).then((result) => {
                                    document.getElementById('id02').style.display='block';
                                });
                            </script>
                            <?php
                        }

                        /**
                         * COMPROBAR QUE EL CORREO NO ESTA REPETIDO
                         */
                        if($email == $fila['email']){
                            $registrado = true;
                            //echo "El e-mail introducido ya esta en uso.";
                            //MEJORARAR MENSAJES DE MAIL REPETIDO
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    text: 'El e-mail introducido ya esta en uso',
                                    background: '#2a2f32'
                                }).then((result) => {
                                    document.getElementById('id02').style.display='block';
                                });
                            </script>
                            <?php
                        }
                    }

                    //COMPROBACION DE QUE LAS CONTRASEÑAS SEAN IGUALES
                    if(($pass == $confirm_pass) && !$registrado){

                        $insert = "
                            INSERT INTO user 
                                (usuario, contraseña, email, online, validate)
                            VALUES
                                (:user, :pass, :email, false, false)";
            
                        $stmt = $conn->prepare($insert);
                        $stmt->bindParam(':user', $user);
                        $stmt->bindParam(':pass', $pass_encriptada);
                        $stmt->bindParam(':email', $email);
                        $stmt->execute();

                        /**
                         * SE ENVIA UN CORREO DE VALIDACION AL USUARIO
                         * email - soportelevelup@gmail.com
                         * pass - up1level
                         */

                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Usuario creado',
                                text: 'Revisa su correo para validar la cuenta',
                                background: '#2a2f32'
                            }).then((result) => {
                            });
                        </script>
                        <?php

                    } else if (!$registrado){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                text: 'Las contraseñas no coinciden',
                                background: '#2a2f32'
                            }).then((result) => {
                                document.getElementById('id02').style.display='block';
                            });
                        </script>
                        <?php
                        //HAY QUE HACER UN MENSAJE CUANDO LAS CONTRASEÑAN CONCUERDAN
                    }
                }
            }
        }
    }
} else{
    //NADA - NO SE HA PULSADO EL BOTON.
}

//INICIAR SESION
//CONDICION QUE DETECTA CUANDO SE PULSA EL BOTON DE INICIAR SESION
if(isset($_POST['bt_iniciar'])){

    $online = false;

    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);

    if(strlen($user) >= 1){//LAS CONDICIONES SIGUIENTES SON PARA REVISAR QUE SE HAYA ESCRITO TODO
        if(strlen($pass) >= 1){

            $selectPass = "SELECT contraseña,email, usuario FROM user WHERE usuario LIKE :user OR email LIKE :user";
            $stmt = $conn->prepare($selectPass);
            $stmt->bindParam(':user', $user);
            $stmt->execute();

            while(($fila = $stmt->fetch()) && !$online){

                if(($user == $fila['usuario']) || ($user == $fila['email'])){//COMPRUEBO DE NUEVO QUE AMBOS VALORES SEAN IGUALES YA QUE CON LA QUERY NO SE DISTINGUEN MAYUS DE MINUS

                    if(password_verify($pass, $fila['contraseña'])){//DESENCRIPTO CONTRASEÑA CON MÉTODO HASH (VARCHAR 255 PARA QUE NO DE PROBLEMAS)
                        $online = true;
                        
                        //CAMBIAR EL DATO ONLINE A TRUE PARA APARECER EN LA PAGINA COMO ONLINE
                        $update = "
                            UPDATE user SET
                                online = :online
                            WHERE 
                                usuario = :user
                            OR
                                email = :user";
                        $stmt = $conn->prepare($update);
                        $stmt->bindParam(':user', $user);
                        $stmt->bindParam(':online', $online);
                        $stmt->execute();
                        
                        //PEDIMOS EL ID DEL USUARIO QUE ACABA DE INICIAR SESION
                        $selectId = "SELECT id, usuario FROM user WHERE usuario = :user";
                        $stmt = $conn->prepare($selectId);
                        $stmt->bindParam(':user', $user);
                        $stmt->execute();

                        $resultado = $stmt->fetch();

                        $_SESSION['id_logueado'] = $resultado['id'];
                        $_SESSION['usuario_logueado'] = $resultado['id'];
                        $_SESSION['nombre_logueado'] = $user;

                        echo "<script>window.location.replace('http://localhost/Pagina/proyecto/index.php');</script>";
                    }
                }
            }     
            
            if(!$online){
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        text: 'Contraseña o Usuario incorrectos',
                        background: '#2a2f32'
                    }).then((result) => {
                        document.getElementById('id01').style.display='block';
                    });
                </script>
                <?php
            }
            
        }
    }
}

?>