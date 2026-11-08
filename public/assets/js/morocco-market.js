(function () {
    'use strict';
    function setup() {
        document.querySelectorAll('input[name="phone"], input[name="contact_number"], input[name="whatsapp_number"]').forEach(function (input) {
            input.type = 'tel';
            input.inputMode = 'tel';
            input.placeholder = '+212 6 12 34 56 78';
            input.autocomplete = 'tel';
        });
        document.querySelectorAll('input[name="zip_code"]').forEach(function (input) {
            input.inputMode = 'numeric';
            input.placeholder = '10000';
            input.maxLength = 5;
        });
        document.querySelectorAll('select[name="country_id"], select[name="country"]').forEach(function (select) {
            var selected = select.options[select.selectedIndex];
            if (selected && !selected.disabled && select.value && select.value !== 'all') return;
            var target = String(window.rafaMorocco.countryId);
            if (!Array.from(select.options).some(function (option) { return option.value === target; })) return;
            select.value = target;
            if (window.jQuery) window.jQuery(select).trigger('change');
            else select.dispatchEvent(new Event('change', {bubbles: true}));
        });
    }
    if (window.jQuery) window.jQuery(function () { window.setTimeout(setup, 100); });
    else if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup);
    else setup();
}());
