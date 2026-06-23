<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Login';
?>
<div class="auth-page">
    <div class="auth-container">
        <?php View::partial('osa-hero-banner', ['heroCompact' => true, 'heroEager' => true]); ?>
        <div class="card auth-card shadow-sm mt-3">
            <div class="card-body p-4">
                <?php View::heading('sign_in', 'h5', '', ' card-title mb-4'); ?>
                <form id="loginForm">
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-3">
                        <?php View::label('username', true); ?>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control form-control-lg" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <?php View::label('password', true); ?>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 bilingual-btn" id="loginBtn">
                        <span class="label-ta"><?= View::escape(Lang::ui('sign_in')['ta']) ?></span>
                        <span class="label-en"><?= View::escape(Lang::ui('sign_in')['en']) ?></span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>/apply" class="text-decoration-none text-white bilingual-text bilingual-block">
                <i class="bi bi-file-earmark-plus"></i>
                <span class="label-ta"><?= View::escape(Lang::ui('apply_membership')['ta']) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('apply_membership')['en']) ?></span>
            </a>
        </div>
    </div>
</div>
