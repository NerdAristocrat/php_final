<?php
session_start();
require 'code/dbh.inc.php';

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
    die("Leitura não encontrada ou você não tem permissão para encerrar.");
}

// Calcula a média de páginas por dia
$start_date = new DateTime($reading['start_date']); // Você deve ter uma coluna start_date na tabela
$end_date = new DateTime();
$interval = $start_date->diff($end_date);
$total_days = $interval->days > 0 ? $interval->days : 1; // Evita divisão por zero

$total_pages = $reading['total_pages'];
$average_pages_per_day = $total_pages / $total_days;

// Atualiza a leitura como finalizada
$stmt = $pdo->prepare("UPDATE readings SET finished = 1, pages_per_day = ? WHERE id = ?");
$stmt->execute([$average_pages_per_day, $reading_id]);

header("Location: principal.php");
exit;