<?php
require_once __DIR__ . '/../includes/auth.php';

if (touchSession()) {
    header('Location: ' . dashboardUrl(currentRole()));
    exit;
}

$error = '';
$old = [
    'full_name' => '', 'email' => '', 'phone' => '',
    'gender' => '', 'date_of_birth' => '', 'address' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $key => $_) {
        $old[$key] = trim($_POST[$key] ?? '');
    }
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $data = $old;
        $data['password'] = $password;
        $result = (new Patient())->register($data);

        if ($result['success']) {
            header('Location: login.php?registered=1');
            exit;
        }
        $error = $result['message'];
    }
}

View::render('auth/register', [
    'pageTitle' => 'Register',
    'error'     => $error,
    'old'       => $old,
    'today'     => date('Y-m-d'),
]);
