<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('patient');

View::render('patient/book', [
    'pageTitle' => 'Book Appointment',
    'scripts'   => ['ui.js', 'book.js'],
    'doctors'   => (new Doctor())->getAll(),
    'selected'  => (int) ($_GET['doctor_id'] ?? 0),
    'today'     => date('Y-m-d'),
]);
