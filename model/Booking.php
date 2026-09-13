<?php
require_once 'dbConnect.php';
require_once 'Station.php';

// Create a booking header row, returns new Booking_ID
function createBooking($phone, $stationId) {
    $conn = connect();
    $sql = "INSERT INTO booking (Booking_Date, Status, Phone_Number, Start_Station_ID) VALUES (NOW(), 'Confirmed', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $phone, $stationId);
    $result = $stmt->execute();
    $newId = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $result ? $newId : false;
}

// Attach a vehicle to a booking, mark it Booked, and reduce station availability
function addVehicleToBooking($bookingId, $vehicleId, $stationId) {
    $conn = connect();
    $sql = "INSERT INTO booking_vehicle (Booking_ID, Vehicle_ID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bookingId, $vehicleId);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        $sql2 = "UPDATE vehicle SET Status = 'Booked' WHERE Vehicle_ID = ? AND Status = 'Available'";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $vehicleId);
        $stmt2->execute();
        $affected = $stmt2->affected_rows;
        $stmt2->close();
        $conn->close();

        if ($affected > 0) {
            updateAvailability($stationId, -1);
        }
        return true;
    }

    $conn->close();
    return false;
}

// Get a booking by ID including its vehicles
function getBooking($bookingId) {
    $conn = connect();
    $sql = "SELECT b.*, s.Station_Name FROM booking b JOIN station s ON b.Start_Station_ID = s.Station_ID WHERE b.Booking_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($booking) {
        $sql2 = "SELECT v.* FROM booking_vehicle bv JOIN vehicle v ON bv.Vehicle_ID = v.Vehicle_ID WHERE bv.Booking_ID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $bookingId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $vehicles = [];
        while ($row = $result2->fetch_assoc()) {
            $vehicles[] = $row;
        }
        $stmt2->close();
        $booking['Vehicles'] = $vehicles;
    }

    $conn->close();
    return $booking;
}

// Get all bookings for a customer
function getMyBookings($phone) {
    $conn = connect();
    $sql = "SELECT b.*, s.Station_Name,
                   (SELECT COUNT(*) FROM booking_vehicle bv WHERE bv.Booking_ID = b.Booking_ID) AS Vehicle_Count
            FROM booking b JOIN station s ON b.Start_Station_ID = s.Station_ID
            WHERE b.Phone_Number = ? ORDER BY b.Booking_Date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $bookings;
}

// Cancel a booking: release vehicles back to Available and restore station availability
function cancelBooking($bookingId) {
    $conn = connect();

    $sql = "SELECT Vehicle_ID FROM booking_vehicle WHERE Booking_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicleIds = [];
    while ($row = $result->fetch_assoc()) {
        $vehicleIds[] = $row['Vehicle_ID'];
    }
    $stmt->close();

    $sql2 = "SELECT Start_Station_ID FROM booking WHERE Booking_ID = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $bookingId);
    $stmt2->execute();
    $bookingRow = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    foreach ($vehicleIds as $vid) {
        $sql3 = "UPDATE vehicle SET Status = 'Available' WHERE Vehicle_ID = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $vid);
        $stmt3->execute();
        $stmt3->close();
    }

    $sql4 = "UPDATE booking SET Status = 'Cancelled' WHERE Booking_ID = ?";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("i", $bookingId);
    $result4 = $stmt4->execute();
    $stmt4->close();
    $conn->close();

    if ($bookingRow && count($vehicleIds) > 0) {
        updateAvailability($bookingRow['Start_Station_ID'], count($vehicleIds));
    }

    return $result4;
}
?>
