$(document).ready(function() {
    const msg = sessionStorage.getItem('mensagemSucesso');
        if(msg){
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
                aviso.fadeIn(500).delay(2000).fadeOut(500, function(){
                aviso.remove(); // remove depois do fadeOut
            });

            sessionStorage.removeItem('mensagemSucesso'); // limpa a mensagem
        }

    $(document).on('click', '#btnSim', function(e) {
        e.preventDefault();

        const id_publicacao = $(this).data('id-publicacao');
        const dados = {
            'id_publicacao': id_publicacao
        };

        $.ajax({
            url: "banco/excluirPublicacao.php",
            type: 'POST',
            data: dados,
            success: function(resposta) {
                sessionStorage.setItem('mensagemSucesso', resposta);
                window.location.href = "Perfil.php";
            },
            error: function(xhr) {
                $('#msg').text("Erro ao excluir publicação.").fadeIn().delay(2000).fadeOut();
            }
        });
    });
});