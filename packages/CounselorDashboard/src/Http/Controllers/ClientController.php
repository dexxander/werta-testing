<?php

namespace CounselorDashboard\Http\Controllers;

use CounselorDashboard\Support\SampleData;
use Illuminate\Routing\Controller;

class ClientController extends Controller
{
    public function index()
    {
        $clients = SampleData::clients();

        return view('counselor-dashboard::clients', compact('clients'));
    }
}