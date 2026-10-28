<?php

namespace Submissions\Support;

/**
 * Manuscript templates and author guidelines for the "Write directly" form,
 * modelled on journal submission systems. Templates use the simple formatting
 * that ContentRenderer understands: "## " starts a section heading, "- " a bullet,
 * blank lines separate paragraphs. Text in [square brackets] is guidance the
 * author must replace (store() rejects a manuscript that still contains it).
 */
class ManuscriptTemplates
{
    public const TYPES = ['research', 'guide', 'reflection'];

    public static function all(): array
    {
        return [
            'research' => [
                'label' => __('Research paper'),
                'icon' => 'bi-clipboard-data',
                'desc' => __('Studies, pilots, surveys or reviews of evidence.'),
                'words' => __('2,000–5,000 words'),
                'sections' => [
                    [__('Keywords'), __('3–5 keywords separated by commas, e.g. exam stress, secondary school, mindfulness.')],
                    [__('1. Introduction'), __('What problem does this paper address, who is affected, and why does it matter now? End with the aim of the paper in one sentence.')],
                    [__('2. Background'), __('Summarise what is already known, citing sources (Author, Year). Explain the gap your paper fills.')],
                    [__('3. Method'), __('Describe what you did: participants or sources, setting, tools, and how you analysed the information. Say how consent and confidentiality were handled.')],
                    [__('4. Findings'), __('Report what you found, clearly and without interpretation. Use short paragraphs or bullet points for key results.')],
                    [__('5. Discussion'), __('What do the findings mean? Compare them with earlier work and be honest about limitations.')],
                    [__('6. Practical recommendations'), __('What should students, parents, schools or counsellors do differently because of this work?')],
                    [__('7. Conclusion'), __('Two or three sentences that restate the main message.')],
                    [__('References'), __('List every source cited, in APA 7th edition style, e.g. Tan, L. (2024). Title of the work. Publisher.')],
                ],
            ],
            'guide' => [
                'label' => __('Practical guide'),
                'icon' => 'bi-signpost-split',
                'desc' => __('Step-by-step advice for students, parents or schools.'),
                'words' => __('1,200–3,000 words'),
                'sections' => [
                    [__('Who this guide is for'), __('Describe the reader in one or two sentences, e.g. parents of teenagers preparing for SPM.')],
                    [__('Key points'), __('List 3–5 takeaways as bullet points, each starting with "- ".')],
                    [__('The challenge'), __('Explain the problem in everyday language. A short example or scenario helps readers recognise themselves.')],
                    [__('Step-by-step guidance'), __('Give clear, practical steps. Use one short paragraph per step, starting with "Step 1:", "Step 2:" and so on.')],
                    [__('When to seek more help'), __('Name the warning signs that mean a reader should speak to a counsellor, doctor or teacher, and how to reach them.')],
                    [__('Summary'), __('Recap the steps in two or three sentences.')],
                    [__('References'), __('List any sources you relied on, in APA 7th edition style.')],
                ],
            ],
            'reflection' => [
                'label' => __('Personal reflection'),
                'icon' => 'bi-chat-heart',
                'desc' => __('A lived experience shared to help others.'),
                'words' => __('800–2,000 words'),
                'sections' => [
                    [__('Consent and privacy'), __('Confirm that you are sharing your own story, or have consent from anyone described. Change names and identifying details.')],
                    [__('Background'), __('Set the scene: what was happening in your life before this experience?')],
                    [__('What happened'), __('Describe the experience honestly. Focus on feelings and turning points rather than graphic detail.')],
                    [__('What helped'), __('Share the people, routines or support that made a difference. Be specific so readers can try them.')],
                    [__('What I learned'), __('What do you understand now that you did not before?')],
                    [__('Where to find support'), __('Point readers to help, such as their school counsellor, a Werta counsellor or a helpline.')],
                ],
            ],
        ];
    }

    /** Plain-text template for the textarea. */
    public static function text(string $type): string
    {
        $tpl = self::all()[$type] ?? self::all()['research'];
        $blocks = [];
        foreach ($tpl['sections'] as [$heading, $guidance]) {
            $blocks[] = '## ' . $heading;
            $blocks[] = '[' . $guidance . ']';
        }

        return implode("\n\n", $blocks);
    }

    /** True if the text still contains a whole line of [template guidance]. */
    public static function hasPlaceholders(?string $content): bool
    {
        return (bool) preg_match('/^\s*\[[^\]\n]{6,}\]\s*$/mu', (string) $content);
    }

    /** Author guidelines shown beside the form. [icon, title, text] */
    public static function guidelines(): array
    {
        return [
            ['bi-file-earmark-text', __('Structure'), __('Use a template or the same sections: introduction, main body, practical advice and references. Give every section a heading.')],
            ['bi-card-text', __('Abstract'), __('150–250 words covering the purpose, approach, key points and why it matters. Readers see it before buying.')],
            ['bi-rulers', __('Length'), __('Research papers 2,000–5,000 words, guides 1,200–3,000, reflections 800–2,000.')],
            ['bi-quote', __('References'), __('Cite sources in the text (Author, Year) and list them at the end in APA 7th edition style.')],
            ['bi-shield-check', __('Ethics and privacy'), __('Remove names and details that could identify a client, student or family. Get consent for any personal story.')],
            ['bi-patch-check', __('Originality'), __('Submit only your own work that has not been published elsewhere. Reviewers check for copied text.')],
            ['bi-translate', __('Language'), __('Write in English, Bahasa Melayu or Chinese. Keep sentences short and explain technical terms.')],
            ['bi-image', __('Tables and figures'), __('Writing directly supports text only. Upload a PDF or Word file if your paper needs tables, charts or images.')],
        ];
    }
}
