<?php
require("../../config.php");
$id_post_guardar = @$_POST['id_post_guardar'];
$usuario_actual = @$_POST['id_logueado'];
$insert_favourite = "
                                                INSERT INTO favourite 
                                                    (id_post, id_user)
                                                VALUES
                                                    (:id_post, :id_user)";

$stmt_favourite = $conn->prepare($insert_favourite);
$stmt_favourite->bindParam(':id_post', $id_post_guardar);
$stmt_favourite->bindParam(':id_user', $usuario_actual);

//Recorrer favoritos del usuario para evitar que agregue repetidos

$sql_repetido = 'SELECT * FROM favourite WHERE id_user = :id_usuario';
$stmt_repetido = $conn->prepare($sql_repetido);
$stmt_repetido->bindParam(':id_usuario', $usuario_actual);
$stmt_repetido->execute();

$repetida = false;

while (($resultado_repetido = $stmt_repetido->fetch()) && $repetida == false) {
    if($resultado_repetido['id_post'] == $id_post_guardar){
        $repetida = true;
        echo "No puedes guardar este post porque ya lo tienes guardado";
    }
}

if($repetida == false){
    echo "¡Post guardado!";
    $stmt_favourite->execute();
}

?>