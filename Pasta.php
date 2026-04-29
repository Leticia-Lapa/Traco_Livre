<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: auth/login.html");
    exit;
}
include 'banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$id_pasta = $_GET['id'] ?? null;
if (!$id_pasta) {
    echo "Pasta não encontrada.";
    exit;
}
$sql_pasta = "SELECT nm_titulo_pasta, ds_pasta, fk_id_usuario_criador
FROM tb_pasta tp
WHERE tp.id_pasta= '".$id_pasta."'";
$stmt_pasta = $conn->prepare($sql_pasta);
$stmt_pasta->execute();
$pasta = $stmt_pasta ->fetch(PDO::FETCH_ASSOC);

$sql_publis="SELECT p.id_publicacao, i.url
            FROM tb_publicacao p
            JOIN tb_conteudo_pasta cp ON p.id_publicacao = cp.fk_id_publicacao
            JOIN tb_imagens_publicacao i ON p.id_publicacao = i.fk_id_publicacao
            WHERE cp.fk_id_pasta = '".$id_pasta."'
            AND i.id_imagem = (
                SELECT MIN(i2.id_imagem)
                FROM tb_imagens_publicacao i2
                WHERE i2.fk_id_publicacao = p.id_publicacao
            );";
$stmt_publis = $conn->prepare($sql_publis);
$stmt_publis->execute();
$publicacoes = $stmt_publis->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/publisPasta.js" defer></script>
    <link rel="stylesheet" href="Pasta.css">
    <link rel="stylesheet" href="modoEscuro.css">
    <title>Pasta</title>
</head>
<body>
    <div class="container">
        <div class="topo">
           <?php if($id_usuario == $pasta['fk_id_usuario_criador']){ ?>
            <button id="btnVolta" class="voltar" onclick="window.location.href='Perfil.php'">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php }else{ ?>
            <button id="btnVolta" class="voltar" onclick="window.location.href='OutroPerfil.php?id=<?php echo $pasta['fk_id_usuario_criador'];?>'">
                <i class="bi bi-chevron-left"></i>
            </button>
        <?php }?>
             
            <?php if($id_usuario == $pasta['fk_id_usuario_criador']){?>
            <div class="usuario-criador">
                <button class="menu-btn" onclick="toggleMenu(this)">
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                </button>
                <div class="popup-menu hidden">
                    <div onclick="window.location.href='#tudo'">Mais Recente</div>
                    <div onclick="window.location.href='#seguindo'">Mais Antigo</div>
                    <!-- <div onclick="window.location.href='#meus-relatos'">Meus Relatos</div> -->
                </div>
            </div>
            <?php }else{?>
                <div class="usuario-criador" style="display: none;"></div>
            <?php }?>
        </div>
        <div class="pasta-container">
            <div class="cont-header">
                <h1><?php echo $pasta['nm_titulo_pasta'];?></h1>
                <?php if($id_usuario == $pasta['fk_id_usuario_criador']){?>
                    <div class="usuario-criador">
                        <button class="options-btn" onclick="togglePopup(this)">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="popup hidden">
                            <button onclick="window.location.href='editar/EditarPasta.php?id=<?php echo $id_pasta;?>&origem=pasta'">Editar Pasta</button>
                            <button onclick="abrirModalApagar()">Excluir Pasta</button>
                        </div>
                    </div>
                <?php }else{?>
                        <div class="usuario-criador" style="display: none;"></div>
                <?php }?>
            </div>
            <div class="bio">
                <h1>Descrição</h1>
                <p><?php echo $pasta['ds_pasta'];?></p>
            </div>
        </div>
        <?php if($id_usuario == $pasta['fk_id_usuario_criador']){?>
            <div class="form">
                <button class="btn-form" onclick="abrirModalRemover()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                </button>
                <button class="btn-form" onclick="abrirModalAdicionar()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </button>
            </div>
        <?php }else{?>
            <div class="form" style="display: none;"></div>
        <?php }?>
        <div class="galeria-container">
            <?php if(count($publicacoes)>0) {?>
                <div class="galeria">
                    <?php foreach ($publicacoes as $pub) { ?>
                        <div class="item-galeria" onclick="window.location.href='Publicacao.php?id=<?php echo $pub['id_publicacao'];?>&origem=pasta&id_pasta=<?php echo $id_pasta;?>'">
                            <img src="<?php echo $pub['url'];?>" alt="Publicação" class="img-galeria">
                        </div>
                    <?php }?>
                </div>
            <?php }else{

                if($id_usuario == $pasta['fk_id_usuario_criador']){?>    
                    <div class="msg">
                        <h1>Você ainda não adicionou nenhuma publicação nessa pasta</h1>
                        <p>Este espaço está esperando por você.</p>
                    </div>
                <?php }else{?>
                    <div class="msg">
                        <h1>Ainda não há publicações nessa pasta</h1>
                        <p>Talvez o dono da pasta adicione algo em breve.</p>
                    </div>
                <?php }?>
            <?php }?>
        </div>
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
            </div></a>
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
       <dialog id="modalApagar" class="modalConfirmarAcao">
            <div class="conteudo-modal">
                <h3>Tem certeza de que deseja apagar essa pasta?</h3>
                <button id="btnNao" class="btn nao" onclick="fecharModalApagar()">Não</button>
                <button id="btnSim" class="btn sim" data-id-pasta="<?php echo $id_pasta;?>">Sim</button>
            </div>
        </dialog>

    <dialog id="modalAdicionar" class="modalSelect">
        <div class="conteudoModal">
            <div class="modalTopo">
                <button onclick="fecharModalAdicionar()" class="btn-voltar">
                   <i class="bi bi-chevron-left"></i>
                </button>
                <h3>Selecione as publicações para adicionar à pasta</h3>
            </div>
            <div id="formAdicionar">
                <input type="hidden" name="id_pasta" value="<?php echo $id_pasta;?>">

                <div class="galeriaContainer">
                    <div class="Galeria">
                        <?php
                            $sql_publis_user = "SELECT p.id_publicacao, i.url
                                            FROM tb_publicacao p
                                            JOIN tb_imagens_publicacao i ON p.id_publicacao = i.fk_id_publicacao
                                            WHERE p.fk_id_usuario_criador = :id_usuario
                                            AND p.id_publicacao NOT IN (
                                                SELECT fk_id_publicacao FROM tb_conteudo_pasta WHERE fk_id_pasta = :id_pasta
                                            )
                                            AND i.id_imagem = (
                                                SELECT MIN(i2.id_imagem)
                                                FROM tb_imagens_publicacao i2
                                                WHERE i2.fk_id_publicacao = p.id_publicacao
                                            )";

                            $stmt_publi = $conn->prepare($sql_publis_user);
                            $stmt_publi->execute([':id_usuario' => $id_usuario, ':id_pasta' => $id_pasta]);

                            while ($pub = $stmt_publi->fetch(PDO::FETCH_ASSOC)) {
                                echo "
                                <label class='itemGaleria'>
                                    <input type='checkbox' name='publicacoes[]' value='{$pub['id_publicacao']}'>
                                    <div class='img-wrapper'>
                                        <img src='{$pub['url']}' alt='Publicação'>
                                    </div>
                                </label>";
                            }
                        ?>
                </div>
            </div>

            <div class="botoes-modal">
                <button type="button" class="btn confirmar" id="Adicionar">Adicionar</button>
            </div>
        </div>
    </div>
