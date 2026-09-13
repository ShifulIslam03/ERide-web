<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Station.php';
require_once '../../model/BikeType.php';
$stations = getStationByManager($_SESSION['phone']);
$bikeTypes = getBikeTypes();
$stationId = isset($_GET['id']) ? intval($_GET['id']) : (!empty($stations) ? $stations[0]['Station_ID'] : 0);
$station = getManagerStation($_SESSION['phone'], $stationId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Bike - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/manager.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'vehicles'; include '../Sidebar.php'; ?>
<div class="main-content">

<main class="page-container">
    <a href="vehicles.php<?php echo $station ? '?id=' . $station['Station_ID'] : ''; ?>" class="back-link btn-secondary btn-sm">&larr; Back</a>
    <h1>Add New Bike(s)</h1>

    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <?php if (!$station): ?>
        <section class="card">
            <p>You are not assigned to a station yet. Please contact the Admin.</p>
        </section>
    <?php else: ?>
        <section class="card">
            <h2>Add Bikes by Bike Type</h2>
            <p>Pick one of the predefined bike types and a quantity to add that many bikes to the station at once.</p>
            <form method="post" action="../../controller/VehicleController.php" class="bike-type-form">
                <input type="hidden" name="action" value="addVehiclesByType">
                <input type="hidden" name="stationId" value="<?php echo $station['Station_ID']; ?>">

                <div class="form-group">
                    <label for="bikeTypeName">Bike Type</label>
                    <select name="bikeTypeName" id="bikeTypeName">
                        <?php foreach ($bikeTypes as $bt): ?>
                            <option value="<?php echo htmlspecialchars($bt['Type_Name']); ?>"><?php echo htmlspecialchars($bt['Type_Name']); ?> (<?php echo htmlspecialchars($bt['Bike_Name']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bikeTypeQuantity">Quantity</label>
                    <input type="number" min="1" max="50" step="1" name="quantity" id="bikeTypeQuantity" value="1">
                </div>

                <button type="submit" class="btn-primary">Add Bike(s) to Station</button>
            </form>
        </section>
    <?php endif; ?>
</main>

</div>
</div>
</body>
</html>
