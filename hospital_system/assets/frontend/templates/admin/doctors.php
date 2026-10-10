<?php $val = fn(string $k) => e($form[$k] ?? ''); ?>
<header class="page-head">
    <div>
        <h1 class="page-title">Doctors</h1>
        <p class="page-sub">Add doctors, update their fees and switch them on or off.</p>
    </div>
</header>

<div class="split split--form">
    <section class="panel sticky" aria-labelledby="form-title">
        <div class="panel-head"><h2 id="form-title"><?= $isEdit ? 'Edit doctor' : 'Add doctor' ?></h2></div>

        <?php if ($error): ?><div class="alert alert-error" role="alert"><?= icon('alert', 18) ?><span><?= e($error) ?></span></div><?php endif; ?>

        <form method="post" action="doctors.php">
            <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add' ?>">
            <?php if ($isEdit): ?><input type="hidden" name="doctor_id" value="<?= (int) $editId ?>"><?php endif; ?>

            <div class="form-group">
                <label for="full_name">Full name</label>
                <input type="text" id="full_name" name="full_name" value="<?= $val('full_name') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= $val('email') ?>" required>
            </div>
            <div class="row">
                <?php if (!$isEdit): ?>
                <div class="form-group">
                    <label for="password">Password <span class="req">(min 6)</span></label>
                    <input type="password" id="password" name="password" minlength="6" autocomplete="new-password" required>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?= $val('phone') ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="specialization_id">Specialization</label>
                    <div class="select">
                        <select id="specialization_id" name="specialization_id" required>
                            <option value="">Select</option>
                            <?php foreach ($specs as $s): ?>
                                <option value="<?= (int) $s['specialization_id'] ?>" <?= (int) ($form['specialization_id'] ?? 0) === (int) $s['specialization_id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="consultation_fee">Consultation fee (&#8358;)</label>
                    <input type="number" id="consultation_fee" name="consultation_fee" min="0" step="0.01" value="<?= $val('consultation_fee') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="bio">Bio <span class="req">(optional)</span></label>
                <textarea id="bio" name="bio" rows="3"><?= $val('bio') ?></textarea>
            </div>
            <div class="row-gap">
                <button type="submit" class="btn"><?= $isEdit ? 'Save changes' : 'Add doctor' ?></button>
                <?php if ($isEdit): ?><a href="doctors.php" class="btn btn-outline">Cancel</a><?php endif; ?>
            </div>
        </form>
    </section>

    <section aria-labelledby="list-title">
        <div class="panel-head" style="flex-wrap:wrap">
            <h2 id="list-title">All doctors (<?= count($doctors) ?>)</h2>
            <div class="input-icon" style="flex:1;max-width:280px">
                <?= icon('search', 18) ?>
                <input type="search" placeholder="Search doctors" aria-label="Search doctors" data-filter-input data-filter-target="#doctor-rows" autocomplete="off">
            </div>
        </div>

        <div id="doctor-rows">
            <div class="item-list">
                <?php foreach ($doctors as $d): ?>
                    <?php $active = $d['status'] === 'active'; ?>
                    <div class="item <?= $isEdit && (int) $editId === (int) $d['doctor_id'] ? 'is-editing' : '' ?>" data-row>
                        <span class="avatar" aria-hidden="true"><?= initials($d['full_name']) ?></span>
                        <div class="item-main">
                            <div class="person-name">Dr. <?= e($d['full_name']) ?></div>
                            <div class="person-sub"><?= e($d['specialization']) ?>, <?= e($d['email']) ?></div>
                        </div>
                        <div class="item-side">
                            <span class="item-fee"><?= money($d['consultation_fee']) ?></span>
                            <?= badge($d['status'], $active ? 'confirmed' : 'cancelled') ?>
                            <a class="btn btn-sm btn-outline" href="doctors.php?edit=<?= (int) $d['doctor_id'] ?>"><?= icon('pencil', 14) ?>Edit</a>
                            <form method="post" action="doctors.php" class="inline-form">
                                <input type="hidden" name="action" value="status">
                                <input type="hidden" name="doctor_id" value="<?= (int) $d['doctor_id'] ?>">
                                <input type="hidden" name="status" value="<?= $active ? 'inactive' : 'active' ?>">
                                <button class="btn btn-sm <?= $active ? 'btn-danger' : '' ?>"><?= $active ? 'Deactivate' : 'Activate' ?></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (!$doctors): ?><div class="empty"><h3>No doctors yet</h3><p>Add the first doctor using the form.</p></div><?php endif; ?>
            <div class="empty" data-filter-empty hidden><h3>No match</h3><p>No doctor matches your search.</p></div>
        </div>
    </section>
</div>
