<?php
require __DIR__ . '/vendor/autoload.php';
require 'connect.php'; 

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

// --- Google API Setup ---
$client = new Client();
$client->setApplicationName('ISC Announcements');
$client->setScopes(Gmail::GMAIL_SEND);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent'); // ensures refresh token
$client->setRedirectUri('http://localhost/ISC-Student-Organization-System/announcements.php');


$tokenPath = 'token.json';

// --- Load token if exists ---
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
}

// --- Handle OAuth callback (first-time authorization) ---
if (isset($_GET['code'])) {
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($accessToken['error'])) {
        file_put_contents($tokenPath, json_encode($accessToken));
        $client->setAccessToken($accessToken);
    } else {
        die('Error fetching access token: ' . $accessToken['error']);
    }
    // Reload page without the ?code= in URL
    header('Location: announcements.php');
    exit;
}

// --- Redirect to Google OAuth if no token or expired ---
if (!$client->getAccessToken() || $client->isAccessTokenExpired()) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}

// --- Form submission handling ---
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['message'] ?? '';

    if (!empty($subject) && !empty($body)) {
        // Fetch emails from newsletter subscribers
        $emails = [];
        $sql = "SELECT nlEmail FROM tbl_newsletter";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $emails[] = $row['nlEmail'];
            }
        } else {
            $error = "No newsletter subscribers found.";
        }

        if (!empty($emails)) {
            $gmail = new Gmail($client);
            $sentCount = 0;

            foreach ($emails as $to) {
                $rawMessageString = "From: ISC Announcements <no-reply@yourdomain.com>\r\n";
                $rawMessageString = "To: $to\r\n";
                $rawMessageString .= "Subject: $subject\r\n";
                $rawMessageString .= "Reply-To: no-reply@yourdomain.com\r\n";
                $rawMessageString .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
                $rawMessageString .= $body;

                $encodedMessage = base64_encode($rawMessageString);
                $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);

                $message = new Message();
                $message->setRaw($encodedMessage);

                try {
                    $gmail->users_messages->send('me', $message);
                    $sentCount++;
                } catch (Exception $e) {
                    $error .= "Failed to send to $to: " . $e->getMessage() . "<br>";
                }
            }

            if ($sentCount > 0) {
                $success = "Announcements sent to $sentCount subscriber(s)!";
            }
        }
    } else {
        $error = "Subject and message cannot be empty.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ISC Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar shadow-sm">
        <div class="container-fluid sticky-top">
            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250" height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>

    <div class="container form-container mt-3 mb-2 p-2 shadow-sm rounded-3" style="max-width: 500px;">
        <div class="row mt-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Announcements</h2>
                <hr class="mx-auto" style="width: 100px; height: 3px; background-color: #000;">
            </div>
        </div>
    </div>

    <div class="container mt-2">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <!-- subject and message -->
    <div class="container mt-3 mb-5 p-4 shadow rounded-3" style="max-width: 800px;">
        <form method="POST" action="announcements.php">
            <div class="mb-3">
                <label for="subject" class="form-label">Subject<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject"
                    placeholder="Enter email subject here (e.g., event name, event reminder)..." required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message<span class="text-danger">*</span></label>
                <textarea class="form-control" id="message" name="message" rows="6"
                    placeholder="Enter message to be sent via email here (e.g., schedule updates, reminders)..."
                    required></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-isc">Send Announcement</button>
            </div>
        </form>
    </div>

    <?php include 'shared/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>