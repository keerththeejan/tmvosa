/**
 * OSA premium homepage UI — no impact on application-wizard business logic.
 */
(function () {
  'use strict';

  if (!document.body.classList.contains('osa-home')) return;

  var pubBase = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
  var VIDEO_SRC = ''; // Set official OSA promo embed URL when available, e.g. 'https://www.youtube.com/embed/VIDEO_ID?autoplay=1&rel=0'

  // Page loader
  window.addEventListener('load', function () {
    var loader = document.getElementById('osaLoader');
    if (loader) loader.classList.add('is-done');
  });
  setTimeout(function () {
    var loader = document.getElementById('osaLoader');
    if (loader) loader.classList.add('is-done');
  }, 1800);

  if (window.AOS) {
    AOS.init({ duration: 750, once: true, offset: 70, easing: 'ease-out-cubic' });
  }

  // Theme toggle
  var root = document.body;
  var storedTheme = localStorage.getItem('osa-home-theme');
  if (storedTheme === 'dark') root.classList.add('osa-dark');

  function syncThemeIcons() {
    var dark = root.classList.contains('osa-dark');
    document.querySelectorAll('#osaThemeToggle i, #osaThemeToggleMobile i').forEach(function (icon) {
      icon.className = dark ? 'bi bi-sun' : 'bi bi-moon-stars';
    });
  }
  syncThemeIcons();

  function toggleTheme() {
    root.classList.toggle('osa-dark');
    localStorage.setItem('osa-home-theme', root.classList.contains('osa-dark') ? 'dark' : 'light');
    syncThemeIcons();
  }
  ['osaThemeToggle', 'osaThemeToggleMobile'].forEach(function (id) {
    var btn = document.getElementById(id);
    if (btn) btn.addEventListener('click', toggleTheme);
  });

  // Sticky glass nav
  var nav = document.getElementById('osaNav');
  var backTop = document.getElementById('osaBackTop');

  function onScroll() {
    var y = window.scrollY || 0;
    if (nav) nav.classList.toggle('is-scrolled', y > 20);
    if (backTop) backTop.classList.toggle('is-visible', y > 500);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (backTop) {
    backTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Close mobile offcanvas on link click
  document.querySelectorAll('#osaNavOffcanvas .nav-link:not(.dropdown-toggle), #osaNavOffcanvas .dropdown-item, #osaNavOffcanvas .btn').forEach(function (el) {
    el.addEventListener('click', function () {
      var panel = document.getElementById('osaNavOffcanvas');
      if (panel && window.bootstrap && window.matchMedia('(max-width: 991.98px)').matches) {
        var inst = bootstrap.Offcanvas.getInstance(panel);
        if (inst) inst.hide();
      }
    });
  });

  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var id = link.getAttribute('href');
      if (!id || id === '#') return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Counters
  function animateCounter(el) {
    var end = parseInt(el.getAttribute('data-counter'), 10) || 0;
    var suffix = el.getAttribute('data-suffix') || '';
    var start = performance.now();
    var duration = 1400;
    function step(now) {
      var p = Math.min(1, (now - start) / duration);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(end * eased).toLocaleString() + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  var counters = document.querySelectorAll('[data-counter]');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    counters.forEach(function (c) { io.observe(c); });
  } else {
    counters.forEach(animateCounter);
  }

  // Countdowns
  function pad(n) { return String(Math.max(0, n)).padStart(2, '0'); }
  function tickCountdowns() {
    document.querySelectorAll('[data-countdown]').forEach(function (node) {
      var end = new Date(node.getAttribute('data-countdown')).getTime();
      var diff = end - Date.now();
      var days = Math.floor(diff / 86400000);
      var hours = Math.floor((diff % 86400000) / 3600000);
      var mins = Math.floor((diff % 3600000) / 60000);
      var d = node.querySelector('[data-unit="days"]');
      var h = node.querySelector('[data-unit="hours"]');
      var m = node.querySelector('[data-unit="mins"]');
      if (d) d.textContent = pad(days);
      if (h) h.textContent = pad(hours);
      if (m) m.textContent = pad(mins);
    });
  }
  tickCountdowns();
  setInterval(tickCountdowns, 30000);

  // Gallery lightbox
  var lightboxImg = document.getElementById('osaLightboxImage');
  var lightboxCaption = document.getElementById('osaLightboxCaption');
  document.querySelectorAll('.osa-gallery-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var src = btn.getAttribute('data-image');
      var caption = btn.getAttribute('data-caption') || 'Gallery';
      if (lightboxImg) {
        lightboxImg.src = src;
        lightboxImg.alt = caption;
      }
      if (lightboxCaption) lightboxCaption.textContent = caption;
    });
  });
  var lightbox = document.getElementById('osaLightbox');
  if (lightbox) {
    lightbox.addEventListener('hidden.bs.modal', function () {
      if (lightboxImg) lightboxImg.removeAttribute('src');
    });
  }

  // Video modal
  var videoModal = document.getElementById('osaVideoModal');
  var videoFrame = document.getElementById('osaVideoFrame');
  document.querySelectorAll('[data-bs-target="#osaVideoModal"]').forEach(function (trigger) {
    trigger.addEventListener('click', function (e) {
      if (VIDEO_SRC) return;
      e.preventDefault();
      e.stopPropagation();
      if (window.Swal) {
        Swal.fire({
          icon: 'info',
          title: 'Video coming soon',
          text: 'The official OSA promotional video will appear here once published.',
          confirmButtonColor: '#1D4ED8'
        });
      }
    });
  });
  if (videoModal && videoFrame) {
    videoModal.addEventListener('show.bs.modal', function (e) {
      if (!VIDEO_SRC) {
        e.preventDefault();
      }
    });
    videoModal.addEventListener('shown.bs.modal', function () {
      if (VIDEO_SRC) videoFrame.src = VIDEO_SRC;
    });
    videoModal.addEventListener('hidden.bs.modal', function () {
      videoFrame.src = '';
    });
  }

  // Verify membership (existing public route)
  var verifyForm = document.getElementById('osaVerifyForm');
  if (verifyForm) {
    verifyForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var input = document.getElementById('verifyMembershipNumber');
      var number = (input && input.value ? input.value : '').trim();
      if (!number) return;
      window.location.href = pubBase + '/verify/' + encodeURIComponent(number);
    });
  }

  // Contact form → mailto
  var contactForm = document.getElementById('osaContactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = contactForm.querySelector('[data-mail]');
      var to = (btn && btn.getAttribute('data-mail')) || 'tmvosa@vkitnet.info';
      var name = document.getElementById('contactName').value.trim();
      var email = document.getElementById('contactEmail').value.trim();
      var subject = document.getElementById('contactSubject').value.trim();
      var message = document.getElementById('contactMessage').value.trim();
      var body = 'Name: ' + name + '\nEmail: ' + email + '\n\n' + message;
      window.location.href = 'mailto:' + encodeURIComponent(to)
        + '?subject=' + encodeURIComponent(subject)
        + '&body=' + encodeURIComponent(body);
    });
  }

  // Newsletter UX
  var newsletter = document.getElementById('osaNewsletterForm');
  if (newsletter) {
    newsletter.addEventListener('submit', function (e) {
      e.preventDefault();
      if (window.Swal) {
        Swal.fire({
          icon: 'success',
          title: 'Subscribed interest received',
          text: 'Please email the Alumni Office to complete newsletter registration.',
          confirmButtonColor: '#1D4ED8'
        });
      }
      newsletter.reset();
    });
  }

  // Ripple
  document.querySelectorAll('.btn-osa-primary').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var rect = btn.getBoundingClientRect();
      btn.style.setProperty('--ripple-x', (e.clientX - rect.left) + 'px');
      btn.style.setProperty('--ripple-y', (e.clientY - rect.top) + 'px');
      btn.classList.add('is-rippling');
      setTimeout(function () { btn.classList.remove('is-rippling'); }, 400);
    });
  });
})();
