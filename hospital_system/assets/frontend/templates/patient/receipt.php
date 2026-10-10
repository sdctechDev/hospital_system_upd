<div class="ticket">
    <div class="ticket-head">
        <span class="muted">Payment receipt</span>
        <?= badge($r['status']) ?>
    </div>
    <div class="ticket-amount num"><?= money($r['amount']) ?></div>
    <p class="muted">Paid to Dr. <?= e($r['doctor_name']) ?></p>

    <div class="ticket-tear" aria-hidden="true"></div>

    <dl>
        <div><dt>Reference</dt><dd class="num"><?= e($r['transaction_ref']) ?></dd></div>
        <div><dt>Date paid</dt><dd><?= fdate($r['paid_at'], 'd M Y, H:i') ?></dd></div>
        <div><dt>Patient</dt><dd><?= e($r['patient_name']) ?></dd></div>
        <div><dt>Doctor</dt><dd>Dr. <?= e($r['doctor_name']) ?> (<?= e($r['specialization']) ?>)</dd></div>
        <div><dt>Appointment</dt><dd><?= fdate($r['appointment_date']) ?> at <?= ftime($r['appointment_time']) ?></dd></div>
        <div><dt>Method</dt><dd style="text-transform:capitalize"><?= methodLabel($r['payment_method']) ?></dd></div>
        <?php if ($r['status'] === 'refunded'): ?>
            <div><dt>Refund reference</dt><dd class="num"><?= e($r['refund_ref']) ?></dd></div>
            <div><dt>Refunded on</dt><dd><?= fdate($r['refunded_at'], 'd M Y, H:i') ?></dd></div>
        <?php endif; ?>
    </dl>

    <p class="ticket-note">Simulated payment. No real money was charged.</p>
    <div class="ticket-actions no-print">
        <button type="button" class="btn" onclick="window.print()"><?= icon('printer', 18) ?>Print receipt</button>
        <a class="btn btn-outline" href="appointments.php">My appointments</a>
    </div>
</div>
