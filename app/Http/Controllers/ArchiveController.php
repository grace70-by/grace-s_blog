<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    public function index(): View
    {
        $driver = \Illuminate\Support\Facades\DB::getDriverName();

        if ($driver === 'sqlite') {
            $selectRaw = "strftime('%Y', published_at) as year, strftime('%m', published_at) as month, COUNT(*) as total";
        } else {
            $selectRaw = 'YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as total';
        }

        $archives = \App\Models\Publication::published()
            ->selectRaw($selectRaw)
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('archives.index', compact('archives'));
    }

    public function show(int $year, ?int $month = null): View
    {
        $publications = \App\Models\Publication::published()
            ->withLikeMeta()
            ->with(['author', 'blocks'])
            ->forArchive($year, $month)
            ->latest('published_at')
            ->paginate(12);

        return view('archives.show', [
            'year' => $year,
            'month' => $month,
            'publications' => $publications,
        ]);
    }
}
