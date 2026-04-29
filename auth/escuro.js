  if (localStorage.getItem('modo') === 'dark') {
    $html.classList.add('dark-mode');
    if ($checkbox) $checkbox.checked = true;
    modo();
   
  }

  function modo(){
     const imagem = document.getElementById("logo");
    const srcAtual = imagem.getAttribute("src");

   if (srcAtual === "imgs/logoEscuro.png") {
    imagem.setAttribute("src", "imgs/logo.png");
  } else {
    imagem.setAttribute("src", "imgs/logoEscuro.png");
  }

  }

  