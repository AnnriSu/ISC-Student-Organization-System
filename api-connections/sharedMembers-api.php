<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include("connect.php"); // Make sure $pdo (PDO connection) is properly set up

$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    handleGet($pdo);
    break;

  case 'POST':
    handlePost($pdo, $input);
    break;

  case 'PUT':
    handlePut($pdo, $input);
    break;

  case 'DELETE':
    handleDelete($pdo, $input);
    break;

  default:
    echo json_encode(['message' => 'Invalid request method']);
    break;
}

/* =========================
   GET – FETCH LOCAL + REMOTE MEMBERS
   ========================= */
function handleGet($pdo)
{
  // 🔹 LOCAL MEMBERS
  $sql = "SELECT * FROM tbl_members ORDER BY mbID DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $localData = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // 🔹 REMOTE API (OTHER WEBSITE)
  $remoteUrl = "https://coletta-parecious-improperly.ngrok-free.dev/CampusWear/api/shared/isc.php"; // Replace with actual URL

  $remoteData = [];
  $ch = curl_init($remoteUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  $response = curl_exec($ch);
  curl_close($ch);

  if ($response) {
    $decoded = json_decode($response, true);
    if (is_array($decoded)) {
      $remoteData = $decoded;
    }
  }

  // 🔹 MERGE LOCAL + REMOTE
  $merged = array_merge($localData, $remoteData);

  echo json_encode($merged);
}

/* =========================
   POST – CREATE NEW MEMBER
   ========================= */
function handlePost($pdo, $input)
{
  if (!isset($input['firstName'], $input['lastName'], $input['email'])) {
    echo json_encode(['error' => 'Missing required fields']);
    return;
  }

  $sql = "INSERT INTO tbl_members (firstName, lastName, email)
          VALUES (:firstName, :lastName, :email)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'firstName' => $input['firstName'],
    'lastName'  => $input['lastName'],
    'email'     => $input['email']
  ]);

  echo json_encode(['message' => 'Member created successfully']);
}

/* =========================
   PUT – UPDATE MEMBER
   ========================= */
function handlePut($pdo, $input)
{
  if (!isset($input['mbID'], $input['firstName'], $input['lastName'], $input['email'])) {
    echo json_encode(['error' => 'Missing required fields']);
    return;
  }

  $sql = "UPDATE tbl_members SET
            firstName = :firstName,
            lastName = :lastName,
            email = :email
          WHERE mbID = :mbID";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'firstName' => $input['firstName'],
    'lastName'  => $input['lastName'],
    'email'     => $input['email'],
    'mbID'      => $input['mbID']
  ]);

  echo json_encode(['message' => 'Member updated successfully']);
}

/* =========================
   DELETE – DELETE MEMBER
   ========================= */
function handleDelete($pdo, $input)
{
  if (!isset($input['mbID'])) {
    echo json_encode(['error' => 'Missing member ID']);
    return;
  }

  $sql = "DELETE FROM tbl_members WHERE mbID = :mbID";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'mbID' => $input['mbID']
  ]);

  echo json_encode(['message' => 'Member deleted successfully']);
}
?>
