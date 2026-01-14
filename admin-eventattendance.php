<?php
session_start();
include("connect.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    // User is not authenticated or not an admin, redirect to login
    header("Location: login.php");
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
                ORDER BY e.evDate DESC, e.evTime DESC";
$eventsResult = $conn->query($eventsQuery);

// Fetch first event title for page header
$eventId = isset($_GET['eventId']) ? intval($_GET['eventId']) : null;

if ($eventId) {
    // Fetch specific event title from eventId parameter
    $firstEventStmt = $conn->prepare("SELECT evTitle FROM tbl_events WHERE evID = ? LIMIT 1");
    $firstEventStmt->bind_param("i", $eventId);
    $firstEventStmt->execute();
    $firstEventResult = $firstEventStmt->get_result();
    $pageTitle = ($firstEventResult && $firstEventResult->num_rows > 0) 
        ? $firstEventResult->fetch_assoc()['evTitle'] 
        : 'Events Management';
    $firstEventStmt->close();
} else {
    // Fetch first event title if no eventId provided
    $firstEventStmt = $conn->prepare("SELECT evTitle FROM tbl_events LIMIT 1");
    $firstEventStmt->execute();
    $firstEventResult = $firstEventStmt->get_result();
    $pageTitle = ($firstEventResult && $firstEventResult->num_rows > 0) 
        ? $firstEventResult->fetch_assoc()['evTitle'] 
        : 'Events Management';
    $firstEventStmt->close();
}

// Attendance status options (default + Present/Absent)
$statusOptions = [
    ['id' => '', 'label' => 'No attendance yet'],
    ['id' => 1, 'label' => 'Present'],
    ['id' => 2, 'label' => 'Absent'],
];

// Fetch members/attendees for the specific event (if eventId is set)
$membersResult = null;
if ($eventId) {
    $membersQuery = "SELECT m.mbID, CONCAT_WS(' ', m.mbFname, NULLIF(m.mbMname, ''), m.mbLname) AS memberName, pa.evAttendanceStatusID
                     FROM tbl_members m
                     LEFT JOIN tbl_eventparticipantsattendance pa ON m.mbID = pa.mbID AND pa.evID = ?
                     ORDER BY m.mbFname ASC";
    $membersStmt = $conn->prepare($membersQuery);
    $membersStmt->bind_param("i", $eventId);
    $membersStmt->execute();
    $membersResult = $membersStmt->get_result();
    $membersStmt->close();
}       

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
                <a class="navbar-brand d-flex ms-4" href="adminhomepage.php">
                    <img src="assets\img\isc_brand_bold.png" alt="Logo" width="250" height="auto" class="mt-1 mb-1">
                </a>
            </div>

            <div
                class="pe-sm-3 d-flex flex-column flex-sm-row gap-2 gap-lg-4 align-items-center justify-content-center justify-content-md-end ms-md-auto">
                <a class="navbar-brand d-flex" href="admin-events.php">
                    <img src="assets\img\back.png" alt="Back" width="30" height="auto" class="mt-1 mb-1">
                </a>
            </div>

        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="bg-white rounded-4 p-4 shadow" style="border:3px solid #2f6fed; min-height:450px;">

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            

            <!-- Events Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Members</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($eventId && $membersResult && $membersResult->num_rows > 0): ?>
                            <?php while ($member = $membersResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($member['memberName']) ?></td>
                                    <td>
                                        <select class="form-select form-select-sm" onchange="updateAttendanceStatus(<?= $member['mbID'] ?>, <?= $eventId ?>, this.value)">
                                            <?php foreach ($statusOptions as $status): ?>
                                                <option value="<?= $status['id'] ?>" <?= ($member['evAttendanceStatusID'] === null && $status['id'] === '') ? 'selected' : ((string)$member['evAttendanceStatusID'] === (string)$status['id'] ? 'selected' : '') ?>>
                                                    <?= htmlspecialchars($status['label']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">No members found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <?php include("shared/footer.php"); ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    
    <script>
        // Function to update attendance status via AJAX
        function updateAttendanceStatus(memberId, eventId, status) {
            fetch('update_attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'memberId=' + memberId + '&eventId=' + eventId + '&status=' + status
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Attendance status updated successfully');
                } else {
                    alert('Error updating attendance status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating attendance status');
            });
        }
    </script>

</body>

</html>