<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\ProductDownloadLog;
use App\Models\ProductLicense;
use App\Models\UserSubscription;
use App\Models\Wishlist;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function dashboard()
    {
        $user = auth()->user();
        $ordersCount = Order::where('user_id', $user->id)->count();
        $subscription = $user->activeSubscription;
        $recentOrders = Order::where('user_id', $user->id)->with('items')->latest()->take(5)->get();
        $downloadsCount = ProductDownloadLog::where('user_id', $user->id)->count();

        return view('customer.dashboard', compact('user', 'ordersCount', 'subscription', 'recentOrders', 'downloadsCount'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product', 'payments'])
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items.product.files', 'items.variant.files', 'payments', 'invoice'])
            ->firstOrFail();

        return view('customer.order-detail', compact('order'));
    }

    public function downloads()
    {
        $user = auth()->user();
        // Fetch all order items from verified/paid orders
        $paidOrderIds = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->pluck('id');

        $purchasedItems = OrderItem::whereIn('order_id', $paidOrderIds)
            ->with(['product.files', 'variant.files'])
            ->latest()
            ->get();

        return view('customer.downloads', compact('purchasedItems'));
    }

    public function downloadProductFile(int $fileId, int $orderItemId)
    {
        $user = auth()->user();

        // Security check: order item must belong to a paid order of this user
        $item = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('payment_status', 'paid');
        })->findOrFail($orderItemId);

        $file = ProductFile::where('is_active', true)->findOrFail($fileId);

        // Verify file belongs to product or variant of this item
        if ($file->product_id !== $item->product_id) {
            abort(403, 'Unauthorized file download access.');
        }

        // Record download log
        ProductDownloadLog::create([
            'user_id' => $user->id,
            'order_id' => $item->order_id,
            'product_id' => $item->product_id,
            'variant_id' => $item->variant_id,
            'file_id' => $file->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        $item->increment('download_count');
        $file->product->increment('download_count');

        // Supabase Storage Signed Download
        if ($this->supabase->isConfigured()) {
            $signedUrl = $this->supabase->createSignedUrl(
                $file->storage_bucket ?: 'storpify-product-downloads',
                $file->file_path,
                3600
            );

            if ($signedUrl) {
                return redirect()->away($signedUrl);
            }
        }

        // Local delivery fallback
        return response()->streamDownload(function () use ($file, $item) {
            echo "STORPIFY SECURE PRODUCT DIGITAL DELIVERY\n";
            echo "Product: {$item->product_name}\n";
            echo "Order: #{$item->order->order_number}\n";
            echo "File: {$file->file_name}\n";
            echo "Licensed to: " . auth()->user()->name . " (" . auth()->user()->email . ")\n";
            echo "Download Timestamp: " . now()->toIso8601String() . "\n";
        }, $file->file_name);
    }

    public function subscription()
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription;
        $history = UserSubscription::where('user_id', $user->id)->with('plan')->latest()->get();

        return view('customer.subscription', compact('user', 'subscription', 'history'));
    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('product.categories')
            ->latest()
            ->paginate(12);

        return view('customer.wishlist', compact('wishlists'));
    }

    public function toggleWishlist(int $productId)
    {
        $user = auth()->user();
        $existing = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $msg = app()->getLocale() === 'bn' ? 'উইশলিস্ট থেকে সরানো হয়েছে।' : 'Removed from wishlist.';
        } else {
            Wishlist::create(['user_id' => $user->id, 'product_id' => $productId]);
            $msg = app()->getLocale() === 'bn' ? 'উইশলিস্টে যুক্ত করা হয়েছে।' : 'Added to wishlist.';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function tickets()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())->latest()->paginate(10);
        return view('customer.tickets', compact('tickets'));
    }

    public function createTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|string',
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => false,
        ]);

        return redirect()->route('customer.tickets')->with('success', app()->getLocale() === 'bn'
            ? 'সাপোর্ট টিকিট সফলভাবে তৈরি হয়েছে। আমাদের টিম দ্রুত আপনার সাথে যোগাযোগ করবে।'
            : 'Support ticket submitted successfully. Our team will assist you shortly.');
    }

    public function profile()
    {
        $user = auth()->user()->load('profile');
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', app()->getLocale() === 'bn' ? 'প্রোফাইল আপডেট হয়েছে।' : 'Profile updated successfully.');
    }
}
