<?php
use App\Core\View;
use App\Helpers\Lang;

$welcome = Lang::ui('welcome_text');
?>
<section class="welcome-section">
    <div class="bilingual-text bilingual-block text-center mb-2">
        <span class="label-ta welcome-title"><?= View::escape(Lang::ui('welcome_title')['ta']) ?></span>
        <span class="label-en welcome-subtitle"><?= View::escape(Lang::ui('welcome_title')['en']) ?></span>
    </div>
    <p class="bilingual-text bilingual-block text-center welcome-text mb-0">
        <span class="label-ta"><?= View::escape($welcome['ta']) ?></span>
        <span class="label-en"><?= View::escape($welcome['en']) ?></span>
    </p>
</section>
