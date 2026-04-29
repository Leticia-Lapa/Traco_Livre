<?php
include_once 'conectaBD.php';
session_start();

$id_usuario = $_POST['idUsuario'] ?? 1;
$id_outroUsuario = $_POST['idOutro'] ?? null;

try {
    $conn->beginTransaction();

    $sql_check = "SELECT id_seguidor from tb_seguidores where fk_id_usuario_seguidor = :idUsuario AND fk_id_usuario_seguido = :idOutro";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([
        ':idUsuario' => $id_usuario,
        ':idOutro' => $id_outroUsuario
    ]);

    if ($stmt_check->rowCount()>0) {
        $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
        $id_seguer = $row['id_seguidor'];

        $sql_deletar = "DELETE from tb_seguidores where id_seguidor = :id";
        $stmt_deletar = $conn->prepare($sql_deletar);
        $stmt_deletar->execute([ ':id' => $id_seguer]);
        $id_segue = 0;

    } else {

        $sql_inserir = "INSERT INTO tb_seguidores VALUES (null, :idOutro, :idUsuario)";
        $stmt_inserir = $conn->prepare($sql_inserir);
        $stmt_inserir->execute([
            ':idOutro' => $id_outroUsuario,
            ':idUsuario' => $id_usuario
        ]);
    $id_segue = 1;
    }

    $conn->commit();
    echo $id_segue;

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
?>