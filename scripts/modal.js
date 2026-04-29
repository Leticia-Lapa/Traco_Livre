// MODAL DE DENUNCIAR
const ModalDenuncia = document.getElementById('modalDenuncia');
const btnOpcao = document.getElementById('opcao01');
const btncancelar = document.getElementById('btnCancelar');

function modalDenuncia() {
    ModalDenuncia.showModal(); // Exibe o modal
}

btnOpcao.addEventListener('click', function() {
    console.log(window.location.href='Criar/EnviarDenuncia.html'); // Ação a ser executada
    ModalDenuncia.close(); // Fecha o modal
});

btncancelar.addEventListener('click', function() {
    console.log('Ação negada.'); // O que acontece quando negado
    ModalDenuncia.close(); // Fecha o modal
});

// MODAL DE EXCLUIR
const ModalExcluir = document.getElementById('modalExcluir');
const btnSim = document.getElementById('btnSim');
const btnNao = document.getElementById('btnNao');

function modalExcluir() {
    ModalExcluir.showModal(); // Exibe o modal
}

btnSim.addEventListener('click', function() {
    //console.log(window.location.href='Colaboracoes.html'); Ação a ser executada
    ModalExcluir.close();// Fecha o modal
});

btnNao.addEventListener('click', function() {
    console.log('Ação negada.'); // O que acontece quando negado
    ModalExcluir.close(); // Fecha o modal
});

// MODAL DE COMENTARIOS
const ModalComentarios = document.getElementById('modalComentario');
const btnvoltar = document.getElementById('btnVolt');

function abrirModalComentarios() {
    ModalComentarios.showModal();
}

function fecharModalComentarios(){
    ModalComentarios.close();
}


// MODAL DE OPÇÃO EXCLUIR COMENTARIO
const ModalDeComentarios = document.getElementById('modalDeComentario');
const btnCan = document.getElementById('bVolt');

function denunciarComentario() {
    ModalDeComentarios.showModal(); // Exibe o modal
}

btnCan.addEventListener('click', function() {
    ModalDeComentarios.close(); // Fecha o modal
});

// MODAL DE OPÇÃO DE DENUNCIAR COMENTARIO
const ModalApComentarios = document.getElementById('modalApComentario');
const btnv = document.getElementById('btVolt');

function apagarComentario() {
    ModalApComentarios.showModal(); // Exibe o modal
}

btnv.addEventListener('click', function() {
    ModalApComentarios.close(); // Fecha o modal
});

// modal de confirmar exclusão comentario
const modalapa = document.getElementById('modalApComentario');
const ModalExc = document.getElementById('modalEx');
const btSim = document.getElementById('btnSim');
const btNao = document.getElementById('btnNao');

function apCom(){
    ModalExc.showModal(); // Exibe o modal
}

btSim.addEventListener('click', function() {
    // console.log(window.location.href='Colaboracoes.html'); // Ação a ser executada
    ModalExc.close(); // Fecha o modal
    modalapa.close();
});

btNao.addEventListener('click', function() {
    console.log('Ação negada.'); // O que acontece quando negado
    ModalExc.close(); // Fecha o modal
    modalapa.close();
});

// DENUNCIAR COMENTARIO
const ModalOp =  document.getElementById('modalDeComentario');
const ModalDenu = document.getElementById('DenunComent');
const btnImpropri = document.getElementById('odio');
const btnncelar = document.getElementById('bnCancelar');

function denuComen() {
    ModalDenu.showModal(); // Exibe o modal
}

btnImpropri.addEventListener('click', function() {
    console.log(window.location.href='Criar/EnviarDenuncia.html'); // Ação a ser executada
    ModalDenu.close(); // Fecha o modal
});

btnncelar.addEventListener('click', function() {
    console.log('Ação negada.'); // O que acontece quando negado
    ModalDenu.close();
    ModalOp.close();// Fecha o modal
});
