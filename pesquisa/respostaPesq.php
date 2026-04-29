<?php 
session_start();
if (isset($_GET['Inserido'])) {
    $_SESSION['Pesquisa'] = $_GET['Inserido'];
}
else{
    $_SESSION['Pesquisa'] = null;
}
echo $_REQUEST['Inserido'];
?>