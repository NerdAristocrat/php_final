<?php
session_start();
require 'code/dbh.inc.php';
error_reporting(E_ALL & ~E_NOTICE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nome'];
    $password = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["user_id"] = $user['id']; // Armazena o ID do usuário na sessão
        header('Location: principal.php');
        exit;
    } else {
        $error = "Dados inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A.L.V.In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <h1>A.L.V.In</h1>
    <h2>Seja bem-vindo ao seu Auxiliar de Leitura Virtual pela Internet!</h2>
    <div class="login">
        <p>Por favor, insira seus dados:</p>
        <form method="post" action="">
            <label for="nome">Usuário:</label>
            <input type="text" id="nome" name="nome" required><br><br>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br><br>
            <button type="submit" class="botao">Acessar</button><br><br>
        </form>
        <form method="post" action="cadastro.php">
            <button type="submit" class="botao">Caso não tenha cadastro, realize-o aqui!</button>
        </form>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>
    </div>
</body>
</html>