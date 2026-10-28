<?php

namespace Submissions\Database\Seeders;

use AdminDashboard\Database\Seeders\ReviewerSeeder;
use AdminDashboard\Models\Reviewer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Submissions\Models\Submission;
use Submissions\Models\SubmissionReview;

/**
 * DEMO data for articles, categories, reviews and the monetisation mock.
 * Safe to re-run: rows are matched on slug / email, so nothing duplicates.
 * php artisan db:seed --class="Submissions\Database\Seeders\SubmissionSeeder"
 */
class SubmissionSeeder extends Seeder
{
    private const PDF = 'submissions/demo/mindfulness-for-exam-season.pdf';

    public function run(): void
    {
        $this->call(ReviewerSeeder::class);

        // Sample PDF for the "first pages free" preview.
        if (! Storage::disk('public')->exists(self::PDF)) {
            Storage::disk('public')->put(self::PDF, file_get_contents(__DIR__ . '/files/mindfulness-for-exam-season.pdf'));
        }

        foreach ($this->papers() as $p) {
            Submission::updateOrCreate(['slug' => Str::slug($p['title'])], [
                'title'          => $p['title'],
                'category'       => $p['category'],
                'abstract'       => $p['abstract'],
                'content'        => $p['content'] ?? null,
                'file_path'      => $p['file'] ?? null,
                'author_role'    => $p['role'],
                'author_name'    => $p['name'],
                'status'         => $p['status'],
                'visibility'     => $p['visibility'] ?? 'public',
                'access_type'    => $p['access'],
                'price'          => $p['access'] === 'paid' ? 5.00 : null,
                'decision_notes' => $p['notes'] ?? null,
                'submitted_at'   => now()->subDays($p['days']),
            ]);
        }

        $reviewers = Reviewer::pluck('id', 'email');
        foreach ($this->reviews() as $slug => $rows) {
            $submission = Submission::where('slug', $slug)->first();
            if (! $submission) {
                continue;
            }
            foreach ($rows as $r) {
                if (! isset($reviewers[$r['by']])) {
                    continue;
                }
                $done = $r['status'] === 'completed';
                [$a, $o, $c, $rel, $e] = $r['scores'] ?? [null, null, null, null, null];
                SubmissionReview::updateOrCreate(
                    ['submission_id' => $submission->id, 'reviewer_id' => $reviewers[$r['by']]],
                    [
                        'status' => $r['status'],
                        'score_accuracy' => $a, 'score_originality' => $o, 'score_clarity' => $c,
                        'score_relevance' => $rel, 'score_ethics' => $e,
                        'recommendation' => $r['rec'] ?? null,
                        'suitable_for_sale' => $r['sale'] ?? null,
                        'comments' => $r['comments'] ?? null,
                        'suggestions' => $r['suggestions'] ?? null,
                        'confidential_notes' => $r['private'] ?? null,
                        'conflict_of_interest' => false,
                        'show_on_article' => $r['show'] ?? true,
                        'assigned_at' => now()->subDays($r['assigned']),
                        'due_at' => now()->subDays($r['assigned'])->addDays(14),
                        'submitted_at' => $done ? now()->subDays($r['done']) : null,
                    ]
                );
            }
        }
    }

