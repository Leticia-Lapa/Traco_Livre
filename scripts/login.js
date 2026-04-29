
// FUNÇÃO DO OLHO DE VER SENHA
function versenha() {
    var inputPass= document.getElementById('senha')
    var ver = document.getElementById('mostrar')

    if(inputPass.type === 'password') {
        inputPass.setAttribute('type', 'text')
        ver.classList.replace('bi-eye-slash', 'bi-eye')
    }
    else {
        inputPass.setAttribute('type', 'password')
        ver.classList.replace('bi-eye', 'bi-eye-slash')
    }
}

$(document).ready(function(){
    $(".btn-entrar").click(function(){

        var login = $(".login").val();
        var senha = $(".senha").val();

        $.ajax({
            url: "../banco/login.php",
            type: "POST",
            data: {
        	campo1: login,
        	campo2: senha
    	},
            dataType: "html"
        }).done(function(resposta){
            if(resposta > 0){
               window.location.href="../Inicio.php";
            }else{
                $('#msg').text("Atenção! Login ou Senha são inválidos").fadeIn().delay(2000).fadeOut();
            }
        }).fail(function(jqXHR, textStatus){
            console.log("Request failed: "+ textStatus);
        }).always(function(){
            console.log("completou");
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

