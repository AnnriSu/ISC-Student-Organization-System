<?php
$url = "http://192.168.1.53/ISC-Student-Organization-System/api-connections/shared-members-api.php";
$json = file_get_contents($url);
$data = json_decode($json, true);

foreach ($data['members'] as $member) {
    echo $member['mbFname'] . "<br>";
}
?>
unused-shared-receive-feedback.php
<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "isc_user", "isc_pass", "isc_db");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['email'], $data['message'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid data"]);
    exit;
}

$sql = "INSERT INTO feedback (name, email, message)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss",
    $data['name'],
    $data['email'],
    $data['message']
);

echo json_encode(["success" => $stmt->execute()]);


