<?php

namespace App\Http\Controllers;

use App\Models\SummaryTransactions;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecapController extends Controller
{
    public function getTransactionSummaryCart(Request $request)
{
    $filterType = $request->get('filterType', 'monthly');
    $filterValue = $request->get('filterValue', now()->year);  // Defaults to current year

    [$year, $month] = explode('-', $filterValue);

    // Bahasa Indonesia day and month names
    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $chartData = [
        'labels' => [],
        'data' => [
            [
                'name' => "Penjualan",
                'total_sales' => [],
            ],
            [
                'name' => "Hpp",
                'total_sales' => [],
            ]
        ]
    ];

    switch ($filterType) {
        case 'yearly':
            for ($i = 1; $i <= 12; $i++) {
                $chartData['labels'][] = $months[$i - 1];

                $summary = SummaryTransactions::whereYear('open_at', $year)
                    ->whereMonth('open_at', $i)
                    ->selectRaw('SUM(total_sales) as total_sales, SUM(total_bp) as pengeluaran')
                    ->first();

                $chartData['data'][0]['total_sales'][] = $summary->total_sales ?? 0;
                $chartData['data'][1]['total_sales'][] = $summary->pengeluaran ?? 0;
            }
            break;

        case 'monthly':
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $chartData['labels'][] = $i;

                $summary = SummaryTransactions::whereYear('open_at', $year)
                    ->whereMonth('open_at', $month)
                    ->whereDay('open_at', $i)
                    ->selectRaw('SUM(total_sales) as total_sales, SUM(total_bp) as pengeluaran')
                    ->first();

                $chartData['data'][0]['total_sales'][] = $summary->total_sales ?? 0;
                $chartData['data'][1]['total_sales'][] = $summary->pengeluaran ?? 0;
            }
            break;

        // case 'weekly':
            for ($i = 0; $i < 7; $i++) {
                $chartData['labels'][] = $days[$i];

                $summary = SummaryTransactions::whereYear('open_at', $year)
                    ->whereMonth('open_at', $month)
                    ->where(DB::raw('WEEK(open_at)'), $week)
                    ->where(DB::raw('DAYOFWEEK(open_at)'), $i + 1)
                    ->selectRaw('SUM(total_sales) as total_sales, SUM(total_bp) as pengeluaran')
                    ->first();

                $chartData['data'][0]['total_sales'][] = $summary->total_sales ?? 0;
                $chartData['data'][1]['total_sales'][] = $summary->pengeluaran ?? 0;
            }
            break;
    }

    return response()->json([
        'message' => 'Success',
        'data' => $chartData,
    ]);
}

public function getPopularCategoryCart(Request $request)
{
    $filterType = $request->get('filterType', 'monthly');
    $filterValue = $request->get('filterValue', now()->year);  // Defaults to current year

    $transactionDetails = TransactionDetail::query()
        ->join('products', 'transaction_details.product_id', '=', 'products.id')
        ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
        ->select(
            'product_categories.name as category_name',
            DB::raw('SUM(transaction_details.quantity) as total_quantity')
        )
        ->groupBy('product_categories.name');

    if ($filterType === 'monthly') {
        // Parse month and year from filterValue
        [$year, $month] = explode('-', $filterValue);
        
        $transactionDetails->whereYear('transaction_details.created_at', $year)
            ->whereMonth('transaction_details.created_at', $month);
    } else {
        $transactionDetails->whereYear('transaction_details.created_at', $filterValue);
    }

    $result = $transactionDetails->get();

    // Prepare data for pie chart
    $chartData = [
        'labels' => $result->pluck('category_name'),
        'data' => $result->pluck('total_quantity')
    ];

    return response()->json([
        'message' => 'Success',
        'data' => $chartData,
    ]);
}

}
