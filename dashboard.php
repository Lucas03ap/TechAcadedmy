<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tech Academy</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
    <a href="logout.php">Sair</a>
</body>
</html>
