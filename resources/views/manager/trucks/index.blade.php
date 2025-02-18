@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Trucks Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('warehouse.trucks.create') }}" class="btn btn-primary">Add New Truck</a>
        </div>
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Chassis Number</th>
                            <th>License Plate</th>
                            <th>Container Volume</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trucks as $truck)
                        <tr>
                            <td>{{ $truck->id }}</td>
                            <td>{{ $truck->chassis_number }}</td>
                            <td>{{ $truck->license_plate }}</td>
                            <td>{{ $truck->container_volume }} m³</td>
                            <td>
                                <span class="badge text-white p-2 bg-{{ 
                                    $truck->is_active == '1' ? 'success' : 'danger'
                                }}">
                                    {{ $truck->is_active == '1' ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('warehouse.trucks.edit', $truck) }}" 
                                   class="btn btn-sm btn-primary">Edit</a>
                                
                                <form action="{{ route('warehouse.trucks.delete', $truck) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this item?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $trucks->links() }}
        </div>
    </div>
</div>
@endsection