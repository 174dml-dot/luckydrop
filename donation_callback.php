<?php
// Твой секретный ключ из DonationAlerts (сейчас получим)
$secretKey = '';

// Получаем данные от DonationAlerts
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    exit;
}

// Проверяем подпись (безопасность)
$sign = $data['sign'];
unset($data['sign']);
ksort($data);
$str = implode('|', $data) . '|' . $secretKey;
$expectedSign = md5($str);

if ($sign !== $expectedSign) {
    http_response_code(403);
    exit;
}

// Донат подтверждён
$amount = (float) $data['amount']; // сумма в рублях
$message = $data['message'] ?? ''; // сообщение
$username = '';

// Ищем имя пользователя в сообщении (формат: username: admin)
if (preg_match('/username:\s*(\w+)/i', $message, $matches)) {
    $username = $matches[1];
}

if ($username) {
    // Загружаем пользователей из файла
    $usersFile = 'users.json';
    if (file_exists($usersFile)) {
        $data = json_decode(file_get_contents($usersFile), true);
        $users = $data['users'] ?? [];
        
        $updated = false;
        foreach ($users as &$user) {
            if ($user['username'] === $username) {
                // Начисляем голду (1 рубль = 1 G)
                $user['balance'] = ($user['balance'] ?? 0) + $amount;
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            file_put_contents($usersFile, json_encode(['users' => $users]));
        }
    }
}

// Отвечаем DonationAlerts, что всё ок
http_response_code(200);
echo 'OK';
?>
