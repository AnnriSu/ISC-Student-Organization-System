<?php
session_start();
include("connect.php");

// Check if user is logged in and is a member
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'member') {
    // User is not authenticated or not a member, redirect to login
    header("Location: login.php");
    exit();
}

// Get member's ID, name, and mobile number from database
$memberID = null;
$memberMobileNo = null;
$memberName = "Member"; // Default fallback
$feedbackMessage = "";
$feedbackType = ""; // 'success' or 'danger'

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    // Retrieve mbID, mbMobileNo, and name fields from tbl_members
    $stmt = $conn->prepare("SELECT mbID, mbMobileNo, mbFname, mbLname, mbMname, mbSuffix FROM tbl_members WHERE mbEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $memberID = $row['mbID'];
        // Use the exact mobile number format as stored in tbl_members (required for foreign key)
        // Trim to remove any whitespace but keep exact format
        $memberMobileNo = trim($row['mbMobileNo']);
        
        // Build full name: First Name + Middle Name (if exists) + Last Name + Suffix (if exists)
        $memberName = trim($row['mbFname']);
        if (!empty($row['mbMname'])) {
            $memberName .= " " . trim($row['mbMname']);
        }
        $memberName .= " " . trim($row['mbLname']);
        if (!empty($row['mbSuffix'])) {
            $memberName .= " " . trim($row['mbSuffix']);
        }
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['feedback'])) {
    $feedbackContent = trim($_POST['feedback']);
    
    // Validate feedback content
    if (empty($feedbackContent)) {
        $feedbackMessage = "Please enter your feedback before submitting.";
        $feedbackType = "danger";
    } elseif (strlen($feedbackContent) > 500) {
        $feedbackMessage = "Feedback is too long. Maximum 500 characters allowed.";
        $feedbackType = "danger";
    } elseif ($memberID === null) {
        $feedbackMessage = "Error: Member ID not found. Please try logging in again.";
        $feedbackType = "danger";
    } elseif ($memberMobileNo === null) {
        $feedbackMessage = "Error: Member mobile number not found. Please try logging in again.";
        $feedbackType = "danger";
    } else {
        // Verify and get the exact mobile number format from database to match foreign key
        $verifyStmt = $conn->prepare("SELECT mbMobileNo FROM tbl_members WHERE mbID = ?");
        $verifyStmt->bind_param("i", $memberID);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();
        
        if ($verifyResult && $verifyResult->num_rows === 1) {
            $verifyRow = $verifyResult->fetch_assoc();
            // Use the exact mobile number format as stored in tbl_members (required for foreign key)
            $memberMobileNo = trim($verifyRow['mbMobileNo']);
        }
        $verifyStmt->close();
        
        // Insert feedback into database with member ID (mbID) and mobile number (mbMobileNo)
        $stmt = $conn->prepare("INSERT INTO tbl_feedback (fbContent, mbID, mbMobileNo) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $feedbackContent, $memberID, $memberMobileNo);
        
        if ($stmt->execute()) {
            $feedbackMessage = "Thank you! Your feedback has been submitted successfully.";
            $feedbackType = "success";
            // Clear the form field by resetting POST data (will show empty on page reload)
            $_POST['feedback'] = "";
        } else {
            $errorMsg = $conn->error;
            // If the error is about mbMobileNo column not existing, try without it
            if (strpos($errorMsg, 'mbMobileNo') !== false && strpos($errorMsg, "Unknown column") !== false) {
                // Retry without mbMobileNo if column doesn't exist
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO tbl_feedback (fbContent, mbID) VALUES (?, ?)");
                $stmt->bind_param("si", $feedbackContent, $memberID);
                
                if ($stmt->execute()) {
                    $feedbackMessage = "Thank you! Your feedback has been submitted successfully.";
                    $feedbackType = "success";
                    $_POST['feedback'] = "";
                } else {
                    $feedbackMessage = "Error submitting feedback: " . $conn->error . ". Please try again later.";
                    $feedbackType = "danger";
                }
            } else {
                $feedbackMessage = "Error submitting feedback: " . $errorMsg . ". Please try again later.";
                $feedbackType = "danger";
            }
        }
        $stmt->close();
    }
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

        <?php if (!empty($feedbackMessage)): ?>
            <div class="alert alert-<?= $feedbackType ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($feedbackMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
              <textarea class="form-control" id="feedback" name="feedback" rows="5" maxlength="500" required placeholder="Please share your feedback below (max 500 characters)."><?= isset($_POST['feedback']) && $feedbackType !== 'success' ? htmlspecialchars($_POST['feedback']) : '' ?></textarea>
              <small class="text-muted">
                  <span id="charCount">0</span>/500 characters
              </small>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
        </form>


    </div>

    <footer class="footer text-center mt-4">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Character counter for feedback textarea
        const feedbackTextarea = document.getElementById('feedback');
        const charCount = document.getElementById('charCount');
        
        if (feedbackTextarea && charCount) {
            // Update character count on input
            feedbackTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                
                // Change color if approaching limit
                if (this.value.length > 450) {
                    charCount.style.color = '#dc3545'; // Red
                } else if (this.value.length > 400) {
                    charCount.style.color = '#ffc107'; // Yellow
                } else {
                    charCount.style.color = '#6c757d'; // Gray
                }
            });
            
            // Set initial count
            charCount.textContent = feedbackTextarea.value.length;
        }
        
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(successAlert);
                bsAlert.close();
            }, 5000);
        }
    </script>
</body>

</html>
