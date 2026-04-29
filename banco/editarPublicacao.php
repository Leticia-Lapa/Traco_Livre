<?php
include_once 'conectaBD.php';
session_start();

$id_publicacao = $_POST['id_publicacao'] ?? null;
$descricao = $_POST['descricao'] ?? '';
$estilo = $_POST['estilo'] ?? '';

$urls = isset($_POST['urls']) ? (array)$_POST['urls'] : [];
$tags = isset($_POST['tags']) ? (array)$_POST['tags'] : [];

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;

if (!$id_publicacao || empty($descricao) || empty($estilo)) {
    echo "Dados insuficientes para atualizar.";
    exit;
}

try {
    $conn->beginTransaction();

    $sql_update_pub = "UPDATE tb_publicacao SET ds_publicacao = :descricao WHERE id_publicacao = :id";
    $stmt_update_pub = $conn->prepare($sql_update_pub);
    $stmt_update_pub->execute([
        ':descricao' => $descricao,
        ':id' => $id_publicacao
    ]);

    $sql_update_estilo = "UPDATE tb_estilo_publicacao SET fk_id_estilo = :id_estilo WHERE fk_id_publicacao = :id_publicacao";
    $stmt_update_estilo = $conn->prepare($sql_update_estilo);
    $stmt_update_estilo->execute([
        ':id_estilo' => $estilo,
        ':id_publicacao' => $id_publicacao
    ]);


    $sql_tags_atuais = "SELECT t.nm_tag FROM tb_publicacao_tag pt JOIN tb_tag t ON t.id_tag = pt.fk_id_tag WHERE pt.fk_id_publicacao = :id";
    $stmt_tags_atuais = $conn->prepare($sql_tags_atuais);
    $stmt_tags_atuais->execute([':id' => $id_publicacao]);
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
            $sql_delete = "DELETE FROM tb_publicacao_tag WHERE fk_id_publicacao = ? AND fk_id_tag IN ($placeholders)";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->execute(array_merge([$id_publicacao], $ids_remover));
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

        $stmt_pub_tag = $conn->prepare("INSERT INTO tb_publicacao_tag (fk_id_publicacao, fk_id_tag) VALUES (:pub, :tag)");
        $stmt_pub_tag->execute([':pub' => $id_publicacao, ':tag' => $id_tag]);
    }

    $sql_urls_atuais = "SELECT url FROM tb_imagens_publicacao WHERE fk_id_publicacao = :id";
    $stmt_urls_atuais = $conn->prepare($sql_urls_atuais);
    $stmt_urls_atuais->execute([':id' => $id_publicacao]);
    $urls_atuais_db = $stmt_urls_atuais->fetchAll(PDO::FETCH_COLUMN);

    $urls_a_adicionar = array_diff($urls, $urls_atuais_db);
    $urls_a_remover = array_diff($urls_atuais_db, $urls);

    if (!empty($urls_a_remover)) {
        $placeholders = str_repeat('?,', count($urls_a_remover) - 1) . '?';
        $sql_delete_img = "DELETE FROM tb_imagens_publicacao WHERE fk_id_publicacao = ? AND url IN ($placeholders)";
        $stmt_delete_img = $conn->prepare($sql_delete_img);
        $stmt_delete_img->execute(array_merge([$id_publicacao], $urls_a_remover));
    }

    if (!empty($urls_a_adicionar)) {
        $sql_insert_img = "INSERT INTO tb_imagens_publicacao (url, fk_id_publicacao) VALUES ";
        $placeholders = implode(', ', array_fill(0, count($urls_a_adicionar), '(?, ?)'));
        $params = [];
        foreach ($urls_a_adicionar as $url) {
            $params[] = $url;
            $params[] = $id_publicacao;
        }
        $sql_insert_img .= $placeholders;
        $stmt_insert_img = $conn->prepare($sql_insert_img);
        $stmt_insert_img->execute($params);
    }

    $conn->commit();
    echo "Publicação atualizada com sucesso!";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
