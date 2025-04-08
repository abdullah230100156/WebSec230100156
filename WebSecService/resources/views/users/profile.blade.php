@extends('layouts.master')
@section('title', 'User Profile')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-person-circle me-2"></i> User Profile</h3>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <strong>Name:</strong>
                    <span>{{ $user->name }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <strong>Email:</strong>
                    <span>{{ $user->email }}</span>
                </li>
                <li class="list-group-item">
                    <strong>Roles:</strong>
                    <div class="mt-2">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </li>
                <li class="list-group-item">
                    <strong>Permissions:</strong>
                    <div class="mt-2">
                        @foreach($permissions as $permission)
                            <span class="badge bg-success me-1">{{ $permission->display_name }}</span>
                        @endforeach
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Account Credit:</strong>
                    <span class="fs-5 text-success">${{ number_format($user->credit, 2) }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
            <div>
                @if(auth()->check())
                    <small class="text-muted">Your Credit: ${{ number_format(auth()->user()->credit, 2) }}</small>
                @endif
            </div>
            <div class="d-flex gap-2">
                @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                    <a href="{{ route('edit_password', $user->id) }}" class="btn btn-outline-primary btn-sm">
                        Change Password
                    </a>
                @endif

                @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                    <a href="{{ route('users_edit', $user->id) }}" class="btn btn-outline-success btn-sm">
                        Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
