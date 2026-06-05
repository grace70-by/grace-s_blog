<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = $request->string('q')->trim()->toString();

        if ($query === '') {
            $publications = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
            $publications->withPath($request->url())->withQueryString();
        } else {
            $publications = Publication::published()
                ->withLikeMeta()
                ->with(['author', 'blocks'])
                ->search($query)
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('search.index', [
            'query' => $query,
            'publications' => $publications,
        ]);
    }
}
