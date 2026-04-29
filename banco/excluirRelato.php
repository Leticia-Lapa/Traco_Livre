<?php
include_once 'conectaBD.php';
session_start();

$id_relato = $_POST['id'] ?? null;
$id_usuario_logado = $_SESSION['id_usuario'] ?? 1;

if (!$id_relato) {
    echo "Erro: ID do relato não fornecido.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql_del_imgs = "DELETE FROM tb_imagens_relato WHERE fk_id_relato = :id";
    $conn->prepare($sql_del_imgs)->execute([':id' => $id_relato]);

    $sql_del_tags = "DELETE FROM tb_relato_tag WHERE fk_id_relato = :id";
    $conn->prepare($sql_del_tags)->execute([':id' => $id_relato]);

    $sql_del_rel = "DELETE FROM tb_relato WHERE id_relato = :id";
    $conn->prepare($sql_del_rel)->execute([':id' => $id_relato]);

    $conn->commit();
    echo "Relato excluído com sucesso!"; 
    
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro no banco de dados: " . $e->getMessage();
}
?>
