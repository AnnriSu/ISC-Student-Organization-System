<?php
session_start();

// Check if mobile number is sent from login.php
$recipient = $_GET['mobile'] ?? "+639948669327"; // fallback
$userType  = $_GET['type'] ?? "member"; // "member" or "admin"

// Store userType in session to use after redirect
$_SESSION['userType'] = $userType;

$gateway_url = "http://192.168.18.12:8080";
$username    = "ISCSystem";
$password    = "ISC_2025";

// Check if user submitted OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $inputOtp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $inputOtp == $_SESSION['otp']) {
        // OTP is correct, redirect based on user type
        unset($_SESSION['otp']); // remove OTP after verification

        if ($_SESSION['userType'] === "admin") {
            header("Location: adminhomepage.php");
            exit();
        } else {
            header("Location: homepage-member.php");
            exit();
        }
    } else {
        echo "<h3>Invalid OTP. Please try again.</h3>";
    }
} else {
    // Generate OTP and send SMS
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp; // store OTP in session

    $message = "Your OTP is $otp. Do not share this code with anyone.";
    $url = rtrim($gateway_url, '/') . '/messages';

    $payload = [
        "phoneNumbers" => [$recipient],
        "message"      => $message
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode("$username:$password")
            ],
            'content' => json_encode($payload)
        ]
    ];

    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context); // suppress errors
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iskonnovators Student Community</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar shadow-sm">
        <div class="container-fluid sticky-top">
            <a class="navbar-brand d-flex ms-4" href="index.php">
                <img src="assets/img/isc_brand_bold.png" alt="Logo" width="250" class="mt-1 mb-1">
            </a>
        </div>
    </nav>

    <div class="container form-container mt-5 mb-5 p-4 shadow-sm rounded-3" style="max-width: 500px;">

        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded icon-box">
            <img src="assets/img/ISC brand logo.png" alt="Application Received" width="150">
        </div>

        <hr>

        <h3 class="fw-bold mb-3 text-center">Give us your feedback!</h3>
        <hr>
        <p class="text-center small">
            We'd love to hear from you! Your thoughts, suggestions, and concerns help us improve and serve you better.<br>
           
        </p>

        <form method="SUBMIT">
            <div class="mb-3">
              <textarea class="form-control" id="feedback" name="feedback" rows="3" required placeholder="Please share your feedback below."></textarea>

            </div>

            <button type="Submit Feedback" class="btn btn-primary w-100">Submit Feedback</button>
        </form>


    </div>

    <footer class="footer text-center mt-4">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
