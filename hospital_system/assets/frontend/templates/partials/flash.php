<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= e($flash['type']) ?>" role="status">
        <?= icon($flash['type'] === 'success' ? 'circle-check' : 'alert', 18) ?>
        <span><?= e($flash['message']) ?></span>
    </div>
<?php endif; ?>
