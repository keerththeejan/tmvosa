<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Membership Application';
?>
<div class="application-page">
    <div class="application-hero px-3 px-md-4 pt-3">
        <?php View::partial('osa-hero-banner', ['heroCompact' => true, 'heroEager' => true]); ?>
        <?php View::partial('hero-quick-actions', [
            'applyHref' => '#applicationForm',
            'trackHref' => '#track-section',
        ]); ?>
    </div>

    <div class="px-3 pb-5" id="applicationForm">
        <?php View::partial('application-wizard', compact('countries', 'membershipTypes')); ?>
    </div>
</div>
