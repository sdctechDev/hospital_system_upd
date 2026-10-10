<header class="page-head">
    <div>
        <h1 class="page-title">Specializations</h1>
        <p class="page-sub">The fields patients can filter doctors by.</p>
    </div>
</header>

<div class="split split--form">
    <section class="panel sticky" aria-labelledby="form-title">
        <div class="panel-head">
            <h2 id="form-title"><?= $isEdit ? 'Edit specialization' : 'Add specialization' ?></h2>
        </div>
        <form method="post" action="specializations.php">
            <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add' ?>">
            <?php if ($isEdit): ?><input type="hidden" name="specialization_id" value="<?= (int) $editId ?>"><?php endif; ?>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= e($form['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description <span class="req">(optional)</span></label>
                <input type="text" id="description" name="description" value="<?= e($form['description'] ?? '') ?>">
            </div>
            <div class="row-gap">
                <button type="submit" class="btn"><?= $isEdit ? 'Save changes' : 'Add specialization' ?></button>
                <?php if ($isEdit): ?><a href="specializations.php" class="btn btn-outline">Cancel</a><?php endif; ?>
            </div>
        </form>
    </section>

    <section aria-labelledby="list-title">
        <div class="panel-head"><h2 id="list-title">All specializations (<?= count($list) ?>)</h2></div>
        <div class="item-list">
            <?php foreach ($list as $s): ?>
                <div class="item <?= $isEdit && (int) $editId === (int) $s['specialization_id'] ? 'is-editing' : '' ?>">
                    <div class="item-main">
                        <div class="person-name"><?= e($s['name']) ?></div>
                        <div class="person-sub"><?= e($s['description'] ?: 'No description') ?></div>
                    </div>
                    <div class="item-side">
                        <span class="tag"><?= (int) $s['doctor_count'] ?> <?= (int) $s['doctor_count'] === 1 ? 'doctor' : 'doctors' ?></span>
                        <a class="btn btn-sm btn-outline" href="specializations.php?edit=<?= (int) $s['specialization_id'] ?>"><?= icon('pencil', 14) ?>Edit</a>
                        <form method="post" action="specializations.php" class="inline-form" data-confirm="Delete this specialization? This can't be undone." data-confirm-label="Delete">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="specialization_id" value="<?= (int) $s['specialization_id'] ?>">
                            <button class="btn btn-sm btn-danger"><?= icon('trash', 14) ?>Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (!$list): ?><div class="empty"><h3>No specializations yet</h3><p>Add your first one to start adding doctors.</p></div><?php endif; ?>
        </div>
    </section>
</div>
