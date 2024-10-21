<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = accessDB();
    $data = json_decode(file_get_contents('php://input'), true);
    $timeWaited = 0;
    while (true) {
        $stmt = $pdo->prepare(
            'SELECT * FROM messages NATURAL JOIN users WHERE timestamp > :lastTimestamp AND roomId = :roomId;'
        );
        $stmt->execute(['lastTimestamp' => $data['lastTimestamp'], 'roomId' => $data['roomId']]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($messages) || $timeWaited > 50) {
            echo json_encode($messages);
            break;
        }
        sleep(1);
        $timeWaited++;
    }
}
?>