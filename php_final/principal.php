<?php
session_start();
require 'code/dbh.inc.php';
error_reporting(E_ALL & ~E_NOTICE);

if (!isset($_SESSION['user_id'])) {
    header("Location: acesso.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Pega os dados do usuário
$stmt = $pdo->prepare("SELECT * FROM readings WHERE user_id = ?");
$stmt->execute([$user_id]);
$readings = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A.L.V.In - Meu Progresso de Leitura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Meu Progresso de Leitura</h1>
    <div class="main">
        <?php if (empty($readings)): ?>
            <p>Acervo vazio! Vamos começar?</p>
            <a href="edit.php"><button class="botao">Clique aqui para começar</button></a>
        <?php else: ?>
            <?php foreach ($readings as $reading): ?>
                <h2>Livro: <?= htmlspecialchars($reading['book_title']) ?></h2>
                <p>Início: <?= $reading['start_date'] ?></p>
                <p>Término: <?= $reading['end_date'] ?: 'Em andamento' ?></p>
                <p>Páginas lidas: <?= $reading['pages_read'] ?></p>
                <p>Páginas por dia: <?= round($reading['pages_read'] / (($reading['end_date'] ? (new DateTime($reading['end_date'])) : (new DateTime()))->diff(new DateTime($reading['start_date']))->days), 2) ?></p>
                <a href="finish.php?id=<?= htmlspecialchars($reading['id']) ?>"><button>Encerrar Leitura</button></a>
                <a href="atual.php?id=<?= htmlspecialchars($reading['id']) ?>"><button>Atualizar Leitura</button></a>
                <hr>
            <?php endforeach; ?>
            <a href="edit.php"><button type="submit" class="botao">Clique aqui para adicionar uma leitura</button></a>
        <?php endif; ?>
    </div>
</body>
</html>