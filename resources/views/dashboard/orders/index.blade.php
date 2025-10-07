@extends('dashboard.layouts.app')

@section('title', 'Orders List')

@section('content')
<div class="container mt-5">
    <h1 class="h3 mb-4">Orders List</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form method="GET" class="mb-4">
        <div class="d-flex">
            <input type="text" name="order_id" class="form-control me-2" placeholder="Search by Order ID" value="{{ request()->get('order_id') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="@switch($order->status)
                        @case('pending') bg-warning @break
                        @case('completed') bg-success @break
                        @case('canceled') bg-danger @break
                        @endswitch">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                        <td>
                            <span class="badge 
                                @switch($order->status)
                                    @case('pending') bg-warning @break
                                    @case('completed') bg-success @break
                                    @case('cancelled') bg-danger @break
                                @endswitch">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
