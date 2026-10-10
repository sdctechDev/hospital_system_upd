<?php
// Group the schedule by weekday for the weekly view
$byDay = [];
foreach ($slots as $s) {
    $byDay[$s['day_of_week']][] = $s;
}
$todayDay = date('D');
?>
<header class="page-head">
    <div>
        <h1 class="page-title">Availability</h1>
        <p class="page-sub">Patients can only book you inside the working hours you add here.</p>
    </div>
</header>

<section class="panel" aria-labelledby="add-title">
    <div class="panel-head"><h2 id="add-title">Add working hours</h2></div>
    <form method="post" action="availability.php" class="sched-form">
        <input type="hidden" name="action" value="add">

        <fieldset class="field span-2">
            <legend>Day</legend>
            <div class="opts">
                <?php foreach ($days as $i => $day): ?>
                    <div class="opt">
                        <input type="radio" name="day_of_week" id="day-<?= e($day) ?>" value="<?= e($day) ?>" <?= $i === 0 ? 'checked' : '' ?>>
                        <label for="day-<?= e($day) ?>"><?= e($day) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <fieldset class="field">
            <legend>Working hours</legend>
            <div class="time-pair">
                <div>
                    <label class="sr-only" for="start_time">Start time</label>
                    <input type="time" id="start_time" name="start_time" required>
                </div>
                <div>
                    <label class="sr-only" for="end_time">End time</label>
                    <input type="time" id="end_time" name="end_time" required>
                </div>
            </div>
            <p class="hint">Start on the left, end on the right.</p>
        </fieldset>

        <fieldset class="field">
            <legend>Slot length</legend>
            <div class="opts">
                <?php foreach ($durations as $min): ?>
                    <div class="opt">
                        <input type="radio" name="slot_duration" id="dur-<?= (int) $min ?>" value="<?= (int) $min ?>" <?= $min === 30 ? 'checked' : '' ?>>
                        <label for="dur-<?= (int) $min ?>"><?= (int) $min ?> min</label>
                    </div>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <div class="span-2"><button type="submit" class="btn"><?= icon('plus', 18) ?>Add hours</button></div>
    </form>
</section>

<section class="mt-3" aria-labelledby="week-title">
    <div class="panel-head"><h2 id="week-title">Your week</h2></div>

    <?php if (!$slots): ?>
        <div class="alert alert-info" role="status"><?= icon('info', 18) ?><span>No schedule yet. Patients cannot book you until you add working hours.</span></div>
    <?php endif; ?>

    <div class="week">
        <?php foreach ($days as $day): ?>
            <div class="day-col <?= $day === $todayDay ? 'is-today' : '' ?>">
                <div class="day-col-head"><span><?= e($day) ?></span><small><?= $day === $todayDay ? 'today' : '' ?></small></div>
                <?php if (!empty($byDay[$day])): ?>
                    <?php foreach ($byDay[$day] as $s): ?>
                        <div class="block">
                            <div class="block-time"><?= ftime($s['start_time']) ?>&ndash;<?= ftime($s['end_time']) ?></div>
                            <div class="block-foot">
                                <small><?= (int) $s['slot_duration'] ?> min slots</small>
                                <form method="post" action="availability.php" data-confirm="These hours will be removed and patients can no longer book those times.">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="availability_id" value="<?= (int) $s['availability_id'] ?>">
                                    <button type="submit" aria-label="Remove <?= e($day) ?> hours"><?= icon('x', 16) ?></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="day-off">Not working</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
