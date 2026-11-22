<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\HeroSlide;
use App\Models\PopupCampaign;
use App\Models\PromotionalBanner;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class HomepageCmsController extends Controller
{
    // 1. Homepage Visual Sections Builder
    public function sections()
    {
        $sections = HomepageSection::orderBy('sort_order')->get();
        return view('admin.cms.sections', compact('sections'));
    }

    public function updateSection(Request $request, int $id)
    {
        $section = HomepageSection::findOrFail($id);
        $oldData = $section->toArray();

        $section->update([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'subtitle' => $request->subtitle,
            'subtitle_bn' => $request->subtitle_bn,
            'is_active' => $request->has('is_active'),
            'show_on_desktop' => $request->has('show_on_desktop'),
            'show_on_tablet' => $request->has('show_on_tablet'),
            'show_on_mobile' => $request->has('show_on_mobile'),
            'sort_order' => (int)$request->sort_order,
        ]);

        AuditLog::record('update_cms_section', 'homepage_sections', (string)$section->id, $oldData, $section->toArray());

        return redirect()->back()->with('success', 'হোমপেজ সেকশন সফলভাবে আপডেট হয়েছে।');
    }

    // 2. Hero Slider Management
    public function hero()
    {
        $slides = HeroSlide::orderBy('sort_order')->get();
        return view('admin.cms.hero', compact('slides'));
    }

    public function storeSlide(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'subtitle_bn' => 'nullable|string',
            'primary_button_text' => 'nullable|string',
            'primary_button_url' => 'nullable|string',
        ]);

        HeroSlide::create([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'subtitle' => $request->subtitle,
            'subtitle_bn' => $request->subtitle_bn,
            'description' => $request->description,
            'description_bn' => $request->description_bn,
            'primary_button_text' => $request->primary_button_text,
            'primary_button_url' => $request->primary_button_url,
            'secondary_button_text' => $request->secondary_button_text,
            'secondary_button_url' => $request->secondary_button_url,
            'bg_color' => $request->bg_color ?: '#17152B',
            'sort_order' => (int)$request->sort_order,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'নতুন হিরো স্লাইড সফলভাবে তৈরি হয়েছে।');
    }

    public function updateSlide(Request $request, int $id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->update([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'subtitle' => $request->subtitle,
            'subtitle_bn' => $request->subtitle_bn,
            'primary_button_text' => $request->primary_button_text,
            'primary_button_url' => $request->primary_button_url,
            'secondary_button_text' => $request->secondary_button_text,
            'secondary_button_url' => $request->secondary_button_url,
            'sort_order' => (int)$request->sort_order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'হিরো স্লাইড আপডেট হয়েছে।');
    }

    public function deleteSlide(int $id)
    {
        HeroSlide::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'হিরো স্লাইড মুছে ফেলা হয়েছে।');
    }

    // 3. Offer Popups Management
    public function popups()
    {
        $popups = PopupCampaign::latest()->get();
        return view('admin.cms.popups', compact('popups'));
    }

    public function storePopup(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_text' => 'nullable|string',
            'coupon_code' => 'nullable|string',
        ]);

        PopupCampaign::create([
            'title' => $request->title,
            'title_bn' => $request->title_bn ?: $request->title,
            'description' => $request->description,
            'description_bn' => $request->description_bn,
            'discount_text' => $request->discount_text,
            'coupon_code' => $request->coupon_code,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'display_delay_sec' => (int)($request->display_delay_sec ?: 3),
            'show_on_homepage' => $request->has('show_on_homepage'),
            'show_on_products' => $request->has('show_on_products'),
            'priority' => (int)($request->priority ?: 1),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'নতুন অফার পপআপ ক্যাম্পেইন তৈরি হয়েছে।');
    }

    public function togglePopup(int $id)
    {
        $p = PopupCampaign::findOrFail($id);
        $p->is_active = !$p->is_active;
        $p->save();

        return redirect()->back()->with('success', 'পপআপ স্ট্যাটাস পরিবর্তিত হয়েছে।');
    }
}
