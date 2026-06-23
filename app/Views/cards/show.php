<?php
use App\Core\View;

$pageTitle = 'Membership Card';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="membership-card-display mx-auto">
    <div class="digital-card">
        <div class="card-header-section">
            <div class="card-logos">
                <div class="logo-sm"><i class="bi bi-mortarboard-fill"></i></div>
            </div>
            <div class="bilingual-text bilingual-block">
                <span class="label-ta">கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம்</span>
                <span class="label-en">Kilinochchi / Thiruvaiyaru Maha Vidyalayam</span>
            </div>
            <div class="bilingual-text bilingual-block">
                <span class="label-ta">பழைய மாணவர் சங்கம்</span>
                <span class="label-en">Old Students' Association</span>
            </div>
        </div>
        <div class="card-body-section text-center">
            <div class="member-photo mx-auto">
                <?php if ($member['photo']): ?>
                <img src="<?= $base ?>/../storage/uploads/<?= $member['photo'] ?>" alt="">
                <?php else: ?>
                <div class="avatar-placeholder-lg"><?= strtoupper(substr($member['full_name_english'], 0, 1)) ?></div>
                <?php endif; ?>
            </div>
            <h5 class="mt-3 mb-0"><?= View::escape($member['full_name_english']) ?></h5>
            <?php if (!empty($member['full_name_tamil'])): ?>
            <p class="text-muted mb-0"><?= View::escape($member['full_name_tamil']) ?></p>
            <?php endif; ?>
            <p class="membership-no"><?= View::escape($member['membership_number']) ?></p>
            <p class="text-muted small mb-1"><?= View::escape($member['membership_type_name'] ?? '') ?></p>
            <div class="bilingual-text bilingual-block text-muted small">
                <span class="label-ta"><?= View::escape(\App\Helpers\Lang::ui('valid_until')['ta']) ?>: <?= View::escape($member['membership_expiry_date'] ?? 'N/A') ?></span>
                <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('valid_until')['en']) ?>: <?= View::escape($member['membership_expiry_date'] ?? 'N/A') ?></span>
            </div>
            <?php if (!empty($card['qr_code_path'])): ?>
            <img src="<?= $base ?>/../storage/uploads/<?= $card['qr_code_path'] ?>" class="qr-code" alt="QR Code">
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card-actions d-grid gap-2 mt-4">
    <a href="<?= $base ?>/card/<?= $member['id'] ?>/pdf" class="btn btn-primary bilingual-btn">
        <span class="label-ta"><i class="bi bi-file-pdf"></i> <?= View::escape(\App\Helpers\Lang::ui('download_pdf')['ta']) ?></span>
        <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('download_pdf')['en']) ?></span>
    </a>
    <a href="<?= $base ?>/card/<?= $member['id'] ?>/image" class="btn btn-outline-primary bilingual-btn">
        <span class="label-ta"><i class="bi bi-image"></i> <?= View::escape(\App\Helpers\Lang::ui('download_image')['ta']) ?></span>
        <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('download_image')['en']) ?></span>
    </a>
    <a href="https://wa.me/?text=<?= urlencode('OSA Membership Card - ' . $member['membership_number'] . ' - ' . $member['full_name_english']) ?>" class="btn btn-success bilingual-btn" target="_blank">
        <span class="label-ta"><i class="bi bi-whatsapp"></i> <?= View::escape(\App\Helpers\Lang::ui('share_whatsapp')['ta']) ?></span>
        <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('share_whatsapp')['en']) ?></span>
    </a>
    <?php if ($member['email']): ?>
    <a href="mailto:<?= $member['email'] ?>?subject=OSA Membership Card&body=<?= urlencode('Membership: ' . $member['membership_number']) ?>" class="btn btn-outline-secondary bilingual-btn">
        <span class="label-ta"><i class="bi bi-envelope"></i> <?= View::escape(\App\Helpers\Lang::ui('share_email')['ta']) ?></span>
        <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('share_email')['en']) ?></span>
    </a>
    <?php endif; ?>
</div>
