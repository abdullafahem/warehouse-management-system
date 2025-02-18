@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Inventory Item</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('warehouse.inventory.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="item_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                           id="item_name" name="item_name" value="{{ old('item_name') }}" required>
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                           id="quantity" name="quantity" value="{{ old('quantity') }}" required min="0">
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="unit_price" class="form-label">Unit Price ($)</label>
                    <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" 
                           id="unit_price" name="unit_price" value="{{ old('unit_price') }}" required min="0">
                    @error('unit_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="package_volume" class="form-label">Package Volume (cm³)</label>
                    <input type="number" step="0.01" class="form-control @error('package_volume') is-invalid @enderror" 
                           id="package_volume" name="package_volume" value="{{ old('package_volume') }}" required min="0">
                    @error('package_volume')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create Item</button>
                    <a href="{{ route('warehouse.inventory') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection