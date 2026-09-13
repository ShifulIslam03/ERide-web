<?php
session_start();
require_once '../model/Ride.php';
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

    if ($action === 'quickStart') {
        $stationId = intval($_POST['stationId']);
        $quantity = max(1, intval($_POST['quantity']));
        $vehicleType = (isset($_POST['vehicleType']) && trim($_POST['vehicleType']) !== '') ? htmlspecialchars(trim($_POST['vehicleType'])) : null;
        $redirectSuffix = $vehicleType ? ("?type=" . urlencode($vehicleType)) : "";

        $existingRide = getActiveRide($phone);
        if ($existingRide) {
            $_SESSION['globalErrMsg'] = "You already have an active ride. Please complete it first.";
            header("Location: ../view/customer/ride.php");
            exit();
        }

        $available = getAvailableVehiclesLimited($stationId, $quantity, $vehicleType);
        if (count($available) < $quantity) {
            $_SESSION['globalErrMsg'] = "Only " . count($available) . " bike(s) available at this station right now.";
            header("Location: ../view/customer/ride.php$redirectSuffix");
            exit();
        }

        $bookingId = createBooking($phone, $stationId);
        if (!$bookingId) {
            $_SESSION['globalErrMsg'] = "Could not create booking. Please try again.";
            header("Location: ../view/customer/ride.php$redirectSuffix");
            exit();
        }

        foreach ($available as $v) {
            addVehicleToBooking($bookingId, $v['Vehicle_ID'], $stationId);
        }

        $rideId = startRide($bookingId, $phone, $stationId);
        if ($rideId) {
            $_SESSION['successMsg'] = "Ride started with $quantity bike(s). Enjoy your trip!";
        } else {
            $_SESSION['globalErrMsg'] = "Could not start the ride. Please try again.";
        }
        header("Location: ../view/customer/ride.php");
        exit();

    } elseif ($action === 'startRide') {
        $bookingId = intval($_POST['bookingId']);

        $existingRide = getActiveRide($phone);
        if ($existingRide) {
            $_SESSION['globalErrMsg'] = "You already have an active ride. Please complete it first.";
            header("Location: ../view/customer/ride.php");
            exit();
        }

        $booking = getBooking($bookingId);
        if (!$booking || $booking['Phone_Number'] !== $phone || $booking['Status'] !== 'Confirmed') {
            $_SESSION['globalErrMsg'] = "Invalid or already-used booking.";
            header("Location: ../view/customer/myBookings.php");
            exit();
        }

        $rideId = startRide($bookingId, $phone, $booking['Start_Station_ID']);
        if ($rideId) {
            $_SESSION['successMsg'] = "Ride started! Enjoy your trip.";
            header("Location: ../view/customer/ride.php");
            exit();
        } else {
            $_SESSION['globalErrMsg'] = "Could not start the ride. Please try again.";
            header("Location: ../view/customer/myBookings.php");
            exit();
        }

    } elseif ($action === 'completeRide') {
        $rideId = intval($_POST['rideId']);
        $endStationId = intval($_POST['endStationId']);

        $ride = getRide($rideId);
        if (!$ride || $ride['Phone_Number'] !== $phone || $ride['Status'] !== 'Ongoing') {
            $_SESSION['globalErrMsg'] = "Invalid ride.";
            header("Location: ../view/customer/dashboard.php");
            exit();
        }

        $fare = completeRide($rideId, $endStationId);
        if ($fare !== false) {
            $_SESSION['successMsg'] = "Ride completed. Total fare: ৳" . number_format($fare, 2);
            header("Location: ../view/customer/payment.php?rideId=$rideId");
            exit();
        } else {
            $_SESSION['globalErrMsg'] = "Could not complete the ride. Please try again.";
            header("Location: ../view/customer/ride.php");
            exit();
        }

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
