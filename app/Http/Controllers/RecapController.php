<?php

namespace App\Http\Controllers;

use App\Models\SummaryTransactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecapController extends Controller
{
    public function getTransactionSummaryCart(Request $request)
    {
        $filters = $request->get('filter', null);
        $filter = $request->get('filterValue', null);
        $filterType = $request->get('filterType', 'monthly');

        // Prepare chart data
        $chartData = [
            'labels' => [],
            'data' => [
                [
                    'name' => "Total Penjualan",
                    'total_sales' => [],
                ],
                [
                    'name' => "Total Pembelian",
                    'total_sales' => [],
                ]
            ],
        ];

        // Process based on filter type
        switch ($filterType) {
            case 'date': {
                // Handle daily chart data
                $year = explode('-', $filter)[0];
                $month = explode('-', $filter)[1];
                $day = explode('-', $filter)[2];
    
                $specificDate = Carbon::createFromDate($year, $month, $day);
    
                // Add hour labels (00:00 to 23:00)
                for ($i = 0; $i < 24; $i++) {
                    array_push($chartData['labels'], str_pad($i, 2, '0', STR_PAD_LEFT) . ":00");
                }
    
                // Fetch chart data for the specific day, grouped by hour
                $summaryData = SummaryTransactions::whereYear('open_at', $year)
                    ->whereMonth('open_at', $month)
                    ->whereDay('open_at', $day)
                    ->groupBy(DB::raw("HOUR(open_at)"))
                    ->select(DB::raw("HOUR(open_at) as hour"), DB::raw("SUM(total_sales) as total_sale"), DB::raw("SUM(total_sales) as total_sales"))
                    ->get();
    
                // Map data to the chart for each hour
                for ($i = 0; $i < 24; $i++) {
                    $total_sale = 0;
                    foreach ($summaryData as $item) {
                        if ($item->hour == $i) {
                            $total_sale = $item->total_sale;
                            break;
                        }
                    }
                    array_push($chartData['data'][0]['total_sales'], $total_sale);
                }
            } break;
            case 'yearly': {
                for ($i = 1; $i <= 12; $i++) {
                    array_push($chartData['labels'], Carbon::create()->month($i)->locale('id')->monthName);  // Month names in Bahasa Indonesia
                }

                $summaryData = SummaryTransactions::whereYear('open_at', $filter)
                    ->groupBy(DB::raw("DATE_FORMAT(open_at, '%Y-%m')"))
                    ->select(DB::raw("DATE_FORMAT(open_at, '%Y-%m') as time"), DB::raw("SUM(total_income) as total_sale"), DB::raw("SUM(total_sales) as total_sales"))
                    ->get();

                for ($i = 1; $i <= 12; $i++) {
                    $total_sale = 0;
                    foreach ($summaryData as $item) {
                        if (Carbon::createFromFormat('Y-m', $item->time)->month == $i) {
                            $total_sale = $item->total_sale;
                            break;
                        }
                    }
                    array_push($chartData['data'][0]['total_sales'], $total_sale);
                }
            } break;

            case 'monthly': {
                $year = explode('-', $filter)[0];
                $month = explode('-', $filter)[1];

                $daysInMonth = Carbon::create($year, $month)->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    array_push($chartData['labels'], $i);
                }

                $summaryData = SummaryTransactions::whereYear('open_at', $year)
                    ->whereMonth('open_at', $month)
                    ->groupBy(DB::raw("DATE(open_at)"))
                    ->select(DB::raw("DATE(open_at) as time"), DB::raw("SUM(total_income) as total_sale"), DB::raw("SUM(total_sales) as total_sales"))
                    ->get();

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $total_sale = 0;
                    foreach ($summaryData as $item) {
                        if (Carbon::parse($item->time)->day == $i) {
                            $total_sale = $item->total_sale;
                            break;
                        }
                    }
                    array_push($chartData['data'][0]['total_sales'], $total_sale);
                }
            } break;

            case 'weekly': {
                for ($i = 0; $i < 7; $i++) {
                    array_push($chartData['labels'], Carbon::now()->startOfWeek()->addDays($i)->locale('id')->dayName);  // Day names in Bahasa Indonesia
                }

                $summaryData = SummaryTransactions::whereYear('open_at', $filter)
                    ->groupBy(DB::raw("WEEK(open_at)"))
                    ->select(DB::raw("WEEK(open_at) as week"), DB::raw("SUM(total_income) as total_sale"), DB::raw("SUM(total_sales) as total_sales"))
                    ->get();

                for ($i = 0; $i < 7; $i++) {
                    $total_sale = 0;
                    foreach ($summaryData as $item) {
                        if (Carbon::parse($item->week)->dayOfWeek == $i) {
                            $total_sale = $item->total_sale;
                            break;
                        }
                    }
                    array_push($chartData['data'][0]['total_sales'], $total_sale);
                }
            } break;
        }

        return response()->json([
            'message' => 'Success',
            'data' => $chartData,
        ]);
    }
}
