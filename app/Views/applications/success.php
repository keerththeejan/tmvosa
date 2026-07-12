<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Application Submitted — OSA Alumni';
$bodyClass = 'osa-apply-page';
$extraCss = ['assets/css/apply-form.css'];
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="application-page osa-apply-success-page">
    <div class="container osa-apply-container py-4 py-lg-5">
        <div class="alert alert-success osa-apply-success-alert border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-start gap-3">
                <span class="osa-apply-success-icon" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                <div>
                    <h1 class="h4 mb-1">Application Submitted Successfully</h1>
                    <p class="mb-0 bilingual-text bilingual-block">
                        <span class="label-ta">விண்ணப்பம் வெற்றிகரமாக சமர்ப்பிக்கப்பட்டது!</span>
                        <span class="label-en">Your membership application has been received.</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm osa-apply-success-card">
            <div class="card-body p-4 p-lg-5 text-center">
                <p class="text-muted small mb-2 bilingual-text bilingual-block">
                    <span class="label-ta">உங்கள் விண்ணப்ப இலக்கம்</span>
                    <span class="label-en">Application Number</span>
                </p>
                <p class="application-success-number osa-apply-success-number mb-3"><?= View::escape($applicationNumber) ?></p>

                <?php if (!empty($fullName)): ?>
                <p class="fw-semibold mb-2"><?= View::escape($fullName) ?></p>
                <?php endif; ?>

                <?php if (!empty($submittedAt)): ?>
                <p class="text-muted small mb-4">
                    <span class="label-ta d-block">சமர்ப்பித்த திகதி: <?= View::escape(date('d M Y, h:i A', strtotime($submittedAt))) ?></span>
                    <span class="label-en d-block">Submitted: <?= View::escape(date('d M Y, h:i A', strtotime($submittedAt))) ?></span>
                </p>
                <?php endif; ?>

                <div class="osa-apply-next-steps text-start mx-auto mb-4">
                    <h2 class="h6 mb-2">Next Steps</h2>
                    <ol class="small text-muted mb-0 ps-3">
                        <li>Keep your application number for tracking.</li>
                        <li>The association committee will review your submission.</li>
                        <li>You will be contacted once a decision is made.</li>
                    </ol>
                </div>

                <div class="d-grid d-sm-flex gap-2 justify-content-center">
                    <a href="<?= $base ?>/" class="btn btn-osa-apply-primary bilingual-btn">
                        <span class="label-ta">முகப்புப் பக்கத்திற்கு திரும்பு</span>
                        <span class="label-en">Back to Home</span>
                    </a>
                    <a href="<?= $base ?>/apply#track-section" class="btn btn-osa-apply-outline">Track Application</a>
                </div>
            </div>
        </div>
    </div>
</div>
