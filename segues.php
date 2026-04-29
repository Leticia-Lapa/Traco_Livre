<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: auth/Login.php");
  exit;
}
include 'banco/conectaBD.php';

$id_usuario = $_SESSION['id_usuario'] ?? 1;
$origem = $_GET['origem'];
$tipo = $_GET['tipo'];
$id_outroUsuario = $_GET['id'];

if ($origem === 'outro') {

  if ($tipo === 'seguidores') {
    $titulo = "Seguidores";

    //     $sql_seguidores = "SELECT s.id_seguidor, u.img-perfil, u.nm_nome_usuario FROM tb_seguidores where fk_id_usuario_seguido = :outro";
    // $stmt_seguidores = $conn->prepare($sql_seguidores);
    // $stmt_seguidores->execute([':outro' => $id_outroUsuario]);
  } else {
    $titulo = "Seguindo";
  }
} else {
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="segues.css">
  <title></title>
</head>

<body>
  <div class="container">
    <header class="top-bar">
      <?php if ($origem === "outro") { ?>
        <button id="btnVolta" class="voltar" onclick="window.location.href = 'OutroPerfil.php?id=<?php echo $id_outroUsuario; ?>'">
          <i class="bi bi-chevron-left"></i>
        </button>
      <?php } else { ?>
        <button id="btnVolta" class="voltar" onclick="window.location.href = 'Perfil.php?id= '<?php echo $id_usuario; ?>'">
          <i class="bi bi-chevron-left"></i>
        </button>
      <?php } ?>
      <div class="l3">
        <h4><?php echo $titulo; ?></h4>
      </div>
    </header>

    <div class="form">
      <a href="">
        <div class="conversa">
          <img src="" class="avatar">
          <div class="info">
            <span></span>
            <button>Seguir</button>
          </div>
        </div>
      </a>
    </div>



  </div>
  <!-- <div class="header">
       <img src="c:\Users\user\Downloads\user1.png" alt="User1"> <br>
       <a href=" c:\Users\user\Downloads\chat.html">
        <br>
         <div class="back">◀</div>
       </a>
       <br>
       <h1>Conversas</h1>
    </div> -->
  <!-- <div class="notifications"><img src="c:\Users\user\Downloads\noti.png" alt="User1"></div>
  </div>
  <div class="followers-list">
    <div class="follower">
      <div class="follower-info">
        <img src="c:\Users\user\Downloads\user1.png" alt="User1">
        <span>@NomeDeUsuário</span>
      </div>
    </div>
     <div class="follower">
      <div class="follower-info">
        <img src="c:\Users\user\Downloads\user1.png" alt="User2">
        <span>@NomeDeUsuário</span>
      </div>
    </div>
     <div class="follower">
      <div class="follower-info">
        <img src="c:\Users\user\Downloads\user1.png" alt="User3">
        <span>@NomeDeUsuário</span>
      </div>
    </div>

    <div class="follower">
      <div class="follower-info">
        <img src="c:\Users\user\Downloads\user1.png" alt="User4">
        <span>@NomeDeUsuário</span>
      </div>
    </div>

    <div class="follower">
      <div class="follower-info">
        <img src="c:\Users\user\Downloads\user1.png" alt="User5">
        <span>@NomeDeUsuário</span>
      </div>
    </div>
  </div> -->
</body>

</html>