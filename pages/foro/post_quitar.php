<?php
require("../../config.php");
$id_post_quitar = $_POST['id_post_quitar'];
$usuario_actual = $_POST['id_logueado'];
$sql_favourite = 'DELETE FROM favourite WHERE id_post = :id_post AND id_user = :id_user';

$stmt_favourite = $conn->prepare($sql_favourite);
$stmt_favourite->bindParam(':id_post', $id_post_quitar);
$stmt_favourite->bindParam(':id_user', $usuario_actual);
$stmt_favourite->execute();

echo "<script>window.location.replace('favoritos.php');</script>";


?>