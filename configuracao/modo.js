const $html = document.querySelector('body');
  const $checkbox = document.querySelector('#modo'); 
  

 
  if (localStorage.getItem('modo') === 'dark') {
    $html.classList.add('dark-mode');
    if ($checkbox) $checkbox.checked = true;
  }

  
  if ($checkbox) {
    $checkbox.addEventListener('change', function () {
      $html.classList.toggle('dark-mode');
      if ($html.classList.contains('dark-mode')) {
        localStorage.setItem('modo', 'dark');
        
      } else {
        localStorage.setItem('modo', 'light');
      }
    });
  }