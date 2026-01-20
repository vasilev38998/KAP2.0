<?php
session_start();

$brand = [
    'name' => 'Kapouch',
    'address' => 'Россия, Иркутская область, г. Шелехов, Култукский тракт 25/1',
    'tagline' => 'Ultra-premium coffee experience',
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
    <title><?= htmlspecialchars($brand['name']) ?> — Black Card PWA</title>
    <meta name="description" content="Цифровая Black-Card гостя Kapouch: статус, привилегии, бесконтактная оплата через Тинькофф СБП и фискализация чеков 54-ФЗ.">
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
                        <div class="logo-subtitle">Кофе с собой</div>
                    </div>
                </div>
                <div class="nav-actions">
                    <a class="nav-link" href="#experience">Опыт</a>
                    <a class="nav-link" href="#payment">Оплата</a>
                    <a class="nav-link" href="#tiers">Уровни</a>
                    <a class="nav-link" href="#concierge">Concierge</a>
                    <button class="install-btn" id="installButton" hidden>Установить PWA</button>
                </div>
            </nav>
            <div class="hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow">Привилегированный доступ</p>
                    <h1>Цифровая Black‑Card гостя</h1>
                    <p class="lead">
                        Добро пожаловать в <?= htmlspecialchars($brand['name']) ?> — ultra‑luxury кофейню, где каждая деталь сервиса
                        становится частью статуса. PWA‑карта заменяет пластик, усиливает персонализацию и открывает премиальные уровни
                        привилегий.
                    </p>
                    <div class="hero-actions">
                        <button class="primary">Получить доступ</button>
                        <button class="ghost">Запросить консультацию</button>
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
                            <span class="stat-value">2 мин</span>
                            <span class="stat-label">Среднее время обслуживания</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value">24/7</span>
                            <span class="stat-label">Личный concierge</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value">99.9%</span>
                            <span class="stat-label">Доступность сервиса</span>
                        </div>
                    </div>
                </div>
                <div class="hero-card">
                    <div class="card-top">
                        <span class="card-chip"></span>
                        <span class="card-tier">BLACK</span>
                    </div>
                    <div class="card-number">•••• 9182</div>
                    <div class="card-name">GUEST NO. 00024</div>
                    <div class="card-footer">
                        <span>Kapouch</span>
                        <span id="cardDate">--/--</span>
                    </div>
                    <div class="card-badge">Premium access</div>
                </div>
            </div>
        </header>

        <main>
            <?php if ($notice !== ''): ?>
                <div class="status-banner" role="status">
                    <?= htmlspecialchars($notice) ?>
                </div>
            <?php endif; ?>

            <section class="section" id="experience">
                <div class="section-head">
                    <h2>Сервис уровня private club</h2>
                    <p>Каждое касание с брендом — это сценарий luxury‑опыта: скорость, приватность и персонализация.</p>
                </div>
                <div class="feature-grid">
                    <article>
                        <h3>Персональная идентификация</h3>
                        <p>Безопасная цифровая карта с биометрической верификацией на устройстве гостя.</p>
                    </article>
                    <article>
                        <h3>СБП‑оплата от Тинькофф</h3>
                        <p>Мгновенные платежи по QR‑коду с подтверждением в приложении банка.</p>
                    </article>
                    <article>
                        <h3>Фискализация 54‑ФЗ</h3>
                        <p>Автоматическая отправка чеков через Тинькофф и хранение истории транзакций.</p>
                    </article>
                    <article>
                        <h3>Concierge‑сервис</h3>
                        <p>Персональные предложения, дегустации и early access к лимитированным коллекциям.</p>
                    </article>
                </div>
            </section>

            <section class="section card-section" id="tiers">
                <div class="section-head">
                    <h2>Black‑Card интерфейс</h2>
                    <p>Минималистичная PWA‑витрина поддерживает офлайн‑режим и мгновенную загрузку на shared‑хостинге.</p>
                </div>
                <div class="tiers">
                    <div class="tier">
                        <div class="tier-title">Prestige</div>
                        <p>Персональные бариста-сессии и закрытые дегустации.</p>
                        <span class="tier-amount">от 25 000 ₽ / мес</span>
                    </div>
                    <div class="tier">
                        <div class="tier-title">Signature</div>
                        <p>Private бронирование и priority обслуживание.</p>
                        <span class="tier-amount">от 15 000 ₽ / мес</span>
                    </div>
                    <div class="tier">
                        <div class="tier-title">Reserve</div>
                        <p>Доступ к лимитированным релизам и закрытым лотам.</p>
                        <span class="tier-amount">от 8 000 ₽ / мес</span>
                    </div>
                </div>
            </section>

            <section class="section journey">
                <div class="section-head">
                    <h2>Сценарий визита</h2>
                    <p>Путь гостя от бронирования до оплаты выстроен как бесшовный luxury‑ритуал.</p>
                </div>
                <div class="timeline">
                    <div class="timeline-step">
                        <span class="step-index">01</span>
                        <div>
                            <h3>Private бронирование</h3>
                            <p>Concierge подтверждает визит и подготавливает индивидуальный сет.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <span class="step-index">02</span>
                        <div>
                            <h3>Идентификация Black‑Card</h3>
                            <p>PWA‑карта активируется в один тап, открывая персональные настройки.</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <span class="step-index">03</span>
                        <div>
                            <h3>СБП‑оплата</h3>
                            <p>QR‑код формируется автоматически, чек фискализируется через Тинькофф.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section payment-section" id="payment">
                <div class="section-head">
                    <h2>Оплата и чеки</h2>
                    <p>Единый поток оплаты СБП + фискализация 54‑ФЗ — полностью совместим с Тинькофф и Beget.</p>
                </div>
                <div class="payment-grid">
                    <div class="payment-card">
                        <h3>СБП QR‑платеж</h3>
                        <ul>
                            <li>QR‑код формируется на кассе или в PWA.</li>
                            <li>Подтверждение платежа в приложении банка.</li>
                            <li>Мгновенное обновление статуса визита.</li>
                        </ul>
                    </div>
                    <div class="payment-card">
                        <h3>Фискальные чеки</h3>
                        <ul>
                            <li>Передача данных в ОФД через API Тинькофф.</li>
                            <li>Отправка чеков в SMS/Email гостя.</li>
                            <li>Архив чеков в личном кабинете.</li>
                        </ul>
                    </div>
                    <div class="payment-card">
                        <h3>Shared‑хостинг ready</h3>
                        <ul>
                            <li>PHP 8.x + MySQL без фоновых сервисов.</li>
                            <li>Всё — на ванильном HTML/CSS/JS.</li>
                            <li>Сервис‑воркер для офлайн‑режима.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="section tech">
                <div class="section-head">
                    <h2>Технологическая витрина</h2>
                    <p>Система готова к интеграции с POS, CRM и витринами премиального контента.</p>
                </div>
                <div class="tech-grid">
                    <div class="tech-card">
                        <h3>PWA‑ядро</h3>
                        <p>Мгновенный запуск, офлайн‑режим и установка в один тап без App Store.</p>
                    </div>
                    <div class="tech-card">
                        <h3>Безопасность</h3>
                        <p>CSRF‑защита формы и минимизация данных для персональных запросов.</p>
                    </div>
                    <div class="tech-card">
                        <h3>Гибкая аналитика</h3>
                        <p>Сегментация гостей и контроль LTV на уровне событий.</p>
                    </div>
                </div>
            </section>

            <section class="section concierge" id="concierge">
                <div class="section-head">
                    <h2>Индивидуальный concierge</h2>
                    <p>Оставьте контакт, и мы персонализируем карту под ваши привычки.</p>
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
                        Предпочтения
                        <textarea name="preferences" placeholder="Например: авторские напитки, приватный зал" maxlength="400"></textarea>
                    </label>
                    <button type="submit" class="primary" id="submitButton">Отправить запрос</button>
                    <p class="form-note">Мы используем данные только для связи и персонализации сервиса.</p>
                </form>
                <div class="toast" id="formToast" hidden></div>
            </section>

            <section class="section faq">
                <div class="section-head">
                    <h2>FAQ</h2>
                    <p>Ответы на частые вопросы о работе Black‑Card.</p>
                </div>
                <div class="faq-grid">
                    <details>
                        <summary>Как получить Black‑Card?</summary>
                        <p>Оставьте заявку через concierge‑форму. Мы свяжемся и согласуем удобный формат активации.</p>
                    </details>
                    <details>
                        <summary>Где хранится история чеков?</summary>
                        <p>Чеки сохраняются в личном кабинете и отправляются гостю в SMS или email.</p>
                    </details>
                    <details>
                        <summary>Можно ли пользоваться картой офлайн?</summary>
                        <p>Да, ключевые данные доступны офлайн благодаря сервис‑воркеру PWA.</p>
                    </details>
                </div>
            </section>
        </main>

        <footer class="footer">
            <div>
                <strong><?= htmlspecialchars($brand['name']) ?></strong>
                <p><?= htmlspecialchars($brand['address']) ?></p>
            </div>
            <div>
                <p>© <?= date('Y') ?> Kapouch. Luxury coffee atelier.</p>
                <p>Лицензированная обработка платежей через Тинькофф СБП.</p>
            </div>
        </footer>
    </div>

    <script src="/assets/app.js"></script>
</body>
</html>
