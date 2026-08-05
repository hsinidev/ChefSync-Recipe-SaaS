@extends('layouts.admin')
@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="max-w-4xl">

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl cs-flash-success" style="display: flex; align-items: center; gap: 8px;">
        <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl cs-flash-error">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-xs">• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr; gap: 30px;" class="lg:grid-cols-2">

            {{-- Left Side: AI Configuration & General Settings --}}
            <div style="display: flex; flex-direction: column; gap: 30px;">
                
                {{-- AI Integration Settings --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">✨ Generative AI Settings</h2>
                        <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Configure Google Gemini or OpenAI to auto-generate recipes</p>
                    </div>

                    <div>
                        <label class="cs-label">Preferred AI Provider</label>
                        <select name="preferred_ai_provider" required class="cs-input" style="-webkit-appearance: none; background-color: var(--slate-50);">
                            <option value="gemini" {{ old('preferred_ai_provider', $setting->preferred_ai_provider) === 'gemini' ? 'selected' : '' }}>Google Gemini (Flash 2.5)</option>
                            <option value="openai" {{ old('preferred_ai_provider', $setting->preferred_ai_provider) === 'openai' ? 'selected' : '' }}>OpenAI ChatGPT (GPT-4o)</option>
                        </select>
                    </div>

                    <div>
                        <label class="cs-label">Google Gemini API Key</label>
                        <input type="password" name="gemini_api_key" value="{{ old('gemini_api_key', $setting->gemini_api_key) }}" placeholder="AIzaSy..." class="cs-input">
                        <span style="font-size: 11px; color: var(--slate-400); margin-top: 4px; display: block;">Used for fast, cost-efficient recipe and description generation.</span>
                    </div>

                    <div>
                        <label class="cs-label">OpenAI API Key</label>
                        <input type="password" name="openai_api_key" value="{{ old('openai_api_key', $setting->openai_api_key) }}" placeholder="sk-proj-..." class="cs-input">
                    </div>

                    <div>
                        <label class="cs-label">OpenAI Chat Model</label>
                        <input type="text" name="openai_model" value="{{ old('openai_model', $setting->openai_model) }}" placeholder="e.g. gpt-4o-mini" class="cs-input">
                    </div>
                </div>

                {{-- Tenant / Portal Configuration --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">🌐 Portal Branding</h2>
                        <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Manage domain routing and brand names</p>
                    </div>

                    <div>
                        <label class="cs-label">Culinary Portal Name *</label>
                        <input type="text" name="tenant_name" value="{{ old('tenant_name', $tenant->name ?? '') }}" required class="cs-input">
                    </div>

                    <div>
                        <label class="cs-label">Domain Hostname *</label>
                        <input type="text" name="tenant_domain" value="{{ old('tenant_domain', $tenant->domain ?? '') }}" required class="cs-input">
                        <span style="font-size: 11px; color: var(--slate-400); margin-top: 4px; display: block;">e.g. localhost, recipes.hsini.dev</span>
                    </div>
                </div>

            </div>

            {{-- Right Side: Profile & Authentication Credentials --}}
            <div style="display: flex; flex-direction: column; gap: 30px;">
                
                {{-- Admin Credentials --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">👤 Admin Account Credentials</h2>
                        <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Change admin profile details and login passwords</p>
                    </div>

                    <div>
                        <label class="cs-label">Admin Name *</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name', $user->name ?? '') }}" required class="cs-input">
                    </div>

                    <div>
                        <label class="cs-label">Admin Email/Username *</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email', $user->email ?? '') }}" required class="cs-input">
                    </div>

                    <div style="border-top: 1px solid var(--slate-200); padding-top: 20px; margin-top: 10px;">
                        <h3 style="font-size: 13px; font-weight: 700; color: var(--slate-800); margin-bottom: 15px;">Update Password</h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <div>
                                <label class="cs-label">New Password</label>
                                <input type="password" name="admin_password" placeholder="••••••••" class="cs-input">
                                <span style="font-size: 11px; color: var(--slate-400); margin-top: 4px; display: block;">Leave blank to keep the current password.</span>
                            </div>

                            <div>
                                <label class="cs-label">Confirm New Password</label>
                                <input type="password" name="admin_password_confirmation" placeholder="••••••••" class="cs-input">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- Header & Footer Customization Card --}}
        <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 20px; margin-top: 30px;">
            <div>
                <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">🎨 Header & Footer Layout Management</h2>
                <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Fully customize the header branding, navigation links, copyright notice, and dark newsletter footer</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 16px;" class="lg:grid-cols-2">
                <div>
                    <label class="cs-label">Header Logo Text</label>
                    <input type="text" name="header_logo_text" value="{{ old('header_logo_text', $setting->header_logo_text) }}" required class="cs-input">
                </div>
                <div>
                    <label class="cs-label">Header Subtitle Tagline</label>
                    <input type="text" name="header_subtitle" value="{{ old('header_subtitle', $setting->header_subtitle) }}" required class="cs-input">
                </div>
            </div>

            <div>
                <label class="cs-label">Footer Newsletter Title</label>
                <input type="text" name="footer_newsletter_title" value="{{ old('footer_newsletter_title', $setting->footer_newsletter_title) }}" required class="cs-input">
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 16px;" class="lg:grid-cols-3">
                <div>
                    <label class="cs-label">Footer Newsletter Placeholder</label>
                    <input type="text" name="footer_newsletter_placeholder" value="{{ old('footer_newsletter_placeholder', $setting->footer_newsletter_placeholder) }}" required class="cs-input">
                </div>
                <div>
                    <label class="cs-label">Footer Newsletter Button</label>
                    <input type="text" name="footer_newsletter_button" value="{{ old('footer_newsletter_button', $setting->footer_newsletter_button) }}" required class="cs-input">
                </div>
                <div>
                    <label class="cs-label">Footer Copyright Notice</label>
                    <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $setting->footer_copyright) }}" required class="cs-input">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 20px; border-top: 1px solid var(--slate-200); padding-top: 20px;" class="lg:grid-cols-2">
                <div>
                    <label class="cs-label">Header Navigation Links (JSON format)</label>
                    <textarea name="header_nav_links" rows="8" required class="cs-input" style="font-family: monospace; font-size: 12px; resize: none;">{{ old('header_nav_links', json_encode($setting->header_nav_links, JSON_PRETTY_PRINT)) }}</textarea>
                    <p style="font-size: 11px; color: var(--slate-400); margin-top: 6px;">Must be a valid JSON array of objects with "text" and "url" properties.</p>
                </div>
                <div>
                    <label class="cs-label">Footer Columns & Links (JSON format)</label>
                    <textarea name="footer_columns_json" rows="8" required class="cs-input" style="font-family: monospace; font-size: 12px; resize: none;">{{ old('footer_columns_json', json_encode($setting->footer_columns_json, JSON_PRETTY_PRINT)) }}</textarea>
                    <p style="font-size: 11px; color: var(--slate-400); margin-top: 6px;">Must be a valid JSON array of columns containing "title" and a list of "links" (text, url).</p>
                </div>
            </div>
        </div>

        {{-- Action Footer --}}
        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 35px; border-top: 1px solid var(--slate-200); padding-top: 20px;">
            <button type="submit" class="btn-primary" style="padding: 12px 36px; font-size: 14.5px;">
                Save Settings
            </button>
        </div>
    </form>

</div>
@endsection
