<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\Payment;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\MainStoreSeeder;
use Database\Seeders\PaymentMethodsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MainStoreSeeder::class);
        $this->seed(PaymentMethodsSeeder::class);
    }

    public function test_customer_can_add_product_to_cart_and_proceed_to_checkout(): void
    {
        $customer = User::where('email', 'customer@storpify.com')->first();
        $product = Product::first();

        // 1. Add to cart
        $response = $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $response->assertStatus(302);

        // 2. View cart
        $response = $this->actingAs($customer)->get('/cart');
        $response->assertStatus(200);
        $response->assertSee($product->name);

        // 3. View checkout
        $response = $this->actingAs($customer)->get('/checkout');
        $response->assertStatus(200);
    }

    public function test_customer_can_place_order_with_valid_bkash_payment(): void
    {
        $customer = User::where('email', 'customer@storpify.com')->first();
        $product = Product::first();
        $bkash = PaymentMethod::where('code', 'bkash')->first();

        // 1. Add product to cart
        $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // 2. Submit checkout
        $trxId = 'BKASH_TEST_TRX_' . time();
        $response = $this->actingAs($customer)->post('/checkout/process', [
            'customer_name' => 'John Doe',
            'customer_email' => $customer->email,
            'customer_phone' => '01712345678',
            'payment_method_id' => $bkash->id,
            'sender_number' => '01712345678',
            'transaction_id' => $trxId,
        ]);

        $this->assertDatabaseHas('payments', [
            'transaction_id' => $trxId,
            'status' => 'pending',
            'method_code' => 'bkash',
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_email' => $customer->email,
            'payment_status' => 'pending',
        ]);
    }

    public function test_duplicate_transaction_id_is_strictly_rejected(): void
    {
        $customer = User::where('email', 'customer@storpify.com')->first();
        $product = Product::first();
        $bkash = PaymentMethod::where('code', 'bkash')->first();

        $trxId = 'UNIQUE_TRX_9999';

        // Seed existing payment with this trxId
        $order = Order::create([
            'order_number' => 'ORD-TEST-001',
            'user_id' => $customer->id,
            'customer_name' => 'Existing Customer',
            'customer_email' => 'existing@example.com',
            'customer_phone' => '01700000000',
            'subtotal' => 500,
            'total_amount' => 500,
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        Payment::create([
            'order_id' => $order->id,
            'user_id' => $customer->id,
            'payment_method_id' => $bkash->id,
            'method_code' => 'bkash',
            'transaction_id' => $trxId,
            'sender_number' => '01700000000',
            'amount' => 500,
            'status' => 'pending',
        ]);

        // Attempt to submit identical trxId in cart checkout
        $this->actingAs($customer)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)->post('/checkout/process', [
            'customer_name' => 'Attacker',
            'customer_email' => $customer->email,
            'customer_phone' => '01711223344',
            'payment_method_id' => $bkash->id,
            'sender_number' => '01711223344',
            'transaction_id' => $trxId,
        ]);

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('security_events', [
            'event_type' => 'duplicate_transaction_id_submitted',
        ]);
    }

    public function test_admin_can_verify_and_approve_pending_payment(): void
    {
        $admin = User::where('email', 'admin@storpify.com')->first();
        $customer = User::where('email', 'customer@storpify.com')->first();
        $bkash = PaymentMethod::where('code', 'bkash')->first();

        $order = Order::create([
            'order_number' => 'ORD-VERIFY-001',
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '01700000000',
            'subtotal' => 1000,
            'total_amount' => 1000,
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $customer->id,
            'payment_method_id' => $bkash->id,
            'method_code' => 'bkash',
            'transaction_id' => 'TRX_TO_BE_VERIFIED',
            'sender_number' => '01700000000',
            'amount' => 1000,
            'status' => 'pending',
        ]);

        // Admin approves payment
        $response = $this->actingAs($admin)->post("/admin/payments/{$payment->id}/verify", [
            'action' => 'approve',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'verified',
            'verified_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);
    }
}
