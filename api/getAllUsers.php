<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pdo = accessDB();
    $users = $pdo->query('SELECT * FROM users WHERE authorId > 0;')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}
?>