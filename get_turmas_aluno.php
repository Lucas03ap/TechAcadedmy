<?php
session_start();
require 'db_connect.php';

// Verifica se o usuário é um aluno
if ($_SESSION['tipo_usuario'] != 'aluno') {
    die("Acesso negado.");
}

$id_aluno = $_SESSION['user_id'];

// Consulta para obter as turmas do aluno e as tarefas associadas
$query = "
    SELECT turmas.id AS turma_id, turmas.nome AS turma_nome, tarefas.titulo AS tarefa_titulo, tarefas.data_limite
    FROM turmas
    INNER JOIN alunos_turmas ON turmas.id = alunos_turmas.turma_id
    LEFT JOIN tarefas ON turmas.id = tarefas.id_turma
    WHERE alunos_turmas.usuario_id = ?
    ORDER BY turmas.nome, tarefas.data_limite
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_aluno);
$stmt->execute();
$result = $stmt->get_result();

$turmas = [];
while ($row = $result->fetch_assoc()) {
    $turma_id = $row['turma_id'];
    if (!isset($turmas[$turma_id])) {
        $turmas[$turma_id] = [
            'nome' => $row['turma_nome'],
            'tarefas' => []
        ];
    }

    if ($row['tarefa_titulo']) {
        $turmas[$turma_id]['tarefas'][] = [
            'titulo' => $row['tarefa_titulo'],
            'data_limite' => $row['data_limite']
        ];
    }
}

$stmt->close();

header('Content-Type: application/json');
echo json_encode(array_values($turmas));
?>
