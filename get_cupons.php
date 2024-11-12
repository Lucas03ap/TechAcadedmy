<?php
session_start();
require 'db_connect.php';

$user_id = $_SESSION['user_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Query para buscar cupons do aluno ou todos para o professor
if ($tipo_usuario === 'aluno') {
    $query = "SELECT * FROM cupons WHERE id_aluno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
} else if ($tipo_usuario === 'professor') {
    $query = "SELECT * FROM cupons";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
$cupons = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($cupons);
$stmt->close();
?>
