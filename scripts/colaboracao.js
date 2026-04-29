let postAtual = null;

$(document).ready(function() {
  $(document).on('click', '.abrirModalExcluir', function() {
    const postAtual = $(this).data('id');
    const modal = $('#modalExcluir');
    modal.data('post-id', postAtual); 

    modal[0].showModal();
  });

  $('#btnNao').on('click', function() {
    const modal = $('#modalExcluir');
    modal[0].close();
    modal.removeData('post-id');
  });

  $('#btnSim').on('click', function() {
    const modal = $('#modalExcluir');
    const postAtual = modal.data('post-id');

    $.ajax({
        url: 'banco/excluirColaboracao.php',
        type: 'POST',
        data: {
            id: postAtual
        },
        success: function(resposta) {
            sessionStorage.setItem('mensagemSucesso', resposta);
            window.location.href = "Colaboracoes.php";
        },

        error: function(xhr) {
            $('#msg').text("Erro ao excluir colaboração.").fadeIn().delay(2000).fadeOut();
        }
    });
  });

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
        tipo: "cob"
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
        tipo: "cob",
        id: postAtual,
        ds: texto
      },
      success: function() {
        $('#comentario').val('');

        $.ajax({
          url: 'banco/comentarios.php',
          type: 'POST',
          data: { id: postAtual, tipo: "cob"},
          success: function(resposta) {
            $('#lista-comentarios').html(resposta);
          }
        });
      }
    });
  });
    
    const msg = sessionStorage.getItem('mensagemSucesso');
    if(msg){
        // cria um elemento temporário para a mensagem
        const aviso = $('<div id="msg">' + msg + '</div>').css('text-align', 'center');
        aviso.css({
            'position': 'fixed',
            'bottom': '9vh',
            'left': '50%',
            'transform': 'translateX(-50%)',
            'background': 'rgba(0,0,0,0.5)',
            'color': 'white',
            'width': '30vh',
            'border-radius': '6px',
            'z-index': '9999',
            'display': 'none',
            'padding': '1vh' 
        });
        $('body').append(aviso);
            aviso.fadeIn(500).delay(2000).fadeOut(500, function(){
            aviso.remove(); // remove depois do fadeOut
        });

        sessionStorage.removeItem('mensagemSucesso'); // limpa a mensagem
    }
});