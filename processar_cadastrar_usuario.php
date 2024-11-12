<?php
session_start();
require 'db_connect.php';

// Verifica se o usuário é um admin
if ($_SESSION['tipo_usuario'] != 'admin') {
    die("Acesso negado.");
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $confirmar_senha = mysqli_real_escape_string($conn, $_POST['confirmar_senha']);
    $tipo_usuario = $_POST['tipo_usuario'];

    // Verifica se as senhas correspondem
    if ($senha !== $confirmar_senha) {
        header('Location: cadastrar_usuario.html?status=error');
        exit();
    } else {
        // Criptografa a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Verifica se o email já existe
        $check_email = "SELECT email FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            header('Location: cadastrar_usuario.html?status=error');
        } else {
            // Insere o novo usuário no banco de dados
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) 
                    VALUES ('$nome', '$email', '$senha_hash', '$tipo_usuario')";

            if (mysqli_query($conn, $sql)) {
                header('Location: cadastrar_usuario.html?status=success');
            } else {
                header('Location: cadastrar_usuario.html?status=error');
            }
        }
        exit();
    }
}
?>
