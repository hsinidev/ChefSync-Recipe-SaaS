<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

final class TenantController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::withCount('recipes')->latest()->paginate(15);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'domain'       => 'required|string|max:255|unique:tenants,domain',
            'billing_plan' => 'required|in:free,pro,enterprise',
        ]);

        Tenant::create([
            'uuid'         => Str::uuid()->getBytes(),
            'name'         => $validated['name'],
            'domain'       => $validated['domain'],
            'billing_plan' => $validated['billing_plan'],
        ]);

        return redirect()->route('admin.tenants.index')
            ->with('success', "Tenant \"{$validated['name']}\" provisioned.");
    }

    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'domain'       => "required|string|max:255|unique:tenants,domain,{$tenant->id}",
            'billing_plan' => 'required|in:free,pro,enterprise',
        ]);

        $tenant->update($validated);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant updated.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant deprovisioned.');
    }
}
