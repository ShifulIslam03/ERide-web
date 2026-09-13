<?php
session_start();
require_once '../model/Booking.php';
require_once '../model/Vehicle.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";
$phone = $_SESSION['phone'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'createBooking') {
        $stationId = intval($_POST['stationId']);
        $vehicleIds = isset($_POST['vehicleIds']) ? $_POST['vehicleIds'] : [];

        if (empty($vehicleIds)) {
            $_SESSION['globalErrMsg'] = "Please select at least one bike";
            header("Location: ../view/customer/bookBike.php?stationId=$stationId");
            exit();
        }

        // Re-verify each selected bike is still Available before booking (prevents double-booking)
        $validIds = [];
        foreach ($vehicleIds as $vid) {
            $vid = intval($vid);
            $vehicle = getVehicle($vid);
            if ($vehicle && $vehicle['Status'] === 'Available' && $vehicle['Station_ID'] == $stationId) {
                $validIds[] = $vid;
            }
        }

        if (empty($validIds)) {
            $_SESSION['globalErrMsg'] = "Selected bikes are no longer available. Please try again.";
            header("Location: ../view/customer/bookBike.php?stationId=$stationId");
            exit();
        }

        $bookingId = createBooking($phone, $stationId);
        if ($bookingId) {
            foreach ($validIds as $vid) {
                addVehicleToBooking($bookingId, $vid, $stationId);
            }
            $_SESSION['successMsg'] = "Booking confirmed! " . count($validIds) . " bike(s) reserved.";
            header("Location: ../view/customer/myBookings.php");
            exit();
        } else {
            $_SESSION['globalErrMsg'] = "Booking failed. Please try again.";
            header("Location: ../view/customer/bookBike.php?stationId=$stationId");
            exit();
        }

    } elseif ($action === 'cancelBooking') {
        $bookingId = intval($_POST['bookingId']);
        cancelBooking($bookingId);
        $_SESSION['successMsg'] = "Booking cancelled";
        header("Location: ../view/customer/myBookings.php");
        exit();

    } else {
        $_SESSION['globalErrMsg'] = "Unknown action requested";
        header("Location: ../view/customer/dashboard.php");
        exit();
    }
} else {
    header("Location: ../view/customer/dashboard.php");
    exit();
}
?>
