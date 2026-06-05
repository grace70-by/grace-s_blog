<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $media = MediaFile::with('user')->latest()->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240'],
        ]);

        foreach ($request->file('files', []) as $file) {
            $path = $file->store('media', 'public');

            MediaFile::create([
                'user_id' => $request->user()->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return back()->with('success', 'Fichier(s) téléversé(s).');
    }

    public function destroy(MediaFile $medium): RedirectResponse
    {
        Storage::disk('public')->delete($medium->path);
        $medium->delete();

        return back()->with('success', 'Fichier supprimé.');
    }
}
