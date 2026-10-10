<header class="page-head">
    <div>
        <h1 class="page-title">Book an appointment</h1>
        <p class="page-sub">Choose a doctor, a day and a time. You pay on the next screen.</p>
    </div>
</header>

<div class="book" id="book" data-selected="<?= (int) $selected ?>">
    <div>
        <section class="step" id="step-doctor" aria-labelledby="s1">
            <div class="step-head"><span class="step-n">1</span><h2 id="s1">Choose a doctor</h2></div>
            <div class="input-icon">
                <?= icon('search', 18) ?>
                <input type="search" id="doctor-search" placeholder="Search by name or specialization" aria-label="Search doctors" autocomplete="off">
            </div>
            <input type="hidden" id="doctor_id" value="">
            <div class="pick-list" id="doctor-list" role="radiogroup" aria-label="Doctors">
                <?php foreach ($doctors as $d): ?>
                    <button type="button" class="pick" role="radio" aria-checked="false"
                            data-id="<?= (int) $d['doctor_id'] ?>" data-fee="<?= e($d['consultation_fee']) ?>" data-name="Dr. <?= e($d['full_name']) ?>">
                        <span class="avatar" aria-hidden="true"><?= initials($d['full_name']) ?></span>
                        <span class="pick-body">
                            <span class="pick-name">Dr. <?= e($d['full_name']) ?></span><br>
                            <span class="pick-spec"><?= e($d['specialization']) ?></span>
                        </span>
                        <span class="pick-fee"><?= money($d['consultation_fee']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
            <p class="pick-empty" id="doctor-empty" hidden>No doctors match your search.</p>
        </section>

        <section class="step" id="step-date" aria-labelledby="s2">
            <div class="step-head"><span class="step-n">2</span><h2 id="s2">Pick a day</h2></div>
            <div class="day-strip" id="date-strip" role="radiogroup" aria-label="Next 14 days"></div>
            <div class="form-group date-alt" style="margin-bottom:0">
                <label for="date">Or choose another date</label>
                <input type="date" id="date" min="<?= e($today) ?>">
            </div>
        </section>

        <section class="step" id="step-time" aria-labelledby="s3">
            <div class="step-head"><span class="step-n">3</span><h2 id="s3">Pick a time</h2></div>
            <div id="slots"><p class="slot-note">Choose a doctor and a date first.</p></div>
        </section>

        <section class="step" aria-labelledby="s4">
            <div class="step-head"><span class="step-n">4</span><h2 id="s4">Reason for visit <span class="muted">(optional)</span></h2></div>
            <label class="sr-only" for="reason">Reason for visit</label>
            <textarea id="reason" rows="3" maxlength="500" placeholder="Briefly describe what you need help with"></textarea>
        </section>
    </div>

    <aside class="panel sticky" aria-labelledby="sum-title">
        <h2 id="sum-title">Your booking</h2>
        <dl class="summary-list">
            <div class="summary-row"><dt>Doctor</dt><dd id="sum-doctor" class="is-empty">Not chosen</dd></div>
            <div class="summary-row"><dt>Date</dt><dd id="sum-date" class="is-empty">Not chosen</dd></div>
            <div class="summary-row"><dt>Time</dt><dd id="sum-time" class="is-empty">Not chosen</dd></div>
        </dl>
        <div class="summary-total"><span class="muted">Consultation fee</span><strong id="fee">&mdash;</strong></div>
        <button type="button" id="book-btn" class="btn btn-lg btn-block mt-2">Book and continue to payment</button>
    </aside>
</div>
