<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('doctor');

$doctorObj = new Doctor();
$doctorId  = currentUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $result = ['success' => false, 'message' => 'Unknown action.'];

    if ($action === 'add') {
        $result = $doctorObj->addAvailability(
            $doctorId,
            $_POST['day_of_week'] ?? '',
            $_POST['start_time'] ?? '',
            $_POST['end_time'] ?? '',
            (int) ($_POST['slot_duration'] ?? 30)
        );
    } elseif ($action === 'delete') {
        $result = $doctorObj->deleteAvailability((int) ($_POST['availability_id'] ?? 0), $doctorId);
    }

    setFlash($result['success'] ? 'success' : 'error', $result['message']);
    header('Location: availability.php');
    exit;
}

View::render('doctor/availability', [
    'pageTitle' => 'My Availability',
    'slots'     => $doctorObj->getAvailability($doctorId),
    'days'      => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    'durations' => [15, 20, 30, 45, 60],
]);
