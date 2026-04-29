<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$sql_dados = "SELECT * FROM tb_usuario WHERE id_usuario = '".$id_usuario."'";

$stmt_dados = $conn->prepare($sql_dados);
$stmt_dados->execute();
$dados = $stmt_dados ->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../modoEscuro.css">
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>EditarPerfil</title>
    <link rel="stylesheet" href="EditarPerfil.css">
    <script src="../scripts/editarPerfil.js" defer></script>
</head>
<body>
    <div class="container">
        <button class="voltar" onclick="window.location.href='../Perfil.php'">
             <i class="bi bi-chevron-left"></i>
        </button>
        <div class="perfil-container">
            <div class="foto">
                <img id="fotoPreview" src="<?php echo $dados['img_perfil'];?>" alt="Foto do Usuário">
            </div>
            <div class="container-editar">
                 <div class="editar">
                    <label for="foto" class="btn-editar">
                        <i class="bi bi-pen"></i>
                    </label>
                    <input type="file" id="foto" accept="image/*" style="display:none;">
                </div>
            </div>
        </div>
        <div class="form">
            <div class="grupo-input">
                <label>Nome Completo</label>
                <input type="text" id="nome-completo" value="<?php echo $dados['nm_usuario'];?>">
            </div>
            <div class="grupo-input">
                <label>Nome de Usuário</label>
                <input type="text" id="nome-usuario" value="<?php echo htmlspecialchars($dados['nm_nome_usuario']);?>" readonly>
            </div>
            <div class="grupo-input">
                <label>Número de Celular</label>
                <input type="text" id="telefone" value="<?php echo $dados['nr_telefonte'];?>">
            </div>
            <div class="grupo-input">
                <label>Bio</label> 
                <textarea  id="bio" rows="4" cols="50" maxlength="200"><?php echo htmlspecialchars($dados['bio']); ?></textarea>
            </div>
                <button  class="btn btn-avancar">Salvar</button>
            </div>
    </div>
    <div id="msg"></div>
    <script src="../configuracao/modo.js"></script>
</body>
</html>