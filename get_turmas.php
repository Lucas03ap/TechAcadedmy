<?php
require 'db_connect.php';

header('Content-Type: application/json');

// Consulta para obter as turmas
$result = mysqli_query($conn, "SELECT id, nome FROM turmas");

$turmas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $turmas[] = $row;
}

// Retorna as turmas como JSON
echo json_encode($turmas);
?>
