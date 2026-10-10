<?php $total = array_sum($stats['by_status']); ?>
<header class="page-head">
    <div>
        <h1 class="page-title">Overview</h1>
        <p class="page-sub">How the hospital is doing right now.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-outline" href="doctors.php"><?= icon('plus', 18) ?>Add doctor</a>
        <a class="btn" href="appointments.php">Manage appointments</a>
    </div>
</header>

<div class="split split--aside split--stretch">
    <section class="panel hero-stat" aria-labelledby="rev-title">
        <div>
            <h2 id="rev-title">Revenue</h2>
            <p class="panel-sub">Simulated payments, test data only</p>
        </div>
        <div class="stat-num num"><?= money($stats['revenue']) ?></div>
    </section>

    <section class="panel" aria-labelledby="fail-title">
        <h2 id="fail-title">Failed payments</h2>
        <div class="callout-num mt-2 <?= $stats['failed_payments'] ? 'is-bad' : '' ?> num"><?= (int) $stats['failed_payments'] ?></div>
        <?php if ($stats['failed_payments']): ?>
            <p class="panel-sub">These patients may need help completing payment.</p>
            <a class="btn btn-outline btn-sm mt" href="payments.php?status=failed">Review failed payments</a>
        <?php else: ?>
            <p class="panel-sub">Every payment attempt has gone through.</p>
        <?php endif; ?>
    </section>
</div>

<div class="stats stats--3 mt-3">
    <div class="stat"><div class="stat-label"><?= icon('users', 16) ?>Patients</div><div class="stat-num"><?= (int) $stats['patients'] ?></div></div>
    <div class="stat"><div class="stat-label"><?= icon('stethoscope', 16) ?>Doctors</div><div class="stat-num"><?= (int) $stats['doctors'] ?></div></div>
    <div class="stat"><div class="stat-label"><?= icon('calendar', 16) ?>Appointments</div><div class="stat-num"><?= (int) $stats['appointments'] ?></div></div>
</div>

<section class="panel mt-3" aria-labelledby="dist-title">
    <div class="panel-head">
        <div><h2 id="dist-title">Appointments by status</h2><p class="panel-sub"><?= (int) $total ?> in total</p></div>
        <a class="link" href="appointments.php">View all</a>
    </div>
    <div class="dist" role="img" aria-label="Appointments by status">
        <?php foreach ($stats['by_status'] as $status => $n): ?>
            <?php if ($n > 0): ?><i class="dot-<?= e($status) ?>" style="flex: <?= (int) $n ?>"></i><?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="legend">
        <?php foreach ($stats['by_status'] as $status => $n): ?>
            <a class="legend-item" href="appointments.php?status=<?= e($status) ?>">
                <span class="stat-label"><span class="dot dot-<?= e($status) ?>"></span><?= e($status) ?></span>
                <b><?= (int) $n ?></b>
            </a>
        <?php endforeach; ?>
    </div>
</section>
