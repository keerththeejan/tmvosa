<?php
use App\Core\View;

$year = date('Y');
$devUrl = 'https://vkitnet.info';
$contact = \App\Helpers\Lang::applicationContact();
$phoneTel = View::escape($contact['phone_tel']);
$phoneDisplay = View::escape($contact['phone_display']);
$email = View::escape($contact['email']);
?>
<footer class="site-footer" role="contentinfo">
    <div class="container-lg">
        <div class="row g-4 site-footer-grid">
            <div class="col-12 col-md-4 site-footer-col text-center text-md-start">
                <h6 class="site-footer-heading"><?= View::escape(__('footer_contact')) ?></h6>
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
                <h6 class="site-footer-heading"><?= View::escape(__('footer_copyright')) ?></h6>
                <div class="site-footer-copyright">
                    <p class="site-footer-year mb-2">&copy; <?= $year ?></p>
                    <p class="mb-2"><?= nl2br(View::escape(__('footer_assoc_name'))) ?></p>
                    <p class="site-footer-rights mb-0"><?= View::escape(__('footer_rights')) ?></p>
                </div>
            </div>

            <div class="col-12 col-md-4 site-footer-col text-center text-md-start">
                <h6 class="site-footer-heading"><?= View::escape(__('footer_website')) ?></h6>
                <div class="site-footer-credit">
                    <p class="mb-2"><?= View::escape(__('footer_design')) ?></p>
                    <p class="site-footer-dev mb-0">
                        <a class="site-footer-link site-footer-dev-link" href="<?= View::escape($devUrl) ?>" target="_blank" rel="noopener noreferrer">Developed by VKITNET</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
