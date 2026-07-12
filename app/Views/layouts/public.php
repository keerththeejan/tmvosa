<!DOCTYPE html>
<?php $uiLocale = \App\Helpers\Lang::locale(); ?>
<html lang="<?= $uiLocale ?>" class="lang-<?= $uiLocale ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="<?= \App\Core\View::escape($seo['theme'] ?? '#0F172A') ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <script>
    (function () {
        var m = document.cookie.match(/(?:^|; )osa_lang=([^;]*)/) || document.cookie.match(/(?:^|; )language=([^;]*)/);
        var lang = (m && decodeURIComponent(m[1]) === 'en') ? 'en' : 'ta';
        document.documentElement.lang = lang;
        document.documentElement.classList.remove('lang-ta', 'lang-en');
        document.documentElement.classList.add('lang-' + lang);
    })();
    </script>
    <?php
    $pub = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $pageTitleSafe = \App\Core\View::escape($pageTitle ?? 'OSA Alumni');
    $seoDescription = \App\Core\View::escape($seo['description'] ?? 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students\' Association — official alumni membership portal.');
    $seoUrl = \App\Core\View::escape($seo['url'] ?? (($pub ?: '') . '/'));
    $seoImage = \App\Core\View::escape($seo['image'] ?? ($pub . '/assets/img/osa-hero-banner.jpg'));
    ?>
    <title><?= $pageTitleSafe ?></title>
    <meta name="description" content="<?= $seoDescription ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $pageTitleSafe ?>">
    <meta property="og:description" content="<?= $seoDescription ?>">
    <meta property="og:url" content="<?= $seoUrl ?>">
    <meta property="og:image" content="<?= $seoImage ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitleSafe ?>">
    <meta name="twitter:description" content="<?= $seoDescription ?>">
    <meta name="twitter:image" content="<?= $seoImage ?>">
    <?php if (!empty($seo['schema'])): ?>
    <script type="application/ld+json"><?= $seo['schema'] ?></script>
    <?php endif; ?>
    <link rel="manifest" href="<?= $pub ?>/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&family=Noto+Sans+Tamil:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php if (!empty($loadAos)): ?>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/css/app.css') ?>" rel="stylesheet">
    <link href="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/css/responsive-global.css') ?>" rel="stylesheet">
    <link href="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/css/premium-university.css') ?>" rel="stylesheet">
    <?php foreach (($extraCss ?? []) as $cssFile): ?>
    <link href="<?= $pub ?>/<?= \App\Core\App::assetVersion($cssFile) ?>" rel="stylesheet">
    <?php endforeach; ?>
</head>
<body class="<?= \App\Core\View::escape($bodyClass ?? '') ?>">
    <?= $content ?>
    <?php \App\Core\View::partial('site-footer'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($loadAos)): ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <?php endif; ?>
    <script>
        const BASE_URL = '<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>';
        const CSRF_TOKEN = '<?= $csrfToken ?>';
    </script>
    <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/js/locale.js') ?>"></script>
    <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/js/app.js') ?>"></script>
    <?php if (($pageScript ?? '') === 'application-wizard.js'): ?>
        <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/js/image-compress.js') ?>"></script>
    <?php endif; ?>
    <?php if (!empty($pageScript)): ?>
        <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion('assets/js/' . $pageScript) ?>"></script>
    <?php endif; ?>
    <?php foreach (($extraJs ?? []) as $jsFile): ?>
    <script src="<?= $pub ?>/<?= \App\Core\App::assetVersion($jsFile) ?>"></script>
    <?php endforeach; ?>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register(BASE_URL + '/service-worker.js');
        }
    </script>
</body>
</html>
