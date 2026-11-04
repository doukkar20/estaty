'use strict';
if (window.RafaCharts) {
  window.RafaCharts.line('packagePurchaseChart', monthArr, packagePurchaseIncomesArr, 'Monthly package revenue', '#bd9140');
  window.RafaCharts.line('userChart', monthArr, totalUsersArr, 'Registered users', '#3b8b98');
}
