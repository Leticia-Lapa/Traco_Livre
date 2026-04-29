<?php
include_once 'conectaBD.php';
session_start();

$id_publicacao = $_POST['id_publicacao'] ?? null;
$id_usuario_logado = $_SESSION['id_usuario'] ?? 1;

if (!$id_publicacao) {
    echo "Erro: ID da publicação não fornecido.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql_del_imgs = "DELETE FROM tb_imagens_publicacao WHERE fk_id_publicacao = :id_publicacao";
    $conn->prepare($sql_del_imgs)->execute([':id_publicacao' => $id_publicacao]);

    $sql_del_tags = "DELETE FROM tb_publicacao_tag WHERE fk_id_publicacao = :id_publicacao";
    $conn->prepare($sql_del_tags)->execute([':id_publicacao' => $id_publicacao]);

    $sql_del_estilo = "DELETE FROM tb_estilo_publicacao WHERE fk_id_publicacao = :id_publicacao";
    $conn->prepare($sql_del_estilo)->execute([':id_publicacao' => $id_publicacao]);

    $sql_del_pub = "DELETE FROM tb_publicacao WHERE id_publicacao = :id_publicacao";
    $conn->prepare($sql_del_pub)->execute([':id_publicacao' => $id_publicacao]);

    $conn->commit();
    echo "Publicação excluída com sucesso!"; 
    
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro no banco de dados: " . $e->getMessage();
}
?>
