@extends('layouts.master')
@section('title', 'Add Employee')
@section('content')

<div class="container mt-4">
  <h2>Add New Employee</h2>

  <form method="POST" action="{{ route('users_store') }}">
    @csrf

    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Confirm Password</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Role</label>
      <select name="role" class="form-select" required>
        @foreach(Spatie\Permission\Models\Role::all() as $role)
          <option value="{{ $role->name }}">{{ $role->name }}</option>
        @endforeach
      </select>
    </div>

    <button type="submit" class="btn btn-success">Create</button>
    <a href="{{ route('users') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>

@endsection
