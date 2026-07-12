<?php
/**
 * Premium home hero banner (UI only).
 * Expects: $heroBg, $loginUrl, $heroMembers, $heroApps, $assetBase
 */
use App\Core\View;

$logoUrl = ($assetBase ?? View::assetBase()) . '/assets/img/osa-school-logo.png';
?>
<section class="osa-hero" id="home" aria-label="OSA Alumni hero">
    <div class="osa-hero-bg" aria-hidden="true">
        <img
            class="osa-hero-bg-img"
            src="<?= View::escape($heroBg) ?>"
            alt=""
            width="1920"
            height="1023"
            decoding="async"
            fetchpriority="high">
    </div>
    <div class="osa-hero-overlay" aria-hidden="true"></div>
    <div class="osa-hero-vignette" aria-hidden="true"></div>

    <div class="container-xl position-relative osa-hero-inner">
        <div class="row align-items-center osa-hero-row g-4 g-xl-5">
            <div class="col-lg-6 col-xl-7" data-aos="fade-up" data-aos-duration="500">
                <div class="osa-hero-content">
                    <div class="osa-hero-brand">
                        <img
                            class="osa-hero-logo"
                            src="<?= View::escape($logoUrl) ?>"
                            alt="Thiruvaiyaru Maha Vidyalayam OSA logo"
                            width="72"
                            height="72"
                            decoding="async">
                        <div class="osa-hero-kicker">
                            <span class="osa-hero-kicker-line1"><?= View::escape(__('hero_kicker_line1')) ?></span>
                            <span class="osa-hero-kicker-line2"><?= View::escape(__('hero_kicker_line2')) ?></span>
                            <span class="osa-hero-kicker-accent" aria-hidden="true"></span>
                        </div>
                    </div>

                    <h1 class="osa-hero-title">
                        <span class="osa-hero-title-osa"><?= View::escape(__('hero_title_osa')) ?></span>
                        <span class="osa-hero-title-line"><?= View::escape(__('hero_title_line1')) ?></span>
                        <span class="osa-hero-title-line"><?= View::escape(__('hero_title_line2')) ?></span>
                    </h1>

                    <p class="osa-hero-lead"><?= View::escape(__('hero_lead')) ?></p>

                    <div class="osa-hero-actions" role="group" aria-label="Hero actions">
                        <a href="#apply" class="btn btn-osa-hero-primary">
                            <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                            <span><?= View::escape(__('apply_membership')) ?></span>
                        </a>
                        <a href="#verify" class="btn btn-osa-hero-secondary">
                            <i class="bi bi-person-check-fill" aria-hidden="true"></i>
                            <span><?= View::escape(__('verify_membership')) ?></span>
                        </a>
                        <a href="<?= View::escape($loginUrl) ?>" class="btn btn-osa-hero-outline">
                            <i class="bi bi-shield-lock" aria-hidden="true"></i>
                            <span><?= View::escape(__('member_login')) ?></span>
                        </a>
                        <a href="#about" class="btn btn-osa-hero-outline-white">
                            <i class="bi bi-play-circle" aria-hidden="true"></i>
                            <span><?= View::escape(__('learn_more')) ?></span>
                        </a>
                    </div>

                    <div class="osa-hero-stats" id="stats" aria-label="Live statistics">
                        <div class="osa-hero-stat" data-aos="zoom-in" data-aos-delay="0" data-aos-duration="500">
                            <span class="osa-hero-stat-icon" aria-hidden="true"><i class="bi bi-people-fill"></i></span>
                            <div class="osa-hero-stat-value"><span data-counter="<?= (int) $heroMembers ?>" data-suffix="+">0</span></div>
                            <div class="osa-hero-stat-label"><?= View::escape(__('stat_members')) ?></div>
                        </div>
                        <div class="osa-hero-stat" data-aos="zoom-in" data-aos-delay="80" data-aos-duration="500">
                            <span class="osa-hero-stat-icon" aria-hidden="true"><i class="bi bi-clipboard2-check-fill"></i></span>
                            <div class="osa-hero-stat-value"><span data-counter="<?= (int) $heroApps ?>" data-suffix="+">0</span></div>
                            <div class="osa-hero-stat-label"><?= View::escape(__('stat_applications')) ?></div>
                        </div>
                        <div class="osa-hero-stat" data-aos="zoom-in" data-aos-delay="160" data-aos-duration="500">
                            <span class="osa-hero-stat-icon" aria-hidden="true"><i class="bi bi-calendar-event-fill"></i></span>
                            <div class="osa-hero-stat-value"><span data-counter="35">0</span></div>
                            <div class="osa-hero-stat-label"><?= View::escape(__('stat_events')) ?></div>
                        </div>
                        <div class="osa-hero-stat" data-aos="zoom-in" data-aos-delay="240" data-aos-duration="500">
                            <span class="osa-hero-stat-icon" aria-hidden="true"><i class="bi bi-trophy-fill"></i></span>
                            <div class="osa-hero-stat-value"><span data-counter="50" data-suffix="+">0</span></div>
                            <div class="osa-hero-stat-label"><?= View::escape(__('stat_years')) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-5 d-flex justify-content-center justify-content-lg-end" data-aos="fade-left" data-aos-duration="500">
                <div class="osa-hero-visual">
                    <div class="osa-hero-visual-glow" aria-hidden="true"></div>
                    <article class="osa-member-card-mock osa-hero-float" aria-label="Digital OSA membership card preview">
                        <div class="osa-member-card-mock__shine" aria-hidden="true"></div>
                        <div class="osa-member-card-mock__header">
                            <img class="osa-member-card-mock__crest" src="<?= View::escape($logoUrl) ?>" alt="" width="48" height="48" decoding="async">
                            <div class="osa-member-card-mock__org">
                                <strong>OSA Alumni</strong>
                                <small><?= View::escape(__('card_org_name')) ?></small>
                            </div>
                            <span class="osa-member-card-mock__verified">
                                <i class="bi bi-patch-check-fill" aria-hidden="true"></i>
                                <?= View::escape(__('verified_member')) ?>
                            </span>
                        </div>

                        <div class="osa-member-card-mock__main">
                            <div class="osa-member-card-mock__profile">
                                <div class="osa-member-card-mock__avatar" aria-hidden="true">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="osa-member-card-mock__details">
                                    <span class="osa-member-card-mock__name"><?= View::escape(__('demo_member_name')) ?></span>
                                    <span class="osa-member-card-mock__meta-label"><?= View::escape(__('member_id')) ?></span>
                                    <span class="osa-member-card-mock__meta-value"><?= View::escape(__('demo_member_id')) ?></span>
                                    <span class="osa-member-card-mock__meta-label"><?= View::escape(__('membership_type_label')) ?></span>
                                    <span class="osa-member-card-mock__meta-value"><?= View::escape(__('lifetime_member')) ?></span>
                                </div>
                            </div>
                            <div class="osa-member-card-mock__qr" aria-hidden="true">
                                <i class="bi bi-qr-code"></i>
                            </div>
                        </div>

                        <div class="osa-member-card-mock__footer">
                            <span><?= View::escape(__('since_1975')) ?></span>
                            <svg class="osa-member-card-mock__skyline" viewBox="0 0 220 28" aria-hidden="true" focusable="false">
                                <path fill="rgba(255,255,255,.28)" d="M0 28h220V18l-10-6-8 4-12-10-9 7-14-8-11 6-16-9-10 5-18-7-9 6-14-5-12 4-15-6-12 5-18-4v14z"/>
                            </svg>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <a href="#about" class="osa-scroll-indicator" aria-label="<?= View::escape(__('scroll')) ?>">
        <span></span>
        <?= View::escape(__('scroll')) ?>
    </a>

    <div class="osa-hero-wave" aria-hidden="true">
        <svg viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="#FFFFFF" d="M0,72 C180,120 360,24 540,56 C720,88 900,120 1080,80 C1260,40 1350,48 1440,64 L1440,120 L0,120 Z"></path>
            <path fill="var(--osa-bg, #F8FAFC)" fill-opacity="0.96" d="M0,88 C240,128 480,40 720,64 C960,88 1200,120 1440,72 L1440,120 L0,120 Z"></path>
        </svg>
    </div>
</section>
