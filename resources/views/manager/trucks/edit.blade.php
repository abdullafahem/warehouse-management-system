@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Truck</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('warehouse.trucks.update', $truck) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="chassis_number" class="form-label">Chassis Number</label>
                    <input type="text" class="form-control @error('chassis_number') is-invalid @enderror" 
                           id="chassis_number" name="chassis_number" value="{{ old('chassis_number', $truck->chassis_number) }}" required>
                    @error('chassis_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="license_plate" class="form-label">License Plate</label>
                    <input type="text" class="form-control @error('license_plate') is-invalid @enderror" 
                           id="license_plate" name="license_plate" value="{{ old('license_plate', $truck->license_plate) }}" required min="0">
                    @error('license_plate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="container_volume" class="form-label">Container Volume (m³)</label>
                    <input type="number" step="0.01" class="form-control @error('container_volume') is-invalid @enderror" 
                           id="container_volume" name="container_volume" value="{{ old('container_volume', $truck->container_volume) }}" required min="0">
                    @error('container_volume')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                        <option value="1" {{ old('is_active', $truck->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $truck->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Truck</button>
                    <a href="{{ route('warehouse.trucks') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection