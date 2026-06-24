<?php
use App\Core\View;
use App\Helpers\Lang;

$year = date('Y');
$devUrl = 'https://vkitnet.info';
?>
<footer class="site-footer">
    <div class="site-footer-inner">
        <p class="site-footer-copy mb-1 bilingual-text bilingual-block">
            <span class="label-ta">&copy; <?= $year ?> கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம். அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.</span>
            <span class="label-en">&copy; <?= $year ?> Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA. All rights reserved.</span>
        </p>
        <p class="site-footer-dev mb-0 bilingual-text bilingual-block">
            <span class="label-ta"><?= View::escape(Lang::ui('developed_by')['ta']) ?>
                <a href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">vkitnet.info</a>
            </span>
            <span class="label-en"><?= View::escape(Lang::ui('developed_by')['en']) ?>
                <a href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">vkitnet.info</a>
            </span>
        </p>
    </div>
</footer>
