<header class="page-head">
    <div>
        <h1 class="page-title">Payments</h1>
        <p class="page-sub">Every card and bank transfer attempt.</p>
    </div>
    <div class="stat-label" style="flex-direction:column;align-items:flex-end;gap:4px">
        <span>Total revenue (simulated)</span>
        <strong class="callout-num num" style="color:var(--text)"><?= money($revenue) ?></strong>
    </div>
</header>

<div class="chips" style="margin-bottom: var(--s3)" aria-label="Filter by status">
    <a class="chip <?= $filter === ''         ? 'is-active' : '' ?>" href="payments.php">All</a>
    <a class="chip <?= $filter === 'success'  ? 'is-active' : '' ?>" href="payments.php?status=success">Success</a>
    <a class="chip <?= $filter === 'failed'   ? 'is-active' : '' ?>" href="payments.php?status=failed">Failed</a>
    <a class="chip <?= $filter === 'refunded' ? 'is-active' : '' ?>" href="payments.php?status=refunded">Refunded</a>
</div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Reference</th><th>Patient</th><th>Doctor</th><th>Method</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach ($payments as $p): ?>
            <tr>
                <td data-label="Reference" class="mono"><?= e($p['transaction_ref']) ?></td>
                <td data-label="Patient"><?= e($p['patient_name']) ?></td>
                <td data-label="Doctor">Dr. <?= e($p['doctor_name']) ?></td>
                <td data-label="Method" style="text-transform:capitalize"><?= methodLabel($p['payment_method']) ?></td>
                <td data-label="Amount" class="num"><?= money($p['amount']) ?></td>
                <td data-label="Status"><?= badge($p['status']) ?></td>
                <td data-label="Date"><?= fdate($p['paid_at'], 'd M Y, H:i') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$payments): ?><tr><td colspan="7" class="cell-empty">No payments found.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
