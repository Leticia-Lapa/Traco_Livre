<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/Login.php");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_conversa = $_GET['id_conversa'] ?? null;
if (!$id_conversa) {
    echo "Conversa não encontrada.";
    exit;
}

$outroUsuario = $_GET['id_outrousuario'];

$sql_dados = "SELECT img_perfil, nm_nome_usuario from tb_usuario WHERE id_usuario = :id";
$stmt_dados = $conn->prepare($sql_dados);
$stmt_dados->execute([':id' => $outroUsuario]);
$dados = $stmt_dados->fetch(PDO::FETCH_ASSOC);

$sql_chat = "SELECT id_mensagem, ds_mensagem,  DATE_FORMAT(dt_mensagem, '%d/%m/%Y %H:%i') as data_mensagem, fk_id_usuario_criador from tb_mensagem
WHERE fk_id_conversa = :id_conversa";
$stmt_chat = $conn->prepare($sql_chat);
$stmt_chat->execute([':id_conversa' => $id_conversa]);
$mensagens = $stmt_chat->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../modoEscuro.css">
    <script src="../configuracao/modo.js" defer></script>
    <script src="../scripts/mensagem.js" defer></script>
    <link rel="stylesheet" href="style.css">
    <title>Chat</title>
</head>

<body>
    <div class="chat">
        <div class="chat-header">
            <div class="voltar">
                <a href="../Conversas.php">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </div>
            <div class="perfil-chat">
                <img src="<?php echo $dados['img_perfil']; ?>" alt="Usuário"><span><?php echo $dados['nm_nome_usuario']; ?></span>
            </div>
        </div>
        <input type="hidden" id="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" id="id_conversa" name="id_conversa" value="<?php echo $id_conversa; ?>">
        <div class="chat-messages">
            <?php foreach ($mensagens as $men) {
                if ($men['fk_id_usuario_criador'] == $id_usuario) { ?>
                    <div class="message sent" data-id="<?php echo $men['id_mensagem']; ?>">
                        <?php echo $men['ds_mensagem']; ?>
                    </div>
                <?php } else { ?>
                    <div class="message received" data-id="<?php echo $men['id_mensagem']; ?>">
                        <?php echo $men['ds_mensagem']; ?>
                    </div>
            <?php }
            } ?>
        </div>
        <div class="grupo-input">
            <label for="file-upload" class="file-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-paperclip" viewBox="0 0 16 16">
                    <path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z" />
                </svg>
            </label>
            <input type="file" id="file-upload">
            <input type="text" placeholder="Digite..." id="mensagem">
            <button class="btn"><img src="../icons/enviar.png" alt=""></button>
        </div>
    </div>
</body>

</html>