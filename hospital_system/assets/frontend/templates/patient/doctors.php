<header class="page-head">
    <div>
        <h1 class="page-title">Our doctors</h1>
        <p class="page-sub">Search by name or filter by specialization, then book a time that works for you.</p>
    </div>
</header>

<div class="toolbar">
    <form method="get" action="doctors.php" class="search-form" role="search">
        <?php if ($specId): ?><input type="hidden" name="specialization_id" value="<?= (int) $specId ?>"><?php endif; ?>
        <div class="input-icon">
            <?= icon('search', 18) ?>
            <input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="Search by name" aria-label="Search by name">
        </div>
        <button type="submit" class="btn">Search</button>
        <?php if ($q !== '' || $specId): ?><a href="doctors.php" class="btn btn-outline">Clear</a><?php endif; ?>
    </form>

    <div class="chips" aria-label="Specializations">
        <?php foreach ($chips as $chip): ?>
            <a class="chip <?= $chip['active'] ? 'is-active' : '' ?>" href="<?= e($chip['url']) ?>"><?= e($chip['label']) ?></a>
        <?php endforeach; ?>
    </div>
</div>

<p class="result-count"><?= count($doctors) ?> <?= count($doctors) === 1 ? 'doctor' : 'doctors' ?> found</p>

<?php if ($doctors): ?>
    <div class="card-grid">
        <?php foreach ($doctors as $d): ?>
            <article class="doc-card">
                <div class="doc-top">
                    <span class="avatar avatar-lg" aria-hidden="true"><?= initials($d['full_name']) ?></span>
                    <div>
                        <h2 class="doc-name">Dr. <?= e($d['full_name']) ?></h2>
                        <div class="doc-spec"><?= e($d['specialization']) ?></div>
                    </div>
                </div>
                <p class="doc-bio"><?= e($d['bio'] ?: 'No bio provided.') ?></p>
                <div class="doc-foot">
                    <div class="doc-fee"><?= money($d['consultation_fee']) ?><small>per consultation</small></div>
                    <a class="btn btn-sm" href="book.php?doctor_id=<?= (int) $d['doctor_id'] ?>">Book</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty">
        <h3>No doctors match your search</h3>
        <p>Try a different name or clear the filters.</p>
        <a class="btn btn-outline" href="doctors.php">Clear filters</a>
    </div>
<?php endif; ?>
