<?php
session_start();
require 'db_connect.php';

header('Content-Type: application/json');

// Verifica se o usuário está logado e se é um aluno
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] != 'aluno') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['cupomId']) || !isset($input['custoPontos'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
    exit();
}

$cupomId = (int)$input['cupomId'];
$custoPontos = (int)$input['custoPontos'];
$alunoId = $_SESSION['user_id'];

// Verifica se o aluno tem pontos suficientes
$query = "SELECT pontos FROM pontos_alunos WHERE usuario_id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $alunoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row && $row['pontos'] >= $custoPontos) {
        // Subtrai os pontos e atualiza o banco de dados
        $novaPontuacao = $row['pontos'] - $custoPontos;
        $updatePontos = "UPDATE pontos_alunos SET pontos = ? WHERE usuario_id = ?";
        $stmtUpdate = $conn->prepare($updatePontos);

        if ($stmtUpdate) {
            $stmtUpdate->bind_param("ii", $novaPontuacao, $alunoId);
            if ($stmtUpdate->execute()) {
                // Registra o resgate do cupom
                $insertResgate = "INSERT INTO cupons_resgatados (aluno_id, cupom_id) VALUES (?, ?)";
                $stmtInsert = $conn->prepare($insertResgate);

                if ($stmtInsert) {
                    $stmtInsert->bind_param("ii", $alunoId, $cupomId);
                    if ($stmtInsert->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Cupom resgatado com sucesso!']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar o resgate do cupom.']);
                    }
                    $stmtInsert->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar o registro do resgate do cupom.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar os pontos.']);
            }
            $stmtUpdate->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar a atualização de pontos.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pontos insuficientes.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao verificar pontos do aluno.']);
}
?>
