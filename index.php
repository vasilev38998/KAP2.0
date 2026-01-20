<?php
session_start();

$brand = [
    'name' => 'Kapouch',
    'address' => 'Россия, Иркутская область, г. Шелехов, Култукский тракт 25/1',
    'tagline' => 'Кофе с собой',
    'support' => '+7 (3952) 50-10-11',
];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($brand['name']) ?> — Loyalty App</title>
    <meta name="description" content="Kapouch Loyalty — приложение для накопления баллов и бесплатного 6-го кофе.">
    <meta name="theme-color" content="#f7cf28">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" href="/assets/icon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="app" id="appRoot">
        <header class="app-header">
            <div class="logo">
                <span class="logo-mark">K</span>
                <div>
                    <div class="logo-title"><?= htmlspecialchars($brand['name']) ?></div>
                    <div class="logo-subtitle"><?= htmlspecialchars($brand['tagline']) ?></div>
                </div>
            </div>
            <button class="install-btn" id="installButton" hidden>Установить PWA</button>
        </header>

        <section class="auth" id="authScreen">
            <div class="auth-card">
                <h1>Вход по номеру телефона</h1>
                <p>Введите номер — мы отправим код подтверждения. В демо‑режиме используйте код <strong>1234</strong>.</p>
                <form id="authForm" class="auth-form">
                    <label>
                        Телефон
                        <input type="tel" name="phone" placeholder="+7 (999) 000-00-00" required>
                    </label>
                    <label class="otp-field" id="otpField" hidden>
                        Код из SMS
                        <input type="text" name="otp" placeholder="1234" maxlength="4" inputmode="numeric">
                    </label>
                    <button type="submit" class="primary" id="authButton">Получить код</button>
                </form>
                <p class="auth-note">Нажимая «Получить код», вы соглашаетесь с правилами программы лояльности.</p>
            </div>
        </section>

        <section class="dashboard" id="dashboard" hidden>
            <div class="balance-card">
                <div>
                    <p class="balance-label">Ваш баланс</p>
                    <h2><span id="pointsBalance">0</span> баллов</h2>
                    <p class="balance-sub">Кэшбэк до <span id="cashbackPercent">6%</span> за каждый заказ</p>
                </div>
                <button class="ghost" id="scanButton">Сканировать QR</button>
            </div>

            <div class="stamp-card">
                <div class="stamp-head">
                    <h3>Карта 6‑го кофе</h3>
                    <span>5 штампов = 6‑я чашка бесплатно</span>
                </div>
                <div class="stamp-grid" id="stampGrid"></div>
                <button class="primary" id="addStamp">Добавить покупку</button>
            </div>

            <div class="quick-actions">
                <button class="quick-card" data-tab="offers">
                    <span class="quick-title">Акции дня</span>
                    <span class="quick-sub">Сезонные напитки</span>
                </button>
                <button class="quick-card" data-tab="wallet">
                    <span class="quick-title">Кошелёк</span>
                    <span class="quick-sub">Баллы и штампы</span>
                </button>
                <button class="quick-card" data-tab="profile">
                    <span class="quick-title">Профиль</span>
                    <span class="quick-sub">Телефон и статус</span>
                </button>
            </div>

            <div class="section-block">
                <div class="section-head">
                    <h3>Персональные предложения</h3>
                    <a href="#" class="section-link">Все</a>
                </div>
                <div class="offer-grid">
                    <article class="offer-card">
                        <h4>Капучино + круассан</h4>
                        <p>Списывайте 120 баллов и получите сет со скидкой 35%.</p>
                        <span>120 баллов</span>
                    </article>
                    <article class="offer-card">
                        <h4>Double Espresso</h4>
                        <p>Начислим +2 штампа за утренний заказ до 11:00.</p>
                        <span>Сегодня</span>
                    </article>
                </div>
            </div>

            <div class="section-block" id="historyBlock">
                <div class="section-head">
                    <h3>История заказов</h3>
                    <a href="#" class="section-link">Все</a>
                </div>
                <div class="history-list" id="historyList"></div>
            </div>
        </section>

        <section class="tab" id="tabOffers" hidden>
            <div class="tab-header">
                <h2>Акции</h2>
                <p>Персональные бонусы и сезонные предложения.</p>
            </div>
            <div class="offer-grid">
                <article class="offer-card">
                    <h4>Лавандовый латте</h4>
                    <p>Списывайте 90 баллов и пробуйте новый вкус.</p>
                    <span>90 баллов</span>
                </article>
                <article class="offer-card">
                    <h4>Завтрак Kapouch</h4>
                    <p>Кофе + тартин со скидкой 25% до 12:00.</p>
                    <span>Утро</span>
                </article>
            </div>
        </section>

        <section class="tab" id="tabWallet" hidden>
            <div class="tab-header">
                <h2>Кошелёк</h2>
                <p>Баллы, штампы и бесплатные чашки.</p>
            </div>
            <div class="wallet-summary">
                <div>
                    <span>Баллы</span>
                    <strong id="walletPoints">0</strong>
                </div>
                <div>
                    <span>Бесплатных чашек</span>
                    <strong id="walletFree">0</strong>
                </div>
                <div>
                    <span>Статус</span>
                    <strong id="walletTier">Silver</strong>
                </div>
            </div>
            <button class="ghost" id="redeemCup">Списать бесплатную чашку</button>
        </section>

        <section class="tab" id="tabProfile" hidden>
            <div class="tab-header">
                <h2>Профиль</h2>
                <p>Настройки аккаунта и контактные данные.</p>
            </div>
            <div class="profile-card">
                <div>
                    <span class="profile-label">Телефон</span>
                    <strong id="profilePhone">—</strong>
                </div>
                <div>
                    <span class="profile-label">Статус</span>
                    <strong id="profileTier">Silver</strong>
                </div>
                <div>
                    <span class="profile-label">Поддержка</span>
                    <strong><?= htmlspecialchars($brand['support']) ?></strong>
                </div>
            </div>
            <button class="ghost" id="logoutButton">Выйти</button>
        </section>

        <nav class="bottom-nav" id="bottomNav" hidden>
            <button class="bottom-link is-active" data-tab="home">Главная</button>
            <button class="bottom-link" data-tab="offers">Акции</button>
            <button class="bottom-link" data-tab="wallet">Кошелёк</button>
            <button class="bottom-link" data-tab="profile">Профиль</button>
        </nav>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>
