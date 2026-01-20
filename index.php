<?php
session_start();

$brand = [
    'name' => 'Kapouch',
    'address' => 'Россия, Иркутская область, г. Шелехов, Култукский тракт 25/1',
    'tagline' => 'Кофе с собой',
];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

$notice = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $notice = 'Запрос получен. Concierge свяжется с вами в течение 24 часов.';
    }
    if ($_GET['status'] === 'error') {
        $notice = 'Не удалось отправить запрос. Попробуйте еще раз или свяжитесь с нами напрямую.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($brand['name']) ?> — Loyalty PWA</title>
    <meta name="description" content="Loyalty PWA для Kapouch: каждая 6-я чашка бесплатно, кэшбэк баллами и удобная оплата СБП.">
    <meta name="theme-color" content="#f7cf28">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" href="/assets/icon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="page">
        <header class="hero" id="top">
            <nav class="nav">
                <div class="logo">
                    <span class="logo-mark">K</span>
                    <div>
                        <div class="logo-title"><?= htmlspecialchars($brand['name']) ?></div>
                        <div class="logo-subtitle"><?= htmlspecialchars($brand['tagline']) ?></div>
                    </div>
                </div>
                <div class="nav-actions">
                    <a class="nav-link" href="#wallet">Карта</a>
                    <a class="nav-link" href="#rewards">Награды</a>
                    <a class="nav-link" href="#payments">Оплата</a>
                    <a class="nav-link" href="#concierge">Concierge</a>
                    <button class="install-btn" id="installButton" hidden>Установить PWA</button>
                </div>
            </nav>
            <div class="hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow">Loyalty для кофе с собой</p>
                    <h1>6‑я чашка бесплатно + кэшбэк баллами</h1>
                    <p class="lead">
                        Kapouch Loyalty — это цифровая карта гостя, в которой каждая покупка превращается в баллы и штампы. Накопили
                        5 штампов — получаете 6‑ю чашку бесплатно. Баллы можно тратить на апгрейды и лимитированные позиции.
                    </p>
                    <div class="hero-actions">
                        <button class="primary" data-action="simulate">Добавить покупку</button>
                        <button class="ghost" data-action="redeem">Списать 6‑ю чашку</button>
                    </div>
                    <div class="hero-meta">
                        <div>
                            <span class="meta-label">Адрес</span>
                            <span class="meta-value"><?= htmlspecialchars($brand['address']) ?></span>
                        </div>
                        <div>
                            <span class="meta-label">Поддержка</span>
                            <span class="meta-value">concierge@kapouch.ru</span>
                        </div>
                    </div>
                    <div class="stats-grid">
                        <div class="stat">
                            <span class="stat-value" id="pointsValue">0</span>
                            <span class="stat-label">Баллов доступно</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value" id="cashbackValue">0%</span>
                            <span class="stat-label">Кэшбэк с заказа</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value" id="freeCupsValue">0</span>
                            <span class="stat-label">Бесплатных чашек</span>
                        </div>
                    </div>
                </div>
                <div class="hero-card">
                    <div class="card-top">
                        <span class="card-chip"></span>
                        <span class="card-tier">LOYALTY</span>
                    </div>
                    <div class="card-number">•••• 9182</div>
                    <div class="card-name">GUEST NO. 00024</div>
                    <div class="card-footer">
                        <span>Kapouch</span>
                        <span id="cardDate">--/--</span>
                    </div>
                    <div class="card-badge">To‑go format</div>
                </div>
            </div>
        </header>

        <main>
            <?php if ($notice !== ''): ?>
                <div class="status-banner" role="status">
                    <?= htmlspecialchars($notice) ?>
                </div>
            <?php endif; ?>

            <section class="section wallet" id="wallet">
                <div class="section-head">
                    <h2>Цифровая карта гостя</h2>
                    <p>Все бонусы, штампы и история заказов — в одном экране, без пластика и лишних приложений.</p>
                </div>
                <div class="wallet-grid">
                    <div class="wallet-card">
                        <div class="wallet-head">
                            <h3>Штампы за кофе</h3>
                            <span class="wallet-sub">5 штампов = 6‑я чашка бесплатно</span>
                        </div>
                        <div class="stamp-grid" id="stampGrid"></div>
                        <p class="wallet-note">Штампы начисляются автоматически после оплаты через СБП.</p>
                    </div>
                    <div class="wallet-card">
                        <div class="wallet-head">
                            <h3>Баллы и кэшбэк</h3>
                            <span class="wallet-sub">до 12% от суммы заказа</span>
                        </div>
                        <div class="points-box">
                            <div>
                                <span class="points-label">Доступно</span>
                                <span class="points-value" id="pointsWallet">0</span>
                            </div>
                            <div>
                                <span class="points-label">Следующий статус</span>
                                <span class="points-value" id="nextTier">Silver</span>
                            </div>
                        </div>
                        <p class="wallet-note">Баллы можно обменивать на сиропы, апгрейд размера и лимитированные напитки.</p>
                    </div>
                </div>
            </section>

            <section class="section rewards" id="rewards">
                <div class="section-head">
                    <h2>Награды и апгрейды</h2>
                    <p>Баллы и бесплатные чашки открывают доступ к дополнительным привилегиям.</p>
                </div>
                <div class="reward-grid">
                    <article>
                        <h3>6‑я чашка бесплатно</h3>
                        <p>Накопите 5 штампов и получите шестую чашку любого напитка бесплатно.</p>
                        <span class="reward-tag">Штампы</span>
                    </article>
                    <article>
                        <h3>Апгрейд размера</h3>
                        <p>Списывайте баллы, чтобы увеличить напиток до большего объема.</p>
                        <span class="reward-tag">120 баллов</span>
                    </article>
                    <article>
                        <h3>Лимитированный релиз</h3>
                        <p>Доступ к сезонным напиткам раньше остальных гостей.</p>
                        <span class="reward-tag">VIP</span>
                    </article>
                </div>
            </section>

            <section class="section payments" id="payments">
                <div class="section-head">
                    <h2>Оплата и фискализация</h2>
                    <p>Единый поток: СБП‑оплата через Тинькофф, фискальные чеки и мгновенное начисление бонусов.</p>
                </div>
                <div class="payment-grid">
                    <div class="payment-card">
                        <h3>СБП QR‑платеж</h3>
                        <ul>
                            <li>QR‑код формируется кассой или в PWA.</li>
                            <li>Оплата подтверждается в приложении банка.</li>
                            <li>Бонусы начисляются мгновенно.</li>
                        </ul>
                    </div>
                    <div class="payment-card">
                        <h3>54‑ФЗ чеки</h3>
                        <ul>
                            <li>Фискализация через Тинькофф ОФД.</li>
                            <li>Чек доступен в личном кабинете.</li>
                            <li>Отправка по SMS или email.</li>
                        </ul>
                    </div>
                    <div class="payment-card">
                        <h3>Shared‑хостинг ready</h3>
                        <ul>
                            <li>PHP 8.x + MySQL, без фоновых сервисов.</li>
                            <li>Vanilla HTML/CSS/JS без Node.</li>
                            <li>Сервис‑воркер для офлайн‑режима.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="section history">
                <div class="section-head">
                    <h2>История визитов</h2>
                    <p>Прозрачная история начислений и списаний.</p>
                </div>
                <div class="history-list" id="historyList"></div>
            </section>

            <section class="section concierge" id="concierge">
                <div class="section-head">
                    <h2>Concierge для корпоративных заказов</h2>
                    <p>Партнерский coffee‑service для офисов и мероприятий.</p>
                </div>
                <form class="concierge-form" id="conciergeForm" action="/submit.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <label>
                        Имя гостя
                        <input type="text" name="guest_name" placeholder="Анна" maxlength="60" required>
                    </label>
                    <label>
                        Контакт
                        <input type="text" name="contact" placeholder="Телефон или e-mail" maxlength="80" required>
                    </label>
                    <label>
                        Комментарий
                        <textarea name="preferences" placeholder="Например: корпоративные заказы, доставить к 10:00" maxlength="400"></textarea>
                    </label>
                    <button type="submit" class="primary" id="submitButton">Отправить запрос</button>
                    <p class="form-note">Мы используем данные только для связи и персонализации сервиса.</p>
                </form>
                <div class="toast" id="formToast" hidden></div>
            </section>
        </main>

        <footer class="footer">
            <div>
                <strong><?= htmlspecialchars($brand['name']) ?></strong>
                <p><?= htmlspecialchars($brand['address']) ?></p>
            </div>
            <div>
                <p>© <?= date('Y') ?> Kapouch. Кофе с собой.</p>
                <p>Оплата и фискализация через Тинькофф СБП.</p>
            </div>
        </footer>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>
