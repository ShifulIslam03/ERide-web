<?php
require_once '../model/User.php';

header('Content-Type: application/json');

if (isset($_GET['phone'])) {
    $phone = htmlspecialchars(trim($_GET['phone']));

    if (!preg_match('/^01[0-9]{9}$/', $phone)) {
        echo json_encode(["valid" => false, "message" => "Invalid phone format"]);
        exit();
    }

    if (phoneExists($phone)) {
        echo json_encode(["exists" => true, "message" => "This phone number is already registered"]);
    } else {
        echo json_encode(["exists" => false, "message" => "Phone number is available"]);
    }
} else {
    echo json_encode(["error" => "No phone provided"]);
}
?>
