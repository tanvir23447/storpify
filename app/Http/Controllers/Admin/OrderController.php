<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['payments.paymentMethod', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('order_number', 'like', "%{$q}%")
                  ->orWhere('customer_name', 'like', "%{$q}%")
                  ->orWhere('customer_email', 'like', "%{$q}%")
                  ->orWhere('customer_phone', 'like', "%{$q}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = Order::with(['items.product.files', 'items.variant.files', 'payments.paymentMethod', 'invoice', 'user'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function verifyPayment(Request $request, int $paymentId)
    {
        $payment = Payment::with('order')->findOrFail($paymentId);
        $order = $payment->order;
        $user = auth()->user();

        $action = $request->input('action'); // 'approve' or 'reject'

        return DB::transaction(function () use ($payment, $order, $user, $action, $request) {
            if ($action === 'approve') {
                $payment->update([
                    'status' => 'verified',
                    'verified_by' => $user->id,
                    'verified_at' => now(),
                ]);

                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'completed',
                ]);

                // Update invoice
                Invoice::where('order_id', $order->id)->update([
                    'status' => 'paid',
                    'paid_amount' => $order->total_amount,
                ]);

                AuditLog::record('verify_payment', 'payments', (string)$payment->id, null, [
                    'order_id' => $order->id,
                    'status' => 'verified',
                    'verifier' => $user->name,
                ]);

                return redirect()->back()->with('success', app()->getLocale() === 'bn'
                    ? 'পেমেন্ট সফলভাবে ভেরিফাই ও অনুমোদন করা হয়েছে। কাস্টমার ফাইল ডাউনলোড করতে পারবেন।'
                    : 'Payment verified and approved successfully. Customer now has download access.');
            } else {
                $reason = $request->input('rejection_reason', 'Invalid or unverified transaction details');
                $payment->update([
                    'status' => 'rejected',
                    'rejection_reason' => $reason,
                    'verified_by' => $user->id,
                    'verified_at' => now(),
                ]);

                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled',
                ]);

                AuditLog::record('reject_payment', 'payments', (string)$payment->id, null, [
                    'order_id' => $order->id,
                    'status' => 'rejected',
                    'reason' => $reason,
                ]);

                return redirect()->back()->with('error', app()->getLocale() === 'bn'
                    ? 'পেমেন্ট বাতিল করা হয়েছে।'
                    : 'Payment rejected.');
            }
        });
    }

    public function payments(Request $request)
    {
        $payments = Payment::with(['order', 'paymentMethod', 'verifier'])->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }
}
