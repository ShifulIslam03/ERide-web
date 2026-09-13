<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Ride.php';
require_once '../../model/Station.php';

$phone = $_SESSION['phone'];
$activeRide = getActiveRide($phone);
$stations = getStations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Home - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/customer.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'home'; include '../Sidebar.php'; ?>
<div class="main-content">
<main class="page-container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

    <?php if (!empty($_SESSION['successMsg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['successMsg']; $_SESSION['successMsg'] = ''; ?></p>
    <?php endif; ?>

    <?php if ($activeRide): ?>
        <div class="alert-card">
            <p>You have an ongoing ride with <?php echo count($activeRide['Vehicles']); ?> bike(s) from <?php echo htmlspecialchars($activeRide['Start_Station_Name']); ?>.</p>
            <a href="ride.php" class="btn-primary">Go to My Ride</a>
        </div>
    <?php endif; ?>

    <a href="bookBike.php" class="rent-tile">
        
        <span>Rent a Bike</span>
    </a>

    <section class="card">
        <h2>Available Stations</h2>
        <div class="station-grid" id="stationGrid">
            <?php foreach ($stations as $s): ?>
                <div class="station-card" data-station-id="<?php echo $s['Station_ID']; ?>">
                    <h3><?php echo htmlspecialchars($s['Station_Name']); ?></h3>
                    <p><?php echo htmlspecialchars($s['Area']); ?>, <?php echo htmlspecialchars($s['Block']); ?></p>
                    <p>Available Bikes: <span class="availability-count"><?php echo $s['Available_Cycle_Quantity']; ?></span> / <?php echo $s['Capacity']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</div>
</div>
</body>
</html>
