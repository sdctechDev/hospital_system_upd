<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

$statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
$filter   = $_GET['status'] ?? '';
if (!in_array($filter, $statuses, true)) {
    $filter = '';
}

View::render('admin/appointments', [
    'pageTitle'    => 'Appointments',
    'scripts'      => ['status.js'],
    'statuses'     => $statuses,
    'filter'       => $filter,
    'appointments' => (new Appointment())->getAll($filter ?: null),
]);
