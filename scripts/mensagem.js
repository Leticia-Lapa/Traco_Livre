let intervaloAtivo = false;

$(document).ready(function () {

    function buscarMensagens() {

        const id_conversa = $("#id_conversa").val();
        const ultimaMsg = $(".chat-messages .message").last();
        const ultima_id = ultimaMsg.length ? ultimaMsg.data("id") : 0;

        $.ajax({
            url: "../banco/buscarNovas.php",
            method: "GET",
            data: { id_conversa, ultima_id },
            dataType: "json",
            success: function (msgs) {

                msgs.forEach(msg => {

                    // SE JÁ EXISTE ESSA MENSAGEM NA TELA, NÃO ADICIONA
                    if ($(`.message[data-id="${msg.id_mensagem}"]`).length) {
                        return;
                    }

                    const classe = msg.fk_id_usuario_criador == $("#id_usuario").val()
                        ? "sent"
                        : "received";

                    $(".chat-messages").append(`
                        <div class="message ${classe}" data-id="${msg.id_mensagem}">
                            ${msg.ds_mensagem}
                        </div>
                    `);
                });

                if (msgs.length > 0) {
                    const box = document.querySelector(".chat-messages");
                    box.scrollTop = box.scrollHeight;
                }
            }
        });
    }

    // Só cria o interval uma vez
    if (!intervaloAtivo) {
        setInterval(buscarMensagens, 1000);
        intervaloAtivo = true;
    }

    // Enviar mensagem
    $('.btn').on('click', function (e) {
        e.preventDefault();

        const id = $("#id_conversa").val();
        const mensagem = $("#mensagem").val().trim();

        if (!mensagem) return;

        $.ajax({
            url: "../banco/mensagem.php",
            type: "POST",
            data: { id_conversa: id, mensagem },
            success: function () {
                $("#mensagem").val('');
                buscarMensagens(); // atualização imediata
            }
        });
    });

});
