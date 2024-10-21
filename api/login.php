<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'SELECT * FROM users WHERE name=:username;'
    );
    $stmt->execute(['username' => $data['username']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        echo json_encode(['success' => false, 'username' => $data['username']]);
    } else {
        echo json_encode(['success' => true, 'username' => $data['username'], 'id' => $result[0]["authorId"]]);
    }
}
?>