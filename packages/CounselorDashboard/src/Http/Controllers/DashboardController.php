<?php
namespace CounselorDashboard\Http\Controllers;

use CounselorDashboard\Support\SampleData;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $appointments = SampleData::appointments();
        $clients      = SampleData::clients();

        $stats = [
            'todays_sessions'    => $appointments->filter(fn($a) => $a->date->isToday())->count(),
            'active_clients'     => $clients->where('status', 'Active')->count(),
            'monthly_earnings'   => 'RM 0.00',
            'published_articles' => 0,
            'pending_reports'    => $clients->where('status', 'Active')->where('has_report', false)->count(),
        ];

        return view('counselor-dashboard::dashboard', compact('stats', 'appointments'));
    }
}