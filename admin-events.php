<?php
session_start();
include("connect.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    // User is not authenticated or not an admin, redirect to login
    header("Location: login.php");
    exit();
}

// Define $showHidden for hidden events toggle
$showHidden = isset($_GET['showHidden']) && $_GET['showHidden'] == 1;

// Hide Event
if (isset($_GET['hideEvent']) && isset($_GET['eventId'])) {
    $eventId = intval($_GET['eventId']);

    $stmt = $conn->prepare("UPDATE tbl_events SET isHidden = 1 WHERE evID = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin-events.php");
    exit();
}

// --- Unhide Event---
if (isset($_GET['unhideEvent']) && isset($_GET['eventId'])) {
    $eventId = intval($_GET['eventId']);

    $stmt = $conn->prepare("UPDATE tbl_events SET isHidden = 0 WHERE evID = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin-events.php?showHidden=1");
    exit();
}

// Fetch event statuses from database
$statusQuery = "SELECT evStatusID, evStatusDesc FROM tbl_eventstatus ORDER BY evStatusID";
$statusResult = $conn->query($statusQuery);
$statusOptions = [];
if ($statusResult && $statusResult->num_rows > 0) {
    while ($statusRow = $statusResult->fetch_assoc()) {
        $statusOptions[] = $statusRow;
    }
}

// Fetch existing events
$eventsQuery = "SELECT e.*, s.evStatusDesc 
                FROM tbl_events e 
                INNER JOIN tbl_eventstatus s ON e.evStatusID = s.evStatusID
                " . ($showHidden ? "" : "WHERE e.isHidden = 0") . "
                ORDER BY e.evDate DESC, e.evTime DESC
                ";
$eventsResult = $conn->query($eventsQuery);

