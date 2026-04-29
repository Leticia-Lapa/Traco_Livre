 const $html = document.documentElement;

  if (localStorage.getItem('modo') === 'dark') {
    $html.classList.add('dark-mode');
    troca(); 
  }

  function troca() {
     
  const relato = document.getElementById("relato");
  if (relato) {
    const src = relato.getAttribute("src");
    if (src === "icons/navbar/relatoE.png") {
      relato.setAttribute("src", "icons/navbar/Relatos.png");
    } else {
      relato.setAttribute("src", "icons/navbar/relatoE.png");
    }
  }

  
  const estilo = document.getElementById("estilo");
  if (estilo) {
    const src = estilo.getAttribute("src");
    if (src === "icons/navbar/estiloE.png") {
      estilo.setAttribute("src", "icons/navbar/PesqAvan.png");
    } else {
      estilo.setAttribute("src", "icons/navbar/estiloE.png");
    }
  }

  
  const inicio = document.getElementById("inicio");
  if (inicio) {
    const src = inicio.getAttribute("src");
    if (src === "icons/navbar/inicioE.png") {
      inicio.setAttribute("src", "icons/navbar/Principal.png");
    } else {
      inicio.setAttribute("src", "icons/navbar/inicioE.png");
    }
  }


  const chat = document.getElementById("chat");
  if (chat) {
    const src = chat.getAttribute("src");
    if (src === "icons/navbar/E.png") {
      chat.setAttribute("src", "icons/navbar/Chat.png");
    } else {
      chat.setAttribute("src", "icons/navbar/E.png");
    }
  }

  
  const colaboracao = document.getElementById("colaboracao");
  if (colaboracao) {
    const src = colaboracao.getAttribute("src");
    if (src === "icons/navbar/colaboracaoE.png") {
      colaboracao.setAttribute("src", "icons/navbar/Colaboracao.png");
    } else {
      colaboracao.setAttribute("src", "icons/navbar/colaboracaoE.png");
    }
  }
}
  