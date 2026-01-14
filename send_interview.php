<?php
require __DIR__ . '/vendor/autoload.php';
require 'connect.php';

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

header('Content-Type: application/json');

// --- Get application ID from POST ---
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['apID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing application ID']);
    exit();
}

$apID = intval($input['apID']);

// --- Fetch application details ---
$stmt = $conn->prepare("
    SELECT a.*, s.apStatusDesc
    FROM tbl_applications a
    JOIN tbl_applicationstatus s ON a.apStatusID = s.apStatusID
    WHERE a.apID = ?
");
$stmt->bind_param("i", $apID);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();

if (!$app) {
    echo json_encode(['success' => false, 'message' => 'Application not found']);
    exit();
}

// Only allow sending if current status is "For Interview" and email not sent
if ($app['apStatusDesc'] !== 'For Interview' || $app['interviewSent'] == 1) {
    echo json_encode(['success' => false, 'message' => 'Interview email cannot be sent for this application.']);
    exit();
}

// --- Gmail API Setup ---
$client = new Client();
$client->setApplicationName('ISC Interview Invitation');
$client->setScopes(Gmail::GMAIL_SEND);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');

// Load token
$tokenPath = 'token.json';
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
    if ($client->isAccessTokenExpired()) {
        $refreshToken = $client->getRefreshToken();
        if ($refreshToken) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        } else {
            echo json_encode(['success' => false, 'message' => 'Authorization required']);
            exit();
        }
    }
}

// --- Prepare Email ---
$to = $app['apEmail'];
$from = 'projmail2025mail@gmail.com';
$subject = "Interview Invitation";
$body = "Hello {$app['apFname']} {$app['apLname']},\n\nYou are invited to the Membership Interview.\nCheck the Group Chat for scheduling details.\n\n- ISC Team";

$rawMessageString = "From: ISC Team <$from>\r\n";
$rawMessageString .= "To: $to\r\n";
$rawMessageString .= "Subject: $subject\r\n";
$rawMessageString .= "MIME-Version: 1.0\r\n";
$rawMessageString .= "Content-Type: text/plain; charset=UTF-8\r\n";
$rawMessageString .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$rawMessageString .= $body;

$encodedMessage = rtrim(strtr(base64_encode($rawMessageString), '+/', '-_'));

$message = new Message();
$message->setRaw($encodedMessage);

// --- Send Email via Gmail API ---
$gmail = new Gmail($client);
try {
    $gmail->users_messages->send('me', $message);

    // Update status: For Interview → Pending and mark email sent
    // Assuming Pending has apStatusID = 2 (adjust if different)
    $pendingStatusID = 2;
    $updateStmt = $conn->prepare("UPDATE tbl_applications SET apStatusID = ?, interviewSent = 1 WHERE apID = ?");
    $updateStmt->bind_param("ii", $pendingStatusID, $apID);
    $updateStmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Interview email sent successfully!',
        'newStatusID' => $pendingStatusID
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
}
