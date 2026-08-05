<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total_recipes'    => Recipe::withoutGlobalScopes()->count(),
            'published'        => Recipe::withoutGlobalScopes()->where('status', 'published')->count(),
            'drafts'           => Recipe::withoutGlobalScopes()->where('status', 'draft')->count(),
            'in_review'        => Recipe::withoutGlobalScopes()->where('status', 'review')->count(),
            'total_tenants'    => Tenant::count(),
            'total_users'      => User::count(),
        ];

        $recentRecipes = Recipe::withoutGlobalScopes()
            ->with('author')
            ->latest()
            ->take(6)
            ->get();

        $tenants = Tenant::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentRecipes', 'tenants'));
    }
}
