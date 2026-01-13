<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include("connect.php"); // must provide $pdo (PDO connection)

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
   GET – FETCH LOCAL + REMOTE
   ========================= */
function handleGet($pdo)
{
  // 🔹 LOCAL FEEDBACK
  $sql = "SELECT fbID, fbContent, mbID, mbMobileNo, mbEmail, fbWebsiteName, fbCategory, fbName, fbStatus FROM tbl_feedback ORDER BY fbID DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $localData = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // 🔹 REMOTE API (OTHER WEBSITE)
  $remoteUrl = "https://buirdly-unwisely-dinorah.ngrok-free.dev/WEBDEV/testAPI/api_reports.php";

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
   POST – CREATE FEEDBACK
   ========================= */
function handlePost($pdo, $input)
{
  $sql = "INSERT INTO tbl_feedback (fbContent, mbID)
          VALUES (:fbContent, :mbID)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'fbContent' => $input['fbContent'],
    'mbID'      => $input['mbID']
  ]);

  echo json_encode(['message' => 'Feedback created successfully']);
}

/* =========================
   PUT – UPDATE FEEDBACK
   ========================= */
function handlePut($pdo, $input)
{
  $sql = "UPDATE tbl_feedback SET
            fbContent = :fbContent
          WHERE fbID = :fbID";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'fbContent' => $input['fbContent'],
    'fbID'      => $input['fbID']
  ]);

  echo json_encode(['message' => 'Feedback updated successfully']);
}

/* =========================
   DELETE – DELETE FEEDBACK
   ========================= */
function handleDelete($pdo, $input)
{
  $sql = "DELETE FROM tbl_feedback WHERE fbID = :fbID";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'fbID' => $input['fbID']
  ]);

  echo json_encode(['message' => 'Feedback deleted successfully']);
}
?>
