const ModalAdicionar = document.getElementById('modalAdicionar');

function abrirModalAdicionar() {
    ModalAdicionar.showModal();
}

function fecharModalAdicionar() {
    ModalAdicionar.close();
}

const Modalremover = document.getElementById('modalRemover');

function abrirModalRemover() {
    Modalremover.showModal();
}

function fecharModalRemover() {
    Modalremover.close();
}

const ModalExcluir = document.getElementById('modalApagar');
function abrirModalApagar() {
    ModalExcluir.showModal();
}
function fecharModalApagar() {
    ModalExcluir.close();
}


$(document).ready(function(){
    $("#Adicionar").click(function() {
        const id_pasta = $('input[name="id_pasta"]').val();
        const publicacoes = [];

        $('input[name="publicacoes[]"]:checked').each(function() {
            publicacoes.push($(this).val());
        });

        if (publicacoes.length === 0) {
             $('#msg').text('Por favor, selecione pelo menos uma publicação.').fadeIn().delay(2000).fadeOut();
            return;
        }

        const formData = {
            id_pasta: id_pasta,
            publicacoes: publicacoes
        };

        $.ajax({
            type: 'POST',
            url: 'banco/salvarPublicacoesPasta.php',
            data: formData,
            success: function(resposta) {
                sessionStorage.setItem('mensagemSucesso', resposta);
                modalAdicionar.close();
                window.location.href = `Pasta.php?id=${id_pasta}`;
            },
            error: function(xhr, status, error) {
                $('#msg').text("Erro ao adicionar publicações. Tente novamente.").fadeIn().delay(2000).fadeOut();
            }
        });
    });

    $("#Remover").click(function() {
        const id_pasta = $('input[name="id_pasta"]').val();
        const publicacoes = [];

        $('input[name="publicacoes[]"]:checked').each(function() {
            publicacoes.push($(this).val());
        });

        if (publicacoes.length === 0) {
             $('#msg').text('Por favor, selecione pelo menos uma publicação.').fadeIn().delay(2000).fadeOut();
            return;
        }

        const formData = {
            id_pasta: id_pasta,
            publicacoes: publicacoes
        };

        $.ajax({
            type: 'POST',
            url: 'banco/removerPublicacoesPasta.php',
            data: formData,
            success: function(resposta) {
                sessionStorage.setItem('mensagemSucesso', resposta);
                modalAdicionar.close();
                window.location.href = `Pasta.php?id=${id_pasta}`;
            },
            error: function(xhr, status, error) {
                $('#msg').text("Erro ao remover publicações. Tente novamente.").fadeIn().delay(2000).fadeOut();
            }
        });
    });

    $('#btnSim').click(function (e) {
        e.preventDefault();

        const id_pasta = $(this).data('id-pasta');
        const dados = {
            'id_pasta': id_pasta
        };

        $.ajax({
            url: "banco/excluirPasta.php",
            type: 'POST',
            data: dados,
            success: function (resposta) {
                sessionStorage.setItem('mensagemSucesso', resposta);
                window.location.href = "Perfil.php";
            },
            error: function (xhr) {
                $('#msg').text("Erro ao excluir pasta.").fadeIn().delay(2000).fadeOut();
            }
        });
    });

    const msg = sessionStorage.getItem('mensagemSucesso');
        if(msg){
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
                aviso.fadeIn(500).delay(2000).fadeOut(500, function(){
                aviso.remove();
            });
                sessionStorage.removeItem('mensagemSucesso');
        }
});