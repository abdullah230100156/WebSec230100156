@extends('layouts.master')
@section('title', 'Users')
@section('content')

<div class="row mt-2 align-items-center">
    <div class="col-md-6">
        <h1>Users</h1>
    </div>
    <div class="col-md-6 text-end">
        @can('create_users')
        <a href="{{ route('users_create') }}" class="btn btn-success">Add Employee</a>
        @endcan

        @can('add_credit')
        <a class="btn btn-success" href="{{ route('credit.form') }}">Add Credit</a>
        @endcan
    </div>
</div>

<form class="mt-3">
    <div class="row g-2">
        <div class="col-sm-3">
            <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col-sm-auto">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col-sm-auto">
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
</form>

<div class="card mt-4">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Credit</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->credit}}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{$role->name}}</span>
                        @endforeach
                    </td>
                    <td>
                        @can('edit_users')
                        <a class="btn btn-sm btn-primary" href='{{ route('users_edit', [$user->id]) }}'>Edit</a>
                        @endcan

                        @can('admin_users')
                        <a class="btn btn-sm btn-secondary" href='{{ route('edit_password', [$user->id]) }}'>Change Password</a>
                        @endcan

                        @can('delete_users')
                        <!-- Delete Button as Form -->
                        <form action="{{ route('users_delete', [$user->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection
