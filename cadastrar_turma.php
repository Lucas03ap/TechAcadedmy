<?php
// Inclui o arquivo de conexão com o banco de dados
include('db_connect.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém o nome da turma e os IDs dos alunos selecionados
    $nome_turma = mysqli_real_escape_string($conn, $_POST['nome-turma']);
    
    // Verifica se o array de alunos foi enviado e não está vazio
    if (isset($_POST['alunos']) && is_array($_POST['alunos'])) {
        $alunos = $_POST['alunos']; // Array de IDs de alunos

        // Inicia uma transação para garantir consistência dos dados
        mysqli_begin_transaction($conn);

        try {
            // Insere a nova turma na tabela turmas
            $sql_turma = "INSERT INTO turmas (nome) VALUES ('$nome_turma')";
            if (!mysqli_query($conn, $sql_turma)) {
                throw new Exception("Erro ao inserir a turma: " . mysqli_error($conn));
            }

            // Obtém o ID da turma recém-inserida
            $turma_id = mysqli_insert_id($conn);

            // Insere os vínculos dos alunos selecionados na tabela alunos_turmas
            foreach ($alunos as $aluno_id) {
                $aluno_id = (int)$aluno_id; // Assegura que o ID do aluno é um número inteiro
                $sql_aluno_turma = "INSERT INTO alunos_turmas (turma_id, usuario_id) VALUES ('$turma_id', '$aluno_id')";
                if (!mysqli_query($conn, $sql_aluno_turma)) {
                    throw new Exception("Erro ao vincular aluno ID $aluno_id à turma: " . mysqli_error($conn));
                }
            }

            // Confirma a transação
            mysqli_commit($conn);

            // Redireciona com status de sucesso
            header('Location: cadastrar_turma.html?status=success');
            exit();
        } catch (Exception $e) {
            // Em caso de erro, desfaz a transação
            mysqli_rollback($conn);
            // Log de erro (pode ser substituído por gravação em log de arquivo ou exibição para debug)
            error_log("Erro ao cadastrar turma: " . $e->getMessage());
            // Redireciona com status de erro
            header('Location: cadastrar_turma.html?status=error');
            exit();
        }
    } else {
        // Caso não tenha alunos selecionados, redireciona com erro
        header('Location: cadastrar_turma.html?status=no_students_selected');
        exit();
    }
}
?>
