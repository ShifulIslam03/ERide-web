<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
// Expects $activePage to be set by the including view (e.g. 'home', 'rent', 'settings').
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$active = isset($activePage) ? $activePage : '';

if ($role === 'Customer') {
    $navItems = [
        ['key' => 'home',     'label' => 'Home',     'href' => 'dashboard.php',   'icon' => 'home'],
        ['key' => 'rent',     'label' => 'Rent',      'href' => 'bookBike.php',    'icon' => 'bike'],
        ['key' => 'rides',    'label' => 'My Rides',  'href' => 'myBookings.php',  'icon' => 'list'],
        ['key' => 'history',  'label' => 'History',   'href' => 'rideHistory.php', 'icon' => 'clock'],
        ['key' => 'settings', 'label' => 'Settings',  'href' => 'profile.php',     'icon' => 'gear'],
    ];
} elseif ($role === 'Manager') {
    $navItems = [
        ['key' => 'home',         'label' => 'Home',         'href' => 'dashboard.php',   'icon' => 'home'],
        ['key' => 'station',      'label' => 'Station',      'href' => 'station.php',     'icon' => 'building'],
        ['key' => 'vehicles',     'label' => 'Vehicles',     'href' => 'vehicles.php',    'icon' => 'bike'],
        ['key' => 'availability', 'label' => 'Availability', 'href' => 'availability.php','icon' => 'chart'],
        ['key' => 'settings',     'label' => 'Settings',     'href' => 'profile.php',     'icon' => 'gear'],
    ];
} elseif ($role === 'Admin') {
    $navItems = [
        ['key' => 'home',         'label' => 'Home',         'href' => 'dashboard.php',        'icon' => 'home'],
        ['key' => 'users',        'label' => 'Users',        'href' => 'users.php',             'icon' => 'users'],
        ['key' => 'requests',     'label' => 'Requests',     'href' => 'managerRequests.php',   'icon' => 'inbox'],
        ['key' => 'stations',     'label' => 'Stations',     'href' => 'stations.php',          'icon' => 'building'],
        ['key' => 'payments',     'label' => 'Payments',     'href' => 'payments.php',          'icon' => 'wallet'],
        ['key' => 'transactions', 'label' => 'Transactions', 'href' => 'transactions.php',      'icon' => 'receipt'],
        ['key' => 'settings',     'label' => 'Settings',     'href' => 'profile.php',           'icon' => 'gear'],
    ];
} else {
    $navItems = [];
}

function sidebarIconPath($name) {
    $icons = [
        'home'     => '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/>',
        'bike'     => '<circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="M6 17l4-9h4l3 5"/><path d="M10 8h4"/>',
        'list'     => '<path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'gear'     => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.34 1.87l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.7 1.7 0 00-1.87-.34 1.7 1.7 0 00-1 1.55V21a2 2 0 11-4 0v-.09A1.7 1.7 0 009 19.4a1.7 1.7 0 00-1.87.34l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.7 1.7 0 004.6 15a1.7 1.7 0 00-1.55-1H3a2 2 0 110-4h.09A1.7 1.7 0 004.6 9a1.7 1.7 0 00-.34-1.87l-.06-.06a2 2 0 112.83-2.83l.06.06A1.7 1.7 0 009 4.6a1.7 1.7 0 001-1.55V3a2 2 0 114 0v.09a1.7 1.7 0 001 1.55 1.7 1.7 0 001.87-.34l.06-.06a2 2 0 112.83 2.83l-.06.06A1.7 1.7 0 0019.4 9a1.7 1.7 0 001.55 1H21a2 2 0 110 4h-.09a1.7 1.7 0 00-1.51 1z"/>',
        'building' => '<rect x="4" y="3" width="16" height="18"/><path d="M9 21v-4h6v4"/><path d="M9 7h1M14 7h1M9 11h1M14 11h1"/>',
        'chart'    => '<path d="M4 20V10"/><path d="M12 20V4"/><path d="M20 20v-7"/>',
        'users'    => '<circle cx="9" cy="8" r="3"/><path d="M2 20c0-3 3-5 7-5s7 2 7 5"/><circle cx="17" cy="9" r="2.3"/><path d="M16 14.2c2.3.4 3.7 1.9 3.7 3.8"/>',
        'inbox'    => '<path d="M3 12h4l2 3h6l2-3h4"/><path d="M5 5h14l2 7v7H3v-7z"/>',
        'wallet'   => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><circle cx="17" cy="14" r="1"/>',
        'receipt'  => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/>',
    ];
    return isset($icons[$name]) ? $icons[$name] : '';
}
?>
<aside class="sidebar">
    <div class="sidebar-logo">
        <span class="logo-badge">E</span>
        <span>-Ride<br>Services</span>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($navItems as $item): ?>
            <a href="<?php echo $item['href']; ?>" class="<?php echo ($active === $item['key']) ? 'active' : ''; ?>">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo sidebarIconPath($item['icon']); ?></svg>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-logout-wrap">
        <a href="../../controller/LogoutController.php" class="btn-primary sidebar-logout">Log out</a>
    </div>
</aside>
