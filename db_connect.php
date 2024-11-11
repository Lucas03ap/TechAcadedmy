<?php
$host = '127.0.0.1';
$port = '3306';
$db = 'bd_techy';
$user = 'root';
$pass = '';

// Cria a conexão com o banco de dados
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Verifica se a conexão foi bem-sucedida
if (!$conn) {
    die('Erro de conexão: ' . mysqli_connect_error());
}
?>
