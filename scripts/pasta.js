$(document).ready(function () {
  $('#imagem').change(function (event) {
    var file = event.target.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {
      var $previewContainer = $('<div class="preview-container"></div>');
      var $img = $('<img>').attr('src', e.target.result);
      var $removerBtn = $('<button type="button" class="remover-btn">X</button>');
      $previewContainer.append($img).append($removerBtn);

      // Remove qualquer imagem anterior e adiciona a nova
      $('#img-list').html('').append($previewContainer);
    }

    reader.readAsDataURL(file);
  });

  $('#img-list').on('click', '.remover-btn', function () {
    $(this).closest('.preview-container').remove();
  });

  $('.btn-avancar').on('click', function (e) {
    e.preventDefault();

    const file = $('#imagem')[0].files[0];

    if (file) {
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
        success: function (res) {
          const imgUrl = res.secure_url;
          CriarPasta(imgUrl);
        },
        error: function () {
          $('#msg').text("Erro ao enviar imagem.").fadeIn().delay(2000).fadeOut();
        }
      });
    } else {
      CriarPasta('');
    }
  });

  function CriarPasta(imgUrl) {

    const titulo = $("#titulo").val();
    const descricao = $("#descricao").val();
    const corFonte = $("#fonte-cor").val();
    const corCapa = $("#cor-capa").val();

    if (descricao === "") {
      $('#msg').text('Por favor, insira uma descricao.').fadeIn().delay(2000).fadeOut();
      return;
    }

    $('#msg').text('Processando...').fadeIn().delay(2000).fadeOut();

    const formData = new FormData();
    formData.append('titulo', titulo);
    formData.append('descricao', descricao);
    formData.append('corFonte', corFonte);
    formData.append('corCapa', corCapa);
    formData.append('imgUrl', imgUrl);

    $.ajax({
      url: "../banco/pasta.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (resposta) {
        sessionStorage.setItem('mensagemSucesso', resposta);
        window.location.href = "../Perfil.php";

      },
      error: function () {
        $('#msg').text('Erro ao salvar no banco de dados.').fadeIn().delay(2000).fadeOut();
      }
    });
  }
});