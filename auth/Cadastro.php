<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="Cadastro.css">
    <link rel="stylesheet" href="eescuro.css">
    <script src="../scripts/cadastro.js"></script>
    <title>Cadastre-se</title>
</head>
<body>
    <div class="container">
        <div class="texto">
            <h1>Criar conta</h1>
            <p>Preencha os dados a seguir para se registrar no Traço Livre</p>
        </div>
        <hr>
        <div class="form">
            <div class="grupo-input">
                <label>Nome Completo <span class="obrigatorio">*</span></label>
                <input type="text" required placeholder="Insira..." id="nome-completo">
            </div>
            <div class="grupo-input">
                <label>Nome de Usuário <span class="obrigatorio">*</span></label>
                <input type="text" required placeholder="Insira..." id="nome-usuario">
                <div id="msgUser"></div>
            </div>
            <div class="grupo-input">
                <label>Data de Nascimento <span class="obrigatorio">*</span></label>
                <input type="date" required placeholder="Insira..." id="data-nascimento">
                <div id="msgData"></div>
            </div>
            <div class="grupo-input">
                <label>Número de Celular</label>
                <input type="text" required placeholder="Insira..." id="telefone">
            </div>
            <div class="grupo-input">
                <label>Email<span class="obrigatorio">*</span></label>
                <input type="email" required placeholder="Insira..." id="email">
            </div>
            <div class="grupo-input">
                <label>Senha<span class="obrigatorio">*</span></label>
                <div class="input-icone">
                    <input type="password" required placeholder="Insira..." id="senha" oninput="validarSenha()">
                    <span><i class="bi bi-eye-slash" id="mostrar" onclick="versenha()"></i></span>
                </div>
                <div class="requisitos-senha">
                    <ul>
                        <li data-requisito="minLength"><i class="bi bi-x-circle"></i> Mínimo de 8 caracteres</li>
                        <li data-requisito="uppercase"><i class="bi bi-x-circle"></i> Uma letra maiúscula</li>
                        <li data-requisito="number"><i class="bi bi-x-circle"></i> Um número</li>
                        <li data-requisito="special"><i class="bi bi-x-circle"></i> Um caractere especial (@$!%*?&)</li>
                    </ul>
                </div>
            </div>
            <div class="grupo-input">
                <label>Confirmar Senha<span class="obrigatorio">*</span></label>
                <div class="input-icone">
                    <input type="password" required placeholder="Insira..." id="csenha" oninput="validarSenha()">
                    <span><i class="bi bi-eye-slash" id="cmostrar" onclick="vercsenha()"></i></span>
                </div>
                <div class="erro-senha" id="erro-confirmar">As senhas não coincidem</div>
            </div>
            <button  class="btn btn-cancelar" onclick="window.location.href='../index.html'">Cancelar</button>
            <button  class="btn btn-avancar" disabled>Avançar</button>
        </div>
    </div>
     <script src="../configuracao/modo.js"></script>
</body>
</html>
