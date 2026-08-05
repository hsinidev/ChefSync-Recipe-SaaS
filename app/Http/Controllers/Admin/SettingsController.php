<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Scopes\TenantScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class SettingsController extends Controller
{
    public function edit(): View
    {
        $setting = Setting::firstOrCreate();
        
        $tenantId = TenantScope::getTenantId();
        $tenant = $tenantId ? Tenant::where('uuid', $tenantId)->first() : null;
        
        $user = User::first(); // Primary Admin User

        return view('admin.settings.edit', compact('setting', 'tenant', 'user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::firstOrCreate();
        
        $validated = $request->validate([
            // AI Configuration
            'preferred_ai_provider' => 'required|in:gemini,openai',
            'gemini_api_key' => 'nullable|string',
            'openai_api_key' => 'nullable|string',
            'openai_model' => 'required|string',
            
            // Tenant Settings
            'tenant_name' => 'required|string|max:255',
            'tenant_domain' => 'required|string|max:255',

            // Admin Credentials
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'nullable|string|min:8|confirmed',

            // Header & Footer Layout Settings
            'header_logo_text' => 'required|string|max:255',
            'header_subtitle' => 'required|string|max:255',
            'footer_newsletter_title' => 'required|string|max:500',
            'footer_newsletter_placeholder' => 'required|string|max:255',
            'footer_newsletter_button' => 'required|string|max:255',
            'footer_copyright' => 'required|string|max:255',
            'header_nav_links' => 'nullable|string',
            'footer_columns_json' => 'nullable|string',
        ]);

        // Decode JSON Fields
        $headerNavLinks = null;
        if (!empty($validated['header_nav_links'])) {
            $headerNavLinks = json_decode($validated['header_nav_links'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['header_nav_links' => 'Header navigation links must be a valid JSON array.'])->withInput();
            }
        }

        $footerColumnsJson = null;
        if (!empty($validated['footer_columns_json'])) {
            $footerColumnsJson = json_decode($validated['footer_columns_json'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['footer_columns_json' => 'Footer columns must be a valid JSON structure.'])->withInput();
            }
        }

        // 1. Update AI & Layout Settings
        $setting->update([
            'preferred_ai_provider' => $validated['preferred_ai_provider'],
            'gemini_api_key' => $validated['gemini_api_key'],
            'openai_api_key' => $validated['openai_api_key'],
            'openai_model' => $validated['openai_model'],
            'header_logo_text' => $validated['header_logo_text'],
            'header_subtitle' => $validated['header_subtitle'],
            'header_nav_links' => $headerNavLinks,
            'footer_newsletter_title' => $validated['footer_newsletter_title'],
            'footer_newsletter_placeholder' => $validated['footer_newsletter_placeholder'],
            'footer_newsletter_button' => $validated['footer_newsletter_button'],
            'footer_copyright' => $validated['footer_copyright'],
            'footer_columns_json' => $footerColumnsJson,
        ]);

        // 2. Update Tenant details
        $tenantId = TenantScope::getTenantId();
        if ($tenantId) {
            $tenant = Tenant::where('uuid', $tenantId)->first();
            if ($tenant) {
                // Clear cache on domain update
                $oldDomain = $tenant->domain;
                $tenant->update([
                    'name' => $validated['tenant_name'],
                    'domain' => $validated['tenant_domain'],
                ]);
                \Illuminate\Support\Facades\Cache::forget("tenant:host:{$oldDomain}");
                \Illuminate\Support\Facades\Cache::forget("tenant:host:{$validated['tenant_domain']}");
            }
        }

        // 3. Update Admin Credentials
        $user = User::first();
        if ($user) {
            $user->name = $validated['admin_name'];
            $user->email = $validated['admin_email'];
            if (!empty($validated['admin_password'])) {
                $user->password = Hash::make($validated['admin_password']);
            }
            $user->save();
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'System settings, navigation layouts, and admin credentials updated successfully.');
    }
}
