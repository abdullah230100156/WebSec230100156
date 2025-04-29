@extends('layouts.master')

@section('title', 'My Purchases')

@section('content')
    <div class="container my-5">
        <h2 class="text-center text-gold mb-4">My Purchases</h2> <!-- Changed the color to gold -->

        @if($purchases->isEmpty())
            <div class="alert alert-info text-center">You haven't purchased anything yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Purchased At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->product->name ?? 'Unknown Product' }}</td>
                                <td>{{ $purchase->price }} EGP</td>
                                <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>

                                <td>
                                    @if(!$purchase->returned)
                                        <form action="{{ route('product.return', $purchase->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Return</button>
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
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            body {
                background-color: #2c3e50; /* Dark background */
            }

            .container {
                background-color: #34495e;
                border-radius: 10px;
                padding: 20px;
            }

            h2 {
                font-size: 2rem;
                font-weight: 600;
                color: #FFD700; /* Gold color */
            }

            table th, table td {
                text-align: center;
            }

            .btn-warning, .btn-danger {
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .btn-warning:hover, .btn-danger:hover {
                background-color: #f39c12;
                color: white;
            }

            .alert {
                background-color: #1abc9c;
                color: #fff;
            }

            .table-dark {
                background-color: #34495e;
                border: 1px solid #2c3e50;
            }

            .table-bordered td, .table-bordered th {
                border-color: #2c3e50;
            }

            .table-hover tbody tr:hover {
                background-color: #2c3e50;
            }
        </style>
    @endpush
@endsection
