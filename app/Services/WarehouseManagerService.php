<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Truck;
use App\Models\Delivery;
use App\Models\InventoryItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseManagerService
{
    // This method is to get all orders
    public function getOrders($status = null)
    {
        Log::info('Fetching orders', ['status' => $status]);
        $query = Order::with(['client', 'items.inventoryItem'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('submitted_date', 'desc');
        return $query->paginate(10);
    }

    // This method is to get order details
    public function getOrderDetails(Order $order)
    {
        Log::info('Fetching order details', ['order_id' => $order->id]);
        // Eager load relationships
        $order->load(['client', 'items.inventoryItem', 'delivery.trucks']);
        return $order;
    }

    // This method is to approve an order
    public function approveOrder(Order $order)
    {
        Log::info('Approving order', ['order_id' => $order->id]);
        if (!$order->status->getLabel() === 'Awaiting Approval') {
            Log::warning('Order cannot be approved in its current status', ['order_id' => $order->id, 'status' => $order->status]);
            return false;
        }

        $order->update(['status' => 'APPROVED']);
        return true;
    }

    // This method is to decline an order
    public function declineOrder(Order $order, $declineReason)
    {
        Log::info('Declining order', ['order_id' => $order->id, 'reason' => $declineReason]);
        if (!$order->status->getLabel() === 'Awaiting Approval') {
            Log::warning('Order cannot be declined in its current status', ['order_id' => $order->id, 'status' => $order->status]);
            return false;
        }

        $order->update([
            'status' => 'DECLINED',
            'decline_reason' => $declineReason
        ]);

        return true;
    }

    // This method is get Inventory Items
    public function getInventoryItems()
    {
        Log::info('Fetching inventory items');
        return InventoryItem::paginate(10);
    }

    // This method is to store Inventory Item
    public function storeInventoryItem($data)
    {
        Log::info('Storing new inventory item', ['data' => $data]);
        InventoryItem::create($data);
    }

    // This method is to update Inventory Item
    public function updateInventoryItem(InventoryItem $item, $data)
    {
        Log::info('Updating inventory item', ['item_id' => $item->id, 'data' => $data]);
        $item->update($data);
    }

    // This method is to delete Inventory Item
    public function deleteInventoryItem(InventoryItem $item)
    {
        Log::info('Deleting inventory item', ['item_id' => $item->id]);
        $item->delete();
    }

    // This method is to get all trucks
    public function getTrucks()
    {
        Log::info('Fetching trucks');
        return Truck::paginate(10);
    }

    // This method is to store Truck
    public function storeTruck($data)
    {
        Log::info('Storing new truck', ['data' => $data]);
        Truck::create($data);
    }

    // This method is to update Truck
    public function updateTruck(Truck $truck, $data)
    {
        Log::info('Updating truck', ['truck_id' => $truck->id, 'data' => $data]);
        $truck->update($data);
    }

    // This method is to delete Truck
    public function deleteTruck(Truck $truck)
    {
        Log::info('Deleting truck', ['truck_id' => $truck->id]);
        $truck->delete();
    }

    // This method is to create a range available dates for delivery
    public function availableDates(): \DatePeriod
    {
        // Get system setting for max days (default 30)
        $maxDays = (int) env('WAREHOUSE_MAX_DELIVERY_DAYS', 30);

        $today = Carbon::today();
        $endDate = (new \DateTime())->add(new \DateInterval("P{$maxDays}D"));
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($today, $interval, $endDate);

        return $dateRange;
    }

    // This method is to get delivery dates
    public function getDeliveryDates(Order $order)
    {
        Log::info('Fetching available delivery dates', ['order_id' => $order->id]);
        $dateRange = $this->availableDates();
        $availableDates = [];

        // Calculate total volume needed for the order
        $totalVolume = $order->items->sum(function ($item) {
            return $item->inventoryItem->package_volume * $item->requested_quantity;
        });

        // Get all trucks with sufficient capacity
        $suitableTrucks = Truck::where('container_volume', '>=', $totalVolume)
            ->where('is_active', true)
            ->get();

        // Check each date
        foreach ($dateRange as $date) {
            // Skip weekends
            if ($date->format('N') >= 6) {
                continue;
            }

            // Check truck availability for this date
            $availableTrucksForDate = $suitableTrucks->filter(function ($truck) use ($date) {
                return $truck->isAvailableOnDate($date);
            });

            if ($availableTrucksForDate->isNotEmpty()) {
                $availableDates[] = [
                    'date' => $date->format('Y-m-d'),
                    'available_trucks' => $availableTrucksForDate
                ];
            }
        }

        return $availableDates;
    }

    public function scheduleDelivery(Order $order, $deliveryDate, $truckIds)
    {
        Log::info('Scheduling delivery', ['order_id' => $order->id, 'delivery_date' => $deliveryDate, 'truck_ids' => $truckIds]);
        // Validate weekend
        $deliveryDate = Carbon::parse($deliveryDate);
        if ($deliveryDate->isWeekend()) {
            Log::warning('Cannot schedule delivery on a weekend', ['delivery_date' => $deliveryDate]);
            return false;
        }

        // Check if date is within the range
        $dateRange = $this->availableDates();
        $isDateInRange = false;
        foreach ($dateRange as $date) {
            if ($date->format('Y-m-d') === $deliveryDate->format('Y-m-d')) {
                $isDateInRange = true;
                break;
            }
        }

        if (!$isDateInRange) {
            Log::warning('Delivery date is out of range', ['delivery_date' => $deliveryDate]);
            return false;
        }

        // Check truck availability
        foreach ($truckIds as $truckId) {
            $truck = Truck::find($truckId);
            if (!$truck->isAvailableOnDate($deliveryDate)) {
                Log::warning('Truck is not available on the delivery date', ['truck_id' => $truckId, 'delivery_date' => $deliveryDate]);
                return false;
            }
        }

        // Create delivery and update order status in a transaction (all or nothing)
        DB::transaction(function () use ($order, $deliveryDate, $truckIds) {
            // Create delivery
            $delivery = Delivery::create([
                'order_id' => $order->id,
                'delivery_date' => $deliveryDate
            ]);

            // Attach trucks
            $delivery->trucks()->attach($truckIds);

            // Update order status
            $order->update([
                'status' => 'UNDER_DELIVERY',
                'delivery_id' => $delivery->id
            ]);

            // Update inventory quantities
            foreach ($order->items as $item) {
                $inventoryItem = $item->inventoryItem;
                $inventoryItem->quantity -= $item->requested_quantity;
                $inventoryItem->save();
            }
        });

        return true;
    }
}
