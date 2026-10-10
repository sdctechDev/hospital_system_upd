<?php
// Role-based navigation. Each item: [file, label, icon, short label for the mobile tab bar]
$navRole  = currentRole();
$script   = basename($_SERVER['SCRIPT_NAME'] ?? '');
$userName = $_SESSION['user_name'] ?? '';
$dir      = $navRole . '/';

if ($navRole === 'patient') {
    $items = [
        ['dashboard.php',    'Home',         'home',          'Home'],
        ['doctors.php',      'Doctors',      'stethoscope',   'Doctors'],
        ['book.php',         'Book',         'calendar-plus', 'Book'],
        ['appointments.php', 'Appointments', 'calendar',      'Visits'],
        ['profile.php',      'Profile',      'user',          'Profile'],
    ];
    $also = ['appointments.php' => ['pay.php', 'receipt.php']];
} elseif ($navRole === 'doctor') {
    $items = [
        ['dashboard.php',    'Home',         'home',     'Home'],
        ['appointments.php', 'Appointments', 'calendar', 'Visits'],
        ['availability.php', 'Availability', 'clock',    'Hours'],
    ];
    $also = [];
} else {
    $items = [
        ['dashboard.php',       'Overview',        'home',        'Home'],
        ['doctors.php',         'Doctors',         'stethoscope', 'Doctors'],
        ['patients.php',        'Patients',        'users',       'Patients'],
        ['appointments.php',    'Appointments',    'calendar',    'Visits'],
        ['payments.php',        'Payments',        'credit-card', 'Pay'],
        ['specializations.php', 'Specializations', 'layers',      'Fields'],
    ];
    $also = [];
}
?>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="<?= e(dashboardUrl($navRole)) ?>" aria-label="Hospital System home">
            <?= brandMark() ?><span>Hospital <span>System</span></span>
        </a>

        <nav class="nav-main" aria-label="Primary">
            <ul>
                <?php foreach ($items as $it): ?>
                    <?php $active = $script === $it[0] || in_array($script, $also[$it[0]] ?? [], true); ?>
                    <li>
                        <a class="nav-link <?= $active ? 'is-active' : '' ?>" href="<?= BASE_URL . $dir . $it[0] ?>" <?= $active ? 'aria-current="page"' : '' ?>>
                            <?php if ($active): ?>
                                <svg class="nav-ring" viewBox="0 0 120 48" preserveAspectRatio="none" aria-hidden="true"><path pathLength="1" d="M10 27C6 11 40 4 68 5C98 6 117 14 113 28C109 41 80 45 52 44C24 43 4 36 9 18"/></svg>
                            <?php endif; ?>
                            <span class="nav-label"><?= e($it[1]) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="topbar-user">
            <div class="user-meta">
                <div class="user-name truncate"><?= e($userName) ?></div>
                <div class="user-role"><?= e($navRole) ?></div>
            </div>
            <button type="button" class="icon-btn theme-toggle" data-theme-toggle aria-label="Switch colour theme" title="Switch colour theme"><?= icon('sun', 18) ?><?= icon('moon', 18) ?></button>
            <span class="avatar" aria-hidden="true"><?= initials($userName) ?></span>
            <a class="icon-btn" href="<?= BASE_URL ?>auth/logout.php" aria-label="Log out" title="Log out"><?= icon('log-out', 18) ?></a>
        </div>
    </div>
</header>

<nav class="tabbar" aria-label="Primary">
    <?php foreach ($items as $it): ?>
        <?php $active = $script === $it[0] || in_array($script, $also[$it[0]] ?? [], true); ?>
        <a class="tab <?= $active ? 'is-active' : '' ?>" href="<?= BASE_URL . $dir . $it[0] ?>" <?= $active ? 'aria-current="page"' : '' ?>>
            <?= icon($it[2], 20) ?>
            <span class="lbl-short"><?= e($it[3]) ?></span>
        </a>
    <?php endforeach; ?>
</nav>
