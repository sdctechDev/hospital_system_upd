<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

$doctorObj = new Doctor();
$specs  = $doctorObj->getSpecializations();
$error  = '';
$form   = [];
$editId = (int) ($_GET['edit'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $result = ['success' => false, 'message' => 'Unknown action.'];

    if ($action === 'add') {
        $result = $doctorObj->create($_POST);
    } elseif ($action === 'update') {
        $editId = (int) ($_POST['doctor_id'] ?? 0);
        $result = $doctorObj->update($editId, $_POST);
    } elseif ($action === 'status') {
        $result = $doctorObj->setStatus((int) ($_POST['doctor_id'] ?? 0), $_POST['status'] ?? '');
    }

    if ($result['success']) {
        setFlash('success', $result['message']);
        header('Location: doctors.php');
        exit;
    }
    // Failed: stay on the page and keep what was typed
    $error = $result['message'];
    $form  = $_POST;
}

if ($editId && !$form) {
    $form = $doctorObj->getById($editId) ?? [];
    if (!$form) {
        $editId = 0;
    }
}

View::render('admin/doctors', [
    'pageTitle' => 'Manage Doctors',
    'specs'     => $specs,
    'doctors'   => (new Admin())->getAllDoctors(),
    'form'      => $form,
    'error'     => $error,
    'editId'    => $editId,
    'isEdit'    => $editId > 0,
]);
