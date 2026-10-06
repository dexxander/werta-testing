<?php

namespace CounselorDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Submissions\Models\Submission;

class ArticleController extends Controller
{
    public function index()
    {
        $counselorName = session('counselor_name', 'Counselor');

        $submissions = Schema::hasTable('submissions')
            ? Submission::where('author_role', 'counselor')
                ->where('author_name', $counselorName)
                ->latest()
                ->get()
            : collect();

        $articles = $submissions->map(function ($submission) {
            $excerpt = !empty($submission->abstract)
                ? $submission->abstract
                : Str::limit(strip_tags($submission->content ?? ''), 140);

            $date = $submission->submitted_at ?? $submission->created_at;
            $dateLabel = $date ? $date->format('j M Y') : '';

            $statusMeta = Submission::STATUS_LABELS[$submission->status] ?? [
                'label' => $submission->status,
                'class' => 'bg-gray-100 text-gray-600',
            ];

            return (object) [
                'id'           => $submission->id,
                'title'        => $submission->title,
                'excerpt'      => $excerpt,
                'date_label'   => $dateLabel,
                'visibility'   => ucfirst($submission->visibility ?? 'public'),
                'status_label' => $statusMeta['label'],
                'status_class' => $statusMeta['class'],
            ];
        });

        return view('counselor-dashboard::articles.index', compact('articles'));
    }

    public function create()
    {
        return redirect()->route('submissions.create');
    }
}