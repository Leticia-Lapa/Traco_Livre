<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.html");
    exit;
}
include 'banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_publicacao = $_GET['id'] ?? null;
if (!$id_publicacao) {
    echo "Publicação não encontrada.";
    exit;
}

$id_pasta = $_GET['id_pasta'] ?? null;
$origem = $_GET['origem'] ?? null;

$sql_detalhes = "SELECT 
    p.id_publicacao,
    DATE_FORMAT(p.dt_hora_publicacao, '%d/%m/%Y %H:%i') AS data_publicacao,
    p.ds_publicacao,
    p.fk_id_usuario_criador,
    u.nm_nome_usuario,
    u.img_perfil,

    GROUP_CONCAT(DISTINCT i.url SEPARATOR ',') AS imagens,
    GROUP_CONCAT(DISTINCT t.nm_tag SEPARATOR ',') AS tags,

    COUNT(DISTINCT c1.id_curtida) AS total_curtidas,

    MAX(CASE WHEN c2.fk_id_usuario = $id_usuario THEN 1 ELSE 0 END) AS ja_curtiu

FROM tb_publicacao p

JOIN tb_usuario u 
    ON p.fk_id_usuario_criador = u.id_usuario

LEFT JOIN tb_imagens_publicacao i
    ON i.fk_id_publicacao = p.id_publicacao

LEFT JOIN tb_publicacao_tag pt
    ON pt.fk_id_publicacao = p.id_publicacao

LEFT JOIN tb_tag t
    ON t.id_tag = pt.fk_id_tag

LEFT JOIN tb_curtidas c1
    ON c1.fk_id_publicacao = p.id_publicacao

LEFT JOIN tb_curtidas c2
    ON c2.fk_id_publicacao = p.id_publicacao
   AND c2.fk_id_usuario = $id_usuario

WHERE p.id_publicacao = $id_publicacao

GROUP BY 
    p.id_publicacao,
    p.dt_hora_publicacao,
    p.ds_publicacao,
    p.fk_id_usuario_criador,
    u.nm_nome_usuario,
    u.img_perfil
";

$stmt = $conn->prepare($sql_detalhes);
$stmt->execute();
$publicacao = $stmt->fetch(PDO::FETCH_ASSOC);

$publicacao['imagens'] = $publicacao['imagens'] ? explode(',', $publicacao['imagens']) : [];
$publicacao['tags'] = $publicacao['tags'] ? explode(',', $publicacao['tags']) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/funcoesPublicacao.js?v=2" defer></script>
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Publicacao</title>
    <link rel="stylesheet" href="Publicacao.css">
    <link rel="stylesheet" href="Inicio.css">
    <script src="scripts/inicio.js" defer></script>
    <script src="Pesquisa.js"></script>
</head>

<body>
    <div class="container">
        <?php if ($origem === 'pasta') { ?>
            <button id="btnVolta" class="voltar" onclick="window.location.href='Pasta.php?id=<?php echo $id_pasta; ?>'">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php } elseif ($id_usuario == $publicacao['fk_id_usuario_criador']) { ?>
            <button id="btnVolta" class="voltar" onclick="window.location.href='Perfil.php'">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php } else { ?>
            <button id="btnVolta" class="voltar" onclick="window.location.href='OutroPerfil.php?id=<?php echo $publicacao['fk_id_usuario_criador']; ?>'">
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
                                <button onclick="window.location.href='editar/EditarPublicacao.php?id=<?php echo $publicacao['id_publicacao']; ?>'">Editar publicação</button>
                                <button onclick="modalExcluir()">Excluir publicação</button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="usuario-criador" style="display: none;"></div>
                    <?php } ?>
                </div>

                <div class="conteudo">
                    <div class="post-imagemp">
                        <?php foreach ($publicacao['imagens'] as $img) { ?>
                            <img src="<?php echo $img; ?>" alt="Postagem">
                        <?php } ?>
                    </div>
                    <div class="post-texto">
                        <h3 class="titulo">Descrição</h3>
                        <p class="descricao"><?php echo $publicacao['ds_publicacao'] ?></p>

                        <div class="post-tags">
                            <?php foreach ($publicacao['tags'] as $tag) { ?>
                                <span class="tag"><?php echo $tag; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="baixo">
                    <label><?php echo $publicacao['data_publicacao']; ?></label>
                    <div class="interacoes">
                        <button onclick="curtir(this)" data-id="<?php echo $publicacao['id_publicacao']; ?>" data-origem = "pub">
                            <i class="bi <?php echo ($publicacao['ja_curtiu'] > 0 ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'); ?>"></i>
                        </button>
                        <span class="likeCount" data-id="<?php echo $publicacao['id_publicacao']; ?>"><?php echo $publicacao['total_curtidas']; ?></span>
                        <button class="abrirModalComentarios" data-id="<?php echo $publicacao['id_publicacao']; ?>">
                            <i class="bi bi-chat-left"></i> </button></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL DE COMENTARIOS -->
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
                    <button class="btn-enviar"><img src="icons/enviar.png" alt=""></button>
                </div>
            </div>
        </dialog>

        <!-- MODAL DE OPÇÃO DE DENUNCIAR COMENTARIO -->
        <dialog id="modalDeComentario" class="modal-acao">
            <div class="conteudo-modal">
                <div class="modal-topo">
                    <button id="bVolt" class="btn-voltar">
                        <img src="icons/volta.png" alt="Voltar">
                    </button>
                    <h3>Opções</h3>
                </div>
                <div class="form">
                    <div class="opcao" onclick="denuComen()">
                        <p>Denúnciar Comentário</p>
                    </div>
                </div>
            </div>
        </dialog>

        <!-- MODAL DE OPÇÃO DE EXCLUIR COMENTARIO -->
        <dialog id="modalApComentario" class="modal-acao">
            <div class="conteudo-modal">
                <div class="modal-topo">
                    <button id="btVolt" class="btn-voltar">
                        <img src="icons/volta.png" alt="Voltar">
                    </button>
                    <h3>Opções</h3>
                </div>
                <div class="form">
                    <div class="opcao" onclick="apCom()">
                        <p>Excluir Comentário</p>
                    </div>
                </div>
            </div>
        </dialog>

        <!-- MODAL DE DENUNCIAR COMENTARIO -->
        <dialog id="DenunComent" class="modal">
            <div class="conteudo-modal">
                <h3>Por qual motivo deseja denúnciar esse comentário?</h3>
                <div class="form">
                    <div class="opcao" id="impropio">
                        <p>Conteúdo Impróprio</p>
                    </div>
                    <div class="opcao" id="odio">
                        <p>Discurso de Ódio</p>
                    </div>
                    <div class="opcao" id="spam">
                        <p>Spam</p>
                    </div>
                    <div class="opcao" id="outro">
                        <p>Outro</p>
                    </div>
                </div>
                <button id="bnCancelar" class="btn cancelar">Cancelar</button>
            </div>
        </dialog>

        <!-- MODAL DE CONFIRMAR EXCLUSÃO DE COMENTARIO -->
        <dialog id="modalEx" class="modal-acao-2">
            <div class="conteudo-modal-acao-2">
                <h3>Tem certeza de que deseja excluir esse comentário?</h3>
                <button id="btnNao" class="btn nao">Não</button>
                <button id="btnSim" class="btn sim">Sim</button>
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
    <script src="./configuracao/modo.js"></script>
    <script>
        const ModalExcluir = document.getElementById('modalExcluir');

        function modalExcluir() {
            ModalExcluir.showModal(); // Exibe o modal
        }

        function fecharModalExcluir() {
            ModalExcluir.close();
        }


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