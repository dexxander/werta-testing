@if (app()->getLocale() === 'zh')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@300;400;500;600;700;800;900&display=swap">
    <style>
        /* Use Simplified Chinese glyphs across translated text and form controls.
           Keep icon fonts and the handwritten Werta wordmark intact. */
        html:lang(zh) body,
        html:lang(zh) body *:not(.bi):not([class*="fa-"]):not(.fa):not([style*="Great Vibes"]):not(.brand-w) {
            font-family: 'Noto Sans SC', 'Microsoft YaHei', 'PingFang SC', sans-serif !important;
        }
    </style>
@endif

<script>
    window.WertaI18n = {
        locale: @json(app()->getLocale()),
        messages: {{ Illuminate\Support\Js::from(\App\Support\SiteText::clientCatalog()) }},
        t(message, replacements = {}) {
            let text = Object.prototype.hasOwnProperty.call(this.messages, message) ? this.messages[message] : message;
            for (const [key, value] of Object.entries(replacements)) {
                text = text.replaceAll(':' + key, String(value));
            }
            return text;
        }
    };
</script>
