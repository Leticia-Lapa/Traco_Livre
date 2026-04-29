<?php
include_once 'conectaBD.php';
session_start();

$id_usuario = $_SESSION['id_usuario'];
$id_conversa = $_POST['id_conversa'] ?? null;
$mensagem = trim($_POST['mensagem'] ?? '');

if (!$id_conversa || $mensagem === '') {
    echo "Dados inválidos";
    exit;
}

try {

    $sql = "INSERT INTO tb_mensagem 
            (fk_id_conversa, ds_mensagem, dt_mensagem, fk_id_usuario_criador)
            VALUES 
            (:id_conversa, :mensagem, CURRENT_TIMESTAMP, :id_usuario)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_conversa' => $id_conversa,
        ':mensagem' => $mensagem,
        ':id_usuario' => $id_usuario
    ]);

    echo $conn->lastInsertId();

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
