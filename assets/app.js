const installButton = document.getElementById('installButton');
const cardDate = document.getElementById('cardDate');
const conciergeForm = document.getElementById('conciergeForm');
const submitButton = document.getElementById('submitButton');
const formToast = document.getElementById('formToast');

const stampGrid = document.getElementById('stampGrid');
const historyList = document.getElementById('historyList');
const pointsValue = document.getElementById('pointsValue');
const pointsWallet = document.getElementById('pointsWallet');
const cashbackValue = document.getElementById('cashbackValue');
const freeCupsValue = document.getElementById('freeCupsValue');
const nextTier = document.getElementById('nextTier');

const actions = document.querySelectorAll('[data-action]');
const storageKey = 'kapouch-loyalty';

const defaultState = {
    stamps: 0,
    points: 0,
    freeCups: 0,
    history: [],
};

const tiers = [
    { name: 'Bronze', threshold: 0, cashback: 3 },
    { name: 'Silver', threshold: 500, cashback: 6 },
    { name: 'Gold', threshold: 1200, cashback: 9 },
    { name: 'Black', threshold: 2000, cashback: 12 },
];

const loadState = () => {
    try {
        const saved = localStorage.getItem(storageKey);
        return saved ? { ...defaultState, ...JSON.parse(saved) } : { ...defaultState };
    } catch (error) {
        return { ...defaultState };
    }
};

const saveState = (state) => {
    localStorage.setItem(storageKey, JSON.stringify(state));
};

const getTier = (points) => {
    const sorted = [...tiers].sort((a, b) => b.threshold - a.threshold);
    return sorted.find((tier) => points >= tier.threshold) || tiers[0];
};

const getNextTier = (points) => {
    const sorted = [...tiers].sort((a, b) => a.threshold - b.threshold);
    return sorted.find((tier) => points < tier.threshold);
};

const renderStamps = (state) => {
    if (!stampGrid) return;
    stampGrid.innerHTML = '';
    for (let i = 0; i < 6; i += 1) {
        const stamp = document.createElement('div');
        stamp.className = 'stamp';
        if (i < state.stamps) {
            stamp.classList.add('stamp--filled');
            stamp.textContent = '✓';
        } else {
            stamp.textContent = String(i + 1);
        }
        stampGrid.appendChild(stamp);
    }
};

const renderHistory = (state) => {
    if (!historyList) return;
    historyList.innerHTML = '';
    if (state.history.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'history-empty';
        empty.textContent = 'Пока нет заказов. Добавьте первую покупку.';
        historyList.appendChild(empty);
        return;
    }
    state.history.slice(0, 6).forEach((entry) => {
        const item = document.createElement('div');
        item.className = 'history-item';
        item.innerHTML = `
            <div>
                <strong>${entry.title}</strong>
                <span>${entry.subtitle}</span>
            </div>
            <span class="history-points">${entry.points}</span>
        `;
        historyList.appendChild(item);
    });
};

const renderStats = (state) => {
    const tier = getTier(state.points);
    const next = getNextTier(state.points);
    if (pointsValue) pointsValue.textContent = state.points;
    if (pointsWallet) pointsWallet.textContent = `${state.points} баллов`;
    if (cashbackValue) cashbackValue.textContent = `${tier.cashback}%`;
    if (freeCupsValue) freeCupsValue.textContent = state.freeCups;
    if (nextTier) nextTier.textContent = next ? next.name : 'Black';
};

const renderAll = (state) => {
    renderStamps(state);
    renderHistory(state);
    renderStats(state);
};

const addHistory = (state, entry) => {
    state.history.unshift(entry);
    state.history = state.history.slice(0, 12);
};

const simulatePurchase = (state) => {
    state.stamps += 1;
    state.points += 80;
    addHistory(state, {
        title: 'Latte / To-go',
        subtitle: 'Начислено 1 штамп + 80 баллов',
        points: '+80',
    });
    if (state.stamps >= 5) {
        state.freeCups += 1;
        state.stamps = 0;
        addHistory(state, {
            title: 'Бесплатная чашка доступна',
            subtitle: 'Поздравляем! Можно списать 6‑ю чашку.',
            points: '+1',
        });
    }
};

const redeemCup = (state) => {
    if (state.freeCups <= 0) return false;
    state.freeCups -= 1;
    addHistory(state, {
        title: 'Списана 6‑я чашка',
        subtitle: 'Наслаждайтесь напитком бесплатно.',
        points: '0',
    });
    return true;
};

const state = loadState();
renderAll(state);

actions.forEach((button) => {
    button.addEventListener('click', () => {
        const action = button.dataset.action;
        if (action === 'simulate') {
            simulatePurchase(state);
        }
        if (action === 'redeem') {
            const success = redeemCup(state);
            if (!success) {
                addHistory(state, {
                    title: 'Недостаточно штампов',
                    subtitle: 'Накопите 5 штампов для бесплатной чашки.',
                    points: '0',
                });
            }
        }
        saveState(state);
        renderAll(state);
    });
});

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
