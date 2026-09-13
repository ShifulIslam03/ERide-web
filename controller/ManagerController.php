<?php
session_start();
require_once '../model/Station.php';
require_once '../model/Vehicle.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";
$managerPhone = $_SESSION['phone'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'moveVehicle') {
        $vehicleId = intval($_POST['vehicleId']);
        $newStationId = intval($_POST['newStationId']);
        $stationId = isset($_POST['stationId']) ? intval($_POST['stationId']) : null;

        $stations = getStationByManager($managerPhone);
        if (empty($stations)) {
            $_SESSION['globalErrMsg'] = "No station assigned to this manager";
            header("Location: ../view/manager/dashboard.php");
            exit();
        }

        assignVehicleToStation($vehicleId, $newStationId);
        $_SESSION['successMsg'] = "Bike moved successfully";
        header("Location: ../view/manager/vehicles.php" . ($stationId ? "?id=" . $stationId : ""));
        exit();

    } else {
        $_SESSION['globalErrMsg'] = "Unknown action requested";
        header("Location: ../view/manager/dashboard.php");
        exit();
    }
} else {
    header("Location: ../view/manager/dashboard.php");
    exit();
}
?>
