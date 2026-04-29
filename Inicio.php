<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth/login.php");
    exit;
}
include 'banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$sql_foto = "SELECT img_perfil FROM tb_usuario WHERE id_usuario = '" . $id_usuario . "'";

$stmt = $conn->prepare($sql_foto);
$stmt->execute();
$foto = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['req'])) {
    // ?req= (vazio) significa "Tudo" -> removemos a sessão para voltar ao padrão
    if ($_GET['req'] === '') {
        unset($_SESSION['mostraInicio']);
    } else {
        // Grava na sessão (ex.: ?req=Sigo)
        $_SESSION['mostraInicio'] = $_GET['req'];
    }
}

if (isset($_SESSION['mostraInicio'])) {
    switch ($_SESSION['mostraInicio']) {
        case "Sigo":
            // #Que eu sigo
            $pubs = " SELECT 
    p.id_publicacao,
    DATE_FORMAT(p.dt_hora_publicacao, '%d/%m/%Y %H:%i') AS data_publicacao,
    p.ds_publicacao,

    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil,

    GROUP_CONCAT(DISTINCT i.url SEPARATOR ',') AS imagens,
    GROUP_CONCAT(DISTINCT t.nm_tag SEPARATOR ',') AS tags,

    COUNT(DISTINCT c1.id_curtida) AS total_curtidas,

    MAX(CASE WHEN c2.fk_id_usuario = :idu THEN 1 ELSE 0 END) AS ja_curtiu

FROM tb_publicacao p

JOIN tb_usuario u 
    ON p.fk_id_usuario_criador = u.id_usuario

JOIN tb_seguidores s
    ON s.fk_id_usuario_seguidor = :idu
    AND s.fk_id_usuario_seguido = u.id_usuario

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
    AND c2.fk_id_usuario = :idu

GROUP BY 
    p.id_publicacao,
    p.ds_publicacao,
    p.dt_hora_publicacao,
    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil

ORDER BY p.id_publicacao DESC
";

            break;
        default:
            // #Todos menos o usuario
            $pubs = " SELECT 
    p.id_publicacao,
    DATE_FORMAT(p.dt_hora_publicacao, '%d/%m/%Y %H:%i') AS data_publicacao,
    p.ds_publicacao,

    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil,

    GROUP_CONCAT(DISTINCT i.url SEPARATOR ',') AS imagens,
    GROUP_CONCAT(DISTINCT t.nm_tag SEPARATOR ',') AS tags,

    COUNT(DISTINCT c1.id_curtida) AS total_curtidas,

    MAX(CASE WHEN c2.fk_id_usuario = :idu THEN 1 ELSE 0 END) AS ja_curtiu

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
    AND c2.fk_id_usuario = :idu

WHERE u.id_usuario != :idu

GROUP BY 
    p.id_publicacao,
    p.ds_publicacao,
    p.dt_hora_publicacao,
    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil

ORDER BY p.id_publicacao DESC
";

            break;
    }
} else {
    // #Todos menos o usuario
   $pubs = " SELECT 
    p.id_publicacao,
    DATE_FORMAT(p.dt_hora_publicacao, '%d/%m/%Y %H:%i') AS data_publicacao,
    p.ds_publicacao,

    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil,

    GROUP_CONCAT(DISTINCT i.url SEPARATOR ',') AS imagens,
    GROUP_CONCAT(DISTINCT t.nm_tag SEPARATOR ',') AS tags,

    COUNT(DISTINCT c1.id_curtida) AS total_curtidas,

    MAX(CASE WHEN c2.fk_id_usuario = :idu THEN 1 ELSE 0 END) AS ja_curtiu

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
    AND c2.fk_id_usuario = :idu

WHERE u.id_usuario != :idu

GROUP BY 
    p.id_publicacao,
    p.ds_publicacao,
    p.dt_hora_publicacao,
    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil

ORDER BY p.id_publicacao DESC
";

}

$stmt_pub = $conn->prepare($pubs);
$stmt_pub->execute([':idu' => $id_usuario]);
$publicacao = $stmt_pub->fetchAll(PDO::FETCH_ASSOC);
foreach ($publicacao as &$publi) {
    $publi['imagens'] = $publi['imagens'] ? explode(',', $publi['imagens']) : [];
    $publi['tags'] = $publi['tags'] ? explode(',', $publi['tags']) : [];
}
unset($publi);

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/modal.js" defer></script>
    <script src="Pesquisa.js"></script>
    <script src="scripts/inicio.js" defer></script>

    <link rel="stylesheet" href="Inicio.css">
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Início</title>
</head>

