<?php

namespace Elearning\Http\Controllers;

use Illuminate\Routing\Controller;

class ElearningController extends Controller
{
    public function index()
    {
        return view('elearning::index', $this->sharedData());
    }

    public function overview()
    {
        return view('elearning::pages.overview', $this->sharedData());
    }

    public function courses()
    {
        return view('elearning::pages.courses', $this->sharedData());
    }

    public function paths()
    {
        return view('elearning::pages.paths', $this->sharedData());
    }

    public function dashboard()
    {
        return view('elearning::pages.dashboard', $this->sharedData());
    }

    public function myCourses()
    {
        return view('elearning::pages.my-courses', $this->sharedData());
    }

    public function courseContent($id)
    {
        return view('elearning::pages.course-content', $this->sharedData() + ['course_id' => $id]);
    }

    public function coursePreview($id)
    {
        return view('elearning::pages.course-preview', $this->sharedData() + ['course_id' => $id]);
    }

    public function categoryCourses($slug)
    {
        return view('elearning::pages.category-courses', $this->sharedData() + ['category_slug' => $slug]);
    }

    public function checkout()
    {
        return view('elearning::pages.checkout', $this->sharedData());
    }

    public function pricing()
    {
        return view('elearning::pages.pricing', $this->sharedData());
    }

    public function faq()
    {
        return view('elearning::pages.faq', $this->sharedData());
    }

