<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class SiteText
{
    /** Translate known interface copy only; never send user content to a third party. */
    public static function translate(mixed $value): mixed
    {
        if (! is_string($value) || $value === '' || app()->getLocale() === 'en') {
            return $value;
        }

        $messages = self::catalog();
        if (isset($messages[$value])) {
            return $messages[$value];
        }

        if (preg_match('/^(\d+)h$/', $value, $match)) {
            return __(':count hours', ['count' => $match[1]]);
        }

        // Database enum values remain unchanged; only their displayed labels change.
        $labels = [
            'public' => 'Public', 'private' => 'Private', 'free' => 'Free',
            'paid' => 'Paid', 'premium' => 'PREMIUM', 'pending' => 'Pending',
            'approved' => 'Approved', 'rejected' => 'Rejected', 'published' => 'Published',
            'draft' => 'Draft', 'submitted' => 'Submitted', 'accepted' => 'Accepted',
            'under_review' => 'Under Review', 'revisions_requested' => 'Revisions Requested',
            'client' => 'Client', 'parent' => 'Parent', 'counselor' => 'Counselor',
            'admin' => 'Admin', 'superadmin' => 'Superadmin',
        ];

        $labelKey = strtolower($value);

        return isset($labels[$labelKey], $messages[$labels[$labelKey]])
            ? $messages[$labels[$labelKey]]
            : $value;
    }

    public static function catalog(): array
    {
        static $catalogs = [];
        $locale = app()->getLocale();

        if (! in_array($locale, ['en', 'ms', 'zh'], true)) {
            return [];
        }

        return $catalogs[$locale] ??= json_decode(
            file_get_contents(lang_path($locale.'.json')), true, 512, JSON_THROW_ON_ERROR
        );
    }

    /** Format existing display dates; never use this for submitted date values. */
    public static function date(?string $value): string
    {
        if (! $value || app()->getLocale() === 'en') {
            return $value ?? '';
        }

        if (preg_match('/^\d{1,2} [A-Za-z]{3}( \d{4})?$/', $value, $match)) {
            return Carbon::parse($value)->translatedFormat(isset($match[1]) ? 'd M Y' : 'j M');
        }

        return $value;
    }

    public static function clientCatalog(): array
    {
        $keys = [
            'The Adaptive Innovator', 'The Caring Free-Spirit', 'The Creative Seeker',
            'The Dedicated Supporter', 'The Deep Conceptualizer', 'The Dynamic Problem-Solver',
            'The Empathetic Harmonizer', 'The Gentle Observer', 'The Grounded Analyst',
            'The Inspiring Explorer', 'The Mindful Explorer', 'The Practical Realist',
            'The Quiet Strategist', 'The Strategic Organizer', 'The Structured Connector',
            'The Thoughtful Idealist', 'The Visionary Guide', 'Profile Pattern: :code',
            'Grid View', 'Interactive View', 'The map could not be loaded. Please try again later.',
            ':count configured locations could not be located on the map.', 'Guidance Center',
            "Please enter your child's name.", 'Child account registered for :name (:email).',
            'no email provided',
            'Please enter a valid IC number.', 'You must be at least 17 years old to register as a client.',
            'Default', 'Latest', 'Oldest', 'Name: A - Z', 'Name: Z - A', 'Most Viewed',
            'Not recorded', 'Not available.', 'Client', 'Active', 'Discharged',
            'Video call', 'In person', '50 mins', '75 mins', 'Individual · Online',
            'Individual · In person', 'Group (4 members) · Online',
            'Academic Anxiety', 'Depression & Mood', 'Career Transitions', 'Relationship Counseling',
            'Discussed the upcoming exam timetable and built a weekly study plan.',
            'Goal setting, structured schedule review, and breathing exercise.',
            'Apply the 25-minute study intervals and note any focus blocks.',
            'Reviewed weekly mood tracking journal and daily physical movement.',
            'Activity scheduling and morning routine planning.',
            'Aim for a 15-minute morning walk three times this week.',
            'Facilitated group discussion on managing deadlines and peer expectations.',
            'Peer sharing reflection and guided group relaxation exercise.',
            'Each member to identify one supportive habit to test before next group meet.',
            'Final review session. Discussed sustained boundary setting and communication habits.',
            'Progress reflection and maintenance planning.',
            'Discharge complete; client welcome to check in on an as-needed basis.',
            // Articles, wallet and reviewer pages
            ':count more pages in the full paper.', 'The paper could not be loaded. Run php artisan storage:link and refresh.',
            'Link copied', 'Decline this review?', ':count words', 'Replace what you have written with the template?',
        ];

        return array_intersect_key(self::catalog(), array_flip($keys));
    }
}
