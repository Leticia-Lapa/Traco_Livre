<?php
include_once 'conectaBD.php';
session_start();

$id_colaboracao = $_POST['id_colaboracao'] ?? null;
$titulo = $_POST['titulo'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$tags = isset($_POST['tags']) ? (array)$_POST['tags'] : [];

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;

if (!$id_colaboracao || empty($descricao) || empty($tipo)) {
    echo "Dados insuficientes para atualizar.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql_update_cob = "UPDATE tb_colaboracao SET nm_titulo_colaboracao = :titulo, ds_colaboracao = :descricao, dt_hora_colaboracao = CURRENT_TIMESTAMP, fk_id_tipo_colaboracao = :tipo WHERE id_colaboracao = :id";
    $stmt_update_cob = $conn->prepare($sql_update_cob);
    $stmt_update_cob->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':id' => $id_colaboracao,
        ':tipo' => $tipo
    ]);

    $sql_tags_atuais = "SELECT t.nm_tag FROM tb_colaboracao_tag ct JOIN tb_tag t ON t.id_tag = ct.fk_id_tag WHERE ct.fk_id_colaboracao = :id";
    $stmt_tags_atuais = $conn->prepare($sql_tags_atuais);
    $stmt_tags_atuais->execute([':id' => $id_colaboracao]);
    $tags_atuais_db = $stmt_tags_atuais->fetchAll(PDO::FETCH_COLUMN);

    $tags_a_adicionar = array_diff($tags, $tags_atuais_db);
    $tags_a_remover = array_diff($tags_atuais_db, $tags);

    if (!empty($tags_a_remover)) {
        $ids_remover = [];
        foreach ($tags_a_remover as $nm_tag) {
            $stmt = $conn->prepare("SELECT id_tag FROM tb_tag WHERE nm_tag = :tag");
            $stmt->execute([':tag' => $nm_tag]);
            $ids_remover[] = $stmt->fetchColumn();
        }
         if (!empty($ids_remover)) {
            // Apenas prepara e executa se houver IDs para remover
            $placeholders = str_repeat('?,', count($ids_remover) - 1) . '?';
            $sql_delete = "DELETE FROM tb_colaboracao_tag WHERE fk_id_colaboracao = ? AND fk_id_tag IN ($placeholders)";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->execute(array_merge([$id_colaboracao], $ids_remover));
        }
    }

    foreach ($tags_a_adicionar as $nm_tag) {
        $stmt_check = $conn->prepare("SELECT id_tag FROM tb_tag WHERE nm_tag = :tag");
        $stmt_check->execute([':tag' => $nm_tag]);

        if ($stmt_check->rowCount() > 0) {
            $id_tag = $stmt_check->fetch(PDO::FETCH_ASSOC)['id_tag'];
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO tb_tag (nm_tag) VALUES (:tag)");
            $stmt_insert->execute([':tag' => $nm_tag]);
            $id_tag = $conn->lastInsertId();
        }

        $stmt_cob_tag = $conn->prepare("INSERT INTO tb_colaboracao_tag (fk_id_colaboracao, fk_id_tag) VALUES (:rel, :tag)");
        $stmt_cob_tag->execute([':rel' => $id_colaboracao, ':tag' => $id_tag]);
    }

    $conn->commit();
    echo "Colaboração atualizada com sucesso!";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
