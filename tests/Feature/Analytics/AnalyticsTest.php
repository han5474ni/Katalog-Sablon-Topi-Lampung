<?php

namespace Tests\Feature\Analytics;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    /** @test */
    public function total_revenue_can_be_calculated()
    {
        Order::create([
            'user_id' => $this->user->id,
            'total_price' => 500000,
            'status' => 'completed',
        ]);

        Order::create([
            'user_id' => $this->user->id,
            'total_price' => 300000,
            'status' => 'completed',
        ]);

        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        $this->assertEquals(800000, $totalRevenue);
    }

    /** @test */
    public function order_count_by_status()
    {
        Order::factory()->count(3)->create(['status' => 'pending']);
        Order::factory()->count(5)->create(['status' => 'completed']);
        Order::factory()->count(2)->create(['status' => 'cancelled']);

        $pendingCount = Order::where('status', 'pending')->count();
        $completedCount = Order::where('status', 'completed')->count();
        $cancelledCount = Order::where('status', 'cancelled')->count();

        $this->assertEquals(3, $pendingCount);
        $this->assertEquals(5, $completedCount);
        $this->assertEquals(2, $cancelledCount);
    }

    /** @test */
    public function top_products_can_be_identified()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        Order::factory()->count(5)->create(['product_id' => $product1->id]);
        Order::factory()->count(3)->create(['product_id' => $product2->id]);

        $topProduct = Order::selectRaw('product_id, count(*) as count')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->first();

        $this->assertEquals($product1->id, $topProduct->product_id);
    }

    /** @test */
    public function average_order_value_calculated()
    {
        Order::create(['user_id' => $this->user->id, 'total_price' => 100000]);
        Order::create(['user_id' => $this->user->id, 'total_price' => 200000]);
        Order::create(['user_id' => $this->user->id, 'total_price' => 300000]);

        $averageOrder = Order::avg('total_price');

        $this->assertEquals(200000, $averageOrder);
    }

    /** @test */
    public function customer_lifetime_value_tracked()
    {
        Order::create(['user_id' => $this->user->id, 'total_price' => 500000, 'status' => 'completed']);
        Order::create(['user_id' => $this->user->id, 'total_price' => 300000, 'status' => 'completed']);

        $lifetimeValue = Order::where('user_id', $this->user->id)
            ->where('status', 'completed')
            ->sum('total_price');

        $this->assertEquals(800000, $lifetimeValue);
    }

    /** @test */
    public function order_trend_by_date()
    {
        Order::factory()->count(5)->create(['created_at' => now()]);
        Order::factory()->count(3)->create(['created_at' => now()->subDay()]);

        $todayOrders = Order::whereDate('created_at', today())->count();
        $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();

        $this->assertEquals(5, $todayOrders);
        $this->assertEquals(3, $yesterdayOrders);
    }

    /** @test */
    public function repeat_customer_count()
    {
        $user2 = User::factory()->create();

        Order::create(['user_id' => $this->user->id, 'total_price' => 100000]);
        Order::create(['user_id' => $this->user->id, 'total_price' => 200000]);

        Order::create(['user_id' => $user2->id, 'total_price' => 150000]);

        $repeatCustomers = User::whereHas('orders', function($q) {
            $q->havingRaw('count(*) > 1');
        })->count();

        $this->assertEquals(1, $repeatCustomers);
    }

    /** @test */
    public function payment_status_distribution()
    {
        Order::factory()->count(4)->create(['payment_status' => 'paid']);
        Order::factory()->count(2)->create(['payment_status' => 'pending']);
        Order::factory()->count(1)->create(['payment_status' => 'failed']);

        $paidCount = Order::where('payment_status', 'paid')->count();
        $pendingPaymentCount = Order::where('payment_status', 'pending')->count();
        $failedCount = Order::where('payment_status', 'failed')->count();

        $this->assertEquals(4, $paidCount);
        $this->assertEquals(2, $pendingPaymentCount);
        $this->assertEquals(1, $failedCount);
    }

    /** @test */
    public function custom_design_order_statistics()
    {
        Order::factory()->count(3)->create(['is_custom_design' => true]);
        Order::factory()->count(7)->create(['is_custom_design' => false]);

        $customDesignCount = Order::where('is_custom_design', true)->count();
        $regularOrderCount = Order::where('is_custom_design', false)->count();

        $this->assertEquals(3, $customDesignCount);
        $this->assertEquals(7, $regularOrderCount);
    }
}
