<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('doctor');

$date = $_GET['date'] ?? '';
$d = DateTime::createFromFormat('Y-m-d', $date);
if (!$d || $d->format('Y-m-d') !== $date) {
    $date = '';
}

View::render('doctor/appointments', [
    'pageTitle'    => 'My Appointments',
    'scripts'      => ['status.js'],
    'date'         => $date,
    'appointments' => (new Appointment())->getByDoctor(currentUserId(), $date ?: null),
]);
