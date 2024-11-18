<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'DELETE FROM messages WHERE id=:messageId;
        INSERT INTO adminActions (roomId, id, actionType, actionTarget) VALUES (:roomId, :messageId, "REM", "MESG");'
    );
    $stmt->execute(['messageId' => $data['messageId'], 'roomId' => $data['roomId']]);
    echo json_encode(['success' => true,]);
}
?>