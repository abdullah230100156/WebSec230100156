@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">

      <form action="{{ route('do_login') }}" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
          @foreach($errors->all() as $error)
          <div class="alert alert-danger">
            <strong>Error!</strong> {{ $error }}
          </div>
          @endforeach
        </div>

        <div class="form-group mb-2">
          <label for="email" class="form-label">Email:</label>
          <input type="email" name="email" class="form-control" placeholder="email" required>
        </div>

        <div class="form-group mb-2">
          <label for="password" class="form-label">Password:</label>
          <input type="password" name="password" class="form-control" placeholder="password" required>
        </div>

        <div class="form-group mb-2">
          <button type="submit" class="btn btn-primary">Login</button>
          <a href="{{ route('redirectToFacebook') }}" class="btn btn-success">Login With Facebook</a>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
