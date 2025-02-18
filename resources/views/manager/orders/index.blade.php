@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Orders</h2>

    <div class="card">
        <div class="card-header">
            <form action="{{ route('warehouse.orders') }}" method="GET" class="row g-3">
                <div class="col-auto">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->name }}" {{ request('status') == $status->name ? 'selected' : '' }}>
                                {{ $status->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->client->name }}</td>
                            <td>
                                <span class="badge text-white p-2 bg-{{ $order->status->getColor() }}">
                                    {{ $order->status->getLabel() }}
                                </span>
                            </td>
                            <td>{{ $order->submitted_date?->format('Y-m-d') ?? 'Not submitted' }}</td>
                            <td>{{ $order->deadline_date->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('warehouse.orders.show', $order) }}" 
                                   class="btn btn-sm btn-primary">View Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection