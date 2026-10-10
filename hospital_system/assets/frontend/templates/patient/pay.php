<header class="page-head">
    <div>
        <h1 class="page-title">Payment</h1>
        <p class="page-sub">Simulated payment only. No real money is charged.</p>
    </div>
</header>

<div class="pay-grid">
    <section class="panel">
        <form id="pay-form" autocomplete="off">
            <input type="hidden" name="appointment_id" value="<?= (int) $appt['appointment_id'] ?>">

            <fieldset class="field">
                <legend>Payment method</legend>
                <div class="choice-grid">
                    <div class="choice">
                        <input type="radio" name="payment_method" id="pm-card" value="card" checked>
                        <label for="pm-card"><?= icon('credit-card', 20) ?>Card</label>
                    </div>
                    <div class="choice">
                        <input type="radio" name="payment_method" id="pm-bank" value="bank_transfer">
                        <label for="pm-bank"><?= icon('landmark', 20) ?>Bank transfer</label>
                    </div>
                </div>
            </fieldset>

            <div id="card-fields">
                <div class="cc" aria-hidden="true">
                    <div class="cc-top"><span>Card</span><span class="cc-chip"></span></div>
                    <div class="cc-number" id="cc-number">&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;</div>
                    <div class="cc-bottom"><span id="cc-name">Name on card</span><span id="cc-exp">MM/YY</span></div>
                </div>

                <div class="form-group">
                    <label for="card_name">Name on card</label>
                    <input type="text" id="card_name" name="card_name" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="card_number">Card number</label>
                    <input type="text" id="card_number" name="card_number" autocomplete="off" inputmode="numeric" placeholder="4242 4242 4242 4242" required>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="expiry">Expiry (MM/YY)</label>
                        <input type="text" id="expiry" name="expiry" autocomplete="off" inputmode="numeric" placeholder="12/30" maxlength="5" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="password" id="cvv" name="cvv" autocomplete="new-password" inputmode="numeric" maxlength="3" required>
                    </div>
                </div>
                <div class="test-cards">
                    <span class="muted">Fill with a test card:</span>
                    <div class="chips">
                        <button type="button" class="chip" data-test-card="4242424242424242">Success</button>
                        <button type="button" class="chip" data-test-card="4000000000000002">Declined</button>
                        <button type="button" class="chip" data-test-card="4000000000009995">Insufficient funds</button>
                    </div>
                </div>
            </div>

            <div id="bank-fields" hidden>
                <div class="form-group">
                    <label for="account_name">Account name</label>
                    <input type="text" id="account_name" name="account_name" required>
                </div>
                <div class="form-group">
                    <label for="account_number">Account number</label>
                    <input type="text" id="account_number" name="account_number" inputmode="numeric" maxlength="10" required>
                    <p class="hint">10 digits. Any 10 digits succeed; 0000000000 fails.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-lg btn-block mt-2"><?= icon('lock', 18) ?>Pay <?= money($appt['fee']) ?></button>
        </form>
    </section>

    <aside class="panel pay-summary sticky">
        <h2>Appointment summary</h2>
        <div class="person mt-2">
            <span class="avatar" aria-hidden="true"><?= initials($appt['doctor_name']) ?></span>
            <div>
                <div class="person-name">Dr. <?= e($appt['doctor_name']) ?></div>
                <div class="person-sub"><?= e($appt['specialization']) ?></div>
            </div>
        </div>
        <dl class="summary-list">
            <div class="summary-row"><dt>Date</dt><dd><?= fdate($appt['appointment_date'], 'D, d M Y') ?></dd></div>
            <div class="summary-row"><dt>Time</dt><dd><?= ftime($appt['appointment_time']) ?></dd></div>
        </dl>
        <div class="summary-total"><span class="muted">Total</span><span class="pay-total"><?= money($appt['fee']) ?></span></div>
    </aside>
</div>
