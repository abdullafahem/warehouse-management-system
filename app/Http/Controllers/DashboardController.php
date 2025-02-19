<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\InventoryItem;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->value == 'WAREHOUSE_MANAGER' || $user->role->value == 'SYSTEM_ADMIN') {
            $totalOrders = Order::count();
            $totalInventoryItems = InventoryItem::count();
            $totalTrucks = Truck::count();
            $totalUsers = User::count();
            $recentOrders = Order::latest()->take(10)->get();

            return view('dashboard', [
                'totalOrders' => $totalOrders,
                'totalInventoryItems' => $totalInventoryItems,
                'totalTrucks' => $totalTrucks,
                'totalUsers' => $totalUsers,
                'recentOrders' => $recentOrders,
            ]);
        } elseif ($user->role->value == 'CLIENT') {
            return redirect()->route('orders.index')->with('error', 'You do not have access to the dashboard.');
        }

        return view('dashboard');
    }
}
