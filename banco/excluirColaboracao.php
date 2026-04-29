<?php
include_once 'conectaBD.php';
session_start();

$id_colaboracao = $_POST['id'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? 1;

if (!$id_colaboracao) {
    echo "Erro: ID da colaboração não fornecido.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql_del_tags = "DELETE FROM tb_colaboracao_tag WHERE fk_id_colaboracao = :id";
    $conn->prepare($sql_del_tags)->execute([':id' => $id_colaboracao]);

    $sql_del_cob = "DELETE FROM tb_colaboracao WHERE id_colaboracao = :id";
    $conn->prepare($sql_del_cob)->execute([':id' => $id_colaboracao]);

    $conn->commit();
    echo "Colaboração excluída com sucesso!"; 
    
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro no banco de dados: " . $e->getMessage();
}
?>
