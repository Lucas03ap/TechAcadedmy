<?php
// Inclui o arquivo de conexão com o banco de dados
include('db_connect.php');

// Consulta para buscar alunos
$query = "SELECT id_usuario, nome FROM usuarios WHERE tipo_usuario = 'aluno'";
$result = mysqli_query($conn, $query);

// Gera opções de alunos no formato HTML
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='" . $row['id_usuario'] . "'>" . $row['nome'] . "</option>";
}
?>
