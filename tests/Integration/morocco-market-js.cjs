const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const script = fs.readFileSync(require('node:path').join(__dirname, '../../public/assets/js/morocco-market.js'), 'utf8');
function run(value, disabled) {
    let changed = 0;
    const select = {
        value, selectedIndex: 0,
        options: [{ value, disabled }, { value: '42', disabled: false }],
        dispatchEvent() { changed++; }
    };
    vm.runInNewContext(script, {
        window: { rafaMorocco: { countryId: 42 } },
        document: { readyState: 'complete', querySelectorAll(selector) { return selector.startsWith('select') ? [select] : []; } },
        Event: class Event {}
    });
    return { value: select.value, changed };
}
assert.deepEqual(run('Select Country', true), { value: '42', changed: 1 });
assert.deepEqual(run('42', false), { value: '42', changed: 0 });
assert.deepEqual(run('17', false), { value: '17', changed: 0 });
console.log('PASS: placeholder selects Morocco and loads regions; existing selections are preserved.');
