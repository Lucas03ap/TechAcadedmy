<?php
$host = '127.0.0.1';
$port = '3306';
$db = 'bd_techy';
$user = 'root';
$pass = '';

#cria a conexao cm o bd
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Verifica se a conexao deu certo
if (!$conn) {
    die('Conexão com o banco de dados falhou: ' . mysqli_connect_error());
}

// Exibi uma mensagem de sucesso
echo "Conexão com o banco de dados estabelecida com sucesso!";
?>
