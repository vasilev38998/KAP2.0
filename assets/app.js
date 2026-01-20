const installButton = document.getElementById('installButton');

const authScreen = document.getElementById('authScreen');
const dashboard = document.getElementById('dashboard');
const authForm = document.getElementById('authForm');
const authButton = document.getElementById('authButton');
const otpField = document.getElementById('otpField');
const authError = document.getElementById('authError');
const bottomNav = document.getElementById('bottomNav');

const tabOffers = document.getElementById('tabOffers');
const tabWallet = document.getElementById('tabWallet');
const tabProfile = document.getElementById('tabProfile');

const stampGrid = document.getElementById('stampGrid');
const historyList = document.getElementById('historyList');
const pointsBalance = document.getElementById('pointsBalance');
const cashbackPercent = document.getElementById('cashbackPercent');
const walletPoints = document.getElementById('walletPoints');
const walletFree = document.getElementById('walletFree');
const walletTier = document.getElementById('walletTier');
const profilePhone = document.getElementById('profilePhone');
const profileTier = document.getElementById('profileTier');

const addStampButton = document.getElementById('addStamp');
const redeemCupButton = document.getElementById('redeemCup');
const scanButton = document.getElementById('scanButton');
const logoutButton = document.getElementById('logoutButton');

const storageKey = 'kapouch-loyalty-profile';
const loyaltyKey = 'kapouch-loyalty-state';

const tiers = [
    { name: 'Silver', threshold: 0, cashback: 4 },
    { name: 'Gold', threshold: 800, cashback: 7 },
    { name: 'Black', threshold: 1800, cashback: 10 },
];

const defaultState = {
    stamps: 0,
    points: 0,
    freeCups: 0,
    history: [],
};

const loadState = () => {
    try {
        const saved = localStorage.getItem(loyaltyKey);
        return saved ? { ...defaultState, ...JSON.parse(saved) } : { ...defaultState };
    } catch (error) {
        return { ...defaultState };
    }
};

const saveState = (state) => {
    localStorage.setItem(loyaltyKey, JSON.stringify(state));
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
    state.history.slice(0, 5).forEach((entry) => {
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

    if (pointsBalance) pointsBalance.textContent = state.points;
    if (cashbackPercent) cashbackPercent.textContent = `${tier.cashback}%`;
    if (walletPoints) walletPoints.textContent = state.points;
    if (walletFree) walletFree.textContent = state.freeCups;
    if (walletTier) walletTier.textContent = next ? next.name : 'Black';
    if (profileTier) profileTier.textContent = tier.name;
};

const addHistory = (state, entry) => {
    state.history.unshift(entry);
    state.history = state.history.slice(0, 12);
};

const simulatePurchase = (state) => {
    state.stamps += 1;
    state.points += 120;
    addHistory(state, {
        title: 'Капучино / To-go',
        subtitle: 'Начислено 1 штамп + 120 баллов',
        points: '+120',
    });
    if (state.stamps >= 5) {
        state.freeCups += 1;
        state.stamps = 0;
        addHistory(state, {
            title: 'Бесплатная чашка доступна',
            subtitle: 'Можно списать 6‑ю чашку.',
            points: '+1',
        });
    }
};

const redeemCup = (state) => {
    if (state.freeCups <= 0) {
        addHistory(state, {
            title: 'Недостаточно штампов',
            subtitle: 'Накопите 5 штампов для бесплатной чашки.',
            points: '0',
        });
        return;
    }
    state.freeCups -= 1;
    addHistory(state, {
        title: 'Списана 6‑я чашка',
        subtitle: 'Наслаждайтесь напитком бесплатно.',
        points: '0',
    });
};

const renderAll = (state) => {
    renderStamps(state);
    renderHistory(state);
    renderStats(state);
    saveState(state);
};

const showTab = (tab) => {
    if (!dashboard || !tabOffers || !tabWallet || !tabProfile) return;
    dashboard.hidden = tab !== 'home';
    tabOffers.hidden = tab !== 'offers';
    tabWallet.hidden = tab !== 'wallet';
    tabProfile.hidden = tab !== 'profile';

    const links = bottomNav?.querySelectorAll('.bottom-link');
    links?.forEach((link) => {
        link.classList.toggle('is-active', link.dataset.tab === tab);
    });
};

const setAuthenticated = (profile) => {
    authScreen.hidden = true;
    dashboard.hidden = false;
    bottomNav.hidden = false;
    if (profilePhone) profilePhone.textContent = profile.phone;
    showTab('home');
};

const setLoggedOut = () => {
    authScreen.hidden = false;
    dashboard.hidden = true;
    tabOffers.hidden = true;
    tabWallet.hidden = true;
    tabProfile.hidden = true;
    bottomNav.hidden = true;
};

const handleAuth = () => {
    const storedProfile = localStorage.getItem(storageKey);
    if (storedProfile) {
        setAuthenticated(JSON.parse(storedProfile));
    } else {
        setLoggedOut();
    }
};

const showError = (message) => {
    if (!authError) return;
    authError.textContent = message;
    authError.hidden = false;
};

const clearError = () => {
    if (!authError) return;
    authError.hidden = true;
};

if (authForm && authButton && otpField) {
    authForm.addEventListener('submit', (event) => {
        event.preventDefault();
    });

    authButton.addEventListener('click', () => {
        const formData = new FormData(authForm);
        const phone = String(formData.get('phone') || '').trim();
        const otp = String(formData.get('otp') || '').trim();

        clearError();

        if (!phone) {
            showError('Введите номер телефона, чтобы получить код.');
            return;
        }

        if (otpField.hidden) {
            otpField.hidden = false;
            otpField.classList.add('is-visible');
            authButton.textContent = 'Войти';
            return;
        }

        if (otp !== '1234') {
            showError('Неверный код. Попробуйте 1234.');
            return;
        }

        const profile = { phone };
        localStorage.setItem(storageKey, JSON.stringify(profile));
        setAuthenticated(profile);
    });
}

if (bottomNav) {
    bottomNav.addEventListener('click', (event) => {
        const target = event.target.closest('.bottom-link');
        if (!target) return;
        showTab(target.dataset.tab);
    });
}

const quickCards = document.querySelectorAll('.quick-card');
quickCards.forEach((card) => {
    card.addEventListener('click', () => {
        showTab(card.dataset.tab);
    });
});

const state = loadState();
renderAll(state);

addStampButton?.addEventListener('click', () => {
    simulatePurchase(state);
    renderAll(state);
});

redeemCupButton?.addEventListener('click', () => {
    redeemCup(state);
    renderAll(state);
});

scanButton?.addEventListener('click', () => {
    addHistory(state, {
        title: 'Сканирование QR',
        subtitle: 'QR‑код готов для кассира.',
        points: '0',
    });
    renderAll(state);
});

logoutButton?.addEventListener('click', () => {
    localStorage.removeItem(storageKey);
    setLoggedOut();
});

handleAuth();

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

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js');
    });
}
