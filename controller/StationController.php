<?php
session_start();
require_once '../model/Station.php';
require_once '../model/User.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['nameErrMsg'] = "";
$_SESSION['areaErrMsg'] = "";
$_SESSION['blockErrMsg'] = "";
$_SESSION['roadErrMsg'] = "";
$_SESSION['capacityErrMsg'] = "";
$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";

$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'addStation' && $role === 'Admin') {
        $name = htmlspecialchars(trim($_POST['stationName']));
        $area = htmlspecialchars(trim($_POST['area']));
        $block = htmlspecialchars(trim($_POST['block']));
        $road = htmlspecialchars(trim($_POST['road']));
        $capacity = intval($_POST['capacity']);
        $flag = true;

        if (empty($name)) { $flag = false; $_SESSION['nameErrMsg'] = "Please enter a station name"; }
        if (empty($area)) { $flag = false; $_SESSION['areaErrMsg'] = "Please enter an area"; }
        if (empty($block)) { $flag = false; $_SESSION['blockErrMsg'] = "Please enter a block"; }
        if (empty($road)) { $flag = false; $_SESSION['roadErrMsg'] = "Please enter a road"; }
        if ($capacity <= 0) { $flag = false; $_SESSION['capacityErrMsg'] = "Capacity must be greater than 0"; }

        if ($flag) {
            addStation($name, $area, $block, $road, $capacity);
            $_SESSION['successMsg'] = "Station added successfully";
            header("Location: ../view/admin/stations.php");
            exit();
        } else {
            $_SESSION['globalErrMsg'] = "Please check the form and try again.";
            header("Location: ../view/admin/addStation.php");
            exit();
        }

    } elseif ($action === 'updateStation') {
        $stationId = intval($_POST['stationId']);
        $name = htmlspecialchars(trim($_POST['stationName']));
        $area = htmlspecialchars(trim($_POST['area']));
        $block = htmlspecialchars(trim($_POST['block']));
        $road = htmlspecialchars(trim($_POST['road']));
        $capacity = intval($_POST['capacity']);
        $status = isset($_POST['status']) ? htmlspecialchars(trim($_POST['status'])) : 'Active';
        $flag = true;

        if (empty($name)) { $flag = false; $_SESSION['nameErrMsg'] = "Please enter a station name"; }
        if ($capacity <= 0) { $flag = false; $_SESSION['capacityErrMsg'] = "Capacity must be greater than 0"; }

        if ($role === 'Manager' && !getManagerStation($_SESSION['phone'], $stationId)) {
            $flag = false;
            $_SESSION['globalErrMsg'] = "That station is not assigned to you";
        }

        if ($flag) {
            updateStation($stationId, $name, $area, $block, $road, $capacity, $status);
            $_SESSION['successMsg'] = "Station updated successfully";
        } else {
            $_SESSION['globalErrMsg'] = "Please check the form and try again.";
        }

        if ($role === 'Admin') {
            header("Location: ../view/admin/stations.php");
        } else {
            header("Location: ../view/manager/station.php?id=" . $stationId);
        }
        exit();

    } elseif ($action === 'deleteStation' && $role === 'Admin') {
        $stationId = intval($_POST['stationId']);
        deleteStation($stationId);
        $_SESSION['successMsg'] = "Station deactivated";
        header("Location: ../view/admin/stations.php");
        exit();

    } elseif ($action === 'assignManager' && $role === 'Admin') {
        $stationId = intval($_POST['stationId']);
        $managerPhone = htmlspecialchars(trim($_POST['managerPhone']));
        assignManager($stationId, $managerPhone);
        $_SESSION['successMsg'] = "Manager assigned to station";
        header("Location: ../view/admin/stations.php");
        exit();

    } else {
        $_SESSION['globalErrMsg'] = "Unknown or unauthorized action";
        header("Location: ../view/login.php");
        exit();
    }
} else {
    header("Location: ../view/login.php");
    exit();
}
?>
