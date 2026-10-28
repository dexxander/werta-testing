<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Models\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/** Verify and manage professional reviewers. */
class ReviewerManagementController extends Controller
{
    public function index(string $role, Request $request)
    {
        $tab = in_array($request->query('tab'), ['pending', 'approved', 'other'], true) ? $request->query('tab') : 'pending';

        $all = Reviewer::withCount([
            'reviews as assigned_count' => fn ($q) => $q->where('status', 'assigned'),
            'reviews as completed_count' => fn ($q) => $q->where('status', 'completed'),
        ])->orderBy('name')->get();

        $counts = [
            'pending'  => $all->where('status', 'pending')->count(),
            'approved' => $all->where('status', 'approved')->count(),
            'other'    => $all->whereIn('status', ['rejected', 'suspended'])->count(),
        ];
        $reviewers = match ($tab) {
            'approved' => $all->where('status', 'approved'),
            'other'    => $all->whereIn('status', ['rejected', 'suspended']),
            default    => $all->where('status', 'pending'),
        };

        return view('admindashboard::reviewers', compact('role', 'reviewers', 'counts', 'tab'));
    }

    public function approve(Reviewer $reviewer)
    {
        $reviewer->update(['status' => 'approved', 'approved_at' => $reviewer->approved_at ?? now()]);

        return back()->with('success', __(':name is now a verified reviewer.', ['name' => $reviewer->name]));
    }

    public function reject(Reviewer $reviewer)
    {
        $reviewer->update(['status' => 'rejected']);

        return back()->with('success', __(':name\'s application was rejected.', ['name' => $reviewer->name]));
    }

    public function suspend(Reviewer $reviewer)
    {
        $reviewer->update(['status' => 'suspended']);

        return back()->with('success', __(':name was suspended and can no longer review.', ['name' => $reviewer->name]));
    }
}
