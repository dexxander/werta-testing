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
                'total_students' => 0,
                'courses_available' => 0,
                'certificates_issued' => 0,
            ],
            'categories' => [],
            'continue_course' => null,
            'featured_courses' => [],
            'learning_paths' => [],
            'testimonials' => [],
            'certificates' => [],
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
