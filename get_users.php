<?php
header('Content-Type: application/json');

$file = 'users.json';
if (file_exists($file)) {
    $data = file_get_contents($file);
    echo json_encode(['users' => json_decode($data, true)]);
} else {
    echo json_encode(['users' => []]);
}
?>
