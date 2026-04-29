document.addEventListener('DOMContentLoaded', function () {
    const botao = document.querySelector('.btn-avancar');
    if (!botao) return;

    const setDisabled = (disabled) => {
        botao.disabled = disabled;
        botao.style.opacity = disabled ? '0.6' : '';
        botao.style.cursor = disabled ? 'not-allowed' : '';
    };


    const selectors = {
        titulo: document.getElementById('titulo'),
        descricao: document.getElementById('descricao'),
        tipo: document.getElementById('tipo'),
        estilo: document.getElementById('estilo'),
        imagem: document.getElementById('imagem'),
        tagList: document.getElementById('tag-list')
    };

    function validaCampos() {
        let ok = true;
        const pathname = window.location.pathname;
        const isPaginaPublicacao = pathname.includes('CriarPublicacao.php');

        // Título (obrigatório exceto em Publicação)
        if (selectors.titulo && !isPaginaPublicacao) {
            if (selectors.titulo.value.trim() === '') ok = false;
        }

        // Descrição (obrigatória exceto em Publicação)
        if (selectors.descricao && !isPaginaPublicacao) {
            if (selectors.descricao.value.trim() === '') ok = false;
        }

        // Select tipo/estilo (sempre obrigatório)
        const sel = selectors.tipo || selectors.estilo;
        if (sel) {
            const val = sel.value;
            if (!val || val === 'valor1') ok = false;
        }

        // Imagem obrigatória apenas em CriarPublicacao.php
if (selectors.imagem) {
    const isPaginaPublicacao = window.location.pathname.includes('CriarPublicacao.php');

    if (isPaginaPublicacao) {
        if (!(selectors.imagem.files && selectors.imagem.files.length > 0)) {
            ok = false;
        }
    }
}

        // Tags nunca são obrigatórias
        // if (selectors.tagList) {
        //     if (selectors.tagList.childElementCount === 0) ok = false;
        // }

        setDisabled(!ok);
        return ok;
    }

    const watchEls = ['titulo', 'descricao', 'tipo', 'estilo', 'imagem'];
    watchEls.forEach(id => {
        const el = selectors[id];
        if (!el) return;
        el.addEventListener('input', validaCampos);
        el.addEventListener('change', validaCampos);
    });

    if (selectors.tagList) {
        const mo = new MutationObserver(function () {
            validaCampos();
        });
        mo.observe(selectors.tagList, { childList: true });
    }

    validaCampos();
});
