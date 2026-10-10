<?php $total = array_sum($counts); ?>
<header class="page-head">
    <div>
        <h1 class="page-title"><?= e(greeting()) ?>, Dr. <?= e(firstName($_SESSION['user_name'] ?? '')) ?></h1>
        <p class="page-sub"><?= fdate(date('Y-m-d'), 'l, d F') ?>.
            <?= count($today) ? 'You have ' . count($today) . ' ' . (count($today) === 1 ? 'appointment' : 'appointments') . ' today.' : 'No appointments today.' ?></p>
    </div>
    <div class="page-actions">
        <a class="btn btn-outline" href="availability.php"><?= icon('clock', 18) ?>Edit availability</a>
    </div>
</header>

<div class="stats stats--4">
    <?php foreach ($counts as $status => $n): ?>
        <div class="stat">
            <div class="stat-label"><span class="dot dot-<?= e($status) ?>"></span><?= e($status) ?></div>
            <div class="stat-num"><?= (int) $n ?></div>
        </div>
    <?php endforeach; ?>
</div>

<section class="mt-3" aria-labelledby="today-title">
    <div class="panel-head"><h2 id="today-title">Today's schedule</h2><a class="link" href="appointments.php">All appointments</a></div>

    <?php if ($today): ?>
        <div class="appt-list">
            <?php foreach ($today as $a): ?>
                <article class="appt">
                    <div class="appt-date is-today">
                        <span class="appt-day" style="font-size:1.125rem"><?= ftime($a['appointment_time']) ?></span>
                        <span class="appt-mon">today</span>
                    </div>
                    <div class="appt-main">
                        <div class="appt-title"><?= e($a['patient_name']) ?> <?= badge($a['status']) ?></div>
                        <div class="appt-meta">
                            <?php if ($a['patient_phone']): ?><span><?= e($a['patient_phone']) ?></span><?php endif; ?>
                            <span><?= $a['is_paid'] ? 'Paid' : 'Not paid yet' ?></span>
                        </div>
                        <?php if ($a['reason']): ?><p class="muted" style="margin-top:6px"><?= e($a['reason']) ?></p><?php endif; ?>
                    </div>
                    <div class="appt-actions">
                        <?php if ($a['status'] === 'confirmed'): ?>
                            <button type="button" class="btn btn-sm" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="completed"><?= icon('check', 16) ?>Complete</button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty"><h3>A clear day</h3><p>No appointments are booked for today.</p></div>
    <?php endif; ?>
</section>
