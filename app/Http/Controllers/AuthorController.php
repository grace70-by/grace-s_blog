<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show(User $user): View
    {
        $publications = $user->publications()
            ->published()
            ->withLikeMeta()
            ->with(['blocks'])
            ->latest('published_at')
            ->paginate(12);

        return view('authors.show', compact('user', 'publications'));
    }
}
