<?php
include_once 'conectaBD.php';
session_start();

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;
$titulo = $_POST['titulo'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$tags = $_POST['tags'] ?? [];

if (empty($descricao) || empty($tipo)) {
    echo "Tipo ou descrição não enviados.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql = "INSERT INTO tb_colaboracao (nm_titulo_colaboracao, ds_colaboracao, fk_id_tipo_colaboracao, fk_id_usuario_criador) 
            VALUES (:titulo, :descricao, :id_tipo, :id_usuario)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':id_tipo', $tipo);
    $stmt->bindParam(':id_usuario', $fk_id_usuario);
    $stmt->execute();

    $id_colaboracao = $conn->lastInsertId();

    if (!empty($tags)) {
        foreach ($tags as $tag) {
            // Verifica se já existe
            $sql_check_tag = "SELECT id_tag FROM tb_tag WHERE nm_tag = :tag";
            $stmt_check = $conn->prepare($sql_check_tag);
            $stmt_check->bindParam(':tag', $tag);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
                $id_tag = $row['id_tag'];
            } else {
                $sql_insert_tag = "INSERT INTO tb_tag (nm_tag) VALUES (:tag)";
                $stmt_insert_tag = $conn->prepare($sql_insert_tag);
                $stmt_insert_tag->bindParam(':tag', $tag);
                $stmt_insert_tag->execute();
                $id_tag = $conn->lastInsertId();
            }

            $sql_cob_tag = "INSERT INTO tb_colaboracao_tag (fk_id_colaboracao, fk_id_tag) 
                            VALUES (:id_colaboracao, :id_tag)";
            $stmt_cob_tag = $conn->prepare($sql_cob_tag);
            $stmt_cob_tag->bindParam(':id_colaboracao', $id_colaboracao);
            $stmt_cob_tag->bindParam(':id_tag', $id_tag);
            $stmt_cob_tag->execute();
        }
    }

    $conn->commit();
    echo "Colaboração feito com sucesso!";
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
?>
