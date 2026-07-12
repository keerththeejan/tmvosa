<?php
use App\Core\View;
use App\Helpers\Lang;
use App\Models\Application;
use App\Models\Member;

$pageTitle = 'OSA Alumni | Premium Membership Portal — Thiruvaiyaru Maha Vidyalayam';
$bodyClass = 'osa-home';
$loadAos = true;
$extraCss = ['assets/css/home.css', 'assets/css/hero.css', 'assets/css/hero-responsive.css', 'assets/css/apply-form.css'];
$extraJs = ['assets/js/home.js', 'assets/js/apply-form-ui.js'];

$assetBase = View::assetBase();
$pub = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$heroImg = $assetBase . '/assets/img/osa-school-entrance.jpg';
$heroHeroAsset = $assetBase . '/assets/img/osa-school-gate-hero.jpg';
$heroLegacy = $assetBase . '/assets/img/osa-hero-banner.jpg';
$entranceHeroPath = \App\Core\App::basePath() . '/public/assets/img/osa-school-gate-hero.jpg';
$entrancePath = \App\Core\App::basePath() . '/public/assets/img/osa-school-entrance.jpg';
if (file_exists($entranceHeroPath)) {
    $heroBg = $heroHeroAsset . '?v=' . filemtime($entranceHeroPath);
} elseif (file_exists($entrancePath)) {
    $heroBg = $heroImg . '?v=' . filemtime($entrancePath);
} else {
    $heroBg = $heroLegacy;
}
$loginUrl = $pub . '/login';
$applyUrl = $pub . '/apply';
$contact = Lang::applicationContact();
$whatsappUrl = 'https://wa.me/' . preg_replace('/\D+/', '', $contact['phone_tel'] ?? '0778870135');

$memberStats = Member::getStats();
$activeMembers = (int) ($memberStats['active'] ?? 0);
$totalMembers = (int) ($memberStats['total'] ?? $activeMembers);
$appTotal = (int) (Application::getAll('', 1, 1)['total'] ?? 0);
$heroMembers = max($activeMembers, $totalMembers, 1);
$heroApps = max($appTotal, 1);

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$absoluteHome = $scheme . '://' . $host . ($pub ?: '') . '/';
$absoluteHero = $scheme . '://' . $host . $heroBg;

$seo = [
    'theme' => '#1E6B34',
    'description' => 'Official OSA Alumni Membership portal for Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students\' Association. Apply online, verify membership, and stay connected.',
    'url' => $absoluteHome,
    'image' => $absoluteHero,
    'schema' => json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students\' Association',
        'alternateName' => 'OSA Alumni',
        'url' => $absoluteHome,
        'email' => $contact['email'] ?? 'tmvosa@vkitnet.info',
        'telephone' => $contact['phone_display'] ?? '077 887 0135',
        'description' => 'Premium alumni membership association connecting generations of Thiruvaiyaru Maha Vidyalayam.',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
];

$benefitsBySlug = [
    'half_year' => [__('perk_half_1'), __('perk_half_2'), __('perk_half_3'), __('perk_half_4')],
    'ordinary' => [__('perk_ord_1'), __('perk_ord_2'), __('perk_ord_3'), __('perk_ord_4')],
    'ten_year' => [__('perk_ten_1'), __('perk_ten_2'), __('perk_ten_3'), __('perk_ten_4')],
];

$whyJoin = [
    ['people', __('why_1_title'), __('why_1_text')],
    ['briefcase', __('why_2_title'), __('why_2_text')],
    ['award', __('why_3_title'), __('why_3_text')],
    ['calendar-event', __('why_4_title'), __('why_4_text')],
    ['mortarboard', __('why_5_title'), __('why_5_text')],
    ['building', __('why_6_title'), __('why_6_text')],
    ['person-vcard', __('why_7_title'), __('why_7_text')],
    ['patch-check', __('why_8_title'), __('why_8_text')],
];

$benefitTimeline = [
    ['1', __('step_1_title'), __('step_1_text')],
    ['2', __('step_2_title'), __('step_2_text')],
    ['3', __('step_3_title'), __('step_3_text')],
    ['4', __('step_4_title'), __('step_4_text')],
];

