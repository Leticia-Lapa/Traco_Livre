<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.php");
    exit;
}
include_once '../banco/conectaBD.php';
$id_usuario = $_SESSION['id_usuario'] ?? 1;

$tipo = $_GET['tipo'] ?? '';

$dados = [];

switch ($tipo) {
    case 'usuarios':
        $dados = $_SESSION['resultado_usuarios'] ?? [];
        $titulo = "Usuários";
        break;
    case 'publi':
        $dados = $_SESSION['resultado_publicacoes'] ?? [];
        $titulo = "Publicações";
        break;
    case 'rel':
        $dados = $_SESSION['resultado_relatos'] ?? [];
        $titulo = "Relatos";
        break;
    case 'colab':
        $dados = $_SESSION['resultado_colabs'] ?? [];
        $titulo = "Colaborações";
        break;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Mais.css">
</head>

<body>
    <header class="top-bar">
        <div class="l2">
            <button id="btnVolta" class="voltar" onclick="fecha()">
                <img src="../icons/volta.png" alt="Voltar">
            </button>
        </div>
        <div class="l3">
            <h4><?php echo $titulo; ?></h4>
        </div>
    </header>

    <div class="feed">
        <div class="tudo">
            <?php if ($titulo == 'Usuarios') {
                foreach ($dados as $da) { ?>

                <?php } ?>
                <?php } elseif ($titulo == 'Publicações') {
                foreach ($dados as $da) { ?>

                    <div id="P<?php echo $da['id_publicacao']; ?>" class="post" onclick="window.location.href='../detalhes/Publicacao.php?id=<?php echo $da['id_publicacao']; ?>'">
                        <div class="post-imagem"><img src="<?php echo $da['url']; ?>" alt=""></div>
                        <label class="descricao"><?php echo $da['ds_publicacao'];?></label>
                        <div class="post-texto"> <label>@<?php echo $da['nm_nome_usuario'];?></label> </div>

                        <?php $selc_publicacao_tag = "select IF(CHAR_LENGTH(nm_tag) > 4, CONCAT(LEFT(nm_tag, 4), '...'), nm_tag) AS nm_tag
                        from tb_publicacao_tag as tag
                        join tb_tag on fk_id_tag = id_tag
                        where fk_id_publicacao = " . $da['id_publicacao'] . ";";
                        $sth = $conn->prepare($selc_publicacao_tag);
                        $sth->execute();
                        $tags = $sth->fetchAll(PDO::FETCH_ASSOC); ?>

                        <div class="tags">
                            <?php foreach ($tags as $tag) { ?>
                                <span class="tag"><?php echo $tag['nm_tag']; ?></span>
                            <?php  } ?>
                        </div>
                        </div>
                    <?php } ?>
                <?php } elseif ($titulo == 'Relatos') { ?>
                <?php } else { ?>
                <?php } ?>
        </div>
    </div>
</body>

</html>