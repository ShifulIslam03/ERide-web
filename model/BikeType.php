<?php
require_once 'dbConnect.php';


function getBikeTypes() {
    $conn = connect();
    $sql = "SELECT * FROM bike_type ORDER BY Bike_Type_ID";
    $result = $conn->query($sql);
    $bikeTypes = [];
    while ($row = $result->fetch_assoc()) {
        $bikeTypes[] = $row;
    }
    $conn->close();
    return $bikeTypes;
}


function getBikeType($typeName) {
    $conn = connect();
    $sql = "SELECT * FROM bike_type WHERE Type_Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $typeName);
    $stmt->execute();
    $result = $stmt->get_result();
    $bikeType = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $bikeType;
}
?>
