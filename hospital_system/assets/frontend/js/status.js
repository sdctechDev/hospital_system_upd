// Doctor and admin: change an appointment's status. Buttons look like:
// <button data-status-action data-id="5" data-status="completed">Complete</button>
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-status-action]');
    if (!btn) return;

    if (btn.dataset.status === 'cancelled') {
        const ok = await confirmDialog('The patient is refunded automatically if they already paid.', {
            title: 'Cancel this appointment?', confirmText: 'Cancel appointment', danger: true
        });
        if (!ok) return;
    }

    btn.disabled = true;
    const fd = new FormData();
    fd.append('appointment_id', btn.dataset.id);
    fd.append('status', btn.dataset.status);

    try {
        const res = await fetch(window.APP.baseUrl + 'api/update_appointment_status.php', {
            method: 'POST',
            body: fd
        });
        const data = await res.json();
        showMessage(data.message, data.success);
        if (data.success) {
            setTimeout(() => location.reload(), 900);
        } else {
            btn.disabled = false;
        }
    } catch (err) {
        showMessage('Network error. Please try again.', false);
        btn.disabled = false;
    }
});
