<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('doctor');

$doctorId = currentUserId();
$apptObj  = new Appointment();

View::render('doctor/dashboard', [
    'pageTitle' => 'Doctor Dashboard',
    'scripts'   => ['status.js'],
    'counts'    => $apptObj->countByStatus($doctorId),
    'today'     => $apptObj->getByDoctor($doctorId, date('Y-m-d')),
]);
