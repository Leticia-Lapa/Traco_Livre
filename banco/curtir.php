<?php
include_once "conectaBD.php";
session_start();

$id_usuario = $_SESSION['id_usuario'];
$ID = $_POST['id'] ?? null;
$tipo = $_POST['tipo'] ?? null;

switch ($tipo) {
    case 'pub':
        $sql_check = "SELECT id_curtida FROM tb_curtidas 
        WHERE fk_id_usuario = :id_usuario AND fk_id_publicacao = :id_item";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([
            ':id_usuario' => $id_usuario,
            ':id_item' => $ID
        ]);

        if ($stmt_check->rowCount() > 0) {
            $sql_deletar = " DELETE FROM tb_curtidas WHERE fk_id_usuario = :id_usuario AND fk_id_publicacao = :id_item";
            $stmt_deletar = $conn->prepare($sql_deletar);
            $stmt_deletar->execute([
                ':id_usuario' => $id_usuario,
                ':id_item' => $ID
            ]);

            $acao = 0;
        } else {
            $sql_curtir = "INSERT INTO tb_curtidas(fk_id_usuario,fk_id_publicacao)
            VALUES (:id_usuario, :id_item)";
            $stmt_curtir = $conn->prepare($sql_curtir);
            $stmt_curtir->execute([
                ':id_usuario' => $id_usuario,
                ':id_item' => $ID
            ]);

            $acao = 1;
        }

        $sql_contagem = "SELECT COUNT(*) FROM tb_curtidas WHERE fk_id_publicacao = :id_item";
        $stmt_contagem = $conn->prepare($sql_contagem);
        $stmt_contagem->execute([':id_item' => $ID]);
        $totalCurtidas = $stmt_contagem->fetchColumn();

        break;
    case 'rel':
        $sql_check = "SELECT id_curtida FROM tb_curtidas 
        WHERE fk_id_usuario = :id_usuario AND fk_id_relato = :id_item";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([
            ':id_usuario' => $id_usuario,
            ':id_item' => $ID
        ]);

        if ($stmt_check->rowCount() > 0) {
            $sql_deletar = " DELETE FROM tb_curtidas WHERE fk_id_usuario = :id_usuario AND fk_id_relato = :id_item";
            $stmt_deletar = $conn->prepare($sql_deletar);
            $stmt_deletar->execute([
                ':id_usuario' => $id_usuario,
                ':id_item' => $ID
            ]);

            $acao = 0;
        } else {
            $sql_curtir = "INSERT INTO tb_curtidas(fk_id_usuario, fk_id_relato)
            VALUES (:id_usuario, :id_item)";
            $stmt_curtir = $conn->prepare($sql_curtir);
            $stmt_curtir->execute([
                ':id_usuario' => $id_usuario,
                ':id_item' => $ID
            ]);

            $acao = 1;
        }

        $sql_contagem = "SELECT COUNT(*) FROM tb_curtidas WHERE fk_id_relato = :id_item";
        $stmt_contagem = $conn->prepare($sql_contagem);
        $stmt_contagem->execute([':id_item' => $ID]);
        $totalCurtidas = $stmt_contagem->fetchColumn();

        break;
    case 'cob':
        $sql_check = "SELECT id_curtida FROM tb_curtidas 
        WHERE fk_id_usuario = :id_usuario AND fk_id_colaboracao = :id_item";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([
            ':id_usuario' => $id_usuario,
            ':id_item' => $ID
        ]);

        if ($stmt_check->rowCount() > 0) {
            $sql_deletar = " DELETE FROM tb_curtidas WHERE fk_id_usuario = :id_usuario AND fk_id_colaboracao = :id_item";
            $stmt_deletar = $conn->prepare($sql_deletar);
            $stmt_deletar->execute([
                ':id_usuario' => $id_usuario,
                ':id_item' => $ID
            ]);

            $acao = 0;
        } else {
            $sql_curtir = "INSERT INTO tb_curtidas(fk_id_usuario, fk_id_colaboracao)
            VALUES (:id_usuario, :id_item)";
            $stmt_curtir = $conn->prepare($sql_curtir);
            $stmt_curtir->execute([
                ':id_usuario' => $id_usuario,
                ':id_item' => $ID
            ]);

            $acao = 1;
        }

        $sql_contagem = "SELECT COUNT(*) FROM tb_curtidas WHERE fk_id_colaboracao = :id_item";
        $stmt_contagem = $conn->prepare($sql_contagem);
        $stmt_contagem->execute([':id_item' => $ID]);
        $totalCurtidas = $stmt_contagem->fetchColumn();

        break;
    default:
        echo "Tipo inválido.";
        exit;
}

echo json_encode([
    "acao" => $acao, 
    "total" => $totalCurtidas
]);
