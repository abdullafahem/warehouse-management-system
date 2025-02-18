@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h2>Order Details: {{ $order->order_number }}</h2>
        <a href="{{ route('warehouse.orders') }}" class="btn btn-secondary">Back to Orders</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Order Information</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge p-2 text-white bg-{{ $order->status->getColor() }}">
                                {{ $order->status->getLabel() }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Client</dt>
                        <dd class="col-sm-8">{{ $order->client->name }}</dd>

                        <dt class="col-sm-4">Submitted Date</dt>
                        <dd class="col-sm-8">{{ $order->submitted_date?->format('Y-m-d') ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Deadline Date</dt>
                        <dd class="col-sm-8">{{ $order->deadline_date->format('Y-m-d') }}</dd>

                        @if($order->status === 'DECLINED')
                            <dt class="col-sm-4">Decline Reason</dt>
                            <dd class="col-sm-8">{{ $order->decline_reason }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>Order Items</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->inventoryItem->item_name }}</td>
                                    <td>{{ $item->requested_quantity }}</td>
                                    <td>${{ number_format($item->inventoryItem->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->requested_quantity * $item->inventoryItem->unit_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total Order Value:</th>
                                    <th>${{ number_format($order->items->sum(function($item) {
                                        return $item->requested_quantity * $item->inventoryItem->unit_price;
                                    }), 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->status === 'AWAITING_APPROVAL')
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <form action="{{ route('warehouse.orders.approve', $order) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve Order</button>
                        </form>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal">
                            Decline Order
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if($order->status === 'APPROVED' && !$order->delivery)
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Schedule Delivery</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('warehouse.orders.schedule-delivery', $order) }}" method="POST" id="scheduleDeliveryForm">
                        @csrf
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <select name="delivery_date" id="delivery_date" class="form-select" required>
                                <option value="">Select Date</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Available Trucks</label>
                            <div id="availableTrucks">
                                <!-- Trucks will be populated via JavaScript -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Schedule Delivery</button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        @if($order->delivery)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Delivery Information</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Delivery Date</dt>
                        <dd class="col-sm-6">{{ $order->delivery->delivery_date->format('Y-m-d') }}</dd>

                        <dt class="col-sm-6">Assigned Trucks</dt>
                        <dd class="col-sm-6">
                            <ul class="list-unstyled">
                                @foreach($order->delivery->trucks as $truck)
                                    <li>{{ $truck->license_plate }}</li>
                                @endforeach
                            </ul>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('warehouse.orders.decline', $order) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Decline Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="decline_reason" class="form-label">Reason for Declining</label>
                        <textarea name="decline_reason" id="decline_reason" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Decline Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($order->status === 'APPROVED' && !$order->delivery)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryDateSelect = document.getElementById('delivery_date');
    const availableTrucksDiv = document.getElementById('availableTrucks');
    
    // Load available delivery dates
    fetch('{{ route('warehouse.orders.delivery-dates', $order) }}')
        .then(response => response.json())
        .then(data => {
            data.forEach(dateInfo => {
                const option = new Option(dateInfo.date, dateInfo.date);
                deliveryDateSelect.add(option);
            });
        });
    
    // Update available trucks when date is selected
    deliveryDateSelect.addEventListener('change', function() {
        const selectedDate = this.value;
        if (!selectedDate) {
            availableTrucksDiv.innerHTML = '';
            return;
        }
        
        fetch(`{{ route('warehouse.orders.delivery-dates', $order) }}`)
            .then(response => response.json())
            .then(data => {
                const dateInfo = data.find(d => d.date === selectedDate);
                if (dateInfo) {
                    availableTrucksDiv.innerHTML = dateInfo.available_trucks.map(truck => `
                        <div class="form-check">
                            <input type="checkbox" name="truck_ids[]" value="${truck.id}" 
                                   class="form-check-input" id="truck_${truck.id}">
                            <label class="form-check-label" for="truck_${truck.id}">
                                ${truck.license_plate} (Volume: ${truck.container_volume} m³)
                            </label>
                        </div>
                    `).join('');
                }
            });
    });
});
</script>
@endpush
@endif
@endsection