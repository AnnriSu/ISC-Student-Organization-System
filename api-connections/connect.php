<?php
/**************************************
 * Database Configuration
 **************************************/
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_iscstudentorganizationrecords";

/**************************************
 * MySQLi Connection (Legacy Support)
 **************************************/
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "MySQLi connection failed",
        "details" => $conn->connect_error
    ]));
}

/**************************************
 * PDO Connection (REST API Support)
 **************************************/
try {
    $pdo = new PDO(
        "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ]
    );
} catch (PDOException $e) {
    die(json_encode([
        "status" => "error",
        "message" => "PDO connection failed",
        "details" => $e->getMessage()
    ]));
}

/**************************************
 * Helper Function (Optional)
 **************************************/
function executeQuery($query)
{
    $conn = $GLOBALS['conn'];
    return mysqli_query($conn, $query);
}
?>
