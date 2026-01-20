<?php
session_start();

function respond(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        respond(['success' => false, 'message' => 'Метод запроса не поддерживается.'], 405);
    }
    header('Location: /index.php?status=error');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    if ($isAjax) {
        respond(['success' => false, 'message' => 'Ошибка безопасности.'], 400);
    }
    header('Location: /index.php?status=error');
    exit;
}

$guestName = trim((string)($_POST['guest_name'] ?? ''));
$contact = trim((string)($_POST['contact'] ?? ''));
$preferences = trim((string)($_POST['preferences'] ?? ''));

$guestName = strip_tags($guestName);
$contact = strip_tags($contact);
$preferences = strip_tags($preferences);

if ($guestName === '' || $contact === '') {
    if ($isAjax) {
        respond(['success' => false, 'message' => 'Заполните имя и контакт для связи.'], 422);
    }
    header('Location: /index.php?status=error');
    exit;
}

$guestName = mb_substr($guestName, 0, 60);
$contact = mb_substr($contact, 0, 80);
$preferences = mb_substr($preferences, 0, 400);

$entry = [
    'submitted_at' => date('c'),
    'guest_name' => $guestName,
    'contact' => $contact,
    'preferences' => $preferences,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
];

$storagePath = __DIR__ . '/storage/concierge_requests.jsonl';

$payload = json_encode($entry, JSON_UNESCAPED_UNICODE);
$written = false;

if ($payload !== false) {
    $handle = fopen($storagePath, 'ab');
    if ($handle) {
        if (flock($handle, LOCK_EX)) {
            fwrite($handle, $payload . PHP_EOL);
            flock($handle, LOCK_UN);
            $written = true;
        }
        fclose($handle);
    }
}

if (!$written) {
    if ($isAjax) {
        respond(['success' => false, 'message' => 'Не удалось сохранить запрос.'], 500);
    }
    header('Location: /index.php?status=error');
    exit;
}

if ($isAjax) {
    respond(['success' => true, 'message' => 'Запрос получен. Concierge свяжется с вами в течение 24 часов.']);
}

header('Location: /index.php?status=success');
