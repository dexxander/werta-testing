<?php

namespace CommunityForum\Support;

class WordFilter
{
    public function filter(string $text): string
    {
        $words = array_values(array_filter(
            config('community.filtered_words', []),
            fn ($word) => is_string($word) && trim($word) !== ''
        ));

        if ($words === []) {
            return $text;
        }

        $words = array_values(array_unique(array_map('trim', $words)));
        usort($words, fn ($first, $second) => mb_strlen($second, 'UTF-8') <=> mb_strlen($first, 'UTF-8'));
        $alternatives = array_map(function ($word) {
            $literal = preg_quote($word, '~');

            // Chinese sentences normally have no spaces between words.
            if (preg_match('/\p{Han}/u', $word)) {
                return $literal;
            }

            return '(?<![\p{L}\p{M}\p{N}_])'.$literal.'(?![\p{L}\p{M}\p{N}_])';
        }, $words);
        $pattern = '~(?:'.implode('|', $alternatives).')~iu';

        return preg_replace_callback(
            $pattern,
            fn ($match) => str_repeat('*', mb_strlen($match[0], 'UTF-8')),
            $text
        ) ?? $text;
    }
}
