const CACHE_NAME = 'osa-alumni-v8';
const OFFLINE_URL = 'offline.html';

const PRECACHE_URLS = [
    'assets/css/app.css',
    'assets/js/app.js',
    'assets/js/image-compress.js',
    'assets/js/application-wizard.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11'
];

function isStaticAsset(url) {
    if (url.origin !== self.location.origin) {
        return PRECACHE_URLS.some(function(entry) {
            return entry.startsWith('http') && entry === url.href;
        });
    }

    return url.pathname.includes('/assets/')
        || url.pathname.endsWith('.css')
        || url.pathname.endsWith('.js')
        || url.pathname.endsWith('.png')
        || url.pathname.endsWith('.jpg')
        || url.pathname.endsWith('.webp')
        || url.pathname.endsWith('.woff2');
}

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            return cache.addAll(PRECACHE_URLS).catch(function() {
                return Promise.resolve();
            });
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(keys) {
            return Promise.all(
                keys.filter(function(key) { return key !== CACHE_NAME; })
                    .map(function(key) { return caches.delete(key); })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', function(event) {
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    if (url.pathname.includes('/admin/') || url.pathname.includes('/api/') || url.pathname.includes('/login')) {
        return;
    }

    // Never cache HTML pages — they contain CSRF tokens that must stay fresh.
    if (event.request.mode === 'navigate'
        || (event.request.headers.get('accept') || '').includes('text/html')
        || !isStaticAsset(url)) {
        event.respondWith(
            fetch(event.request).catch(function() {
                if (event.request.mode === 'navigate') {
                    return caches.match(OFFLINE_URL);
                }
            })
        );
        return;
    }

    event.respondWith(
        caches.match(event.request).then(function(cached) {
            const fetchPromise = fetch(event.request).then(function(response) {
                if (response && response.status === 200) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(function(cache) {
                        cache.put(event.request, clone);
                    });
                }
                return response;
            });
            return cached || fetchPromise;
        })
    );
});

self.addEventListener('push', function(event) {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'OSA Alumni';
    const options = {
        body: data.body || 'You have a new notification',
        icon: 'assets/img/icon-192.png',
        badge: 'assets/img/icon-192.png',
        data: data.url || 'dashboard'
    };
    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(clients.openWindow(event.notification.data));
});
