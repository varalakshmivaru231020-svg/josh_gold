<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminOrdersMissingProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_uses_a_default_product_when_the_relation_is_missing(): void
    {
        $order = new Order([
            'product_id' => 999999,
        ]);

        $this->assertSame('Product Removed', $order->product->name);
        $this->assertSame([], $order->product->images);
        $this->assertFalse($order->product->exists);
    }

    public function test_admin_orders_index_view_renders_when_product_is_missing(): void
    {
        $this->actingAs(Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]), 'admin');

        $order = new Order([
            'order_number' => 'ORD-TEST-001',
            'product_id' => 999999,
            'quantity' => 2,
            'total' => 499.00,
            'discount' => 0,
            'customer_name' => 'Test Customer',
            'customer_mobile' => '9999999999',
            'payment_status' => 'completed',
            'order_status' => 'pending',
        ]);
        $order->forceFill([
            'id' => 1,
            'created_at' => now(),
        ]);

        $orders = new LengthAwarePaginator([$order], 1, 10);

        $html = view('admin.orders.index', compact('orders'))->render();

        $this->assertStringContainsString('Product Removed', $html);
        $this->assertStringContainsString('ORD-TEST-001', $html);
    }
}
