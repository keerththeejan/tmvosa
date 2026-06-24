// OSA Membership System - Main Application JS

$(document).ready(function() {
  const isAdmin = $('body').hasClass('admin-panel');

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '' }
  });

  $('#sidebarToggle').on('click', function() {
    $('#sidebar').toggleClass('d-none d-block');
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
