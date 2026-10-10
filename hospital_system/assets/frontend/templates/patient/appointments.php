<header class="page-head">
    <div>
        <h1 class="page-title">My appointments</h1>
        <p class="page-sub">Pay, review receipts or cancel from here.</p>
    </div>
    <div class="page-actions"><a class="btn" href="book.php"><?= icon('calendar-plus', 18) ?>Book appointment</a></div>
</header>

<?php if ($appointments): ?>
    <div class="chips" data-chip-filter data-target="#appt-list" style="margin-bottom: var(--s3)">
        <button type="button" class="chip is-active" data-value="all" aria-pressed="true">All <span class="count"></span></button>
        <button type="button" class="chip" data-value="pending" aria-pressed="false">Pending <span class="count"></span></button>
        <button type="button" class="chip" data-value="confirmed" aria-pressed="false">Confirmed <span class="count"></span></button>
        <button type="button" class="chip" data-value="completed" aria-pressed="false">Completed <span class="count"></span></button>
        <button type="button" class="chip" data-value="cancelled" aria-pressed="false">Cancelled <span class="count"></span></button>
    </div>

    <div id="appt-list">
        <div class="appt-list">
            <?php foreach ($appointments as $a): ?>
                <article class="appt" data-status="<?= e($a['status']) ?>">
                    <?= dateBlock($a['appointment_date']) ?>
                    <div class="appt-main">
                        <div class="appt-title">Dr. <?= e($a['doctor_name']) ?> <?= badge($a['status']) ?></div>
                        <div class="appt-meta">
                            <span><?= icon('stethoscope', 14) ?><?= e($a['specialization']) ?></span>
                            <span><?= icon('clock', 14) ?><?= ftime($a['appointment_time']) ?></span>
                            <span><?= icon('banknote', 14) ?><?= money($a['fee']) ?></span>
                        </div>
                    </div>
                    <div class="appt-actions">
                        <?php if ($a['status'] === 'pending' && !$a['is_paid']): ?>
                            <a class="btn btn-sm" href="pay.php?appointment_id=<?= (int) $a['appointment_id'] ?>">Pay</a>
                        <?php endif; ?>
                        <?php if (isset($receipts[$a['appointment_id']])): ?>
                            <a class="btn btn-sm btn-outline" href="receipt.php?payment_id=<?= (int) $receipts[$a['appointment_id']] ?>">Receipt</a>
                        <?php endif; ?>
                        <?php if (in_array($a['status'], ['pending', 'confirmed'], true)): ?>
                            <button type="button" class="btn btn-sm btn-danger" data-cancel-id="<?= (int) $a['appointment_id'] ?>">Cancel</button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="empty" data-filter-empty hidden><h3>Nothing here</h3><p>No appointments with this status.</p></div>
    </div>
<?php else: ?>
    <div class="empty">
        <h3>No appointments yet</h3>
        <p>Your bookings will show up here.</p>
        <a class="btn" href="book.php">Book appointment</a>
    </div>
<?php endif; ?>