$news = [
    ['title' => __('news_1_title'), 'date' => __('news_1_date'), 'category' => __('news_1_cat'), 'summary' => __('news_1_summary')],
    ['title' => __('news_2_title'), 'date' => __('news_2_date'), 'category' => __('news_2_cat'), 'summary' => __('news_2_summary')],
    ['title' => __('news_3_title'), 'date' => __('news_3_date'), 'category' => __('news_3_cat'), 'summary' => __('news_3_summary')],
];

$events = [
    ['title' => __('event_1_title'), 'date' => '2026-08-16', 'place' => __('event_1_place'), 'summary' => __('event_1_summary')],
    ['title' => __('event_2_title'), 'date' => '2026-09-12', 'place' => __('event_2_place'), 'summary' => __('event_2_summary')],
    ['title' => __('event_3_title'), 'date' => '2026-10-05', 'place' => __('event_3_place'), 'summary' => __('event_3_summary')],
];

$testimonials = [
    ['name' => __('testimonial_1_name'), 'batch' => __('testimonial_1_batch'), 'role' => __('testimonial_1_role'), 'quote' => __('testimonial_1_quote'), 'rating' => 5],
    ['name' => __('testimonial_2_name'), 'batch' => __('testimonial_2_batch'), 'role' => __('testimonial_2_role'), 'quote' => __('testimonial_2_quote'), 'rating' => 5],
    ['name' => __('testimonial_3_name'), 'batch' => __('testimonial_3_batch'), 'role' => __('testimonial_3_role'), 'quote' => __('testimonial_3_quote'), 'rating' => 5],
];
?>
<script>
window.APP_VALIDATION_CONFIG = <?= json_encode($validationConfig ?? [
    'blockDuplicateMobile' => false,
    'blockDuplicateEmail' => false,
], JSON_UNESCAPED_UNICODE) ?>;
</script>

<div class="osa-loader" id="osaLoader" aria-hidden="true">
    <div class="osa-loader-mark"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i></div>
    <p class="mb-0">OSA Alumni</p>
</div>

