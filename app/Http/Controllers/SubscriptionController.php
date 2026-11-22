<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionCategory;
use App\Models\SubscriptionFile;
use App\Models\UserSubscription;
use App\Models\SubscriptionOrder;
use App\Models\SubscriptionDownloadLog;
use App\Models\PaymentMethod;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        $plans = SubscriptionPlan::active()->get();
        $categories = SubscriptionCategory::active()->withCount('files')->get();
        $recentFiles = SubscriptionFile::active()->with('category')->latest()->take(8)->get();
        $userSubscription = auth()->check() ? auth()->user()->activeSubscription : null;

        return view('subscription.index', compact('plans', 'categories', 'recentFiles', 'userSubscription'));
    }

    public function plans()
    {
        $plans = SubscriptionPlan::active()->get();
        $userSubscription = auth()->check() ? auth()->user()->activeSubscription : null;

        return view('subscription.plans', compact('plans', 'userSubscription'));
    }

    public function categories()
    {
        $categories = SubscriptionCategory::active()->withCount('files')->get();
        return view('subscription.categories', compact('categories'));
    }

    public function category(string $slug)
    {
        $category = SubscriptionCategory::active()->where('slug', $slug)->firstOrFail();
        $files = SubscriptionFile::active()->where('category_id', $category->id)->paginate(12);

        return view('subscription.category-files', compact('category', 'files'));
    }

    public function file(string $slug)
    {
        $file = SubscriptionFile::active()->where('slug', $slug)->with('category')->firstOrFail();
        $userSubscription = auth()->check() ? auth()->user()->activeSubscription : null;
        $canDownload = $userSubscription && $userSubscription->canAccessFile($file) && $userSubscription->canDownload();

        return view('subscription.file-detail', compact('file', 'userSubscription', 'canDownload'));
    }

    public function download(string $slug)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to download subscription resources.');
        }

        $user = auth()->user();
        $subscription = $user->activeSubscription;

        if (!$subscription || !$subscription->isActive()) {
            return redirect()->route('subscription.plans')->with('error', app()->getLocale() === 'bn'
                ? 'আপনার কোন সক্রিয় সাবস্ক্রিপশন নেই বা মেয়াদ শেষ হয়েছে। দয়া করে একটি প্ল্যান বেছে নিন।'
                : 'You do not have an active subscription. Please subscribe to download this resource.');
        }

        $file = SubscriptionFile::active()->where('slug', $slug)->firstOrFail();

        if (!$subscription->canAccessFile($file)) {
            return redirect()->back()->with('error', 'Your current subscription plan does not grant access to this category.');
        }

        if (!$subscription->canDownload()) {
            return redirect()->back()->with('error', 'Daily or total download limit reached for your current plan.');
        }

        // Record download log
        SubscriptionDownloadLog::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'file_id' => $file->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        $file->increment('download_count');
        $subscription->increment('total_downloads');

        // Check if Supabase signed URL or direct storage delivery
        if ($this->supabase->isConfigured()) {
            $signedUrl = $this->supabase->createSignedUrl(
                $file->storage_bucket ?: 'storpify-subscription-library',
                $file->file_path,
                3600
            );

            if ($signedUrl) {
                return redirect()->away($signedUrl);
            }
        }

        // Local dummy download or fallback
        return response()->streamDownload(function () use ($file) {
            echo "STORPIFY SECURE SUBSCRIPTION RESOURCE DELIVERY\n";
            echo "Title: {$file->title}\n";
            echo "Version: {$file->version}\n";
            echo "Authorized User: " . auth()->user()->email . "\n";
            echo "Downloaded At: " . now()->toIso8601String() . "\n";
        }, $file->slug . '-' . $file->version . '.' . ($file->file_type ?: 'zip'));
    }

    public function subscribe(int $planId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login or register to subscribe.');
        }

        $plan = SubscriptionPlan::active()->findOrFail($planId);
        $paymentMethods = PaymentMethod::active()->get();

        return view('subscription.checkout', compact('plan', 'paymentMethods'));
    }

    public function processSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'sender_number' => 'required|string|max:30',
            'transaction_id' => 'required|string|max:100',
        ]);

        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $method = PaymentMethod::findOrFail($request->payment_method_id);
        $trxId = trim($request->transaction_id);

        return DB::transaction(function () use ($user, $plan, $method, $trxId, $request) {
            $orderNumber = SubscriptionOrder::generateOrderNumber();

            // Create Subscription Order
            $order = SubscriptionOrder::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $plan->effective_price,
                'currency' => 'BDT',
                'payment_status' => 'pending',
                'payment_method_id' => $method->id,
                'method_code' => $method->code,
                'transaction_id' => $trxId,
                'sender_number' => $request->sender_number,
            ]);

            // Create Membership Record (marked active or pending until admin verification)
            // Here we activate or wait for verification. In production manual flow, pending until approved.
            UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'order_id' => $order->id,
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_days),
                'status' => 'active', // Instant active with pending audit check
                'total_downloads' => 0,
            ]);

            return redirect()->route('customer.subscription')->with('success', app()->getLocale() === 'bn'
                ? 'আপনার সাবস্ক্রিপশন সফলভাবে সক্রিয় হয়েছে! এখন সব ফাইল আনলিমিটেড ডাউনলোড করুন।'
                : 'Subscription activated successfully! Enjoy unlimited downloads.');
        });
    }
}
