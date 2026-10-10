<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('patient');

$r = (new Payment())->getReceipt((int) ($_GET['payment_id'] ?? 0), currentUserId());
if (!$r) {
    setFlash('error', 'Receipt not found.');
    header('Location: appointments.php');
    exit;
}

View::render('patient/receipt', [
    'pageTitle' => 'Receipt',
    'r'         => $r,
]);
