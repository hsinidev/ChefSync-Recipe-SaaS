@extends('layouts.admin')
@section('title','Edit Tenant')
@section('page-title','Edit Tenant')

@section('content')
<div style="max-width:540px;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.tenants.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#64748b;text-decoration:none;">
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
        <h2 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Edit Tenant</h2>
        <p style="font-size:13px;color:#64748b;margin-bottom:24px;">Modify settings for <strong>{{ $tenant->name }}</strong>.</p>

        <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
            @csrf @method('PUT')
            <div style="display:flex;flex-direction:column;gap:18px;">
                <div>
                    <label class="cs-label">Tenant Name *</label>
                    <input type="text" name="name" class="cs-input" value="{{ old('name', $tenant->name) }}" required>
                </div>
                <div>
                    <label class="cs-label">Domain *</label>
                    <input type="text" name="domain" class="cs-input" value="{{ old('domain', $tenant->domain) }}" required>
                </div>
                <div>
                    <label class="cs-label">Billing Plan *</label>
                    <select name="billing_plan" class="cs-input" required>
                        @foreach(['free','pro','enterprise'] as $p)
                        <option value="{{ $p }}" {{ old('billing_plan',$tenant->billing_plan)===$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px;">
                    <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}"
                          onsubmit="return confirm('Permanently deprovision {{ addslashes($tenant->name) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">Deprovision</button>
                    </form>
                    <div style="display:flex;gap:10px;">
                        <a href="{{ route('admin.tenants.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
