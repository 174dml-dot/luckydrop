<?php
$crystalPayId = 'luckydrop';

$orderId = $_GET['order_id'] ?? '';

if (!$orderId) {
    echo json_encode(['status' => 'error']);
    exit;
}

$url = "https://api.crystalpay.io/v1/invoice/info/";
$data = [
    's' => $crystalPayId,
    'n' => $orderId
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$response = json_decode($result, true);

if ($response && isset($response['state']) && $response['state'] == 1) {
    echo json_encode(['status' => 'paid']);
} else {
    echo json_encode(['status' => 'pending']);
}
?>
