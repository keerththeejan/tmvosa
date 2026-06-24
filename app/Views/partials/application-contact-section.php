<?php
use App\Core\View;
use App\Helpers\Lang;

$contact = Lang::applicationContact();
$inquiriesTitle = Lang::ui('contact_for_inquiries');
$inquiriesText = Lang::ui('contact_inquiries_text');
$secretary = Lang::ui('secretary');
?>
<div class="application-contact-card card border-0 shadow-sm mb-3">
    <div class="card-body text-center">
        <div class="application-contact-icon mb-2" aria-hidden="true">
            <i class="bi bi-telephone-fill"></i>
        </div>

        <div class="bilingual-heading mb-3">
            <h6 class="mb-0 application-contact-title">
                <span class="label-ta"><?= View::escape($inquiriesTitle['ta']) ?></span>
                <span class="label-en"><?= View::escape($inquiriesTitle['en']) ?></span>
            </h6>
        </div>

        <p class="application-contact-text bilingual-text bilingual-block mb-4">
            <span class="label-ta"><?= View::escape($inquiriesText['ta']) ?></span>
            <span class="label-en"><?= View::escape($inquiriesText['en']) ?></span>
        </p>

        <div class="application-contact-secretary">
            <div class="bilingual-text bilingual-block mb-2">
                <span class="label-ta application-contact-role"><?= View::escape($secretary['ta']) ?></span>
                <span class="label-en application-contact-role-en"><?= View::escape($secretary['en']) ?></span>
            </div>
            <a href="tel:<?= View::escape($contact['phone_tel']) ?>" class="application-contact-phone">
                <i class="bi bi-telephone-outbound" aria-hidden="true"></i>
                <?= View::escape($contact['phone_display']) ?>
            </a>
        </div>
    </div>
</div>
