<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Manager\InventoryItemController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\WarehouseManagerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:CLIENT'])->group(function () {
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{order}/submit', [OrderController::class, 'submit'])->name('orders.submit');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

Route::middleware(['auth', 'role:WAREHOUSE_MANAGER,SYSTEM_ADMIN'])->group(function () {
    // Orders
    Route::get('/warehouse/orders', [WarehouseManagerController::class, 'orders'])
        ->name('warehouse.orders');
    Route::get('/warehouse/orders/{order}', [WarehouseManagerController::class, 'orderDetails'])
        ->name('warehouse.orders.show');
    Route::post('/warehouse/orders/{order}/approve', [WarehouseManagerController::class, 'approveOrder'])
        ->name('warehouse.orders.approve');
    Route::post('/warehouse/orders/{order}/decline', [WarehouseManagerController::class, 'declineOrder'])
        ->name('warehouse.orders.decline');

    // Inventory
    Route::get('/warehouse/inventory', [WarehouseManagerController::class, 'inventory'])
        ->name('warehouse.inventory');
    Route::get('/warehouse/inventory/create', [WarehouseManagerController::class, 'createInventory'])
        ->name('warehouse.inventory.create');
    Route::post('/warehouse/inventory', [WarehouseManagerController::class, 'storeInventoryItem'])
        ->name('warehouse.inventory.store');
    Route::get('/warehouse/inventory/{item}/edit', [WarehouseManagerController::class, 'editInventory'])
        ->name('warehouse.inventory.edit');
    Route::put('/warehouse/inventory/{item}', [WarehouseManagerController::class, 'updateInventoryItem'])
        ->name('warehouse.inventory.update');
    Route::delete('/warehouse/inventory/{item}', [WarehouseManagerController::class, 'deleteInventoryItem'])
        ->name('warehouse.inventory.delete');

    // Trucks
    Route::get('/warehouse/trucks', [WarehouseManagerController::class, 'trucks'])
        ->name('warehouse.trucks');
    Route::get('/warehouse/trucks/create', [WarehouseManagerController::class, 'createTruck'])
        ->name('warehouse.trucks.create');
    Route::post('/warehouse/trucks', [WarehouseManagerController::class, 'storeTruck'])
        ->name('warehouse.trucks.store');
    Route::get('/warehouse/trucks/{truck}/edit', [WarehouseManagerController::class, 'editTruck'])
        ->name('warehouse.trucks.edit');
    Route::put('/warehouse/trucks/{truck}', [WarehouseManagerController::class, 'updateTruck'])
        ->name('warehouse.trucks.update');
    Route::delete('/warehouse/trucks/{truck}', [WarehouseManagerController::class, 'deleteTruck'])
        ->name('warehouse.trucks.delete');

    // Delivery
    Route::get('/warehouse/orders/{order}/delivery-dates', [WarehouseManagerController::class, 'getDeliveryDates'])
        ->name('warehouse.orders.delivery-dates');
    Route::get('/warehouse/orders/{order}/available-trucks', [WarehouseManagerController::class, 'getAvailableTrucks'])->name('warehouse.orders.available-trucks');
    Route::post('/warehouse/orders/{order}/schedule-delivery', [WarehouseManagerController::class, 'scheduleDelivery'])
        ->name('warehouse.orders.schedule-delivery');
});

Route::middleware(['auth', 'role:SYSTEM_ADMIN'])->group(
    function () {
        Route::resource('admin/users', UserController::class);
    }
);

require __DIR__ . '/auth.php';
