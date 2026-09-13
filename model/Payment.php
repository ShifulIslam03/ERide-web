<?php
require_once 'dbConnect.php';

// Create a payment record for a completed ride
function createPayment($rideId, $amount, $method, $transactionId) {
    $conn = connect();
    $sql = "INSERT INTO payment (Amount, Payment_Date, Payment_Method, Payment_Status, Ride_ID, Transaction_ID)
            VALUES (?, NOW(), ?, 'Paid', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dsis", $amount, $method, $rideId, $transactionId);
    $result = $stmt->execute();
    $newId = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $result ? $newId : false;
}

// Get payment for a specific ride
function getRidePayment($rideId) {
    $conn = connect();
    $sql = "SELECT * FROM payment WHERE Ride_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rideId);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $payment;
}

// Get single payment by ID
function getPayment($paymentId) {
    $conn = connect();
    $sql = "SELECT * FROM payment WHERE Payment_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paymentId);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $payment;
}

// Get a customer's full payment history
function getPaymentHistory($phone) {
    $conn = connect();
    $sql = "SELECT p.*, r.Start_Time, r.End_Time
            FROM payment p
            JOIN ride r ON p.Ride_ID = r.Ride_ID
            WHERE r.Phone_Number = ? ORDER BY p.Payment_Date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $payments;
}

// Admin: get every payment/transaction in the system
function getAllPayments() {
    $conn = connect();
    $sql = "SELECT p.*, r.Phone_Number, u.Name AS Customer_Name
            FROM payment p
            JOIN ride r ON p.Ride_ID = r.Ride_ID
            JOIN users u ON r.Phone_Number = u.Phone_Number
            ORDER BY p.Payment_Date DESC";
    $result = $conn->query($sql);
    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    $conn->close();
    return $payments;
}

// Update the status of a payment (Pending / Paid / Failed)
function updatePaymentStatus($paymentId, $status) {
    $conn = connect();
    $sql = "UPDATE payment SET Payment_Status = ? WHERE Payment_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $paymentId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}
?>
