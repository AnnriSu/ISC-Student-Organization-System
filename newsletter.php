<?php
include("connect.php");

$message = "";

// Only run when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['email'])) {

    $email = trim($_POST['email']);

    // STEP 1: Lookup member by email
    $stmt = $conn->prepare("SELECT mbID FROM tbl_members WHERE mbEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Email not found in members
    if ($result->num_rows === 0) {
        $message = "The email you entered does not match any ISC member.";
    } 
    // Email exists
    else {
        $member = $result->fetch_assoc();
        $mbID = $member['mbID'];

        // STEP 2: Check if already subscribed
        $check = $conn->prepare("SELECT nlID FROM tbl_newsletter WHERE mbID = ?");
        $check->bind_param("i", $mbID);
        $check->execute();
        $exists = $check->get_result();

        if ($exists->num_rows > 0) {
            $message = "This member is already subscribed to the newsletter.";
        } 
        // STEP 3: Insert subscription
        else {
            $insert = $conn->prepare("INSERT INTO tbl_newsletter (nlEmail, mbID) VALUES (?, ?)");
            $insert->bind_param("si", $email, $mbID);

            if ($insert->execute()) {
                $message = "Newsletter subscription successful! 🎉";
            } else {
                $message = "Subscription failed. Please try again.";
            }
        }
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
        <div class="d-flex gap-4 me-4">
            <a class="navbar-brand d-flex ms-4" href="index.php">
                <img src="assets/img/isc_brand_bold.png" alt="Logo" width="250">
            </a>
        </div>
    </div>
</nav>

<div class="container form-container mt-5 mb-5 p-4 shadow-sm rounded-3" style="max-width: 500px;">

    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded icon-box">
        <img src="assets/img/ISC brand logo.png" alt="ISC Logo" width="150">
    </div>

    <hr>

    <h3 class="fw-bold mb-3 text-center">
        Subscribe to our Newsletter
    </h3>

    <hr>

    <p class="text-center small">
        Receive updates, announcements, and exclusive content.
        Our newsletter is sent once a week, every Monday.
    </p>

    <!-- Feedback Message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Newsletter Form -->
    <form method="POST">
        <div class="mb-3">
            <input type="email"
                   class="form-control"
                   name="email"
                   required
                   placeholder="Enter your registered ISC email">
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Subscribe
        </button>
    </form>

    <hr>

    <p class="text-center small">We promise not to spam you!</p>

    <hr>
</div>

<?php include 'shared/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
