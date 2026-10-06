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
