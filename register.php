<?php
// arquivo de conexão com o bd
include('db_connect.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar e filtrar os dados do formulário
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $confirmar_senha = mysqli_real_escape_string($conn, $_POST['confirmar_senha']);
    $tipo_usuario = 'aluno'; // resgistrar qualquer usuario, o tipo dele será aluno

    // Verifica se as senhas correspondem
    if ($senha !== $confirmar_senha) {
        header('Location: register.html?status=error');
        exit();
    } else {
        // Criptografa a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Verifica se o email ja existe
        $check_email = "SELECT email FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            header('Location: register.html?status=error');
        } else {
            // Inseri novo usuario no banco de dados
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) 
                    VALUES ('$nome', '$email', '$senha_hash', '$tipo_usuario')";

            if (mysqli_query($conn, $sql)) {
                header('Location: register.html?status=success');
            } else {
                header('Location: register.html?status=error');
            }
        }
        exit();
    }
}
?>
