function Pesquisa() {
    var local = window.location.href;
    var localPes = window.location.href;
    local = local.substring(0, local.indexOf('TracoLivre')) + 'TracoLivre/pesquisa/';
    localPes = local.substring(0, local.indexOf('TracoLivre')) + 'TracoLivre/';
    var Inserido = document.getElementById("barraPesquisa").value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
    };
    xmlhttp.open("GET", local + "respostaPesq.php?Inserido=" + Inserido, true);
    xmlhttp.send();
    window.location.href = localPes + 'Pesquisa.php';
}

function verificarResultados() {

    const secUsuarios = document.querySelector('.Usuarios');
    const secPublicacoes = document.querySelector('.Publicacoes');
    const secCols = document.querySelector('.Colaboracao');
    const secRelatos = document.querySelector('.Relatos');

    // Verifica se cada section está visível
    const usuariosVisivel = window.getComputedStyle(secUsuarios).display !== "none";
    const publicacoesVisivel = window.getComputedStyle(secPublicacoes).display !== "none";
    const colsVisivel = window.getComputedStyle(secCols).display !== "none";
    const relatosVisivel = window.getComputedStyle(secRelatos).display !== "none";

    // Se todas estiverem ocultas → exibe mensagem
    if (!usuariosVisivel && !publicacoesVisivel && !colsVisivel && !relatosVisivel) {
        document.getElementById('msgNada').style.display = "block";
    } else {
        document.getElementById('msgNada').style.display = "none";
    }
}

function voltarResultados() {
    const origem = localStorage.getItem("pesquisa_origem");

    if (origem) {
        window.location.href = origem;
    } else {
        history.back(); // fallback
    }
}

