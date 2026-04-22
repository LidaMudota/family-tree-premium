<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        return view('dashboard.index', [
            'treesCount' => $user->trees()->count(),
            'peopleCount' => $user->trees()->withCount('people')->get()->sum('people_count'),
        ]);
    }
}
