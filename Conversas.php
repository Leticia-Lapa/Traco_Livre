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

$sql_conversas = "SELECT 
    c.id_conversa,
    
    CASE 
        WHEN c.fk_id_usuario_criador = :id_usuario 
            THEN c.fk_id_usuario_participante
        ELSE c.fk_id_usuario_criador
    END AS id_outro,

    u.img_perfil,
    u.nm_nome_usuario,
    DATE_FORMAT(c.dt_criacao, '%d/%m/%Y') AS data

FROM tb_conversa c

JOIN tb_usuario u 
    ON u.id_usuario = 
        CASE 
            WHEN c.fk_id_usuario_criador = :id_usuario 
                THEN c.fk_id_usuario_participante
            ELSE c.fk_id_usuario_criador
        END

WHERE 
    c.fk_id_usuario_criador = :id_usuario
    OR c.fk_id_usuario_participante = :id_usuario

ORDER BY c.dt_criacao DESC
";
$stmt_con = $conn->prepare($sql_conversas);
$stmt_con->execute([':id_usuario' => $id_usuario]);
$conversas = $stmt_con->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="Pesquisa.js"></script>
    <link rel="stylesheet" href="chat/style.css">
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Conversas</title>
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
        <div class="container">
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

                    <div class="popup-menu hidden">
                        <div onclick="window.location.href='#tudo'">Recentes</div>
                        <div onclick="window.location.href='#seguindo'">Antigas</div>

                    </div>
                </div>
                <div class="l3">
                    <h4>Chat</h4>
                </div>
            </header>
            <?php if (count($conversas) > 0) { ?>
            <div class="texto">
                <h1>Histórico de Conversas</h1>
            </div>
            <div class="form">
                
                <?php foreach ($conversas as $con) { ?>
                <a href="chat/Chat.php?id_conversa=<?= $con['id_conversa'] ?>&id_outrousuario=<?= $con['id_outro'] ?>">
                    <div class="conversa">
                        <img src="<?php echo $con['img_perfil']; ?>" class="avatar">
                        <div class="info">
                            <span><?php echo $con['nm_nome_usuario']; ?></span>
                            <label><?php echo $con['data']; ?></label>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
                <?php } else { ?>
                    <div class="form">
                    <div class="msg">
                        <h1>Você ainda não tem nenhuma conversa.</h1>
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
                <div>
                    <img src="icons/navbar/Principal.png" id="inicio">
                </div>
            </a>
            <a href="Conversas.php">
                <div class="select" onclick="location.reload()">
                    <img src="icons/navbar/Chat.png" id="chat">
                </div>
            </a>
            <a href="Colaboracoes.php">
                <div>
                    <img src="icons/navbar/Colaboracao.png" id="colaboracao">
                </div>
            </a>
        </footer>
    </div>
    <script src="img.js"></script>
    <script src="./configuracao/modo.js"></script>
    <script>
        function toggleMenu(button) {
            const menu = document.querySelector('.popup-menu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', (e) => {
            const clickedInsideMenuBtn = e.target.closest('.menu-btn');
            const clickedInsideMenuPopup = e.target.closest('.popup-menu');

            if (!clickedInsideMenuBtn && !clickedInsideMenuPopup) {
                document.querySelector('.popup-menu')?.classList.add('hidden');
            }
        });
    </script>
</body>

</html>