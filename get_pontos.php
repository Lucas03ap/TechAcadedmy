<?php
session_start();
require 'db_connect.php';

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] != 'aluno') {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit();
}

$usuario_id = $_SESSION['user_id'];

// Inicializa os pontos com valor padrão
$pontos = 0;

// Obter os pontos do aluno
$pontos_query = "SELECT pontos FROM pontos_alunos WHERE usuario_id = ?";
$stmt = $conn->prepare($pontos_query);

if ($stmt) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $pontos = $row['pontos'];
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar consulta de pontos']);
    exit();
}

// Obter os cupons disponíveis
$cupons = [];
$cupons_query = "SELECT id, nome, descricao, pontos_custo FROM cupons";
$cupons_result = mysqli_query($conn, $cupons_query);

if ($cupons_result) {
    while ($row = mysqli_fetch_assoc($cupons_result)) {
        $cupons[] = $row;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao consultar cupons disponíveis']);
    exit();
}

// Retornar dados em JSON
echo json_encode([
    'status' => 'success',
    'pontos' => $pontos,
    'cupons' => $cupons
]);
exit();
?>
