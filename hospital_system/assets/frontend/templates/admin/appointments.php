<header class="page-head">
    <div>
        <h1 class="page-title">Appointments</h1>
        <p class="page-sub">Confirm, complete or cancel any booking.</p>
    </div>
</header>

<div class="chips" style="margin-bottom: var(--s3)" aria-label="Filter by status">
    <a class="chip <?= $filter === '' ? 'is-active' : '' ?>" href="appointments.php">All</a>
    <?php foreach ($statuses as $s): ?>
        <a class="chip cap <?= $filter === $s ? 'is-active' : '' ?>" href="appointments.php?status=<?= e($s) ?>"><span class="dot dot-<?= e($s) ?>"></span><?= e($s) ?></a>
    <?php endforeach; ?>
</div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>#</th><th>Patient</th><th>Doctor</th><th>When</th><th>Fee</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($appointments as $a): ?>
            <tr>
                <td data-label="ID" class="num faint"><?= (int) $a['appointment_id'] ?></td>
                <td data-label="Patient"><?= e($a['patient_name']) ?></td>
                <td data-label="Doctor">Dr. <?= e($a['doctor_name']) ?></td>
                <td data-label="When"><span><?= fdate($a['appointment_date']) ?><span class="cell-sub"><?= ftime($a['appointment_time']) ?></span></span></td>
                <td data-label="Fee" class="num"><?= money($a['fee']) ?></td>
                <td data-label="Status"><?= badge($a['status']) ?></td>
                <td class="cell-actions">
                    <?php if ($a['status'] === 'pending'): ?>
                        <button type="button" class="btn btn-sm" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="confirmed">Confirm</button>
                        <button type="button" class="btn btn-sm btn-danger" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="cancelled">Cancel</button>
                    <?php elseif ($a['status'] === 'confirmed'): ?>
                        <button type="button" class="btn btn-sm" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="completed">Complete</button>
                        <button type="button" class="btn btn-sm btn-danger" data-status-action data-id="<?= (int) $a['appointment_id'] ?>" data-status="cancelled">Cancel</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$appointments): ?><tr><td colspan="7" class="cell-empty">No appointments found.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
