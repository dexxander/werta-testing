import fs from 'node:fs';
import assert from 'node:assert/strict';

// The source contains English keys and [Bahasa Melayu, Simplified Chinese] values.
const source = JSON.parse(fs.readFileSync('lang/site.json', 'utf8'));
for (const [key, values] of Object.entries(source)) {
    assert.equal(values.length, 2, key);
    for (const value of values) {
        assert.ok(typeof value === 'string' && value.trim(), key);
        assert.deepEqual((value.match(/:[a-z_]+/g) ?? []).sort(), (key.match(/:[a-z_]+/g) ?? []).sort(), key);
    }
}

for (const [locale, index] of [['en', -1], ['ms', 0], ['zh', 1]]) {
    const expected = JSON.stringify(Object.fromEntries(Object.entries(source).map(([key, values]) => [key, index < 0 ? key : values[index]])), null, 2) + '\n';
    const file = `lang/${locale}.json`;
    if (process.argv.includes('--check')) {
        assert.equal(fs.readFileSync(file, 'utf8'), expected, `${file} is out of date; run node scripts/build-translations.mjs`);
    } else {
        fs.writeFileSync(file, expected);
    }
}
console.log(`${Object.keys(source).length} translation entries validated for en, ms and zh.`);
