
<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = accessDB();
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare('SELECT name, authorId, status FROM users WHERE authorId in (SELECT authorId FROM roomAccess WHERE roomId=:roomId) AND authorId !=-1;');
    $stmt->execute(['roomId' => $data['roomId']]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}
?>