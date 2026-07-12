<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Membership Application — OSA Alumni';
$bodyClass = 'osa-apply-page';
$extraCss = ['assets/css/apply-form.css'];
$extraJs = ['assets/js/apply-form-ui.js'];
$pub = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="application-page osa-apply-page-shell">
    <div class="application-hero px-3 px-md-4 pt-3 d-none d-md-block">
        <?php View::partial('osa-hero-banner', ['heroCompact' => true, 'heroEager' => true]); ?>
    </div>
            <div class="px-2 px-sm-3 pb-5" id="applicationFormSection">
        <div class="d-flex justify-content-end mb-2 px-1">
            <?php View::partial('language-switcher', ['variant' => 'light']); ?>
        </div>
        <?php View::partial('hero-quick-actions', [
            'applyHref' => '#applicationFormSection',
            'trackHref' => '#track-section',
        ]); ?>
        <script>
        window.APP_VALIDATION_CONFIG = <?= json_encode($validationConfig ?? [
            'blockDuplicateMobile' => false,
            'blockDuplicateEmail' => false,
        ], JSON_UNESCAPED_UNICODE) ?>;
        </script>
        <?php View::partial('application-wizard', compact('countries', 'membershipTypes')); ?>
    </div>
</div>
