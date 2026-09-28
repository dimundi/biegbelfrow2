document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!form.matches('#registerform')) return;

    if (form.dataset.submitting === '1') {
        event.preventDefault();
        return;
    }

    form.dataset.submitting = '1';
    var button = form.querySelector('[type="submit"]');
    if (button) {
        button.disabled = true;
        button.value = 'Tworzenie konta…';
    }
});
