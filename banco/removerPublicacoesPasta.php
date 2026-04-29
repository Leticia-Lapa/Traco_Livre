<?php
include_once 'conectaBD.php';
session_start();

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;
$id_pasta = $_POST['id_pasta'] ?? null;
$publicacoes = $_POST['publicacoes'] ?? [];

if (!$id_pasta || empty($publicacoes)) {
    echo 'Erro: Dados insuficientes para processar a requisição.';
    exit;
}

try {
    $conn->beginTransaction();

    $placeholders = implode(',', array_fill(0, count($publicacoes), '?'));
    $delete_conteudo_pasta = "DELETE FROM tb_conteudo_pasta 
                              WHERE fk_id_pasta = ? AND fk_id_publicacao IN ($placeholders)";
    $stmt_delete = $conn->prepare($delete_conteudo_pasta);
    $parametros = array_merge([$id_pasta], $publicacoes);
    $stmt_delete->execute($parametros);

    $conn->commit();
    echo "Publicação(ões) removida(s) com sucesso!";
    exit;

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
?>
