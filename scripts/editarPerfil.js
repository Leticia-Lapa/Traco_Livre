$(document).ready(function() {
  // Preview da imagem
  $('#foto').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(ev) {
        $('#fotoPreview').attr('src', ev.target.result);
      };
      reader.readAsDataURL(file);
    }
  });
 
  // Clique no botão "Salvar"
  $('.btn-avancar').on('click', function(e) {
    e.preventDefault();
 
    const file = $('#foto')[0].files[0];
 
    if (file) {
      // Upload Cloudinary
      const cloudName = "ddmcxyujg";
      const uploadPreset = "tracoLivre";
 
      const uploadData = new FormData();
      uploadData.append('file', file);
      uploadData.append('upload_preset', uploadPreset);
 
      $.ajax({
        url: `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`,
        type: 'POST',
        data: uploadData,
        processData: false,
        contentType: false,
        success: function(res) {
          const fotoUrl = res.secure_url;
          salvarPerfil(fotoUrl);
        },
        error: function() {
          $('#msg').text("Erro ao enviar imagem.").fadeIn().delay(2000).fadeOut();
        }
      });
    } else {
      salvarPerfil('');
    }
  });
 
  function salvarPerfil(fotoUrl) {
    const dadosp = {
      nome: $('#nome-completo').val(),
      usuario: $('#nome-usuario').val(),
      fone: $('#telefone').val(),
      bio: $('#bio').val(),
      foto_url: fotoUrl
    };
 
    $.ajax({
      url: '../banco/editarPerfil.php',
      type: 'POST',
      data: dadosp,
      success: function(resposta) {
        sessionStorage.setItem('mensagemSucesso', resposta);
        window.location.href = "../Perfil.php";
      },
      error: function() {
        $('#msg').text("Erro ao atualizar perfil.").fadeIn().delay(2000).fadeOut();
      }
    });
  }
});
 