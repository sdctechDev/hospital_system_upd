<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

$filter = $_GET['status'] ?? '';
if (!in_array($filter, ['success', 'failed', 'refunded'], true)) {
    $filter = '';
}

$paymentObj = new Payment();

View::render('admin/payments', [
    'pageTitle' => 'Payments',
    'filter'    => $filter,
    'revenue'   => $paymentObj->totalRevenue(),
    'payments'  => $paymentObj->getAll($filter ?: null),
]);
