<?php

namespace App\Http\Controllers\Manager;

use App\Enums\Statuses;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Truck;
use App\Models\InventoryItem;
use App\Http\Requests\Warehouse\Orders\{
    DeclineOrderRequest,
    ScheduleDeliveryRequest
};
use App\Http\Requests\Warehouse\Inventory\{
    StoreInventoryRequest,
    UpdateInventoryRequest
};
use App\Http\Requests\Warehouse\Trucks\{
    StoreTruckRequest,
    UpdateTruckRequest
};
use App\Services\WarehouseManagerService;

class WarehouseManagerController extends Controller
{
    protected $service;

    public function __construct(WarehouseManagerService $service)
    {
        $this->service = $service;
    }

    // This method is to get all orders
    public function orders(Request $request)
    {
        $orders = $this->service->getOrders($request->status);

        return view('manager.orders.index', [
            'orders' => $orders,
            'statuses' => Statuses::cases()
        ]);
    }

    // This method is to get order details
    public function orderDetails(Order $order)
    {
        $order = $this->service->getOrderDetails($order);
        return view('manager.orders.show', [
            'order' => $order
        ]);
    }

    // This method is to approve an order
    public function approveOrder(Order $order)
    {
        if (!$this->service->approveOrder($order)) {
            return back()->with('error', 'Order cannot be approved in its current status');
        }

        return back()->with('success', 'Order approved successfully');
    }

    // This method is to decline an order
    public function declineOrder(DeclineOrderRequest $request, Order $order)
    {
        if (!$this->service->declineOrder($order, $request->validated()['decline_reason'])) {
            return back()->with('error', 'Order cannot be declined in its current status');
        }

        return back()->with('success', 'Order declined successfully');
    }

    // This method is to get inventory items
    public function inventory()
    {
        $items = $this->service->getInventoryItems();
        return view('manager.inventory.index', [
            'items' => $items
        ]);
    }

    // This method redirects to create inventory page
    public function createInventory()
    {
        return view('manager.inventory.create');
    }

    // This method is to store inventory item
    public function storeInventoryItem(StoreInventoryRequest $request)
    {
        $this->service->storeInventoryItem($request->validated());
        return redirect()->route('warehouse.inventory')->with('success', 'Item added successfully');
    }

    // This method redirects to edit inventory page
    public function editInventory(InventoryItem $item)
    {
        return view('manager.inventory.edit', [
            'item' => $item
        ]);
    }

    // This method is to update inventory item
    public function updateInventoryItem(UpdateInventoryRequest $request, InventoryItem $item)
    {
        $this->service->updateInventoryItem($item, $request->validated());
        return redirect()->route('warehouse.inventory')->with('success', 'Item updated successfully');
    }

    // This method is to delete inventory item
    public function deleteInventoryItem(InventoryItem $item)
    {
        $this->service->deleteInventoryItem($item);
        return redirect()->route('warehouse.inventory')->with('success', 'Item deleted successfully');
    }

    // This method is to get all trucks
    public function trucks()
    {
        $trucks = $this->service->getTrucks();
        return view('manager.trucks.index', [
            'trucks' => $trucks
        ]);
    }

    // This method redirects to create truck page
    public function createTruck()
    {
        return view('manager.trucks.create');
    }

    // This method is to store truck
    public function storeTruck(StoreTruckRequest $request)
    {
        $this->service->storeTruck($request->validated());
        return redirect()->route('warehouse.trucks')->with('success', 'Truck added successfully');
    }

    // This method redirects to edit truck page
    public function editTruck(Truck $truck)
    {
        return view('manager.trucks.edit', [
            'truck' => $truck
        ]);
    }

    // This method is to update truck
    public function updateTruck(UpdateTruckRequest $request, Truck $truck)
    {
        $this->service->updateTruck($truck, $request->validated());
        return redirect()->route('warehouse.trucks')->with('success', 'Truck updated successfully');
    }

    // This method is to delete truck
    public function deleteTruck(Truck $truck)
    {
        $this->service->deleteTruck($truck);
        return redirect()->route('warehouse.trucks')->with('success', 'Truck deleted successfully');
    }

    // This method is to get available delivery dates
    public function getDeliveryDates(Order $order)
    {
        $availableDates = $this->service->getDeliveryDates($order);

        return response()->json($availableDates);
    }

    // This method is to schedule delivery
    public function scheduleDelivery(ScheduleDeliveryRequest $request, Order $order)
    {
        if (!$this->service->scheduleDelivery($order, $request->validated()['delivery_date'], $request->validated()['truck_ids'])) {
            return back()->with('error', 'Failed to schedule delivery');
        }

        return redirect()->route('warehouse.orders')->with('success', 'Delivery scheduled successfully');
    }
}
