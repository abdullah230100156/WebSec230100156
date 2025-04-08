@extends('layouts.master')

@section('title', 'My Purchases')

@section('content')
    <h2>My Purchases</h2>

    @if($purchases->isEmpty())
        <p>You haven't purchased anything yet.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Purchased At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->product->name ?? 'Unknown Product' }}</td>
                        <td>{{ $purchase->price }} EGP</td>
                        <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
