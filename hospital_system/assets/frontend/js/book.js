// Booking flow: pick a doctor, a date, a time, then continue to payment.
(function () {
    const root = document.getElementById('book');
    if (!root) return;

    const doctorInp = document.getElementById('doctor_id');
    const dateInp   = document.getElementById('date');
    const slotsBox  = document.getElementById('slots');
    const reasonInp = document.getElementById('reason');
    const bookBtn   = document.getElementById('book-btn');
    const list      = document.getElementById('doctor-list');
    const search    = document.getElementById('doctor-search');
    const empty     = document.getElementById('doctor-empty');
    const strip     = document.getElementById('date-strip');
    const sum = {
        doctor: document.getElementById('sum-doctor'),
        date:   document.getElementById('sum-date'),
        time:   document.getElementById('sum-time'),
        fee:    document.getElementById('fee')
    };
    const steps = {
        doctor: document.getElementById('step-doctor'),
        date:   document.getElementById('step-date'),
        time:   document.getElementById('step-time')
    };

    let selectedTime = '';
    let current = null;      // the chosen doctor's button
    let loadToken = 0;       // ignore slow responses that arrive out of order

    function setSummary(el, text, placeholder) {
        el.textContent = text || placeholder;
        el.classList.toggle('is-empty', !text);
    }

    function parseISO(iso) {
        const p = iso.split('-');
        return new Date(Number(p[0]), Number(p[1]) - 1, Number(p[2]));
    }
    function toISO(d) {
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + m + '-' + day;
    }

    function refresh() {
        setSummary(sum.doctor, current ? current.dataset.name : '', 'Not chosen');
        setSummary(sum.date, dateInp.value
            ? parseISO(dateInp.value).toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' })
            : '', 'Not chosen');
        setSummary(sum.time, selectedTime, 'Not chosen');
        const fee = current && current.dataset.fee ? Number(current.dataset.fee) : null;
        sum.fee.textContent = fee !== null ? '\u20A6' + fee.toLocaleString('en-NG', { minimumFractionDigits: 2 }) : '\u2014';
        steps.doctor.classList.toggle('is-done', !!current);
        steps.date.classList.toggle('is-done', !!dateInp.value);
        steps.time.classList.toggle('is-done', !!selectedTime);
    }

    /* ---------- Step 1: doctors ---------- */
    function pick(btn, scroll) {
        list.querySelectorAll('.pick').forEach(function (b) { b.setAttribute('aria-checked', b === btn ? 'true' : 'false'); });
        current = btn;
        doctorInp.value = btn.dataset.id;
        if (scroll) btn.scrollIntoView({ block: 'nearest' });
        refresh();
        loadSlots();
    }
    list.addEventListener('click', function (e) {
        const btn = e.target.closest('.pick');
        if (btn) pick(btn, false);
    });
    search.addEventListener('input', function () {
        const q = search.value.trim().toLowerCase();
        let shown = 0;
        list.querySelectorAll('.pick').forEach(function (b) {
            const hit = b.textContent.toLowerCase().indexOf(q) !== -1;
            b.hidden = !hit;
            if (hit) shown++;
        });
        empty.hidden = shown > 0;
    });

    /* ---------- Step 2: dates ---------- */
    function buildStrip() {
        const start = parseISO(dateInp.min || toISO(new Date()));
        const todayISO = toISO(new Date());
        for (let i = 0; i < 14; i++) {
            const d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'day' + (toISO(d) === todayISO ? ' is-today' : '');
            b.dataset.date = toISO(d);
            b.setAttribute('role', 'radio');
            b.setAttribute('aria-checked', 'false');
            b.innerHTML = '<span></span><b></b><span></span>';
            const parts = b.querySelectorAll('span, b');
            parts[0].textContent = d.toLocaleDateString('en-GB', { weekday: 'short' });
            parts[1].textContent = d.getDate();
            parts[2].textContent = d.toLocaleDateString('en-GB', { month: 'short' });
            strip.appendChild(b);
        }
    }
    function syncStrip() {
        strip.querySelectorAll('.day').forEach(function (b) {
            b.setAttribute('aria-checked', b.dataset.date === dateInp.value ? 'true' : 'false');
        });
    }
    strip.addEventListener('click', function (e) {
        const b = e.target.closest('.day');
        if (!b) return;
        dateInp.value = b.dataset.date;
        syncStrip(); refresh(); loadSlots();
    });
    dateInp.addEventListener('change', function () { syncStrip(); refresh(); loadSlots(); });

    /* ---------- Step 3: time slots ---------- */
    function groupOf(time) {
        const h = parseInt(time.split(':')[0], 10);
        return h < 12 ? 'Morning' : (h < 17 ? 'Afternoon' : 'Evening');
    }
    function note(text) {
        slotsBox.innerHTML = '';
        const p = document.createElement('p');
        p.className = 'slot-note';
        p.textContent = text;
        slotsBox.appendChild(p);
    }

    async function loadSlots() {
        selectedTime = '';
        refresh();
        if (!doctorInp.value || !dateInp.value) {
            note(doctorInp.value ? 'Choose a date to see available times.' : 'Choose a doctor and a date first.');
            return;
        }
        const token = ++loadToken;
        note('Loading available times\u2026');
        try {
            const url = window.APP.baseUrl + 'api/get_slots.php?doctor_id=' +
                encodeURIComponent(doctorInp.value) + '&date=' + encodeURIComponent(dateInp.value);
            const res  = await fetch(url);
            const data = await res.json();
            if (token !== loadToken) return;

            if (!data.success) { note(data.message); showMessage(data.message, false); return; }
            if (data.slots.length === 0) { note(data.message); return; }

            slotsBox.innerHTML = '';
            const groups = {};
            data.slots.forEach(function (t) { (groups[groupOf(t)] = groups[groupOf(t)] || []).push(t); });
            let n = 0;
            ['Morning', 'Afternoon', 'Evening'].forEach(function (name) {
                if (!groups[name]) return;
                const wrap = document.createElement('div');
                wrap.className = 'slot-group';
                wrap.innerHTML = '<h3></h3><div class="slot-row" role="radiogroup"></div>';
                wrap.querySelector('h3').textContent = name;
                const row = wrap.querySelector('.slot-row');
                groups[name].forEach(function (time) {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'slot';
                    b.textContent = time;
                    b.setAttribute('role', 'radio');
                    b.setAttribute('aria-checked', 'false');
                    b.style.animationDelay = (n++ * 25) + 'ms';
                    b.addEventListener('click', function () {
                        slotsBox.querySelectorAll('.slot').forEach(function (s) { s.setAttribute('aria-checked', 'false'); });
                        b.setAttribute('aria-checked', 'true');
                        selectedTime = time;
                        refresh();
                    });
                    row.appendChild(b);
                });
                slotsBox.appendChild(wrap);
            });
        } catch (err) {
            if (token !== loadToken) return;
            note('Could not load time slots.');
            showMessage('Could not load time slots. Check your connection.', false);
        }
    }

    /* ---------- Submit ---------- */
    async function book() {
        if (!doctorInp.value) { steps.doctor.scrollIntoView({ behavior: 'smooth', block: 'center' }); return showMessage('Please choose a doctor.', false); }
        if (!dateInp.value)   { steps.date.scrollIntoView({ behavior: 'smooth', block: 'center' });   return showMessage('Please choose a date.', false); }
        if (!selectedTime)    { steps.time.scrollIntoView({ behavior: 'smooth', block: 'center' });   return showMessage('Please pick a time slot.', false); }

        const fd = new FormData();
        fd.append('doctor_id', doctorInp.value);
        fd.append('date', dateInp.value);
        fd.append('time', selectedTime);
        fd.append('reason', reasonInp.value);

        bookBtn.disabled = true;
        try {
            const res  = await fetch(window.APP.baseUrl + 'api/book_appointment.php', { method: 'POST', body: fd });
            const data = await res.json();
            showMessage(data.message, data.success);

            if (data.success) {
                setTimeout(function () {
                    location.href = window.APP.baseUrl + 'patient/pay.php?appointment_id=' + data.appointment_id;
                }, 800);
            } else {
                bookBtn.disabled = false;
                loadSlots(); // refresh in case the slot was taken
            }
        } catch (err) {
            showMessage('Network error. Please try again.', false);
            bookBtn.disabled = false;
        }
    }
    bookBtn.addEventListener('click', book);

    /* ---------- Init ---------- */
    buildStrip();
    const pre = Number(root.dataset.selected || 0);
    const first = pre ? list.querySelector('.pick[data-id="' + pre + '"]') : null;
    if (first) pick(first, true); else { refresh(); loadSlots(); }
})();
