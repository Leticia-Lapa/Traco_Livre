function pesquisa() {

  localStorage.setItem("pesquisa_origem", window.location.href);
  
  document.getElementById("conteudo").style.display = "none"; // some com tudo
  document.getElementById("tela-pesquisa").style.display = "flex"; // mostra a pesquisa

  // já foca o input automaticamente
  const input = document.querySelector("#tela-pesquisa input");
  input.focus();
}

function fecha() { 
  document.getElementById("tela-pesquisa").style.display = "none"; 
  document.getElementById("conteudo").style.display = "block"; 
}

function curtir(botao) {
    const id = botao.dataset.id;
    const tipo = botao.dataset.origem;
    const icone = botao.querySelector("i");
    const contador = document.querySelector('.likeCount[data-id="' + id + '"]');

    $.ajax({
        url: "banco/curtir.php",
        type: "POST",
        data: { id, tipo },
        dataType: "json",
        success: function(res) {

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
        error: function() {
            alert("Erro ao curtir :(");
        }
    });
}