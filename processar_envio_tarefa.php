<?php
session_start();
require 'db_connect.php';

// Verifica se o usuário é um aluno
if ($_SESSION['tipo_usuario'] != 'aluno') {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_tarefa = (int)$_POST['id_tarefa'];
    $id_aluno = $_SESSION['user_id'];

    // Caminho onde os arquivos serão salvos
    $diretorio = 'uploads/';
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    $arquivo = $_FILES['arquivo'];
    $nome_arquivo = uniqid() . "_" . basename($arquivo['name']); // Nome único para evitar conflitos
    $caminho_arquivo = $diretorio . $nome_arquivo;

    // Move o arquivo para o diretório de uploads
    if (move_uploaded_file($arquivo['tmp_name'], $caminho_arquivo)) {
        // Insere o envio da tarefa no banco de dados
        $sql = "INSERT INTO envios_tarefas (id_tarefa, id_aluno, arquivo) 
                VALUES ('$id_tarefa', '$id_aluno', '$caminho_arquivo')";

        if (mysqli_query($conn, $sql)) {
            echo "Tarefa enviada com sucesso!";
        } else {
            echo "Erro ao registrar envio: " . mysqli_error($conn);
        }
    } else {
        echo "Erro ao enviar arquivo.";
    }
}
?>
