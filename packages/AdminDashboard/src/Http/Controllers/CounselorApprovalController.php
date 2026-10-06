<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Models\Counselor;
use Illuminate\Http\Request;

class CounselorApprovalController
{
    public function index($role)
    {
        return view('admindashboard::counselor-approvals', [
            'role' => $role,
            'counselors' => Counselor::orderByDesc('created_at')->get(),
        ]);
    }

    public function approve(Request $request, Counselor $counselor)
    {
        $counselor->update(['status' => 'approved']);
        return back()->with('success', $counselor->name . ' has been approved.');
    }

    public function reject(Request $request, Counselor $counselor)
    {
        $counselor->update(['status' => 'rejected']);
        return back()->with('success', $counselor->name . ' has been rejected.');
    }
}