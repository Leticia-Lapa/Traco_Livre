<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../modoEscuro.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/pasta.js"></script>
    <link rel="stylesheet" href="Criar.css">
    <link rel="stylesheet" href="../modoEscuro.css">

    <title>Criar Pasta</title>
</head>

<body>
    <div class="container">
        <div class="voltar">
            <a href="../Perfil.php">
                <i class="bi bi-chevron-left"></i>
            </a>
        </div>
        <div class="texto">
            <h1>Criar Pasta</h1>
            <p>Preencha os campos a seguir para criar uma pasta no seu portifólio.</p>
        </div>
        <hr>
        <div class="form">
            <div class="grupo-input">
                <label>Título<span class="obrigatorio">*</span></label>
                <input type="text" id="titulo" placeholder="Digite...">
            </div>
            <div class="grupo-input">
                <label>Descrição <span class="obrigatorio">*</span></label>
                <textarea id="descricao" rows="4" cols="50" placeholder="Digite aqui..." required></textarea>
            </div>
            <div class="texto-color">
                <h4>Capa da Pasta</h4>
                <p>Aqui é possível definir como a sua pasta vai aparecer no seu portifólio.</p>
            </div>
            <div class="grupo-input-color">
                <input type="color" id="fonte">
                <label>Cor da Fonte</label>
            </div>
            <div class="grupo-input-color">
                <input type="color" id="cor-capa">
                <label>Cor da Pasta</label>
            </div>
            <div class="texto-color">
                <p>Ou adicione uma imagem de capa.</p>
            </div>
            <div class="grupo-img">
                <div class="btn-file">
                    <input type="file" id="imagem" accept="image/*">
                    <label for="imagem" class="btn-adicionar">
                        <img src="../icons/mais.png" alt="Adicionar Imagem">
                    </label>
                </div>
                <div id="img-list" class="img-list"></div>
            </div>
            <button class="btn btn-cancelar" onclick="window.location.href='../Perfil.php'">Cancelar</button>
            <button class="btn btn-avancar">Criar</button>
        </div>
    </div>
    <div id="msg"></div>
    <script src="../configuracao/modo.js"></script>
    <script>

    </script>
</body>

</html>