@extends('layouts.admin')
@section('title','Tenants')
@section('page-title','Tenants')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:13px;color:#64748b;">Manage multi-tenant culinary blogs on this platform.</p>
        <a href="{{ route('admin.tenants.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Provision Tenant
        </a>
    </div>

    <div class="cs-card">
        <table class="cs-table">
            <thead>
                <tr>
                    <th>Tenant</th>
                    <th>Domain</th>
                    <th>Plan</th>
                    <th>Recipes</th>
                    <th>DB Mode</th>
                    <th>Since</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($tenants as $tenant)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($tenant->name,0,1)) }}
                        </div>
                        <div style="font-weight:600;color:#1e293b;">{{ $tenant->name }}</div>
                    </div>
                </td>
                <td>
                    <a href="http://{{ $tenant->domain }}" target="_blank"
                       style="font-size:12.5px;color:#2563eb;text-decoration:none;font-weight:500;">
                        {{ $tenant->domain }}
                        <svg style="width:10px;height:10px;display:inline;margin-left:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </td>
                <td><span class="badge badge-{{ $tenant->billing_plan }}">{{ ucfirst($tenant->billing_plan) }}</span></td>
                <td style="color:#64748b;font-weight:600;">{{ $tenant->recipes_count }}</td>
                <td>
                    @if($tenant->uses_isolated_db)
                    <span style="font-size:11.5px;background:#dcfce7;color:#16a34a;padding:2px 9px;border-radius:99px;font-weight:600;">Dedicated DB</span>
                    @else
                    <span style="font-size:11.5px;background:#f1f5f9;color:#64748b;padding:2px 9px;border-radius:99px;font-weight:600;">Shared DB</span>
                    @endif
                </td>
                <td style="color:#94a3b8;font-size:12.5px;">{{ $tenant->created_at->format('d M Y') }}</td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="icon-btn icon-btn-blue" title="Edit">
                            <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}"
                              onsubmit="return confirm('Deprovision {{ addslashes($tenant->name) }}? All data will be lost.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-red" title="Deprovision">
                                <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:#94a3b8;padding:40px 0;">
                    No tenants provisioned.
                    <a href="{{ route('admin.tenants.create') }}" style="color:#2563eb;">Provision one →</a>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        @if($tenants->hasPages())
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">{{ $tenants->links() }}</div>
        @endif
    </div>
</div>
@endsection
