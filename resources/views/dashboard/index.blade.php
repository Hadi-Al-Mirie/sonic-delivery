@extends('dashboard.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text">{{ $userCount }}</p>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-3">
                <div class="card text-white bg-success mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text">{{ $orderCount }}</p>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-3">
                <div class="card text-white bg-info mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Completed Orders</h5>
                        <p class="card-text">{{ $completedOrderCount }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Stores</h5>
                        <p class="card-text">{{ $storeCount }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <p class="card-text">${{ number_format($totalSales, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
