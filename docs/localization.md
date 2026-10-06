# Site localization

Werta supports English (`en` / BI), Bahasa Melayu (`ms` / BM), and Simplified Chinese (`zh` / 中文). The saved session choice takes priority over browser language detection. All web packages use the same locale middleware.

## Updating translations

`lang/site.json` is the source catalog. Each English key maps to `[Malay translation, Chinese translation]`. After editing it, run:

```sh
node scripts/build-translations.mjs
node scripts/build-translations.mjs --check
```

Commit the generated `lang/en.json`, `lang/ms.json`, and `lang/zh.json` together with the source. Navbar entries remain in `lang/{locale}/nav.php`; form validation messages are in `lang/{locale}/validation.php`.

Use `{{ __('English interface text') }}` in Blade. For text with variable values, use named placeholders, for example `__('Profile Pattern: :code', ['code' => $code])`. Keep complete sentences together when adding new copy. `SiteText::translate()` translates known display labels from arrays and database enums without changing submitted values. Unknown strings retain their original text.

Client-side interactions use `WertaI18n.t(message, replacements)`, loaded before page scripts and Alpine. Add new browser-side keys to `SiteText::clientCatalog()` as well as the source catalog. The catalog is safely encoded with Laravel's `Js::from`; insert translated content through text APIs, not HTML concatenation.

User-written articles, biographies, messages, names, uploaded documents, place names, and technical identifiers remain as authored. This is local interface localization; no personal content is sent to a machine-translation provider. New content requires its own translations if multilingual publication is needed.

## Verification

```sh
php artisan test --filter=SiteLocalizationTest
php artisan test
```

The tests render public, authenticated, populated and empty page states, check translated visible text and attributes, preserve questionnaire answer values, validate placeholders, and exercise form errors. To inspect remaining Latin-script text and browser scripts in PowerShell:

```powershell
$env:LOCALE_AUDIT='1'
$env:LOCALE_JS_AUDIT='1'
php artisan test --filter=SiteLocalizationTest
node scripts/check-localization-js.mjs
```

These automated checks do not replace a visual browser review or clinical validation of assessment wording.
