<?php
require_once __DIR__ . '/../includes/auth.php';

$error = '';
$info  = '';
$email = '';
$role  = 'patient';

if (isset($_GET['timeout'])) {
    $info = 'Please log in to continue.';
}
if (isset($_GET['registered'])) {
    $info = 'Registration successful. You can now log in.';
}
if (isset($_GET['denied'])) {
    $error = 'You do not have access to that page.';
}

// Already logged in? Go to the dashboard (unless they were just denied a page)
if (touchSession() && !isset($_GET['denied'])) {
    header('Location: ' . dashboardUrl(currentRole()));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role     = $_POST['role'] ?? 'patient';
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } else {
        switch ($role) {
            case 'patient':
                $result = (new Patient())->login($email, $password);
                break;
            case 'doctor':
                $result = (new Doctor())->login($email, $password);
                break;
            case 'admin':
                $result = (new Admin())->login($email, $password);
                break;
            default:
                $result = ['success' => false, 'message' => 'Invalid role selected.'];
        }

        if ($result['success']) {
            header('Location: ' . dashboardUrl($role));
            exit;
        }
        $error = $result['message'];
        $info  = '';
    }
}

View::render('auth/login', [
    'pageTitle'    => 'Login',
    'error'        => $error,
    'info'         => $info,
    'email'        => $email,
    'selectedRole' => $role,
]);
