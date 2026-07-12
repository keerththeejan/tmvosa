// OSA Membership System - Main Application JS

$(document).ready(function() {
  const isAdmin = $('body').hasClass('admin-panel');
  const $sidebar = $('#sidebar');
  const $backdrop = $('#sidebarBackdrop');
  const $toggle = $('#sidebarToggle');

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '' }
  });

  function openSidebar() {
    if (!$sidebar.length) return;
    $sidebar.addClass('is-open');
    $backdrop.prop('hidden', false).addClass('is-visible');
    $('body').addClass('sidebar-open');
    $toggle.attr('aria-expanded', 'true');
  }

  function closeSidebar() {
    if (!$sidebar.length) return;
    $sidebar.removeClass('is-open');
    $backdrop.removeClass('is-visible').prop('hidden', true);
    $('body').removeClass('sidebar-open');
    $toggle.attr('aria-expanded', 'false');
  }

  function toggleSidebar() {
    if ($sidebar.hasClass('is-open')) {
      closeSidebar();
    } else {
      openSidebar();
    }
  }

  $toggle.on('click', function(e) {
    e.preventDefault();
    toggleSidebar();
  });

  $('#sidebarClose, #sidebarBackdrop').on('click', function() {
    closeSidebar();
  });

  $sidebar.on('click', 'a', function() {
    if (window.matchMedia('(max-width: 991.98px)').matches) {
      closeSidebar();
    }
  });

  $(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
      closeSidebar();
    }
  });

  $(window).on('resize', function() {
    if (window.matchMedia('(min-width: 992px)').matches) {
      closeSidebar();
    }
  });

  // Ensure data tables / HTML tables scroll on small screens
  $('table.table').each(function() {
    const $table = $(this);
    if (!$table.closest('.table-responsive').length) {
      $table.wrap('<div class="table-responsive"></div>');
    }
  });

  // Optional swipe-to-close for mobile sidebar
  let touchStartX = null;
  $sidebar.on('touchstart', function(e) {
    if (!window.matchMedia('(max-width: 991.98px)').matches) return;
    touchStartX = e.originalEvent.touches[0].clientX;
  });
  $sidebar.on('touchend', function(e) {
    if (touchStartX === null) return;
    const dx = e.originalEvent.changedTouches[0].clientX - touchStartX;
    touchStartX = null;
    if (dx < -60) {
      closeSidebar();
    }
  });

  // Edge swipe to open sidebar
  let edgeStartX = null;
  $(document).on('touchstart', function(e) {
    if (!window.matchMedia('(max-width: 991.98px)').matches) return;
    if ($sidebar.hasClass('is-open')) return;
    const x = e.originalEvent.touches[0].clientX;
    if (x <= 24) {
      edgeStartX = x;
    }
  });
  $(document).on('touchend', function(e) {
    if (edgeStartX === null) return;
    const dx = e.originalEvent.changedTouches[0].clientX - edgeStartX;
    edgeStartX = null;
    if (dx > 60) {
      openSidebar();
    }
  });

  // Make Bootstrap modals scrollable on small screens
  $('.modal-dialog').each(function() {
    $(this).addClass('modal-dialog-centered modal-dialog-scrollable');
  });

  // DataTables defaults when present (pages init their own tables)
  if ($.fn.DataTable) {
    $.extend(true, $.fn.dataTable.defaults, {
      autoWidth: false,
      language: {
        search: '',
        searchPlaceholder: 'Search…'
      }
    });
  }

  // Charts: keep fluid on resize
  let chartResizeTimer;
  $(window).on('resize', function() {
    clearTimeout(chartResizeTimer);
    chartResizeTimer = setTimeout(function() {
      $('canvas').each(function() {
        if (typeof Chart === 'undefined' || !Chart.getChart) return;
        const chart = Chart.getChart(this);
        if (chart) {
          try { chart.resize(); } catch (err) { /* ignore */ }
        }
      });
    }, 150);
  });

  if (isAdmin) {
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', function(e) {
      e.preventDefault();
      deferredPrompt = e;
      if (!localStorage.getItem('pwa-dismissed')) {
        showInstallPrompt();
      }
    });

    function showInstallPrompt() {
      const prompt = $('<div class="install-prompt">' +
        '<span><i class="bi bi-download"></i> Install OSA App</span>' +
        '<div><button class="btn btn-light btn-sm me-1" id="installBtn">Install</button>' +
        '<button class="btn btn-outline-light btn-sm" id="dismissInstall">×</button></div></div>');
      $('body').append(prompt);

      $('#installBtn').on('click', function() {
        if (deferredPrompt) {
          deferredPrompt.prompt();
          deferredPrompt.userChoice.then(function() { deferredPrompt = null; prompt.remove(); });
        }
      });
      $('#dismissInstall').on('click', function() {
        localStorage.setItem('pwa-dismissed', '1');
        prompt.remove();
      });
    }

    const timeout = 3600000;
    setTimeout(function() {
      if (typeof Swal !== 'undefined' && typeof BASE_URL !== 'undefined') {
        Swal.fire({
          title: 'Session Expiring',
          text: 'Your session will expire soon. Stay logged in?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Stay Logged In'
        }).then(function(result) {
          if (result.isConfirmed) {
            $.get(BASE_URL + '/api/chart-data');
          }
        });
      }
    }, timeout - 300000);
  }

  if (typeof Swal !== 'undefined') {
    Swal.mixin({
      confirmButtonColor: '#1a5276',
      cancelButtonColor: '#6c757d'
    });
  }
});
