<!--  adicionado para ter a mesma verificação das outras telas e pegar o id para não mostrar as prorpias coisas -->

<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.php");
    exit;
}
include_once 'banco/conectaBD.php';
$id_usuario = $_SESSION['id_usuario'] ?? 1;

if (!isset($_SESSION['Pesquisa'])) {
    $_SESSION['Pesquisa'] = '';
}
$barraP = $_SESSION['Pesquisa'];

$sql_foto = "SELECT img_perfil FROM tb_usuario WHERE id_usuario = '" . $id_usuario . "'";


$selc_users = "SELECT id_usuario,IF(CHAR_LENGTH(nm_nome_usuario) > 7, CONCAT(LEFT(nm_nome_usuario, 7), '...'), nm_nome_usuario) AS nm_nome_usuario,img_perfil FROM tb_usuario 
WHERE id_usuario != '" . $id_usuario . "'and nm_nome_usuario like '%" . $barraP . "%'";

$selc_colaboracoes = "SELECT id_colaboracao,
IF(CHAR_LENGTH(nm_titulo_colaboracao) > 20, CONCAT(LEFT(nm_titulo_colaboracao, 20), '...'), nm_titulo_colaboracao) AS nm_titulo_colaboracao,nm_tipo,
IF(CHAR_LENGTH(nm_nome_usuario) > 12, CONCAT(LEFT(nm_nome_usuario, 12), '...'), nm_nome_usuario) AS nm_nome_usuario, 
IF(CHAR_LENGTH(ds_colaboracao) > 6, CONCAT(LEFT(ds_colaboracao, 35), '...'), ds_colaboracao) AS ds_colaboracao from tb_colaboracao
join tb_tipo_colaboracao on fk_id_tipo_colaboracao = id_tipo_colaboracao
join tb_usuario on fk_id_usuario_criador = id_usuario
where nm_titulo_colaboracao like '%" . $barraP . "%' OR nm_nome_usuario LIKE '%$barraP%';";

$selc_publicacao = "SELECT IF(CHAR_LENGTH(nm_nome_usuario) > 12, CONCAT(LEFT(nm_nome_usuario, 12), '...'), nm_nome_usuario) AS nm_nome_usuario,url,id_publicacao,
IF(CHAR_LENGTH(ds_publicacao) > 6, CONCAT(LEFT(ds_publicacao, 28), '...'), ds_publicacao) AS ds_publicacao from tb_publicacao join tb_usuario
on fk_id_usuario_criador = id_usuario
join tb_imagens_publicacao as imga
on imga.fk_id_publicacao = id_publicacao
WHERE id_usuario != " . $id_usuario . " and
(nm_nome_usuario like '%" . $barraP . "%' or ds_publicacao like '%" . $barraP . "%');";


$selc_relatos = "SELECT IF(CHAR_LENGTH(nm_nome_usuario) > 12, CONCAT(LEFT(nm_nome_usuario, 12), '...'), nm_nome_usuario) AS nm_nome_usuario,
(
    SELECT GROUP_CONCAT(i.url SEPARATOR ',')
    FROM tb_imagens_relato i
    WHERE i.fk_id_relato = r.id_relato
) AS imagens, id_relato, IF(CHAR_LENGTH(nm_titulo_relato) > 12, CONCAT(LEFT(nm_titulo_relato, 12), '...'), nm_titulo_relato) AS nm_titulo_relato,
IF(CHAR_LENGTH(ds_relato) > 6, CONCAT(LEFT(ds_relato, 22), '...'), ds_relato) AS ds_relato from tb_relato as r join tb_usuario
on fk_id_usuario_criador = id_usuario
join tb_imagens_relato as imga
on imga.fk_id_relato = id_relato
WHERE id_usuario != " . $id_usuario . " and
(nm_nome_usuario like '%" . $barraP . "%' or ds_relato like '%" . $barraP . "%' or nm_titulo_relato like '%" . $barraP . "%');";

$stmt = $conn->prepare($sql_foto);
$stmt->execute();
$foto = $stmt->fetch(PDO::FETCH_ASSOC);

$usuarios = $conn->query($selc_users);
$publicacoes = $conn->query($selc_publicacao);
$realtos = $conn->query($selc_relatos);
$colaboracoes = $conn->query($selc_colaboracoes);

$_SESSION['resultado_usuarios'] = $usuarios->fetchAll(PDO::FETCH_ASSOC);
$usuarios = $conn->query($selc_users);

