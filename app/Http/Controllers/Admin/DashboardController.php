<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $orderCount = Order::count();
        $completedOrderCount = Order::where('status', 'completed')->count();
        $storeCount = Store::count();

        $totalSales = Order::where('status', 'completed')->sum('total_price');  

        return view('dashboard.index', compact(
            'userCount',
            'orderCount',
            'completedOrderCount',
            'storeCount',
            'totalSales'
        ));
    }
}
