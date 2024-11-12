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
        // Consulta para obter a data limite da tarefa
        $sql_tarefa = "SELECT data_limite FROM tarefas WHERE id = ?";
        $stmt = $conn->prepare($sql_tarefa);
        $stmt->bind_param("i", $id_tarefa);
        $stmt->execute();
        $result = $stmt->get_result();
        $tarefa = $result->fetch_assoc();

        if ($tarefa) {
            $data_limite = $tarefa['data_limite'];
            $data_envio = date('Y-m-d H:i:s');

            // Verifica se o envio foi feito antes da data limite
            if ($data_envio <= $data_limite) {
                // Conceder pontos ao aluno
                $update_points_sql = "INSERT INTO pontos_alunos (usuario_id, pontos) VALUES (?, 2) 
                                      ON DUPLICATE KEY UPDATE pontos = pontos + 2";
                $stmt = $conn->prepare($update_points_sql);
                $stmt->bind_param("i", $id_aluno);
                $stmt->execute();
            }
        }

        // Insere o envio da tarefa no banco de dados
        $sql_envio = "INSERT INTO envios_tarefas (id_tarefa, id_aluno, arquivo, data_envio) 
                      VALUES ('$id_tarefa', '$id_aluno', '$caminho_arquivo', '$data_envio')";

        if (mysqli_query($conn, $sql_envio)) {
            echo "Tarefa enviada com sucesso!";
        } else {
            echo "Erro ao registrar envio: " . mysqli_error($conn);
        }
    } else {
        echo "Erro ao enviar arquivo.";
    }
}
?>
