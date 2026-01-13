<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Database connection
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "db_iscstudentorganizationrecords"
);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database connection failed",
        "details" => $conn->connect_error
    ]);
    exit;
}

// Check if mbID is provided
$mbID = $_GET['mbID'] ?? null;

// 🔹 CASE 1: Get ALL members
if (!$mbID) {

    $sql = "SELECT * FROM tbl_members";
    $result = $conn->query($sql);

    $members = [];

    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "count" => count($members),
        "members" => $members
    ], JSON_PRETTY_PRINT);

    $conn->close();
    exit;
}

// 🔹 CASE 2: Get ONE member
$sql = "SELECT * FROM tbl_members WHERE mbID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mbID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    echo json_encode([
        "status" => "found",
        "data" => $result->fetch_assoc()
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "status" => "not_found"
    ]);
}

$stmt->close();
$conn->close();
?>
