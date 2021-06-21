<?php 
    $ip = "localhost";
    $user = "root";
    $pass = "";
    $nombreDB = "pagina";

    try{
        $conn = new PDO("mysql:host=$ip;dbname=$nombreDB", $user, $pass);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e) {
        echo "ERROS: ".$e->getMessage();
    }
?>