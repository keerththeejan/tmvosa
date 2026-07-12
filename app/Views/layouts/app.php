<!DOCTYPE html>
<?php $uiLocale = \App\Helpers\Lang::locale(); ?>
<html lang="<?= $uiLocale ?>" class="lang-<?= $uiLocale ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1a5276">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <script>
    (function () {
        var m = document.cookie.match(/(?:^|; )osa_lang=([^;]*)/) || document.cookie.match(/(?:^|; )language=([^;]*)/);
        var lang = (m && decodeURIComponent(m[1]) === 'en') ? 'en' : 'ta';
        document.documentElement.lang = lang;
        document.documentElement.classList.remove('lang-ta', 'lang-en');
        document.documentElement.classList.add('lang-' + lang);
    })();
    </script>
    <link rel="manifest" href="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/manifest.json">
    <link rel="apple-touch-icon" href="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/assets/img/icon-192.png">
    <title><?= \App\Core\View::escape($pageTitle ?? 'OSA Alumni') ?> - OSA Membership</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/<?= \App\Core\App::assetVersion('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="admin-panel">
    <?php if ($user): ?>
    <div class="app-wrapper">
        <?php require __DIR__ . '/../partials/sidebar.php'; ?>
        <div class="main-content">
            <?php require __DIR__ . '/../partials/topbar.php'; ?>
            <div class="content-area">
                <?php if (!empty($flash)): ?>
                    <?php foreach ($flash as $type => $message): ?>
                        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                            <?= \App\Core\View::escape($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?= $content ?>
            </div>
            <?php require __DIR__ . '/../partials/bottom-nav.php'; ?>
        </div>
    </div>
    <?php else: ?>
        <?= $content ?>
    <?php endif; ?>

    <?php \App\Core\View::partial('site-footer'); ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const BASE_URL = '<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>';
        const CSRF_TOKEN = '<?= $csrfToken ?>';
    </script>
    <script src="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/<?= \App\Core\App::assetVersion('assets/js/locale.js') ?>"></script>
    <script src="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/<?= \App\Core\App::assetVersion('assets/js/app.js') ?>"></script>
    <?php if (isset($pageScript)): ?>
        <script src="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/<?= \App\Core\App::assetVersion('assets/js/' . $pageScript) ?>"></script>
    <?php endif; ?>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register(BASE_URL + '/service-worker.js');
        }
    </script>
</body>
</html>
