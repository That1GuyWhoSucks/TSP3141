<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'UPDATE users SET status=:newStatus WHERE authorId=:authorId;'
    );
    $stmt->execute(['newStatus' => $data['status'], 'authorId' => $data['authorId']]);
    // echo json_encode(['passed' => true]);
    echo json_encode($data);
}
?>