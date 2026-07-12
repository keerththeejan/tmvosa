<?php
use App\Core\View;
?>
<section class="welcome-section">
    <div class="text-center mb-2">
        <span class="welcome-title"><?= View::escape(__('welcome_title')) ?></span>
    </div>
    <p class="text-center welcome-text mb-0">
        <?= View::escape(__('welcome_text')) ?>
    </p>
</section>
