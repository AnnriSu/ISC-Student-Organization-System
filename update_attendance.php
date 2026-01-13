<?php
session_start();
include("connect.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Ensure attendance statuses exist (Present = 1, Absent = 2)
$seedStatuses = [
    ['id' => 1, 'label' => 'Present'],
    ['id' => 2, 'label' => 'Absent'],
];
$seedStmt = $conn->prepare("INSERT INTO tbl_eventattendancestatus (evAttendanceStatusID, evAttendanceStatusDesc) VALUES (?, ?) ON DUPLICATE KEY UPDATE evAttendanceStatusDesc = VALUES(evAttendanceStatusDesc)");
foreach ($seedStatuses as $seed) {
    $seedStmt->bind_param("is", $seed['id'], $seed['label']);
    $seedStmt->execute();
}
$seedStmt->close();

// Get POST data
$memberId = isset($_POST['memberId']) ? intval($_POST['memberId']) : 0;
$eventId = isset($_POST['eventId']) ? intval($_POST['eventId']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

header('Content-Type: application/json');

// Validate inputs
if ($memberId <= 0 || $eventId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid member or event ID']);
    exit();
}   

// Check if attendance record exists
$checkStmt = $conn->prepare("SELECT evID, mbID FROM tbl_eventparticipantsattendance WHERE mbID = ? AND evID = ?");
$checkStmt->bind_param("ii", $memberId, $eventId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$recordExists = $checkResult->num_rows > 0;
$checkStmt->close();

if ($status === '') {
    // Remove record if status cleared
    if ($recordExists) {
        $deleteStmt = $conn->prepare("DELETE FROM tbl_eventparticipantsattendance WHERE mbID = ? AND evID = ?");
        $deleteStmt->bind_param("ii", $memberId, $eventId);
        if ($deleteStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Attendance cleared']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error clearing attendance: ' . $conn->error]);
        }
        $deleteStmt->close();
    } else {
        echo json_encode(['success' => true, 'message' => 'No attendance to clear']);
    }
    exit();
}

// Require a valid status (1 = Present, 2 = Absent). Empty string is handled earlier.
$statusInt = intval($status);
if ($statusInt !== 1 && $statusInt !== 2) {
    echo json_encode(['success' => false, 'message' => 'Invalid attendance status']);
    exit();
}

// Update or insert with new status
if ($recordExists) {
    $updateStmt = $conn->prepare("UPDATE tbl_eventparticipantsattendance SET evAttendanceStatusID = ? WHERE mbID = ? AND evID = ?");
    $updateStmt->bind_param("iii", $statusInt, $memberId, $eventId);

    if ($updateStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Attendance status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating attendance: ' . $updateStmt->error]);
    }
    $updateStmt->close();
} else {
    $insertStmt = $conn->prepare("INSERT INTO tbl_eventparticipantsattendance (mbID, evID, evAttendanceStatusID) VALUES (?, ?, ?)");
    $insertStmt->bind_param("iii", $memberId, $eventId, $statusInt);

    if ($insertStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Attendance status recorded successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error recording attendance: ' . $insertStmt->error]);
    }
    $insertStmt->close();
}
?>
