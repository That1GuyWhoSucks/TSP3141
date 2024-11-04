<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'DELETE FROM sessions WHERE sessionId=:sessionId;'
    );
    $stmt->execute(['sessionId' => $data['sessionId']]);
}
?>