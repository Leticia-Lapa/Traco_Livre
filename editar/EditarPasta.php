<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_pasta = $_GET['id'];
$origem = $_GET['origem'];

$sql_pasta = "SELECT COALESCE(NULLIF(tp.img_capa, ''), tp.corCapa) AS capa, nm_titulo_pasta, ds_pasta
FROM tb_pasta tp
WHERE tp.id_pasta = '" . $id_pasta . "'";
$stmt_pasta = $conn->prepare($sql_pasta);
$stmt_pasta->execute();
$pasta = $stmt_pasta->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/editarPasta.js" defer></script>
    <link rel="stylesheet" href="../modoEscuro.css">
    <link rel="stylesheet" href="../Criar/Criar.css">
    <title>Editar Pasta</title>
</head>

<body>
    <div class="container">
        <?php if($origem === 'pasta'){ ?>
            <div class="voltar">
                <a href="../Pasta.php?id=<?php echo $id_pasta; ?>">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </div>
        <?php }else{ ?>
            <div class="voltar">
                <a href="../Perfil.php">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </div>
        <?php } ?>
        <div class="texto">
            <h1>Editar Pasta</h1>
            <p>Faça as modificações necessárias para refletir as suas preferências!</p>
        </div>
        <hr>
        <div class="form">
            <input type="hidden" id="id_pasta" name="id_pasta" value="<?php echo $id_pasta; ?>">
            <div class="grupo-input">
                <label>Título<span class="obrigatorio">*</span></label>
                <input type="text" value="<?php echo $pasta['nm_titulo_pasta']; ?>" id="titulo">
            </div>
            <div class="grupo-input">
                <label>Descrição <span class="obrigatorio">*</span></label>
                <textarea name="descricao" id="descricao" rows="4" cols="50" required><?php echo $pasta['ds_pasta']; ?></textarea>
            </div>
            <div class="texto-color">
                <h4>Capa da Pasta</h4>
                <p>Aqui é possível definir como a sua pasta vai aparecer no seu portifólio.</p>
            </div>
            <div class="grupo-input-color">
                <input type="color" id="fonte-cor">
                <label>Cor da Fonte</label>
            </div>
            <?php $capa = $pasta['capa'];
            $imagem = '';
            $cor = '';
            $capaI = '';
            if (str_starts_with($capa, '#')) {
                $cor = htmlspecialchars($capa);
            } else {
                $imagem = htmlspecialchars($capa);
                $capaI = '<div class="preview-container">
                    <img src="' . $imagem . '" alt="Capa Pasta">
                    <button type="button" class="remover-btn">X</button>
                </div>';
            } ?>
            <div class="grupo-input-color">
                <input type="color" id="cor-capa" value="<?php echo $cor ?>">
                <label>Cor da Pasta</label>
            </div>
            <div class="grupo-img">
                <div class="btn-file">
                    <input type="file" id="imagem" accept="image/*">
                    <label for="imagem" class="btn-adicionar">
                        <img src="../icons/mais.png" alt="Adicionar Imagem">
                    </label>
                </div>
                <div id="img-list" class="img-list"><?php echo $capaI; ?></div>
            </div>
            <div class="texto-color">
                <p>Ou adicione uma imagem de capa.</p>
            </div>
            <button class="btn btn-avancar">Salvar</button>
        </div>
    </div>
    <div id="msg"></div>
    <script src="../configuracao/modo.js"></script>
</body>

</html>