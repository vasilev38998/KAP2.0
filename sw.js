const CACHE_NAME = 'kapouch-pwa-v1';
const ASSETS = [
    '/',
    '/index.php',
    '/assets/styles.css',
    '/assets/app.js',
    '/assets/icon.svg',
    '/manifest.webmanifest'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS))
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.map((key) => (key === CACHE_NAME ? null : caches.delete(key)))
            )
        )
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((cached) => cached || fetch(event.request))
    );
});
