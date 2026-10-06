<?php

namespace CounselorDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        // TODO: pull the logged-in counselor's record once DB/auth exist
        $counselor = null;

        return view('counselor-dashboard::profile', compact('counselor'));
    }
}