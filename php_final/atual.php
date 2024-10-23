<?php
session_start();
require 'code/dbh.inc.php';
error_reporting(E_ALL & ~E_NOTICE);

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: acesso.php");
    exit;
}

// Verifica se o ID da leitura foi passado
if (!isset($_GET['id'])) {
    die("ID da leitura não fornecido.");
}

$reading_id = $_GET['id'];

// Pega os dados da leitura
$stmt = $pdo->prepare("SELECT * FROM readings WHERE id = ? AND user_id = ?");
$stmt->execute([$reading_id, $_SESSION['user_id']]);
$reading = $stmt->fetch();

if (!$reading) {
    die("Leitura não encontrada ou você não tem permissão para atualizar.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pages_read = $_POST['pages_read'];

    // Atualiza a quantidade de páginas lidas
    $stmt = $pdo->prepare("UPDATE readings SET pages_read = ? WHERE id = ?");
    $stmt->execute([$pages_read, $reading_id]);

    header("Location: principal.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>A.L.V.In - Atualizar Leitura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Atualizar Leitura: <?= htmlspecialchars($reading['book_title']) ?></h1>
    <div class="main">
        <form method="POST">
            <label>Páginas lidas até agora: <input type="number" name="pages_read" value="<?= htmlspecialchars($reading['pages_read']) ?>" required></label><br>
            <button type="submit" class="botao">Atualizar Páginas</button>
        </form>
        <a href="principal.php"><button>Voltar à página principal</button></a>
    </div>
</body>
</html>