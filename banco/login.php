<?php
session_start(); 
include_once 'conectaBD.php';

$login = $_POST['campo1']; 
$Digitada = $_POST['campo2']; 
$sql = "SELECT * FROM tb_usuario WHERE (nm_nome_usuario = :login OR nm_email = :login)"; 
$stmt = $conn->prepare($sql); 
$stmt->bindParam(':login', $login); 
$stmt->execute(); 
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    
    $senhaBanco = $usuario['nm_senha'];
    if (!password_verify($Digitada, $senhaBanco)) {

        if ($Digitada === $senhaBanco) {

            $novoHash = password_hash($Digitada, PASSWORD_DEFAULT);

            $update = $conn->prepare("
                UPDATE tb_usuario 
                SET nm_senha = :s
                WHERE id_usuario = :id
            ");
            $update->execute([
                ':s' => $novoHash,
                ':id' => $usuario['id_usuario']
            ]);

            // Login OK
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome_usuario'] = $usuario['nm_nome_usuario'];
            echo 1;
            exit;
        }
    }

    if (password_verify($Digitada, $senhaBanco)) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nome_usuario'] = $usuario['nm_nome_usuario'];
        echo 1;
        exit;
    }
} else {
    echo 0;
}


?>
