<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionCategory;
use App\Models\SubscriptionFile;
use App\Models\UserSubscription;
use App\Models\PaymentMethod;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\WebSubscriptionSeeder;
use Database\Seeders\PaymentMethodsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(WebSubscriptionSeeder::class);
        $this->seed(PaymentMethodsSeeder::class);
    }

    public function test_public_subscription_pages_can_be_rendered(): void
    {
        $response = $this->get('/web-subscription');
        $response->assertStatus(200);

        $response = $this->get('/web-subscription/plans');
        $response->assertStatus(200);

        $response = $this->get('/web-subscription/categories');
        $response->assertStatus(200);
    }

    public function test_customer_can_subscribe_to_a_membership_plan(): void
    {
        $customer = User::factory()->create(['role' => 'customer', 'status' => 'active']);
        $plan = SubscriptionPlan::first();
        $bkash = PaymentMethod::where('code', 'bkash')->first();

        $trxId = 'SUB_TRX_' . time();

        $response = $this->actingAs($customer)->post('/web-subscription/checkout/process', [
            'plan_id' => $plan->id,
            'payment_method_id' => $bkash->id,
            'sender_number' => '01711223344',
            'transaction_id' => $trxId,
        ]);

        $response->assertRedirect('/customer/subscription');

        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
        ]);
    }

    public function test_active_subscriber_can_download_subscription_resource(): void
    {
        $subscriber = User::factory()->create(['role' => 'customer', 'status' => 'active']);
        $plan = SubscriptionPlan::where('all_categories_access', true)->first();
        $file = SubscriptionFile::first();

        // Create active subscription
        UserSubscription::create([
            'user_id' => $subscriber->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'status' => 'active',
            'total_downloads' => 0,
        ]);

        $response = $this->actingAs($subscriber)->get("/web-subscription/file/{$file->slug}/download");

        // Verify download succeeds
        $response->assertOk();

        $this->assertDatabaseHas('subscription_download_logs', [
            'user_id' => $subscriber->id,
            'file_id' => $file->id,
        ]);
    }

    public function test_non_subscriber_is_denied_file_download(): void
    {
        $nonSubscriber = User::factory()->create(['role' => 'customer', 'status' => 'active']);
        $file = SubscriptionFile::first();

        // User without active subscription tries to download
        $response = $this->actingAs($nonSubscriber)->get("/web-subscription/file/{$file->slug}/download");

        $response->assertRedirect('/web-subscription/plans');
        $response->assertSessionHas('error');
    }

    public function test_expired_subscription_is_denied_file_download(): void
    {
        $expiredSubscriber = User::factory()->create(['role' => 'customer', 'status' => 'active']);
        $plan = SubscriptionPlan::first();
        $file = SubscriptionFile::first();

        // Expired subscription (ended yesterday)
        UserSubscription::create([
            'user_id' => $expiredSubscriber->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->subDays(60),
            'ends_at' => now()->subDay(),
            'status' => 'expired',
            'total_downloads' => 5,
        ]);

        $response = $this->actingAs($expiredSubscriber)->get("/web-subscription/file/{$file->slug}/download");

        $response->assertRedirect('/web-subscription/plans');
        $response->assertSessionHas('error');
    }
}
