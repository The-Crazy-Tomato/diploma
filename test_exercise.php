<?php
require_once 'config.php';

// Посмотрим какие упражнения есть
$stmt = $pdo->query("SELECT id, text FROM exercises");
echo "<h2>Упражнения в базе:</h2>";
while($row = $stmt->fetch()) {
    echo "ID: {$row['id']} - Текст: " . substr($row['text'], 0, 50) . "...<br>";
}

// Попробуем удалить одно (например id=1)
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM exercise_images WHERE exercise_id = ?");
    $stmt->execute([$id]);
    $stmt = $pdo->prepare("DELETE FROM exercises WHERE id = ?");
    $stmt->execute([$id]);
    echo "<p style='color:green'>Упражнение ID $id удалено</p>";
    header('Refresh:2');
}
?>