<?php
session_start();
require_once 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventory_id = intval($_POST['inventory_id']);
    $text = trim($_POST['text']);
    
    if($text) {
        $stmt = $pdo->prepare("INSERT INTO comments (inventory_id, text) VALUES (?, ?)");
        $stmt->execute([$inventory_id, $text]);
    }
    
    header("Location: item_detail.php?id=$inventory_id");
    exit;
}
?>