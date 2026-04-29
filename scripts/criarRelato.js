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

        if (imagens.length > 0) {
        const cloudName = "ddmcxyujg";
        const uploadPreset = "tracoLivre";
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
                        cadastrarRelato(urls);
                    }
                },
                error: function() {
                    $('#msg').text("Erro ao enviar imagem(ns).").fadeIn().delay(2000).fadeOut();
                }
            });
        }
    } else {
        cadastrarRelato([]);
    }

        function cadastrarRelato(urls){
            const titulo = $("#titulo").val();
            const tipo = $("#tipo").val();
            const descricao = $("#descricao").val();
        
            if(titulo === ""){
                $('#msg').text('Por favor, insira um título.').fadeIn().delay(2000).fadeOut();
                return;
            }
            if (tipo === "valor1") {
                $('#msg').text('Por favor, selecione um tipo de relato.').fadeIn().delay(2000).fadeOut();
                return;
            }$('#msg').text('Processando...').fadeIn().delay(2000).fadeOut();

            const formData = new FormData();
            formData.append('titulo', titulo);
            formData.append('tipo', tipo);
            formData.append('descricao', descricao);
            urls.forEach(url => formData.append('urls[]', url));
            tags.forEach(tag => formData.append('tags[]', tag));
        
            $.ajax({
                url: "../banco/relato.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resposta){
                    sessionStorage.setItem('mensagemSucesso', resposta);
                    window.location.href = "../Relatos.php";

                },
                error: function(){
                    $('#msg').text('Erro ao salvar no banco de dados.').fadeIn().delay(2000).fadeOut();
                }
            });
        }
        
    });
});

