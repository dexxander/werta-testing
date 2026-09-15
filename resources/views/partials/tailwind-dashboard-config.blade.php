{{--
    Tailwind CSS CDN Theme Configuration for Dashboards.

    SYNC NOTE:
    These 7 color values intentionally mirror the `:root` design token block in
    `resources/views/layouts/app.blade.php`. Because dashboard layouts are standalone
    HTML documents loading Tailwind via CDN (https://cdn.tailwindcss.com) rather than
    extending `layouts/app.blade.php`, they cannot inherit the public site's CSS variables.

    Any changes to the brand color tokens in `layouts/app.blade.php` MUST be manually
    synchronized here as well to prevent visual drift between the public site and dashboards.

    PARITY NOTE:
    All 7 colors from `:root` are defined below for full parity, although `primary-dark`
    (#574B22) and `muted` (#6B6455) are not currently referenced in dashboard views.
    Do not remove them — they preserve complete token coverage across all portal scopes.
--}}
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'cream':        '#F5EFE0',
                    'cream-light':  '#FDFAF4',
                    'primary':      '#7B6B35',
                    'primary-dark': '#574B22',
                    'gold':         '#C4A840',
                    'dark':         '#2C2416',
                    'muted':        '#6B6455',
                },
                boxShadow: {
                    'sidebar': '2px 0 10px rgba(0,0,0,0.02)',
                }
            }
        }
    }
</script>
