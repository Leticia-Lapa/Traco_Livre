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

$cobs = " SELECT 
    c.id_colaboracao,
    c.nm_titulo_colaboracao,
    c.ds_colaboracao,
    DATE_FORMAT(c.dt_hora_colaboracao, '%d/%m/%Y %H:%i') AS data_colaboracao,
    
    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil,
    
    GROUP_CONCAT(DISTINCT t.nm_tag SEPARATOR ',') AS tags,
    
    COUNT(DISTINCT c1.id_curtida) AS total_curtidas,

    MAX(CASE WHEN c2.fk_id_usuario = $id_usuario THEN 1 ELSE 0 END) AS ja_curtiu

FROM tb_colaboracao c
JOIN tb_usuario u 
    ON c.fk_id_usuario_criador = u.id_usuario

LEFT JOIN tb_colaboracao_tag ct 
    ON ct.fk_id_colaboracao = c.id_colaboracao

LEFT JOIN tb_tag t 
    ON t.id_tag = ct.fk_id_tag

LEFT JOIN tb_curtidas c1 
    ON c1.fk_id_colaboracao = c.id_colaboracao

LEFT JOIN tb_curtidas c2 
    ON c2.fk_id_colaboracao = c.id_colaboracao 
    AND c2.fk_id_usuario = $id_usuario

GROUP BY 
    c.id_colaboracao,
    c.nm_titulo_colaboracao,
    c.ds_colaboracao,
    c.dt_hora_colaboracao,
    u.id_usuario,
    u.nm_nome_usuario,
    u.img_perfil

ORDER BY data_colaboracao DESC
";
$stmt_cob = $conn->prepare($cobs);
$stmt_cob->execute();
$colaboracao = $stmt_cob->fetchAll(PDO::FETCH_ASSOC);
foreach ($colaboracao as &$cob) {
    $cob['tags'] = $cob['tags'] ? explode(',', $cob['tags']) : [];
}
unset($cob);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/colaboracao.js" defer></script>
    <script src="Pesquisa.js"></script>
    <link rel="stylesheet" href="ColaboracaoRelato.css">
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Colaborações</title>
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
                <script src="pesquisa/Pesquisa.js"></script>
            </div>
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
                <div class="pesquisa" id="pes" class="compact" onclick="pesquisa()">
                    <input type="text" placeholder="Pesquisar..." readonly>
                    <i class="bi bi-search" alt="Buscar" class="search-icon"> </i>
                </div>
                <button class="menu-btn" onclick="toggleMenu(this)">
                    <i class="bi bi-list"></i>
                </button>

                <!--popup filtro-->
                <div class="popup-menu hidden">
                    <div onclick="window.location.href='#tudo'">Voluntário</div>
                    <div onclick="window.location.href='#seguindo'">Remunerado</div>
                    <div onclick="window.location.href='#meus-relatos'">Minhas Colaborações</div>
                </div>
            </div>

            <div class="l3">
                <h4>Colaborações</h4>
            </div>
        </header>

        <div class="feed-container">
            <div class="feed">
                <?php foreach ($colaboracao as $cob) { ?>
                    <div class="post">
                        <div class="post-header">
                            <img class="avatar" src="<?php echo $cob['img_perfil']; ?>" alt="Usuário">
                            <?php if ($cob['id_usuario'] == $id_usuario) { ?>
                                <span class="nomeusuario" onclick="window.location.href='Perfil.php?id=<?php echo $cob['id_usuario']; ?>'"><?php echo $cob['nm_nome_usuario']; ?></span>
                            <?php } else { ?>
                                <span class="nomeusuario" onclick="window.location.href='OutroPerfil.php?id=<?php echo $cob['id_usuario']; ?>'"><?php echo $cob['nm_nome_usuario']; ?></span>
                            <?php } ?>
                            <button class="options-btn" onclick="togglePopup(this)">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="popup hidden">
                                <?php if ($cob['id_usuario'] == $id_usuario) { ?>
                                    <button onclick="window.location.href='editar/EditarColaboracao.php?id=<?php echo $cob['id_colaboracao']; ?>'">Editar Colaboração</button>
                                    <button class="abrirModalExcluir" data-id=<?php echo $cob['id_colaboracao']; ?>>Excluir Colaboração</button>
                                <?php } else { ?>
                                    <button onclick="modalDenuncia()">Denunciar Colaboração</button>
                                <?php } ?>
                            </div>
                        </div>
                        <h3 class="titulo"><?php echo $cob['nm_titulo_colaboracao']; ?></h3>
                        <div class="conteudo">
                            <div class="post-texto">
                                <h3 class="titulo">Descrição</h3>
                                <p class="descricao"><?php echo $cob['ds_colaboracao']; ?></p>

                                <div class="post-tags">
                                    <?php foreach ($cob['tags'] as $tag) { ?>
                                        <span class="tag"><?php echo $tag; ?></span>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="baixo">
                            <label><?php echo $cob['data_colaboracao']; ?></label>
                            <div class="interacoes">
                                <button onclick="curtir(this)" data-id="<?php echo $cob['id_colaboracao']; ?>" data-origem = "cob">
                                    <i class="bi <?php echo ($cob['ja_curtiu'] > 0 ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'); ?>"></i></button>
                                <span class="likeCount" data-id="<?php echo $cob['id_colaboracao']; ?>"><?php echo $cob['total_curtidas']; ?></span>
                                <button class="abrirModalComentarios" data-id=<?php echo $cob['id_colaboracao']; ?>><i class="bi bi-chat-left"></i></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <button class="fab-btn" onclick="window.location.href='Criar/CriarColaboracao.php'">
                <img src="icons/mais.png" alt="Adicionar" />
            </button>
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
                <div class="select" onclick="location.reload()">
                    <img src="icons/navbar/Colaboracao.png" id="colaboracao">
                </div>
            </a>
        </footer>
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


        <dialog id="modalDenuncia" class="modal">
            <div class="conteudo-modal">
                <h3>Por qual motivo deseja denúnciar essa colaboração?</h3>
                <div class="form">
                    <div class="opcao" id="opcao01">
                        <p>Conteúdo Impróprio</p>
                    </div>
                    <div class="opcao" id="opcao02">
                        <p>Golpe ou Fraude</p>
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

        <dialog id="modalExcluir" class="modal-acao">
            <div class="conteudo-modal-acao">
                <h3>Tem certeza de que deseja excluir esse relato?</h3>
                <button id="btnNao" class="btn nao">Não</button>
                <button id="btnSim" class="btn sim">Sim</button>
            </div>
        </dialog>
    </div>
    <script src="img.js"></script>
    <script src="./configuracao/modo.js"></script>
    <script>
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