<?php
session_start();
require 'db_connect.php';

// Verifica se o usuário é um professor
if ($_SESSION['tipo_usuario'] != 'professor') {
    die(json_encode(['status' => 'error', 'message' => 'Acesso negado']));
}

// Consulta os resgates de cupons
$query = "
    SELECT cr.id, u.nome AS aluno_nome, c.nome AS cupom_nome, cr.data_resgate
    FROM cupons_resgatados cr
    INNER JOIN usuarios u ON cr.aluno_id = u.id_usuario
    INNER JOIN cupons c ON cr.cupom_id = c.id
    ORDER BY cr.data_resgate DESC
";
$result = mysqli_query($conn, $query);

$resgates = [];
while ($row = mysqli_fetch_assoc($result)) {
    $resgates[] = $row;
}

echo json_encode($resgates);
?>
