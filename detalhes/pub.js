$(document).ready(function () {
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
        aviso.fadeIn(500).delay(2000).fadeOut(500, function () {
            aviso.remove(); // remove depois do fadeOut
        });

        sessionStorage.removeItem('mensagemSucesso'); // limpa a mensagem
    }

    $(document).on('click', '.abrirModalComentarios', function () {
        const postAtual = $(this).data('id');
        const modal = $('#modalComentario');
        modal.data('post-id', postAtual);
        const tipo = $(this).data('tipo');

        modal[0].showModal();

        $.ajax({
            url: '../banco/comentarios.php',
            type: 'POST',
            data: {
                id: postAtual,
                tipo: tipo
            },
            success: function (resposta) {
                $('#lista-comentarios').html(resposta);
            },
            error: function () {
                $('#lista-comentarios').html('<p>Erro ao carregar comentários.</p>');
            }
        });
    });

    $('#btnVolt').on('click', function () {
        const modal = $('#modalComentario');
        modal[0].close();
        $('#lista-comentarios').empty();
        modal.removeData('post-id');
    });

    $('.btn-enviar').on('click', function () {
        const modal = $('#modalComentario');
        const postAtual = modal.data('post-id');
        const tipo = $(this).data('tipo');

        const texto = $('#comentario').val().trim();
        if (texto === '' || !postAtual) {
            if (!postAtual) console.error('ID da postagem não encontrado!');
            return;
        }

        $.ajax({
            url: '../banco/addComentario.php',
            type: 'POST',
            data: {
                tipo: tipo,
                id: postAtual,
                ds: texto
            },
            success: function () {
                $('#comentario').val('');

                $.ajax({
                    url: '../banco/comentarios.php',
                    type: 'POST',
                    data: { id: postAtual, tipo: tipo },
                    success: function (resposta) {
                        $('#lista-comentarios').html(resposta);
                    }
                });
            }
        });
    });
});


function curtir(botao) {
    const id = botao.dataset.id;
    const tipo = botao.dataset.origem;
    const icone = botao.querySelector("i");
    const contador = document.querySelector('.likeCount[data-id="' + id + '"]');

    $.ajax({
        url: "../banco/curtir.php",
        type: "POST",
        data: { id, tipo },
        dataType: "json",
        success: function (res) {

            if (res.acao == 1) {
                // Curtiu
                icone.classList.replace("bi-hand-thumbs-up", "bi-hand-thumbs-up-fill");
            } else {
                // Descurtiu
                icone.classList.replace("bi-hand-thumbs-up-fill", "bi-hand-thumbs-up");
            }

            // Atualiza número exibido
            contador.textContent = res.total;

        },
        error: function () {
             $('#msg').text("Atenção! Erro ao curtir").fadeIn().delay(2000).fadeOut();
        }
    });
}