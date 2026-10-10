<header class="page-head">
    <div>
        <h1 class="page-title">Patients</h1>
        <p class="page-sub"><?= count($patients) ?> registered <?= count($patients) === 1 ? 'patient' : 'patients' ?>.</p>
    </div>
    <div class="page-actions" style="min-width: min(320px, 100%)">
        <div class="input-icon" style="flex:1">
            <?= icon('search', 18) ?>
            <input type="search" placeholder="Search patients" aria-label="Search patients" data-filter-input data-filter-target="#patients" autocomplete="off">
        </div>
    </div>
</header>

<div id="patients">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Patient</th><th>Phone</th><th>Gender</th><th>Date of birth</th><th>Registered</th></tr></thead>
            <tbody>
            <?php foreach ($patients as $p): ?>
                <tr data-row>
                    <td data-label="Patient">
                        <span class="person">
                            <span class="avatar avatar-sm" aria-hidden="true"><?= initials($p['full_name']) ?></span>
                            <span><span class="person-name"><?= e($p['full_name']) ?></span><span class="cell-sub"><?= e($p['email']) ?></span></span>
                        </span>
                    </td>
                    <td data-label="Phone"><?= e($p['phone'] ?: '-') ?></td>
                    <td data-label="Gender" style="text-transform:capitalize"><?= e($p['gender'] ?: '-') ?></td>
                    <td data-label="Date of birth"><?= $p['date_of_birth'] ? fdate($p['date_of_birth']) : '-' ?></td>
                    <td data-label="Registered"><?= fdate($p['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$patients): ?><tr><td colspan="5" class="cell-empty">No patients yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="empty" data-filter-empty hidden><h3>No match</h3><p>No patient matches your search.</p></div>
</div>
