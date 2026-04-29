<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.html");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_publicacao = $_GET['id'] ?? null;
if (!$id_publicacao) {
    echo "Publicação não encontrada.";
    exit;
}
$sql_dados_pub = "SELECT p.id_publicacao,
p.ds_publicacao,
DATE_FORMAT(p.dt_hora_publicacao, '%d/%m/%Y %H:%i') as data_publicacao, 
p.fk_id_usuario_criador, 
u.nm_nome_usuario,
u.img_perfil,
   (
        SELECT COUNT(*)
        FROM tb_curtidas c
        WHERE c.fk_id_publicacao = p.id_publicacao
    ) AS total_curtidas,
    (
    SELECT COUNT(*)
    FROM tb_curtidas c2
    WHERE c2.fk_id_publicacao = p.id_publicacao
    AND c2.fk_id_usuario = '" . $id_usuario . "'
) AS ja_curtiu
        FROM tb_publicacao p
        JOIN tb_usuario u ON p.fk_id_usuario_criador = u.id_usuario
        WHERE p.id_publicacao = '" . $id_publicacao . "'";
$stmt_dados_pub = $conn->prepare($sql_dados_pub);
$stmt_dados_pub->execute();
$publicacao = $stmt_dados_pub->fetch(PDO::FETCH_ASSOC);

$sql_imgs = "SELECT url FROM tb_imagens_publicacao WHERE fk_id_publicacao = '" . $id_publicacao . "'";
$stmt_imgs = $conn->prepare($sql_imgs);
$stmt_imgs->execute();
$imagens = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);

$sql_tags = "SELECT t.nm_tag 
             FROM tb_publicacao_tag pt 
             JOIN tb_tag t ON t.id_tag = pt.fk_id_tag 
             WHERE pt.fk_id_publicacao = '" . $id_publicacao . "'";
$stmt_tags = $conn->prepare($sql_tags);
$stmt_tags->execute();
$tags = $stmt_tags->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="pub.js" defer></script>
    <script src="../configuracao/modo.js" defer></script>
    <link rel="stylesheet" href="../modoEscuro.css">
    <title>Publicacao</title>
    <link rel="stylesheet" href="Publicacao.css">
    <link rel="stylesheet" href="../Inicio.css">
</head>

