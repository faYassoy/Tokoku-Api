<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function counterData()
    {
        $products = Product::count('*');
        $categories = ProductCategory::count('*');
        $customers = Customer::count('*');
        $users = User::count('*');
        $transactions = Transaction::whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->count('*');
        
        return response([
            'message' => 'Success',
            'data' => [
                'products' => $products,
                'categories' => $categories,
                'customers' => $customers,
                'users' => $users,
                'transactions' => $transactions,
            ]
        ]);
    }
}