    private function papers(): array
    {
        $demoBody = "This is demo premium content for the monetisation mock.\n\nIn the real build this text (or the uploaded paper) is served only to readers who have bought the paper, received it as a gift, or unlocked it with their subscription, with a per-reader watermark.\n\nIt stays in the database for now so the checkout, wallet and library can be demonstrated end to end.";

        return [
            [
                'title' => 'Managing Exam Anxiety: A Student\'s Guide', 'category' => 'education',
                'abstract' => 'Practical, evidence-based strategies for reducing exam-related stress.',
                'content' => "Exam anxiety is one of the most common stressors students face. It can show up as racing thoughts, a tight chest, or trouble sleeping in the days before a test.\n\nStart by breaking your revision into small daily blocks instead of long last-minute sessions. Short, regular study periods are easier to sustain and leave less room for panic.\n\nOn the day itself, slow breathing can calm the body quickly. Try breathing in for four counts and out for six, repeated a few times before you begin.\n\nFinally, protect your sleep and take short breaks. A rested mind recalls information far better than a tired one that stayed up cramming.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'submitted', 'access' => 'free', 'days' => 2,
            ],
            [
                'title' => 'Navigating Parenting in the Digital Age', 'category' => 'parenting',
                'abstract' => 'Balanced strategies for healthy tech habits at home.',
                'content' => "Screens are now part of family life, and most parents are unsure how much is too much. The goal is not to remove technology but to build healthy habits around it.\n\nBegin by agreeing on simple household rules together, such as no devices at meals and a set time to switch off in the evening. Children follow rules more willingly when they helped shape them.\n\nModel the behaviour you want to see. Children notice how often parents check their own phones.\n\nKeep the conversation open. Ask what your child enjoys online and who they talk to, so they feel safe coming to you if something goes wrong.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'published', 'access' => 'paid', 'days' => 40,
            ],
            [
                'title' => 'A Client\'s Journey Through Grief', 'category' => 'mental-health',
                'abstract' => 'A personal reflection shared with consent for educational use.',
                'content' => "When I first lost someone close to me, I expected sadness, but not the exhaustion, the anger, or the moments of numbness that came without warning.\n\nIn the early weeks I avoided talking about it. Sessions with my counselor helped me see that grief does not follow a schedule, and that there was no correct way to feel.\n\nWhat helped most was small routines: a daily walk, regular meals, and writing down memories when they surfaced.\n\nI am still grieving, but the weight feels different now. I am sharing this in the hope that it helps someone who feels alone in the same place.",
                'role' => 'client', 'name' => 'Client User', 'status' => 'revisions_requested', 'visibility' => 'private', 'access' => 'free', 'days' => 10,
                'notes' => 'Please expand the section on coping mechanisms.',
            ],
            [
                'title' => 'Understanding Sleep Deprivation in Teens', 'category' => 'science',
                'abstract' => 'How screen time and sleep hygiene affect adolescent mood.',
                'content' => "Teenagers need around eight to ten hours of sleep, yet many get far less. Changes in the body clock during adolescence make early bedtimes hard, and late-night screen use makes it worse.\n\nParents often notice irritability, poor concentration, and dropping grades before they notice the sleep problem behind them.\n\nA consistent bedtime, keeping phones out of the bedroom, and limiting caffeine in the afternoon can make a real difference within a couple of weeks.\n\nIf sleep problems persist despite these changes, it is worth speaking to a counselor or doctor, since poor sleep can be linked to low mood and anxiety.",
                'role' => 'parent', 'name' => 'Parent User', 'status' => 'accepted', 'access' => 'free', 'days' => 14,
            ],
            [
                'title' => 'Building Healthy Habits Early', 'category' => 'parenting',
                'abstract' => 'Foundations for lifelong emotional regulation in young children.',
                'content' => "The habits children form in their early years shape how they handle emotions for the rest of their lives. Small, repeated routines matter more than occasional big efforts.\n\nStart with predictable daily rhythms: regular meals, a calm bedtime routine, and time set aside for play. Predictability helps young children feel secure.\n\nTeach children to name their feelings. A child who can say they feel angry or sad is much better able to manage those feelings than one who cannot.\n\nPraise effort rather than results, and stay patient. Emotional regulation is a skill that grows slowly, with practice and support.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'published', 'access' => 'free', 'days' => 60,
            ],
            [
                'title' => 'Coping With Social Media Pressure', 'category' => 'social',
                'abstract' => 'Practical steps for teens and parents to reduce comparison stress.',
                'content' => "Social media shows everyone's best moments, and teenagers are quick to compare their ordinary days with other people's highlights. Over time this comparison can wear down confidence and mood.\n\nA helpful first step is noticing how different apps make you feel. If scrolling one feed leaves you flat or anxious, that is useful information, not a personal failing.\n\nSmall changes add up: muting accounts that trigger comparison, turning off non-essential notifications, and keeping the phone out of reach during homework and before sleep.\n\nParents can help most by staying curious rather than critical. Ask what your teen enjoys online and share your own struggles with screens, so the conversation feels two-way.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'published', 'access' => 'paid', 'days' => 25,
            ],
            [
                'title' => 'Mindfulness for Exam Season', 'category' => 'mental-health',
                'abstract' => 'A six-week classroom mindfulness programme for Form 4 and Form 5 students, delivered in ten-minute blocks, with the session plan, core exercises and findings from a small pilot.',
                'file' => self::PDF,
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'published', 'access' => 'paid', 'days' => 18,
            ],
            [
                'title' => 'Choosing a Career Path After SPM', 'category' => 'career',
                'abstract' => 'A step-by-step way for school leavers to compare pre-university, diploma and TVET routes.',
                'content' => "Finishing SPM brings a sudden list of choices: Form 6, matriculation, foundation, diploma, TVET or work. Many students pick the option their friends choose, then discover a year later that it does not suit them.\n\nStart with interests and strengths rather than course names. List the subjects you enjoyed, the activities that make time pass quickly, and the kind of problems you like solving.\n\nNext, compare routes on cost, duration and entry requirements. A diploma or TVET pathway can lead to strong careers and, later, a degree, often with less financial pressure.\n\nFinally, talk to people already working in the fields you are considering. A twenty-minute conversation can tell you more about daily work than any brochure.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'published', 'access' => 'paid', 'days' => 8,
            ],
            [
                'title' => 'Peer Support Circles in Secondary Schools', 'category' => 'social',
                'abstract' => 'How trained student volunteers can offer a first point of contact before counselling.',
                'content' => "Many students talk to friends long before they talk to a counsellor. Peer support circles build on this by training a small group of student volunteers to listen well and know when to refer.\n\nVolunteers learn simple skills: listening without fixing, asking open questions, and recognising signs that a friend needs adult help.\n\nCircles meet weekly with a counsellor present, which keeps volunteers supported and makes referrals easy.\n\nSchools that run circles often see students reach out earlier, when problems are still small.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'published', 'access' => 'free', 'days' => 33,
            ],
            [
                'title' => 'The Science of Teen Sleep Cycles', 'category' => 'science',
                'abstract' => 'Why adolescent body clocks shift later, and what schools and families can do about it.',
                'content' => "During puberty the body releases melatonin later in the evening, so teenagers genuinely feel awake at times when adults expect them to sleep.\n\nEarly school starts then cut sleep short, building a sleep debt across the week that weekend lie-ins only partly repay.\n\nLight exposure matters: bright screens late at night delay melatonin further, while morning daylight helps reset the clock.\n\nFamilies can protect sleep with consistent wake times, a wind-down hour without screens, and daylight soon after waking.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'under_review', 'access' => 'paid', 'days' => 5,
            ],
            [
                'title' => 'How Exercise Lifts Teen Mood', 'category' => 'science',
                'abstract' => 'What the research says about movement, sleep and mood in adolescence, in plain language.',
                'content' => "Regular physical activity is one of the most reliable ways to support mood. For teenagers, even moderate movement such as brisk walking or cycling can ease stress and improve sleep.\n\nExercise raises the body's natural mood chemicals and lowers stress hormones over time. It also gives the mind a break from worry, which is why many students feel clearer after a game or a walk.\n\nThe best activity is one a young person enjoys and can repeat. Team sports add friendship, while solo activities like running or dance can fit around busy timetables.\n\nParents can help by being active together and treating movement as part of the routine, not a reward or a punishment.",
                'role' => 'counselor', 'name' => 'Counselor User', 'status' => 'published', 'access' => 'free', 'days' => 48,
            ],
            [
                'title' => 'Study Skills That Actually Stick', 'category' => 'education',
                'abstract' => 'Retrieval practice, spacing and other evidence-based study habits a parent can coach at home.',
                'content' => "Many students revise by re-reading notes and highlighting. It feels productive, but research shows it does little for long-term memory.\n\nRetrieval practice works better: closing the book and trying to recall what you learned, then checking. Flashcards and past-year questions are simple ways to do this.\n\nSpacing matters too. Short sessions spread over several days beat one long session the night before.\n\nParents can coach these habits by asking their child to teach them a topic, which turns revision into recall without extra pressure.",
                'role' => 'parent', 'name' => 'Parent User', 'status' => 'published', 'access' => 'paid', 'days' => 12,
            ],
            [
                'title' => 'Talking About Anxiety With Your Counselor', 'category' => 'mental-health',
                'abstract' => 'A client\'s guide to getting more from counselling sessions.', 'content' => $demoBody,
                'role' => 'client', 'name' => 'Client User', 'status' => 'published', 'access' => 'paid', 'days' => 21,
            ],
            [
                'title' => 'A Parent\'s Guide to Teen Sleep', 'category' => 'parenting',
                'abstract' => 'Evidence-based routines that help adolescents sleep better.', 'content' => $demoBody,
                'role' => 'parent', 'name' => 'Parent User', 'status' => 'published', 'access' => 'paid', 'days' => 30,
            ],
            [
                'title' => 'Supporting a Child Through School Transitions', 'category' => 'education',
                'abstract' => 'Helping children settle when they move from primary to secondary school.',
                'content' => "Moving to secondary school means new buildings, new teachers and new friendship groups all at once. Some children adjust in weeks; others take a full term.\n\nVisit the school together before the first day if you can, and practise the journey so that at least one part of the change feels familiar.\n\nIn the first weeks, ask about small things, such as who they sat with at lunch, rather than asking whether they like the school.\n\nIf worries persist past the first term, talk to the class teacher or school counsellor early.",
                'role' => 'parent', 'name' => 'Parent User', 'status' => 'accepted', 'access' => 'paid', 'days' => 9,
            ],
        ];
    }

