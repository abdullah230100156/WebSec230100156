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
                <form method="GET" action="{{ route('purchases.index') }}">
                    <input type="text" name="product_name" placeholder="Search by product name">
                    <select name="category">
                        <option value="">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="books">Books</option>
                    </select>
                    <button type="submit">Filter</button>
                    
                </form>
                
                @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->product->name ?? 'Unknown Product' }}</td>
                        <td>{{ $purchase->price }} EGP</td>
                        <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>

                        <td>
                            @if(!$purchase->returned)
                                <form action="{{ route('product.return', $purchase->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Return</button>
                                </form>
                                @else
                                <span class="text-success">Returned</span>
                                <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                            
                        </td>                                                
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
