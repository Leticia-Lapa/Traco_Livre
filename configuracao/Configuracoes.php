<?php 
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../auth/login.html");
    exit;
}
include '../banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="Configuracoes.css">
    <link rel="stylesheet" href="../modoEscuro.css">
    <title>Configurações</title>
</head>

<body>
    <div class="container">
        <div class="voltar">
            <a href="../Perfil.php">
                 <i class="bi bi-chevron-left" id="imagemPrincipal"></i>
            </a>
        </div>

        <div class="texto">
            <h1>Configurações</h1>
        </div>

        <div class="form">
            <a href="Email/AltEmail.html">
                <div class="opcao">
                    <p>Alterar Email</p>
                </div>
            </a>
            <a href="Senha/AltSenha.html">
                <div class="opcao">
                    <p>Alterar Senha</p>
                </div>
            </a>
            <button onclick="ModalExcluir()">
                <div class="opcao">
                    <p>Excluir Conta</p>
                </div>
            </button>
            <button onclick="ModalSair()">
                <div class="opcao">
                    <p>Sair da Conta</p>
                </div>
            </button>
        </div>
        <h4>Tema</h4>
        <div class="tema">
            <p>Escuro</p>
            <div class="toggle">
                <input type="checkbox" id="modo">
            </div>
        </div>
    </div>

    <!-- A estrutura do modal -->
    <dialog id="modalExcluir" class="modal">
        <div class="conteudo-modal">
            <h3>Tem certeza de que deseja excluir sua conta?</h3>
            <button id="btnNao" class="btn nao">Não</button>
            <button id="btnSim" class="btn sim">Sim</button>
        </div>
    </dialog>

    <dialog id="modalSair" class="modal">
        <div class="conteudo-modal">
            <h3>Tem certeza de que deseja sair da sua conta?</h3>
            <button id="btn-Nao" class="btn nao">Não</button>
            <button id="btn-Sim" class="btn sim">Sim</button>
        </div>
    </dialog>
    <script src="modo.js"></script>
    <script src="../auth/escuro.js"></script>
    <script src="../img.js"></script>

    <script>
        
        // MODAL DA OPÇÃO EXCLUIR CONTA
        const modalExcluir = document.getElementById('modalExcluir');
        const btnSim = document.getElementById('btnSim');
        const btnNao = document.getElementById('btnNao');

        function ModalExcluir() {
            modalExcluir.showModal(); // Exibe o modal
        }

        btnSim.addEventListener('click', function () {
            modalExcluir.close(); // Fecha o modal 
        });

        btnNao.addEventListener('click', function () {
            console.log('Ação negada.'); // O que acontece quando negado
            modalExcluir.close(); // Fecha o modal
        });

        // MODAL DA OPÇÃO SAIR DA CONTA

        const modalSair = document.getElementById('modalSair');
        const btnsim = document.getElementById('btn-Sim');
        const btnnao = document.getElementById('btn-Nao');

        function ModalSair() {
            modalSair.showModal(); // Exibe o modal
        }

        btnsim.addEventListener('click', function () {
            $.ajax({
                url: "../banco/sair.php",
                type: "POST",
                dataType: "html"
            }).done(function (resposta) {
                console.log(resposta);
                window.location.href = "../auth/Login.php";
            }).fail(function (jqXHR, textStatus) {
                console.log("Request failed: " + textStatus);
            }).always(function () {
                console.log("completou");
            });

    modalSair.close(); 
        });

        btnnao.addEventListener('click', function () {
            console.log('Ação negada.'); // O que acontece quando negado
            modalSair.close(); // Fecha o modal
        });

    </script>
</body>

</html>