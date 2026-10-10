<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('patient');

$apptObj = new Appointment();
$appt = $apptObj->getById((int) ($_GET['appointment_id'] ?? 0));

if (!$appt || (int) $appt['patient_id'] !== currentUserId()) {
    setFlash('error', 'Appointment not found.');
    header('Location: appointments.php');
    exit;
}
if ($appt['status'] !== 'pending' || $apptObj->isPaid((int) $appt['appointment_id'])) {
    setFlash('error', 'This appointment is not awaiting payment.');
    header('Location: appointments.php');
    exit;
}

View::render('patient/pay', [
    'pageTitle' => 'Payment',
    'scripts'   => ['ui.js', 'pay.js'],
    'appt'      => $appt,
]);
