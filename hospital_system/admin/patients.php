<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

View::render('admin/patients', [
    'pageTitle' => 'Patients',
    'patients'  => (new Admin())->getAllPatients(),
]);
