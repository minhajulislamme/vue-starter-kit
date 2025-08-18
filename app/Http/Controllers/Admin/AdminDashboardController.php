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

    public function users(Request $request)
    {
        $query = \App\Models\User::query();

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Order by creation date (newest first)
        $query->orderBy('created_at', 'desc');

        return Inertia::render('Admin/Users', [
            'users' => $query->paginate(15)->withQueryString(),
            'filters' => [
                'role' => $request->role,
                'search' => $request->search,
            ]
        ]);
    }

    public function updateUserRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|in:admin,manager,user'
        ]);

        $user = \App\Models\User::findOrFail($userId);

        // Prevent users from changing their own role
        if ($user->id === auth()->id()) {
            return back()->withErrors(['role' => 'You cannot change your own role.']);
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully');
    }

    public function createUser()
    {
        return Inertia::render('Admin/CreateUser');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,user',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }

    public function editUser($userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        return Inertia::render('Admin/EditUser', [
            'user' => $user
        ]);
    }

    public function updateUser(Request $request, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,user',
        ]);

        // Prevent users from changing their own role
        if ($user->id === auth()->id() && $user->role !== $request->role) {
            return back()->withErrors(['role' => 'You cannot change your own role.']);
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function deleteUser($userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }
}
