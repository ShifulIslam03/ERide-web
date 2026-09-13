<?php
require_once 'dbConnect.php';
require_once 'Station.php';
require_once 'BikeType.php';


function addVehicle($code, $name, $type, $battery, $topSpeed, $stationId) {
    $conn = connect();
    $sql = "INSERT INTO vehicle (Vehicle_Code, Vehicle_Name, Vehicle_Type, Battery_Backup, Top_Speed, Status, Station_ID)
            VALUES (?, ?, ?, ?, ?, 'Available', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdi", $code, $name, $type, $battery, $topSpeed, $stationId);
    $result = $stmt->execute();
    $newId = $stmt->insert_id;
    $stmt->close();
    $conn->close();

    if ($result) {
        updateAvailability($stationId, 1);
    }
    return $result ? $newId : false;
}


function vehicleCodeExists($code) {
    $conn = connect();
    $sql = "SELECT Vehicle_ID FROM vehicle WHERE Vehicle_Code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}


function addMultipleVehicles($bikes, $stationId) {
    $addedCount = 0;
    foreach ($bikes as $bike) {
        $newId = addVehicle($bike['code'], $bike['name'], $bike['type'], $bike['battery'], $bike['topSpeed'], $stationId);
        if ($newId) {
            $addedCount++;
        }
    }
    return $addedCount;
}


function getVehicles($stationId = null) {
    $conn = connect();
    if ($stationId) {
        $sql = "SELECT v.*, COALESCE(bt.Image_Path, 'assets/images/bikes/default-bike.jpg') AS Image_Path
                FROM vehicle v
                LEFT JOIN bike_type bt ON bt.Type_Name = v.Vehicle_Type
                WHERE v.Station_ID = ? ORDER BY v.Vehicle_Code";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stationId);
    } else {
        $sql = "SELECT v.*, s.Station_Name, COALESCE(bt.Image_Path, 'assets/images/bikes/default-bike.jpg') AS Image_Path
                FROM vehicle v
                LEFT JOIN station s ON v.Station_ID = s.Station_ID
                LEFT JOIN bike_type bt ON bt.Type_Name = v.Vehicle_Type
                ORDER BY v.Vehicle_Code";
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $vehicles;
}


function getAvailableVehicles($stationId) {
    $conn = connect();
    $sql = "SELECT * FROM vehicle WHERE Station_ID = ? AND Status = 'Available' ORDER BY Vehicle_Code";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $vehicles;
}


function getVehicle($vehicleId) {
    $conn = connect();
    $sql = "SELECT * FROM vehicle WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $vehicle;
}


function updateVehicle($vehicleId, $name, $type, $battery, $topSpeed) {
    $conn = connect();
    $sql = "UPDATE vehicle SET Vehicle_Name = ?, Vehicle_Type = ?, Battery_Backup = ?, Top_Speed = ? WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $name, $type, $battery, $topSpeed, $vehicleId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function updateVehicleStatus($vehicleId, $status) {
    $conn = connect();
    $sql = "UPDATE vehicle SET Status = ? WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $vehicleId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}


function assignVehicleToStation($vehicleId, $newStationId) {
    $conn = connect();

    $sql = "SELECT Station_ID, Status FROM vehicle WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $old = $result->fetch_assoc();
    $stmt->close();

    $sql = "UPDATE vehicle SET Station_ID = ? WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newStationId, $vehicleId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();

    if ($result && $old && $old['Status'] === 'Available') {
        if ($old['Station_ID']) {
            updateAvailability($old['Station_ID'], -1);
        }
        updateAvailability($newStationId, 1);
    }
    return $result;
}


function getVehicleCatalog() {
    $conn = connect();
    $sql = "SELECT v.Vehicle_Name, v.Vehicle_Type, v.Battery_Backup, v.Top_Speed, COUNT(*) AS Available_Count,
                   COALESCE(bt.Image_Path, 'assets/images/bikes/default-bike.jpg') AS Image_Path
            FROM vehicle v
            LEFT JOIN bike_type bt ON bt.Type_Name = v.Vehicle_Type
            WHERE v.Status = 'Available'
            GROUP BY v.Vehicle_Name, v.Vehicle_Type, v.Battery_Backup, v.Top_Speed, bt.Image_Path
            ORDER BY v.Vehicle_Name";
    $result = $conn->query($sql);
    $catalog = [];
    while ($row = $result->fetch_assoc()) {
        $catalog[] = $row;
    }
    $conn->close();
    return $catalog;
}


function getVehicleCatalogByStation($stationId) {
    $conn = connect();
    $sql = "SELECT v.Vehicle_Name, v.Vehicle_Type, v.Battery_Backup, v.Top_Speed, COUNT(*) AS Available_Count,
                   COALESCE(bt.Image_Path, 'assets/images/bikes/default-bike.jpg') AS Image_Path
            FROM vehicle v
            LEFT JOIN bike_type bt ON bt.Type_Name = v.Vehicle_Type
            WHERE v.Status = 'Available' AND v.Station_ID = ?
            GROUP BY v.Vehicle_Name, v.Vehicle_Type, v.Battery_Backup, v.Top_Speed, bt.Image_Path
            ORDER BY v.Vehicle_Name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $catalog = [];
    while ($row = $result->fetch_assoc()) {
        $catalog[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $catalog;
}


function getStationsWithVehicleType($vehicleName) {
    $conn = connect();
    $sql = "SELECT s.Station_ID, s.Station_Name, s.Area, s.Block, s.Road, s.Available_Cycle_Quantity,
                   COUNT(v.Vehicle_ID) AS Type_Available_Count
            FROM station s
            JOIN vehicle v ON v.Station_ID = s.Station_ID AND v.Status = 'Available' AND v.Vehicle_Name = ?
            WHERE s.Status = 'Active'
            GROUP BY s.Station_ID
            ORDER BY s.Station_Name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vehicleName);
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


function getAvailableVehiclesLimited($stationId, $quantity, $vehicleName = null) {
    $conn = connect();
    if ($vehicleName) {
        $sql = "SELECT * FROM vehicle WHERE Station_ID = ? AND Status = 'Available' AND Vehicle_Name = ? ORDER BY Vehicle_ID LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $stationId, $vehicleName, $quantity);
    } else {
        $sql = "SELECT * FROM vehicle WHERE Station_ID = ? AND Status = 'Available' ORDER BY Vehicle_ID LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $stationId, $quantity);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $vehicles;
}


function generateVehicleCode($typeName) {
    $prefix = strtoupper(preg_replace('/[^A-Za-z]/', '', $typeName));
    $prefix = substr($prefix, 0, 3);
    if ($prefix === '') {
        $prefix = 'BIK';
    }
    do {
        $code = $prefix . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    } while (vehicleCodeExists($code));
    return $code;
}


function addVehiclesByType($typeName, $quantity, $stationId) {
    $bikeType = getBikeType($typeName);
    if (!$bikeType || $quantity < 1) {
        return 0;
    }

    $bikes = [];
    for ($i = 0; $i < $quantity; $i++) {
        $bikes[] = [
            'code' => generateVehicleCode($typeName),
            'name' => $bikeType['Bike_Name'],
            'type' => $bikeType['Type_Name'],
            'battery' => $bikeType['Battery_Backup'],
            'topSpeed' => $bikeType['Top_Speed']
        ];
    }
    return addMultipleVehicles($bikes, $stationId);
}


function deleteVehicle($vehicleId) {
    $conn = connect();
    $sql = "DELETE FROM vehicle WHERE Vehicle_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicleId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}
?>
