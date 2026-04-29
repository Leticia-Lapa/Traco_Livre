<?php
include_once 'conectaBD.php';
session_start();

$id_usuario = $_SESSION['id_usuario'] ?? 1;
$id_outroUsuario = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;

try {
    $conn->beginTransaction();

    $check_conversa = "
        SELECT id_conversa 
        FROM tb_conversa 
        WHERE fk_id_usuario_criador = :id_usuario 
          AND fk_id_usuario_participante = :id_outro_usuario
    ";
    $stmt_check = $conn->prepare($check_conversa);
    $stmt_check->execute([
        ':id_usuario' => $id_usuario,
        ':id_outro_usuario' => $id_outroUsuario
    ]);

    if ($stmt_check->rowCount() > 0) {
        $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
        $id_conversa = $row['id_conversa'];
    } else {

        $sql_conversa = "
            INSERT INTO tb_conversa 
               (nm_conversa, dt_criacao, fk_id_usuario_criador, fk_id_usuario_participante)
            VALUES 
               (:nome, CURRENT_TIMESTAMP, :id_usuario, :id_outro_usuario)
        ";
        $stmt_conversa = $conn->prepare($sql_conversa);
        $stmt_conversa->execute([
            ':nome' => $nome,
            ':id_usuario' => $id_usuario,
            ':id_outro_usuario' => $id_outroUsuario
        ]);

        $id_conversa = $conn->lastInsertId();
    }

    $conn->commit();
    echo $id_conversa;

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Erro: " . $e->getMessage();
}
