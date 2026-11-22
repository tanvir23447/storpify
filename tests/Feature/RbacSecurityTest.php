<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductCategory;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\MainStoreSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MainStoreSeeder::class);
    }

    public function test_super_admin_can_access_staff_and_audit_logs(): void
    {
        $superAdmin = User::where('email', 'admin@storpify.com')->first();

        $response = $this->actingAs($superAdmin)->get('/admin/staff');
        $response->assertStatus(200);

        $response = $this->actingAs($superAdmin)->get('/admin/audit-logs');
        $response->assertStatus(200);
    }

    public function test_moderator_is_denied_access_to_staff_management(): void
    {
        $moderator = User::where('email', 'moderator@storpify.com')->first();

        // Staff management routes are restricted to super_admin only
        $response = $this->actingAs($moderator)->get('/admin/staff');
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }

    public function test_customer_cannot_access_any_admin_page(): void
    {
        $customer = User::where('email', 'customer@storpify.com')->first();

        $response = $this->actingAs($customer)->get('/admin/dashboard');
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }

    public function test_category_scoped_permissions_enforced_for_moderator(): void
    {
        $categoryA = ProductCategory::first();
        $categoryB = ProductCategory::skip(1)->first();

        // Moderator assigned ONLY to Category A
        $moderator = User::where('email', 'moderator@storpify.com')->first();
        $moderator->update([
            'assigned_categories' => [$categoryA->id],
        ]);

        // Attempting to create product in Category B (which is not assigned) should be aborted with 403
        $response = $this->actingAs($moderator)->post('/admin/products', [
            'name' => 'Unauthorized Product',
            'category_id' => $categoryB->id,
            'regular_price' => 500,
            'product_type' => 'digital_download',
        ]);

        $response->assertStatus(403);
    }
}
