@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>My Orders</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">Create New Order</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3">
                <div class="col-auto">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->name }}" {{ request('status') == $status->name ? 'selected' : '' }}>
                                {{ $status->value }}
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
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Deadline Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>
                                <span class="badge text-white p-2 bg-{{ 
                                    $order->status === 'APPROVED' ? 'success' : 
                                    ($order->status === 'DECLINED' ? 'danger' : 
                                    ($order->status === 'AWAITING_APPROVAL' ? 'warning' : 
                                    ($order->status === 'UNDER_DELIVERY' ? 'info' : 
                                    ($order->status === 'FULFILLED' ? 'primary' : 
                                    ($order->status === 'CANCELED' ? 'dark' : 'secondary'))))) 
                                }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ $order->submitted_date ? $order->submitted_date->format('Y-m-d') : 'Not submitted' }}</td>
                            <td>{{ $order->deadline_date->format('Y-m-d') }}</td>
                            <td>
                                @if($order->canUpdate())
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-primary">Edit</a>
                                @endif
                                @if($order->canSubmit())
                                    <form action="{{ route('orders.submit', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </form>
                                @endif
                                @if($order->canCancel())
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
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