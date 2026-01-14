<?php
session_start();
include("connect.php");

if (!isset($_SESSION['logged_in']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['eventId'])) {
    header("Location: admin-events.php");
    exit();
}

$eventId = intval($_GET['eventId']);

// Fetch event
$stmt = $conn->prepare("SELECT * FROM tbl_events WHERE evID = ?");
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) {
    header("Location: admin-events.php");
    exit();
}

// Fetch statuses
$statusResult = $conn->query("SELECT * FROM tbl_eventstatus ORDER BY evStatusID");
$statusOptions = $statusResult->fetch_all(MYSQLI_ASSOC);

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evTitle = trim($_POST['evTitle']);
    $evDesc = trim($_POST['evDesc']);
    $evDate = $_POST['evDate'];
    $evTime = $_POST['evTime'];
    $evVenue = trim($_POST['evVenue']);
    $evInstructor = trim($_POST['evInstructor']);
    $evLink = trim($_POST['evLink']);
    $evEvaluationLink = trim($_POST['evEvaluationLink']);
    $evStatusID = intval($_POST['evStatusID']);

    $stmt = $conn->prepare("UPDATE tbl_events SET evTitle=?, evDesc=?, evDate=?, evTime=?, evVenue=?, evInstructor=?, evLink=?, evEvaluationLink=?, evStatusID=? WHERE evID=?");
    $stmt->bind_param("ssssssssii", $evTitle, $evDesc, $evDate, $evTime, $evVenue, $evInstructor, $evLink, $evEvaluationLink, $evStatusID, $eventId);

    if ($stmt->execute()) {
        $message = "Event updated successfully!";
    } else {
        $message = "Error updating event: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">

</head>

<body style="padding-bottom: 120px;">

    <?php include 'shared/navbar.php'; ?>

    <div class="container">

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="my-3">
                <label>Event Title</label>
                <input type="text" class="form-control" name="evTitle"
                    value="<?= htmlspecialchars($event['evTitle']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea class="form-control" name="evDesc" rows="3"
                    required><?= htmlspecialchars($event['evDesc']) ?></textarea>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label>Date</label>
                    <input type="date" class="form-control" name="evDate"
                        value="<?= htmlspecialchars($event['evDate']) ?>" required>
                </div>
                <div class="col">
                    <label>Time</label>
                    <input type="time" class="form-control" name="evTime"
                        value="<?= htmlspecialchars($event['evTime']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Venue</label>
                <input type="text" class="form-control" name="evVenue"
                    value="<?= htmlspecialchars($event['evVenue']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Instructor</label>
                <input type="text" class="form-control" name="evInstructor"
                    value="<?= htmlspecialchars($event['evInstructor']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Event Link</label>
                <input type="url" class="form-control" name="evLink" value="<?= htmlspecialchars($event['evLink']) ?>"
                    required>
            </div>
            <div class="mb-3">
                <label>Evaluation Link</label>
                <input type="url" class="form-control" name="evEvaluationLink"
                    value="<?= htmlspecialchars($event['evEvaluationLink']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select class="form-select" name="evStatusID" required>
                    <?php foreach ($statusOptions as $status): ?>
                        <option value="<?= $status['evStatusID'] ?>" <?= $event['evStatusID'] == $status['evStatusID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['evStatusDesc']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="admin-events.php" class="btn btn-secondary me-2">Back</a>
                <button class="btn btn-primary">Update Event</button>
            </div>
        </form>
    </div>

    <?php include 'shared/footer.php'; ?>

</body>

</html>