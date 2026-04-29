<?php
include_once "conectaBD.php";

$ID = $_POST['id'] ?? null;
$tipo = $_POST['tipo'] ?? null;

if (!$ID || !$tipo) {
    echo "Erro: dados incompletos.";
    exit;
}

switch ($tipo) {
    case 'pub':
        $sql = "SELECT c.id_comentario, DATE_FORMAT(c.dt_comentario, '%d/%m/%Y %H:%i') as data_com, c.ds_comentario, c.fk_id_usuario_criador, u.nm_nome_usuario, u.img_perfil
                FROM tb_comentario c
                JOIN tb_usuario u ON u.id_usuario = c.fk_id_usuario_criador
                WHERE c.fk_id_publicacao = :id
                ORDER BY data_com DESC";
        break;

    case 'rel':
        $sql = "SELECT c.id_comentario, DATE_FORMAT(c.dt_comentario, '%d/%m/%Y %H:%i') as data_com, c.ds_comentario, c.fk_id_usuario_criador, u.nm_nome_usuario, u.img_perfil
                FROM tb_comentario c
                JOIN tb_usuario u ON u.id_usuario = c.fk_id_usuario_criador
                WHERE c.fk_id_relato = :id
                ORDER BY data_com DESC";
        break;

    case 'cob':
        $sql = "SELECT c.id_comentario, DATE_FORMAT(c.dt_comentario, '%d/%m/%Y %H:%i') as data_com, c.ds_comentario, c.fk_id_usuario_criador, u.nm_nome_usuario, u.img_perfil
                FROM tb_comentario c
                JOIN tb_usuario u ON u.id_usuario = c.fk_id_usuario_criador
                WHERE c.fk_id_colaboracao = :id
                ORDER BY data_com DESC";
        break;

    default:
        echo "Erro: tipo inválido.";
        exit;
}

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $ID);
$stmt->execute();
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($comentarios){
    foreach($comentarios as $com){
?>
<div class="comentario" id="<?php echo $com['id_comentario'];?>">
    <div class="topo-comen">
        <div class= "avatar-comen">
            <img src="<?php echo $com['img_perfil'];?>">
            <span><?php echo $com['nm_nome_usuario'];?></span>
        </div>
        <span class = "data"><?php echo $com['data_com'];?></span>
    </div>
    <div class="conteudo-comen">
        <p><?php echo $com['ds_comentario'];?></p>
    </div>
</div>
<?php }

}else{
    ?>
    <div class="msg">
        <h1>Nenhum comentario encontrado</h1>
    </div>
<?php
}
