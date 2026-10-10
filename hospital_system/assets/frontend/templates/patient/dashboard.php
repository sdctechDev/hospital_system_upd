<?php $total = array_sum($counts); ?>
<header class="page-head">
    <div>
        <h1 class="page-title"><?= e(greeting()) ?>, <?= e(firstName($_SESSION['user_name'] ?? '')) ?></h1>
        <?php if ($upcoming): ?>
            <p class="page-sub">You have <?= count($upcoming) ?> upcoming <?= count($upcoming) === 1 ? 'visit' : 'visits' ?>. Unpaid ones are held until you pay.</p>
        <?php else: ?>
            <p class="page-sub">Nothing on your calendar yet. Find a doctor and book your first visit.</p>
        <?php endif; ?>
    </div>
    <div class="page-actions">
        <a class="btn" href="book.php"><?= icon('calendar-plus', 18) ?>Book appointment</a>
        <a class="btn btn-outline" href="doctors.php">Browse doctors</a>
    </div>
</header>

<div class="split">
    <section aria-labelledby="up-title">
        <div class="panel-head">
            <h2 id="up-title">Coming up</h2>
            <?php if ($upcoming): ?><a class="link" href="appointments.php">All appointments</a><?php endif; ?>
        </div>

        <?php if ($upcoming): ?>
            <div class="appt-list">
                <?php foreach ($upcoming as $a): ?>
                    <article class="appt">
                        <?= dateBlock($a['appointment_date']) ?>
                        <div class="appt-main">
                            <div class="appt-title">Dr. <?= e($a['doctor_name']) ?> <?= badge($a['status']) ?></div>
                            <div class="appt-meta">
                                <span><?= icon('stethoscope', 14) ?><?= e($a['specialization']) ?></span>
                                <span><?= icon('clock', 14) ?><?= ftime($a['appointment_time']) ?></span>
                            </div>
                        </div>
                        <div class="appt-actions">
                            <?php if ($a['status'] === 'pending' && !$a['is_paid']): ?>
                                <a class="btn btn-sm" href="pay.php?appointment_id=<?= (int) $a['appointment_id'] ?>">Pay now</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <h3>No upcoming appointments</h3>
                <p>Pick a doctor and a time that suits you.</p>
                <a class="btn" href="book.php">Book appointment</a>
            </div>
        <?php endif; ?>
    </section>

    <aside class="panel" aria-labelledby="st-title">
        <div class="panel-head">
            <div>
                <h2 id="st-title">Your visits</h2>
                <p class="panel-sub"><?= (int) $total ?> in total</p>
            </div>
        </div>
        <?php foreach ($counts as $status => $n): ?>
            <div class="meter-row">
                <span class="stat-label"><span class="dot dot-<?= e($status) ?>"></span><?= e($status) ?></span>
                <strong class="num"><?= (int) $n ?></strong>
                <div class="meter"><i class="dot-<?= e($status) ?>" style="width: <?= $total ? round($n / $total * 100) : 0 ?>%"></i></div>
            </div>
        <?php endforeach; ?>
    </aside>
</div>
