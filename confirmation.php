<?php
session_start();

// Check if mobile number is sent from login.php
$recipient = $_GET['mobile'] ?? ($_SESSION['mobileNumber'] ?? "+639922623280"); // fallback
$userType = $_GET['type'] ?? ($_SESSION['userType'] ?? "member"); // "member" or "admin"

// Ensure session has email and userType from login.php
if (!isset($_SESSION['email']) || !isset($_SESSION['userType'])) {
    // If session is missing, redirect back to login
    header("Location: login.php");
    exit();
}

// Store userType in session to use after redirect (in case it was passed via GET)
$_SESSION['userType'] = $userType;

$gateway_url = "http://192.168.18.12:8080";
$username = "ISCSystem";
$password = "ISC_2025";

// Check if user submitted OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $inputOtp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $inputOtp == $_SESSION['otp']) {
        // OTP is correct, set logged-in flag and redirect based on user type
        unset($_SESSION['otp']); // remove OTP after verification
        $_SESSION['logged_in'] = true; // Set authentication flag

        if ($_SESSION['userType'] === "admin") {
            header("Location: adminhomepage.php");
            exit();
        } else {
            header("Location: homepage-member.php");
            exit();
        }
    } else {
        $showErrorCard = true;
    }
} else {
    // Generate OTP and send SMS
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp; // store OTP in session

    $message = "Your OTP is $otp. Do not share this code with anyone.";
    $url = rtrim($gateway_url, '/') . '/messages';

    $payload = [
        "phoneNumbers" => [$recipient],
        "message" => $message
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode("$username:$password")
            ],
            'content' => json_encode($payload)
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context); // suppress errors

    // Show debug info
    // echo "<h3>OTP Sent</h3>";
    // echo "<p>Recipient: <strong>$recipient</strong></p>";
    // echo "<p>Generated OTP: <strong>$otp</strong></p>";
    // echo "<h4>API Response:</h4>";
    // echo "<pre>" . htmlspecialchars($response ?: "No response from SMS Gateway.") . "</pre>";


}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iskonnovators Student Community</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="assets/style.css" rel="stylesheet">
    <style>
        /* Error Modal Styles */
        .error-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .error-modal.show {
            display: flex;
        }

        .error-card {
            background: white;
            border-radius: 15px;
            padding: 48px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            max-width: 520px;
            width: 90%;
            text-align: center;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-card h2 {
            color: #dc3545;
            font-weight: 800;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .error-card p {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: nowrap;
        }

        .btn-error-ok {
            background-color: #3769b2;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
            flex: 1;
            min-width: 150px;
        }

        .btn-error-ok:hover {
            background-color: #2a50a0;
        }

        .btn-error-resend {
            background-color: #84152c;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
            flex: 1;
            min-width: 180px;
        }

        .btn-error-resend:hover {
            background-color: #6c1122;
        }
    </style>
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

    <div class="container form-container mt-5 mb-5 p-4 shadow-sm rounded-3 " style="max-width: 500px;">

        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded icon-box">
            <img src="assets\img\ISC brand logo.png" alt="Application Received" width="150" height="auto">
        </div>

        <hr>

        <h3 class="fw-bold mb-3 d-flex align-items-center justify-content-center text-center">Enter One Time Password
        </h3>
        <hr>

        <p class="text-center small">
            An OTP has been sent to your registered phone number:
            <strong><?= htmlspecialchars($recipient) ?></strong><br>
            Logging in as: <strong><?= htmlspecialchars(ucfirst($userType)) ?></strong><br>
            Please enter it below to proceed.
        </p>

        <?php
        // Show debug output for development
        if (isset($debugOutput)) {
            echo '<div class="alert alert-info">' . $debugOutput . '</div>';
        }
        ?>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" id="otp" name="otp" required placeholder="Enter OTP here">
            </div>

            <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
        </form>
        <hr>
        <p class="text-center">
            If you did not receive the text message or if the OTP has expired,
            <a href="?resend=1&mobile=<?= rawurlencode($recipient) ?>&type=<?= rawurlencode($userType) ?>">click here to
                request a new OTP.</a>

        </p>
        <hr>

    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="error-modal <?php echo (isset($showErrorCard) && $showErrorCard) ? 'show' : ''; ?>">
        <div class="error-card">
            <h2>Invalid OTP</h2>
            <p>Invalid OTP. Please try again.</p>
            <div class="button-group">
                <button id="okayBtn" class="btn-error-ok">Okay</button>
                <button id="resendBtn" class="btn-error-resend">Resend OTP</button>
            </div>
        </div>
    </div>

    <?php include("shared/footer.php"); ?>


    <script>
        // Handle okay button click - just hide modal
        document.getElementById('okayBtn')?.addEventListener('click', () => {
            document.getElementById('errorModal').classList.remove('show');
        });

        // Handle resend button click - redirect to resend OTP
        document.getElementById('resendBtn')?.addEventListener('click', () => {
            window.location.href = '?resend=1&mobile=<?= rawurlencode($recipient) ?>&type=<?= rawurlencode($userType) ?>';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>