/**
 * OSA language switcher — cookie osa_lang / language + PHP session, default Tamil.
 * Writes cookies then reloads so the server renders the selected language.
 */
(function () {
  'use strict';

  var DAYS = 365;

  function readLang() {
    var cookies = document.cookie || '';
    var primary = cookies.match(/(?:^|; )osa_lang=([^;]*)/);
    var alt = cookies.match(/(?:^|; )language=([^;]*)/);
    var v = primary ? decodeURIComponent(primary[1]) : (alt ? decodeURIComponent(alt[1]) : 'ta');
    return v === 'en' ? 'en' : 'ta';
  }

  function writeLang(lang) {
    var maxAge = DAYS * 24 * 60 * 60;
    var secure = location.protocol === 'https:' ? '; Secure' : '';
    var base = '; Path=/; Max-Age=' + maxAge + '; SameSite=Lax' + secure;
    document.cookie = 'osa_lang=' + encodeURIComponent(lang) + base;
    document.cookie = 'language=' + encodeURIComponent(lang) + base;
  }

  function applyLang(lang) {
    document.documentElement.lang = lang;
    document.documentElement.classList.remove('lang-ta', 'lang-en');
    document.documentElement.classList.add('lang-' + lang);
  }

  applyLang(readLang());

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-osa-lang]');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    var lang = btn.getAttribute('data-osa-lang') === 'en' ? 'en' : 'ta';
    writeLang(lang);
    applyLang(lang);
    // Defer reload so cookies are committed before navigation.
    setTimeout(function () {
      window.location.reload();
    }, 10);
  }, true);
})();
