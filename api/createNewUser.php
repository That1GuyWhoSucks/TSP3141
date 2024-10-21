<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, status) VALUES (:name, :status);
        INSERT INTO roomAccess (SELECT LAST_INSERT_ID(),roomId,0 FROM rooms WHERE autoAdd=1);'
    );
    try {
        $stmt->execute(['name' => $data['username'], 'status' => 'ONLINE']);
        echo json_encode(['success' => true, 'username' => $data['username']]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'username' => $data['username']]);
    }
}
?>