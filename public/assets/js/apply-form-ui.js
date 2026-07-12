/**
 * Application form UI helpers only (stepper, preview, sticky actions).
 * Does not alter submit/validation business logic in application-wizard.js.
 */
(function () {
  'use strict';

  var sectionIds = [
    'osaApplyPersonal',
    'osaApplyContact',
    'osaApplyMembership',
    'osaApplyDocuments',
    'osaApplyReview'
  ];

  function byId(id) {
    return document.getElementById(id);
  }

  function scrollToId(id) {
    var el = byId(id);
    if (!el) return;
    var top = el.getBoundingClientRect().top + window.scrollY - 88;
    window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
  }

  function setActiveStep(targetId) {
    document.querySelectorAll('.osa-apply-step').forEach(function (step) {
      var id = step.getAttribute('data-target');
      var idx = sectionIds.indexOf(id);
      var activeIdx = sectionIds.indexOf(targetId);
      step.classList.toggle('is-active', id === targetId);
      step.classList.toggle('is-complete', idx > -1 && activeIdx > -1 && idx < activeIdx);
    });
  }

  function val(selector) {
    var el = document.querySelector(selector);
    if (!el) return '';
    if (el.tagName === 'SELECT') {
      var opt = el.options[el.selectedIndex];
      return opt ? String(opt.text || '').trim() : '';
    }
    return String(el.value || '').trim();
  }

  function radioLabel() {
    var checked = document.querySelector('input[name="membership_type_id"]:checked');
    if (!checked) return '—';
    var card = checked.closest('.membership-option');
    if (!card) return checked.value;
    var en = card.querySelector('.option-title-ta, .option-title-en, .option-title');
    return en ? en.textContent.trim() : checked.value;
  }

  function buildReview() {
    var items = [
      ['Full Name (Tamil)', val('input[name="full_name_tamil"]')],
      ['Full Name', val('input[name="full_name_english"]') || '—'],
      ['Gender', val('select[name="gender"]') || '—'],
      ['Date of Birth', val('#dateOfBirthInput') || '—'],
      ['NIC', val('#nicNumberInput') || '—'],
      ['Mobile', val('#mobileInput') || '—'],
      ['WhatsApp', val('input[name="whatsapp"]') || '—'],
      ['Email', val('#emailInput') || '—'],
      ['Current Address', val('textarea[name="current_address"]') || '—'],
      ['Country', val('select[name="country_id"]') || '—'],
      ['Studied From', val('input[name="studied_from_year"]') || '—'],
      ['Studied To', val('#studiedToYear') || '—'],
      ['Membership', radioLabel()],
      ['Amount (Rs.)', val('#amountPaid') || '—'],
      ['Payment Method', val('#paymentMethodSelect') || '—']
    ];

    var html = '<div class="osa-apply-review-grid">';
    items.forEach(function (pair) {
      html += '<div class="osa-apply-review-item"><dt>' + pair[0] + '</dt><dd>' +
        (pair[1] ? String(pair[1]).replace(/</g, '&lt;') : '—') +
        '</dd></div>';
    });
    html += '</div>';
    return html;
  }

  function refreshReview() {
    var box = byId('osaApplyReviewSummary');
    if (!box) return;
    box.innerHTML = buildReview();
  }

  function currentSectionIndex() {
    var active = document.querySelector('.osa-apply-step.is-active');
    if (!active) return 0;
    return Math.max(0, sectionIds.indexOf(active.getAttribute('data-target')));
  }

  function initStepper() {
    document.querySelectorAll('.osa-apply-step').forEach(function (step) {
      step.addEventListener('click', function () {
        var target = step.getAttribute('data-target');
        setActiveStep(target);
        scrollToId(target);
      });
      step.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          step.click();
        }
      });
      step.setAttribute('tabindex', '0');
      step.setAttribute('role', 'button');
    });

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) setActiveStep(entry.target.id);
        });
      }, { rootMargin: '-35% 0px -50% 0px', threshold: 0.05 });
      sectionIds.forEach(function (id) {
        var el = byId(id);
        if (el) io.observe(el);
      });
    }
  }

  function initActions() {
    var backBtn = byId('osaApplyBackBtn');
    var draftBtn = byId('osaApplyDraftBtn');
    var previewBtn = byId('osaApplyPreviewBtn');

    if (backBtn) {
      backBtn.addEventListener('click', function () {
        var idx = currentSectionIndex();
        var prev = sectionIds[Math.max(0, idx - 1)];
        setActiveStep(prev);
        scrollToId(prev);
      });
    }

    if (draftBtn) {
      draftBtn.addEventListener('click', function () {
        if (window.Swal) {
          Swal.fire({
            icon: 'info',
            title: 'Save Draft',
            text: 'Draft saving is not available yet. Please complete and submit the application when ready.',
            confirmButtonColor: '#1D4ED8'
          });
        }
      });
    }

    if (previewBtn) {
      previewBtn.addEventListener('click', function () {
        refreshReview();
        setActiveStep('osaApplyReview');
        scrollToId('osaApplyReview');
      });
    }

    ['change', 'input', 'blur'].forEach(function (evt) {
      document.addEventListener(evt, function (e) {
        if (!e.target || !e.target.closest || !e.target.closest('#applicationForm')) return;
        // Keep review fresh when user edits after preview
        if (byId('osaApplyReviewSummary') && byId('osaApplyReviewSummary').querySelector('.osa-apply-review-grid')) {
          refreshReview();
        }
      }, true);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (!byId('applicationForm') || !document.querySelector('.osa-apply')) return;
    initStepper();
    initActions();
  });
})();
