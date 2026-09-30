<?php

namespace Submissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Submissions\Models\Submission;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        Submission::insert([
            [
                'title' => 'Managing Exam Anxiety: A Student\'s Guide',
                'slug' => Str::slug('Managing Exam Anxiety: A Student\'s Guide'),
                'abstract' => 'Practical, evidence-based strategies for reducing exam-related stress.',
                'content' => "Exam anxiety is one of the most common stressors students face. It can show up as racing thoughts, a tight chest, or trouble sleeping in the days before a test.\n\nStart by breaking your revision into small daily blocks instead of long last-minute sessions. Short, regular study periods are easier to sustain and leave less room for panic.\n\nOn the day itself, slow breathing can calm the body quickly. Try breathing in for four counts and out for six, repeated a few times before you begin.\n\nFinally, protect your sleep and take short breaks. A rested mind recalls information far better than a tired one that stayed up cramming.",
                'author_role' => 'counselor',
                'author_name' => 'Counselor User',
                'status' => 'submitted',
                'visibility' => 'public',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(2),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Navigating Parenting in the Digital Age',
                'slug' => Str::slug('Navigating Parenting in the Digital Age'),
                'abstract' => 'Balanced strategies for healthy tech habits at home.',
                'content' => "Screens are now part of family life, and most parents are unsure how much is too much. The goal is not to remove technology but to build healthy habits around it.\n\nBegin by agreeing on simple household rules together, such as no devices at meals and a set time to switch off in the evening. Children follow rules more willingly when they helped shape them.\n\nModel the behaviour you want to see. Children notice how often parents check their own phones.\n\nKeep the conversation open. Ask what your child enjoys online and who they talk to, so they feel safe coming to you if something goes wrong.",
                'author_role' => 'counselor',
                'author_name' => 'Counselor User',
                'status' => 'published',
                'visibility' => 'public',
                'access_type' => 'paid',
                'price' => 9.90,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(5),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'A Client\'s Journey Through Grief',
                'slug' => Str::slug('A Client\'s Journey Through Grief'),
                'abstract' => 'A personal reflection shared with consent for educational use.',
                'content' => "When I first lost someone close to me, I expected sadness, but not the exhaustion, the anger, or the moments of numbness that came without warning.\n\nIn the early weeks I avoided talking about it. Sessions with my counselor helped me see that grief does not follow a schedule, and that there was no correct way to feel.\n\nWhat helped most was small routines: a daily walk, regular meals, and writing down memories when they surfaced.\n\nI am still grieving, but the weight feels different now. I am sharing this in the hope that it helps someone who feels alone in the same place.",
                'author_role' => 'client',
                'author_name' => 'Client User',
                'status' => 'revisions_requested',
                'visibility' => 'private',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => 'Please expand the section on coping mechanisms.',
                'submitted_at' => now()->subDays(10),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Understanding Sleep Deprivation in Teens',
                'slug' => Str::slug('Understanding Sleep Deprivation in Teens'),
                'abstract' => 'How screen time and sleep hygiene affect adolescent mood.',
                'content' => "Teenagers need around eight to ten hours of sleep, yet many get far less. Changes in the body clock during adolescence make early bedtimes hard, and late-night screen use makes it worse.\n\nParents often notice irritability, poor concentration, and dropping grades before they notice the sleep problem behind them.\n\nA consistent bedtime, keeping phones out of the bedroom, and limiting caffeine in the afternoon can make a real difference within a couple of weeks.\n\nIf sleep problems persist despite these changes, it is worth speaking to a counselor or doctor, since poor sleep can be linked to low mood and anxiety.",
                'author_role' => 'parent',
                'author_name' => 'Parent User',
                'status' => 'accepted',
                'visibility' => 'public',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(14),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Building Healthy Habits Early',
                'slug' => Str::slug('Building Healthy Habits Early'),
                'abstract' => 'Foundations for lifelong emotional regulation in young children.',
                'content' => "The habits children form in their early years shape how they handle emotions for the rest of their lives. Small, repeated routines matter more than occasional big efforts.\n\nStart with predictable daily rhythms: regular meals, a calm bedtime routine, and time set aside for play. Predictability helps young children feel secure.\n\nTeach children to name their feelings. A child who can say they feel angry or sad is much better able to manage those feelings than one who cannot.\n\nPraise effort rather than results, and stay patient. Emotional regulation is a skill that grows slowly, with practice and support.",
                'author_role' => 'counselor',
                'author_name' => 'Counselor User',
                'status' => 'published',
                'visibility' => 'public',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(20),
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}