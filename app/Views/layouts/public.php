<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1a5276">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/manifest.json">
    <title><?= \App\Core\View::escape($pageTitle ?? 'OSA Alumni') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php $pub = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
    <link href="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
    <?= $content ?>
    <?php \App\Core\View::partial('site-footer'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const BASE_URL = '<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>';
        const CSRF_TOKEN = '<?= $csrfToken ?>';
    </script>
    <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/js/app.js') ?>"></script>
    <?php if (($pageScript ?? '') === 'application-wizard.js'): ?>
        <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/js/image-compress.js') ?>"></script>
    <?php endif; ?>
    <?php if (!empty($pageScript)): ?>
        <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/js/' . $pageScript) ?>"></script>
    <?php endif; ?>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register(BASE_URL + '/service-worker.js');
        }
    </script>
</body>
</html>
