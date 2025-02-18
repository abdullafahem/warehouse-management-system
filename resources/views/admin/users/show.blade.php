@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Details</h2>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">ID</dt>
                <dd class="col-sm-8">{{ $user->id }}</dd>

                <dt class="col-sm-4">Name</dt>
                <dd class="col-sm-8">{{ $user->name }}</dd>

                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">{{ $user->email }}</dd>

                <dt class="col-sm-4">Role</dt>
                <dd class="col-sm-8">{{ $user->role }}</dd>

                <dt class="col-sm-4">Created At</dt>
                <dd class="col-sm-8">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>

                <dt class="col-sm-4">Updated At</dt>
                <dd class="col-sm-8">{{ $user->updated_at->format('Y-m-d H:i:s') }}</dd>
            </dl>
        </div>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to Users</a>
</div>
@endsection