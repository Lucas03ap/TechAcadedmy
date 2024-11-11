<?php
session_start();
require 'db_connect.php'; /*caminho do bd */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('O método de requisição deve ser POST.');
}

if (!isset($_POST['email']) || !isset($_POST['senha'])) {
    die('E-mail ou senha não foram fornecidos.');
}

$email = mysqli_real_escape_string($conn, $_POST['email']);
$senha = mysqli_real_escape_string($conn, $_POST['senha']);

$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Caso o login seja bem-sucedido
if ($user) {
    if (password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario']; // Armazena o tipo do usuário (aluno ou professor)
        header("Location: painel.html");
        exit();
    } else {
        header("Location: login.html?error=senha");
        exit();
    }
}

?>
