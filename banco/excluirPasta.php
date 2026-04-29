<?php
include_once 'conectaBD.php';
session_start();

$id_pasta = $_POST['id_pasta'] ?? null;
$id_usuario_logado = $_SESSION['id_usuario'] ?? 1;

if (!$id_pasta) {
    echo "Erro: ID da pasta não fornecido.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql_del_pas = "DELETE FROM tb_pasta WHERE id_pasta = :id";
    $conn->prepare($sql_del_pas)->execute([':id' => $id_pasta]);

    $conn->commit();
    echo "Pasta excluída com sucesso!"; 
    
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro no banco de dados: " . $e->getMessage();
}
?>
