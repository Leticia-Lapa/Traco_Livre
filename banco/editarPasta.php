<?php
include_once 'conectaBD.php';
session_start();

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_pasta = $_POST['id_pasta'] ?? null;
$titulo = $_POST['titulo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$fonte = $_POST['corFonte'] ?? '';
$capa = $_POST['corCapa'] ?? '';
$url = $_POST['imgUrl'] ?? null;

if (empty($titulo) || empty($descricao)) {
    echo "Título ou descrição não enviados.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql_update_pasta = "UPDATE tb_pasta SET nm_titulo_pasta = :titulo, ds_pasta = :descricao, corFonte = :fonte, corCapa = :capa, img_capa = :url WHERE id_pasta = :id";
    $stmt_update_pasta = $conn->prepare($sql_update_pasta);
    $stmt_update_pasta->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':id' => $id_pasta,
        ':fonte' => $fonte,
        ':capa' => $capa,
        ':url' => $url
    ]);

    $conn->commit();
    echo "Pasta atualizada com sucesso!";
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
