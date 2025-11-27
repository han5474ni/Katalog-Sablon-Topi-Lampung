<?php

namespace Tests\Unit\VirtualAccount;

use App\Models\VirtualAccount;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VirtualAccountModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function virtual_account_can_be_created()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $va = VirtualAccount::create([
            'order_id' => $order->id,
            'bank_code' => 'bca',
            'account_number' => '3572123456789',
            'account_name' => 'TOKO ONLINE XYZ',
            'amount' => 500000,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('virtual_accounts', [
            'bank_code' => 'bca',
            'account_number' => '3572123456789',
        ]);
    }

    /** @test */
    public function virtual_account_belongs_to_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $va = VirtualAccount::create([
            'order_id' => $order->id,
            'bank_code' => 'bni',
            'account_number' => '7654321098765',
            'account_name' => 'TEST VA',
            'amount' => 250000,
            'status' => 'active',
        ]);

        $this->assertTrue($va->order->is($order));
    }

    /** @test */
    public function virtual_account_can_track_amount()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $va = VirtualAccount::create([
            'order_id' => $order->id,
            'bank_code' => 'mandiri',
            'account_number' => '1234567890123',
            'account_name' => 'AMOUNT TEST',
            'amount' => 1500000.50,
            'status' => 'active',
        ]);

        $this->assertEquals(1500000.50, $va->fresh()->amount);
    }

    /** @test */
    public function virtual_account_status_can_be_active()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $va = VirtualAccount::create([
            'order_id' => $order->id,
            'bank_code' => 'bca',
            'account_number' => '1111111111111',
            'account_name' => 'ACTIVE VA',
            'amount' => 300000,
            'status' => 'active',
        ]);

        $this->assertEquals('active', $va->status);
    }

    /** @test */
    public function virtual_account_status_can_be_inactive()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $va = VirtualAccount::create([
            'order_id' => $order->id,
            'bank_code' => 'bca',
            'account_number' => '2222222222222',
            'account_name' => 'INACTIVE VA',
            'amount' => 400000,
            'status' => 'inactive',
        ]);

        $this->assertEquals('inactive', $va->status);
    }

    /** @test */
    public function virtual_account_can_be_updated()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $va = VirtualAccount::create([
            'order_id' => $order->id,
            'bank_code' => 'bca',
            'account_number' => '3333333333333',
            'account_name' => 'UPDATE TEST',
            'amount' => 500000,
            'status' => 'active',
        ]);

        $va->update(['status' => 'paid']);
        $this->assertEquals('paid', $va->fresh()->status);
    }

    /** @test */
    public function virtual_account_tracks_created_at()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $va = VirtualAccount::create([
            'order_id' => $order->id,
            'bank_code' => 'bni',
            'account_number' => '9999999999999',
            'account_name' => 'TIMESTAMP TEST',
            'amount' => 600000,
            'status' => 'active',
        ]);

        $this->assertNotNull($va->created_at);
    }

    /** @test */
    public function virtual_account_can_have_multiple_banks()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $banks = ['bca', 'bni', 'mandiri', 'permata', 'cimb'];

        foreach ($banks as $index => $bank) {
            VirtualAccount::create([
                'order_id' => $order->id,
                'bank_code' => $bank,
                'account_number' => '1000000000000' . $index,
                'account_name' => 'BANK ' . strtoupper($bank),
                'amount' => 500000,
                'status' => 'active',
            ]);
        }

        $this->assertEquals(5, VirtualAccount::where('order_id', $order->id)->count());
    }
}
