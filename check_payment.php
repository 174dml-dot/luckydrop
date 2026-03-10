<?php
header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? '';

// Заглушка для демонстрации
$file = 'payments.json';
$payments = [];
if (file_exists($file)) {
    $payments = json_decode(file_get_contents($file), true);
}

if (isset($payments[$order_id])) {
    echo json_encode(['status' => $payments[$order_id]['status']]);
} else {
    echo json_encode(['status' => 'pending']);
}
?>
