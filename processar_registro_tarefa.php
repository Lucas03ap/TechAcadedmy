<?php
session_start();
require 'db_connect.php';

// Verifica se o usuário é um professor
if ($_SESSION['tipo_usuario'] != 'professor') {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_limite = !empty($_POST['data_limite']) ? $_POST['data_limite'] : null;
    $id_professor = $_SESSION['user_id'];

    // Prepara a consulta para evitar injeção de SQL
    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, data_limite, id_professor) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $titulo, $descricao, $data_limite, $id_professor);

    // Executa a consulta e verifica o sucesso
    if ($stmt->execute()) {
        // Redireciona para a página de registro com uma mensagem de sucesso
        header("Location: registrar_tarefa.html?status=success");
    } else {
        // Redireciona com uma mensagem de erro
        header("Location: registrar_tarefa.html?status=error");
    }

    // Fecha o statement
    $stmt->close();
}
?>
