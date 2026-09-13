<?php
session_start();
require_once '../model/Vehicle.php';
require_once '../model/Station.php';

if (!isset($_SESSION['isLoggedIn']) || ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Admin')) {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";

$phone = $_SESSION['phone'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'addVehiclesByType') {
       
        $typeName = htmlspecialchars(trim(isset($_POST['bikeTypeName']) ? $_POST['bikeTypeName'] : ''));
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
        $postedStationId = isset($_POST['stationId']) ? intval($_POST['stationId']) : null;
        $stationId = $postedStationId;
        $flag = true;

        if ($_SESSION['role'] === 'Manager') {
            $stations = getStationByManager($phone);
            if (empty($stations)) {
                $flag = false;
                $_SESSION['globalErrMsg'] = "You are not assigned to any station yet";
            } elseif (!getManagerStation($phone, $postedStationId)) {
                $flag = false;
                $_SESSION['globalErrMsg'] = "Please select one of your assigned stations";
            }
        }

        if ($flag && !getBikeType($typeName)) {
            $flag = false;
            $_SESSION['globalErrMsg'] = "Please select a valid bike type";
        }
        if ($flag && ($quantity < 1 || $quantity > 50)) {
            $flag = false;
            $_SESSION['globalErrMsg'] = "Please enter a quantity between 1 and 50";
        }

        if ($flag) {
            $addedCount = addVehiclesByType($typeName, $quantity, $stationId);
            $_SESSION['successMsg'] = $addedCount . " bike(s) of \"" . $typeName . "\" added to the station";
        }
        header("Location: ../view/manager/addVehicle.php" . ($postedStationId ? "?id=" . $postedStationId : ""));
        exit();

    } elseif ($action === 'updateVehicle') {
        $vehicleId = intval($_POST['vehicleId']);
        $name = htmlspecialchars(trim($_POST['vehicleName']));
        $type = htmlspecialchars(trim($_POST['vehicleType']));
        $battery = htmlspecialchars(trim($_POST['battery']));
        $topSpeed = floatval($_POST['topSpeed']);
        $stationId = isset($_POST['stationId']) ? intval($_POST['stationId']) : null;

        updateVehicle($vehicleId, $name, $type, $battery, $topSpeed);
        $_SESSION['successMsg'] = "Bike updated successfully";
        header("Location: ../view/manager/vehicles.php" . ($stationId ? "?id=" . $stationId : ""));
        exit();

    } elseif ($action === 'updateStatus') {
        $vehicleId = intval($_POST['vehicleId']);
        $status = htmlspecialchars(trim($_POST['status']));
        $stationId = isset($_POST['stationId']) ? intval($_POST['stationId']) : null;
        if (in_array($status, ['Available', 'Booked', 'In Ride', 'Maintenance'])) {
            updateVehicleStatus($vehicleId, $status);
            $_SESSION['successMsg'] = "Bike status updated";
        } else {
            $_SESSION['globalErrMsg'] = "Invalid status";
        }
        header("Location: ../view/manager/vehicles.php" . ($stationId ? "?id=" . $stationId : ""));
        exit();

    } elseif ($action === 'deleteVehicle') {
        $vehicleId = intval($_POST['vehicleId']);
        $stationId = isset($_POST['stationId']) ? intval($_POST['stationId']) : null;
        deleteVehicle($vehicleId);
        $_SESSION['successMsg'] = "Bike removed from fleet";
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
