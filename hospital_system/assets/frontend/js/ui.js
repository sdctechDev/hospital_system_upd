// Global UI helpers, loaded on every page by layouts/main.php.
(function () {
    'use strict';

    var ICONS = {
        ok:  '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
        bad: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v5"/><path d="M12 17h.01"/></svg>'
    };

    function toastHost() {
        var host = document.getElementById('toasts');
        if (!host) {
            host = document.createElement('div');
            host.id = 'toasts';
            host.className = 'toasts';
            host.setAttribute('aria-live', 'polite');
            document.body.appendChild(host);
        }
        return host;
    }

    // Show a success or error message as a toast.
    window.showMessage = function (text, ok) {
        var el = document.createElement('div');
        el.className = 'toast ' + (ok ? 'toast-ok' : 'toast-bad');
        el.setAttribute('role', ok ? 'status' : 'alert');

        var ico = document.createElement('span');
        ico.className = 'toast-ico';
        ico.innerHTML = ok ? ICONS.ok : ICONS.bad;
        var msg = document.createElement('span');
        msg.textContent = text;
        el.appendChild(ico);
        el.appendChild(msg);

        function close() {
            el.classList.add('is-leaving');
            setTimeout(function () { el.remove(); }, 250);
        }
        el.addEventListener('click', close);
        toastHost().appendChild(el);
        setTimeout(close, ok ? 4500 : 7000);
    };

    // Promise-based replacement for window.confirm().
    // confirmDialog('Cancel this appointment?', { confirmText: 'Cancel it', danger: true }).then(ok => ...)
    window.confirmDialog = function (message, opts) {
        opts = opts || {};
        return new Promise(function (resolve) {
            if (!window.HTMLDialogElement) { resolve(window.confirm(message)); return; }

            var dlg = document.createElement('dialog');
            dlg.className = 'dlg';
            dlg.innerHTML =
                '<form method="dialog">' +
                '<h2 class="dlg-title"></h2>' +
                '<p class="muted dlg-text"></p>' +
                '<div class="dlg-actions">' +
                '<button class="btn btn-outline" value="cancel"></button>' +
                '<button class="btn" value="ok"></button>' +
                '</div></form>';
            dlg.querySelector('.dlg-title').textContent = opts.title || 'Are you sure?';
            dlg.querySelector('.dlg-text').textContent = message;
            var btns = dlg.querySelectorAll('button');
            btns[0].textContent = opts.cancelText || 'Keep it';
            btns[1].textContent = opts.confirmText || 'Confirm';
            if (opts.danger) btns[1].className = 'btn btn-danger';

            dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close('cancel'); });
            dlg.addEventListener('close', function () {
                var ok = dlg.returnValue === 'ok';
                dlg.remove();
                resolve(ok);
            });
            document.body.appendChild(dlg);
            dlg.showModal();
            btns[0].focus();
        });
    };

    // Light / dark toggle (choice is remembered in this browser)
    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-theme-toggle]')) return;
        var light = document.documentElement.getAttribute('data-theme') === 'light';
        var next = light ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', next);
        try { localStorage.setItem('hs-theme', next); } catch (err) {}
    });

    // <form data-confirm="Message"> asks before submitting
    document.addEventListener('submit', function (e) {
        var form = e.target;
        var msg = form.getAttribute && form.getAttribute('data-confirm');
        if (!msg) return;
        e.preventDefault();
        window.confirmDialog(msg, { confirmText: form.getAttribute('data-confirm-label') || 'Confirm', danger: true })
            .then(function (ok) { if (ok) form.submit(); });
    }, true);

    // <input data-autosubmit> submits its form when changed
    document.addEventListener('change', function (e) {
        if (e.target.matches && e.target.matches('[data-autosubmit]') && e.target.form) e.target.form.submit();
    });

    // <button data-toggle-password="#password">
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-toggle-password]');
        if (!btn) return;
        var input = document.querySelector(btn.getAttribute('data-toggle-password'));
        if (!input) return;
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-pressed', show ? 'true' : 'false');
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });

    // <input data-match="#password"> must equal the other field
    function checkMatch(input) {
        var other = document.querySelector(input.getAttribute('data-match'));
        if (!other) return;
        input.setCustomValidity(input.value && input.value !== other.value ? 'Passwords do not match.' : '');
    }
    document.querySelectorAll('[data-match]').forEach(function (input) {
        var other = document.querySelector(input.getAttribute('data-match'));
        input.addEventListener('input', function () { checkMatch(input); });
        if (other) other.addEventListener('input', function () { checkMatch(input); });
    });

    // Live text filter: <input data-filter-input data-filter-target="#list"> over [data-row] items
    function toggleEmpty(scope, visible) {
        var empty = scope.querySelector('[data-filter-empty]');
        if (empty) empty.hidden = visible > 0;
    }
    document.querySelectorAll('[data-filter-input]').forEach(function (input) {
        var scope = document.querySelector(input.getAttribute('data-filter-target'));
        if (!scope) return;
        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase(), shown = 0;
            scope.querySelectorAll('[data-row]').forEach(function (row) {
                var hit = row.textContent.toLowerCase().indexOf(q) !== -1;
                row.hidden = !hit;
                if (hit) shown++;
            });
            toggleEmpty(scope, shown);
        });
    });

    // Client-side status chips: <div data-chip-filter data-target="#list"> with .chip[data-value]
    document.querySelectorAll('[data-chip-filter]').forEach(function (group) {
        var scope = document.querySelector(group.getAttribute('data-target'));
        if (!scope) return;
        var items = scope.querySelectorAll('[data-status]');

        group.querySelectorAll('.chip').forEach(function (chip) {
            var v = chip.getAttribute('data-value');
            var n = v === 'all' ? items.length : scope.querySelectorAll('[data-status="' + v + '"]').length;
            var c = chip.querySelector('.count');
            if (c) c.textContent = n;

            chip.addEventListener('click', function () {
                group.querySelectorAll('.chip').forEach(function (o) {
                    o.classList.toggle('is-active', o === chip);
                    o.setAttribute('aria-pressed', o === chip ? 'true' : 'false');
                });
                var shown = 0;
                items.forEach(function (it) {
                    var hit = v === 'all' || it.getAttribute('data-status') === v;
                    it.hidden = !hit;
                    if (hit) shown++;
                });
                toggleEmpty(scope, shown);
            });
        });
    });
})();