<body>
    <div class="container">
        <?php if ($id_usuario == $publicacao['fk_id_usuario_criador']) { ?>
            <!-- window.location.href='Perfil.php' -->
            <button id="btnVolta" class="voltar" onclick="window.history.back();">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php } else { ?>
            <!-- window.location.href='OutroPerfil.php?id=
    <?php //echo $publicacao['fk_id_usuario_criador'];
    ?>
    ' -->
            <button id="btnVolta" class="voltar" onclick="window.history.back();">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php } ?>
        <div class="feedp">
            <div class="post">
                <div class="post-header">
                    <img class="avatar" src="<?php echo $publicacao['img_perfil']; ?>" alt="Usuário">
                    <span class="nomeusuario"><?php echo $publicacao['nm_nome_usuario']; ?></span>

                    <?php if ($id_usuario == $publicacao['fk_id_usuario_criador']) { ?>
                        <div class="usuario-criador">
                            <button class="options-btn" onclick="togglePopup(this)">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="popup hidden">
                                <button onclick="window.location.href='editar/EditarPublicacao.php?id=<?php echo $publicacao['id_publicacao']; ?>'">Editar Publicação</button>
                                <button onclick="modalExcluir()">Excluir Publicação</button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="usuario-criador" style="display: none;"></div>
                    <?php } ?>
                </div>

                <div class="conteudo">
                    <div class="post-imagemp">
                        <?php foreach ($imagens as $img) { ?>
                            <img src="<?php echo $img['url']; ?>" alt="Postagem">
                        <?php } ?>
                    </div>
                    <div class="post-texto">
                        <h3 class="titulo">Descrição</h3>
                        <p class="descricao"><?php echo $publicacao['ds_publicacao'] ?></p>

                        <div class="post-tags">
                            <?php foreach ($tags as $tag) { ?>
                                <span class="tag"><?php echo $tag; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="baixo">
                    <label><?php echo $publicacao['data_publicacao']; ?></label>
                    <div class="interacoes">
                        <button onclick="curtir(this)" data-id="<?php echo $publicacao['id_publicacao']; ?>" data-origem="pub">
                            <i class="bi <?php echo ($publicacao['ja_curtiu'] > 0 ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'); ?>"></i>
                        </button>
                        <span class="likeCount" data-id="<?php echo $publicacao['id_publicacao']; ?>"><?php echo $publicacao['total_curtidas']; ?></span>
                        <button class="abrirModalComentarios" data-id="<?php echo $publicacao['id_publicacao']; ?>" data-tipo="pub">
                            <i class="bi bi-chat-left"></i> </button></i></button>
                    </div>
                </div>
            </div>
        </div>
        <dialog id="modalComentario" class="modal">
            <div class="modal-conteudo">
                <div class="modal-topo">
                    <button id="btnVolt" class="btn-voltar">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h3>Comentários</h3>
                </div>
                <div class="form-comentario" id="lista-comentarios">

                </div>
                <div class="grupo-input">
                    <input type="text" placeholder="Digite..." id="comentario">
                    <button class="btn-enviar" data-tipo = "pub"><img src="../icons/enviar.png" alt=""></button>
                </div>
            </div>
        </dialog>

        <dialog id="modalExcluir" class="modal-acao">
            <div class="conteudo-modal-acao">
                <h3>Tem certeza de que deseja excluir essa publicação?</h3>
                <button id="btnNao" class="btn nao" onclick="fecharModalExcluir()">Não</button>
                <button id="btnSim" class="btn sim" data-id-publicacao="<?php echo $id_publicacao; ?>">Sim</button>
            </div>
        </dialog>
    </div>
    <script>
        const ModalExcluir = document.getElementById('modalExcluir');

        function modalExcluir() {
            ModalExcluir.showModal(); // Exibe o modal
        }

        function fecharModalExcluir() {
            ModalExcluir.close();
        }

        $(document).ready(function() {
            $(document).on('click', '#btnSim', function(e) {
                e.preventDefault();

                const id_publicacao = $(this).data('id-publicacao');
                const dados = {
                    'id_publicacao': id_publicacao
                };

                $.ajax({
                    url: "../banco/excluirPublicacao.php",
                    type: 'POST',
                    data: dados,
                    success: function(resposta) {
                        sessionStorage.setItem('mensagemSucesso', resposta);
                        window.location.href = "../Pesquisa.php";
                    },
                    error: function(xhr) {
                        $('#msg').text("Erro ao excluir publicação.").fadeIn().delay(2000).fadeOut();
                    }
                });
            });
        });

        // Alterna o popup do botão de opções (3 pontinhos dos posts)
        function togglePopup(button) {
            // Fecha todos os popups dos posts, menos o clicado
            document.querySelectorAll('.popup').forEach(p => {
                if (p !== button.nextElementSibling) {
                    p.classList.add('hidden');
                }
            });

            // Fecha o menu de filtro, se estiver aberto
            document.querySelector('.popup-menu')?.classList.add('hidden');

            // Abre ou fecha o popup do post clicado
            const popup = button.nextElementSibling;
            popup.classList.toggle('hidden');
        }

        // Alterna o menu do filtro
        function toggleMenu(button) {
            const menu = document.querySelector('.popup-menu');

            // Fecha todos os popups dos posts
            document.querySelectorAll('.popup').forEach(p => p.classList.add('hidden'));

            // Alterna o menu do filtro usando a classe 'hidden'
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', (e) => {
            const clickedInsidePostBtn = e.target.closest('.options-btn');
            const clickedInsidePostPopup = e.target.closest('.popup');
            const clickedInsideMenuBtn = e.target.closest('.menu-btn');
            const clickedInsideMenuPopup = e.target.closest('.popup-menu');

            if (!clickedInsidePostBtn && !clickedInsidePostPopup && !clickedInsideMenuBtn && !clickedInsideMenuPopup) {
                document.querySelectorAll('.popup').forEach(p => p.classList.add('hidden'));
                document.querySelector('.popup-menu')?.classList.add('hidden');
            }
        });
    </script>

</body>

</html>