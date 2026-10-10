// Patient: cancel an appointment. <button data-cancel-id="5">
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('[data-cancel-id]');
    if (!btn) return;

    const ok = await confirmDialog('If you already paid, the fee is refunded automatically.', {
        title: 'Cancel this appointment?', confirmText: 'Cancel appointment', danger: true
    });
    if (!ok) return;

    btn.disabled = true;
    const fd = new FormData();
    fd.append('appointment_id', btn.dataset.cancelId);

    try {
        const res  = await fetch(window.APP.baseUrl + 'api/cancel_appointment.php', { method: 'POST', body: fd });
        const data = await res.json();
        showMessage(data.message, data.success);
        if (data.success) {
            setTimeout(function () { location.reload(); }, 900);
        } else {
            btn.disabled = false;
        }
    } catch (err) {
        showMessage('Network error. Please try again.', false);
        btn.disabled = false;
    }
});
