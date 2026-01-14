<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit();
}

include("connect.php"); // provides mysqli $conn

$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    handleGet($conn);
    break;

  case 'POST':
    handlePost($conn, $input);
    break;

  case 'PUT':
    handlePut($conn, $input);
    break;

  case 'DELETE':
    handleDelete($conn, $input);
    break;

  default:
    echo json_encode(['message' => 'Invalid request method']);
    break;
}

/* =========================
   GET – FETCH LOCAL + REMOTE MEMBERS
   (unchanged behaviour)
   ========================= */
function handleGet($conn)
{
  // 🔹 LOCAL MEMBERS
  $localData = [];
  $sql = "SELECT * FROM tbl_members ORDER BY mbID DESC";
  if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
      $localData[] = $row;
    }
    $result->free();
  }

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
   POST – RECEIVE REMOTE APPLICATION
   and map it into tbl_applications
   ========================= */
function handlePost($conn, $input)
{
  if (!is_array($input)) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON payload']);
    return;
  }

  // Expected fields from remote site
  $required = [
    'isc_applications_id',
    'first_name',
    'last_name',
    'birth_date',
    'department',
    'section',
    'institution',
    'email',
    'phone',
    'salutation',
    'pronoun',
    'status'
  ];

  foreach ($required as $field) {
    if (!isset($input[$field]) || $input[$field] === '') {
      echo json_encode(['success' => false, 'error' => "Missing required field: $field"]);
      return;
    }
  }

  // Map remote fields -> tbl_applications columns
  $apFname       = $input['first_name'];
  $apLname       = $input['last_name'];
  $apMname       = $input['middle_name'] ?? null;
  $apSuffix      = $input['suffix'] ?? null;
  $apSalutations = $input['salutation'];
  $apPronouns    = $input['pronoun'];
  $apBirthDate   = $input['birth_date'];   // YYYY-MM-DD
  $apDepartment  = $input['department'];
  $apSection     = $input['section'];
  $apInstitution = $input['institution'];
  $apMobileNo    = $input['phone'];
  $apEmail       = $input['email'];
  $statusText    = $input['status'];       // e.g. "approved", "pending"

  // Map status text -> apStatusID
  $apStatusID = null;
  if ($stmtStatus = $conn->prepare("SELECT apStatusID FROM tbl_applicationstatus WHERE LOWER(apStatusDesc) = LOWER(?) LIMIT 1")) {
    $stmtStatus->bind_param("s", $statusText);
    $stmtStatus->execute();
    $stmtStatus->bind_result($foundStatusID);
    if ($stmtStatus->fetch()) {
      $apStatusID = $foundStatusID;
    }
    $stmtStatus->close();
  }

  // Default to Pending (usually ID 1) if status not found
  if ($apStatusID === null) {
    $apStatusID = 1;
  }

  // Insert into tbl_applications
  $stmt = $conn->prepare("
        INSERT INTO tbl_applications
          (apFname, apLname, apMname, apSuffix, apSalutations, apPronouns, apBirthDate, apDepartment, apSection, apInstitution, apMobileNo, apEmail, apStatusID)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

  if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
    return;
  }

  $stmt->bind_param(
    "ssssssssssssi",
    $apFname,
    $apLname,
    $apMname,
    $apSuffix,
    $apSalutations,
    $apPronouns,
    $apBirthDate,
    $apDepartment,
    $apSection,
    $apInstitution,
    $apMobileNo,
    $apEmail,
    $apStatusID
  );

  if ($stmt->execute()) {
    $newId = $stmt->insert_id;
    $stmt->close();
    echo json_encode([
      'success' => true,
      'message' => 'Application saved to tbl_applications',
      'apID'    => $newId
    ]);
  } else {
    $error = $stmt->error;
    $stmt->close();
    echo json_encode(['success' => false, 'error' => 'Failed to save application: ' . $error]);
  }
}

/* =========================
   PUT – not used here
   ========================= */
function handlePut($conn, $input)
{
  echo json_encode(['message' => 'PUT not implemented for this endpoint']);
}

/* =========================
   DELETE – not used here
   ========================= */
function handleDelete($conn, $input)
{
  echo json_encode(['message' => 'DELETE not implemented for this endpoint']);
}
?>