// Handle form submission
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['evTitle'])) {
    $evTitle = trim($_POST['evTitle']);
    $evDesc = trim($_POST['evDesc']);
    $evDate = $_POST['evDate'];
    $evTime = $_POST['evTime'];
    $evVenue = trim($_POST['evVenue']);
    $evInstructor = trim($_POST['evInstructor']);
    $evLink = trim($_POST['evLink']);
    $evEvaluationLink = trim($_POST['evEvaluationLink']);
    $evStatusID = intval($_POST['evStatusID']);
    
    // Validate required fields
    if (empty($evTitle) || empty($evDesc) || empty($evDate) || empty($evTime) || 
        empty($evVenue) || empty($evInstructor) || empty($evLink) || empty($evEvaluationLink)) {
        $message = "Please fill in all required fields.";
        $messageType = "danger";
    } elseif (strlen($evDesc) > 500) {
        $message = "Description is too long. Maximum 500 characters allowed.";
        $messageType = "danger";
    } else {
        // Insert new event
        $stmt = $conn->prepare("INSERT INTO tbl_events (evTitle, evDesc, evDate, evTime, evVenue, evInstructor, evLink, evEvaluationLink, evStatusID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $evTitle, $evDesc, $evDate, $evTime, $evVenue, $evInstructor, $evLink, $evEvaluationLink, $evStatusID);
        
        if ($stmt->execute()) {
            $message = "Event added successfully!";
            $messageType = "success";
            // Refresh events list
            $eventsResult = $conn->query($eventsQuery);
            // Clear form by reloading page after a moment
            header("Location: admin-events.php?success=1");
            exit();
        } else {
            $message = "Error adding event: " . $conn->error;
            $messageType = "danger";
        }
        $stmt->close();
    }
}

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Event added successfully!";
    $messageType = "success";
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
</head>

<body>

    <nav class="navbar shadow-sm">
        <div class="container-fluid sticky-top">
            <div class="d-flex gap-4 me-4">
                <a class="navbar-brand d-flex ms-4" href="index.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250" height="auto" class="mt-1 mb-1">
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="bg-white rounded-4 p-4 shadow" style="border:3px solid #2f6fed; min-height:450px;">

            <h5 class="fw-bold mb-3">Events Management</h5>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php
            $showHidden = isset($_GET['showHidden']) && $_GET['showHidden'] == 1;
            ?>
            <a href="admin-events.php<?= $showHidden ? '' : '?showHidden=1' ?>" class="btn btn-sm btn-secondary mb-3">
                <?= $showHidden ? 'Hide Hidden Events' : 'Show Hidden Events' ?>
            </a>


            <!-- Events Table -->
            <h5 class="fw-bold mb-3">Existing Events</h5>
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
                            <th>Evaluation Link</th>
                            <th>Status</th>
                            <th>Actions</th>
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

                                    <td class="d-flex gap-2 justify-content-center">
                                        <a href="admin-eventattendance.php?eventId=<?= htmlspecialchars($event['evID']) ?>" 
                                            class="btn btn-sm btn-primary">View Attendance</a>

                                        <a href="admin-event-edit.php?eventId=<?= $event['evID'] ?>" 
                                            class="btn btn-sm btn-info"
                                            onclick="return confirm('Edit this event?')">
                                            Edit
                                        </a>

                                        <?php if ($event['isHidden'] == 0): ?>
                                            <a href="admin-events.php?hideEvent=1&eventId=<?= $event['evID'] ?>" 
                                                class="btn btn-sm btn-warning"
                                                onclick="return confirm('Hide this event? It will not be deleted.')">
                                                Hide
                                            </a>
                                        <?php else: ?>
                                            <a href="admin-events.php?unhideEvent=1&eventId=<?= $event['evID'] ?>" 
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Unhide this event?')">
                                                Unhide
                                            </a>
                                        <?php endif; ?>
                                    </td>


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

            <!-- Add New Event Form -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Add New Event</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="evTitle" class="form-label">Event Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="evTitle" name="evTitle" maxlength="100" required
                                    value="<?= isset($_POST['evTitle']) ? htmlspecialchars($_POST['evTitle']) : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="evStatusID" class="form-label">Event Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="evStatusID" name="evStatusID" required>
                                    <?php foreach ($statusOptions as $status): ?>
                                        <option value="<?= $status['evStatusID'] ?>"
                                            <?= (isset($_POST['evStatusID']) && $_POST['evStatusID'] == $status['evStatusID']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($status['evStatusDesc']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="evDesc" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="evDesc" name="evDesc" rows="3" maxlength="500" required
                                placeholder="Enter event description (max 500 characters)"><?= isset($_POST['evDesc']) ? htmlspecialchars($_POST['evDesc']) : '' ?></textarea>
                            <small class="text-muted">
                                <span id="descCharCount">0</span>/500 characters
                            </small>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="evDate" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="evDate" name="evDate" required
                                    value="<?= isset($_POST['evDate']) ? htmlspecialchars($_POST['evDate']) : '' ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="evTime" class="form-label">Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="evTime" name="evTime" required
                                    value="<?= isset($_POST['evTime']) ? htmlspecialchars($_POST['evTime']) : '' ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="evVenue" class="form-label">Venue <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="evVenue" name="evVenue" maxlength="100" required
                                    value="<?= isset($_POST['evVenue']) ? htmlspecialchars($_POST['evVenue']) : '' ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="evInstructor" class="form-label">Instructor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="evInstructor" name="evInstructor" maxlength="100" required
                                value="<?= isset($_POST['evInstructor']) ? htmlspecialchars($_POST['evInstructor']) : '' ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="evLink" class="form-label">Event Link <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" id="evLink" name="evLink" maxlength="200" required
                                    placeholder="https://..." value="<?= isset($_POST['evLink']) ? htmlspecialchars($_POST['evLink']) : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="evEvaluationLink" class="form-label">Evaluation Link <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" id="evEvaluationLink" name="evEvaluationLink" maxlength="200" required
                                    placeholder="https://..." value="<?= isset($_POST['evEvaluationLink']) ? htmlspecialchars($_POST['evEvaluationLink']) : '' ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Event</button>
                    </form>
                </div>
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
    
    <script>
        // Character counter for description
        const descTextarea = document.getElementById('evDesc');
        const descCharCount = document.getElementById('descCharCount');
        
        if (descTextarea && descCharCount) {
            descTextarea.addEventListener('input', function() {
                descCharCount.textContent = this.value.length;
                
                if (this.value.length > 450) {
                    descCharCount.style.color = '#dc3545';
                } else if (this.value.length > 400) {
                    descCharCount.style.color = '#ffc107';
                } else {
                    descCharCount.style.color = '#6c757d';
                }
            });
            
            descCharCount.textContent = descTextarea.value.length;
        }
        
        // Auto-dismiss success alert
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
