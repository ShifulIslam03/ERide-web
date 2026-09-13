<?php
session_start();
require_once '../model/Payment.php';
require_once '../model/Ride.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['globalErrMsg'] = "";
$_SESSION['successMsg'] = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'pay' && $_SESSION['role'] === 'Customer') {
        $rideId = intval($_POST['rideId']);
        $method = htmlspecialchars(trim($_POST['method']));
        $phone = $_SESSION['phone'];

        $ride = getRide($rideId);
        if (!$ride || $ride['Phone_Number'] !== $phone || $ride['Status'] !== 'Completed') {
            $_SESSION['globalErrMsg'] = "Invalid ride for payment.";
            header("Location: ../view/customer/dashboard.php");
            exit();
        }

        $existing = getRidePayment($rideId);
        if ($existing) {
            $_SESSION['successMsg'] = "This ride has already been paid.";
            header("Location: ../view/customer/rideHistory.php");
            exit();
        }

        if (!in_array($method, ['Card', 'Bkash'])) {
            $_SESSION['globalErrMsg'] = "Please select a valid payment method.";
            header("Location: ../view/customer/payment.php?rideId=$rideId");
            exit();
        }

        $transactionId = strtoupper($method) . '-' . time() . '-' . rand(1000, 9999);
        $paymentId = createPayment($rideId, $ride['Fare'], $method, $transactionId);

        if ($paymentId) {
            $_SESSION['successMsg'] = "Payment successful! Transaction ID: $transactionId";
            header("Location: ../view/customer/rideHistory.php");
            exit();
        } else {
            $_SESSION['globalErrMsg'] = "Payment failed. Please try again.";
            header("Location: ../view/customer/payment.php?rideId=$rideId");
            exit();
        }

    } elseif ($action === 'updateStatus' && $_SESSION['role'] === 'Admin') {
        $paymentId = intval($_POST['paymentId']);
        $status = htmlspecialchars(trim($_POST['status']));
        if (in_array($status, ['Pending', 'Paid', 'Failed'])) {
            updatePaymentStatus($paymentId, $status);
            $_SESSION['successMsg'] = "Payment status updated";
        } else {
            $_SESSION['globalErrMsg'] = "Invalid status";
        }
        header("Location: ../view/admin/payments.php");
        exit();

    } else {
        $_SESSION['globalErrMsg'] = "Unauthorized or unknown action";
        header("Location: ../view/login.php");
        exit();
    }
} else {
    header("Location: ../view/login.php");
    exit();
}
?>
