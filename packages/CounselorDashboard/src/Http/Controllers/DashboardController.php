<?php
namespace CounselorDashboard\Http\Controllers;

use CounselorDashboard\Support\SampleData;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Submissions\Models\Submission;

class DashboardController extends Controller
{
    public function index()
    {
        $appointments = SampleData::appointments();
        $clients      = SampleData::clients();

        $publishedArticles = 0;
        if (Schema::hasTable('submissions')) {
            $counselorName = session('counselor_name', 'Counselor');
            $publishedArticles = Submission::where('author_role', 'counselor')
                ->where('author_name', $counselorName)
                ->where('status', 'published')
                ->count();
        }

        $stats = [
            'todays_sessions'    => $appointments->filter(fn($a) => $a->date->isToday())->count(),
            'active_clients'     => $clients->where('status', 'Active')->count(),
            'monthly_earnings'   => 'RM 0.00',
            'published_articles' => $publishedArticles,
            'pending_reports'    => $clients->where('status', 'Active')->where('has_report', false)->count(),
        ];

        return view('counselor-dashboard::dashboard', compact('stats', 'appointments'));
    }
}