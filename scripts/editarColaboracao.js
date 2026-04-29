
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
    $(".btn-avancar").click(function (e) {
        e.preventDefault();
        
        $('#msg').text('Processando...').fadeIn();

            const id_colaboracao = $("#id_colaboracao").val();
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
            formData.append('id_colaboracao', id_colaboracao);
            formData.append('titulo', titulo);
            formData.append('tipo', tipo);
            formData.append('descricao', descricao);

            tags.forEach(tag => formData.append('tags[]', tag));

            $.ajax({
                url: "../banco/editarColaboracao.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resposta) {
                    sessionStorage.setItem('mensagemSucesso', resposta);
                     window.location.href = '../Colaboracoes.php';
                },
                error: function() {
                    $('#msg').text("Erro ao atualizar colaboração.").fadeIn().delay(2000).fadeOut();
                }
            });
    });
});