    /** slug => reviews. scores = [accuracy, originality, clarity, relevance, ethics], each 1–5. */
    private function reviews(): array
    {
        $aisyah = 'aisyah.rahman@example.com';
        $lim = 'lim.weijie@example.com';
        $kavitha = 'kavitha.nair@example.com';
        $tan = 'tan.meiling@example.com';

        return [
            'navigating-parenting-in-the-digital-age' => [
                ['by' => $aisyah, 'status' => 'completed', 'scores' => [5, 4, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 50, 'done' => 44,
                    'comments' => 'Clear, practical and well grounded. Families will find the household rules section especially useful.'],
                ['by' => $lim, 'status' => 'completed', 'scores' => [4, 4, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 50, 'done' => 46,
                    'comments' => 'Strong structure and an accessible tone. Suitable as a premium paper.'],
            ],
            'coping-with-social-media-pressure' => [
                ['by' => $tan, 'status' => 'completed', 'scores' => [4, 4, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'show' => false, 'assigned' => 35, 'done' => 29,
                    'comments' => 'Sensitive and balanced. The advice for parents is realistic.'],
                ['by' => $aisyah, 'status' => 'completed', 'scores' => [5, 4, 4, 5, 5], 'rec' => 'minor_revision', 'sale' => true, 'assigned' => 35, 'done' => 30,
                    'comments' => 'Good piece. Minor wording changes were made before publication.'],
            ],
            'mindfulness-for-exam-season' => [
                ['by' => $aisyah, 'status' => 'completed', 'scores' => [5, 5, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 26, 'done' => 21,
                    'comments' => 'An excellent, ready-to-use programme. The safety note on stillness is important and well handled.'],
                ['by' => $lim, 'status' => 'completed', 'scores' => [4, 5, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 26, 'done' => 22,
                    'comments' => 'Teachers can run this with minimal training. Highly recommended.'],
            ],
            'choosing-a-career-path-after-spm' => [
                ['by' => $lim, 'status' => 'completed', 'scores' => [4, 4, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 15, 'done' => 10,
                    'comments' => 'Useful and balanced across pathways, including TVET.'],
            ],
            'talking-about-anxiety-with-your-counselor' => [
                ['by' => $aisyah, 'status' => 'completed', 'scores' => [4, 4, 4, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 28, 'done' => 23,
                    'comments' => 'A helpful client perspective that will reassure first-time clients.'],
            ],
            'a-parents-guide-to-teen-sleep' => [
                ['by' => $kavitha, 'status' => 'completed', 'scores' => [5, 4, 4, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 38, 'done' => 32,
                    'comments' => 'Accurate on sleep needs and routines.'],
            ],
            'building-healthy-habits-early' => [
                ['by' => $kavitha, 'status' => 'completed', 'scores' => [4, 3, 5, 5, 5], 'rec' => 'accept', 'sale' => false, 'assigned' => 66, 'done' => 61,
                    'comments' => 'Sound general guidance. Better suited as a free introductory article.'],
            ],
            'peer-support-circles-in-secondary-schools' => [
                ['by' => $tan, 'status' => 'completed', 'scores' => [4, 4, 4, 5, 5], 'rec' => 'accept', 'sale' => false, 'show' => false, 'assigned' => 40, 'done' => 35,
                    'comments' => 'A good overview for schools considering peer support.'],
            ],
            'how-exercise-lifts-teen-mood' => [
                ['by' => $kavitha, 'status' => 'completed', 'scores' => [5, 3, 5, 5, 5], 'rec' => 'accept', 'sale' => false, 'assigned' => 52, 'done' => 49,
                    'comments' => 'Accurate and encouraging. A good free introduction to the topic.'],
            ],
            'study-skills-that-actually-stick' => [
                ['by' => $lim, 'status' => 'completed', 'scores' => [5, 4, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 16, 'done' => 13,
                    'comments' => 'Evidence-based and practical. Parents will find the coaching tips useful.'],
            ],
            'a-clients-journey-through-grief' => [
                ['by' => $aisyah, 'status' => 'completed', 'scores' => [4, 4, 3, 4, 5], 'rec' => 'major_revision', 'sale' => false, 'assigned' => 9, 'done' => 6,
                    'comments' => 'A moving and honest reflection. It needs more on what helped, so readers can take something practical from it.',
                    'suggestions' => 'Expand the coping section with two or three concrete strategies, and add where readers can find support.',
                    'private' => 'Author consent for educational use is noted. Check identifying details before publication.'],
            ],
            'understanding-sleep-deprivation-in-teens' => [
                ['by' => $kavitha, 'status' => 'completed', 'scores' => [4, 3, 4, 5, 5], 'rec' => 'accept', 'sale' => false, 'assigned' => 13, 'done' => 7,
                    'comments' => 'Accurate and accessible. Recommend publishing as a free article.'],
            ],
            'supporting-a-child-through-school-transitions' => [
                ['by' => $lim, 'status' => 'completed', 'scores' => [4, 4, 5, 5, 5], 'rec' => 'accept', 'sale' => true, 'assigned' => 8, 'done' => 4,
                    'comments' => 'Practical and warm. Suitable for sale.'],
            ],
            'the-science-of-teen-sleep-cycles' => [
                ['by' => $aisyah, 'status' => 'assigned', 'assigned' => 3],
                ['by' => $kavitha, 'status' => 'assigned', 'assigned' => 3],
            ],
        ];
    }
}
