<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('patient');

$patientId = currentUserId();
$appointments = (new Appointment())->getByPatient($patientId);

// Map appointment -> its paid (or refunded) payment, for the receipt link
$receipts = [];
foreach ((new Payment())->getByPatient($patientId) as $p) {
    if (in_array($p['status'], ['success', 'refunded'], true)) {
        $receipts[$p['appointment_id']] = $p['payment_id'];
    }
}

View::render('patient/appointments', [
    'pageTitle'    => 'My Appointments',
    'scripts'      => ['ui.js', 'appointments.js'],
    'appointments' => $appointments,
    'receipts'     => $receipts,
]);
