<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../auth/login.php");
    exit;
}
include '../banco/conectaBD.php';

$sql = "SELECT * FROM tb_tipo_colaboracao";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../modoEscuro.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/criarColaboracao.js" defer></script>
    <script src="../scripts/tag.js" defer></script>
    <script src="../scripts/validarCriar.js" defer></script>
    <link rel="stylesheet" href="Criar.css">
    <title>Criar Colaboração</title>
</head>
<body>
    <div class="container">
        <div class="voltar">
            <a href="../Colaboracoes.php">
                <i class="bi bi-chevron-left"></i>
            </a> 
        </div>
        <div class="texto">
            <h1>Criar Colaboração</h1>
            <p>Preencha os campos a seguir para postar uma colaboração na sessão de colaborações.</p>
        </div>
        <hr>
        <div class="form">
            <div class="grupo-input">
                <label>Título<span class="obrigatorio">*</span></label>
                <input type="text" id="titulo" placeholder="Digite...">
            </div>
            <div class="grupo-input">
                <label>Tipo de Colaboração<span class="obrigatorio">*</span></label>
                <select id="tipo">
                     <option value="valor1">Selecione</option>
                    <?php foreach ($rows as $row) { ?>
                    <option value="<?php echo $row['id_tipo_colaboracao']; ?>"><?php echo $row['nm_tipo'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="grupo-input">
                <label>Descrição <span class="obrigatorio">*</span></label>
                <textarea  id="descricao" rows="4" cols="50" placeholder="Digite..." required></textarea>
            </div>
            <div class="grupo-input">
                <label>Tags</label>
                <div class="post-tags">
                    <div id="tag-list"></div>
                    <button type="button" class="ver-mais" onclick="mostrarCampoTag()">
                    <img src="../icons/mais.png" alt="Adicionar Tag">
                    </button>

                    <div id="campo-tag" class="adicionar" style="display: none;">
                        <input type="text" id="novaTag" maxlength="38" placeholder="Digite a tag">
                        <button type="button" onclick="fecharCampoTag()" class="fechar">
                            <img src="../icons/x.png" alt="Cancelar">
                        </button>
                        <button type="button" class="ok" onclick="adicionarTag()">
                            <img src="../icons/ok.png" alt="Confirmar">
                        </button>
                    </div>
                </div>
            </div>
            <button  class="btn btn-cancelar" onclick="window.location.href='../Colaboracoes.php'">Cancelar</button>
            <button  class="btn btn-avancar">Postar</button>
        </div>
    </div>
    <div id="msg"></div>
    <script src="../configuracao/modo.js"></script>
    <script>
        function aer(){
            const add = document.getElementById('adicionartag');
            add.style.visibility = 'visible' ;
        }
    </script>
</body>
</html>