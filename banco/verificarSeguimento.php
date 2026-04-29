<?php
include_once "conectaBD.php";
session_start();

$id_usuario = $_POST['idUsuario']; 
$id_outroUsuario = $_POST['idOutro']; 

$sql = "SELECT id_seguidor FROM tb_seguidores 
        WHERE fk_id_usuario_seguidor = :id_usuario 
        AND fk_id_usuario_seguido = :id_outro";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':id_outro' => $id_outroUsuario
]);

echo $stmt->rowCount() > 0 ? 1 : 0;
?>
