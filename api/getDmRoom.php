
<?php
require 'DB.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = accessDB();
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare('
        SELECT roomId FROM (
            SELECT * FROM 
            (SELECT authorId as authorIdA, roomId from roomAccess) as A 
            NATURAL JOIN 
            (SELECT authorId as authorIdB, roomId FROM roomAccess) as B 
            WHERE authorIdA < authorIdB
            AND (authorIdA=:authorIdA OR authorIdB=:authorIdA)
            ORDER BY roomId) AS A
            WHERE roomId IN (
                SELECT roomId FROM 
                (SELECT authorId as authorIdA, roomId from roomAccess) as A 
                NATURAL JOIN 
                (SELECT authorId as authorIdB, roomId FROM roomAccess) as B 
                WHERE authorIdA < authorIdB
                AND (authorIdA=:authorIdA OR authorIdB=:authorIdA)
                AND (authorIdA=:authorIdB OR authorIdB=:authorIdB)
            )
            GROUP BY roomId
            HAVING count(roomId)=1
        ;
    '); // writing this made me want to cry
    $stmt->execute(['authorIdA' => $data['authorId'], 'authorIdB' => $data['targetAuthorId']]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rooms)) {
        echo json_encode(['success' => false]);
    } else {
        echo json_encode(['success' => true, 'roomId' => $rooms[0]['roomId']]);
    }
}
?>