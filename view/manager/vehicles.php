<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Station.php';
require_once '../../model/Vehicle.php';

$stations = getStationByManager($_SESSION['phone']);
$stationId = isset($_GET['id']) ? intval($_GET['id']) : (!empty($stations) ? $stations[0]['Station_ID'] : 0);
$station = getManagerStation($_SESSION['phone'], $stationId);
$vehicles = $station ? getVehicles($station['Station_ID']) : [];
$allStations = getStations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Bikes - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/manager.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'vehicles'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <h1>Manage Bikes</h1>

    <?php if (count($stations) > 1): ?>
        <div class="station-switch">
            <?php foreach ($stations as $s): ?>
                <a href="vehicles.php?id=<?php echo $s['Station_ID']; ?>" class="btn-secondary btn-sm <?php echo $station && $s['Station_ID'] == $station['Station_ID'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($s['Station_Name']); ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <?php if (!$station): ?>
        <section class="card">
            <p>You are not assigned to a station yet. Please contact the Admin.</p>
        </section>
    <?php else: ?>
    <a href="addVehicle.php?id=<?php echo $station['Station_ID']; ?>" class="btn-primary">+ Add New Bike</a>

    <section class="card">
        <?php if (empty($vehicles)): ?>
            <p>No bikes at this station yet.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr><th>Image</th><th>Code</th><th>Name</th><th>Type</th><th>Battery</th><th>Top Speed</th><th>Status</th><th>Move To</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php foreach ($vehicles as $v): ?>
                    <tr>
                        <td><img src="../../<?php echo htmlspecialchars($v['Image_Path']); ?>" alt="<?php echo htmlspecialchars($v['Vehicle_Name']); ?>" class="fleet-thumb-img" onerror="this.onerror=null; this.src='../../assets/images/bikes/default-bike.jpg';"></td>
                        <td><?php echo htmlspecialchars($v['Vehicle_Code']); ?></td>
                        <td><?php echo htmlspecialchars($v['Vehicle_Name']); ?></td>
                        <td><?php echo htmlspecialchars($v['Vehicle_Type']); ?></td>
                        <td><?php echo htmlspecialchars($v['Battery_Backup']); ?></td>
                        <td><?php echo $v['Top_Speed']; ?> km/h</td>
                        <td>
                            <form method="post" action="../../controller/VehicleController.php" class="inline-form">
                                <input type="hidden" name="action" value="updateStatus">
                                <input type="hidden" name="vehicleId" value="<?php echo $v['Vehicle_ID']; ?>">
                                <input type="hidden" name="stationId" value="<?php echo $station['Station_ID']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Available" <?php echo $v['Status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                                    <option value="Booked" <?php echo $v['Status'] === 'Booked' ? 'selected' : ''; ?>>Booked</option>
                                    <option value="In Ride" <?php echo $v['Status'] === 'In Ride' ? 'selected' : ''; ?>>In Ride</option>
                                    <option value="Maintenance" <?php echo $v['Status'] === 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="../../controller/ManagerController.php" class="inline-form">
                                <input type="hidden" name="action" value="moveVehicle">
                                <input type="hidden" name="vehicleId" value="<?php echo $v['Vehicle_ID']; ?>">
                                <input type="hidden" name="stationId" value="<?php echo $station['Station_ID']; ?>">
                                <select name="newStationId" onchange="this.form.submit()">
                                    <option value="">Move to...</option>
                                    <?php foreach ($allStations as $s): if ($s['Station_ID'] != $v['Station_ID']): ?>
                                        <option value="<?php echo $s['Station_ID']; ?>"><?php echo htmlspecialchars($s['Station_Name']); ?></option>
                                    <?php endif; endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="../../controller/VehicleController.php" class="inline-form" onsubmit="return confirm('Remove this bike from the fleet?')">
                                <input type="hidden" name="action" value="deleteVehicle">
                                <input type="hidden" name="vehicleId" value="<?php echo $v['Vehicle_ID']; ?>">
                                <input type="hidden" name="stationId" value="<?php echo $station['Station_ID']; ?>">
                                <button type="submit" class="btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
    <?php endif; ?>
</main>

</div>
</div>
</body>
</html>
