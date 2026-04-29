<?php
include_once 'conectaBD.php';

$usuario = $_POST['usuario'] ?? '';

$sql = "SELECT id_usuario FROM tb_usuario WHERE nm_nome_usuario = :usuario LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "EXISTE";
} else {
    echo "LIVRE";
}
?>