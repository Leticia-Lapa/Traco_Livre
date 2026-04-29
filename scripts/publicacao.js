$(document).ready(function(){
    $('#imagem').change(function(event) {
        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();
            reader.onload = function(e) {
                var $previewContainer = $('<div class="preview-container"></div>');
                var $img = $('<img>').attr('src', e.target.result);
                var $removerBtn = $('<button type="button" class="remover-btn">X</button>');
                $previewContainer.append($img).append($removerBtn);
                $('#img-list').append($previewContainer);
            }
            reader.readAsDataURL(file);
        }
    });

    $('#img-list').on('click', '.remover-btn', function() {
        $(this).closest('.preview-container').remove();
    });

    $(".btn-avancar").click(function(){
        const imagens = $("#imagem")[0].files;
        const estilo = $("#estilo").val();
        const descricao = $("#descricao").val();

        if(imagens.length === 0){
            $('#msg').text('Por favor, selecione pelo menos uma imagem.').fadeIn().delay(2000).fadeOut();
            return;
        }

        if (estilo === "valor1") {
            $('#msg').text('Por favor, selecione um estilo.').fadeIn().delay(2000).fadeOut();
            return;
        }$('#msg').text('Processando...').fadeIn().delay(2000).fadeOut();

        const cloudName = 'ddmcxyujg';
        const uploadPreset = 'tracoLivre';
        const urls = [];

        let uploadCount = 0;
        for(let i=0; i<imagens.length; i++){
            const formData = new FormData();
            formData.append('file', imagens[i]);
            formData.append('upload_preset', uploadPreset);

            $.ajax({
                url: `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                urls.push(response.secure_url);
                uploadCount++;

                    if(uploadCount === imagens.length){
                        // Todas as imagens enviadas, agora salva no banco
                        const formData = new FormData();
                        formData.append('estilo', estilo);
                        formData.append('descricao', descricao);
                        urls.forEach(url => formData.append('urls[]', url));
                        tags.forEach(tag => formData.append('tags[]', tag));

                        $.ajax({
                            url: "../banco/publicacao.php",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(resposta){
                                sessionStorage.setItem('mensagemSucesso', resposta);
                                window.location.href = "../Perfil.php";
                            },
                            error: function(){
                                $('#msg').text('Erro ao salvar no banco de dados.').fadeIn().delay(2000).fadeOut();
                            }
                        });
                    }
                },
                error: function(){
                    $('#msg').text('Erro ao enviar imagens.').fadeIn().delay(2000).fadeOut();
                }
            });
        }
    });
});

