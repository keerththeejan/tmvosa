<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSA</title>
    <script>
    (function() {
        var redirect = <?= json_encode($redirect ?? '/') ?>;
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
        if (window.indexedDB && indexedDB.deleteDatabase) {
            try { indexedDB.deleteDatabase('osa_application_draft_files'); } catch (e) {}
        }
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(regs) {
                regs.forEach(function(reg) { reg.unregister(); });
            }).catch(function() {});
        }
        if ('caches' in window) {
            caches.keys().then(function(keys) {
                keys.forEach(function(key) { caches.delete(key); });
            }).catch(function() {});
        }
        window.location.replace(redirect);
    })();
    </script>
</head>
<body></body>
</html>
