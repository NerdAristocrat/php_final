<?php
session_start();
require 'code/dbh.inc.php';
error_reporting(E_ALL & ~E_NOTICE);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>A.L.V.In - Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <div class="login">
        <?php
            echo "É bom tê-lo(a) conosco!<br>Para se cadastrar, insira seus dados:<br><br>";
            $error = '';
        ?>
    <form method="POST">
        <label>Usuário: <input type="text" name="username" required></label><br><br>
        <label>Senha: <input type="password" name="password" required></label><br><br>
        <button type="submit">Cadastrar</button><br><br>
        <?php if ($error): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>
    </form>
    <a href="acesso.php"><button>Voltar ao Login</button></a>

        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // Verifica se o usuário já existe
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    // Mensagem de erro se o usuário já existe
                    $error = "Nome de usuário já escolhido. Por favor, escolha outro.";
                } else {
                    // Insere o novo usuário
                    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    $stmt->execute([$username, $password]);
                    header("Location: acesso.php");
                    exit;
                }
            }
        ?>
    </div>
</body>
</html>