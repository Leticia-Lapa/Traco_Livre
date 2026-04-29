$(document).ready(function () {
    $(".btn-avancar").click(function () {

        var nomecompleto = $("#nome-completo").val();
        var nomeusuario = $("#nome-usuario").val();
        var datanascimento = $("#data-nascimento").val();
        var telefone = $("#telefone").val();
        var email = $("#email").val();
        var senha = $("#senha").val();

        $.ajax({
            url: "../banco/cadastro.php",
            type: "POST",
            data: "campo1=" + nomecompleto + "&campo2=" + nomeusuario + "&campo3=" + datanascimento + "&campo4=" + telefone + "&campo5=" + email + "&campo6=" + senha,
            dataType: "html"

        }).done(function (resposta) {
            sessionStorage.setItem('mensagemSucesso', resposta);
            window.location.href = "../auth/Login.php";

        }).fail(function (jqXHR, textStatus) {
            console.log("Request failed: " + textStatus);

        }).always(function () {
            console.log("completou");
        });
    });


});

function versenha() {
    var inputPass = document.getElementById('senha')
    var ver = document.getElementById('mostrar')

    if (inputPass.type === 'password') {
        inputPass.setAttribute('type', 'text')
        ver.classList.replace('bi-eye-slash', 'bi-eye')
    }
    else {
        inputPass.setAttribute('type', 'password')
        ver.classList.replace('bi-eye', 'bi-eye-slash')
    }
}

function validarFormulario() {
    const camposObrigatorios = {
        'nome-completo': document.getElementById('nome-completo').value.trim(),
        'nome-usuario': document.getElementById('nome-usuario').value.trim(),
        'data-nascimento': document.getElementById('data-nascimento').value.trim(),
        'email': document.getElementById('email').value.trim()
    };

    // Verifica se todos os campos obrigatórios estão preenchidos
    const camposPreenchidos = Object.values(camposObrigatorios).every(valor => valor !== '');

    return camposPreenchidos;
}

function validarSenha() {
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('csenha').value;
    const erroConfirmar = document.getElementById('erro-confirmar');
    const inputSenha = document.getElementById('csenha');

    // Validar requisitos da senha
    const requisitos = {
        minLength: senha.length >= 8,
        uppercase: /[A-Z]/.test(senha),
        number: /[0-9]/.test(senha),
        special: /[@$!%*?&]/.test(senha)
    };

    // Atualizar a lista de requisitos
    document.querySelectorAll('.requisitos-senha li').forEach(item => {
        const requisito = item.getAttribute('data-requisito');
        const icon = item.querySelector('i');
        // limpar classes de ícone
        if (icon) {
            icon.classList.remove('bi-x-circle', 'bi-check-circle');
            if (requisitos[requisito]) {
                item.classList.add('valido');
                icon.classList.add('bi-check-circle');
            } else {
                item.classList.remove('valido');
                icon.classList.add('bi-x-circle');
            }
        }
    });

    // Verificar se todos os requisitos foram atendidos
    const todosValidados = Object.values(requisitos).every(req => req === true);

    // Verificar se as senhas coincidem (apenas se confirmarSenha não estiver vazio)
    const senhasIguais = confirmarSenha === '' ? false : senha === confirmarSenha;

    // Mostrar/esconder mensagem de erro de confirmação
    if (confirmarSenha !== '') {

        if (senhasIguais) {
            // Senhas conferem
            erroConfirmar.style.display = 'none';
            inputSenha.classList.remove("erro");
            inputSenha.classList.add("normal");
        } else {
            // Senhas diferentes
            erroConfirmar.style.display = 'block';
            inputSenha.classList.remove("normal");
            inputSenha.classList.add("erro");
        }
    } else {
        erroConfirmar.style.display = 'none';
        inputSenha.classList.remove("erro");
        inputSenha.classList.add("normal");
    }

    // Verificar todos os campos obrigatórios
    const camposValidos = validarFormulario();

    // Habilitar/Desabilitar botão Avançar (apenas se todas as validações passarem)
    const botaoAvancar = document.querySelector('.btn-avancar');
    if (botaoAvancar) {
        botaoAvancar.disabled = !(todosValidados && senhasIguais && camposValidos);
        if (botaoAvancar.disabled) {
            botaoAvancar.style.opacity = '0.6';
            botaoAvancar.style.cursor = 'not-allowed';
        } else {
            botaoAvancar.style.opacity = '';
            botaoAvancar.style.cursor = '';
        }
    }

    return todosValidados && senhasIguais && camposValidos;
}

// Adicionar listeners para todos os campos obrigatórios
document.addEventListener('DOMContentLoaded', function () {
    const camposObrigatorios = [
        'nome-completo',
        'nome-usuario',
        'data-nascimento',
        'email'
    ];

    camposObrigatorios.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento) {
            elemento.addEventListener('input', validarSenha);
        }
    });

    // Validação inicial
    validarSenha();
});

// Se o DOM já estiver carregado, fazer a validação inicial
if (document.readyState !== 'loading') {
    validarSenha();
}
function vercsenha() {
    var inputPass = document.getElementById('csenha')
    var ver = document.getElementById('cmostrar')

    if (inputPass.type === 'password') {
        inputPass.setAttribute('type', 'text')
        ver.classList.replace('bi-eye-slash', 'bi-eye')
    }
    else {
        inputPass.setAttribute('type', 'password')
        ver.classList.replace('bi-eye', 'bi-eye-slash')
    }
}

$(document).ready(function () {
    $('#nome-usuario').keyup(function () {
        let nm = $(this).val();

        if (nm.trim() === "") {
            $('#msgUser').html("");
            $('#nome-usuario').css("border-color", "");
            return;
        }

        $.ajax({
            url: "../banco/verificarUsuario.php",
            type: "POST",
            data: { usuario: nm },
            success: function (resposta) {

                if (resposta === "EXISTE") {
                    $('#msgUser').html("<span style='color: #dc3545; font-size: 12px;'>Nome de usuário já existe</span>");
                    $('#nome-usuario').css("border-color", "#dc3545");
                } else {
                    $('#msgUser').html("");
                    $('#nome-usuario').css("border-color", "var(--border)");
                }
            }
        });

    });

    $('#data-nascimento').on('change keyup', function () {
        const data = $(this).val();

        if (!validarData(data)) {
            $('#data-nascimento').css("border-color", "#dc3545");
            $('#msgData').html("<span style='color: #dc3545; font-size: 12px;'>Data inválida</span>");
            return;
        }

        $('#msgData').html("");
        $('#data-nascimento').css("border-color", "var(--border)");
    });
});


function validarData(dataStr) {
    const data = new Date(dataStr);

    // Se data inválida
    if (isNaN(data.getTime())) {
        return false;
    }
    return true;
}

function validarData(dataStr) {
    const data = new Date(dataStr);
    const hoje = new Date();

    if (isNaN(data.getTime())) return false;
    if (data > hoje) return false;

    return true;
}
