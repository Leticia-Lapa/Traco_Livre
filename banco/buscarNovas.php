<?php
session_start();
include_once '../banco/conectaBD.php';

$id_conversa = (int) $_GET['id_conversa'];
$ultima_id = (int) $_GET['ultima_id'];

$sql = "SELECT id_mensagem, ds_mensagem, fk_id_usuario_criador
        FROM tb_mensagem
        WHERE fk_id_conversa = :id_conversa
        AND id_mensagem > :ultima_id
        ORDER BY id_mensagem ASC";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id_conversa' => $id_conversa,
    ':ultima_id' => $ultima_id
]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
