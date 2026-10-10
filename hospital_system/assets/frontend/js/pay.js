// Payment: method switching, card formatting + live preview, submit.
(function () {
    const form = document.getElementById('pay-form');
    if (!form) return;

    const groups = {
        card: document.getElementById('card-fields'),
        bank_transfer: document.getElementById('bank-fields')
    };
    const btn   = form.querySelector('button[type=submit]');
    const label = btn.innerHTML;

    const numberInp = form.querySelector('[name=card_number]');
    const nameInp   = form.querySelector('[name=card_name]');
    const expiryInp = form.querySelector('[name=expiry]');
    const cvvInp    = form.querySelector('[name=cvv]');
    const view = {
        number: document.getElementById('cc-number'),
        name:   document.getElementById('cc-name'),
        expiry: document.getElementById('cc-exp')
    };

    function method() {
        const c = form.querySelector('input[name=payment_method]:checked');
        return c ? c.value : 'card';
    }

    // Show only the fields for the chosen method (disabled inputs are not sent)
    function toggle() {
        Object.keys(groups).forEach(function (key) {
            const on = key === method();
            groups[key].hidden = !on;
            groups[key].querySelectorAll('input').forEach(function (i) { i.disabled = !on; });
        });
    }
    form.querySelectorAll('input[name=payment_method]').forEach(function (r) { r.addEventListener('change', toggle); });
    toggle();

    function digits(v) { return v.replace(/\D/g, ''); }

    function paint() {
        const d = digits(numberInp.value).padEnd(16, '\u2022');
        view.number.textContent = d.replace(/(.{4})/g, '$1 ').trim();
        view.name.textContent   = nameInp.value.trim() || 'Name on card';
        view.expiry.textContent = expiryInp.value || 'MM/YY';
    }

    numberInp.addEventListener('input', function () {
        numberInp.value = digits(numberInp.value).slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
        paint();
    });
    expiryInp.addEventListener('input', function () {
        let d = digits(expiryInp.value).slice(0, 4);
        if (d.length > 2) d = d.slice(0, 2) + '/' + d.slice(2);
        expiryInp.value = d;
        paint();
    });
    cvvInp.addEventListener('input', function () { cvvInp.value = digits(cvvInp.value).slice(0, 3); });
    nameInp.addEventListener('input', paint);

    // Test card shortcuts: <button type="button" data-test-card="4242424242424242">
    form.querySelectorAll('[data-test-card]').forEach(function (b) {
        b.addEventListener('click', function () {
            numberInp.value = b.dataset.testCard.replace(/(.{4})/g, '$1 ').trim();
            if (!expiryInp.value) expiryInp.value = '12/30';
            if (!cvvInp.value) cvvInp.value = '123';
            if (!nameInp.value) nameInp.value = 'Test Patient';
            paint();
        });
    });
    paint();

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        btn.disabled = true;
        btn.textContent = 'Processing\u2026';

        try {
            const res  = await fetch(window.APP.baseUrl + 'api/process_payment.php', {
                method: 'POST',
                body: new FormData(form)
            });
            const data = await res.json();

            let text = data.message;
            if (data.transaction_ref) text += ' (Ref: ' + data.transaction_ref + ')';
            showMessage(text, data.success);

            if (data.success) {
                setTimeout(function () {
                    location.href = window.APP.baseUrl + 'patient/receipt.php?payment_id=' + data.payment_id;
                }, 1200);
            } else {
                btn.disabled = false;
                btn.innerHTML = label;
            }
        } catch (err) {
            showMessage('Network error. Please try again.', false);
            btn.disabled = false;
            btn.innerHTML = label;
        }
    });
})();
