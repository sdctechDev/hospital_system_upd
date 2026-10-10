<div class="auth-head">
    <h1>Welcome back</h1>
    <p>Sign in to manage your appointments.</p>
</div>

<?php if ($error): ?><div class="alert alert-error" role="alert"><?= icon('alert', 18) ?><span><?= e($error) ?></span></div><?php endif; ?>
<?php if ($info): ?><div class="alert alert-info" role="status"><?= icon('info', 18) ?><span><?= e($info) ?></span></div><?php endif; ?>

<form method="post" action="">
    <fieldset class="field">
        <legend>Sign in as</legend>
        <div class="seg">
            <div class="opt">
                <input type="radio" name="role" id="role-patient" value="patient" <?= $selectedRole === 'patient' ? 'checked' : '' ?>>
                <label for="role-patient"><?= icon('user', 16) ?>Patient</label>
            </div>
            <div class="opt">
                <input type="radio" name="role" id="role-doctor" value="doctor" <?= $selectedRole === 'doctor' ? 'checked' : '' ?>>
                <label for="role-doctor"><?= icon('stethoscope', 16) ?>Doctor</label>
            </div>
            <div class="opt">
                <input type="radio" name="role" id="role-admin" value="admin" <?= $selectedRole === 'admin' ? 'checked' : '' ?>>
                <label for="role-admin"><?= icon('shield', 16) ?>Admin</label>
            </div>
        </div>
    </fieldset>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= e($email) ?>" autocomplete="email" required>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <div class="input-action">
            <input type="password" name="password" id="password" autocomplete="current-password" required>
            <button type="button" class="icon-btn" data-toggle-password="#password" aria-pressed="false" aria-label="Show password"><?= icon('eye', 18) ?></button>
        </div>
    </div>

    <button type="submit" class="btn btn-lg btn-block">Sign in</button>
</form>

<p class="auth-foot">New patient? <a class="link" href="<?= BASE_URL ?>auth/register.php">Create an account</a></p>
