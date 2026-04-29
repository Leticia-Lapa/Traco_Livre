<?php
include_once 'conectaBD.php';
session_start();

$fk_id_usuario = $_SESSION['id_usuario'] ?? 1;
$estilo = $_POST['estilo'] ?? '';
$descricao = $_POST['descricao'];
$urls = $_POST['urls'] ?? [];
$tags = $_POST['tags'] ?? [];

if(empty($urls) || empty($estilo)){
    echo "Estilo ou imagens não enviadas.";
    exit;
}

try {
    // Inicia transação
    $conn->beginTransaction();

    // Inserir publicação
    $sql = "INSERT INTO tb_publicacao (ds_publicacao, fk_id_usuario_criador) 
            VALUES (:descricao, :usuario)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':usuario', $fk_id_usuario);
    $stmt->execute();

    $id_publicacao = $conn->lastInsertId();

    // Inserir estilo da publicação
    $sql_estilo = "INSERT INTO tb_estilo_publicacao (fk_id_publicacao, fk_id_estilo) 
                   VALUES (:id_publicacao, :id_estilo)";
    $stmt_estilo = $conn->prepare($sql_estilo);
    $stmt_estilo->bindParam(':id_publicacao', $id_publicacao);
    $stmt_estilo->bindParam(':id_estilo', $estilo);
    $stmt_estilo->execute();

    // Inserir tags (se houver)
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            // Verifica se a tag já existe
            $sql_check_tag = "SELECT id_tag FROM tb_tag WHERE nm_tag = :tag";
            $stmt_check = $conn->prepare($sql_check_tag);
            $stmt_check->bindParam(':tag', $tag);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                // Já existe → pega o id existente
                $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
                $id_tag = $row['id_tag'];
            } else {
                // Não existe → cria nova
                $sql_insert_tag = "INSERT INTO tb_tag (nm_tag) VALUES (:tag)";
                $stmt_insert_tag = $conn->prepare($sql_insert_tag);
                $stmt_insert_tag->bindParam(':tag', $tag);
                $stmt_insert_tag->execute();
                $id_tag = $conn->lastInsertId();
            }

            // Cria vínculo na tabela intermediária
            $sql_pub_tag = "INSERT INTO tb_publicacao_tag (fk_id_publicacao, fk_id_tag) 
                            VALUES (:id_publicacao, :id_tag)";
            $stmt_pub_tag = $conn->prepare($sql_pub_tag);
            $stmt_pub_tag->bindParam(':id_publicacao', $id_publicacao);
            $stmt_pub_tag->bindParam(':id_tag', $id_tag);
            $stmt_pub_tag->execute();
        }
    }

    // Inserir imagens
    foreach($urls as $url){
        $sql_img = "INSERT INTO tb_imagens_publicacao (url, fk_id_publicacao) 
                    VALUES (:url, :id_publicacao)";
        $stmt_img = $conn->prepare($sql_img);
        $stmt_img->bindParam(':url', $url);
        $stmt_img->bindParam(':id_publicacao', $id_publicacao);
        $stmt_img->execute();
    }

    // Confirma transação
    $conn->commit();
    echo "Publicação feita com sucesso!";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
?>
