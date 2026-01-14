<?php
session_start();
include("connect.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    // User is not authenticated or not an admin, redirect to login
    header("Location: login.php");
    exit();
}

// Get admin's name from database
$adminName = "Admin"; // Default fallback
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT adFname, adLname, adMname, adSuffix FROM tbl_admin WHERE adEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Build full name: First Name + Middle Name (if exists) + Last Name + Suffix (if exists)
        $adminName = trim($row['adFname']);
        if (!empty($row['adMname'])) {
            $adminName .= " " . trim($row['adMname']);
        }
        $adminName .= " " . trim($row['adLname']);
        if (!empty($row['adSuffix'])) {
            $adminName .= " " . trim($row['adSuffix']);
        }
    }
    $stmt->close();
}

// Fetch events from database
$eventsQuery = "SELECT e.*, s.evStatusDesc 
                FROM tbl_events e 
                INNER JOIN tbl_eventstatus s ON e.evStatusID = s.evStatusID 
                ORDER BY e.evDate DESC, e.evTime DESC
                LIMIT 10";
$eventsResult = $conn->query($eventsQuery);
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
</head>

<body style="padding-bottom: 80px;">

    <nav class="navbar shadow-sm">

        <?php include("shared/navbar.php"); ?>


        <div
            class="pe-sm-3 d-flex flex-column flex-sm-row gap-2 gap-lg-4 align-items-center justify-content-center justify-content-md-end ms-md-auto">
            <a class="navbar-brand d-flex" href="logout.php">
                <img src="assets\img\Log out.svg" alt="Logout" width="30" height="auto" class="mt-1 mb-1">
            </a>
        </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row g-4">

            <div class="col-md-3 col-lg-2">
                <div class="bg-primary text-white rounded-4 p-3 shadow" style="min-height:450px;">

                    <h5 class="text-center fw-bold mb-4">Admin</h5>

                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-white text-primary d-flex
                                align-items-center justify-content-center mx-auto mb-2"
                            style="width:80px; height:80px; font-size:40px;">

                        </div>
                        <medium class="d-block text-white fw-medium">
                            <?= htmlspecialchars($adminName) ?>
                        </medium>

                        <hr class="border border-white opacity-100 my-1">


                    </div>

                    <div class="d-grid gap-2">
                        <a href="applications.php" class="btn btn-sidebar">Applications</a>
                        <a href="admin-events.php" class="btn btn-sidebar">Events</a>
                        <a href="announcements.php" class="btn btn-sidebar">Announcements</a>
                        <a href="adminprofile.php" class="btn btn-sidebar">Edit Profile</a>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-lg-10">
                <div class="bg-white rounded-4 p-4 shadow" style="border:3px solid #668cd8ff; min-height:450px;">

                    <h5 class="fw-bold mb-0">Home</h5>
                    <small class="text-muted">Dashboard</small>

                   <!-- Events Display -->
                    <?php if ($eventsResult && $eventsResult->num_rows > 0): ?>
                        <?php while ($event = $eventsResult->fetch_assoc()):
                            $dateTime = date('M d, Y', strtotime($event['evDate'])) . ' ' . date('g:i A', strtotime($event['evTime']));

                            $status = strtolower(trim($event['evStatusDesc']));
                            $statusClass = match ($status) {
                                'upcoming' => 'bg-primary',
                                'ongoing' => 'bg-warning',
                                'completed' => 'bg-success',
                                default => 'bg-secondary',
                            };
                        ?>
                            <div class="border rounded-3 p-3 mt-4 d-flex gap-3 align-items-start">

                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width:120px; height:120px; flex-shrink: 0;">
                                    <img src="assets/img/ISC brand logo.png" alt="ISC Logo"
                                        style="width: 100%; height: 100%; object-fit: contain; padding: 10px;">
                                </div>

                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($event['evTitle']) ?></h6>
                                    <small
                                        class="d-block text-muted"><?= htmlspecialchars(substr($event['evDesc'], 0, 100)) ?><?= strlen($event['evDesc']) > 100 ? '...' : '' ?></small>
                                    <small class="d-block"><strong>Date & Time:</strong>
                                        <?= htmlspecialchars($dateTime) ?></small>
                                    <small class="d-block"><strong>Venue:</strong>
                                        <?= htmlspecialchars($event['evVenue']) ?></small>
                                    <small class="d-block"><strong>Instructor:</strong>
                                        <?= htmlspecialchars($event['evInstructor']) ?></small>
                                    <small class="d-block"><strong>Status:</strong><span
                                            class="badge <?= $statusClass ?>"><?= htmlspecialchars($event['evStatusDesc']) ?></span>
                                    </small>

                                    <div class="mt-2 d-flex gap-2">
                                        <?php if (!empty($event['evLink'])): ?>
                                            <a href="<?= htmlspecialchars($event['evLink']) ?>" target="_blank"
                                                class="btn btn-primary btn-sm">Event Link</a>
                                        <?php endif; ?>
                                        <?php if (!empty($event['evEvaluationLink'])): ?>
                                            <a href="<?= htmlspecialchars($event['evEvaluationLink']) ?>" target="_blank"
                                                class="btn btn-outline-primary btn-sm">Evaluation</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="border rounded-3 p-4 mt-4 text-center">
                            <p class="text-muted mb-0">No events available at the moment.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>


            <?php include("shared/footer.php"); ?>



            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
                crossorigin="anonymous"></script>
</body>

</html>