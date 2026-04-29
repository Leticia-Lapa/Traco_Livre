<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_publicacao = $_GET['id'];

$sql = "SELECT * FROM tb_estilo";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt ->fetchAll(PDO::FETCH_ASSOC);

$sql_dados_pub = "SELECT DISTINCT 
    p.ds_publicacao,
    ep.fk_id_estilo,
    e.nm_estilo,
    (
        SELECT GROUP_CONCAT(i.url SEPARATOR ',')
        FROM tb_imagens_publicacao i
        WHERE i.fk_id_publicacao = p.id_publicacao
    ) AS imagens,
    (
        SELECT GROUP_CONCAT(t.nm_tag SEPARATOR ',')
        FROM tb_publicacao_tag pt
        JOIN tb_tag t ON t.id_tag = pt.fk_id_tag
        WHERE pt.fk_id_publicacao = p.id_publicacao
    )AS tags
FROM tb_publicacao p
JOIN tb_estilo_publicacao ep
ON ep.fk_id_publicacao = p.id_publicacao
JOIN tb_estilo e ON ep.fk_id_estilo = e.id_estilo
WHERE p.id_publicacao = '".$id_publicacao."'
";
$stmt_publ = $conn->prepare($sql_dados_pub);
$stmt_publ->execute();
$publicacao = $stmt_publ->fetch(PDO::FETCH_ASSOC);
if($publicacao){
    $publicacao['imagens'] = !empty($publicacao['imagens']) ? explode(',', $publicacao['imagens']) : [];
    $publicacao['tags'] =  !empty($publicacao['tags']) ? explode(',', $publicacao['tags']) : [];
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../modoEscuro.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/editarPublicacao.js" defer></script>
    <link rel="stylesheet" href="../Criar/Criar.css">
    <link rel="stylesheet" href="Editar.css">
    <title>Editar Publicação</title>
</head>
<body>
    <div class="container">
        <button id="btnVolta" class="voltar">
                <i class="bi bi-chevron-left"></i>
        </button>
        <div class="texto">
            <h1>Editar Publicação</h1>
            <p>Faça as modificações necessárias e torne este conteúdo ainda melhor!</p>
        </div>
        <hr>
        <div class="form">
             <input type="hidden" id="id_publicacao" name="id_publicacao" value="<?php echo $id_publicacao;?>">
            <div class="grupo-img">
                <div id="img-list" class="img-list">
                    <?php foreach($publicacao['imagens'] as $img){?>
                        <div class="preview-container">
                            <img src="<?php echo $img;?>" alt="">
                            <button type="button" class="remover-btn">X</button>
                        </div>
                    <?php }?>
                </div>
                <div class="btn-file">
                    <input type="file" id="imagem" name="imagem[]" accept="image/*" multiple required>
                    <label for="imagem" class="btn-adicionar">
                        <img src="../icons/mais.png" alt="Adicionar Imagem">
                    </label>
                </div>
            </div>
            <div class="grupo-input">
                <label>Estilo Artistico<span class="obrigatorio">*</span></label>
                <select name="estilo" id="estilo">
                    <option value="<?php echo $publicacao['fk_id_estilo'];?>"><?php echo $publicacao['nm_estilo'];?></option>
                    <?php foreach ($rows as $row) { ?>
                    <option value="<?php echo $row['id_estilo']; ?>"><?php echo $row['nm_estilo'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="grupo-input">
                <label>Descrição <span class="obrigatorio">*</span></label>
                <textarea  name="descricao" id="descricao" rows="4" cols="50" required><?php echo $publicacao['ds_publicacao'];?></textarea>
            </div>
            <div class="grupo-input">
                <label>Tags</label>
                <div class="post-tags">
                    <div id="tag-list">
                        <?php foreach($publicacao['tags'] as $i => $tag){?>
                        <span class="tag">#<?php echo $tag; ?> <button type="button" class="remover-tag">×</button></span>
                        <?php } ?>
                    </div>
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
            <button class="btn btn-avancar">Salvar</button>
        </div>
    </div>
    <div id="msg"></div>
<script src="../configuracao/modo.js"></script>
    <script>

        document.getElementById("btnVolta").addEventListener("click", function() {
            history.back();
        });
    </script>
</body>
</html>