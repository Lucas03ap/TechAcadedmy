<?php
session_start();
require 'db_connect.php';

// Verifica se o usuário é um admin
if ($_SESSION['tipo_usuario'] != 'admin') {
    die("Acesso negado.");
}

header('Content-Type: application/json');

// Consulta para obter todos os usuários
$result = mysqli_query($conn, "SELECT id_usuario AS id, nome, email, tipo_usuario FROM usuarios");

$usuarios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row;
}

// Retorna os usuários como JSON
echo json_encode($usuarios);
?>
