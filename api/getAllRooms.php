<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pdo = accessDB();
    $rooms = $pdo->query('SELECT * FROM rooms;')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rooms);
}
?>