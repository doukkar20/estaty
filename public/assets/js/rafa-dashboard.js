(function () {
  'use strict';
  document.querySelectorAll('[data-rafa-scroll-top]').forEach(function (button) {
    button.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
    });
  });
  document.querySelectorAll('.sidebar-search').forEach(function (input) {
    input.setAttribute('aria-label', input.placeholder || 'Search navigation');
    input.setAttribute('type', 'search');
    const sidebar = input.closest('.sidebar');
    if (!sidebar) return;
    const items = Array.from(sidebar.querySelectorAll('li.nav-item'));
    const empty = document.createElement('p');
    empty.className = 'px-4 py-3 text-white';
    empty.setAttribute('role', 'status');
    empty.hidden = true;
    empty.textContent = input.dataset.emptyLabel || 'No matching menu items';
    input.parentElement.appendChild(empty);
    let initialExpanded = null;
    function search() {
      const term = input.value.trim().toLocaleLowerCase();
      if (term && !initialExpanded) initialExpanded = new Map(Array.from(sidebar.querySelectorAll('.collapse')).map(function (panel) {
        return [panel, panel.classList.contains('show')];
      }));
      let visible = 0;
      items.forEach(function (item) {
        const matches = !term || item.textContent.toLocaleLowerCase().includes(term);
        item.classList.toggle('d-none', !matches);
        item.classList.remove('d-block');
        if (matches) visible++;
        if (term && matches && window.jQuery) window.jQuery(item).find('.collapse').collapse('show');
      });
      if (!term && initialExpanded) {
        if (window.jQuery) initialExpanded.forEach(function (expanded, panel) {
          window.jQuery(panel).collapse(expanded ? 'show' : 'hide');
        });
        initialExpanded = null;
      }
      empty.hidden = visible > 0 || !term;
    }
    input.addEventListener('input', search);
    input.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') { input.value = ''; search(); }
    });
  });
  document.querySelectorAll('.rafa-dashboard .table-responsive').forEach(function (table) {
    table.setAttribute('tabindex', '0');
    table.setAttribute('role', 'region');
    const card = table.closest('.card');
    const heading = card && card.querySelector('.card-title');
    table.setAttribute('aria-label', heading ? heading.textContent.trim() : 'Table');
  });
  // Chart.js 2 adapter. The individual dashboards supply their server data.
  window.RafaCharts = {
    line: function (id, labels, values, label, color) {
      const canvas = document.getElementById(id);
      if (!canvas || typeof window.Chart !== 'function') return;
      const points = Array.isArray(values) ? values.map(function (value) {
        const number = Number(value);
        return Number.isFinite(number) ? number : 0;
      }) : [];
      const dark = document.body.dataset.backgroundColor === 'dark';
      const text = dark ? '#acbbcd' : '#617184';
      const grid = dark ? '#304357' : '#edf0f4';
      canvas.setAttribute('role', 'img');
      canvas.setAttribute('aria-label', label + ': ' + points.reduce(function (sum, number) { return sum + number; }, 0));
      const context = canvas.getContext('2d');
      if (!context) return;
      const chart = new window.Chart(context, {
        type: 'line',
        data: { labels: labels, datasets: [{ label: label, data: points, borderColor: color, backgroundColor: 'transparent',
          pointBackgroundColor: color, pointBorderColor: dark ? '#172b40' : '#fff', pointBorderWidth: 2,
          pointRadius: 3, pointHoverRadius: 6, borderWidth: 3, fill: false, lineTension: 0.3 }] },
        options: {
          responsive: true, maintainAspectRatio: false,
          animation: { duration: window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 500 },
          legend: { display: false },
          tooltips: { mode: 'index', intersect: false, backgroundColor: '#102b46', xPadding: 12, yPadding: 12, cornerRadius: 8 },
          scales: {
            xAxes: [{ gridLines: { display: false }, ticks: { fontColor: text, maxRotation: 0, autoSkip: true, maxTicksLimit: 12 } }],
            yAxes: [{ gridLines: { color: grid, drawBorder: false }, ticks: { beginAtZero: true, fontColor: text, maxTicksLimit: 5 } }]
          },
          layout: { padding: { top: 14, right: 8, bottom: 4, left: 0 } }
        }
      });
      if (points.every(function (number) { return number === 0; })) {
        const card = canvas.closest('.card');
        const message = card && card.querySelector('.rafa-chart-empty');
        if (message) message.hidden = false;
      }
      return chart;
    }
  };
}());
