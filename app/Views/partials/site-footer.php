<?php
use App\Core\View;
use App\Helpers\Lang;

$year = date('Y');
$devUrl = 'https://vkitnet.info';
$contact = Lang::applicationContact();
$phoneTel = View::escape($contact['phone_tel']);
$phoneDisplay = View::escape($contact['phone_display']);
$email = View::escape($contact['email']);
?>
<footer class="site-footer" role="contentinfo">
    <div class="container-lg">
        <div class="row g-4 site-footer-grid">
            <div class="col-12 col-md-4 site-footer-col text-center text-md-start">
                <h6 class="site-footer-heading">
                    <span class="site-footer-ta">தொடர்பு</span>
                    <span class="site-footer-en d-block">Contact</span>
                </h6>
                <ul class="site-footer-links list-unstyled mb-0">
                    <li class="site-footer-link-item">
                        <a class="site-footer-link" href="tel:<?= $phoneTel ?>">
                            <span class="site-footer-link-icon" aria-hidden="true">📞</span>
                            <span class="site-footer-link-text"><?= $phoneDisplay ?></span>
                        </a>
                    </li>
                    <li class="site-footer-link-item">
                        <a class="site-footer-link" href="mailto:<?= $email ?>">
                            <span class="site-footer-link-icon" aria-hidden="true">✉</span>
                            <span class="site-footer-link-text"><?= $email ?></span>
                        </a>
                    </li>
                    <li class="site-footer-link-item">
                        <a class="site-footer-link" href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">
                            <span class="site-footer-link-icon" aria-hidden="true">🌐</span>
                            <span class="site-footer-link-text">vkitnet.info</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-12 col-md-4 site-footer-col text-center text-md-start">
                <h6 class="site-footer-heading">
                    <span class="site-footer-ta">பதிப்புரிமை</span>
                    <span class="site-footer-en d-block">Copyright</span>
                </h6>
                <div class="site-footer-copyright">
                    <p class="site-footer-year mb-2">&copy; <?= $year ?></p>
                    <p class="site-footer-ta mb-2">கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம்<br>பழைய மாணவர் சங்கம்</p>
                    <p class="site-footer-en mb-2">Kilinochchi / Thiruvaiyaru Maha Vidyalayam<br>Old Students' Association</p>
                    <p class="site-footer-ta site-footer-rights mb-1">அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.</p>
                    <p class="site-footer-en site-footer-rights mb-0">All Rights Reserved.</p>
                </div>
            </div>

            <div class="col-12 col-md-4 site-footer-col text-center text-md-start">
                <h6 class="site-footer-heading">
                    <span class="site-footer-ta">வலைத்தளம்</span>
                    <span class="site-footer-en d-block">Website</span>
                </h6>
                <div class="site-footer-credit">
                    <p class="site-footer-ta mb-1">வடிவமைப்பு மற்றும் உருவாக்கம்</p>
                    <p class="site-footer-en mb-2">Design &amp; Development</p>
                    <p class="site-footer-dev mb-0">
                        <a class="site-footer-link site-footer-dev-link" href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">Developed by VKITNET</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