$_SESSION['resultado_publicacoes'] = $publicacoes->fetchAll(PDO::FETCH_ASSOC);
$publicacoes = $conn->query($selc_publicacao);

$_SESSION['resultado_relatos'] = $realtos->fetchAll(PDO::FETCH_ASSOC);
$realtos = $conn->query($selc_relatos);

$_SESSION['resultado_colaboracoes'] = $colaboracoes->fetchAll(PDO::FETCH_ASSOC);
$colaboracoes = $conn->query($selc_colaboracoes);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa</title>
    <link rel="stylesheet" href="pesquisa/Pesquisa.css">
    <link rel="stylesheet" href="./modoEscuro.css">
    <script src="img.js" defer></script>
    <script src="./configuracao/modo.js" defer></script>
</head>

<body>
    <header class="top-bar">
        <div class="l2">
            <button id="btnVolta" class="voltar" onclick="voltarResultados()">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="pesquisa">
                <input type="text" placeholder="Pesquisar..." id="barraPesquisa">
                <i class="bi bi-search" onclick="Pesquisa()"></i>
            </div>
        </div>
        <div class="l3">
            <h4>Resultados Para Sua Pesquisa</h4>
        </div>
    </header>
    <script src="Pesquisa.js">
    </script>
    <main id="Resultados">
        <!-- Usuários -->
        <?php if ($usuarios->rowCount() > 0) { ?>
            <section class="Usuarios">
                <div class="topo">
                    <h4>Usuários</h4>
                    <!-- <i class="bi bi-arrow-right" onclick="window.location.href='Mais.php?tipo=usuarios'"></i> -->
                </div>
                <div id="C-User" class="conteudo">
                    <?php
                    while ($pessoa = $usuarios->fetch()) {
                        $divA = '<div id="U' . $pessoa[0] . '" class="perfil" onclick="window.location.href=' . "'OutroPerfil.php?id=" . $pessoa[0] . "'" . ';">';
                        $FotoP = '<img src="' . $pessoa[2] . '" alt="">';
                        $NomeP = '<label class="usuario">@' . $pessoa[1] . '</label></div>';
                        echo $divA . $FotoP . $NomeP;
                    }
                    ?>
                </div>
            </section>
        <?php } else { ?>
            <section class="Usuarios" style="display: none;"></section>
        <?php } ?>
        <!-- Publicações -->
        <?php if ($publicacoes->rowCount() > 0) { ?>
            <section class="Publicacoes">
                <div class="topo">
                    <h4>Publicações</h4>
                    <!-- <i class="bi bi-arrow-right" onclick="window.location.href='Mais.php?tipo=publi'"></i> -->
                </div>
                <div id="C-Publ" class="conteudo">
                    <!-- Mudar aqui -->
                    <?php
                    while ($publi = $publicacoes->fetch()) {
                        $divA = '<div id="P' . $publi[2] . '" class="post" onclick="window.location.href=' . "'detalhes/Publicacao.php?id=" . $publi[2] . "'" . ';">';
                        echo "<script>alert('" . $divA . "');</script>";
                        $FotoP = '<div class="post-imagem"><img src="' . $publi[1] . '" alt=""></div>';
                        $NomeP = '<div class="post-texto"> <label>@' . $publi[0] . '</label> </div>';
                        $DescP = '<label class="descricao">' . $publi[3] . '</label>';
                        $selc_publicacao_tag = "select IF(CHAR_LENGTH(nm_tag) > 4, CONCAT(LEFT(nm_tag, 4), '...'), nm_tag) AS nm_tag
                        from tb_publicacao_tag as tag
                        join tb_tag on fk_id_tag = id_tag
                        where fk_id_publicacao = " . $publi[2] . ";";
                        $sth = $conn->prepare($selc_publicacao_tag);
                        $sth->execute();
                        $vez = 0;
                        echo $divA . $FotoP . $DescP . $NomeP;
                        echo '<div class="tags">';
                        while ($row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT) and $vez != 3) {
                            $data = '<span class="tag">#' . $row[0] . '</span>';
                            echo $data;
                            $vez++;
                        }
                        echo '</div></div>';
                    }
                    ?>
                </div>
            </section>
        <?php } else { ?>
            <section class="Publicacoes" style="display: none;"></section>
        <?php } ?>

        <!-- Colaborações -->
        <?php if ($colaboracoes->rowCount() > 0) { ?>
            <section class="Colaboracao">
                <div class="topo">
                    <h4>Colaborações</h4>
                    <!-- <i class="bi bi-arrow-right" onclick="window.location.href='Mais.php?tipo=colab'"></i> -->
                </div>
                <div id="C-Cola" class="conteudo">
                    <?php
                    while ($cola = $colaboracoes->fetch()) {
                        $divA = '<div id="Cola' . $cola[0] . '" class="post" onclick="window.location.href=' . "'detalhes/ColaboraDeta.php?id=" . $cola[0] . "'" . ';">';
                        $Titulo = '<label class="titulo">' . $cola[1] . '</label>';
                        $Tipo = '<label class="tipo">' . $cola[2] . '</label> <label class="desc">ver detalhes</label>';
                        $NomeP = '<label class="usuario">@' . $cola[3] . '</label>';
                        $selc_colab_tag = "select IF(CHAR_LENGTH(nm_tag) > 4, CONCAT(LEFT(nm_tag, 4), '...'), nm_tag) AS nm_tag
                        from tb_colaboracao_tag as tag
                        join tb_tag on fk_id_tag = id_tag
                        where fk_id_colaboracao = " . $cola[0] . ";";
                        $sth = $conn->prepare($selc_colab_tag);
                        $sth->execute();
                        $vez = 0;
                        echo $divA . $Titulo . $Tipo . $NomeP;
                        echo '<div class="tags">';
                        while ($row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT) and $vez != 3) {
                            $data = '<spam class="tag">#' . $row[0] . '</spam>';
                            echo $data;
                            $vez++;
                        }
                        echo '</div></div>';
                    }
                    ?>
                </div>
            </section>
        <?php } else { ?>
            <section class="Colaboracao" style="display: none;"></section>
        <?php } ?>

        <!-- Relatos -->
        <?php if ($realtos->rowCount() > 0) { ?>
            <section class="Relatos">
                <div class="topo">
                    <h4>Relatos</h4>
                    <!-- <i class="bi bi-arrow-right" onclick="window.location.href='Mais.php?tipo=rel'"></i> -->
                </div>
                <div id="C-Rela" class="conteudo">
                    <?php
                    while ($realto = $realtos->fetch()) {
                        $divA = '<div id="R' . $realto[2] . '" class="post" onclick="window.location.href=' . "'detalhes/RelatoDeta.php?id=" . $realto[2] . "'" . ';"><label class="titulo">' . $realto[3] . '</label>';
                        $FotoP = '<div class = "post-imagem"> <img src="' . $realto[1] . '" alt=""> </div>';
                        $NomeP = '<label class="usuario">@' . $realto[0] . '</label>';
                        $DescP = '<label class="descricao">' . $realto[4] . '</label>';
                        $selc_relatos_tag = "select IF(CHAR_LENGTH(nm_tag) > 4, CONCAT(LEFT(nm_tag, 4), '...'), nm_tag) AS nm_tag
                        from tb_relato_tag as tag
                        join tb_tag on fk_id_tag = id_tag
                        where fk_id_relato = " . $realto[2] . ";";
                        $sth = $conn->prepare($selc_relatos_tag);
                        $sth->execute();
                        $vez = 0;
                        echo $divA . $FotoP . $DescP . $NomeP;
                        echo '<div class="tags">';
                        while ($row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT) and $vez != 3) {
                            $data = '<label class="tag">#' . $row[0] . '</label>';
                            echo $data;
                            $vez++;
                        }
                        echo '</div></div>';
                    }
                    ?>
                </div>
            </section>
        <?php } else { ?>
            <section class="Relatos" style="display: none;"></section>
        <?php } ?>

        <div id="msgNada" class="msg" style="display:none;">
            <h1>Nada Encontrado</h1>
            <p>Não encontramos nada relacionado ao que você pesquisou, que tal tentar novamente?</p>
        </div>
    </main>
    <footer class="navegacao">
        <a href="Relatos.php">
            <div>
                <img src="icons/navbar/Relatos.png" id="relato">
            </div>
        </a>
        <a href="Estilo.php">
            <div>
                <img src="icons/navbar/PesqAvan.png" id="estilo">
            </div>
        </a>
        <a href="Inicio.php">
            <div>
                <img src="icons/navbar/Principal.png" id="inicio">
            </div>
        </a>
        <a href="Conversas.php">
            <div>
                <img src="icons/navbar/Chat.png" id="chat">
            </div>
        </a>
        <a href="Colaboracoes.php">
            <div>
                <img src="icons/navbar/Colaboracao.png" id="colaboracao">
            </div>
        </a>
    </footer>
    <script>
        verificarResultados();
    </script>
    <script src="pesquisa/Pesquisa.js"></script>
</body>
</html>