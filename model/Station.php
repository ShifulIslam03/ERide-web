<?php
require_once 'dbConnect.php';


function addStation($name, $area, $block, $road, $capacity) {
    $conn = connect();
    $sql = "INSERT INTO station (Station_Name, Area, Block, Road, Capacity, Available_Cycle_Quantity, Status)
            VALUES (?, ?, ?, ?, ?, 0, 'Active')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $area, $block, $road, $capacity);
    $result = $stmt->execute();
    $newId = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $result ? $newId : false;
}


function getStations() {
    $conn = connect();
    $sql = "SELECT s.*, u.Name AS Manager_Name
            FROM station s
            LEFT JOIN users u ON s.Manager_Phone_Number = u.Phone_Number
            ORDER BY s.Station_Name";
    $result = $conn->query($sql);
    $stations = [];
    while ($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
    $conn->close();
    return $stations;
}


function getStation($stationId) {
    $conn = connect();
    $sql = "SELECT s.*, u.Name AS Manager_Name
            FROM station s
            LEFT JOIN users u ON s.Manager_Phone_Number = u.Phone_Number
            WHERE s.Station_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $station = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $station;
}


function getStationByManager($managerPhone) {
    $conn = connect();
    $sql = "SELECT * FROM station WHERE Manager_Phone_Number = ? ORDER BY Station_Name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $managerPhone);
    $stmt->execute();
    $result = $stmt->get_result();
    $stations = [];
    while ($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $stations;
}


function getManagerStation($managerPhone, $stationId) {
    $stations = getStationByManager($managerPhone);
    foreach ($stations as $s) {
        if ($s['Station_ID'] == $stationId) {
            return $s;
        }
    }
    return null;
}


function updateStation($stationId, $name, $area, $block, $road, $capacity, $status) {
    $conn = connect();
    $sql = "UPDATE station SET Station_Name = ?, Area = ?, Block = ?, Road = ?, Capacity = ?, Status = ? WHERE Station_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $name, $area, $block, $road, $capacity, $status, $stationId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function deleteStation($stationId) {
    $conn = connect();
    $sql = "UPDATE station SET Status = 'Inactive' WHERE Station_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stationId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function assignManager($stationId, $managerPhone) {
    $conn = connect();
    $sql = "UPDATE station SET Manager_Phone_Number = ? WHERE Station_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $managerPhone, $stationId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function updateAvailability($stationId, $delta) {
    $conn = connect();
    $sql = "UPDATE station SET Available_Cycle_Quantity = Available_Cycle_Quantity + ? WHERE Station_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $delta, $stationId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}
?>
