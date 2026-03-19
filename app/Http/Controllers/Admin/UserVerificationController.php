<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserVerificationController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'verified' => ['required','boolean'],
        ]);

        $user->verified = (bool) $data['verified'];
        $user->save();

        return back()->with('success', 'Verificatiestatus bijgewerkt.');
    }
}
