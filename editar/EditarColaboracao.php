<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_colaboracao = $_GET['id'];

$sql = "SELECT * FROM tb_tipo_colaboracao";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt ->fetchAll(PDO::FETCH_ASSOC);

$sql_dados_cob = "SELECT DISTINCT
    c.nm_titulo_colaboracao,
    c.ds_colaboracao,
    c.fk_id_tipo_colaboracao,
    c.dt_hora_colaboracao,
    tc.nm_tipo,
    (
        SELECT GROUP_CONCAT(t.nm_tag SEPARATOR ',')
        FROM tb_colaboracao_tag ct
        JOIN tb_tag t ON t.id_tag = ct.fk_id_tag
        WHERE ct.fk_id_colaboracao = c.id_colaboracao
    )AS tags
FROM tb_colaboracao c
JOIN tb_tipo_colaboracao tc
ON tc.id_tipo_colaboracao = c.fk_id_tipo_colaboracao
WHERE c.id_colaboracao = '".$id_colaboracao."'
";
$stmt_colb = $conn->prepare($sql_dados_cob);
$stmt_colb->execute();
$colaboracao = $stmt_colb->fetch(PDO::FETCH_ASSOC);
if($colaboracao){
    $colaboracao['tags'] =  !empty($colaboracao['tags']) ? explode(',', $colaboracao['tags']) : [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/editarColaboracao.js" defer></script>
    <link rel="stylesheet" href="../modoEscuro.css">
   <link rel="stylesheet" href="../Criar/Criar.css">
    <title>Editar Colaboracao</title>
</head>
<body>
    <div class="container">
        <div class="voltar">
            <a href="../Colaboracoes.php">
                <i class="bi bi-chevron-left"></i>
            </a> 
        </div>
        <div class="texto">
            <h1>Editar Colaboração</h1>
            <p>Faça as modificações necessárias e torne este conteúdo ainda melhor!</p>
        </div>
        <hr>
        <div class="form">
        <input type="hidden" id="id_colaboracao" name="id_colaboracao" value="<?php echo $id_colaboracao;?>">
            <div class="grupo-input">
                <label>Título<span class="obrigatorio">*</span></label>
                <input type="text" id="titulo" value="<?php echo $colaboracao['nm_titulo_colaboracao'];?>">
            </div>
            <div class="grupo-input">
                <label>Tipo de Colaboração<span class="obrigatorio">*</span></label>
                <select name="tipo" id="tipo" required>
                    <option value="<?php echo $colaboracao['fk_id_tipo_colaboracao'];?>"><?php echo $colaboracao['nm_tipo'];?></option>
                    <?php foreach ($rows as $row) { ?>
                    <option value="<?php echo $row['id_tipo_colaboracao']; ?>"><?php echo $row['nm_tipo'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="grupo-input">
                <label>Descrição <span class="obrigatorio">*</span></label>
                <textarea  name="descricao" id="descricao" rows="4" cols="50"  required><?php echo $colaboracao['ds_colaboracao'];?></textarea>
            </div>
            <div class="grupo-input">
                <label>Tags</label>
                <div class="post-tags">
                    <div id="tag-list">
                        <?php foreach($colaboracao['tags'] as $i => $tag){?>
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