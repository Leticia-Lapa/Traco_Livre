<?php
include_once 'conectaBD.php';

$nomeCompleto    = $_POST['campo1'];
$nomeUsuario     = $_POST['campo2'];
$dataNascimento  = $_POST['campo3'];
$telefone        = $_POST['campo4'];
$email           = $_POST['campo5'];
$senhaHash       = password_hash($_POST['campo6'], PASSWORD_DEFAULT);

$sql = "INSERT INTO tb_usuario 
        (nm_usuario, nm_nome_usuario, dt_nascimento, nr_telefonte, nm_email, nm_senha, bio, img_perfil, id_tipo)
        VALUES
        (:nome, :usuario, :nasc, :tel, :email, :senha, NULL, DEFAULT, DEFAULT)";

$stmt = $conn->prepare($sql);

$ok = $stmt->execute([
    ':usuario' => $nomeUsuario,
    ':nome'    => $nomeCompleto,
    ':nasc'    => $dataNascimento,
    ':tel'     => $telefone,
    ':email'   => $email,
    ':senha'   => $senhaHash
]);

if ($ok) {
    echo "Cadastro feito com sucesso!";
} else {
    print_r($stmt->errorInfo());
}

?>