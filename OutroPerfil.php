<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/Login.php");
    exit;
}
include 'banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_outroUsuario = $_GET['id'] ?? null;
if (!$id_outroUsuario) {
    echo "Usuário não encontrado.";
    exit;
}

$sql_dados_usuario = "SELECT nm_usuario, nm_nome_usuario, bio, img_perfil FROM tb_usuario WHERE id_usuario = '" . $id_outroUsuario . "'";

$stmt_dados = $conn->prepare($sql_dados_usuario);
$stmt_dados->execute();
$dados = $stmt_dados->fetch(PDO::FETCH_ASSOC);

$sql_pasta = "SELECT id_pasta, COALESCE(NULLIF(tp.img_capa, ''), tp.corCapa) AS capa, nm_titulo_pasta
FROM tb_pasta tp
WHERE tp.fk_id_usuario_criador = '" . $id_outroUsuario . "'";
$stmt_pasta = $conn->prepare($sql_pasta);
$stmt_pasta->execute();
$pastas = $stmt_pasta->fetchAll(PDO::FETCH_ASSOC);

$sql_publicacao = "SELECT p.id_publicacao,
    MIN(i.url) AS url
FROM tb_publicacao p
LEFT JOIN tb_imagens_publicacao i 
    ON p.id_publicacao = i.fk_id_publicacao
WHERE p.fk_id_usuario_criador = '" . $id_outroUsuario . "'
GROUP BY p.id_publicacao
ORDER BY p.id_publicacao DESC
";
$stmt_publicacao = $conn->prepare($sql_publicacao);
$stmt_publicacao->execute();

$publicacoes = $stmt_publicacao->fetchAll(PDO::FETCH_ASSOC);

$sql_seguidores = "SELECT id_seguidor FROM tb_seguidores where fk_id_usuario_seguido = :outro";
$stmt_seguidores = $conn->prepare($sql_seguidores);
$stmt_seguidores->execute([':outro' => $id_outroUsuario]);
$seguidores = $stmt_seguidores->rowCount();


$sql_seguindo = "SELECT id_seguidor FROM tb_seguidores where fk_id_usuario_seguidor = :outro";
$stmt_seguindo = $conn->prepare($sql_seguindo);
$stmt_seguindo->execute([':outro' => $id_outroUsuario]);
$seguindo = $stmt_seguindo->rowCount();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/OutroPerfil.js" defer></script>
    <link rel="stylesheet" href="OutroPerfil.css">
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Outro Perfil</title>
</head>

