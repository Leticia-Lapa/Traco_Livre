<?php

  $host = ""; // Seu nome de host no InfinityFree
  $user = "";      // Seu usuário do banco de dados no InfinityFree
  $a = "";        // Sua senha do banco de dados no InfinityFree
  $dbName = ""; // O nome do seu banco de dados no InfinityFree

  try {
    // Use PDO para conectar ao banco de dados do InfinityFree    
      $conn = new PDO("mysql:host=$host;dbname=$dbName", $user, $a);
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
//      // Ajuste de fuso horário no MySQL
     $conn->exec("SET time_zone = '-03:00'");

     // Ajuste de fuso horário no PHP
     date_default_timezone_set('America/Sao_Paulo');
     
  } catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
  }
?>