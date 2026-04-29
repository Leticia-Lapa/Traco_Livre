<?php
include_once 'conectaBD.php';
session_start();

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;
$titulo = $_POST['titulo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$fonte = $_POST['corFonte'] ?? '#FFFFFF'; // padrão branco
$capa = $_POST['corCapa'] ?? '#FFFFFF';   // padrão branco
$url = $_POST['imgUrl'] ?? null;

if (empty($titulo) || empty($descricao)) {
    echo "Título ou descrição não enviados.";
    exit;
}

try {
    $conn->beginTransaction();

    // Sempre insere todos os campos, imagem pode ser nula
    $sql = "INSERT INTO tb_pasta 
        (nm_titulo_pasta, ds_pasta, corFonte, corCapa, img_capa, fk_id_usuario_criador) 
        VALUES (:titulo, :descricao, :fonte, :capa, :url, :id_usuario)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':fonte', $fonte);
    $stmt->bindParam(':capa', $capa);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':id_usuario', $fk_id_usuario);
    $stmt->execute();

    $conn->commit();
    echo "Pasta criada com sucesso!";
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
?>
