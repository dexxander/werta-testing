<?php
namespace CounselorDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO: replace with real Eloquent queries once DB/models exist
        $stats = [
            'todays_sessions'    => 0,
            'active_clients'     => 0,
            'monthly_earnings'   => 'RM 0.00',
            'published_articles' => 0,
            'pending_reports'    => 0,
        ];
        $appointments = collect(); // empty for now

        return view('counselor-dashboard::dashboard', compact('stats', 'appointments'));
    }
}