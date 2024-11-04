<?php

require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdo = accessDB();
    $stmt = $pdo->prepare(
        'SELECT authorId, name FROM sessions NATURAL JOIN users WHERE sessionId=:sessionId'
    );
    $stmt->execute(['sessionId' => $data['sessionId']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        echo json_encode(['success' => false]);
    } else {
        echo json_encode(['success' => true, 'id' => $result[0]["authorId"], 'name' => $result[0]['name']]);
    }
}
?>