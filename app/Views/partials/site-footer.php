<?php
use App\Core\View;
use App\Helpers\Lang;

$year = date('Y');
$devUrl = 'https://vkitnet.info';
$contact = Lang::applicationContact();
?>
<footer class="site-footer">
    <div class="site-footer-inner">
        <div class="site-footer-contact bilingual-text bilingual-block mb-2">
            <span class="label-ta">
                <a href="tel:<?= View::escape($contact['phone_tel']) ?>">📞 <?= View::escape($contact['phone_display']) ?></a>
                &nbsp;|&nbsp;
                <a href="mailto:<?= View::escape($contact['email']) ?>">📧 <?= View::escape($contact['email']) ?></a>
            </span>
            <span class="label-en">
                <a href="tel:<?= View::escape($contact['phone_tel']) ?>">📞 <?= View::escape($contact['phone_display']) ?></a>
                &nbsp;|&nbsp;
                <a href="mailto:<?= View::escape($contact['email']) ?>">📧 <?= View::escape($contact['email']) ?></a>
            </span>
        </div>
        <p class="site-footer-copy mb-1 bilingual-text bilingual-block">
            <span class="label-ta">&copy; <?= $year ?> கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம். அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.</span>
            <span class="label-en">&copy; <?= $year ?> Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA. All rights reserved.</span>
        </p>
        <p class="site-footer-dev mb-1 bilingual-text bilingual-block">
            <span class="label-ta"><?= View::escape(Lang::ui('developed_by')['ta']) ?>
                <a href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">vkitnet.info</a>
            </span>
            <span class="label-en"><?= View::escape(Lang::ui('developed_by')['en']) ?>
                <a href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">vkitnet.info</a>
            </span>
        </p>
        <?php $clearSiteUrl = \App\Core\App::routeUrl('clear-site-data'); ?>
        <p class="site-footer-clear mb-0 small">
            <a href="<?= View::escape($clearSiteUrl) ?>" class="text-muted">Clear cookies &amp; saved form data</a>
        </p>
    </div>
</footer>