<body>
    <header class="top-bar">
        <div class="l1">
            <div class="notificacoes">
                <a href="Notificacao.html"><i class="bi bi-bell"></i></a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="perfil-container">
            <div class="foto">
                <img src="<?php echo $dados['img_perfil']; ?>" alt="Foto do Usuário">
            </div>
            <div class="container-pontos">
                <button class="options-btn" onclick="togglePopup(this)">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="popup hidden">
                    <button class="iniciarConversa" data-nome="<?php echo $dados['nm_nome_usuario']; ?>" data-id="<?php echo $id_outroUsuario; ?>">Iniciar Conversa</button>
                    <button onclick="modalDenuncia()">Denúnciar Usuário</button>
                </div>
            </div>
            <div class="texto">
                <h1><?php echo $dados['nm_nome_usuario']; ?></h1>
            </div>
            <?php if ($dados['bio'] == null) { ?>
                <div class="bio" style="display: none;">
                    <h1>Bio</h1>
                    <p></p>
                </div>
            <?php } else { ?>
                <div class="bio">
                    <h1>Bio</h1>
                    <p><?php echo $dados['bio']; ?></p>
                </div>
            <?php } ?>
            <div class="btn-perfil">
                <div class="coluna">
                    <a href="segues.php?origem=outro&tipo=seguidores&id=<?php echo $id_outroUsuario;?>">
                        <p>Seguidores</p>
                        <?php if ($seguidores > 0) { ?>
                            <span id="nseguidores"><?php echo $seguidores; ?></span>
                        <?php } else { ?>
                            <span id="nseguidores">0</span>
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna">
                    <a href="segues.php?origem=outro&tipo=seguindo&id=<?php echo $id_outroUsuario; ?>">
                        <p>Seguindo</p>
                        <?php if ($seguindo > 0) { ?>
                            <span id="nseguindo"><?php echo $seguindo; ?></span>
                        <?php } else { ?>
                            <span id="nseguindo">0</span>
                        <?php } ?>
                    </a>
                </div>
            </div>
            <button id="seguir" data-id-usuario=<?php echo $id_usuario; ?> data-id-outro=<?php echo $id_outroUsuario; ?>>Seguir</button>
        </div>
        <div class="galeria-container">
            <?php if (count($pastas) > 0) { ?>
                <div class="form-pasta">
                    <div class="pasta-container">
                        <?php foreach ($pastas as $pas) {

                            $capa = $pas['capa'];

                            $style = '';
                            if (str_starts_with($capa, '#')) {
                                $style = "background-color: " . htmlspecialchars($capa) . ";";
                            } else {
                                $style = "background-image: url('" . htmlspecialchars($capa) . "'); background-size: cover; background-position: center;";
                            }

                        ?>

                            <div class="pasta" onclick="window.location.href='Pasta.php?id=<?php echo $pas['id_pasta']; ?>'" style="<?php echo htmlspecialchars($style); ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="form-pasta" style="display: none;"></div>
            <?php } ?>
            <?php if (count($publicacoes) > 0) { ?>
                <div class="galeria">
                    <?php foreach ($publicacoes as $pub) { ?>
                        <div class="item-galeria" onclick="window.location.href='Publicacao.php?id=<?php echo $pub['id_publicacao']; ?>'">
                            <img src="<?php echo $pub['url']; ?>" alt="Imagem" class="img-galeria">
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="msg">
                    <h1>Esse usuário ainda não fez nenhuma publicação</h1>
                    <p>Enquanto espera por novas postagens, você pode explorar outros perfis.</p>
                </div>
            <?php } ?>
        </div>
    </div>
    <footer class="navegacao">
        <a href="Relatos.php">
            <div>
                <img src="icons/navbar/Relatos.png" alt="Relatos" id="relato">
            </div>
        </a>
        <a href="Estilo.php">
            <div>
                <img src="icons/navbar/PesqAvan.png" alt="Estilos" id="estilo">
            </div>
        </a>
        <a href="Inicio.php">
            <div>
                <img src="icons/navbar/Principal.png" alt="Início" id="inicio">
            </div>
        </a>
        <a href="Conversas.php">
            <div>
                <img src="icons/navbar/Chat.png" alt="Chat" id="chat">
            </div>
        </a>
        <a href="Colaboracoes.php">
            <div>
                <img src="icons/navbar/Colaboracao.png" alt="Colaborações" id="colaboracao">
            </div>
        </a>
    </footer>
    <dialog id="modalDenuncia" class="modal">
        <div class="conteudo-modal">
            <h3>Por qual motivo deseja denúnciar esse usuário?</h3>
            <div class="form">
                <div class="opcao" id="opcao01">
                    <p>Comportamento Abusivo</p>
                </div>
                <div class="opcao" id="opcao02">
                    <p>Perfil Falso</p>
                </div>
                <div class="opcao" id="opcao03">
                    <p>Spam</p>
                </div>
                <div class="opcao" id="opcao04">
                    <p>Outro</p>
                </div>
            </div>
            <button id="btnCancelar" class="btn cancelar">Cancelar</button>
        </div>
    </dialog>
    <script src="img.js"></script>
    <script src="./configuracao/modo.js"></script>
    <script src="scripts/modal.js"></script>
    <script>
        function togglePopup(button) {
            document.querySelectorAll('.popup').forEach(p => {
                if (p !== button.nextElementSibling) {
                    p.classList.add('hidden');
                }
            });
            button.nextElementSibling.classList.toggle('hidden');
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.options-btn') && !e.target.closest('.popup')) {
                document.querySelectorAll('.popup').forEach(p => p.classList.add('hidden'));
            }
        });
    </script>
</body>

</html>