<?php
require_once 'config.php';
header('Content-Type: application/json');

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

$news['date'] = date('d.m.Y', strtotime($news['date']));

echo json_encode(['success' => true, 'news' => $news]);
?>