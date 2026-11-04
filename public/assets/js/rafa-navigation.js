(function () {
  'use strict';
  const toggles = Array.from(document.querySelectorAll('.menu-toggler'));
  function syncMenu() {
    const expanded = document.body.classList.contains('mobile-menu-active');
    toggles.forEach(function (toggle) { toggle.setAttribute('aria-expanded', String(expanded)); });
  }
  if (toggles.length) {
    new MutationObserver(syncMenu).observe(document.body, { attributes: true, attributeFilter: ['class'] });
    syncMenu();
    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape' || !document.body.classList.contains('mobile-menu-active')) return;
      document.body.classList.remove('mobile-menu-active');
      toggles.forEach(function (toggle) { toggle.classList.remove('active'); });
      document.querySelectorAll('.menu-backdrop').forEach(function (backdrop) { backdrop.remove(); });
      const visibleToggle = toggles.find(function (toggle) { return toggle.getClientRects().length > 0; });
      if (visibleToggle) visibleToggle.focus();
    });
  }
  // Reserve the banner's actual height, including wrapped mobile copy.
  const banner = document.querySelector('.js-cookie-consent');
  if (banner) {
    let previous = -1;
    function syncBanner() {
      const height = Math.ceil(banner.getBoundingClientRect().height);
      if (height === previous) return;
      previous = height;
      document.documentElement.style.setProperty('--rafa-cookie-height', height + 'px');
    }
    syncBanner();
    new MutationObserver(syncBanner).observe(banner, { attributes: true, attributeFilter: ['style', 'class', 'hidden'] });
    if (window.ResizeObserver) new ResizeObserver(syncBanner).observe(banner);
    else window.addEventListener('resize', syncBanner);
  }
}());
