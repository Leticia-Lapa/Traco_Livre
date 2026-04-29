  if (localStorage.getItem('modo') === 'dark') {
    $html.classList.add('dark-mode');
    if ($checkbox) $checkbox.checked = true;
    modoo();
  }

  
  

  function modoo(){
const imagemm = document.getElementById("bemvindo");
const srcAtual = imagemm.getAttribute("src");
    
   if (srcAtual === "../imgs/bemvE.png") {
    imagemm.setAttribute("src", "../imgs/bem.png");
  } else {
    imagemm.setAttribute("src", "../imgs/bemvE.png");
  }
  }