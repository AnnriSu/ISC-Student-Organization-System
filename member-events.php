<?php
session_start();
include("connect.php");

// Check if user is logged in and is a member
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'member') {
    // User is not authenticated or not a member, redirect to login
    header("Location: login.php");
    exit();
}

// Fetch existing events
$eventsQuery = "SELECT e.*, s.evStatusDesc 
                FROM tbl_events e 
                INNER JOIN tbl_eventstatus s ON e.evStatusID = s.evStatusID 
                ORDER BY e.evDate DESC, e.evTime DESC";
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

<body style="padding-bottom: 120px;">

    <nav class="navbar shadow-sm">
        <div class="container-fluid sticky-top">
            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250" height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4 mb-2">
        <div class="bg-white rounded-4 p-4 shadow" style="border:3px solid #2f6fed; min-height:450px;">

            <h5 class="fw-bold mb-3">Events</h5>

            <!-- Events Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Event Title</th>
                            <th>Description</th>
                            <th>Date & Time</th>
                            <th>Venue</th>
                            <th>Instructor</th>
                            <th>Event Link</th>
                            <th>Evaluation</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($eventsResult && $eventsResult->num_rows > 0): ?>
                            <?php while ($event = $eventsResult->fetch_assoc()): 
                                $dateTime = date('M d, Y', strtotime($event['evDate'])) . ' ' . date('g:i A', strtotime($event['evTime']));
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($event['evTitle']) ?></td>
                                    <td><?= htmlspecialchars(substr($event['evDesc'], 0, 50)) ?><?= strlen($event['evDesc']) > 50 ? '...' : '' ?></td>
                                    <td><?= htmlspecialchars($dateTime) ?></td>
                                    <td><?= htmlspecialchars($event['evVenue']) ?></td>
                                    <td><?= htmlspecialchars($event['evInstructor']) ?></td>
                                    <td><a href="<?= htmlspecialchars($event['evLink']) ?>" target="_blank">View Link</a></td>
                                    <td><a href="<?= htmlspecialchars($event['evEvaluationLink']) ?>" target="_blank">View Link</a></td>
                                    <td><?= htmlspecialchars($event['evStatusDesc']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No events found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- footer -->
    <footer class="footer text-center text-lg-start mt-4 fixed-bottom ">
        <div class="text-center p-3">
            © 2025 Iskonnovators Student Community PUPSTC, All Rights Reserved
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>

</html>
