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
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Estilos</title>
    <script src="Pesquisa.js"></script>
    <link rel="stylesheet" href="Estilo.css">
    <link rel="stylesheet" href="modoEscuro.css">
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
                </div>
            </div>
            <div class="l2">
                <div id="pes" class="pesquisa compact" onclick="pesquisa()">
                    <input type="text" placeholder="Pesquisar..." readonly>
                    <i class="bi bi-search" alt="Buscar" class="search-icon"></i>
                </div>
            </div>
            <div class="l3">
                <h4>Estilos</h4>
            </div>
        </header>
        <div class="form-container">
            <div class="container">
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=1'">
                    <img src="estilos/abstrata.jpg" alt="Abstrato">
                    <span>Abstrato</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=12'">
                    <img src="estilos/frutas.jpg" alt="Semi-realista">
                    <span>Semi-realista</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=10'">
                    <img src="estilos/vangogh.jpg" alt="Pixel Art">
                    <span>Pixel Art</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=11'">
                    <img src="estilos/abelha.jpeg" alt="Realista">
                    <span>Realista</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=7'">
                    <img src="estilos/FURRY.jpg" alt="Furry">
                    <span>Furry</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=6'">
                    <img src="estilos/chibi.jfif" alt="Chibi">
                    <span>Chibi</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=2'">
                    <img src="estilos/anime.jpg" alt="Anime">
                    <span>Anime</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=5'">
                    <img src="estilos/hq.jpg" alt="Comic/HQ">
                    <span>Comic/HQ</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=9'">
                    <img src="estilos/paisagem.jpg" alt="Paisagem">
                    <span>Paisagem</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=4'">
                    <img src="estilos/cartoon.jfif" alt="Cartoon">
                    <span>Cartoon</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=8'">
                    <img src="estilos/minimalista.jpg" alt="Minimalista">
                    <span>Minimalista</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=3'">
                    <img src="estilos/caricatura.jfif" alt="Caricatura">
                    <span>Caricatura</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=13'">
                    <img src="estilos/tradicional.jpg" alt="Tradicional">
                    <span>Tradicional (No Papel)</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=14'">
                    <img src="estilos/original.jpg" alt="Original">
                    <span>Original</span>
                </div>
                <div class="coluna" onclick="window.location.href = 'EstiloEscolhido.php?id=15'">
                    <div id="img" style="background: #0a7269;"></div>
                    <span>Outro</span>
                </div>
            </div>
        </div>

        <footer class="navegacao">
            <a href="Relatos.php">
                <div>
                    <img src="icons/navbar/Relatos.png" id="relato">
                </div>
            </a>
            <a href="Estilo.php">
                <div class="select" onclick="location.reload()">
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
    </div>
    <script src="img.js"></script>
    <script src="./configuracao/modo.js"></script>

</body>

</html>