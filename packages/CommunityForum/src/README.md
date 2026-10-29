# Community Forum

Self-contained Laravel module for `/community`, following the repository's package conventions.

- `src/Providers`: registers routes, migrations, views and translations.
- `src/Http/Controllers` and `src/Models`: discussions and replies.
- `src/Database/migrations`: dedicated `community_posts` and `community_replies` tables.
- `src/resources`: responsive Blade views and English, Malay and Simplified Chinese translations.
- `tests`: feature coverage for access, posting, replies, filtering, validation and escaping.

Integration points outside this package are the PSR-4 entry in `composer.json`, the provider in `bootstrap/providers.php`, and links in the shared navbar/footer.

Word filtering is configured in `config/community.php` under `filtered_words`, with English, Bahasa Malaysia, Simplified Chinese and Traditional Chinese terms and regional variants. New post titles, bodies and replies replace each matched character with `*` before saving. Matching ignores case and treats entries literally, preferring longer phrases. Latin-script entries respect whole-word boundaries; entries containing Chinese characters also match inside unspaced sentences. Add spelling variants separately. The list is not exhaustive or context-aware: ambiguous terms also match in innocent contexts, and unlisted spellings or obfuscations may bypass it. Existing content is not changed. After changing the list on a deployment with cached configuration, run `php artisan config:cache`.

Setup:

```sh
composer dump-autoload
php artisan migrate --path=packages/CommunityForum/src/Database/migrations
```

Test:

```sh
php artisan test packages/CommunityForum/src/tests
```

Visitors can read discussions. Client, parent and counselor sessions can publish and reply, following the application's existing session-based login conventions. Display names are snapshots from those sessions, not verified account identities. User content is escaped and remains in its original language. Writes use CSRF protection, length validation and request throttling.

Members can edit or delete replies created during their current login session. Ownership uses a server-generated UUID, never a display name or submitted owner value, and is cleared when role sessions are cleared on login/logout. The current authentication system has no persistent member IDs, so ownership does not carry across login sessions or devices. Replies predating the ownership migration remain read-only. Replace session ownership with verified account IDs when persistent authentication is introduced. Edits retain length validation and word filtering. Deleting a reply preserves child replies and clears their parent reference. Discussion editing/deletion and moderation tools are not included.
