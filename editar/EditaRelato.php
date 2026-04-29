<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_relato = $_GET['id'];

$sql = "SELECT * FROM tb_tipo_relato";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt ->fetchAll(PDO::FETCH_ASSOC);

$sql_dados_rel = "SELECT DISTINCT
    r.nm_titulo_relato,
    r.ds_relato,
    r.fk_id_tipo_relato,
    r.dt_hora_relato,
    tr.nm_tipo,
    (
        SELECT GROUP_CONCAT(i.url SEPARATOR ',')
        FROM tb_imagens_relato i
        WHERE i.fk_id_relato = r.id_relato
    ) AS imagens,
    (
        SELECT GROUP_CONCAT(t.nm_tag SEPARATOR ',')
        FROM tb_relato_tag rt
        JOIN tb_tag t ON t.id_tag = rt.fk_id_tag
        WHERE rt.fk_id_relato = r.id_relato
    )AS tags
FROM tb_relato r
JOIN tb_tipo_relato tr
ON tr.id_tipo_relato = r.fk_id_tipo_relato
WHERE r.id_relato = '".$id_relato."'
";
$stmt_rela = $conn->prepare($sql_dados_rel);
$stmt_rela->execute();
$relato = $stmt_rela->fetch(PDO::FETCH_ASSOC);
if($relato){
    $relato['imagens'] = !empty($relato['imagens']) ? explode(',', $relato['imagens']) : [];
    $relato['tags'] =  !empty($relato['tags']) ? explode(',', $relato['tags']) : [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/editarRelato.js" defer></script>
    <link rel="stylesheet" href="../modoEscuro.css">
    <link rel="stylesheet" href="../Criar/Criar.css">
    <link rel="stylesheet" href="Editar.css">
    <title>Editar Relato</title>
</head>
<body>
    <div class="container">
        <div class="voltar">
            <a href="../Relatos.php">
                <i class="bi bi-chevron-left"></i>
            </a> 
        </div>
        <div class="texto">
            <h1>Editar Relato</h1>
            <p>Faça as modificações necessárias e torne este conteúdo ainda melhor!</p>
        </div>
        <hr>
        <div class="form">
            <input type="hidden" id="id_relato" name="id_relato" value="<?php echo $id_relato;?>">
            <div class="grupo-img">
                <div id="img-list" class="img-list">
                    <?php foreach($relato['imagens'] as $img){?>
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
                <label>Título<span class="obrigatorio">*</span></label>
                <input type="text" id="titulo" value="<?php echo $relato['nm_titulo_relato'];?>">
            </div>
            <div class="grupo-input">
                <label>Tipo de Relato<span class="obrigatorio">*</span></label>
                <select name="tipo" id="tipo" required>
                    <option value="<?php echo $relato['fk_id_tipo_relato'];?>"><?php echo $relato['nm_tipo'];?></option>
                    <?php foreach ($rows as $row) { ?>
                    <option value="<?php echo $row['id_tipo_relato']; ?>"><?php echo $row['nm_tipo'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="grupo-input">
                <label>Descrição <span class="obrigatorio">*</span></label>
                <textarea  name="descricao" id="descricao" rows="4" cols="50"  required><?php echo $relato['ds_relato'];?></textarea>
            </div>
            <div class="grupo-input">
                <label>Tags</label>
                <div class="post-tags">
                    <div id="tag-list">
                        <?php foreach($relato['tags'] as $i => $tag){?>
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
            <button  class="btn btn-avancar">Salvar</button>
        </div>
    </div>
<script src="../configuracao/modo.js"></script>
</body>
</html>