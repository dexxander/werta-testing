<?php

namespace Submissions\Support;

use Illuminate\Support\HtmlString;

/**
 * Turns manuscript text into safe HTML. Everything is escaped; the only
 * formatting understood is:
 *   "## Heading"  section heading (first line of a block)
 *   "- item"      bullet list (every line of a block)
 *   blank line    new paragraph
 */
class ContentRenderer
{
    /** Split text into trimmed, non-empty blocks. */
    public static function blocks(?string $content): array
    {
        $text = str_replace("\r\n", "\n", (string) $content);

        return array_values(array_filter(array_map('trim', preg_split('/\n\s*\n/', $text)), fn ($b) => $b !== ''));
    }

    public static function isHeading(string $block): bool
    {
        return str_starts_with($block, '## ') && ! str_contains($block, "\n");
    }

    public static function render(array $blocks): HtmlString
    {
        $html = '';
        foreach ($blocks as $block) {
            $lines = explode("\n", $block);

            // A heading may be followed by text in the same block.
            if (str_starts_with($lines[0], '## ')) {
                $html .= '<h2>' . e(trim(substr(array_shift($lines), 3))) . '</h2>';
                if (! $lines) {
                    continue;
                }
                $block = implode("\n", $lines);
            }

            if (collect($lines)->every(fn ($l) => preg_match('/^\s*[-*•]\s+/u', $l))) {
                $html .= '<ul>' . collect($lines)
                    ->map(fn ($l) => '<li>' . e(preg_replace('/^\s*[-*•]\s+/u', '', $l)) . '</li>')
                    ->implode('') . '</ul>';
            } else {
                $html .= '<p>' . nl2br(e($block), false) . '</p>';
            }
        }

        return new HtmlString($html);
    }

    public static function html(?string $content): HtmlString
    {
        return self::render(self::blocks($content));
    }

    /** Plain words of a set of blocks, without formatting marks. */
    public static function plain(array $blocks): string
    {
        return trim(preg_replace(['/^##\s+/mu', '/^\s*[-*•]\s+/mu'], '', implode("\n", $blocks)));
    }
}
