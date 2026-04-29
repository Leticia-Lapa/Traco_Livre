<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="Login.css">
    <script src="../scripts/login.js"></script>
    <link rel="stylesheet" href="eescuro.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="imagem-container">
            <img src="../imgs/bem.png" alt="Bem-vindo" id="bemvindo">
        </div>

        <div class="voltar">
            <a href="../index.html">
                <i class="bi bi-chevron-left"></i>
            </a> 
        </div>

        <div class="texto">
            <h1>Bem-vindo de volta!</h1>
            <p>Que bom ver você de novo.</p>
        </div>

        <div class="form">
            <div class="grupo-input">
                <label>Nome de Usuário ou Email <span class="obrigatorio">*</span></label>
                <input type="text" required placeholder="Insira..." class="login">
            </div>

            <div class="grupo-input">
                <label>Senha <span class="obrigatorio">*</span></label>
                <div class="input-icone">
                    <input type="password" required placeholder="Insira..." class="senha" id="senha">
                    <span><i class="bi bi-eye-slash" id="mostrar" onclick="versenha()"></i></span>
                </div>
                <a href="EsqueciSenha/EsqSenha.html" class="forgot-link">Esqueci minha senha</a>
            </div>
            <button  class="btn-entrar">Entrar</button>
        </div>
    </div>
    <div id="msg"></div>
     <script src="../configuracao/modo.js"></script>
    <script src="loginE.js"></script>
</body>
</html>