<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Dashboard', [
            'user' => Auth::user(),
            'profile' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
                'joined' => Auth::user()->created_at->format('M d, Y'),
            ]
        ]);
    }

    public function profile()
    {
        return Inertia::render('User/Profile', [
            'user' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = \App\Models\User::find(Auth::id());
        $user->update($request->only('name', 'email'));

        return back()->with('success', 'Profile updated successfully');
    }
}
