<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.html");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_colaboracao = $_GET['id'] ?? null;
if (!$id_colaboracao) {
    echo "Publicação não encontrada.";
    exit;
}
$sql_dados_pub = "SELECT c.id_colaboracao, 
c.ds_colaboracao,
DATE_FORMAT(c.dt_hora_colaboracao, '%d/%m/%Y %H:%i') as data_colaboracao,
c.fk_id_usuario_criador, 
u.nm_nome_usuario, 
u.img_perfil, 
c.nm_titulo_colaboracao,
   (
        SELECT COUNT(*)
        FROM tb_curtidas c1
        WHERE c1.fk_id_colaboracao = c.id_colaboracao
    ) AS total_curtidas,
    (
    SELECT COUNT(*)
    FROM tb_curtidas c2
    WHERE c2.fk_id_colaboracao = c.id_colaboracao
    AND c2.fk_id_usuario = '" . $id_usuario . "'
) AS ja_curtiu
        FROM tb_colaboracao c
        JOIN tb_usuario u ON c.fk_id_usuario_criador = u.id_usuario
        WHERE c.id_colaboracao = '" . $id_colaboracao . "'";
$stmt_dados_pub = $conn->prepare($sql_dados_pub);
$stmt_dados_pub->execute();
$colaboracao = $stmt_dados_pub->fetch(PDO::FETCH_ASSOC);


$sql_tags = "SELECT t.nm_tag 
             FROM tb_colaboracao_tag pt 
             JOIN tb_tag t ON t.id_tag = pt.fk_id_tag 
             WHERE pt.fk_id_colaboracao = '" . $id_colaboracao . "'";
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
    <title>colaboracao</title>
    <link rel="stylesheet" href="ColaboraDeta.css">
    <link rel="stylesheet" href="../Inicio.css">
</head>

<body>
    <div class="container">
        <?php if ($id_usuario == $colaboracao['fk_id_usuario_criador']) { ?>
            <!-- window.location.href='Perfil.php' -->
            <button id="btnVolta" class="voltar" onclick="window.history.back();">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php } else { ?>
            <!-- window.location.href='OutroPerfil.php?id=
    <?php //echo $colaboracao['fk_id_usuario_criador'];
    ?>
    ' -->
            <button id="btnVolta" class="voltar" onclick="window.history.back();">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php } ?>
        <div class="feedp">
            <div class="post">
                <div class="post-header">
                    <img class="avatar" src="<?php echo $colaboracao['img_perfil']; ?>" alt="Usuário">
                    <span class="nomeusuario"><?php echo $colaboracao['nm_nome_usuario']; ?></span>

                    <?php if ($id_usuario == $colaboracao['fk_id_usuario_criador']) { ?>
                        <div class="usuario-criador">
                            <button class="options-btn" onclick="togglePopup(this)">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="popup hidden">
                                <button onclick="window.location.href='../editar/Editarcolaboracao.php?id=<?php echo $colaboracao['id_colaboracao']; ?>'">Editar Colaboração</button>
                                <button onclick="modalExcluir()">Excluir Colaboração</button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="usuario-criador" style="display: none;"></div>
                    <?php } ?>
                </div>

                <h3 class="titulo"><?php echo $colaboracao['nm_titulo_colaboracao']; ?></h3>
                <div class="conteudo">
                    <div class="post-texto">
                        <h3 class="titulo">Descrição</h3>
                        <p class="descricao"><?php echo $colaboracao['ds_colaboracao'] ?></p>

                        <div class="post-tags">
                            <?php foreach ($tags as $tag) { ?>
                                <span class="tag"><?php echo $tag; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="baixo">
                    <label><?php echo $colaboracao['data_colaboracao']; ?></label>
                    <div class="interacoes">
                        <button onclick="curtir(this)" data-id="<?php echo $colaboracao['id_colaboracao']; ?>" data-origem="cob">
                            <i class="bi <?php echo ($colaboracao['ja_curtiu'] > 0 ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'); ?>"></i>
                        </button>
                        <span class="likeCount" data-id="<?php echo $colaboracao['id_colaboracao']; ?>"><?php echo $colaboracao['total_curtidas']; ?></span>
                        <button class="abrirModalComentarios" data-id="<?php echo $colaboracao['id_colaboracao']; ?>" data-tipo = "cob">
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
                    <button class="btn-enviar" data-tipo="cob"><img src="../icons/enviar.png" alt=""></button>
                </div>
            </div>
        </dialog>

        <dialog id="modalExcluir" class="modal-acao">
            <div class="conteudo-modal-acao">
                <h3>Tem certeza de que deseja excluir essa colaboração?</h3>
                <button id="btnNao" class="btn nao" onclick="fecharModalExcluir()">Não</button>
                <button id="btnSim" class="btn sim" data-id-colaboracao="<?php echo $id_colaboracao; ?>">Sim</button>
            </div>
        </dialog>
    </div>
    <div id="msg"></div>
    <script>
        const ModalExcluir = document.getElementById('modalExcluir');

        function modalExcluir() {
            ModalExcluir.showModal(); // Exibe o modal
        }

        function fecharModalExcluir() {
            ModalExcluir.close();
        }

        $(document).ready(function() {
            $('#btnSim').on('click', function() {
                const modal = $('#modalExcluir');
                const postAtual = modal.data('post-id');

                $.ajax({
                    url: '../banco/excluirColaboracao.php',
                    type: 'POST',
                    data: {
                        id: postAtual
                    },
                    success: function(resposta) {
                        sessionStorage.setItem('mensagemSucesso', resposta);
                        window.location.href = "../Pesquisa.php";
                    },

                    error: function(xhr) {
                        $('#msg').text("Erro ao excluir colaboração.").fadeIn().delay(2000).fadeOut();
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