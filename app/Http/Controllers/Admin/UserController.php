<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount(['publications', 'comments'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
        ]);

        if ($user->id === $request->user()->id && $validated['role'] !== User::ROLE_ADMIN) {
            return back()->with('error', 'Vous ne pouvez pas retirer votre propre rôle admin.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte ici.');
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function search(Request $request)
    {
        $term = $request->string('q')->trim()->toString();

        if (empty($term)) {
            return response()->json([]);
        }

        $users = User::searchMentions($term)
            ->select('id', 'name', 'username')
            ->limit(10)
            ->get();

        return response()->json($users);
    }
}
