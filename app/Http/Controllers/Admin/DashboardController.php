<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Publication;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'publications_total' => Publication::count(),
            'publications_published' => Publication::where('status', Publication::STATUS_PUBLISHED)->count(),
            'publications_draft' => Publication::where('status', Publication::STATUS_DRAFT)->count(),
            'comments_total' => Comment::count(),
            'users_total' => User::count(),
            'reports_pending' => CommentReport::where('status', 'pending')->count(),
            'views_total' => Publication::sum('views_count'),
        ];

        $recentPublications = Publication::with('author')->latest()->limit(5)->get();
        $recentReports = CommentReport::with(['comment.user', 'comment.publication'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPublications', 'recentReports'));
    }
}
