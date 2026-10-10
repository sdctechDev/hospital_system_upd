<header class="page-head">
    <div>
        <h1 class="page-title">My profile</h1>
        <p class="page-sub">Keep your contact details up to date so doctors can reach you.</p>
    </div>
</header>

<div class="split split--form">
    <aside class="panel identity">
        <span class="avatar avatar-lg" aria-hidden="true"><?= initials($p['full_name']) ?></span>
        <h2><?= e($p['full_name']) ?></h2>
        <p class="panel-sub"><?= e($p['email']) ?></p>
        <dl class="identity-meta summary-list">
            <div class="summary-row"><dt>Member since</dt><dd><?= fdate($p['created_at']) ?></dd></div>
            <div class="summary-row"><dt>Date of birth</dt><dd><?= $p['date_of_birth'] ? fdate($p['date_of_birth']) : '&mdash;' ?></dd></div>
        </dl>
    </aside>

    <section class="panel">
        <form method="post" action="profile.php">
            <div class="form-group">
                <label for="full_name">Full name</label>
                <input type="text" id="full_name" name="full_name" value="<?= e($p['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="<?= e($p['email']) ?>" readonly>
                <p class="hint">Your email is your sign-in and can't be changed here.</p>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?= e($p['phone']) ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <input type="text" id="gender" value="<?= e($p['gender'] ?: 'Not stated') ?>" readonly style="text-transform:capitalize">
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?= e($p['address']) ?>">
            </div>
            <button type="submit" class="btn">Save changes</button>
        </form>
    </section>
</div>
