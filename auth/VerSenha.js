 function versenha() {
    var inputPass = document.getElementById('senha')
    var ver = document.getElementById('mostrar')

    if(inputPass.type === 'password') {
        inputPass.setAttribute('type', 'text')
        ver.classList.replace('bi-eye', 'bi-eye-slash')
    }
    else {
        inputPass.setAttribute('type', 'password')
        ver.classList.replace('bi-eye-slash', 'bi-eye')
    }
}

function vercsenha() {
    var inputPass = document.getElementById('csenha')
    var ver = document.getElementById('cmostrar')

    if(inputPass.type === 'password') {
        inputPass.setAttribute('type', 'text')
        ver.classList.replace('bi-eye', 'bi-eye-slash')
    }
    else {
        inputPass.setAttribute('type', 'password')
        ver.classList.replace('bi-eye-slash', 'bi-eye')
    }
}

function vernsenha() {
    var inputPass = document.getElementById('nsenha')
    var ver = document.getElementById('nmostrar')

    if(inputPass.type === 'password') {
        inputPass.setAttribute('type', 'text')
        ver.classList.replace('bi-eye', 'bi-eye-slash')
    }
    else {
        inputPass.setAttribute('type', 'password')
        ver.classList.replace('bi-eye-slash', 'bi-eye')
    }
}