</dialog>

<dialog id="modalRemover" class="modalSelect">
        <div class="conteudoModal">
            <div class="modalTopo">
                <button onclick="fecharModalRemover()" class="btn-voltar">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <h3>Selecione as publicações que deseja remover da pasta</h3>
            </div>
            <div id="formRemover">
                <input type="hidden" name="id_pasta" value="<?php echo $id_pasta;?>">

                <div class="galeriaContainer">
                    <div class="Galeria">
                        <?php
                        $sql_publi = "SELECT p.id_publicacao, i.url
                                    FROM tb_publicacao p
                                    JOIN tb_conteudo_pasta cp ON p.id_publicacao = cp.fk_id_publicacao
                                    JOIN tb_imagens_publicacao i ON p.id_publicacao = i.fk_id_publicacao
                                    WHERE cp.fk_id_pasta = :id_pasta
                                    AND i.id_imagem = (
                                        SELECT MIN(i2.id_imagem)
                                        FROM tb_imagens_publicacao i2
                                        WHERE i2.fk_id_publicacao = p.id_publicacao
                                    )";
                    $stmt_publi = $conn->prepare($sql_publi);
                    $stmt_publi->bindParam(':id_pasta', $id_pasta);
                    $stmt_publi->execute();
                    while ($pub = $stmt_publi->fetch(PDO::FETCH_ASSOC)) {
                        echo "
                        <label class='itemGaleria'>
                            <input type='checkbox' name='publicacoes[]' value='{$pub['id_publicacao']}'>
                            <div class='img-wrapper'>
                                <img src='{$pub['url']}' alt=''>
                            </div>
                        </label>
                        ";
                    }
                    ?>
                </div>
            </div>

            <div class="botoes-modal">
                <button type="button" class="btn confirmar" id="Remover">Remover</button>
            </div>
        </div>
    </div>
</dialog>

<div id="msg"></div>
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