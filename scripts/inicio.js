let postAtual = null;
 
$(document).ready(function() {
  $(document).on('click', '.abrirModalComentarios', function() {
    const postAtual = $(this).data('id');
    const modal = $('#modalComentario');
    modal.data('post-id', postAtual); 

    modal[0].showModal();

    $.ajax({
      url: 'banco/comentarios.php',
      type: 'POST',
      data: { 
        id: postAtual,
        tipo: "pub"
      },
      success: function(resposta) {
        $('#lista-comentarios').html(resposta);
      },
      error: function() {
        $('#lista-comentarios').html('<p>Erro ao carregar comentários.</p>');
      }
    });
  });

  $('#btnVolt').on('click', function() {
    const modal = $('#modalComentario');
    modal[0].close();
    $('#lista-comentarios').empty();
    modal.removeData('post-id');
  });

  $('.btn-enviar').on('click', function() {
    const modal = $('#modalComentario');
    const postAtual = modal.data('post-id');

    const texto = $('#comentario').val().trim();
    if (texto === '' || !postAtual) {
      if (!postAtual) console.error('ID da postagem não encontrado!');
      return;
    }
 
    $.ajax({
      url: 'banco/addComentario.php',
      type: 'POST',
      data: {
        tipo: "pub",
        id: postAtual,
        ds: texto
      },
      success: function() {
        $('#comentario').val('');

        $.ajax({
          url: 'banco/comentarios.php',
          type: 'POST',
          data: { id: postAtual, tipo: "pub"},
          success: function(resposta) {
            $('#lista-comentarios').html(resposta);
          }
        });
      }
    });
  });
});

 