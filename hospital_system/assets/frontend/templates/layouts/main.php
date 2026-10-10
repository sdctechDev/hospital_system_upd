<?php
// Main page layout. $viewFile is the page template; $pageTitle and $scripts come from the PHP page.
// Signed-in users get the app shell (floating nav + tab bar). Guests get the split sign-in screen.
$styles    = ['variables', 'base', 'layout', 'components', 'pages', 'print'];
$navRole   = currentRole();
$isGuest   = !$navRole;
$authWide  = basename($_SERVER['SCRIPT_NAME'] ?? '') === 'register.php';
// ui.js is always loaded first, so pages that still list it are de-duplicated here.
$scripts   = array_values(array_filter($scripts ?? [], fn($s) => $s !== 'ui.js'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0B0F19">
    <title><?= e($pageTitle ?? 'Hospital System') ?> | Hospital System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Inter:wght@400;500&family=Red+Hat+Display:wght@500;600;700&display=swap">
    <?php foreach ($styles as $css): ?>
        <link rel="stylesheet" href="<?= FRONTEND_URL ?>css/<?= $css ?>.css">
    <?php endforeach; ?>
    <script>try{var t=localStorage.getItem("hs-theme");if(t)document.documentElement.setAttribute("data-theme",t);}catch(e){}</script>
    <script>window.APP = { baseUrl: "<?= BASE_URL ?>" };</script>
</head>
<body class="<?= $isGuest ? 'is-guest' : 'is-app role-' . e($navRole) ?>">
<?php if ($isGuest): ?>
    <div class="auth">
        <?php View::partial('auth-hero'); ?>
        <main id="main" class="auth-panel">
            <button type="button" class="icon-btn theme-toggle auth-theme" data-theme-toggle aria-label="Switch colour theme"><?= icon('sun', 18) ?><?= icon('moon', 18) ?></button>
            <div class="auth-box <?= $authWide ? 'auth-box--wide' : '' ?>">
                <a class="brand auth-brand-mobile" href="<?= BASE_URL ?>"><?= brandMark() ?><span>Hospital <span>System</span></span></a>
                <?php View::partial('flash', ['flash' => $flash ?? null]); ?>
                <?php require $viewFile; ?>
            </div>
        </main>
    </div>
<?php else: ?>
    <?php View::partial('nav'); ?>
    <main id="main" class="page">
        <?php View::partial('flash', ['flash' => $flash ?? null]); ?>
        <?php require $viewFile; ?>
    </main>
<?php endif; ?>
<div class="toasts" id="toasts" aria-live="polite"></div>
<script src="<?= FRONTEND_URL ?>js/ui.js"></script>
<?php foreach ($scripts as $script): ?>
    <script src="<?= FRONTEND_URL ?>js/<?= e($script) ?>"></script>
<?php endforeach; ?>
</body>
</html>
