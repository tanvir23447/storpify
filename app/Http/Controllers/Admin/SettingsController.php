<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PreflightService;
use App\Services\SupabaseService;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected PreflightService $preflight;
    protected SupabaseService $supabase;

    public function __construct(PreflightService $preflight, SupabaseService $supabase)
    {
        $this->preflight = $preflight;
        $this->supabase = $supabase;
    }

    public function preflight()
    {
        $results = $this->preflight->runAllChecks();
        $supabaseConfigured = $this->supabase->isConfigured();

        return view('admin.settings.preflight', compact('results', 'supabaseConfigured'));
    }

    public function websiteSettings()
    {
        $siteName = WebsiteSetting::get('site_name', 'STORPIFY');
        $tagline = WebsiteSetting::get('site_tagline');
        $taglineBn = WebsiteSetting::get('site_tagline_bn');
        $email = WebsiteSetting::get('site_email');
        $phone = WebsiteSetting::get('site_phone');
        $social = WebsiteSetting::get('social_links', []);

        return view('admin.settings.website', compact('siteName', 'tagline', 'taglineBn', 'email', 'phone', 'social'));
    }

    public function updateWebsiteSettings(Request $request)
    {
        WebsiteSetting::set('site_name', $request->site_name, 'general', true);
        WebsiteSetting::set('site_tagline', $request->site_tagline, 'general', true);
        WebsiteSetting::set('site_tagline_bn', $request->site_tagline_bn, 'general', true);
        WebsiteSetting::set('site_email', $request->site_email, 'contact', true);
        WebsiteSetting::set('site_phone', $request->site_phone, 'contact', true);
        WebsiteSetting::set('social_links', [
            'facebook' => $request->facebook,
            'youtube' => $request->youtube,
            'telegram' => $request->telegram,
            'whatsapp' => $request->whatsapp,
        ], 'social', true);

        return redirect()->back()->with('success', 'ওয়েবসাইট সেটিংস সফলভাবে সংরক্ষিত হয়েছে।');
    }
}
