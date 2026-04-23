// Auto-dismiss flash messages after 4s
document.querySelectorAll('.flash').forEach(el => {
    setTimeout(() => el.remove(), 4000);
});

// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
        if (!confirm(btn.dataset.confirm)) e.preventDefault();
    });
});
