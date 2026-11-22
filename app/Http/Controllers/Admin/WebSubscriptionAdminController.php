<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionCategory;
use App\Models\SubscriptionFile;
use App\Models\UserSubscription;
use App\Models\SubscriptionOrder;
use App\Models\SubscriptionDownloadLog;
use App\Models\SubscriptionSetting;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebSubscriptionAdminController extends Controller
{
    public function dashboard()
    {
        $totalSubscribers = UserSubscription::count();
        $activeMembers = UserSubscription::where('status', 'active')->where('ends_at', '>', now())->count();
        $expiredMembers = UserSubscription::where('status', 'expired')->orWhere('ends_at', '<=', now())->count();
        $totalFiles = SubscriptionFile::count();
        $totalDownloads = SubscriptionDownloadLog::count();

        // Plans table matching mockup
        $plans = SubscriptionPlan::orderBy('sort_order')->get();
        $recentOrders = SubscriptionOrder::with(['user', 'plan'])->latest()->take(5)->get();

        return view('admin.subscriptions.dashboard', compact(
            'totalSubscribers',
            'activeMembers',
            'expiredMembers',
            'totalFiles',
            'totalDownloads',
            'plans',
            'recentOrders'
        ));
    }

    public function plans()
    {
        $plans = SubscriptionPlan::withCount(['categories', 'files', 'subscriptions'])->orderBy('sort_order')->get();
        $categories = SubscriptionCategory::active()->get();

        return view('admin.subscriptions.plans', compact('plans', 'categories'));
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'download_limit_daily' => 'nullable|integer|min:0',
        ]);

        $plan = SubscriptionPlan::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn ?: $request->name,
            'description' => $request->description,
            'description_bn' => $request->description_bn,
            'duration_days' => (int)$request->duration_days,
            'duration_type' => 'days',
            'price' => $request->price,
            'sale_price' => $request->sale_price ?: null,
            'download_limit_daily' => (int)($request->download_limit_daily ?: 0),
            'download_limit_total' => (int)($request->download_limit_total ?: 0),
            'device_limit' => (int)($request->device_limit ?: 1),
            'is_featured' => $request->has('is_featured'),
            'is_active' => true,
            'all_categories_access' => $request->has('all_categories_access'),
            'sort_order' => (int)$request->sort_order,
        ]);

        if (!$request->has('all_categories_access') && $request->filled('category_ids')) {
            $plan->categories()->sync($request->category_ids);
        }

        AuditLog::record('create_subscription_plan', 'subscription_plans', (string)$plan->id, null, $plan->toArray());

        return redirect()->back()->with('success', 'নতুন সাবস্ক্রিপশন প্ল্যান সফলভাবে তৈরি হয়েছে।');
    }

    public function categories()
    {
        $categories = SubscriptionCategory::withCount('files')->orderBy('sort_order')->get();
        return view('admin.subscriptions.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);
        SubscriptionCategory::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn ?: $request->name,
            'slug' => $slug,
            'icon' => $request->icon ?: 'folder',
            'description' => $request->description,
            'description_bn' => $request->description_bn,
            'sort_order' => (int)$request->sort_order,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'সাবস্ক্রিপশন ক্যাটাগরি তৈরি হয়েছে।');
    }

    public function files()
    {
        $files = SubscriptionFile::with('category')->latest()->paginate(15);
        $categories = SubscriptionCategory::active()->get();

        return view('admin.subscriptions.files', compact('files', 'categories'));
    }

    public function storeFile(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:subscription_categories,id',
            'version' => 'required|string',
            'digital_file' => 'nullable|file|max:102400',
        ]);

        $filePath = 'subscriptions/' . Str::slug($request->title) . '.zip';
        $fileSize = 1000000;
        $fileType = 'zip';

        if ($request->hasFile('digital_file')) {
            $uploaded = $request->file('digital_file');
            $filePath = $uploaded->storeAs('subscriptions', time() . '_' . $uploaded->getClientOriginalName(), 'public');
            $fileSize = $uploaded->getSize();
            $fileType = $uploaded->getClientOriginalExtension();
        }

        $slug = Str::slug($request->title);
        SubscriptionFile::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'title_bn' => $request->title_bn ?: $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'file_path' => $filePath,
            'storage_bucket' => 'storpify-subscription-library',
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'version' => $request->version,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'সাবস্ক্রিপশন লাইব্রেরিতে নতুন ফাইল যুক্ত হয়েছে।');
    }

    public function subscribers()
    {
        $subscribers = UserSubscription::with(['user', 'plan'])->latest()->paginate(15);
        return view('admin.subscriptions.subscribers', compact('subscribers'));
    }

    public function orders()
    {
        $orders = SubscriptionOrder::with(['user', 'plan', 'paymentMethod'])->latest()->paginate(15);
        return view('admin.subscriptions.orders', compact('orders'));
    }

    public function downloads()
    {
        $downloads = SubscriptionDownloadLog::with(['user', 'file.category', 'subscription.plan'])->latest()->paginate(20);
        return view('admin.subscriptions.downloads', compact('downloads'));
    }

    public function settings()
    {
        $baseSlug = SubscriptionSetting::get('base_slug', 'web-subscription');
        return view('admin.subscriptions.settings', compact('baseSlug'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'base_slug' => 'required|string|alpha_dash|max:50',
        ]);

        SubscriptionSetting::set('base_slug', $request->base_slug);

        return redirect()->back()->with('success', 'সাবস্ক্রিপশন বেস স্লাগ ও সেটিংস আপডেট হয়েছে।');
    }
}
