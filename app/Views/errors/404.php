<div class="text-center py-5">
    <i class="bi bi-exclamation-triangle text-warning" style="font-size:4rem;"></i>
    <div class="bilingual-text bilingual-block mt-3">
        <?php \App\Core\View::text('page_not_found', 'h3', true); ?>
    </div>
    <p class="text-muted bilingual-text bilingual-block">
        <span class="label-ta">நீங்கள் தேடும் பக்கம் இல்லை.</span>
        <span class="label-en">The page you are looking for does not exist.</span>
    </p>
    <a href="<?= \App\Core\App::routeUrl('dashboard') ?>" class="btn btn-primary bilingual-btn">
        <span class="label-ta"><?= \App\Core\View::escape(\App\Helpers\Lang::ui('go_dashboard')['ta']) ?></span>
        <span class="label-en"><?= \App\Core\View::escape(\App\Helpers\Lang::ui('go_dashboard')['en']) ?></span>
    </a>
</div>
