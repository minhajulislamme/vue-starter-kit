<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Manager/Dashboard', [
            'users' => \App\Models\User::where('role', 'user')->get(),
            'stats' => [
                'total_users' => \App\Models\User::where('role', 'user')->count(),
                'recent_users' => \App\Models\User::where('role', 'user')
                    ->latest()
                    ->take(5)
                    ->get(),
            ]
        ]);
    }

    public function users()
    {
        return Inertia::render('Manager/Users', [
            'users' => \App\Models\User::where('role', 'user')->paginate(15)
        ]);
    }

    public function reports()
    {
        return Inertia::render('Manager/Reports');
    }
}
