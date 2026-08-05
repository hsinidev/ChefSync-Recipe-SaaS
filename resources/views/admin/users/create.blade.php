@extends('layouts.admin')
@section('title','Add User')
@section('page-title','Add User')

@section('content')
<div style="max-width:480px;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.users.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#64748b;text-decoration:none;">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Users
        </a>
    </div>

    @if($errors->any())
    <div class="cs-flash-error" style="margin-bottom:16px;">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="cs-card" style="padding:28px;">
        <h2 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Add Team Member</h2>
        <p style="font-size:13px;color:#64748b;margin-bottom:24px;">Create a new platform user with access to the admin panel.</p>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div style="display:flex;flex-direction:column;gap:18px;">
                <div>
                    <label class="cs-label">Full Name *</label>
                    <input type="text" name="name" class="cs-input" value="{{ old('name') }}" required placeholder="Jane Smith">
                </div>
                <div>
                    <label class="cs-label">Email Address *</label>
                    <input type="email" name="email" class="cs-input" value="{{ old('email') }}" required placeholder="jane@example.com">
                </div>
                <div>
                    <label class="cs-label">Password *</label>
                    <input type="password" name="password" class="cs-input" required placeholder="Min 8 characters">
                </div>
                <div>
                    <label class="cs-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="cs-input" required placeholder="Repeat password">
                </div>
                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px;">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
