'use strict';
if (window.RafaCharts) {
  window.RafaCharts.line('CarChart', monthArr, totalPropertyArr, 'Properties published', '#bd9140');
  window.RafaCharts.line('visitorChart', monthArr, totalProjectsArr, 'Projects published', '#3b8b98');
}
