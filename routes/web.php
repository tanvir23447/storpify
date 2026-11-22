<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\HomepageCmsController;
use App\Http\Controllers\Admin\WebSubscriptionAdminController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SettingsController;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| STORPIFY Web Routes
|--------------------------------------------------------------------------
|
| High-performance, secure digital commerce and subscription marketplace routes.
| Target hosting: Hostinger Web Hosting with Supabase PostgreSQL & Storage.
|
*/

// =========================================================================
// 1. LANGUAGE SWITCHER
// =========================================================================
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['bn', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('set.locale');

// =========================================================================
// 2. MAIN DIGITAL PRODUCTS STORE (PUBLIC)
// =========================================================================
Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/shop', [StoreController::class, 'shop'])->name('shop');
Route::get('/category/{slug}', function (string $slug) {
    return redirect()->route('shop', ['category' => $slug]);
})->name('category.show');
Route::get('/product/{slug}', [StoreController::class, 'product'])->name('product.show');
Route::get('/faq', [StoreController::class, 'faq'])->name('faq');
Route::post('/newsletter/subscribe', [StoreController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// =========================================================================
// 3. SHOPPING CART & CHECKOUT
// =========================================================================
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/item/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
Route::get('/order/confirmation/{orderNumber}', [CartController::class, 'confirmation'])->name('order.confirmation');

// =========================================================================
// 4. INDEPENDENT WEB SUBSCRIPTION SYSTEM (PUBLIC)
// =========================================================================
Route::prefix('web-subscription')->name('subscription.')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index'])->name('index');
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
    Route::get('/categories', [SubscriptionController::class, 'categories'])->name('categories');
    Route::get('/category/{slug}', [SubscriptionController::class, 'category'])->name('category');
    Route::get('/file/{slug}', [SubscriptionController::class, 'file'])->name('file');
    Route::get('/file/{slug}/download', [SubscriptionController::class, 'download'])->name('file.download');
    Route::get('/checkout/{planId}', [SubscriptionController::class, 'subscribe'])->name('checkout');
    Route::get('/subscribe/{planId}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/checkout/process', [SubscriptionController::class, 'processSubscription'])->name('process');
});

// =========================================================================
// 5. AUTHENTICATION & ACCESS
// =========================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================================
// 6. CUSTOMER DASHBOARD & DIGITAL DOWNLOADS (AUTH REQUIRED)
// =========================================================================
Route::prefix('customer')->name('customer.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('/order/{orderNumber}', [CustomerController::class, 'orderDetail'])->name('order.detail');
    Route::get('/downloads', [CustomerController::class, 'downloads'])->name('downloads');
    Route::get('/download/{fileId}/{orderItemId}', [CustomerController::class, 'downloadProductFile'])->name('download.file');
    Route::get('/subscription', [CustomerController::class, 'subscription'])->name('subscription');
    Route::get('/wishlist', [CustomerController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/toggle/{productId}', [CustomerController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/tickets', [CustomerController::class, 'tickets'])->name('tickets');
    Route::post('/tickets', [CustomerController::class, 'createTicket'])->name('tickets.create');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
});

// =========================================================================
// 7. ADMIN CONTROL CENTER (AUTH + STAFF REQUIRED)
// =========================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'staff'])->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{id}/file', [ProductController::class, 'uploadFile'])->name('products.upload_file');

    // Categories
    Route::get('/categories', [ProductController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [ProductController::class, 'storeCategory'])->name('categories.store');

    // Orders & Payment Verification
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/payments/{id}/verify', [OrderController::class, 'verifyPayment'])->name('payments.verify');
    Route::get('/payments', [OrderController::class, 'payments'])->name('payments.index');

    // Dynamic Bangladeshi Payment Methods & Phone Numbers
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('/payment-methods/{id}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{id}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');

    // Customers List
    Route::get('/customers', function () {
        $customers = User::where('role', 'customer')->withCount('orders')->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    })->name('customers.index');

    // Promotional Coupons
    Route::get('/coupons', function () {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    })->name('coupons.index');

    Route::post('/coupons', function (Request $request) {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_spend' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_spend' => $request->min_spend,
            'usage_limit' => $request->usage_limit,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'নতুন কুপন সফলভাবে তৈরি হয়েছে।');
    })->name('coupons.store');

    // Homepage CMS & Banner Builders
    Route::get('/cms/sections', [HomepageCmsController::class, 'sections'])->name('cms.sections');
    Route::put('/cms/sections/{id}', [HomepageCmsController::class, 'updateSection'])->name('cms.sections.update');
    Route::get('/cms/hero', [HomepageCmsController::class, 'hero'])->name('cms.hero');
    Route::post('/cms/hero', [HomepageCmsController::class, 'storeSlide'])->name('cms.hero.store');
    Route::put('/cms/hero/{id}', [HomepageCmsController::class, 'updateSlide'])->name('cms.hero.update');
    Route::delete('/cms/hero/{id}', [HomepageCmsController::class, 'deleteSlide'])->name('cms.hero.delete');
    Route::get('/cms/popups', [HomepageCmsController::class, 'popups'])->name('cms.popups');
    Route::post('/cms/popups', [HomepageCmsController::class, 'storePopup'])->name('cms.popups.store');
    Route::post('/cms/popups/{id}/toggle', [HomepageCmsController::class, 'togglePopup'])->name('cms.popups.toggle');

    // Dedicated Web Subscription Module Admin
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/dashboard', [WebSubscriptionAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/plans', [WebSubscriptionAdminController::class, 'plans'])->name('plans');
        Route::post('/plans', [WebSubscriptionAdminController::class, 'storePlan'])->name('plans.store');
        Route::get('/categories', [WebSubscriptionAdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [WebSubscriptionAdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/files', [WebSubscriptionAdminController::class, 'files'])->name('files');
        Route::post('/files', [WebSubscriptionAdminController::class, 'storeFile'])->name('files.store');
        Route::get('/subscribers', [WebSubscriptionAdminController::class, 'subscribers'])->name('subscribers');
        Route::get('/orders', [WebSubscriptionAdminController::class, 'orders'])->name('orders');
        Route::get('/downloads', [WebSubscriptionAdminController::class, 'downloads'])->name('downloads');
        Route::get('/settings', [WebSubscriptionAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [WebSubscriptionAdminController::class, 'updateSettings'])->name('settings.update');
    });

    // 3-Level Dynamic RBAC Staff & Access (Super Admin Only)
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/audit-logs', [StaffController::class, 'auditLogs'])->name('audit-logs');

    // System Preflight & Website Settings
    Route::get('/settings/preflight', [SettingsController::class, 'preflight'])->name('settings.preflight');
    Route::get('/settings/website', [SettingsController::class, 'websiteSettings'])->name('settings.website');
    Route::post('/settings/website', [SettingsController::class, 'updateWebsiteSettings'])->name('settings.website.update');
});
