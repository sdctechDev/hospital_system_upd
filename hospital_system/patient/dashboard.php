<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('patient');

$all = (new Appointment())->getByPatient(currentUserId());

$counts = ['pending' => 0, 'confirmed' => 0, 'completed' => 0, 'cancelled' => 0];
$upcoming = [];
foreach ($all as $a) {
    $counts[$a['status']]++;
    if (in_array($a['status'], ['pending', 'confirmed'], true) && $a['appointment_date'] >= date('Y-m-d')) {
        $upcoming[] = $a;
    }
}
$upcoming = array_slice(array_reverse($upcoming), 0, 5);

View::render('patient/dashboard', [
    'pageTitle' => 'Dashboard',
    'counts'    => $counts,
    'upcoming'  => $upcoming,
]);
