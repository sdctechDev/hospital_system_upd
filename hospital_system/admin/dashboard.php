<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

View::render('admin/dashboard', [
    'pageTitle' => 'Admin Dashboard',
    'stats'     => (new Admin())->getStats(),
]);
