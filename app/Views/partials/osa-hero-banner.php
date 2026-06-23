<?php
use App\Core\View;
use App\Helpers\Lang;

$assetBase = View::assetBase();
$heroCompact = $heroCompact ?? false;
$heroEager = $heroEager ?? true;
$loading = $heroEager ? 'eager' : 'lazy';
$fetchPriority = $heroEager ? 'high' : 'auto';
$webpPath = App\Core\App::basePath() . '/public/assets/img/osa-hero-banner.webp';
$hasWebp = file_exists($webpPath);
$heroAlt = Lang::ui('hero_alt');
$alt = (is_array($heroAlt) ? $heroAlt['ta'] . ' — ' . $heroAlt['en'] : (string) $heroAlt);
?>
<section class="osa-hero-section<?= $heroCompact ? ' osa-hero-section--compact' : '' ?>" aria-label="<?= View::escape(is_array($heroAlt) ? $heroAlt['en'] : $heroAlt) ?>">
    <div class="osa-hero">
        <picture class="osa-hero__picture">
            <?php if ($hasWebp): ?>
            <source
                type="image/webp"
                srcset="<?= View::escape($assetBase) ?>/assets/img/osa-hero-banner.webp"
                media="(min-width: 1px)">
            <?php endif; ?>
            <img
                src="<?= View::escape($assetBase) ?>/assets/img/osa-hero-banner.jpg"
                alt="<?= View::escape($alt) ?>"
                class="osa-hero__img"
                width="1920"
                height="600"
                loading="<?= $loading ?>"
                fetchpriority="<?= $fetchPriority ?>"
                decoding="async">
        </picture>
    </div>
</section>
