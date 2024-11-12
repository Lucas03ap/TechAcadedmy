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
    $id_turma = $_POST['id_turma']; // Novo campo para a turma

    // Prepara a consulta para evitar injeção de SQL
    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, data_limite, id_professor, id_turma) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $titulo, $descricao, $data_limite, $id_professor, $id_turma);

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
