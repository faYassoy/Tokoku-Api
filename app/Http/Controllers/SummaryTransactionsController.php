<?php

namespace App\Http\Controllers;

use App\Models\SummaryTransactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SummaryTransactionsController extends Controller
{
    //
    public function index(Request $request)
    {
        $sortDirection = $request->get('sortDirection', 'DESC');
        $sortby = $request->get('sortBy', 'created_at');
        $paginate = $request->get('paginate', 10);
        $filter = $request->get('filter', null);

        $columnAliases = [
            'open_at' => 'summary_sales.open_at',
        ];


        $model = new SummaryTransactions();

        $query = SummaryTransactions::query();

        if ($request->get('search') != '') {
            $query = $this->search($request->get('search'), $model, $query);
        } else {
            $query = $query;
        }

        if ($filter) {
            $filters = json_decode($filter);
            foreach ($filters as $column => $value) {
                $query = $this->filter($this->remark_column($column, $columnAliases), $value, $query, $model);
            }
        }

        $query = $query->orderBy($this->remark_column($sortby, $columnAliases), $sortDirection)
            ->select($model->selectable)
            ->paginate($paginate);

        if (empty($query->items())) {
            return response([
                'message' => 'Empty data',
                'data' => [],
            ], 200);
        }

        return response([
            'message' => 'Success',
            'data' => $query->all(),
            'total_row' => $query->total()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        /**
         * * Summary sale Creation
         */
        $model = new SummaryTransactions();
        $model->open_by = Auth::user()->id;
        $model->open_at = Carbon::now();

        try {
            if (Auth::user()->role == 'admin') {
                $model->save();
            } else {
                DB::rollback();
                return response([
                    'message' => "unautorize to open store!",
                ], 403);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response([
                'message' => "Error: failed to insert new summary sale",
                'th' => $th,
            ], 500);
        }

        DB::commit();

        $model = SummaryTransactions::where('id', $model->id)->first();

        return response([
            'message' => 'Success',
            'data' => $model,
        ]);
    }

    public function check_open(Request $request)
    {

        $model = SummaryTransactions::where('is_active', 1)
            ->first();

        if (!$model) {
            return response([
                'message' => 'Data not found'
            ], 404);
        }

        // Check if the found model is active
        if ($model->is_active != 1) {
            return response([
                'message' => 'Data not active'
            ], 404);
        }

        return response([
            'message' => 'Success',
            'data' => $model,
        ]);
    }

    public function close(Request $request)
    {
        // Ensure that the open_at value is in the correct format
        $openAt = Carbon::parse($request->open_at)->format('Y-m-d H:i:s');

        $model = SummaryTransactions::where('is_active', 1)
            ->first();

        if (!$model) {
            return response([
                'message' => 'Data not found'
            ], 404);
        }

        $model->is_active = 0;
        // $model->close_by = Auth::user()->id;
        $model->close_at = Carbon::now();

        try {
            DB::beginTransaction(); // Begin transaction here
            $model->save();
            DB::commit(); // Commit transaction here
        } catch (\Throwable $th) {
            DB::rollback();
            return response([
                'message' => "Error: failed to close active summary sale",
                'th' => $th
            ], 500);
        }

        return response([
            'message' => 'Success',
            'data' => $model,
        ]);
    }
    public function destroy($id)
    {
        $model = SummaryTransactions::findOrFail($id);

        DB::beginTransaction();
        try {
            $model->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                "message" => "Error: server side having problem!",
            ], 500);
        }

        DB::commit();

        return response([
            "message" => "success",
            "data" => $model
        ], 200);
    }
    public function getTransactionSummaryChart(Request $request)
    {
        $filter = $request->get('filter', null);
        $filterType = $request->get('filterType', 'monthly');

        $chartData = [
            'labels' => [],
            'data' => [
                [
                    'name' => "Penjualan",
                    'total_sales' => [],
                ]
            ]
        ];

        switch ($filterType) {
            case 'yearly':
                $year = $filter ?: now()->year;

                // Labels: Indonesian months
                for ($i = 1; $i <= 12; $i++) {
                    array_push($chartData['labels'], Carbon::createFromFormat('m', $i)->translatedFormat('F')); // 'F' formats the full month name
                }

                // Query for yearly data grouped by month
                $transactions = SummaryTransactions::whereYear('open_at', $year)
                    ->selectRaw('MONTH(open_at) as month, SUM(total_income) as total_income')
                    ->groupBy('month')
                    ->get();

                for ($i = 1; $i <= 12; $i++) {
                    $income = $transactions->firstWhere('month', $i)->total_income ?? 0;
                    array_push($chartData['data'][0]['total_sales'], $income);
                }
                break;

            case 'monthly':
                $yearMonth = $filter ?: now()->format('Y-m');
                [$year, $month] = explode('-', $yearMonth);

                // Get days of the month
                $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    array_push($chartData['labels'], $i);
                }

                // Query for monthly data grouped by day
                $transactions = SummaryTransactions::whereYear('open_at', $year)
                    ->whereMonth('open_at', $month)
                    ->selectRaw('DAY(open_at) as day, SUM(total_income) as total_income')
                    ->groupBy('day')
                    ->get();

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $income = $transactions->firstWhere('day', $i)->total_income ?? 0;
                    array_push($chartData['data'][0]['total_sales'], $income);
                }
                break;

            case 'weekly':
                $yearMonth = $filter ?: now()->format('Y-m-W');
                [$year, $month, $week] = explode('-', $yearMonth);

                // Labels: Indonesian days of the week
                for ($i = 0; $i < 7; $i++) {
                    array_push($chartData['labels'], Carbon::now()->startOfWeek()->addDays($i)->translatedFormat('l')); // 'l' formats full day name
                }

                // Query for weekly data
                $startOfWeek = Carbon::createFromDate($year, $month)->startOfWeek()->addWeeks($week);
                $endOfWeek = $startOfWeek->copy()->endOfWeek();

                $transactions = SummaryTransactions::whereBetween('open_at', [$startOfWeek, $endOfWeek])
                    ->selectRaw('DAYOFWEEK(open_at) as day_of_week, SUM(total_income) as total_income')
                    ->groupBy('day_of_week')
                    ->get();

                for ($i = 1; $i <= 7; $i++) {
                    $income = $transactions->firstWhere('day_of_week', $i)->total_income ?? 0;
                    array_push($chartData['data'][0]['total_sales'], $income);
                }
                break;
        }

        return response()->json([
            'message' => 'Success',
            'data' => $chartData,
        ]);
    }
}
