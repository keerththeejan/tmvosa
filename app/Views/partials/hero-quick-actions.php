<?php
use App\Core\View;
use App\Helpers\Lang;

$assetBase = View::assetBase();
$applyHref = $applyHref ?? ($assetBase . '/apply');
$trackHref = $trackHref ?? '#track-section';
?>
<div class="hero-quick-actions">
    <div class="row g-2 g-md-3">
        <div class="col-12 col-sm-6">
            <a href="<?= View::escape($applyHref) ?>" class="btn btn-primary btn-lg w-100 bilingual-btn hero-action-btn">
                <i class="bi bi-file-earmark-plus me-1"></i>
                <span class="label-ta"><?= View::escape(Lang::ui('apply_membership')['ta']) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('apply_membership')['en']) ?></span>
            </a>
        </div>
        <div class="col-12 col-sm-6">
            <a href="<?= View::escape($trackHref) ?>" class="btn btn-outline-primary btn-lg w-100 bilingual-btn hero-action-btn">
                <i class="bi bi-search me-1"></i>
                <span class="label-ta"><?= View::escape(Lang::ui('track_status')['ta']) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('track_status')['en']) ?></span>
            </a>
        </div>
    </div>
</div>
