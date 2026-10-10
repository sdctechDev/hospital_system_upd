<div class="auth-head">
    <h1>Create your account</h1>
    <p>Register as a patient to book and pay for visits online.</p>
</div>

<?php if ($error): ?><div class="alert alert-error" role="alert"><?= icon('alert', 18) ?><span><?= e($error) ?></span></div><?php endif; ?>

<form method="post" action="">
    <section class="form-section">
        <h2>Your account</h2>
        <div class="form-group">
            <label for="full_name">Full name</label>
            <input type="text" name="full_name" id="full_name" value="<?= e($old['full_name']) ?>" autocomplete="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= e($old['email']) ?>" autocomplete="email" required>
        </div>
        <div class="row">
            <div class="form-group">
                <label for="password">Password <span class="req">(min 6 characters)</span></label>
                <input type="password" name="password" id="password" minlength="6" autocomplete="new-password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm password</label>
                <input type="password" name="confirm_password" id="confirm_password" minlength="6" autocomplete="new-password" data-match="#password" required>
            </div>
        </div>
    </section>

    <section class="form-section">
        <h2>About you <span class="muted">(optional)</span></h2>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" value="<?= e($old['phone']) ?>" autocomplete="tel">
        </div>
        <fieldset class="field">
            <legend>Gender</legend>
            <div class="opts">
                <div class="opt"><input type="radio" name="gender" id="g-none" value=""       <?= $old['gender'] === ''       ? 'checked' : '' ?>><label for="g-none">Prefer not to say</label></div>
                <div class="opt"><input type="radio" name="gender" id="g-male" value="male"   <?= $old['gender'] === 'male'   ? 'checked' : '' ?>><label for="g-male">Male</label></div>
                <div class="opt"><input type="radio" name="gender" id="g-female" value="female" <?= $old['gender'] === 'female' ? 'checked' : '' ?>><label for="g-female">Female</label></div>
                <div class="opt"><input type="radio" name="gender" id="g-other" value="other" <?= $old['gender'] === 'other'  ? 'checked' : '' ?>><label for="g-other">Other</label></div>
            </div>
        </fieldset>
        <div class="form-group">
            <label for="date_of_birth">Date of birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth" max="<?= e($today) ?>" value="<?= e($old['date_of_birth']) ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" id="address" value="<?= e($old['address']) ?>" autocomplete="street-address">
        </div>
    </section>

    <button type="submit" class="btn btn-lg btn-block">Create account</button>
</form>

<p class="auth-foot">Already registered? <a class="link" href="<?= BASE_URL ?>auth/login.php">Sign in</a></p>
