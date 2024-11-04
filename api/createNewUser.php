<?php
require 'DB.php';
require 'hash.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, status, password) VALUES (:name, :status, :password);
        INSERT INTO roomAccess (SELECT LAST_INSERT_ID(),roomId,0 FROM rooms WHERE autoAdd=1);'
    );
    try {
        $stmt->execute(['name' => $data['username'], 'status' => 'ONLINE', 'password' => password_hash($data['password'], PASSWORD_DEFAULT)]);
        $stmt = $pdo->prepare(
            'SELECT authorId FROM users WHERE name=:username;'
        );
        $stmt->execute(['username' => $data['username']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $authorId = $result[0]['authorId'];
        $stmt = $pdo->prepare(
            'INSERT INTO sessions (authorId, sessionId) VALUES (:authorId, :sessionId);'
        );
        $sessionId = generateRandomString();
        while (true) {
            try {
                $stmt->execute(['authorId' => $authorId, 'sessionId' => $sessionId]);
                break;
            } catch (Exception $e) {
                $sessionId = generateRandomString();
            }
        }
        
        echo json_encode(['success' => true, 'sessionId' => $sessionId]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'username' => $data['username']]);
    }
}
?>