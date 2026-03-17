<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ModerationUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->orderByDesc('id')
            ->paginate(15);

        return view('moderation.users.index', compact('users'));
    }

    public function updateVerified(Request $request, User $user)
    {
        $data = $request->validate([
            'verified' => ['required','boolean'],
        ]);

        $user->verified = (bool) $data['verified'];
        $user->save();

        if ($request->wantsJson()) {
            return $user;
        }

        return redirect()
            ->back()
            ->with('success', "Account '{$user->name}' is nu " . ($user->verified ? 'verified' : 'not verified') . '.');
    }
}
