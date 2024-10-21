<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'INSERT INTO messages (message, authorId, roomId) VALUES (:message, :authorId, :roomId);'
    );
    $stmt->execute(['message' => $data['message'], 'authorId' => $data['authorId'], 'roomId' => $data['roomId']]);
    echo json_encode(['passed' => true]);
}
?>