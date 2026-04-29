<?php
include_once 'conectaBD.php';
session_start();

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;
$id_pasta = $_POST['id_pasta'] ?? null;
$publicacoes = $_POST['publicacoes'] ?? [];

// Validação básica para garantir que os dados necessários existem
if (!$id_pasta || empty($publicacoes)) {
    // Redireciona ou exibe uma mensagem de erro
    echo 'erro';
    exit;
}

try {
    $conn->beginTransaction();

    $sql = "INSERT INTO tb_conteudo_pasta (fk_id_pasta, fk_id_publicacao)
            VALUES (:id_pasta, :id_publicacao)";
    $stmt = $conn->prepare($sql);

    // Usa bindValue para associar o valor a cada iteração
    foreach ($publicacoes as $id_pub) {
        $stmt->bindValue(':id_pasta', $id_pasta);
        $stmt->bindValue(':id_publicacao', $id_pub);
        $stmt->execute();
    }

    $conn->commit();
    echo "Puplicação(ões) inserida(as) com sucesso!";
    exit;
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
?>
