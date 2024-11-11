<?php
require 'db_connect.php';

header('Content-Type: application/json');

// Verifica se a conexão foi bem-sucedida
if (!$conn) {
    echo json_encode(["error" => "Erro de conexão com o banco de dados"]);
    exit();
}

// Executa a consulta para obter as tarefas
$result = mysqli_query($conn, "SELECT id, titulo FROM tarefas");

// Verifica se a consulta foi bem-sucedida
if (!$result) {
    echo json_encode(["error" => "Erro ao obter tarefas"]);
    exit();
}

// Prepara as tarefas em um array
$tarefas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tarefas[] = $row;
}

// Retorna as tarefas como JSON
echo json_encode($tarefas);
?>
