<header class="page-head">
    <div>
        <h1 class="page-title">Appointments</h1>
        <p class="page-sub"><?= $date ? 'Showing ' . fdate($date, 'l, d F Y') . '.' : 'Showing every appointment, newest first.' ?></p>
    </div>
    <form method="get" class="page-actions" style="align-items:flex-end">
        <div class="form-group" style="margin:0">
            <label for="date">Filter by date</label>
            <input type="date" id="date" name="date" value="<?= e($date) ?>" data-autosubmit>
        </div>
        <?php if ($date): ?><a href="appointments.php" class="btn btn-outline">Show all</a><?php endif; ?>
    </form>
</header>

<?php if ($appointments): ?>
    <div class="appt-list">
        <?php foreach ($appointments as $a): ?>
            <article class="appt">
                <?= dateBlock($a['appointment_date']) ?>
                <div class="appt-main">
                    <div class="appt-title"><?= e($a['patient_name']) ?> <?= badge($a['status']) ?></div>
                    <div class="appt-meta">
                        <span><?= icon('clock', 14) ?><?= ftime($a['appointment_time']) ?></span>
                        <?php if ($a['patient_phone']): ?><span><?= e($a['patient_phone']) ?></span><?php endif; ?>
                        <span><?= icon('banknote', 14) ?><?= $a['is_paid'] ? 'Paid' : 'Not paid yet' ?></span>
                    </div>
                    <?php if ($a['reason']): ?><p class="muted" style="margin-top:6px"><?= e($a['reason']) ?></p><?php endif; ?>
                </div>
                <div class="appt-actions">
                    <?php if ($a['status'] === 'confirmed'): ?>
                        <button type="button" class="btn btn-sm" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="completed"><?= icon('check', 16) ?>Complete</button>
                        <button type="button" class="btn btn-sm btn-danger" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="cancelled">Cancel</button>
                    <?php elseif ($a['status'] === 'pending'): ?>
                        <button type="button" class="btn btn-sm btn-danger" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="cancelled">Cancel</button>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty"><h3>No appointments found</h3><p><?= $date ? 'Nothing is booked for this date.' : 'Bookings will appear here.' ?></p></div>
<?php endif; ?>
