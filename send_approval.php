<?php
require __DIR__ . '/vendor/autoload.php';
require 'connect.php';

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

header('Content-Type: application/json');

// --- Google API Setup ---
$client = new Client();
$client->setApplicationName('ISC Membership Approval');
$client->setScopes(Gmail::GMAIL_SEND);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

$tokenPath = 'token.json';

// Load token if exists
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        } else {
            echo json_encode(['success' => false, 'message' => 'Authorization required. Please reauthorize Gmail API.']);
            exit();
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Authorization required. Please authorize Gmail API first.']);
    exit();
}

// --- Get application ID from POST ---
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['apID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing application ID']);
    exit();
}
$apID = intval($input['apID']);

// --- Fetch application details ---
$stmt = $conn->prepare("SELECT * FROM tbl_applications WHERE apID = ?");
$stmt->bind_param("i", $apID);
$stmt->execute();
$result = $stmt->get_result();
$app = $result->fetch_assoc();
$stmt->close();

if (!$app) {
    echo json_encode(['success' => false, 'message' => 'Application not found']);
    exit();
}

// --- Prepare Email ---
$to = $app['apEmail'];
$subject = "ISC Membership Approved!";
$body = "Hello {$app['apFname']},\n\nCongratulations! Your ISC membership application has been approved.\nYou can now log in to your account.\n\n- ISC Team";

$from = 'projmail2025mail@gmail.com';

$rawMessage = "From: ISC Team <$from>\r\n";
$rawMessage .= "To: $to\r\n";
$rawMessage .= "Subject: $subject\r\n";
$rawMessage .= "MIME-Version: 1.0\r\n";
$rawMessage .= "Content-Type: text/plain; charset=UTF-8\r\n";
$rawMessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$rawMessage .= $body;

$encodedMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'));
$message = new Message();
$message->setRaw($encodedMessage);

// --- Send Email ---
try {
    $gmail = new Gmail($client);
    $gmail->users_messages->send('me', $message);
} catch (\Google\Service\Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Gmail API Error: ' . $e->getMessage()]);
    exit();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error sending email: ' . $e->getMessage()]);
    exit();
}

// --- Insert into tbl_members if not exists ---
$check = $conn->prepare("SELECT mbID FROM tbl_members WHERE mbEmail = ?");
$check->bind_param("s", $app['apEmail']);
$check->execute();
$check->store_result(); // Works without mysqlnd
if ($check->num_rows === 0) {
    $insert = $conn->prepare("
        INSERT INTO tbl_members 
        (mbFname, mbLname, mbMname, mbSuffix, mbSalutations, mbPronouns, mbBirthDate, mbDepartment, mbSection, mbInstitution, mbMobileNo, mbEmail) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
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
    $insert->close();
}
$check->close();

// --- Delete the application ---
$delete = $conn->prepare("DELETE FROM tbl_applications WHERE apID = ?");
$delete->bind_param("i", $apID);
$delete->execute();
$delete->close();

echo json_encode(['success' => true, 'message' => 'Membership approved, email sent, and application moved to members!']);
