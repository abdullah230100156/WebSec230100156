
@extends('layouts.master')
@section('title', 'My Purchases')

@section('content')
    <h1>My Purchases</h1>

    @if($purchases->isEmpty())
        <p>You haven’t purchased anything yet.</p>
    @else
        @foreach($purchases as $purchase)
            <div class="card my-2">
                <div class="card-body">
                    <h5>{{ $purchase->product->name }}</h5>
                    <p>Price: ${{ $purchase->price }}</p>
                    <p>Purchased on: {{ $purchase->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        @endforeach
    @endif
@endsection