<body>
    <div id="tela-pesquisa" class="overlay-pesquisa">
        <div class="topo-pesquisa">
            <div class="l1-p">
                <button id="btnVolta" class="voltar" onclick="fecha()">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </div>
            <div class="l2-p">
                <div class="pesquisa">
                    <input type="text" placeholder="Pesquisar..." id="barraPesquisa">
                </div>
                <button onclick="Pesquisa()"><i class="bi bi-search"></i></button>
            </div>
            <script src="pesquisa/Pesquisa.js"></script>
        </div>
    </div>
    <div id="conteudo">
        <header class="top-bar">
            <div class="l1">
                <div class="perfil">
                    <a href="Perfil.php"><img src="<?php echo $foto['img_perfil']; ?>" alt="Usuário" class="avatar"></a>
                </div>

                <div class="notificacoes">
                    <a href="Notificacao.html"><i class="bi bi-bell"></i></a>
                    <!-- <span class="badge">2</span> -->
                </div>
            </div>

            <div class="l2">
                <div class="pesquisa" id="pes" class="pesquisa compact" onclick="pesquisa()">
                    <input type="text" placeholder="Pesquisar..." readonly>
                    <i class="bi bi-search"></i>
                </div>
                <button class="menu-btn" onclick="toggleMenu(this)">
                    <i class="bi bi-list"></i>
                </button>

                <!--popup filtro-->
                <div class="popup-menu hidden">
                    <div onclick="window.location.href='?req='">Tudo</div>
                    <div onclick="window.location.href='?req=Sigo'">Seguindo</div>
                </div>
            </div>

            <div class="l3">
                <h4>Início</h4>
            </div>
        </header>

        <div class="feed">
            <?php foreach ($publicacao as $publi) { ?>
                <div class="post">
                    <div class="post-header">
                        <img class="avatar" src="<?php echo $publi['img_perfil']; ?>" alt="Usuário">
                        <span class="nomeusuario" onclick="window.location.href='OutroPerfil.php?id=<?php echo $publi['id_usuario']; ?>'"><?php echo $publi['nm_nome_usuario']; ?></span>
                        <button class="options-btn" onclick="togglePopup(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="popup hidden">
                            <!-- <button>Não tenho interesse</button> -->
                            <button onclick="modalDenuncia()">Denunciar publicação</button>
                        </div>
                    </div>

                    <div class="conteudo">
                        <div class="post-imagem">
                            <?php foreach ($publi['imagens'] as $img) { ?>
                                <img src="<?php echo $img; ?>" alt="Postagem">
                            <?php } ?>
                        </div>

                        <div class="post-texto">
                            <h3 class="titulo">Descrição</h3>
                            <p class="descricao"><?php echo $publi['ds_publicacao']; ?></p>

                            <div class="post-tags">
                                <?php foreach ($publi['tags'] as $tag) { ?>
                                    <span class="tag"><?php echo $tag; ?></span>
                                <?php } ?>
                              
                            </div>
                        </div>
                    </div>
                    <div class="baixo">
                        <label><?php echo $publi['data_publicacao']; ?></label>
                        <div class="interacoes">
                            <button onclick="curtir(this)" data-id="<?php echo $publi['id_publicacao']; ?>" data-origem="pub">
                                <i class="bi <?php echo ($publi['ja_curtiu'] > 0 ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'); ?>"></i></button>
                            <span class="likeCount" data-id="<?php echo $publi['id_publicacao']; ?>"><?php echo $publi['total_curtidas']; ?></span>
                            <button class="abrirModalComentarios" data-id=<?php echo $publi['id_publicacao']; ?>><i class="bi bi-chat-left"></i></button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
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
                <div class="select" onclick="location.reload()">
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

        <!-- MODAL DE DENÚNCIAR PUBLICAÇÃO -->
        <dialog id="modalDenuncia" class="modal">
            <div class="conteudo-modal">
                <h3>Por qual motivo deseja denúnciar essa publicação?</h3>
                <div class="form">
                    <div class="opcao" id="opcao01">
                        <p>Conteúdo Impróprio</p>
                    </div>
                    <div class="opcao" id="opcao02">
                        <p>Violação dos Direitos Autorais</p>
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
    </div>
    <script src="img.js"></script>
    <script src="./configuracao/modo.js"></script>
    <script>
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