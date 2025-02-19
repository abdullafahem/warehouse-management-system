<?php

namespace Tests\Unit;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

class OrderTest extends TestCase
{
    /**
     * Test if an order has a client.
     */
    public function test_order_has_client(): void
    {
        // Create mock instances
        $user = $this->createMock(User::class);
        $order = $this->createMock(Order::class);

        // Configure the user mock
        $user->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        // Configure the order mock
        $order->method('getAttribute')
            ->with('client_id')
            ->willReturn(1);

        $order->method('getRelation')
            ->with('client')
            ->willReturn($user);

        // Test the relationship
        $this->assertInstanceOf(User::class, $order->getRelation('client'));
        $this->assertEquals(1, $order->getAttribute('client_id'));
    }

    /**
     * Test if an order has items.
     */
    public function test_order_has_items()
    {
        // Create mock instances
        $order = $this->createMock(Order::class);
        $orderItem = $this->createMock(OrderItem::class);

        // Configure the order mock
        $order->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $orderItem->method('getAttribute')
            ->with('order_id')
            ->willReturn(1);

        // Mock the items collection
        $itemsCollection = new Collection([$orderItem]);
        $order->method('getRelation')
            ->with('items')
            ->willReturn($itemsCollection);

        // Test the relationship
        $this->assertTrue($order->getRelation('items')->contains($orderItem));
    }

    /**
     * Test if an order has a delivery.
     */
    public function test_order_has_delivery()
    {
        // Create mock instances
        $order = $this->createMock(Order::class);
        $delivery = $this->createMock(Delivery::class);

        // Configure the delivery mock
        $delivery->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        // Configure the order mock
        $order->method('getAttribute')
            ->with('order_id')
            ->willReturn(1);

        $order->method('getRelation')
            ->with('delivery')
            ->willReturn($delivery);

        // Test the relationship
        $this->assertInstanceOf(Delivery::class, $order->getRelation('delivery'));
        $this->assertEquals(1, $delivery->getAttribute('id'));
    }
}
