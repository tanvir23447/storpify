<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\Invoice;
use App\Models\SecurityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CartController extends Controller
{
    protected function getOrCreateCart(): Cart
    {
        $sessionId = session()->getId();
        $userId = auth()->id();

        $cart = Cart::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId)->orWhere('session_id', $sessionId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
        } elseif ($userId && !$cart->user_id) {
            $cart->update(['user_id' => $userId]);
        }

        return $cart;
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product', 'items.variant']);

        $appliedCoupon = null;
        $discount = 0.00;

        if (session()->has('applied_coupon')) {
            $coupon = Coupon::where('code', session('applied_coupon'))->where('is_active', true)->first();
            if ($coupon) {
                $validity = $coupon->isValidForAmount($cart->subtotal, auth()->id());
                if ($validity['valid']) {
                    $appliedCoupon = $coupon;
                    $discount = $validity['discount'];
                } else {
                    session()->forget('applied_coupon');
                }
            }
        }

        $total = max(0, $cart->subtotal - $discount);

        return view('store.cart', compact('cart', 'appliedCoupon', 'discount', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::published()->findOrFail($request->product_id);
        $variant = null;
        $price = $product->effective_price;

        // If product has variants, require or validate variation selection
        if ($product->variants()->count() > 0) {
            if (!$request->variant_id) {
                // If not provided, choose the first variant by default
                $variant = $product->variants()->first();
            } else {
                $variant = ProductVariant::where('product_id', $product->id)->findOrFail($request->variant_id);
            }
            $price = $variant->effective_price;
        }

        $cart = $this->getOrCreateCart();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variant ? $variant->id : null)
            ->first();

        if ($item) {
            // For digital products, quantity is typically 1, but we can increment or keep 1
            $item->increment('quantity', $request->get('quantity', 1));
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'quantity' => $request->get('quantity', 1),
                'price' => $price, // Securely calculated server-side
            ]);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('checkout');
        }

        return redirect()->back()->with('success', app()->getLocale() === 'bn' 
            ? 'প্রোডাক্টটি সফলভাবে কার্টে যোগ করা হয়েছে।' 
            : 'Product added to your cart.');
    }

    public function remove(int $id)
    {
        $cart = $this->getOrCreateCart();
        CartItem::where('cart_id', $cart->id)->where('id', $id)->delete();

        return redirect()->back()->with('success', app()->getLocale() === 'bn'
            ? 'কার্ট থেকে আইটেম মুছে ফেলা হয়েছে।'
            : 'Item removed from your cart.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $cart = $this->getOrCreateCart();
        $code = trim(strtoupper($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', app()->getLocale() === 'bn' ? 'অবৈধ কুপন কোড।' : 'Invalid coupon code.');
        }

        $result = $coupon->isValidForAmount($cart->subtotal, auth()->id());
        if (!$result['valid']) {
            return redirect()->back()->with('error', $result['message']);
        }

        session(['applied_coupon' => $coupon->code]);

        return redirect()->back()->with('success', app()->getLocale() === 'bn' ? 'কুপন সফলভাবে প্রয়োগ করা হয়েছে।' : 'Coupon applied successfully.');
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return redirect()->back()->with('success', app()->getLocale() === 'bn' ? 'কুপন মুছে ফেলা হয়েছে।' : 'Coupon removed.');
    }

    public function checkout()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product', 'items.variant']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop')->with('error', app()->getLocale() === 'bn' ? 'আপনার কার্ট খালি রয়েছে।' : 'Your cart is empty.');
        }

        $appliedCoupon = null;
        $discount = 0.00;

        if (session()->has('applied_coupon')) {
            $coupon = Coupon::where('code', session('applied_coupon'))->where('is_active', true)->first();
            if ($coupon) {
                $validity = $coupon->isValidForAmount($cart->subtotal, auth()->id());
                if ($validity['valid']) {
                    $appliedCoupon = $coupon;
                    $discount = $validity['discount'];
                }
            }
        }

        $total = max(0, $cart->subtotal - $discount);
        $paymentMethods = PaymentMethod::active()->get();

        return view('store.checkout', compact('cart', 'appliedCoupon', 'discount', 'total', 'paymentMethods'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'sender_number' => 'required|string|max:30',
            'transaction_id' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = $this->getOrCreateCart();
        $cart->load(['items.product', 'items.variant']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Your cart is empty.');
        }

        $method = PaymentMethod::findOrFail($request->payment_method_id);
        $trxId = trim($request->transaction_id);

        // Security: Prevent duplicate transaction ID reuse
        $existingPayment = Payment::where('transaction_id', $trxId)->first();
        if ($existingPayment) {
            SecurityEvent::log('duplicate_transaction_id_submitted', 'warning', [
                'trx_id' => $trxId,
                'email' => $request->customer_email,
            ]);
            return redirect()->back()->withInput()->with('error', app()->getLocale() === 'bn'
                ? 'এই ট্রানজেকশন আইডিটি ইতিমধ্যে পূর্বে ব্যবহার করা হয়েছে। দয়া করে সঠিক TrxID দিন।'
                : 'This Transaction ID has already been submitted. Please provide a valid transaction ID.');
        }

        // Calculate discount
        $discount = 0.00;
        $coupon = null;
        if (session()->has('applied_coupon')) {
            $coupon = Coupon::where('code', session('applied_coupon'))->where('is_active', true)->first();
            if ($coupon) {
                $validity = $coupon->isValidForAmount($cart->subtotal, auth()->id());
                if ($validity['valid']) {
                    $discount = $validity['discount'];
                }
            }
        }

        $subtotal = $cart->subtotal;
        $total = max(0, $subtotal - $discount);

        return DB::transaction(function () use ($request, $cart, $method, $subtotal, $discount, $total, $coupon, $trxId) {
            // 1. Create Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => 0.00,
                'total_amount' => $total,
                'currency' => 'BDT',
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'coupon_code' => $coupon ? $coupon->code : null,
                'notes' => $request->notes,
            ]);

            // 2. Create Order Items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant ? $item->variant->name : null,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                // Increment sales count on product
                $item->product->increment('sales_count', $item->quantity);
            }

            // 3. Handle Payment Proof Upload (if provided)
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'proof_' . $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
                $proofPath = $file->storeAs('payment_proofs', $filename, 'public');
            }

            // 4. Create Payment Record
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'payment_method_id' => $method->id,
                'method_code' => $method->code,
                'transaction_id' => $trxId,
                'sender_number' => $request->sender_number,
                'receiver_number' => $method->primary_number,
                'amount' => $total,
                'currency' => 'BDT',
                'proof_file' => $proofPath,
                'payment_type' => $method->account_type === 'automated' ? 'automated' : 'manual',
                'status' => 'pending',
            ]);

            PaymentTransaction::create([
                'payment_id' => $payment->id,
                'transaction_reference' => $trxId,
                'event' => 'payment_submitted',
                'payload' => [
                    'amount' => $total,
                    'method' => $method->code,
                    'sender' => $request->sender_number,
                ],
            ]);

            // 5. Create Invoice
            Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'paid_amount' => 0.00,
                'status' => 'unpaid',
                'invoice_date' => today(),
            ]);

            // 6. Record Coupon Redemption
            if ($coupon) {
                CouponRedemption::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                    'created_at' => now(),
                ]);
                $coupon->increment('usage_count');
                session()->forget('applied_coupon');
            }

            // 7. Clear Cart
            $cart->items()->delete();

            return redirect()->route('order.confirmation', $order->order_number)->with('success', app()->getLocale() === 'bn'
                ? 'আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে। পেমেন্ট যাচাইকরণের পর ডাউনলোড লিংক উন্মুক্ত হবে।'
                : 'Order placed successfully. Your downloads will be available once the payment is verified.');
        });
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product.files', 'items.variant.files', 'payments', 'invoice'])
            ->firstOrFail();

        return view('store.order-confirmation', compact('order'));
    }
}
