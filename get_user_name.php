<?php
session_start();

$response = [];

if (isset($_SESSION['user_id']) && isset($_SESSION['nome']) && isset($_SESSION['tipo_usuario'])) {
    // O usuário está logado, retorna o nome e o tipo
    $response['status'] = 'success';
    $response['nome'] = $_SESSION['nome'];
    $response['tipo_usuario'] = $_SESSION['tipo_usuario']; // Retorna o tipo de usuário
} else {
    // Usuário não está logado
    $response['status'] = 'error';
    $response['message'] = 'Usuário não autenticado';
}

// Retorna a resposta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
