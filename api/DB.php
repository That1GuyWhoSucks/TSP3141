<?php
function accessDB() {
    return new PDO('mysql:host=classdb.it.mtu.edu;dbname=zrlatuse;charset=utf8mb4', 'zrlatuse', 'Password1-');
}
?>