<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

$admin  = new Admin();
$db     = Database::getInstance();
$form   = ['name' => '', 'description' => ''];
$editId = (int) ($_GET['edit'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $name   = $_POST['name'] ?? '';
    $desc   = $_POST['description'] ?? '';
    $result = ['success' => false, 'message' => 'Unknown action.'];

    if ($action === 'add') {
        $result = $admin->addSpecialization($name, $desc);
    } elseif ($action === 'update') {
        $editId = (int) ($_POST['specialization_id'] ?? 0);
        $result = $admin->updateSpecialization($editId, $name, $desc);
    } elseif ($action === 'delete') {
        $result = $admin->deleteSpecialization((int) ($_POST['specialization_id'] ?? 0));
    }

    setFlash($result['success'] ? 'success' : 'error', $result['message']);
    header('Location: specializations.php');
    exit;
}

if ($editId) {
    $row = $db->fetchOne("SELECT * FROM specializations WHERE specialization_id = ?", [$editId]);
    if ($row) {
        $form = $row;
    } else {
        $editId = 0;
    }
}

$list = $db->fetchAll(
    "SELECT s.*, (SELECT COUNT(*) FROM doctors d WHERE d.specialization_id = s.specialization_id) AS doctor_count
     FROM specializations s ORDER BY s.name"
);

View::render('admin/specializations', [
    'pageTitle' => 'Specializations',
    'list'      => $list,
    'form'      => $form,
    'editId'    => $editId,
    'isEdit'    => $editId > 0,
]);
