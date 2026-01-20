<?php
$brand = [
    'name' => 'Kapouch',
    'address' => 'Россия, Иркутская область, г. Шелехов, Култукский тракт 25/1',
    'tagline' => 'Ultra-premium coffee experience',
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($brand['name']) ?> — Black Card PWA</title>
    <meta name="description" content="Цифровая Black-Card гостя Kapouch: статус, привилегии, бесконтактная оплата через Тинькофф СБП и фискализация чеков 54-ФЗ.">
    <meta name="theme-color" content="#0b0b0b">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" href="/assets/icon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="page">
        <header class="hero">
            <nav class="nav">
                <div class="logo">
                    <span class="logo-mark">K</span>
                    <div>
                        <div class="logo-title"><?= htmlspecialchars($brand['name']) ?></div>
                        <div class="logo-subtitle">Black Card Loyalty</div>
                    </div>
                </div>
                <button class="install-btn" id="installButton" hidden>Установить PWA</button>
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
                </div>
            </div>
        </header>

        <main>
            <section class="section">
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

            <section class="section card-section">
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

            <section class="section payment-section">
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

            <section class="section concierge">
                <div class="section-head">
                    <h2>Индивидуальный concierge</h2>
                    <p>Оставьте контакт, и мы персонализируем карту под ваши привычки.</p>
                </div>
                <form class="concierge-form">
                    <label>
                        Имя гостя
                        <input type="text" placeholder="Анна" required>
                    </label>
                    <label>
                        Контакт
                        <input type="text" placeholder="Телефон или e-mail" required>
                    </label>
                    <label>
                        Предпочтения
                        <textarea placeholder="Например: авторские напитки, приватный зал"></textarea>
                    </label>
                    <button type="submit" class="primary">Отправить запрос</button>
                </form>
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
