<?php
require "connect.php";
echo json_encode(["status" => "Bangis - successful connection"]);
$method = $_SERVER['REQUEST_METHOD'];