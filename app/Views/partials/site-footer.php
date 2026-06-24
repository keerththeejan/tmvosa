<?php
use App\Core\View;
use App\Helpers\Lang;

$year = date('Y');
$devUrl = 'https://vkitnet.info';
$contact = Lang::applicationContact();
?>
<footer class="site-footer">
    <div class="site-footer-inner">
        <div class="site-footer-contact">
            <a class="site-footer-contact-link" href="tel:<?= View::escape($contact['phone_tel']) ?>">
                📞 <?= View::escape($contact['phone_display']) ?>
            </a>
            <a class="site-footer-contact-link" href="mailto:<?= View::escape($contact['email']) ?>">
                📧 <?= View::escape($contact['email']) ?>
            </a>
        </div>

        <div class="site-footer-copyright">
            <p class="site-footer-year">&copy; <?= $year ?></p>
            <p class="site-footer-ta">கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம்<br>பழைய மாணவர் சங்கம்</p>
            <p class="site-footer-en">Kilinochchi / Thiruvaiyaru Maha Vidyalayam<br>Old Students' Association</p>
            <p class="site-footer-ta site-footer-rights">அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.</p>
            <p class="site-footer-en site-footer-rights">All Rights Reserved.</p>
        </div>

        <div class="site-footer-credit">
            <p class="site-footer-website-label">🌐 Website</p>
            <a class="site-footer-website-link" href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">vkitnet.info</a>
            <p class="site-footer-en site-footer-dev">Developed by VKITNET</p>
        </div>
    </div>
</footer>
