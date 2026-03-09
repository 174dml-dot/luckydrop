<?php
$usersFile = 'users.json';
if (file_exists($usersFile)) {
    header('Content-Type: application/json');
    echo file_get_contents($usersFile);
} else {
    echo json_encode(['users' => []]);
}
?>
