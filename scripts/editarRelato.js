
const tagList = document.getElementById('tag-list');
const campoTag = document.getElementById('campo-tag');
const inputTag = document.getElementById('novaTag');


let tags = Array.from(tagList.querySelectorAll('.tag')).map(span =>
    span.textContent.replace('×', '').trim().replace(/^#/, '')
);

function atualizarTags() {
    tagList.innerHTML = ''; 

    tags.forEach(tag => {
        const span = document.createElement('span');
        span.classList.add('tag');

        const texto = document.createTextNode(`#${tag} `);
        span.appendChild(texto);


        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = '×';
        btn.classList.add('remover-tag');
        btn.addEventListener('click', () => {
            tags = tags.filter(t => t !== tag);
            atualizarTags();
        });

        span.appendChild(btn);
        tagList.appendChild(span);
    });
}

function mostrarCampoTag() {
    campoTag.style.display = 'flex';
    inputTag.focus();
}

function fecharCampoTag() {
    campoTag.style.display = 'none';
    inputTag.value = '';
}

function adicionarTag() {
    const nova = inputTag.value.trim();

    if (nova === '') return;
    if (tags.includes(nova)) {
        alert('Essa tag já foi adicionada!');
        inputTag.value = '';
        return;
    }
    if (tags.length >= 20) {
        alert('Você atingiu o limite máximo de 20 tags.');
        return;
    }

    tags.push(nova);
    inputTag.value = '';
    atualizarTags();
    inputTag.value = '';
}

document.querySelectorAll('.remover-tag').forEach((btn, i) => {
    btn.addEventListener('click', () => {
        tags.splice(i, 1);
        atualizarTags();
    });
});


inputTag.addEventListener('keydown', e => {
    if (e.key === 'Escape') fecharCampoTag();
});

document.addEventListener('DOMContentLoaded', atualizarTags);

$(document).ready(function () {

    $('#imagem').change(function (event) {
        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var $previewContainer = $('<div class="preview-container"></div>');
                var $img = $('<img>').attr('src', e.target.result);
                var $removerBtn = $('<button type="button" class="remover-btn">X</button>');
                $previewContainer.append($img).append($removerBtn);
                $('#img-list').append($previewContainer);
            };
            reader.readAsDataURL(files[i]);
        }
    });

    $('#img-list').on('click', '.remover-btn', function () {
        $(this).closest('.preview-container').remove();
    });

    $(".btn-avancar").click(function (e) {
        e.preventDefault();
         const imagens_novas = $("#imagem")[0].files;

        $('#msg').text('Processando...').fadeIn();

        let urls_existentes = [];
        $("#img-list .preview-container img").each(function () {
            urls_existentes.push($(this).attr("src"));
        });

        if (imagens_novas.length > 0) {
            const cloudName = "ddmcxyujg";
            const uploadPreset = "tracoLivre";
            const urls_novas = [];
            let uploadCount = 0;

            for (let i = 0; i < imagens_novas.length; i++) {
                const formData = new FormData();
                formData.append('file', imagens_novas[i]);
                formData.append('upload_preset', uploadPreset);

                $.ajax({
                    url: `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        urls_novas.push(response.secure_url);
                        uploadCount++;

                        if (uploadCount === imagens_novas.length) {
                            const todas_urls = urls_existentes.concat(urls_novas);
                            atualizarRelato(todas_urls);
                        }
                    },
                    error: function () {
                        $('#msg').text("Erro ao enviar imagem(ns).").fadeIn().delay(2000).fadeOut();
                    }
                });
            }
        } else {
            atualizarRelato(urls_existentes);
        }

        function atualizarRelato(urls) {
            const id_relato = $("#id_relato").val();
            const titulo = $("#titulo").val();
            const tipo = $("#tipo").val();
            const descricao = $("#descricao").val();

            let tags = [];
            $("#tag-list .tag").each(function () {
                let texto = $(this).text().trim();
                texto = texto.replace('×', '').replace('#', '').trim();
                if (texto !== "") tags.push(texto);
            });

            const formData = new FormData();
            formData.append('id_relato', id_relato);
            formData.append('titulo', titulo);
            formData.append('tipo', tipo);
            formData.append('descricao', descricao);

            urls.forEach(url => formData.append('urls[]', url));
            tags.forEach(tag => formData.append('tags[]', tag));

            $.ajax({
                url: "../banco/editarRelato.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resposta) {
                    sessionStorage.setItem('mensagemSucesso', resposta);
                     window.location.href = '../Relatos.php';
                },
                error: function() {
                    $('#msg').text("Erro ao atualizar relato.").fadeIn().delay(2000).fadeOut();
                }
            });
        }
    });
});
