@extends('layouts.admin')
@section('title','Users')
@section('page-title','Users')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:13px;color:#64748b;">Manage platform users and their access levels.</p>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Add User
        </a>
    </div>

    <div class="cs-card">
        <table class="cs-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Email Verified</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <div style="font-weight:600;color:#1e293b;">{{ $user->name }}</div>
                    </div>
                </td>
                <td style="color:#64748b;">{{ $user->email }}</td>
                <td style="color:#94a3b8;font-size:12.5px;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    @if($user->email_verified_at)
                    <span style="font-size:11.5px;background:#dcfce7;color:#16a34a;padding:2px 9px;border-radius:99px;font-weight:600;">Verified</span>
                    @else
                    <span style="font-size:11.5px;background:#fee2e2;color:#dc2626;padding:2px 9px;border-radius:99px;font-weight:600;">Unverified</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Remove {{ addslashes($user->name) }} from the platform?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn-red" title="Remove user">
                            <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;color:#94a3b8;padding:40px 0;">No users found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
