<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = accessDB();
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare(
        'INSERT INTO roomAccess (authorId, roomId, role) VALUES (:authorId, :roomId, 0);'
    );
    $stmt->execute(['roomId' => $data['roomId'], 'authorId' => $data['authorId']]);
    echo json_encode(['success' => true]);
}
?>