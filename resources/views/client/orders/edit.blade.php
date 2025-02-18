@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Order {{ $order->order_number }}</h2>
    
    <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Deadline Date</label>
                    <input type="date" class="form-control" value="{{ $order->deadline_date->format('Y-m-d') }}" readonly>
                </div>

                <div class="mb-3">
                    <h4>Order Items</h4>
                    <div id="items-container">
                        @foreach($order->items as $index => $orderItem)
                        <div class="row mb-2 item-row">
                            <div class="col-md-6">
                                <select name="items[{{ $index }}][inventory_item_id]" class="form-select" required>
                                    <option value="">Select Item</option>
                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}" 
                                                {{ $orderItem->inventory_item_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->item_name }} - ${{ number_format($item->unit_price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="items[{{ $index }}][quantity]" 
                                       value="{{ $orderItem->requested_quantity }}"
                                       class="form-control" placeholder="Quantity" required min="1">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-item">Remove</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-item">Add Another Item</button>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update Order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = {{ count($order->items) }};

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const template = container.querySelector('.item-row').cloneNode(true);
    
    // Update indices and clear values
    template.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace(/\[\d+\]/, `[${itemIndex}]`);
        input.value = '';
        if (input.tagName === 'SELECT') {
            input.selectedIndex = 0;
        }
    });
    
    // Add remove button functionality
    template.querySelector('.remove-item').addEventListener('click', function() {
        this.closest('.item-row').remove();
    });
    
    container.appendChild(template);
    itemIndex++;
});

// Add remove functionality to existing rows
document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', function() {
        if (document.querySelectorAll('.item-row').length > 1) {
            this.closest('.item-row').remove();
        }
    });
});
</script>
@endpush
@endsection