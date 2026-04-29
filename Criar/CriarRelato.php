<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';

$sql = "SELECT * FROM tb_tipo_relato";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../modoEscuro.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/criarRelato.js" defer></script>
    <script src="../scripts/tag.js" defer></script>
    <script src="../scripts/validarCriar.js" defer></script>
    <link rel="stylesheet" href="Criar.css">
    <title>Criar Relato</title>
</head>

<body>
    <div class="container">
        <div class="voltar">
            <a href="../Relatos.php">
                <i class="bi bi-chevron-left"></i>
            </a>
        </div>
        <div class="texto">
            <h1>Criar Relato</h1>
            <p>Preencha os campos a seguir para postar um relato na sessão de relatos.</p>
        </div>
        <hr>
        <div class="form">
            <div class="grupo-img">
                <div id="img-list" class="img-list"></div>
                <div class="btn-file">
                    <input type="file" id="imagem" name="imagem[]" accept="image/*" multiple required>
                    <label for="imagem" class="btn-adicionar">
                        <img src="../icons/mais.png" alt="Adicionar Imagem">
                    </label>
                </div>
            </div>
            <div class="grupo-input">
                <label>Título<span class="obrigatorio">*</span></label>
                <input type="text" id="titulo" placeholder="Digite..." required>
            </div>
            <div class="grupo-input">
                <label>Tipo de Relato<span class="obrigatorio">*</span></label>
                <select name="tipo" id="tipo" required>
                    <option value="valor1">Selecione</option>
                    <?php foreach ($rows as $row) { ?>
                        <option value="<?php echo $row['id_tipo_relato']; ?>"><?php echo $row['nm_tipo']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="grupo-input">
                <label>Descrição <span class="obrigatorio">*</span></label>
                <textarea id="descricao" rows="4" cols="50" maxlength="800" placeholder="Digite..."></textarea>
            </div>
            <div class="grupo-input">
                <label>Tags</label>
                <div class="post-tags">
                    <div id="tag-list"></div>
                    <button type="button" class="ver-mais" onclick="mostrarCampoTag()">
                        <img src="../icons/mais.png" alt="Adicionar Tag">
                    </button>

                    <div id="campo-tag" class="adicionar" style="display: none;">
                        <input type="text" id="novaTag" maxlength="38" placeholder="Digite a tag">
                        <button type="button" onclick="fecharCampoTag()" class="fechar">
                            <img src="../icons/x.png" alt="Cancelar">
                        </button>
                        <button type="button" class="ok" onclick="adicionarTag()">
                            <img src="../icons/ok.png" alt="Confirmar">
                        </button>
                    </div>
                </div>
            </div>
            <button class="btn btn-cancelar" onclick="window.location.href='../Relatos.php'">Cancelar</button>
            <button class="btn btn-avancar">Postar</button>
        </div>
    </div>
    </div>
    <script src="../configuracao/modo.js"></script>
    <div id="msg"></div>
</body>

</html>