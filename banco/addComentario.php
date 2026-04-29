<?php
include_once "conectaBD.php";
session_start();

$id_usuario = $_SESSION['id_usuario'];
$ID = $_POST['id'] ?? null;
$ds = $_POST['ds'] ?? null;
$tipo = $_POST['tipo'] ?? null;

if (!$id_usuario || !$ds || !$ID || !$tipo) {
    echo "Comentário não enviado.";
    exit;
}

switch ($tipo) {
    case 'pub':
        $sql = "INSERT INTO tb_comentario(fk_id_usuario_criador, fk_id_publicacao, ds_comentario)
                VALUES (:id_usuario, :id_item, :comentario)";
        break;
    case 'rel':
        $sql = "INSERT INTO tb_comentario(fk_id_usuario_criador, fk_id_relato, ds_comentario)
                VALUES (:id_usuario, :id_item, :comentario)";
        break;
    case 'cob':
        $sql = "INSERT INTO tb_comentario(fk_id_usuario_criador, fk_id_colaboracao, ds_comentario)
                VALUES (:id_usuario, :id_item, :comentario)";
        break;
    default:
        echo "Tipo inválido.";
        exit;
}

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':id_item' => $ID,
    ':comentario' => $ds
]);

echo "Comentário inserido com sucesso.";
