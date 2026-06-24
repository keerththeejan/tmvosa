<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Application Submitted';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="application-page">
    <div class="app-header text-center py-4">
        <div class="logo-circle-lg mx-auto bg-success text-white"><i class="bi bi-check-lg"></i></div>
        <div class="mt-3 mb-1 bilingual-text bilingual-block">
            <span class="label-ta">விண்ணப்பம் வெற்றிகரமாக சமர்ப்பிக்கப்பட்டது!</span>
            <span class="label-en">Application Submitted Successfully!</span>
        </div>
    </div>

    <div class="px-3 pb-4 mb-3">
        <div class="card border-0 shadow-sm application-success-card mx-auto">
            <div class="card-body text-center p-4">
                <p class="bilingual-text bilingual-block mb-3">
                    <span class="label-ta">உங்கள் விண்ணப்ப இலக்கம்</span>
                    <span class="label-en">Your Application Number</span>
                </p>
                <p class="application-success-number mb-3"><?= View::escape($applicationNumber) ?></p>

                <?php if (!empty($fullName)): ?>
                <p class="text-muted small mb-2"><?= View::escape($fullName) ?></p>
                <?php endif; ?>

                <?php if (!empty($submittedAt)): ?>
                <p class="text-muted small mb-4">
                    <span class="label-ta d-block">சமர்ப்பித்த திகதி: <?= View::escape(date('d M Y, h:i A', strtotime($submittedAt))) ?></span>
                    <span class="label-en d-block">Submitted: <?= View::escape(date('d M Y, h:i A', strtotime($submittedAt))) ?></span>
                </p>
                <?php endif; ?>

                <p class="bilingual-text bilingual-block small text-muted mb-4">
                    <span class="label-ta">உங்கள் விண்ணப்பம் பரிசீலனைக்கு அனுப்பப்பட்டுள்ளது. சங்க நிர்வாகம் விரைவில் தொடர்பு கொள்ளும்.</span>
                    <span class="label-en">Your application has been sent for review. The association office will contact you soon.</span>
                </p>

                <a href="<?= $base ?>/" class="btn btn-primary btn-lg w-100 bilingual-btn">
                    <span class="label-ta">முகப்புப் பக்கத்திற்கு திரும்பு</span>
                    <span class="label-en">Back to Home</span>
                </a>
            </div>
        </div>
    </div>
</div>
