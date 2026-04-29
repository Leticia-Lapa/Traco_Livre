
$(document).ready(function () {
    let idUsuario = $('#seguir').data('id-usuario');
    let idOutro = $('#seguir').data('id-outro');

    $.ajax({
        url: "banco/verificarSeguimento.php",
        type: "POST",
        data: { idUsuario, idOutro },
        success: function (resposta) {
            if (resposta == 1) {
                $('#seguir').text('Deixar de Seguir');
            } else {
                $('#seguir').text('Seguir');
            }
        }
    });

    $(".iniciarConversa").click(function () {
        const nome = $(this).data('nome');
        const id = $(this).data('id');

        $.ajax({
            url: "banco/conversa.php",
            type: "POST",
            data: { nome, id },
            success: function (resposta) {
                window.location.href = `chat/Chat.php?id_conversa=${resposta}&id_outrousuario=${id}`;
            },
            error: function () {
                alert('Erro ao iniciar conversa.');
            }
        });
    });

    $('#seguir').click(function () {
        let idUsuario = $(this).data('id-usuario');
        let idOutro = $(this).data('id-outro');

        $.ajax({
            url: "banco/seguir.php",
            type: "POST",
            data: { idUsuario, idOutro },
            success: function (resposta) {

                let seguidoresElemento = $('#nseguidores');
                let seguidoresAtual = parseInt(seguidoresElemento.text());

                if (resposta == 1) {
                    // Agora PASSOU a seguir
                    $('#seguir').text('Deixar de Seguir');
                    seguidoresElemento.text(seguidoresAtual + 1);

                } else {
                    // Agora deixou de seguir
                    $('#seguir').text('Seguir');
                    seguidoresElemento.text(Math.max(0, seguidoresAtual - 1));
                }
            },
            error: function () {
                alert('Erro ao seguir.');
            }
        });
    });
});

