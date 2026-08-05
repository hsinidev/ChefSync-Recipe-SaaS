@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div style="display:flex;flex-direction:column;gap:28px;">

{{-- ── STATS ROW ─────────────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:16px;">
@php
$cards = [
  ['label'=>'Total Recipes',  'value'=>$stats['total_recipes'],  'color'=>'#2563eb','bg'=>'#eff6ff','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
  ['label'=>'Published',      'value'=>$stats['published'],      'color'=>'#16a34a','bg'=>'#dcfce7','icon'=>'M5 13l4 4L19 7'],
  ['label'=>'In Review',      'value'=>$stats['in_review'],      'color'=>'#b45309','bg'=>'#fef9c3','icon'=>'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
  ['label'=>'Drafts',         'value'=>$stats['drafts'],         'color'=>'#475569','bg'=>'#f1f5f9','icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
  ['label'=>'Tenants',        'value'=>$stats['total_tenants'],  'color'=>'#7c3aed','bg'=>'#f3e8ff','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
  ['label'=>'Users',          'value'=>$stats['total_users'],    'color'=>'#0369a1','bg'=>'#e0f2fe','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
];
@endphp
@foreach($cards as $c)
<div class="cs-stat" style="cursor:default;">
    <div style="width:38px;height:38px;border-radius:10px;background:{{ $c['bg'] }};display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
        <svg style="width:18px;height:18px;color:{{ $c['color'] }}" fill="none" viewBox="0 0 24 24" stroke="{{ $c['color'] }}" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/>
        </svg>
    </div>
    <div style="font-size:28px;font-weight:800;color:#0f172a;line-height:1;">{{ $c['value'] }}</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:4px;font-weight:500;">{{ $c['label'] }}</div>
</div>
@endforeach
</div>

{{-- ── TWO COLUMN LAYOUT ─────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;">

    {{-- Recent Recipes --}}
    <div class="cs-card">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #f1f5f9;">
            <h2 style="font-size:14px;font-weight:700;color:#0f172a;">Recent Recipes</h2>
            <a href="{{ route('admin.recipes.index') }}"
               style="font-size:12px;font-weight:600;color:#2563eb;text-decoration:none;padding:5px 12px;border-radius:8px;background:#eff6ff;transition:all .15s;"
               onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
               View All →
            </a>
        </div>
        <table class="cs-table">
            <thead>
                <tr>
                    <th>Recipe</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Author</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentRecipes as $recipe)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($recipe->title,0,1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600;color:#1e293b;">{{ $recipe->title }}</div>
                            @if($recipe->excerpt)
                            <div style="font-size:11.5px;color:#94a3b8;margin-top:1px;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $recipe->excerpt }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-{{ $recipe->status }}">{{ ucfirst($recipe->status) }}</span></td>
                <td style="color:#64748b;">{{ $recipe->prep_time_minutes + $recipe->cook_time_minutes }}min</td>
                <td style="color:#64748b;">{{ $recipe->author?->name ?? '—' }}</td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a href="{{ url('/recipes/'.$recipe->slug) }}" target="_blank" class="icon-btn icon-btn-slate" title="View on site">
                            <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <a href="{{ route('admin.recipes.edit', $recipe) }}" class="icon-btn icon-btn-blue" title="Edit">
                            <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:32px;">No recipes yet. <a href="{{ route('admin.recipes.create') }}" style="color:#2563eb;">Create one →</a></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right Column --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Quick Actions --}}
        <div class="cs-card" style="padding:20px;">
            <h2 style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:14px;">Quick Actions</h2>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('admin.recipes.create') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;text-decoration:none;transition:all .15s;"
                   onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    <div style="width:32px;height:32px;border-radius:8px;background:#2563eb;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:15px;height:15px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#1e40af;">New Recipe</div>
                        <div style="font-size:11px;color:#60a5fa;">Create & publish content</div>
                    </div>
                </a>
                <a href="{{ route('admin.tenants.create') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;background:#f3e8ff;border:1px solid #ddd6fe;text-decoration:none;transition:all .15s;"
                   onmouseover="this.style.background='#ede9fe'" onmouseout="this.style.background='#f3e8ff'">
                    <div style="width:32px;height:32px;border-radius:8px;background:#7c3aed;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#5b21b6;">New Tenant</div>
                        <div style="font-size:11px;color:#a78bfa;">Provision a blog</div>
                    </div>
                </a>
                <a href="{{ route('admin.users.create') }}"
                   style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;background:#e0f2fe;border:1px solid #bae6fd;text-decoration:none;transition:all .15s;"
                   onmouseover="this.style.background='#bae6fd'" onmouseout="this.style.background='#e0f2fe'">
                    <div style="width:32px;height:32px;border-radius:8px;background:#0369a1;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#075985;">Add User</div>
                        <div style="font-size:11px;color:#38bdf8;">Invite a team member</div>
                    </div>
                </a>
                <a href="{{ route('admin.recipes.index') }}?status=review"
                   style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;background:#fef9c3;border:1px solid #fde68a;text-decoration:none;transition:all .15s;"
                   onmouseover="this.style.background='#fef08a'" onmouseout="this.style.background='#fef9c3'">
                    <div style="width:32px;height:32px;border-radius:8px;background:#b45309;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#92400e;">Review Queue</div>
                        <div style="font-size:11px;color:#f59e0b;">{{ $stats['in_review'] }} awaiting approval</div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Tenants Overview --}}
        <div class="cs-card" style="padding:20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <h2 style="font-size:13px;font-weight:700;color:#0f172a;">Active Tenants</h2>
                <a href="{{ route('admin.tenants.index') }}" style="font-size:11.5px;color:#2563eb;text-decoration:none;font-weight:600;">Manage →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @forelse($tenants as $t)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:9px;background:#f8fafc;border:1px solid #f1f5f9;">
                    <div style="display:flex;align-items:center;gap:9px;">
                        <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($t->name,0,1)) }}
                        </div>
                        <div>
                            <div style="font-size:12.5px;font-weight:600;color:#1e293b;">{{ $t->name }}</div>
                            <div style="font-size:11px;color:#94a3b8;">{{ $t->domain }}</div>
                        </div>
                    </div>
                    <span class="badge badge-{{ $t->billing_plan }}">{{ $t->billing_plan }}</span>
                </div>
                @empty
                <p style="font-size:12px;color:#94a3b8;text-align:center;padding:16px 0;">No tenants yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

</div>
@endsection
