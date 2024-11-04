<?php
require 'DB.php';
require 'hash.php';
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
        echo json_encode(['success' => false]);
        return;
    }
    if (password_verify($data['password'], $result[0]['password'])) {
        // user logged in, create session token
        $authorId = $result[0]['authorId'];
        $stmt = $pdo->prepare(
            'DELETE FROM sessions WHERE authorId=:authorId;'
        );
        $stmt->execute(['authorId' => $authorId]);
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
    } else {
        echo json_encode(['success' => false]);
    }
}
?>