<div class="osa-home" id="osaHomeRoot">
    <a class="visually-hidden-focusable btn btn-light position-absolute top-0 start-0 m-2 z-3" href="#mainContent"><?= View::escape(__('skip_to_content')) ?></a>

    <header class="osa-nav navbar navbar-expand-xl fixed-top" id="osaNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#home" aria-label="OSA Alumni home">
                <img class="osa-nav-logo" src="<?= View::escape($assetBase) ?>/assets/img/osa-school-logo.png" alt="OSA Alumni" width="60" height="60" decoding="async">
                <span class="osa-brand-text">
                    <strong>OSA Alumni</strong>
                    <small>Thiruvaiyaru MV</small>
                </span>
            </a>
            <div class="d-flex align-items-center gap-2 d-xl-none">
                <?php View::partial('language-switcher', ['variant' => 'nav']); ?>
                <button type="button" class="btn btn-osa-icon" id="osaThemeToggleMobile" aria-label="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#osaNavMenu" aria-controls="osaNavMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list fs-3"></i>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="osaNavMenu">
                <ul class="navbar-nav mx-xl-auto mb-2 mb-xl-0 align-items-xl-center i18n-nav">
                    <?php
                    $homeNav = [
                        ['#home', 'home'],
                        ['#about', 'about'],
                    ];
                    foreach ($homeNav as [$href, $key]):
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $href ?>"><?= View::escape(__($key)) ?></a>
                    </li>
                    <?php endforeach; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#membership" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= View::escape(__('membership')) ?>
                        </a>
                        <ul class="dropdown-menu osa-mega shadow border-0">
                            <li><a class="dropdown-item" href="#membership"><?= View::escape(__('membership_plans')) ?></a></li>
                            <li><a class="dropdown-item" href="#benefits"><?= View::escape(__('why_join')) ?></a></li>
                            <li><a class="dropdown-item" href="#member-benefits"><?= View::escape(__('member_benefits')) ?></a></li>
                            <li><a class="dropdown-item" href="#verify"><?= View::escape(__('verify_member')) ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#apply"><?= View::escape(__('apply_now')) ?></a></li>
                        </ul>
                    </li>
                    <?php
                    $homeNav2 = [
                        ['#benefits', 'benefits'],
                        ['#gallery', 'gallery'],
                        ['#events', 'events'],
                        ['#news', 'news'],
                        ['#contact', 'contact'],
                        ['#verify', 'verify_member'],
                    ];
                    foreach ($homeNav2 as [$href, $key]):
                        $hideClass = in_array($key, ['gallery', 'news', 'verify_member'], true) ? ' d-none d-xxl-block' : '';
                    ?>
                    <li class="nav-item<?= $hideClass ?>">
                        <a class="nav-link" href="<?= $href ?>"><?= View::escape(__($key)) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="d-flex flex-column flex-xl-row align-items-xl-center gap-2 mt-2 mt-xl-0">
                    <div class="osa-nav-lang d-none d-xl-inline-flex align-items-center"><?php View::partial('language-switcher', ['variant' => 'nav']); ?></div>
                    <button type="button" class="btn btn-osa-icon d-none d-xl-inline-flex" id="osaThemeToggle" aria-label="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
                    <a class="btn btn-osa-ghost btn-sm bilingual-btn" href="<?= View::escape($loginUrl) ?>">
                        <?= View::escape(__('member_login')) ?>
                    </a>
                    <a class="btn btn-osa-primary btn-sm bilingual-btn" href="#apply">
                        <?= View::escape(__('apply_now')) ?>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main id="mainContent">
        <?php View::partial('home-hero', compact('heroBg', 'loginUrl', 'heroMembers', 'heroApps', 'assetBase')); ?>

        <section class="osa-section" id="about">
            <div class="container">
                <div class="row g-4 g-xl-5 align-items-center">
                    <div class="col-lg-5" data-aos="fade-right">
                        <div class="osa-about-media">
                            <img src="<?= View::escape($heroImg) ?>" class="img-fluid" alt="<?= View::escape(__('hero_alt')) ?>" loading="lazy" decoding="async" width="720" height="540">
                        </div>
                    </div>
                    <div class="col-lg-7" data-aos="fade-left">
                        <p class="osa-eyebrow"><?= View::escape(__('about_eyebrow')) ?></p>
                        <h2 class="osa-section-title"><?= View::escape(__('about_title')) ?></h2>
                        <p class="osa-muted mb-4"><?= View::escape(__('about_lead')) ?></p>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="osa-mini-card h-100">
                                    <h3 class="h6"><?= View::escape(__('mission')) ?></h3>
                                    <p class="small mb-0"><?= View::escape(__('mission_text')) ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="osa-mini-card h-100">
                                    <h3 class="h6"><?= View::escape(__('vision')) ?></h3>
                                    <p class="small mb-0"><?= View::escape(__('vision_text')) ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="osa-mini-card h-100">
                                    <h3 class="h6"><?= View::escape(__('objectives')) ?></h3>
                                    <ul class="small mb-0 ps-3">
                                        <li><?= View::escape(__('obj_1')) ?></li>
                                        <li><?= View::escape(__('obj_2')) ?></li>
                                        <li><?= View::escape(__('obj_3')) ?></li>
                                        <li><?= View::escape(__('obj_4')) ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a href="#membership" class="btn btn-osa-primary"><?= View::escape(__('learn_more')) ?></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="osa-section osa-section-alt" id="membership">
            <div class="container">
                <div class="text-center col-lg-8 mx-auto mb-4 mb-lg-5" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('membership_eyebrow')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('membership_title')) ?></h2>
                    <p class="osa-muted mb-0"><?= View::escape(__('membership_lead')) ?></p>
                </div>
                <div class="row g-4 justify-content-center">
                    <?php foreach ($membershipTypes as $idx => $type):
                        $slug = $type['slug'] ?? 'ordinary';
                        $years = (int) ($type['duration_years'] ?? ($slug === 'ten_year' ? 10 : ($slug === 'half_year' ? 0 : 1)));
                        $display = Lang::membershipDisplayFromSlug($slug, $years, $type['name'] ?? null);
                        $amount = number_format((float) $type['fee'], 0);
                        $perks = $benefitsBySlug[$slug] ?? $benefitsBySlug['ordinary'];
                        $featured = $slug === 'ordinary';
                    ?>
                    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="<?= $idx * 80 ?>">
                        <article class="osa-price-card h-100<?= $featured ? ' is-featured' : '' ?>">
                            <?php if ($featured): ?><span class="osa-price-badge"><?= View::escape(__('featured')) ?></span><?php endif; ?>
                            <h3 class="h5 mb-1"><?= View::escape(Lang::pick(['ta' => $display['title_ta'] ?? '', 'en' => $display['title_en'] ?? ''])) ?></h3>
                            <div class="osa-price mb-1"><?= View::escape(__('currency_rs')) ?> <?= $amount ?></div>
                            <p class="small mb-3 fw-semibold"><?= View::escape(Lang::pick(['ta' => $display['validity_ta'] ?? '', 'en' => $display['validity_en'] ?? ''])) ?></p>
                            <ul class="list-unstyled osa-price-list mb-4">
                                <?php foreach ($perks as $perk): ?>
                                <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> <?= View::escape($perk) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="#apply" class="btn <?= $featured ? 'btn-osa-primary' : 'btn-osa-outline' ?> w-100"><?= View::escape(__('apply')) ?></a>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-4" data-aos="fade-up"><?php View::partial('membership-info-summary', compact('membershipTypes')); ?></div>
            </div>
        </section>

        <section class="osa-section" id="benefits">
            <div class="container">
                <div class="text-center col-lg-8 mx-auto mb-4 mb-lg-5" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('why_join_eyebrow')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('why_join_title')) ?></h2>
                </div>
                <div class="row g-3 g-lg-4">
                    <?php foreach ($whyJoin as $i => [$icon, $title, $text]): ?>
                    <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= ($i % 4) * 60 ?>">
                        <div class="osa-icon-card h-100">
                            <div class="osa-icon-wrap"><i class="bi bi-<?= $icon ?>" aria-hidden="true"></i></div>
                            <h3 class="h6"><?= View::escape($title) ?></h3>
                            <p class="small mb-0 osa-muted"><?= View::escape($text) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="osa-section osa-section-alt" id="member-benefits">
            <div class="container">
                <div class="text-center col-lg-8 mx-auto mb-4 mb-lg-5" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('journey_eyebrow')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('journey_title')) ?></h2>
                </div>
                <div class="osa-benefit-timeline">
                    <?php foreach ($benefitTimeline as $i => [$step, $title, $text]): ?>
                    <div class="osa-benefit-step" data-aos="fade-up" data-aos-delay="<?= $i * 90 ?>">
                        <div class="osa-benefit-num"><?= View::escape($step) ?></div>
                        <div class="osa-benefit-body">
                            <h3 class="h5 mb-1"><?= View::escape($title) ?></h3>
                            <p class="mb-0 osa-muted"><?= View::escape($text) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="osa-section" id="news">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4" data-aos="fade-up">
                    <div>
                        <p class="osa-eyebrow mb-1"><?= View::escape(__('news_eyebrow')) ?></p>
                        <h2 class="osa-section-title mb-0"><?= View::escape(__('news_title')) ?></h2>
                    </div>
                </div>
                <div class="row g-4">
                    <?php foreach ($news as $i => $item): ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
                        <article class="osa-news-card h-100">
                            <div class="osa-news-media" style="background-image:url('<?= View::escape($heroImg) ?>')" role="img" aria-label="<?= View::escape($item['title']) ?>"></div>
                            <div class="p-3 p-md-4">
                                <div class="d-flex justify-content-between gap-2 small text-muted mb-2">
                                    <time><?= View::escape($item['date']) ?></time>
                                    <span class="osa-chip"><?= View::escape($item['category']) ?></span>
                                </div>
                                <h3 class="h5"><?= View::escape($item['title']) ?></h3>
                                <p class="osa-muted"><?= View::escape($item['summary']) ?></p>
                                <a href="#contact" class="osa-link"><?= View::escape(__('read_more')) ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="osa-section osa-section-alt" id="events">
            <div class="container">
                <div class="text-center col-lg-8 mx-auto mb-4 mb-lg-5" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('events_eyebrow')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('events')) ?></h2>
                </div>
                <div class="row g-4">
                    <?php foreach ($events as $i => $event):
                        $ts = strtotime($event['date']);
                    ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
                        <article class="osa-event-card h-100">
                            <div class="osa-event-date">
                                <span><?= date('d', $ts) ?></span>
                                <small><?= date('M Y', $ts) ?></small>
                            </div>
                            <h3 class="h5"><?= View::escape($event['title']) ?></h3>
                            <p class="small text-muted"><i class="bi bi-geo-alt"></i> <?= View::escape($event['place']) ?></p>
                            <p class="osa-muted"><?= View::escape($event['summary']) ?></p>
                            <div class="osa-countdown mb-3" data-countdown="<?= View::escape($event['date']) ?>T09:00:00">
                                <span data-unit="days">00</span>d :
                                <span data-unit="hours">00</span>h :
                                <span data-unit="mins">00</span>m
                            </div>
                            <a href="#contact" class="btn btn-osa-outline btn-sm w-100"><?= View::escape(__('subscribe')) ?></a>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="osa-section" id="gallery">
            <div class="container">
                <div class="text-center col-lg-8 mx-auto mb-4" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('gallery_eyebrow')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('gallery_title')) ?></h2>
                </div>
                <div class="osa-masonry" id="osaGallery">
                    <?php
                    $positions = ['20% 30%', '60% 40%', '40% 70%', '75% 20%', '15% 60%', '50% 50%', '30% 80%', '80% 55%'];
                    foreach ($positions as $g => $pos):
                    ?>
                    <button type="button" class="osa-gallery-item" data-bs-toggle="modal" data-bs-target="#osaLightbox" data-image="<?= View::escape($heroImg) ?>" data-caption="OSA Alumni gallery <?= $g + 1 ?>" aria-label="Open gallery image <?= $g + 1 ?>" data-aos="zoom-in" data-aos-delay="<?= ($g % 4) * 50 ?>">
                        <img src="<?= View::escape($heroImg) ?>" alt="OSA alumni gallery image <?= $g + 1 ?>" loading="lazy" decoding="async" width="600" height="400" style="object-position:<?= View::escape($pos) ?>">
                        <span class="osa-gallery-zoom" aria-hidden="true"><i class="bi bi-zoom-in"></i></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="osa-section osa-section-alt" id="video">
            <div class="container">
                <div class="row align-items-center g-4 g-xl-5">
                    <div class="col-lg-6" data-aos="fade-right">
                        <p class="osa-eyebrow"><?= View::escape(__('video_eyebrow')) ?></p>
                        <h2 class="osa-section-title"><?= View::escape(__('video_title')) ?></h2>
                        <p class="osa-muted mb-4"><?= View::escape(__('welcome_text')) ?></p>
                        <button type="button" class="btn btn-osa-primary" data-bs-toggle="modal" data-bs-target="#osaVideoModal"><i class="bi bi-play-fill me-1"></i> <?= View::escape(__('view')) ?></button>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left">
                        <button type="button" class="osa-video-poster w-100" data-bs-toggle="modal" data-bs-target="#osaVideoModal" aria-label="Play promotional video" style="--osa-hero-image:url('<?= View::escape($heroImg) ?>')">
                            <span class="osa-play-pulse"><i class="bi bi-play-fill"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="osa-section" id="stories">
            <div class="container">
                <div class="text-center mb-4" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('stories_eyebrow')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('stories_title')) ?></h2>
                </div>
                <div id="osaTestimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up">
                    <div class="carousel-inner">
                        <?php foreach ($testimonials as $i => $t): ?>
                        <div class="carousel-item<?= $i === 0 ? ' active' : '' ?>">
                            <figure class="osa-quote mx-auto text-center">
                                <div class="osa-quote-avatar mx-auto mb-3" aria-hidden="true"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
                                <div class="osa-stars mb-3" aria-label="<?= (int) $t['rating'] ?> star rating">
                                    <?php for ($s = 0; $s < (int) $t['rating']; $s++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                </div>
                                <blockquote class="mb-3">“<?= View::escape($t['quote']) ?>”</blockquote>
                                <figcaption>
                                    <strong><?= View::escape($t['name']) ?></strong>
                                    <span class="d-block small text-muted"><?= View::escape($t['batch']) ?> · <?= View::escape($t['role']) ?></span>
                                </figcaption>
                            </figure>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#osaTestimonialCarousel" data-bs-slide="prev" aria-label="Previous story"><span class="carousel-control-prev-icon"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#osaTestimonialCarousel" data-bs-slide="next" aria-label="Next story"><span class="carousel-control-next-icon"></span></button>
                </div>
            </div>
        </section>

        <section class="osa-partners py-4" aria-label="Partner organizations">
            <div class="container">
                <p class="text-center small text-uppercase text-muted mb-3"><?= View::escape(__('partners_eyebrow')) ?></p>
                <div class="osa-logo-marquee" aria-hidden="true">
                    <div class="osa-logo-track">
                        <?php
                        $partners = ['School Board', 'Alumni Trust', 'Scholarship Fund', 'Sports Club', 'Cultural Wing', 'VKITNET', 'Education Partners'];
                        foreach (array_merge($partners, $partners) as $pName):
                        ?>
                        <span class="osa-logo-pill"><?= View::escape($pName) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="osa-section osa-section-alt" id="verify">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="osa-verify-card text-center">
                            <p class="osa-eyebrow"><?= View::escape(__('verify_eyebrow')) ?></p>
                            <h2 class="osa-section-title"><?= View::escape(__('verify_title')) ?></h2>
                            <p class="osa-muted mb-4"><?= View::escape(__('verify_lead')) ?></p>
                            <ul class="nav nav-pills justify-content-center gap-2 mb-4" role="tablist">
                                <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#verifyByNumber" type="button" role="tab"><?= View::escape(__('field_membership_number')) ?></button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#verifyByNic" type="button" role="tab"><?= View::escape(__('field_nic_number')) ?></button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#verifyByQr" type="button" role="tab">QR Code</button></li>
                            </ul>
                            <div class="tab-content text-start">
                                <div class="tab-pane fade show active" id="verifyByNumber" role="tabpanel">
                                    <form id="osaVerifyForm" class="row g-2 align-items-end">
                                        <div class="col-md-8">
                                            <label class="form-label" for="verifyMembershipNumber"><?= View::escape(__('field_membership_number')) ?></label>
                                            <input type="text" class="form-control form-control-lg" id="verifyMembershipNumber" placeholder="<?= View::escape(__('verify_placeholder')) ?>" required autocomplete="off">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-osa-primary btn-lg w-100"><?= View::escape(__('verify')) ?></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="verifyByNic" role="tabpanel">
                                    <div class="alert alert-light border mb-0">
                                        <?= View::escape(__('verify_lead')) ?>
                                        <a href="tel:<?= View::escape($contact['phone_tel']) ?>"><?= View::escape($contact['phone_display']) ?></a>.
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="verifyByQr" role="tabpanel">
                                    <div class="alert alert-light border mb-0">
                                        <?= View::escape(__('verify_lead')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="osa-cta" data-aos="zoom-in">
            <div class="container text-center py-5">
                <h2 class="osa-section-title text-white mb-3"><?= View::escape(__('cta_title')) ?></h2>
                <p class="text-white-50 mb-4 col-lg-7 mx-auto"><?= View::escape(__('cta_lead')) ?></p>
                <a href="#apply" class="btn btn-osa-light btn-lg"><?= View::escape(__('apply_now')) ?></a>
            </div>
        </section>

        <section class="osa-section" id="contact">
            <div class="container">
                <div class="text-center col-lg-8 mx-auto mb-4 mb-lg-5" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('contact_eyebrow')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('contact_title')) ?></h2>
                </div>

                <?php
                $mapsQuery = '9C4H+4C8, Kilinochchi, Sri Lanka';
                $mapsEmbed = 'https://maps.google.com/maps?q=' . rawurlencode($mapsQuery) . '&z=17&ie=UTF8&iwloc=&output=embed';
                $mapsOpen = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapsQuery);
                $mapsDirections = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($mapsQuery);
                $siteUrl = $absoluteHome ?? (($scheme ?? 'http') . '://' . ($host ?? 'localhost') . ($pub ?: '') . '/');
                ?>

                <div class="row g-4 g-xl-5 align-items-stretch mb-4 mb-lg-5">
                    <div class="col-lg-7" data-aos="fade-right">
                        <div class="osa-map-embed">
                            <iframe
                                title="Thiruvaiyaru Maha Vidyalayam — 9C4H+4C8, Kilinochchi, Sri Lanka"
                                src="<?= View::escape($mapsEmbed) ?>"
                                width="100%"
                                height="450"
                                style="border:0"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-5" data-aos="fade-left">
                        <div class="osa-contact-card h-100">
                            <div class="osa-contact-item">
                                <div class="osa-contact-icon" aria-hidden="true"><i class="bi bi-geo-alt-fill"></i></div>
                                <div>
                                    <h3 class="osa-contact-label"><?= View::escape(__('contact_address_label')) ?></h3>
                                    <p class="osa-contact-address-ta mb-1">
                                        திருவையாறு மகா வித்தியாலயம்<br>
                                        கிளிநொச்சி, இலங்கை
                                    </p>
                                    <p class="osa-contact-address-en mb-0">
                                        Thiruvaiyaru Maha Vidyalayam<br>
                                        Kilinochchi, Sri Lanka
                                    </p>
                                    <p class="small text-muted mt-2 mb-0">9C4H+4C8, Kilinochchi, Sri Lanka</p>
                                </div>
                            </div>

                            <div class="osa-contact-item">
                                <div class="osa-contact-icon" aria-hidden="true"><i class="bi bi-telephone-fill"></i></div>
                                <div>
                                    <h3 class="osa-contact-label"><?= View::escape(__('contact_phone_label')) ?></h3>
                                    <a href="tel:<?= View::escape($contact['phone_tel']) ?>"><?= View::escape($contact['phone_display']) ?></a>
                                </div>
                            </div>

                            <div class="osa-contact-item">
                                <div class="osa-contact-icon" aria-hidden="true"><i class="bi bi-envelope-fill"></i></div>
                                <div>
                                    <h3 class="osa-contact-label"><?= View::escape(__('contact_email_label')) ?></h3>
                                    <a href="mailto:<?= View::escape($contact['email']) ?>"><?= View::escape($contact['email']) ?></a>
                                </div>
                            </div>

                            <div class="osa-contact-item">
                                <div class="osa-contact-icon" aria-hidden="true"><i class="bi bi-globe"></i></div>
                                <div>
                                    <h3 class="osa-contact-label"><?= View::escape(__('contact_website_label')) ?></h3>
                                    <a href="<?= View::escape($siteUrl) ?>"><?= View::escape(rtrim($siteUrl, '/')) ?></a>
                                </div>
                            </div>

                            <div class="osa-contact-item">
                                <div class="osa-contact-icon" aria-hidden="true"><i class="bi bi-clock-fill"></i></div>
                                <div>
                                    <h3 class="osa-contact-label"><?= View::escape(__('contact_hours_label')) ?></h3>
                                    <p class="mb-0"><?= View::escape(__('contact_hours_days')) ?></p>
                                    <p class="mb-0"><?= View::escape(__('contact_hours_time')) ?></p>
                                </div>
                            </div>

                            <div class="osa-contact-actions">
                                <a class="btn btn-osa-primary" href="<?= View::escape($mapsOpen) ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                    <?= View::escape(__('contact_open_maps')) ?>
                                </a>
                                <a class="btn btn-osa-outline" href="<?= View::escape($mapsDirections) ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="bi bi-compass" aria-hidden="true"></i>
                                    <?= View::escape(__('contact_get_directions')) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" data-aos="fade-up">
                    <div class="col-lg-8">
                        <form class="osa-contact-form card border-0 shadow-sm" id="osaContactForm">
                            <div class="card-body p-4">
                                <h3 class="h5 mb-3"><?= View::escape(__('contact_info')) ?></h3>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="contactName"><?= View::escape(__('contact_name')) ?></label>
                                        <input type="text" class="form-control" id="contactName" required autocomplete="name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="contactEmail"><?= View::escape(__('field_email')) ?></label>
                                        <input type="email" class="form-control" id="contactEmail" required autocomplete="email">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="contactSubject"><?= View::escape(__('contact_for_inquiries')) ?></label>
                                        <input type="text" class="form-control" id="contactSubject" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="contactMessage"><?= View::escape(__('contact_message')) ?></label>
                                        <textarea class="form-control" id="contactMessage" rows="4" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-osa-primary" data-mail="<?= View::escape($contact['email']) ?>"><?= View::escape(__('contact_send')) ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="osa-section osa-section-alt" id="apply">
            <div class="container">
                <div class="text-center col-lg-8 mx-auto mb-4" data-aos="fade-up">
                    <p class="osa-eyebrow"><?= View::escape(__('applications')) ?></p>
                    <h2 class="osa-section-title"><?= View::escape(__('begin_application')) ?></h2>
                    <p class="osa-muted"><?= View::escape(__('welcome_text')) ?></p>
                    <a href="<?= View::escape($applyUrl) ?>" class="btn btn-osa-ghost-dark btn-sm mb-3"><?= View::escape(__('start_application')) ?></a>
                </div>
                <div class="osa-apply-shell" data-aos="fade-up">
                    <?php View::partial('hero-quick-actions', [
                        'applyHref' => '#applicationFormSection',
                        'trackHref' => '#track-section',
                    ]); ?>
                    <div class="mt-3" id="applicationFormSection">
                        <?php View::partial('application-wizard', compact('countries', 'membershipTypes')); ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="osa-footer" id="osaFooter">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="osa-brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
                        <strong>OSA Alumni</strong>
                    </div>
                    <p class="small osa-muted"><?= View::escape(__('footer_assoc_name')) ?></p>
                    <form class="osa-newsletter mt-3" id="osaNewsletterForm">
                        <label class="form-label small" for="newsletterEmail"><?= View::escape(__('newsletter_title')) ?></label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="newsletterEmail" placeholder="<?= View::escape(__('ph_email')) ?>" required>
                            <button class="btn btn-osa-primary" type="submit"><?= View::escape(__('subscribe')) ?></button>
                        </div>
                    </form>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h3 class="h6"><?= View::escape(__('footer_quick_links')) ?></h3>
                    <ul class="list-unstyled small mb-0">
                        <li><a href="#about"><?= View::escape(__('about')) ?></a></li>
                        <li><a href="#news"><?= View::escape(__('news')) ?></a></li>
                        <li><a href="#events"><?= View::escape(__('events')) ?></a></li>
                        <li><a href="#gallery"><?= View::escape(__('gallery')) ?></a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h3 class="h6"><?= View::escape(__('membership')) ?></h3>
                    <ul class="list-unstyled small mb-0">
                        <li><a href="#membership"><?= View::escape(__('membership_plans')) ?></a></li>
                        <li><a href="#apply"><?= View::escape(__('applications')) ?></a></li>
                        <li><a href="#verify"><?= View::escape(__('verify_eyebrow')) ?></a></li>
                        <li><a href="<?= View::escape($loginUrl) ?>"><?= View::escape(__('login')) ?></a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-4">
                    <h3 class="h6"><?= View::escape(__('footer_contact')) ?></h3>
                    <p class="small mb-1"><?= View::escape($contact['phone_display']) ?></p>
                    <p class="small mb-3"><?= View::escape($contact['email']) ?></p>
                    <div class="d-flex gap-2 mb-3">
                        <a class="osa-social" href="<?= View::escape($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a class="osa-social" href="mailto:<?= View::escape($contact['email']) ?>" aria-label="Email"><i class="bi bi-envelope"></i></a>
                        <a class="osa-social" href="https://vkitnet.info" target="_blank" rel="noopener noreferrer" aria-label="Website"><i class="bi bi-globe"></i></a>
                    </div>
                    <p class="small mb-0">
                        <a href="#contact"><?= View::escape(__('footer_website')) ?></a> ·
                        <a href="#contact"><?= View::escape(__('footer_copyright')) ?></a> ·
                        <a href="#contact"><?= View::escape(__('download_pdf')) ?></a>
                    </p>
                </div>
            </div>
            <hr class="border-secondary opacity-25 my-4">
            <div class="d-flex flex-wrap justify-content-between gap-2 small osa-muted">
                <span>&copy; <?= date('Y') ?> OSA Alumni. <?= View::escape(__('footer_rights')) ?></span>
                <span><?= View::escape(__('footer_design')) ?> <a href="https://vkitnet.info" target="_blank" rel="noopener noreferrer">VKITNET</a></span>
            </div>
        </div>
    </footer>

    <a href="<?= View::escape($whatsappUrl) ?>" class="osa-float-wa" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp"><i class="bi bi-whatsapp"></i></a>
    <button type="button" class="osa-back-top" id="osaBackTop" aria-label="Back to top"><i class="bi bi-arrow-up"></i></button>
</div>

<div class="modal fade" id="osaLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <h2 class="modal-title h6 text-white" id="osaLightboxCaption"><?= View::escape(__('gallery')) ?></h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0"><img src="" alt="" class="img-fluid w-100" id="osaLightboxImage"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="osaVideoModal" tabindex="-1" aria-labelledby="osaVideoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <h2 class="modal-title h6 text-white" id="osaVideoTitle"><?= View::escape(__('video_title')) ?></h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="osaVideoFrame" title="OSA Alumni promotional video" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
