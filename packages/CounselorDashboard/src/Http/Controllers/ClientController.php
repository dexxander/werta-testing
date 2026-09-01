<?php

namespace CounselorDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class ClientController extends Controller
{
    public function index()
    {
        // TODO: pull from a real Client model once DB exists.
        // Each $client is expected to expose: display_name, subtitle,
        // last_session_date, primary_focus, status ('Active'|'Discharged'), has_report (bool)
        $clients = collect();

        return view('counselor-dashboard::clients', compact('clients'));
    }
}