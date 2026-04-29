<?php
include_once 'conectaBD.php';
session_start();

$id_usuario = $_SESSION['id_usuario'] ?? 1;
 
$nm_completo = $_POST['nome'];
$nm_usuario  = $_POST['usuario'];
$fone     = $_POST['fone'];
$bio       = $_POST['bio'];
$foto_url = $_POST['foto_url'];
 
// Atualiza no banco (se tiver foto, atualiza também)
if (!empty($foto_url)) {
    $sql = "UPDATE tb_usuario SET nm_usuario=?, nm_nome_usuario=?, nr_telefonte=?, bio=?, img_perfil=? WHERE id_usuario=?";
    $stmt = $conn->prepare($sql);
    $ok = $stmt->execute([$nm_completo, $nm_usuario, $fone, $bio, $foto_url, $id_usuario]);
} else {
    $sql = "UPDATE tb_usuario SET nm_usuario=?, nm_nome_usuario=?, nr_telefonte=?, bio=? WHERE id_usuario=?";
    $stmt = $conn->prepare($sql);
    $ok = $stmt->execute([$nm_completo, $nm_usuario, $fone, $bio, $id_usuario]);
}
 
if ($ok) {
    echo "Perfil atualizado com sucesso!";
} else {
    echo "Erro ao atualizar perfil.";
}
 