<?php
require_once 'config.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$item) {
    echo json_encode(['success' => false]);
    exit;
}

// Получаем упражнения
$exStmt = $pdo->prepare("SELECT * FROM exercises WHERE inventory_id = ?");
$exStmt->execute([$id]);
$exercises = $exStmt->fetchAll(PDO::FETCH_ASSOC);

// Для каждого упражнения получаем картинки
foreach($exercises as &$ex) {
    $imgStmt = $pdo->prepare("SELECT * FROM exercise_images WHERE exercise_id = ?");
    $imgStmt->execute([$ex['id']]);
    $ex['images'] = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
}

$comStmt = $pdo->prepare("SELECT * FROM comments WHERE inventory_id = ? ORDER BY date DESC");
$comStmt->execute([$id]);
$comments = $comStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'item' => [
        'id' => $item['id'],
        'name' => $item['name'],
        'description' => $item['description'],
        'main_img' => $item['main_img'],
        'exercises' => $exercises,
        'comments' => $comments
    ]
]);
?>