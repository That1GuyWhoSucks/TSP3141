<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = accessDB();
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data['autoAdd']) {
        $stmt = $pdo->prepare(
            'INSERT INTO rooms (name, autoAdd) VALUES (:name, 1);
            INSERT INTO roomAccess (SELECT authorId,LAST_INSERT_ID(),0 FROM users);
            INSERT INTO adminActions (roomId, id, actionType, actionTarget) SELECT LAST_INSERT_ID(), authorId, "ADD", "USER" FROM users;
            UPDATE roomAccess SET role=1 WHERE authorId=:authorId AND roomId=LAST_INSERT_ID();'
        );
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO rooms (name, autoAdd) VALUES (:name, 0);
            INSERT INTO roomAccess (authorId, roomId, role) VALUES (:authorId, LAST_INSERT_ID(), 1);'
        );
    }
    $stmt->execute(['name' => $data['name'], 'authorId' => $data['authorId']]);
    echo json_encode(['success' => true, 'roomId' => (int)$pdo->lastInsertId()]);
}
?>