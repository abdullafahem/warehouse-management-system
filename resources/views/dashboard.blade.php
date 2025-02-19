@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    @if(auth()->user()->role->value == 'WAREHOUSE_MANAGER' || auth()->user()->role->value == 'SYSTEM_ADMIN')
        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Orders</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Inventory Items</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalInventoryItems }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Trucks</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTrucks }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Status</th>
                                        <th>Submitted Date</th>
                                        <th>Deadline Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>
                                                <span class="badge text-white p-2 bg-{{ $order->status->getColor() }}">
                                                    {{ $order->status->getLabel() }}
                                                </span>
                                            </td>
                                            <td>{{ $order->submitted_date?->format('Y-m-d') ?? 'Not submitted' }}</td>
                                            <td>{{ $order->deadline_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Links</h6>
                    </div>
                    <div class="card-body">
                        <a target="_blank" href="{{ route('warehouse.orders') }}" class="btn btn-primary">Manage Orders</a>
                        <a target="_blank" href="{{ route('warehouse.inventory') }}" class="btn btn-success">Manage Inventory</a>
                        <a target="_blank" href="{{ route('warehouse.trucks') }}" class="btn btn-info">Manage Trucks</a>
                        @if(auth()->user()->role->value == 'SYSTEM_ADMIN')
                            <a target="_blank" href="{{ route('users.index') }}" class="btn btn-warning">Manage Users</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection