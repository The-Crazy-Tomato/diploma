<?php
require_once 'config.php';
header('Content-Type: application/json');

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM exercises WHERE id = ?");
$stmt->execute([$id]);
$exercise = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM exercise_images WHERE exercise_id = ?");
$stmt->execute([$id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$exercise['images'] = $images;
echo json_encode($exercise);
?>