<?php

require_once '../model/Station.php';

header('Content-Type: application/json');

if (isset($_GET['stationId'])) {
    $stationId = intval($_GET['stationId']);
    $station = getStation($stationId);

    if ($station) {
        echo json_encode([
            "stationId" => $station['Station_ID'],
            "stationName" => $station['Station_Name'],
            "capacity" => $station['Capacity'],
            "available" => $station['Available_Cycle_Quantity'],
            "status" => $station['Status']
        ]);
    } else {
        echo json_encode(["error" => "Station not found"]);
    }
} else {
    $stations = getStations();
    $data = [];
    foreach ($stations as $s) {
        $data[] = [
            "stationId" => $s['Station_ID'],
            "stationName" => $s['Station_Name'],
            "capacity" => $s['Capacity'],
            "available" => $s['Available_Cycle_Quantity'],
            "status" => $s['Status']
        ];
    }
    echo json_encode($data);
}
?>
