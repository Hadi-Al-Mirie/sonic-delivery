@extends('dashboard.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container mt-5">
    <h1 class="h3 mb-4">Order Details</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Order #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <p><strong>User:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>

            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="status" class="form-label">Update Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>

    <h4>Order Items</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
