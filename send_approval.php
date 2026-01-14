<?php
require __DIR__ . '/vendor/autoload.php';
require 'connect.php';

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

// --- Google API Setup ---
$client = new Client();
$client->setApplicationName('ISC Membership Approval');
$client->setScopes(Gmail::GMAIL_SEND);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent'); // ensures refresh token
$client->setRedirectUri('http://localhost/ISC-Student-Organization-System/send_approval.php');

$tokenPath = 'token.json';

// Load token if exists
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
}

// Handle OAuth callback
if (isset($_GET['code'])) {
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($accessToken['error'])) {
        file_put_contents($tokenPath, json_encode($accessToken));
        $client->setAccessToken($accessToken);
    } else {
        die('Error fetching access token: ' . $accessToken['error']);
    }
    header('Location: send_approval.php');
    exit;
}

// Redirect to Google OAuth if no token or expired
if (!$client->getAccessToken() || $client->isAccessTokenExpired()) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}

// --- Get application ID from POST ---
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['apID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing application ID']);
    exit();
}

$apID = intval($input['apID']);

// Get application details
$stmt = $conn->prepare("SELECT * FROM tbl_applications WHERE apID = ?");
$stmt->bind_param("i", $apID);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
if (!$app) {
    echo json_encode(['success' => false, 'message' => 'Application not found']);
    exit();
}

// Prepare the email
$to = $app['apEmail'];
$subject = "ISC Membership Approved!";
$body = "Hello {$app['apFname']},\n\nCongratulations! Your ISC membership application has been approved.\nYou can now log in to your account.\n\n- ISC Team";

$rawMessageString = "From: ISC Membership <no-reply@isccommunity.com>\r\n";
$rawMessageString .= "To: $to\r\n";
$rawMessageString .= "Subject: $subject\r\n";
$rawMessageString .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
$rawMessageString .= $body;

// Encode the message
$encodedMessage = base64_encode($rawMessageString);
$encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);

$message = new Message();
$message->setRaw($encodedMessage);

// Send via Gmail API
$gmail = new Gmail($client);
try {
    $gmail->users_messages->send('me', $message);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
    exit();
}

// Insert into members automatically if not exists - copy all data from application
$check = $conn->prepare("SELECT mbID FROM tbl_members WHERE mbEmail = ?");
$check->bind_param("s", $app['apEmail']);
$check->execute();
if ($check->get_result()->num_rows === 0) {
    $insert = $conn->prepare("INSERT INTO tbl_members (mbFname, mbLname, mbMname, mbSuffix, mbSalutations, mbPronouns, mbBirthDate, mbDepartment, mbSection, mbInstitution, mbMobileNo, mbEmail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param(
        "ssssssssssss",
        $app['apFname'], 
        $app['apLname'], 
        $app['apMname'], 
        $app['apSuffix'],
        $app['apSalutations'],
        $app['apPronouns'],
        $app['apBirthDate'],
        $app['apDepartment'], 
        $app['apSection'], 
        $app['apInstitution'], 
        $app['apMobileNo'],
        $app['apEmail']
    );
    $insert->execute();
}

// Delete the application from tbl_applications after approval and data transfer
$delete = $conn->prepare("DELETE FROM tbl_applications WHERE apID = ?");
$delete->bind_param("i", $apID);
$delete->execute();

echo json_encode(['success' => true]);
