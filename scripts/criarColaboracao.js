$(document).ready(function(){
$(".btn-avancar").click(function(){

    const titulo = $("#titulo").val();
    const tipo = $("#tipo").val();
    const descricao = $("#descricao").val();
        
    if(descricao === ""){
        $('#msg').text('Por favor, insira uma descricao.').fadeIn().delay(2000).fadeOut();
        return;
    }

    if (tipo === "valor1") {
        $('#msg').text('Por favor, selecione um tipo de relato.').fadeIn().delay(2000).fadeOut();
        return;
    }$('#msg').text('Processando...').fadeIn().delay(2000).fadeOut();

            const formData = new FormData();
            formData.append('titulo', titulo);
            formData.append('tipo', tipo);
            formData.append('descricao', descricao);
            tags.forEach(tag => formData.append('tags[]', tag));
        
            $.ajax({
                url: "../banco/colaboracao.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resposta){
                    sessionStorage.setItem('mensagemSucesso', resposta);
                    window.location.href = "../Colaboracoes.php";

                },
                error: function(){
                    $('#msg').text('Erro ao salvar no banco de dados.').fadeIn().delay(2000).fadeOut();
                }
            });
    });
});

