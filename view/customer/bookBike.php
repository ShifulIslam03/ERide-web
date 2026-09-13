<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../../model/Vehicle.php';
require_once '../../model/Ride.php';
require_once '../../model/Station.php';

$phone = $_SESSION['phone'];
$activeRide = getActiveRide($phone);

// Step 1: the customer picks a Start Station. Step 2 (below) then shows the
// bike catalog for that specific station only.
$stationId = isset($_GET['stationId']) ? intval($_GET['stationId']) : 0;
$station = $stationId ? getStation($stationId) : null;

if ($stationId && !$station) {
    header("Location: bookBike.php");
    exit();
}

$stations = [];
$catalog = [];
if ($station) {
    $catalog = getVehicleCatalogByStation($station['Station_ID']);
} else {
    foreach (getStations() as $s) {
        if ($s['Status'] === 'Active') {
            $stations[] = $s;
        }
    }
}

// Simple stock icon/description text per vehicle type, used only when a catalog
// entry doesn't already convey enough detail in its own fields.
function catalogBlurb($type) {
    $blurbs = [
        'E-Bike'  => 'Best for: City riding & daily commute',
        'Scooter' => 'Best for: Quick last-mile trips',
    ];
    return isset($blurbs[$type]) ? $blurbs[$type] : 'Best for: Short-distance city travel';
}

// Default placeholder shown if a bike's catalog image path is missing or the
// file hasn't been added to assets/images/bikes/ yet.
define('DEFAULT_BIKE_IMAGE', 'assets/images/bikes/default-bike.jpg');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rent a Bike - E-Ride</title>
<link rel="stylesheet" href="../../css/style.css">
<link rel="stylesheet" href="../../css/customer.css">
</head>
<body>
<div class="app-shell">
<?php $activePage = 'rent'; include '../Sidebar.php'; ?>
<div class="main-content">
<main class="page-container">
    <h1>Rent a Bike</h1>

    <?php if ($activeRide): ?>
        <div class="alert-card">
            <p>You have an ongoing ride with <?php echo count($activeRide['Vehicles']); ?> bike(s). Finish it before renting more.</p>
            <a href="ride.php" class="btn-primary">Go to My Ride</a>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['globalErrMsg'])): ?>
        <p class="error-msg"><?php echo $_SESSION['globalErrMsg']; $_SESSION['globalErrMsg'] = ''; ?></p>
    <?php endif; ?>

    <?php if (!$station): ?>
        <!-- Step 1: choose a Start Station -->
        <section class="card">
            <h2>Select a Start Station</h2>
            <?php if (empty($stations)): ?>
                <p>No active stations are available right now.</p>
            <?php else: ?>
                <div class="station-grid" id="stationGrid">
                    <?php foreach ($stations as $s): ?>
                        <a href="bookBike.php?stationId=<?php echo $s['Station_ID']; ?>" class="station-card station-card-link" data-station-id="<?php echo $s['Station_ID']; ?>">
                            <h3><?php echo htmlspecialchars($s['Station_Name']); ?></h3>
                            <p><?php echo htmlspecialchars($s['Area']); ?>, <?php echo htmlspecialchars($s['Block']); ?></p>
                            <p>Available Bikes: <span class="availability-count"><?php echo $s['Available_Cycle_Quantity']; ?></span> / <?php echo $s['Capacity']; ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <!-- Step 2: station-wise bike catalog, grouped by Bike Name -->
        <a href="bookBike.php" class="back-link btn-secondary btn-sm">&larr; Change Station</a>
        <h2><?php echo htmlspecialchars($station['Station_Name']); ?> &mdash; Available Bikes</h2>

        <?php if (empty($catalog)): ?>
            <section class="card">
                <p>No bikes are available at this station right now. Please try another station.</p>
            </section>
        <?php else: ?>
            <div class="catalog-grid">
                <?php foreach ($catalog as $i => $c): ?>
                    <div class="catalog-card">
                        <div class="catalog-thumb">
                            <img src="../../<?php echo htmlspecialchars(!empty($c['Image_Path']) ? $c['Image_Path'] : DEFAULT_BIKE_IMAGE); ?>" alt="<?php echo htmlspecialchars($c['Vehicle_Name']); ?>" class="catalog-thumb-img" onerror="this.onerror=null; this.src='../../<?php echo DEFAULT_BIKE_IMAGE; ?>';">
                        </div>
                        <h3><?php echo htmlspecialchars($c['Vehicle_Name']); ?></h3>
                        <p class="catalog-meta"><?php echo htmlspecialchars($c['Vehicle_Type']); ?> &middot; <?php echo $c['Available_Count']; ?> available</p>

                        <div class="catalog-actions">
                            <form method="post" action="../../controller/RideController.php" class="inline-form">
                                <input type="hidden" name="action" value="quickStart">
                                <input type="hidden" name="stationId" value="<?php echo $station['Station_ID']; ?>">
                                <input type="hidden" name="vehicleType" value="<?php echo htmlspecialchars($c['Vehicle_Name']); ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-primary btn-sm <?php echo $activeRide ? 'btn-disabled' : ''; ?>"
                                    <?php echo $activeRide ? 'disabled' : ''; ?>>Start Ride</button>
                            </form>
                            <button type="button" class="btn-secondary btn-sm" onclick="toggleDetails(<?php echo $i; ?>)">Details</button>
                        </div>

                        <div class="details-popover" id="details-<?php echo $i; ?>">
                            <strong>Details</strong>
                            <ol>
                                <li>Name: <?php echo htmlspecialchars($c['Vehicle_Name']); ?></li>
                                <li>Type: <?php echo htmlspecialchars($c['Vehicle_Type']); ?></li>
                                <li>Battery Backup: <?php echo htmlspecialchars($c['Battery_Backup']); ?></li>
                                <li>Top Speed: <?php echo $c['Top_Speed']; ?> km/h</li>
                                <li><?php echo catalogBlurb($c['Vehicle_Type']); ?></li>
                            </ol>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>
</div>
</div>
<script>
function toggleDetails(i) {
    var el = document.getElementById('details-' + i);
    var isOpen = el.classList.contains('open');
    document.querySelectorAll('.details-popover.open').forEach(function (p) { p.classList.remove('open'); });
    if (!isOpen) el.classList.add('open');
}
</script>
</body>
</html>
