<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearing site data…</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #f4f7fb; color: #1a5276; }
        .box { background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); max-width: 420px; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <h1 style="font-size:1.25rem;">Clearing cookies &amp; saved data</h1>
        <p id="status">Please wait…</p>
    </div>
    <script>
    (function() {
        var redirect = <?= json_encode($redirect ?? '/osa/') ?>;
        var status = document.getElementById('status');

        function done(msg) {
            status.textContent = msg || 'Done. Redirecting…';
            setTimeout(function() { window.location.replace(redirect); }, 900);
        }

        try {
            localStorage.removeItem('osa_application_form_draft');
            localStorage.removeItem('pwa-dismissed');
        } catch (e) {}

        try {
            document.cookie.split(';').forEach(function(part) {
                var name = part.split('=')[0].trim();
                if (!name) return;
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/osa';
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/osa/public';
            });
        } catch (e) {}

        var tasks = [];

        if (window.indexedDB && indexedDB.deleteDatabase) {
            tasks.push(new Promise(function(resolve) {
                var req = indexedDB.deleteDatabase('osa_application_draft_files');
                req.onsuccess = req.onerror = req.onblocked = function() { resolve(); };
            }));
        }

        if ('serviceWorker' in navigator) {
            tasks.push(navigator.serviceWorker.getRegistrations().then(function(regs) {
                return Promise.all(regs.map(function(reg) { return reg.unregister(); }));
            }).catch(function() {}));

            if ('caches' in window) {
                tasks.push(caches.keys().then(function(keys) {
                    return Promise.all(keys.map(function(key) { return caches.delete(key); }));
                }).catch(function() {}));
            }
        }

        Promise.all(tasks).finally(function() {
            done('Cookies, cache, and draft data cleared.');
        });
    })();
    </script>
</body>
</html>
