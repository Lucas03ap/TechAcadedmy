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

if ($user) {
    //validar a senha(password_verify)
    if (password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['nome'] = $user['nome'];
        //Como Teste de Sucesso.   Caso o login seja efetuado com sucesso, o sistema deve redirecionar para a pagina inicial do google
        header("Location: https://www.google.com");
        exit();
    } else {
        header("Location: login.html?error=senha");
        exit();
    }
} else {
    header("Location: login.html?error=email");
    exit();
}
?>
