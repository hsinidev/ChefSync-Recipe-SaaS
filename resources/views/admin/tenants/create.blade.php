@extends('layouts.admin')
@section('title','Provision Tenant')
@section('page-title','Provision Tenant')

@section('content')
<div style="max-width:540px;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.tenants.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#64748b;text-decoration:none;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#64748b'">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Tenants
        </a>
    </div>

    @if($errors->any())
    <div class="cs-flash-error" style="margin-bottom:16px;">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="cs-card" style="padding:28px;">
        <h2 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Provision New Tenant</h2>
        <p style="font-size:13px;color:#64748b;margin-bottom:24px;">This will create a new isolated culinary blog on the platform.</p>

        <form method="POST" action="{{ route('admin.tenants.store') }}">
            @csrf
            <div style="display:flex;flex-direction:column;gap:18px;">

                <div>
                    <label class="cs-label">Tenant Name *</label>
                    <input type="text" name="name" class="cs-input" value="{{ old('name') }}" required placeholder="e.g. Mario's Kitchen Blog">
                </div>

                <div>
                    <label class="cs-label">Domain *</label>
                    <input type="text" name="domain" class="cs-input" value="{{ old('domain') }}" required placeholder="e.g. mario.chefsync.app">
                    <p style="font-size:11.5px;color:#94a3b8;margin-top:5px;">The domain this tenant's blog will be served on.</p>
                </div>

                <div>
                    <label class="cs-label">Billing Plan *</label>
                    <select name="billing_plan" class="cs-input" required>
                        <option value="free" {{ old('billing_plan','free')==='free'?'selected':'' }}>Free</option>
                        <option value="pro" {{ old('billing_plan')==='pro'?'selected':'' }}>Pro</option>
                        <option value="enterprise" {{ old('billing_plan')==='enterprise'?'selected':'' }}>Enterprise</option>
                    </select>
                </div>

                <div style="padding:14px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;">
                    <p style="font-size:12.5px;font-weight:600;color:#1d4ed8;margin-bottom:4px;">ℹ️ Database Isolation</p>
                    <p style="font-size:12px;color:#3b82f6;">Free and Pro tenants use shared database isolation via Global Query Scopes. Enterprise tenants can have dedicated database configuration added post-provisioning.</p>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px;">
                    <a href="{{ route('admin.tenants.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Provision Tenant</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
