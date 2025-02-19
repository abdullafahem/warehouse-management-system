<?php

namespace App\Http\Controllers\Client;

use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // This method is to get all orders
    public function index(Request $request)
    {
        $query = Order::where('client_id', Auth::id());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view(
            'client.orders.index',
            [
                'orders' => $orders,
                'statuses' => Statuses::cases()
            ]
        );
    }

    // This method redirects to the create order page
    public function create()
    {
        // Fetch all inventory items
        $inventoryItems = InventoryItem::all();

        return view('client.orders.create', [
            'inventoryItems' => $inventoryItems
        ]);
    }

    // This method redirects to the edit order page
    public function edit(Order $order)
    {
        // Fetch all inventory items
        $inventoryItems = InventoryItem::all();

        // Fetch the order items
        $orderItems = $order->items;

        return view('client.orders.edit', [
            'order' => $order,
            'inventoryItems' => $inventoryItems,
            'orderItems' => $orderItems
        ]);
    }

    // This method is to store an order
    public function store(Request $request)
    {
        $request->validate([
            'deadline_date' => 'required|date|after:today',
            'items' => 'required|array',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        // Create the order and its items in a transaction (all or nothing)
        DB::transaction(function () use ($request) {
            $order = Order::create([
                'client_id' => Auth::id(),
                'deadline_date' => $request->deadline_date,
                'status' => 'CREATED'
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'inventory_item_id' => $item['inventory_item_id'],
                    'requested_quantity' => $item['quantity']
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    // This method is to update an order
    public function update(Request $request, Order $order)
    {
        if (!$order->canUpdate()) {
            return back()->with('error', 'Order cannot be updated in its current status');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        // Update the order and its items in a transaction (all or nothing)
        DB::transaction(function () use ($request, $order) {
            // Delete existing items
            $order->items()->delete();

            // Add new items
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'inventory_item_id' => $item['inventory_item_id'],
                    'requested_quantity' => $item['quantity']
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    // This method is to submit an order
    public function submit(Order $order)
    {
        if (!$order->canSubmit()) {
            return back()->with('error', 'Order cannot be submitted in its current status');
        }

        $order->update([
            'status' => 'AWAITING_APPROVAL',
            'submitted_date' => now()
        ]);

        return redirect()->route('orders.index')->with('success', 'Order submitted successfully');
    }

    // This method is to cancel an order
    public function cancel(Order $order)
    {
        if (!$order->canCancel()) {
            return back()->with('error', 'Order cannot be canceled in its current status');
        }

        $order->update(['status' => 'CANCELED']);

        return redirect()->route('orders.index')->with('success', 'Order canceled successfully');
    }
}
