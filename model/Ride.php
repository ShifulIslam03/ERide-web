<?php
require_once 'dbConnect.php';
require_once 'Station.php';

// Fare calculation: 2 Taka per minute per bike, with a 3 Taka minimum charge per bike
function calculateFare($startTime, $endTime, $bikeCount) {
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);
    $minutes = max(1, ceil(($end->getTimestamp() - $start->getTimestamp()) / 60));

    $perMinuteRate = 2.00;  // taka per minute per bike
    $minimumCharge = 3.00;  // minimum charge per bike

    $farePerBike = max($minimumCharge, $minutes * $perMinuteRate);
    $fare = $bikeCount * $farePerBike;
    return round($fare, 2);
}

// Start a ride from a Confirmed booking
function startRide($bookingId, $phone, $stationId) {
    $conn = connect();
    $sql = "INSERT INTO ride (Booking_ID, Status, Fare, Start_Time, Start_Station_ID, Phone_Number)
            VALUES (?, 'Ongoing', 0, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $bookingId, $stationId, $phone);
    $result = $stmt->execute();
    $rideId = $stmt->insert_id;
    $stmt->close();

    if ($result) {
        $sql2 = "UPDATE booking SET Status = 'Ongoing' WHERE Booking_ID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $bookingId);
        $stmt2->execute();
        $stmt2->close();

        // Copy vehicles from Booking_Vehicle into Ride_Vehicle and mark them In Ride
        $sql3 = "SELECT Vehicle_ID FROM booking_vehicle WHERE Booking_ID = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $bookingId);
        $stmt3->execute();
        $res3 = $stmt3->get_result();
        while ($row = $res3->fetch_assoc()) {
            addVehicleToRide($rideId, $row['Vehicle_ID']);
        }
        $stmt3->close();
    }

    $conn->close();
    return $result ? $rideId : false;
}

// Link a vehicle to a ride and set it "In Ride"
function addVehicleToRide($rideId, $vehicleId) {
    $conn = connect();
    $sql = "INSERT INTO ride_vehicle (Ride_ID, Vehicle_ID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $rideId, $vehicleId);
    $result = $stmt->execute();
    $stmt->close();

    $sql2 = "UPDATE vehicle SET Status = 'In Ride' WHERE Vehicle_ID = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $vehicleId);
    $stmt2->execute();
    $stmt2->close();

    $conn->close();
    return $result;
}

// Get a customer's currently ongoing ride (if any)
function getActiveRide($phone) {
    $conn = connect();
    $sql = "SELECT r.*, s.Station_Name AS Start_Station_Name
            FROM ride r JOIN station s ON r.Start_Station_ID = s.Station_ID
            WHERE r.Phone_Number = ? AND r.Status = 'Ongoing'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $ride = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($ride) {
        $sql2 = "SELECT v.* FROM ride_vehicle rv JOIN vehicle v ON rv.Vehicle_ID = v.Vehicle_ID WHERE rv.Ride_ID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $ride['Ride_ID']);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $vehicles = [];
        while ($row = $res2->fetch_assoc()) {
            $vehicles[] = $row;
        }
        $stmt2->close();
        $ride['Vehicles'] = $vehicles;
    }

    $conn->close();
    return $ride;
}

// Complete a ride at the given end station: calculate fare, free bikes, update availability
function completeRide($rideId, $endStationId) {
    $conn = connect();

    $sql = "SELECT * FROM ride WHERE Ride_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rideId);
    $stmt->execute();
    $ride = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$ride) {
        $conn->close();
        return false;
    }

    $sql2 = "SELECT Vehicle_ID FROM ride_vehicle WHERE Ride_ID = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $rideId);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $vehicleIds = [];
    while ($row = $res2->fetch_assoc()) {
        $vehicleIds[] = $row['Vehicle_ID'];
    }
    $stmt2->close();

    $endTime = date('Y-m-d H:i:s');
    $fare = calculateFare($ride['Start_Time'], $endTime, count($vehicleIds));

    $sql3 = "UPDATE ride SET Status = 'Completed', End_Time = ?, End_Station_ID = ?, Fare = ? WHERE Ride_ID = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("sidi", $endTime, $endStationId, $fare, $rideId);
    $result = $stmt3->execute();
    $stmt3->close();

    if ($result) {
        $sql4 = "UPDATE booking SET Status = 'Completed' WHERE Booking_ID = ?";
        $stmt4 = $conn->prepare($sql4);
        $stmt4->bind_param("i", $ride['Booking_ID']);
        $stmt4->execute();
        $stmt4->close();

        foreach ($vehicleIds as $vid) {
            $sql5 = "UPDATE vehicle SET Status = 'Available', Station_ID = ? WHERE Vehicle_ID = ?";
            $stmt5 = $conn->prepare($sql5);
            $stmt5->bind_param("ii", $endStationId, $vid);
            $stmt5->execute();
            $stmt5->close();
        }
    }

    $conn->close();

    if ($result && count($vehicleIds) > 0) {
        updateAvailability($endStationId, count($vehicleIds));
    }

    return $result ? $fare : false;
}

// Get ride history for a customer
function getRideHistory($phone) {
    $conn = connect();
    $sql = "SELECT r.*, s1.Station_Name AS Start_Station_Name, s2.Station_Name AS End_Station_Name
            FROM ride r
            JOIN station s1 ON r.Start_Station_ID = s1.Station_ID
            LEFT JOIN station s2 ON r.End_Station_ID = s2.Station_ID
            WHERE r.Phone_Number = ? ORDER BY r.Start_Time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $rides = [];
    while ($row = $result->fetch_assoc()) {
        $rides[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $rides;
}

// Get a single ride by ID
function getRide($rideId) {
    $conn = connect();
    $sql = "SELECT * FROM ride WHERE Ride_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rideId);
    $stmt->execute();
    $ride = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $ride;
}
?>
