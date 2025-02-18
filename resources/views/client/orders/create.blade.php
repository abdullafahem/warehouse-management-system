@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Order</h2>
    
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label for="deadline_date" class="form-label">Deadline Date</label>
                    <input type="date" class="form-control @error('deadline_date') is-invalid @enderror" 
                           id="deadline_date" name="deadline_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('deadline_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <h4>Order Items</h4>
                    <div id="items-container">
                        <div class="row mb-2 item-row">
                            <div class="col-md-6">
                                <select name="items[0][inventory_item_id]" class="form-select" required>
                                    <option value="">Select Item</option>
                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->unit_price }}">
                                            {{ $item->item_name }} - ${{ number_format($item->unit_price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="items[0][quantity]" class="form-control" 
                                       placeholder="Quantity" required min="1">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-item">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-item">Add Another Item</button>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Create Order</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = 1;

document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const template = container.querySelector('.item-row').cloneNode(true);
    
    // Update indices
    template.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace('[0]', `[${itemIndex}]`);
        input.value = '';
    });
    
    // Add remove button functionality
    template.querySelector('.remove-item').addEventListener('click', function() {
        this.closest('.item-row').remove();
    });
    
    container.appendChild(template);
    itemIndex++;
});

// Add remove functionality to initial row
document.querySelector('.remove-item').addEventListener('click', function() {
    if (document.querySelectorAll('.item-row').length > 1) {
        this.closest('.item-row').remove();
    }
});
</script>
@endpush
@endsection