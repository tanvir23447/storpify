<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Real KPI metrics from Database
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $todayOrdersCount = Order::whereDate('created_at', today())->count();
        $totalProductsCount = Product::count();
        $totalCustomersCount = User::where('role', 'customer')->count();
        $pendingPaymentsCount = Payment::where('status', 'pending')->count();
        $activeSubscriptionsCount = UserSubscription::where('status', 'active')->where('ends_at', '>', now())->count();

        // Recent Orders matching mockup table
        $recentOrders = Order::with(['payments.paymentMethod', 'items'])
            ->latest()
            ->take(8)
            ->get();

        // Recent Audit Logs
        $recentLogs = AuditLog::with('staff')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'user',
            'totalRevenue',
            'todayOrdersCount',
            'totalProductsCount',
            'totalCustomersCount',
            'pendingPaymentsCount',
            'activeSubscriptionsCount',
            'recentOrders',
            'recentLogs'
        ));
    }
}
