@extends('layouts.master')

@section('title', 'Add Credit')

@section('content')
<div class="card mt-4">
    <div class="card-body">
        <h3>Add Credit to Customer</h3>
        <form method="POST" action="{{ route('credit.store') }}">
            @csrf

            <div class="mb-3">
                <label for="user_id" class="form-label">Select Customer</label>
                <select class="form-select" name="user_id" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" name="amount" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Credit</button>
        </form>
    </div>
</div>
@endsection
