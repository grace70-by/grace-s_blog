<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Publication;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $publications = Publication::published()->latest('published_at')->get(['slug', 'updated_at']);
        $pages = Page::all(['slug', 'updated_at']);

        return response()
            ->view('sitemap.index', compact('publications', 'pages'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
