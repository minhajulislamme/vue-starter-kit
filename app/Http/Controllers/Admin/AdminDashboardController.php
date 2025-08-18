<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'users' => \App\Models\User::all(),
            'stats' => [
                'total_users' => \App\Models\User::count(),
                'admins' => \App\Models\User::where('role', 'admin')->count(),
                'managers' => \App\Models\User::where('role', 'manager')->count(),
                'users' => \App\Models\User::where('role', 'user')->count(),
            ]
        ]);
    }

    public function users()
    {
        return Inertia::render('Admin/Users', [
            'users' => \App\Models\User::paginate(15)
        ]);
    }

    public function updateUserRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|in:admin,manager,user'
        ]);

        $user = \App\Models\User::findOrFail($userId);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully');
    }
}
