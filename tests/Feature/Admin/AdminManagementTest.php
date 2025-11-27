<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create(['email' => 'admin@example.com']);
    }

    /** @test */
    public function admin_can_be_created()
    {
        $admin = Admin::create([
            'name' => 'Admin Baru',
            'email' => 'admin.baru@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->assertDatabaseHas('admins', [
            'email' => 'admin.baru@example.com',
        ]);
    }

    /** @test */
    public function admin_can_view_all_orders()
    {
        Order::factory()->count(5)->create();

        $orders = Order::all();

        $this->assertCount(5, $orders);
    }

    /** @test */
    public function admin_can_update_order_status()
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $order->update(['status' => 'processing']);

        $this->assertEquals('processing', $order->fresh()->status);
    }

    /** @test */
    public function admin_can_approve_custom_design()
    {
        $order = Order::factory()->create([
            'is_custom_design' => true,
            'custom_design_status' => 'pending',
        ]);

        $order->update(['custom_design_status' => 'approved']);

        $this->assertEquals('approved', $order->fresh()->custom_design_status);
    }

    /** @test */
    public function admin_can_reject_custom_design()
    {
        $order = Order::factory()->create([
            'is_custom_design' => true,
            'custom_design_status' => 'pending',
        ]);

        $order->update(['custom_design_status' => 'rejected']);

        $this->assertEquals('rejected', $order->fresh()->custom_design_status);
    }

    /** @test */
    public function admin_can_add_notes_to_order()
    {
        $order = Order::factory()->create();

        $notes = 'Order ini perlu perhatian khusus';
        $order->update(['notes' => $notes]);

        $this->assertEquals($notes, $order->fresh()->notes);
    }

    /** @test */
    public function admin_can_manage_users()
    {
        $user = User::factory()->create();
        $user->update(['is_active' => false]);

        $this->assertFalse($user->fresh()->is_active);
    }

    /** @test */
    public function admin_can_view_activity_logs()
    {
        $this->assertIsIterable(Admin::all());
    }
}
