<?php
session_start();
require 'code/dbh.inc.php';
error_reporting(E_ALL & ~E_NOTICE);

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: acesso.php"); // Redireciona para a página de login se não estiver logado
    exit;
}

// Adiciona nova leitura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $book_title = $_POST['book_title'];
    $total_pages = $_POST['total_pages'];
    $pages_per_day = $_POST['pages_per_day'];

    // Insere nova leitura
    $stmt = $pdo->prepare("INSERT INTO readings (user_id, book_title, total_pages, pages_per_day) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $book_title, $total_pages, $pages_per_day]);

    header("Location: principal.php");
    exit;
}

// Edita uma leitura existente
if (isset($_GET['id'])) {
    $reading_id = $_GET['id'];

    // Pega os dados da leitura
    $stmt = $pdo->prepare("SELECT * FROM readings WHERE id = ? AND user_id = ?");
    $stmt->execute([$reading_id, $_SESSION['user_id']]);
    $reading = $stmt->fetch();

    if (!$reading) {
        die("Leitura não encontrada ou você não tem permissão para editá-la.");
    }

    // Processa o formulário de edição
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
        $book_title = $_POST['book_title'];
        $total_pages = $_POST['total_pages'];
        $pages_per_day = $_POST['pages_per_day'];

        // Atualiza os dados da leitura
        $stmt = $pdo->prepare("UPDATE readings SET book_title = ?, total_pages = ?, pages_per_day = ? WHERE id = ?");
        $stmt->execute([$book_title, $total_pages, $pages_per_day, $reading_id]);

        header("Location: principal.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>A.L.V.In - Gerenciar Leituras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= isset($reading) ? "Editar Leitura" : "Adicionar Nova Leitura" ?></h1>
    <div class="main">
        <form method="POST">
            <input type="hidden" name="reading_id" value="<?= isset($reading) ? htmlspecialchars($reading['id']) : '' ?>">
            <input type="hidden" name="action" value="<?= isset($reading) ? 'edit' : 'add' ?>">
            <label>Título do Livro: <input type="text" name="book_title" value="<?= isset($reading) ? htmlspecialchars($reading['book_title']) : '' ?>" required></label><br>
            <label>Número de Páginas: <input type="number" name="total_pages" value="<?= isset($reading) ? htmlspecialchars($reading['total_pages']) : '' ?>" required></label><br>
            <label>Páginas por Dia: <input type="number" name="pages_per_day" value="<?= isset($reading) ? htmlspecialchars($reading['pages_per_day']) : '' ?>" required></label><br>
            <label>Data de Início: <input type="datetime" name="start_date" value="<?= isset($reading) ? htmlspecialchars($reading['start_date']) : '' ?>" required></label><br>
            <button type="submit" class="botao"><?= isset($reading) ? "Salvar Alterações" : "Adicionar Leitura" ?></button>
        </form>
        <a href="principal.php"><button>Voltar à página principal</button></a>
    </div>
</body>
</html>