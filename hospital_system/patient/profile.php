<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('patient');

$patientObj = new Patient();
$id = currentUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $patientObj->updateProfile($id, $_POST);
    setFlash($result['success'] ? 'success' : 'error', $result['message']);
    header('Location: profile.php');
    exit;
}

View::render('patient/profile', [
    'pageTitle' => 'Profile',
    'p'         => $patientObj->getById($id),
]);
