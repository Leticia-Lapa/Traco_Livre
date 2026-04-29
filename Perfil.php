<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.php");
    exit;
}
include 'banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$sql_dados_usuario = "SELECT nm_usuario, nm_nome_usuario, bio, img_perfil FROM tb_usuario WHERE id_usuario = '" . $id_usuario . "'";
$stmt_dados = $conn->prepare($sql_dados_usuario);
$stmt_dados->execute();
$dados = $stmt_dados->fetch(PDO::FETCH_ASSOC);

$sql_pasta = "SELECT id_pasta, COALESCE(NULLIF(tp.img_capa, ''), tp.corCapa) AS capa, nm_titulo_pasta
FROM tb_pasta tp
WHERE tp.fk_id_usuario_criador = '" . $id_usuario . "'";
$stmt_pasta = $conn->prepare($sql_pasta);
$stmt_pasta->execute();
$pastas = $stmt_pasta->fetchAll(PDO::FETCH_ASSOC);

$sql_publicacao = "SELECT p.id_publicacao,
    MIN(i.url) AS url
FROM tb_publicacao p
LEFT JOIN tb_imagens_publicacao i 
    ON p.id_publicacao = i.fk_id_publicacao
WHERE p.fk_id_usuario_criador = $id_usuario
GROUP BY p.id_publicacao
ORDER BY p.id_publicacao DESC
";
$stmt_publicacao = $conn->prepare($sql_publicacao);
$stmt_publicacao->execute();
$publicacoes = $stmt_publicacao->fetchAll(PDO::FETCH_ASSOC);

$sql_seguidores = "SELECT id_seguidor FROM tb_seguidores where fk_id_usuario_seguido = :usuario";
$stmt_seguidores = $conn->prepare($sql_seguidores);
$stmt_seguidores->execute([':usuario' => $id_usuario]);
$seguidores = $stmt_seguidores->rowCount();


$sql_seguindo = "SELECT id_seguidor FROM tb_seguidores where fk_id_usuario_seguidor = :usuario";
$stmt_seguindo = $conn->prepare($sql_seguindo);
$stmt_seguindo->execute([':usuario' => $id_usuario]);
$seguindo = $stmt_seguindo->rowCount();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Perfil.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Perfil</title>
</head>

<body>
    <header class="top-bar">
        <div class="l1">
            <div class="perfil">
                <a href="configuracao/Configuracoes.php"><i class="bi bi-gear-fill"></i></a>
            </div>
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
            <div class="container-editar">
                <a href="editar/EditarPerfil.php">
                    <div class="editar">
                        <i class="bi bi-pen"></i>
                    </div>
                </a>
            </div>
            <div class="texto">
                <h1><?php echo $dados['nm_usuario']; ?></h1>
                <p><?php echo $dados['nm_nome_usuario']; ?></p>
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
                </div><?php } ?>
            <div class="btn-perfil">
                <div class="coluna">
                    <a href="#">
                        <p>Seguidores</p>
                        <?php if ($seguidores > 0) { ?>
                            <span id="nseguidores"><?php echo $seguidores; ?></span>
                        <?php } else { ?>
                            <span id="nseguidores">0</span>
                        <?php } ?>
                    </a>
                </div>
                <div class="coluna">
                    <a href="#">
                        <p>Seguindo</p>
                        <?php if ($seguindo > 0) { ?>
                            <span id="nseguindo"><?php echo $seguindo; ?></span>
                        <?php } else { ?>
                            <span id="nseguindo">0</span>
                        <?php } ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="form">
            <button class="options-btn" onclick="togglePopup(this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </button>
            <div class="popup hidden">
                <button onclick="window.location.href='Criar/CriarPasta.php'">Criar Pasta</button>
                <button onclick="window.location.href='Criar/CriarPublicacao.php'">Criar Publicação</button>
            </div>
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
                                <button class="options-btn" onclick="event.stopPropagation(); togglePopup(this)">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="popup hidden" onclick="event.stopPropagation()">
                                    <button onclick="window.location.href='editar/EditarPasta.php?id=<?php echo $pas['id_pasta']; ?>&origem=perfil'">Editar Pasta</button>
                                    <button class="abrirModalApagar" data-id-pasta="<?php echo $pas['id_pasta']; ?>" onclick="event.stopPropagation()">Excluir Pasta</button>
                                </div>
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
                    <h1>Você ainda não fez nenhuma publicação</h1>
                    <p>Que tal adicionar sua primeira publicação agora?</p>
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

    <dialog id="modalApagar" class="modal">
        <div class="conteudo-modal">
            <h3>Tem certeza de que deseja apagar essa pasta?</h3>
            <button id="btnNao" class="btn nao">Não</button>
            <button id="btnSim" class="btn sim">Sim</button>
        </div>
    </dialog>
    <script src="img.js"></script>
    <script src="./configuracao/modo.js"></script>
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

        $(document).ready(function() {
            const msg = sessionStorage.getItem('mensagemSucesso');
            if (msg) {
                // cria um elemento temporário para a mensagem
                const aviso = $('<div id="msg">' + msg + '</div>').css('text-align', 'center');
                aviso.css({
                    'position': 'fixed',
                    'bottom': '9vh',
                    'left': '50%',
                    'transform': 'translateX(-50%)',
                    'background': 'rgba(0,0,0,0.5)',
                    'color': 'white',
                    'width': '30vh',
                    'border-radius': '6px',
                    'z-index': '9999',
                    'display': 'none',
                    'padding': '1vh'
                });
                $('body').append(aviso);
                aviso.fadeIn(500).delay(2000).fadeOut(500, function() {
                    aviso.remove(); // remove depois do fadeOut
                });

                sessionStorage.removeItem('mensagemSucesso'); // limpa a mensagem
            }
            $(".abrirModalApagar").on('click', function() {
                const id_pasta = $(this).data('id-pasta');
                const modal = $('#modalApagar');
                modal.data('id_pasta', id_pasta);

                modal[0].showModal();
            });

            $("#btnNao").on('click', function() {
                const id_pasta = $(this).data('id-pasta');
                const modal = $('#modalApagar');
                modal.data('id_pasta', id_pasta);

                modal[0].close();
            });

            $('#btnSim').click(function(e) {
                e.preventDefault();

                const id_pasta = $('#modalApagar').data('id_pasta');

                $.ajax({
                    url: "banco/excluirPasta.php",
                    type: 'POST',
                    data: {
                        id_pasta: id_pasta
                    },
                    success: function(resposta) {
                        sessionStorage.setItem('mensagemSucesso', resposta);
                        window.location.href = "Perfil.php";
                    },
                    error: function() {
                        $('#msg').text("Erro ao excluir pasta.").fadeIn().delay(2000).fadeOut();
                    }
                });
            });
        });
    </script>
</body>

</html>