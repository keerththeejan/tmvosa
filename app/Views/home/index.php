<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'OSA Alumni — Membership';
?>
<div class="public-landing-page">
    <?php View::partial('osa-hero-banner', ['heroEager' => true]); ?>

    <div class="landing-body px-3 px-md-4 pb-5">
            <?php View::partial('hero-quick-actions', [
                'applyHref' => '#applicationForm',
                'trackHref' => '#track-section',
            ]); ?>

            <?php View::partial('welcome-section'); ?>

            <section class="membership-info-section mt-4" id="membership-info">
                <?php View::heading('membership_information', 'h5', 'info-circle', 'section-heading'); ?>
                <?php View::partial('membership-info-summary', compact('membershipTypes')); ?>
            </section>

            <section class="application-form-section mt-4" id="applicationFormSection">
                <?php View::heading('begin_application', 'h5', 'file-earmark-text', 'section-heading'); ?>
                <?php View::partial('application-wizard', compact('countries', 'membershipTypes')); ?>
            </section>
        </div>
</div>
