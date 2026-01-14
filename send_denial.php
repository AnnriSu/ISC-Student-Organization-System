<?php
require __DIR__ . '/vendor/autoload.php';
require 'connect.php';

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['apID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing application ID']);
    exit();
}

$apID = (int)$data['apID'];

// Fetch applicant info
$stmt = $conn->prepare("SELECT apFname, apEmail FROM tbl_applications WHERE apID = ?");
$stmt->bind_param("i", $apID);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$app) {
    echo json_encode(['success' => false, 'message' => 'Application not found']);
    exit();
}

// Google Client
$client = new Client();
$client->setApplicationName('ISC Denial Mail');
$client->setScopes(Gmail::GMAIL_SEND);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');

$tokenPath = 'token.json';
if (!file_exists($tokenPath)) {
    echo json_encode(['success' => false, 'message' => 'Authorization required']);
    exit();
}

$client->setAccessToken(json_decode(file_get_contents($tokenPath), true));

if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}

$to = $app['apEmail'];
$from = 'projmail2025mail@gmail.com';
$subject = 'ISC Application Update';

$body =
"Hello {$app['apFname']},

Thank you for applying to the Iskonnovators Student Community.

After careful review, we regret to inform you that your application was not selected at this time.

We truly appreciate your interest and encourage you to apply again in the future.

Best regards,
ISC Team";

$raw =
"From: ISC Team <$from>\r\n" .
"To: $to\r\n" .
"Subject: $subject\r\n" .
"MIME-Version: 1.0\r\n" .
"Content-Type: text/plain; charset=UTF-8\r\n\r\n" .
$body;

$message = new Message();
$message->setRaw(rtrim(strtr(base64_encode($raw), '+/', '-_')));

try {
    $gmail = new Gmail($client);
    $gmail->users_messages->send('me', $message);

    // DELETE APPLICATION AFTER EMAIL
    $del = $conn->prepare("DELETE FROM tbl_applications WHERE apID = ?");
    $del->bind_param("i", $apID);
    $del->execute();
    $del->close();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}