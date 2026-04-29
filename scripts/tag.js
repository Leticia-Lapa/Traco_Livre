const tagList = document.getElementById('tag-list');
const campoTag = document.getElementById('campo-tag');
const inputTag = document.getElementById('novaTag');
let tags = [];

    function mostrarCampoTag() {
        campoTag.style.display = 'block';
        inputTag.focus();
    }

    function fecharCampoTag() {
        campoTag.style.display = 'none';
        inputTag.value = '';
    }

    function adicionarTag() {
        const tag = inputTag.value.trim();

        if (tag === '' || tags.includes(tag)) return;
            if (tags.length >= 20) {
                $('#msg').text("Limite de 20 tags atingido.").fadeIn().delay(2000).fadeOut();
                return;
            }

        tags.push(tag);
        atualizarTags();
        inputTag.value = '';
    }

    function removerTag(index) {
        tags.splice(index, 1);
        atualizarTags();
    }

    function atualizarTags() {
        tagList.innerHTML = '';
        tags.forEach((tag, i) => {
        const span = document.createElement('span');
        span.classList.add('tag');
        span.innerHTML = `#${tag} <button type="button" onclick="removerTag(${i})">×</button>`;
        tagList.appendChild(span);
        });
    }