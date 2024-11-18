<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = accessDB();
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare(
        'SELECT roomId, name, role FROM rooms NATURAL JOIN roomAccess WHERE authorId=:authorId;'
    );
    $stmt->execute(['authorId' => $data['authorId']]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rooms);
}
?>