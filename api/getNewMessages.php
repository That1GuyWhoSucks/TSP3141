<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = accessDB();
    $data = json_decode(file_get_contents('php://input'), true);
    $lastUpdate = date("Y-m-d H:i:s");
    $timeWaited = 0;
    while (true) {
        $stmt = $pdo->prepare(
            'SELECT authorId, Id, message, timestamp, roomId, name FROM messages NATURAL JOIN users WHERE timestamp > :lastTimestamp AND roomId = :roomId;'
        );
        $stmt->execute(['lastTimestamp' => $data['lastTimestamp'], 'roomId' => $data['roomId']]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare(
            'SELECT * FROM adminActions WHERE roomId = :roomId AND timestamp > :timestamp;'
        );
        $stmt->execute(['timestamp' => $lastUpdate, 'roomId' => $data['roomId']]);
        $updates = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($messages) || $timeWaited > 50 || !empty($updates)) {
            echo json_encode(["messages" => $messages, "updates" => $updates]);
            break;
        }
        sleep(2);
        $timeWaited += 2;
    }
}
?>