const installButton = document.getElementById('installButton');
const cardDate = document.getElementById('cardDate');
const conciergeForm = document.getElementById('conciergeForm');
const submitButton = document.getElementById('submitButton');
const formToast = document.getElementById('formToast');

if (cardDate) {
    const now = new Date();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = String(now.getFullYear()).slice(-2);
    cardDate.textContent = `${month}/${year}`;
}

let deferredPrompt = null;

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredPrompt = event;
    if (installButton) {
        installButton.hidden = false;
    }
});

if (installButton) {
    installButton.addEventListener('click', async () => {
        if (!deferredPrompt) {
            return;
        }
        deferredPrompt.prompt();
        await deferredPrompt.userChoice;
        deferredPrompt = null;
        installButton.hidden = true;
    });
}

if (conciergeForm) {
    conciergeForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Отправляем...';
        }
        if (formToast) {
            formToast.hidden = true;
        }

        try {
            const response = await fetch(conciergeForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(conciergeForm),
            });
            const payload = await response.json();
            if (formToast) {
                formToast.textContent = payload.message || 'Запрос отправлен.';
                formToast.hidden = false;
                formToast.classList.toggle('toast--error', !payload.success);
            }
            if (payload.success) {
                conciergeForm.reset();
            }
        } catch (error) {
            if (formToast) {
                formToast.textContent = 'Сервис временно недоступен. Попробуйте позже.';
                formToast.hidden = false;
                formToast.classList.add('toast--error');
            }
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Отправить запрос';
            }
        }
    });
}

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js');
    });
}
