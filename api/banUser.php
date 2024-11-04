<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'DELETE FROM roomAccess WHERE authorId=:authorId AND roomId=:roomId'
    );
    $stmt->execute(['authorId' => $data['authorId'], 'roomId' => $data['roomId']]);
    echo json_encode(['success' => true,]);
}
?>