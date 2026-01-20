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
    <meta name="description" content="Kapouch Loyalty — платформа баллов, штампов, подарков и персональных уведомлений.">
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
                <div class="status-bar">
                    <span>14:21</span>
                    <div class="status-icons">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="battery">43</span>
                    </div>
                </div>
                <h1>Вход по номеру телефона</h1>
                <p>Введите номер — мы отправим код подтверждения. В демо‑режиме используйте код <strong>1234</strong>.</p>
                <form id="authForm" class="auth-form" novalidate>
                    <div class="auth-row">
                        <label>
                            Телефон
                            <input type="tel" name="phone" placeholder="+7 (999) 000-00-00" inputmode="tel">
                        </label>
                        <button type="button" class="primary" id="authButton">Получить код</button>
                    </div>
                    <label class="otp-field" id="otpField" hidden>
                        Код из SMS
                        <input type="text" name="otp" placeholder="1234" maxlength="4" inputmode="numeric">
                    </label>
                </form>
                <p class="auth-error" id="authError" hidden></p>
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

            <div class="insight-grid">
                <div class="insight-card">
                    <span>Серия визитов</span>
                    <strong><span id="streakValue">0</span> дней подряд</strong>
                    <small>+25 баллов за 3 дня подряд</small>
                </div>
                <div class="insight-card">
                    <span>Эко‑бонус</span>
                    <strong id="ecoStatus">Не активен</strong>
                    <small>+10 баллов за свой стакан</small>
                </div>
                <div class="insight-card">
                    <span>Любимый напиток</span>
                    <strong id="favoriteDrink">Не выбран</strong>
                    <small>Скидка 5% на любимый вкус</small>
                </div>
            </div>

            <div class="stamp-card">
                <div class="stamp-head">
                    <h3>Карта 6‑го кофе</h3>
                    <span>5 штампов = 6‑я чашка бесплатно</span>
                </div>
                <div class="stamp-grid" id="stampGrid"></div>
                <button class="primary" id="addStamp">Добавить покупку</button>
            </div>

            <div class="pickup-card">
                <div class="section-head">
                    <h3>Заказ заранее</h3>
                    <button class="ghost" id="startPickup">Запустить таймер</button>
                </div>
                <div class="pickup-row">
                    <label>
                        Время получения
                        <select id="pickupTime">
                            <option value="10">Через 10 минут</option>
                            <option value="20">Через 20 минут</option>
                            <option value="30">Через 30 минут</option>
                            <option value="45">Через 45 минут</option>
                        </select>
                    </label>
                    <div class="pickup-status" id="pickupStatus">Ожидает запуска</div>
                </div>
            </div>

            <div class="notification-card">
                <div class="section-head">
                    <h3>Уведомления</h3>
                    <button class="ghost" id="testPush">Тест‑пуш</button>
                </div>
                <div class="notification-list" id="notificationList"></div>
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

            <div class="section-block">
                <div class="section-head">
                    <h3>Ваша миссия недели</h3>
                    <a href="#" class="section-link">Прогресс</a>
                </div>
                <div class="mission-card">
                    <div>
                        <strong>3 визита за неделю</strong>
                        <span>Получите +200 баллов и фирменный десерт.</span>
                    </div>
                    <div class="mission-progress">
                        <div class="mission-bar" id="missionBar"></div>
                    </div>
                </div>
            </div>

            <div class="section-block">
                <div class="section-head">
                    <h3>Реферальная программа</h3>
                    <a href="#" class="section-link">Пригласить</a>
                </div>
                <div class="referral-card">
                    <div>
                        <strong>Ваш код</strong>
                        <span id="referralCode">KAP-7421</span>
                    </div>
                    <button class="ghost" id="copyReferral">Скопировать</button>
                </div>
            </div>

            <div class="section-block">
                <div class="section-head">
                    <h3>Что нового в Kapouch</h3>
                    <a href="#" class="section-link">12 функций</a>
                </div>
                <div class="innovation-grid">
                    <article class="innovation-card">Подарок ко дню рождения +150 баллов</article>
                    <article class="innovation-card">Push‑уведомления о новых напитках</article>
                    <article class="innovation-card">QR‑оплата без кассы</article>
                    <article class="innovation-card">Заказ заранее и pickup‑таймер</article>
                    <article class="innovation-card">Избранные напитки и быстрый заказ</article>
                    <article class="innovation-card">Подписка «Кофе каждое утро»</article>
                    <article class="innovation-card">Эко‑бонус за собственный стакан</article>
                    <article class="innovation-card">Стрики за ежедневные визиты</article>
                    <article class="innovation-card">Партнёрские предложения рядом</article>
                    <article class="innovation-card">Отзывы за бонусные баллы</article>
                    <article class="innovation-card">Внутренний кошелёк и подарочные карты</article>
                    <article class="innovation-card">Семейные аккаунты и общий баланс</article>
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
                <label class="profile-field">
                    <span class="profile-label">Телефон</span>
                    <strong id="profilePhone">—</strong>
                </label>
                <label class="profile-field">
                    <span class="profile-label">Дата рождения</span>
                    <input type="date" id="birthdayInput">
                </label>
                <label class="profile-field">
                    <span class="profile-label">Любимый напиток</span>
                    <select id="favoriteSelect">
                        <option value="">Не выбран</option>
                        <option value="Капучино">Капучино</option>
                        <option value="Латте">Латте</option>
                        <option value="Флэт уайт">Флэт уайт</option>
                        <option value="Американо">Американо</option>
                    </select>
                </label>
                <label class="profile-field">
                    <span class="profile-label">Статус</span>
                    <strong id="profileTier">Silver</strong>
                </label>
                <label class="profile-field">
                    <span class="profile-label">Уведомления</span>
                    <div class="toggle">
                        <input type="checkbox" id="promoToggle">
                        <span></span>
                    </div>
                </label>
                <label class="profile-field">
                    <span class="profile-label">Push поздравления</span>
                    <div class="toggle">
                        <input type="checkbox" id="birthdayToggle">
                        <span></span>
                    </div>
                </label>
                <label class="profile-field">
                    <span class="profile-label">Эко‑бонус</span>
                    <div class="toggle">
                        <input type="checkbox" id="ecoToggle">
                        <span></span>
                    </div>
                </label>
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
