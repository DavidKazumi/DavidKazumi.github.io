<?php
header('Content-Type: application/json');

$webhookUrl = 'https://discord.com/api/webhooks/1366624223090573413/_IHPZDkyan3LUyLUNgI53ljLuVLlZUY6G19f53hZYtgKc0-JxEPO2iE2mUZRI6sVSi_w';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['server']) || empty($data['discordNick']) || empty($data['gameTitle'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

$payload = [
    'content' => "Novo pedido:\nServidor: {$data['server']}\nNick: {$data['discordNick']}\nJogo: {$data['gameTitle']}"
];

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao enviar para Discord']);
}
?>