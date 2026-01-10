<?php
include("connect.php");
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['apID']) || !isset($input['apStatusID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$apID = intval($input['apID']);
$apStatusID = intval($input['apStatusID']);

// Validate that the status ID exists in tbl_applicationstatus
$statusCheck = $conn->prepare("SELECT apStatusID FROM tbl_applicationstatus WHERE apStatusID = ?");
$statusCheck->bind_param("i", $apStatusID);
$statusCheck->execute();
$statusResult = $statusCheck->get_result();

if ($statusResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid status ID']);
    $statusCheck->close();
    exit();
}
$statusCheck->close();

// Update the application status
$stmt = $conn->prepare("UPDATE tbl_applications SET apStatusID = ? WHERE apID = ?");
$stmt->bind_param("ii", $apStatusID, $apID);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>
