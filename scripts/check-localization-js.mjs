import fs from 'node:fs';
import vm from 'node:vm';
import assert from 'node:assert/strict';

const entries = JSON.parse(fs.readFileSync('storage/framework/testing/localization-scripts.json', 'utf8'));
let helpers = 0;
for (const entry of entries) {
    const script = new vm.Script(entry.code, { filename: entry.page });
    if (entry.code.includes('window.WertaI18n =')) {
        const context = vm.createContext({ window: {} });
        script.runInContext(context);
        const { t, locale } = context.window.WertaI18n;
        const translator = context.window.WertaI18n;
        assert.equal(t.call(translator, 'unlisted <text>'), 'unlisted <text>');
        assert.ok(t.call(translator, 'Profile Pattern: :code', { code: 'ET–JS' }).includes('ET–JS'));
        assert.ok(t.call(translator, 'Child account registered for :name (:email).', { name: "O'Neil", email: 'test@example.com' }).includes("O'Neil"));
        assert.notEqual(t.call(translator, 'The Structured Connector'), 'The Structured Connector', locale);
        helpers++;
    }
}
console.log(`Validated ${entries.length} inline scripts and exercised ${helpers} translation helpers.`);