    /**
     * Shared data for every elearning page. The hero, tab-bar, and final-cta
     * all live in the shared layout and need this on every route, so it's
     * simplest to always pass the full set rather than slicing it per page.
     *
     * Currently utilizing empty arrays to represent data-driven empty states
     * as requested. In the future, these can be populated from the database models.
     */
    private function sharedData(): array
    {
        return [
            'statistics' => [
                'total_students' => 8540,
                'courses_available' => 48,
                'certificates_issued' => 3260,
            ],
            'categories' => [
                ['name' => 'Web Development', 'icon' => 'code-slash', 'course_count' => 12, 'url' => route('elearning.category', ['slug' => 'web-development'])],
                ['name' => 'Data & Analytics', 'icon' => 'bar-chart-line', 'course_count' => 8, 'url' => route('elearning.category', ['slug' => 'data-analytics'])],
                ['name' => 'Product Design', 'icon' => 'palette2', 'course_count' => 7, 'url' => route('elearning.category', ['slug' => 'product-design'])],
                ['name' => 'Digital Marketing', 'icon' => 'megaphone', 'course_count' => 6, 'url' => route('elearning.category', ['slug' => 'digital-marketing'])],
                ['name' => 'Business Skills', 'icon' => 'briefcase', 'course_count' => 5, 'url' => route('elearning.category', ['slug' => 'business-skills'])],
                ['name' => 'Cybersecurity', 'icon' => 'shield-lock', 'course_count' => 5, 'url' => route('elearning.category', ['slug' => 'cybersecurity'])],
            ],
            'continue_course' => [
                'title' => 'Advanced React & Next.js',
                'instructor_name' => 'Elena Ruiz',
                'progress_percent' => 68,
                'next_lesson_title' => 'Building resilient data-fetching flows',
                'resume_url' => route('elearning.course-content', ['id' => 5]),
            ],
            'featured_courses' => [
                ['id' => 1, 'title' => 'Complete Web Development Bootcamp', 'instructor' => 'James Carter', 'difficulty' => 'Beginner', 'rating' => '4.8', 'duration' => '32h', 'students' => 4210, 'icon' => 'code-slash', 'topics' => ['HTML & CSS foundations', 'Responsive layouts', 'JavaScript essentials', 'React components', 'Portfolio project']],
                ['id' => 2, 'title' => 'Data Science & Machine Learning', 'instructor' => 'Priya Nair', 'difficulty' => 'Intermediate', 'rating' => '4.9', 'duration' => '28h', 'students' => 3185, 'icon' => 'bar-chart-line', 'topics' => ['Python analysis', 'Statistics', 'Data cleaning', 'Model training', 'Model evaluation']],
                ['id' => 3, 'title' => 'UI/UX Design Fundamentals', 'instructor' => 'Sarah Chen', 'difficulty' => 'Beginner', 'rating' => '4.7', 'duration' => '18h', 'students' => 2674, 'icon' => 'palette2', 'topics' => ['User research', 'Information architecture', 'Wireframing', 'Figma prototyping', 'Usability testing']],
                ['id' => 4, 'title' => 'Digital Marketing Mastery', 'instructor' => 'Ahmad Faiz', 'difficulty' => 'Beginner', 'rating' => '4.6', 'duration' => '15h', 'students' => 1932, 'icon' => 'megaphone', 'topics' => ['Marketing strategy', 'SEO fundamentals', 'Content planning', 'Campaigns', 'Analytics']],
                ['id' => 5, 'title' => 'Advanced React & Next.js', 'instructor' => 'Elena Ruiz', 'difficulty' => 'Advanced', 'rating' => '4.9', 'duration' => '24h', 'students' => 1547, 'icon' => 'layers', 'topics' => ['React architecture', 'Next.js routing', 'Server components', 'Data fetching', 'Deployment']],
                ['id' => 6, 'title' => 'Cybersecurity Essentials', 'instructor' => 'Marcus Lee', 'difficulty' => 'Intermediate', 'rating' => '4.8', 'duration' => '20h', 'students' => 2098, 'icon' => 'shield-lock', 'topics' => ['Network security', 'Threat detection', 'Identity & access', 'Incident response', 'Security practices']],
            ],
            'learning_paths' => [
                ['name' => 'Full Stack Developer', 'url' => route('elearning.category', ['slug' => 'web-development']), 'courses' => ['Programming Foundations', 'HTML & CSS', 'JavaScript', 'React', 'Backend APIs', 'Capstone project']],
                ['name' => 'Data Analyst', 'url' => route('elearning.category', ['slug' => 'data-analytics']), 'courses' => ['Statistics', 'Excel for Analysts', 'SQL', 'Data Visualisation', 'Python', 'Insight presentation']],
                ['name' => 'Product Designer', 'url' => route('elearning.category', ['slug' => 'product-design']), 'courses' => ['Design Thinking', 'User Research', 'Wireframing', 'Figma', 'Design Systems', 'Portfolio case study']],
                ['name' => 'Cybersecurity Specialist', 'url' => route('elearning.category', ['slug' => 'cybersecurity']), 'courses' => ['Networking', 'Security Fundamentals', 'Ethical Hacking', 'Threat Analysis', 'Incident Response']],
            ],
            'testimonials' => [
                ['name' => 'Aina Rahman', 'role' => 'Junior Product Designer', 'rating' => 5, 'content' => 'The project briefs helped me build a portfolio I could confidently take into interviews.'],
                ['name' => 'Daniel Wong', 'role' => 'Frontend Developer', 'rating' => 5, 'content' => 'Every lesson ends with something practical. I went from tutorials to shipping a real app.'],
                ['name' => 'Nur Izzati', 'role' => 'Data Analyst', 'rating' => 5, 'content' => 'The learning path made a big subject feel manageable and gave me a clear next step every week.'],
            ],
            'certificates' => [
                ['title' => 'Course Completion Certificate', 'icon' => 'patch-check', 'description' => 'Earn a verified certificate when you complete the lessons, exercises, and final assessment.'],
                ['title' => 'Career Track Badge', 'icon' => 'award', 'description' => 'Complete a full learning path to show a broader, job-ready skill set.'],
                ['title' => 'Project Portfolio', 'icon' => 'kanban', 'description' => 'Keep your completed projects in one shareable portfolio for applications and reviews.'],
                ['title' => 'Community Contributor', 'icon' => 'chat-heart', 'description' => 'Get recognised for meaningful peer feedback and helpful discussion contributions.'],
            ],
            'dashboard_courses' => [
                ['title' => 'Advanced React & Next.js', 'icon' => 'layers', 'progress' => 68, 'next_lesson' => 'Building resilient data-fetching flows'],
                ['title' => 'UI/UX Design Fundamentals', 'icon' => 'palette2', 'progress' => 42, 'next_lesson' => 'Design systems in practice'],
                ['title' => 'Data Visualisation with Python', 'icon' => 'bar-chart-line', 'progress' => 15, 'next_lesson' => 'Your first Matplotlib dashboard'],
            ],
            'dashboard_overall_progress' => 54,
            'dashboard_goals' => [
                ['label' => 'Complete 3 lessons', 'done' => true],
                ['label' => 'Finish weekly quiz', 'done' => true],
                ['label' => 'Submit project draft', 'done' => false],
            ],
            'dashboard_streak_days' => 12,
            'dashboard_streak_active_days' => 5,
            'pricing_plans' => [
                [
                    'name' => 'Free',
                    'price' => 'RM 0',
                    'period' => 'forever',
                    'features' => [
                        'Access to introductory courses',
                        'Basic community support',
                        'Limited exercises'
                    ]
                ],
                [
                    'name' => 'Pro',
                    'price' => 'RM 49',
                    'period' => 'per month',
                    'features' => [
                        'Access to all intermediate courses',
                        'Priority community support',
                        'Certificates of completion',
                        'Full exercise access'
                    ],
                    'recommended' => true
                ],
                [
                    'name' => 'Premium',
                    'price' => 'RM 99',
                    'period' => 'per month',
                    'features' => [
                        'Access to all advanced courses',
                        '1-on-1 mentorship sessions',
                        'Career guidance & portfolio review',
                        'Verified Professional Certificates'
                    ]
                ]
            ],
            'faqs' => [
                [
                    'question' => 'How do I access the courses?',
                    'answer' => 'Once enrolled, you can access your courses through the Student Dashboard anytime, anywhere.'
                ],
                [
                    'question' => 'Are the certificates recognized?',
                    'answer' => 'Yes, our verified certificates are recognized by industry partners.'
                ],
                [
                    'question' => 'Can I learn at my own pace?',
                    'answer' => 'Absolutely. All our MOOCs are self-paced, allowing you to learn whenever it fits your schedule.'
                ],
                [
                    'question' => 'What is the refund policy?',
                    'answer' => 'We offer a 7-day money-back guarantee for all Pro and Premium subscriptions if you are not satisfied.'
                ]
            ]
        ];
    }
}
